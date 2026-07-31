#!/usr/bin/env bash
#
# deploy-qlever.sh - Deploy a new YAGO version to the QLever server
#
# Do not run this with nohup!

echo "Deploying YAGO on Qlever..."
echo ""
echo "  *** Do not run this with nohup, as it will fail! Run in a terminal window! ***"
echo ""
echo "  Stopping Qlever..."
docker stop qlever.server.yago
echo "  done"

echo "  Deleting old index..."
rm -f /data/qlever/yago.* # dont forget the dot, you do not want to delete the TTL files
echo "  done"

echo "  Building index..."
cd /data/qlever/
qlever index --overwrite-existing
echo "  done"

echo "  Restarting Qlever..."
docker start qlever.server.yago
echo "  done"

echo "done"

echo ""
echo "*** Now restart the server ***"
