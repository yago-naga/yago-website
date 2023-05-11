Blazegraph
==========

To install Blazegraph, run the following command in the blazegraph directory:
```
wget https://github.com/blazegraph/database/releases/download/BLAZEGRAPH_2_1_6_RC/blazegraph.jar
```

To enable the Blazegraph service:
```
sudo ln -s /home/yago/website/blazegraph/blazegraph.service /etc/systemd/system/blazegraph.service
sudo systemctl enable blazegraph
sudo systemctl start blazegraph
```
To load a new YAGO version:
```
sudo systemctl stop blazegraph
nohup ./load-zip.sh ZIP-FILE &  # This will take a few hours
sudo systemctl start blazegraph
~/restart-nginx.sh
```
