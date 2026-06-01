#!/usr/bin/env bash
#
# deploy-qlever.sh - Deploy a new YAGO version to the QLever server
#
# Copies TTL data to /data/qlever, builds the index, restarts the server.
# Brief downtime occurs during the restart.
#
# Usage:
#   ./deploy-qlever.sh /path/to/ttl/files
#   ./deploy-qlever.sh --skip-index
#
# Run this on the YAGO server.

echo "Deploying YAGO on Qlever..."

set -euo pipefail

INDEX_DIR="/data/qlever"
NGINX_CACHE="/etc/nginx/cache"
MIN_TRIPLE_COUNT=1000000

die() { 
  echo ""
  echo "  Error: $*" >&2; 
  echo "failed"
  exit 1; 
}

# Parse args
SKIP_INDEX=false
DATA_DIR=""
CLEANUP_TTL=false

echo -n "  Checking arguments..."
case "${1:-}" in
    --skip-index) SKIP_INDEX=true ;;
    -h|--help)
        echo "done"
		echo "Usage: $0 /path/to/ttl/files [--cleanup-ttl]"
        echo "       $0 --skip-index"
        exit 0 ;;
    "") die "Usage: $0 /path/to/ttl/files or $0 --skip-index" ;;
    *) DATA_DIR="$1" ;;
esac
[[ "${2:-}" == "--cleanup-ttl" ]] && CLEANUP_TTL=true

# Prerequisites
command -v qlever >/dev/null 2>&1 || die "qlever CLI not found"
[[ -f "$INDEX_DIR/Qleverfile" ]] || die "No Qleverfile in $INDEX_DIR"
echo "done"

# Index
if [[ "$SKIP_INDEX" == false ]]; then
    echo -n "  Preparing index..."
    [[ -d "$DATA_DIR" ]] || die "Directory not found: $DATA_DIR"
    TTL_COUNT=$(find "$DATA_DIR" -maxdepth 1 -name '*.ttl' | wc -l)
    [[ "$TTL_COUNT" -gt 0 ]] || die "No .ttl files found in $DATA_DIR"

    if [[ "$(realpath "$DATA_DIR")" != "$(realpath "$INDEX_DIR")" ]]; then
        echo -n "    Copying $TTL_COUNT TTL file(s) to $INDEX_DIR..."
        cp "$DATA_DIR"/*.ttl "$INDEX_DIR/"
		echo "done"
    fi

    echo "done"	
    echo -n "  Building index (this may take a long time)..."
    BEFORE_INDEX=$(date +%s)
    (cd "$INDEX_DIR" && qlever index --overwrite-existing)

    # qlever CLI may return 0 even on failure, so verify the index is new
    [[ -f "$INDEX_DIR/yago.index.spo" ]] || die "Index build failed - no index file produced"
    INDEX_MTIME=$(stat -c %Y "$INDEX_DIR/yago.index.spo")
    [[ "$INDEX_MTIME" -ge "$BEFORE_INDEX" ]] || die "Index build failed - index file was not updated (check qlever output for errors)"
    echo "done"

    if [[ "$CLEANUP_TTL" == true ]]; then
        echo -n "  Cleaning up TTL files..."
        rm -f "$INDEX_DIR"/*.ttl
		echo "done"
    fi
else
    [[ -f "$INDEX_DIR/yago.index.spo" ]] || die "No existing index found"
    echo "  Skipping indexing"
fi

# Restart
echo -n "  Restarting QLever server..."
cd "$INDEX_DIR"
qlever stop 2>/dev/null || echo "    No running server to stop"
qlever start
echo "done"

# Health check
echo -n "  Checking server..."
PORT=$(grep -oP '^\s*PORT\s*=\s*\K\d+' "$INDEX_DIR/Qleverfile" | head -1)
ENDPOINT="http://localhost:${PORT:-9004}"

for i in $(seq 1 36); do
    CODE=$(curl -s -o /dev/null -w "%{http_code}" "$ENDPOINT/?query=SELECT+*+WHERE+%7B+%3Fs+%3Fp+%3Fo+%7D+LIMIT+1" 2>/dev/null || true)
    [[ "$CODE" == "200" ]] && break
    sleep 5
done
[[ "$CODE" == "200" ]] || die "Server not responding after 3 minutes"
echo "done"

# Triple count
echo -n "  Counting triples..."
COUNT=$(curl -s "$ENDPOINT/?query=SELECT+(COUNT(*)+AS+?c)+WHERE+%7B+%3Fs+%3Fp+%3Fo+%7D" 2>/dev/null \
    | python3 -c "import sys,json; print(json.load(sys.stdin)['results']['bindings'][0]['c']['value'])" 2>/dev/null || echo "0")
echo "done"
echo "  Triple count: $COUNT"
[[ "$COUNT" -lt "$MIN_TRIPLE_COUNT" ]] && echo "  Warning: triple count is below expected minimum ($MIN_TRIPLE_COUNT)"

# Clear nginx cache
sudo -n rm -rf "${NGINX_CACHE:?}"/* 2>/dev/null || echo "  Warning: Could not clear nginx cache (may need sudo). Run: sudo rm -rf ${NGINX_CACHE}/*"

echo "done ($(date -Iseconds))"
