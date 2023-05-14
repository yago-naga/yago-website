# call: nohupnsudon/bin/bash load-zip.sh
echo "----------------- Loading YAGO into Blazegraph ---------------------"
date +"Current time: %F %T"
systemctl stop blazegraph
mkdir /data/temp
rm /data/temp/*.*
echo Unzipping
unzip /data/public/yago4.5/yago-4.5.0.zip -d /data/temp
rm /data/temp/yago-beyond-wikipedia.ttl
rm /data/yago.jnl
for f in /data/temp/*.ttl
do
  echo Loading $f
  java -cp blazegraph.jar com.bigdata.rdf.store.DataLoader -namespace kb  RWStore.properties $f
done
#echo "Start blazegraph with:        sudo systemctl start blazegraph"
#echo "Restart server with:          ~/restart-nginx.sh"
#echo "Remove temporary data with:   rm /data/temp/*"
systemctl start blazegraph
~/restart-nginx.sh
date +"Current time: %F %T"
echo "----------------- Done loading YAGO into Blazegraph ---------------------"
