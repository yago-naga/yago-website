<h1>SPARQL</h1>
<p>
    YAGO can be queried by a SPARQL API.
    The SPARQL endpoint URI is <code><?php echo config('sparql_endpoint'); ?></code>.
    There is a 1 minute timeout to ensure a responsive SPARQL endpoint for everyone.
    You can also fire a SPARQL query directly in the field below. If you plan to launch several queries, please <a href=downloads>download</a> YAGO instead.<br>
	<span style="font-size: 80%; color: gray;">By using this service, you grant us (the <a href="http://dig.telecom-paristech.fr/">DIG team at Télécom Paris</a>) to use the anonymized queries for any purpose, including but not limitited to the right to publish the queries. You can contact us at <i>nameOfTheKnowledgeBase</i>@fabian.suchanek.name if you want to exercise any right about this data you have under GDPR. You can avoid this issue altogether by downloading YAGO and querying it locally without using our API.</span>
</p>

<div id="yasqe"></div>
<div id="yasr"></div>
<link rel="stylesheet" href="//tools-static.wmflabs.org/cdnjs/ajax/libs/yasqe/2.11.22/yasqe.min.css"/>
<link rel="stylesheet" href="//tools-static.wmflabs.org/cdnjs/ajax/libs/yasr/2.12.19/yasr.min.css"/>
<script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/yasr/2.12.19/yasr.bundled.min.js"></script>
<script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/yasqe/2.11.22/yasqe.bundled.min.js"></script>
<script>
    var yasqe = YASQE(document.getElementById("yasqe"), {
        sparql: {
            showQueryButton: true,
            endpoint: '<?php echo config('sparql_endpoint'); ?>'
        }
    });
	yasqe.addPrefixes({yago: "http://yago-knowledge.org/resource/"});
	yasqe.addPrefixes({schema: "http://schema.org/"});
    var yasr = YASR(document.getElementById("yasr"), {
        getUsedPrefixes: yasqe.getPrefixesFromQuery,
        useGoogleCharts: false
    });
    //link both together
    yasqe.options.sparql.callbacks.complete = yasr.setResponse;
</script>