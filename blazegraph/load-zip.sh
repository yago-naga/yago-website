echo "Stop blazegraph with:        sudo systemctl stop blazegraph"
mkdir /data/temp
echo Unzipping $1
#unzip $1 -d /data/temp
rm /data/yago.jnl
for f in /data/temp/*.ttl
do
  echo Loading $f
  java -cp blazegraph.jar com.bigdata.rdf.store.DataLoader -namespace kb  RWStore.properties $f
done
echo "Start blazegraph with:        sudo systemctl start blazegraph"
echo "Restart server with:          ~/restart-nginx.sh"
echo "Remove temporary data with:   rm /data/temp/*"
