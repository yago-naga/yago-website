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

To (re)load data into Blazegraph, first edit the last line of `dataloader.xml` to target the version of YAGO 4 you want to load.

Then, remove the previous version of YAGO by executing:
```
sudo systemctl stop blazegraph
rm yago.jnl
sudo systemctl start blazegraph
```

And load the new version using:
```
curl -X POST --data-binary @dataloader.xml --header 'Content-Type:application/xml' http://localhost:9999/blazegraph/dataloader
```

This takes a few hours. When it returns the loading should be done.