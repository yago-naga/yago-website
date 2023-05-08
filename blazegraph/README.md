Blazegraph
==========

To install Blazegraph, run the following command in the blazegraph directory:
```
wget https://github.com/blazegraph/database/releases/download/BLAZEGRAPH_2_1_6_RC/blazegraph.jar
```

Then, enable the Blazegraph service using:
```
sudo ln -s /home/yago/website/blazegraph/blazegraph.service /etc/systemd/system/blazegraph.service
sudo systemctl enable blazegraph
sudo systemctl start blazegraph
```
Then, remove the previous version of YAGO by executing:
```
sudo systemctl stop blazegraph
rm yago.jnl
sudo systemctl start blazegraph
```
Load the new data using:
```
./load-file FILE```

This takes a few hours. When it returns the loading should be done.
