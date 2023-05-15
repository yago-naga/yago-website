# Make sure that the ZIP file mentioned below is the one you want to load
# Execute: sudo nohup ./load-zip.sh
# Type sudo password
# Type CTRL+Z
# Execute: bg
echo "----------------- Loading YAGO into Blazegraph ---------------------"
date +"Current time: %F %T"
systemctl stop blazegraph
mkdir /data/temp
rm /data/temp/*.*
echo Unzipping
#################### YAGO ZIP FILE GOES IN LINE BELOW
unzip /data/public/yago4.5/yago-4.5.0.zip -d /data/temp
rm /data/temp/yago-beyond-wikipedia.ttl
rm /data/yago.jnl
for f in /data/temp/*.ttl
do
  echo Loading $f
  java -cp blazegraph.jar com.bigdata.rdf.store.DataLoader -namespace kb  RWStore.properties $f
done
chown yago:yago /data/yago.jnl
systemctl start blazegraph
~/restart-nginx.sh
date +"Current time: %F %T"
echo "----------------- Done loading YAGO into Blazegraph ---------------------"
