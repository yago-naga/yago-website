#!/usr/bin/env python3
"""
Generate the excluded_facts.db SQLite database from YAGO build pipeline logs.

The YAGO build pipeline (steps 02-04) produces log files recording facts that
were excluded during construction, in RDF-star TSV format:

    <<  subject  predicate  object  >>  ys:excludedFor  "reason"  .

This script parses those logs and produces a SQLite database that the website's
api/excluded_facts.php endpoint can query.

Usage:
    python3 generate-excluded-facts-db.py /path/to/yago-data [--output excluded_facts.db]

The yago-data directory should contain (or have subdirectories containing):
  - Log files from build steps 02, 03, and 04
  - 04-yago-ids.tsv (Wikidata-to-YAGO ID mapping, used to translate subjects)
"""

import argparse
import glob
import os
import re
import sqlite3
import sys

BATCH_SIZE = 10000


#################################################################
#             Helper methods
#################################################################


# Matches: << subject predicate object >> ys:excludedFor "reason" .
# Fields are tab-separated. Subject/predicate/object can contain spaces (literals).
META_FACT_RE = re.compile(
    r'^<<\t'
    r'([^\t]+)\t'      # subject
    r'([^\t]+)\t'      # predicate
    r'(.+?)\t'         # object (greedy enough for literals with spaces)
    r'>>\t'
    r'ys:excludedFor\t'
    r'"(.+?)"\t'        # reason (quoted)
    r'\.$'
)

PREFIX_RE = re.compile(r'^@prefix\s+(\S+):\s+<([^>]+)>\s*\.\s*$')


def parse_prefixes(lines):
    """Parse @prefix declarations and return a prefix -> URI map."""
    prefixes = {}
    for line in lines:
        m = PREFIX_RE.match(line)
        if m:
            prefixes[m.group(1)] = m.group(2)
    return prefixes


def expand_prefixed(name, prefixes):
    """Expand a prefixed name (e.g., yago:Elvis) to a full URI.

    Returns the original string if it's already a full URI, a literal,
    or the prefix is unknown.
    """
    if name.startswith('<') or name.startswith('"'):
        return name
    colon = name.find(':')
    if colon == -1:
        return name
    prefix = name[:colon]
    local = name[colon + 1:]
    if prefix in prefixes:
        return prefixes[prefix] + local
    return name

#################################################################
#             Preparation methods
#################################################################


def load_id_mapping(data_dir):
    """Load the Wikidata→YAGO ID mapping from 04-yago-ids.tsv.

    The file contains lines like:
        wd:Q42\towl:sameAs\tyago:Douglas_Adams\t. #WIKI
    Returns a dict mapping expanded Wikidata URIs to expanded YAGO URIs.
    """
    print(f"  Loading YAGO ids...", end='', flush=True)
    patterns = [
        os.path.join(data_dir, '**', '04-yago-ids.tsv'),
        os.path.join(data_dir, '04-yago-ids.tsv'),
    ]
    ids_file = None
    for pattern in patterns:
        matches = glob.glob(pattern, recursive=True)
        if matches:
            ids_file = matches[0]
            break

    if not ids_file:
        print("failed\n  Warning: 04-yago-ids.tsv not found, subjects will not be mapped to YAGO URIs", file=sys.stderr)
        return {}

    mapping = {}
    prefixes = {}
    bytes_read=0
    file_size = os.path.getsize(ids_file)
    numDots=0
    
    with open(ids_file, 'r', encoding='utf-8', errors='replace') as f:
        for line in f:
            bytes_read += len(line)
            while numDots<bytes_read*40/file_size:
                print('.', end='', flush=True)
                numDots+=1

            line = line.rstrip('\n')
            if line.startswith('@prefix'):
                m = PREFIX_RE.match(line)
                if m:
                    prefixes[m.group(1)] = m.group(2)
                continue
            # Format: wd:Q42\towl:sameAs\tyago:Douglas_Adams\t. #WIKI
            parts = line.split('\t')
            if len(parts) >= 3 and 'sameAs' in parts[1]:
                wd_uri = expand_prefixed(parts[0], prefixes)
                yago_uri = expand_prefixed(parts[2], prefixes)
                mapping[wd_uri] = yago_uri
    print(" done")                
    print(f"  INFO: Loaded {len(mapping):,} ID mappings")    
    return mapping


def find_log_files(data_dir):
    """Find all relevant log files from build steps 02, 03, 04."""
    print("  Finding log files...")
    patterns = [
        os.path.join(data_dir, '**', '02*.log'),
        os.path.join(data_dir, '**', '03*.log'),
        os.path.join(data_dir, '**', '04*.log'),
        os.path.join(data_dir, '02*.log'),
        os.path.join(data_dir, '03*.log'),
        os.path.join(data_dir, '04*.log'),
    ]
    found = set()
    for pattern in patterns:
        found.update(glob.glob(pattern, recursive=True))
    if found:
        print(f"    INFO: Found {len(found)} log files\n  done")
    else:
        print(f"  failed (no log files)")
    return sorted(found)


def derive_stage(filepath):
    """Derive a stage name from the log filename."""
    basename = os.path.basename(filepath)
    stem = os.path.splitext(basename)[0]
    return stem


#################################################################
#             Main methods
#################################################################


def parse_log_file(filepath):
    """Parse a single log file, yielding (subject, predicate, object, reason, stage) tuples."""
    stage = derive_stage(filepath)

    print(f"    Parsing log file {stage}...", flush=True, end='')
    prefixes = {}   
    parsed = 0
    skipped = 0
    bytes_read = 0
    file_size = os.path.getsize(filepath)
    numDots=0
    with open(filepath, 'rb') as f:
        for raw_line in f:
            bytes_read += len(raw_line)
            while numDots<bytes_read*40/file_size:
                print('.', end='', flush=True)
                numDots+=1
            line = raw_line.decode('utf-8', errors='replace').rstrip('\n')
            if line.startswith('@prefix'):
                m = PREFIX_RE.match(line.rstrip('\n'))
                if m:
                    prefixes[m.group(1)] = m.group(2)
                continue
                
            if not line:
                continue

            m = META_FACT_RE.match(line)
            if m:
                subject = expand_prefixed(m.group(1), prefixes)
                predicate = m.group(2)
                obj = m.group(3)
                reason = m.group(4)
                parsed += 1
                yield (subject, predicate, obj, reason, stage)
            else:
                skipped += 1
    print(" done")
    if skipped > 0:
        print(f"    Warning: {skipped} non-matching lines in {filepath}", file=sys.stderr)
    print(f"    INFO: Parsed {parsed} excluded facts from {os.path.basename(filepath)}")


def main():

    ########  Check arguments
    
    print("Generating database of excluded YAGO facts...")
    print("  Checking arguments...", end="", flush=True)
    parser = argparse.ArgumentParser(
        description='Generate excluded_facts.db from YAGO build pipeline logs.'
    )
    parser.add_argument(
        'data_dir',
        help='Path to YAGO build output directory containing log files'
    )
    parser.add_argument(
        '--output', '-o',
        default='excluded_facts.db',
        help='Output SQLite database path (default: excluded_facts.db)'
    )
    args = parser.parse_args()

    if not os.path.isdir(args.data_dir):
        print(f"\n    Error: {args.data_dir} is not a directory", file=sys.stderr)
        sys.exit(1)    
    if os.path.exists(args.output):
        os.remove(args.output)        
    print("done")
    
    log_files = find_log_files(args.data_dir)
    if not log_files:
        sys.exit(1)

    ########  Load IDs, bootstrap database
    
    # Load Wikidata→YAGO ID mapping so subjects use YAGO URIs
    id_mapping = load_id_mapping(args.data_dir)

    if os.path.exists(args.output):
        os.remove(args.output)
    
    print("  Connecting to database...", end='', flush=True)
    db = sqlite3.connect(args.output)
    print("done")
    
    print("  Creating database table...", end='', flush=True)
    db.execute('''CREATE TABLE excluded_facts (
        subject TEXT,
        predicate TEXT,
        object TEXT,
        reason TEXT,
        stage TEXT
    )''')
    print("done")
    
    ######### Load facts
    
    totalFacts = 0
    print("  Loading log files...")
    for log_file in log_files:
        stage = derive_stage(log_file)
        
        count = 0
        batch = []
        numDots = 0

        for row in parse_log_file(log_file):
            # Map subject from Wikidata URI to YAGO URI if possible
            subject, predicate, obj, reason, stage_name = row
            subject = id_mapping.get(subject, subject)
            row = (subject, predicate, obj, reason, stage_name)
            batch.append(row)
            count += 1            
            if len(batch) >= BATCH_SIZE:
                db.executemany('INSERT INTO excluded_facts VALUES (?,?,?,?,?)', batch)
                batch = []

        if batch:
            db.executemany('INSERT INTO excluded_facts VALUES (?,?,?,?,?)', batch)
        db.commit()
        totalFacts += count
    print(f"    INFO: Loaded {totalFacts} facts in total")
    print("  done")
    
    print(f"  Creating index on subject column...", end='', flush=True)
    db.execute('CREATE INDEX idx_subject ON excluded_facts(subject)')
    db.commit()
    db.close()
    print("done")   

if __name__ == '__main__':
    main()
