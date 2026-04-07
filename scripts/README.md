# YAGO Deployment Scripts

Scripts for deploying and managing the YAGO QLever backend.

## Overview

The QLever SPARQL server runs in a Podman container on the YAGO server (`ssh yago`), managed by the `qlever` CLI tool via a Qleverfile.

| Component | Details |
|---|---|
| Index directory | `/data/qlever` |
| Qleverfile | `/data/qlever/Qleverfile` |
| Container image | `docker.io/adfreiburg/qlever:latest` |
| nginx upstream | `qlever_backend` → `localhost:9004` |
| PHP endpoint | `http://localhost/sparql/qlever` (routes through nginx) |

## Scripts

### deploy-qlever.sh

Deploys a new YAGO version: copies TTL data, builds the QLever index, restarts the server. Brief downtime during the restart.

```bash
# Full deployment: index new TTL data and restart
./deploy-qlever.sh --data-dir /path/to/new-ttl-files
```

**What it does:**
1. Copies TTL files to the index directory
2. Runs `qlever index` to build the index
3. Runs `qlever stop` then `qlever start` (brief downtime)
4. Health checks (liveness, triple count)
5. Clears the nginx cache

### generate-excluded-facts-db.py

Generates the `excluded_facts.db` SQLite database from YAGO build pipeline log files.

```bash
# Generate from YAGO build output
python3 scripts/generate-excluded-facts-db.py /path/to/yago-data --output data/excluded_facts.db
```

**Input:** Directory containing log files from build steps 02, 03, and 04.

**Output:** SQLite database with schema matching `api_excluded_facts.php`.
