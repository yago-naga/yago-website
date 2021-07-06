<h1>SPARQL</h1>
<p>
    YAGO can be queried by a SPARQL API.
    The SPARQL endpoint URI is <code><?php echo config('sparql_endpoint'); ?></code>.
    There is a 1 minute timeout to ensure a responsive SPARQL endpoint for everyone.
    You can also fire a SPARQL query directly in the field below. If you plan to launch several queries, please <a href=downloads>download</a> YAGO instead.<br>
	<span style="font-size: 50%">We (the <a href="http://dig.telecom-paristech.fr/">DIG team at Télécom Paris</a>, hosting the website) log queries and IP addresses, primarily for debugging purpose in order to run the service smoothly. We can share this log, pseudonymized, with selected researchers, purely for research purpose. You can contact us at <i>nameOfTheKnowledgeBase</i>@fabian.suchanek.name if you want to exercise any right about this data you have under GDPR. You can avoid this logging altogether by downloading YAGO without using our API endpoint.</span>
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
    var yasr = YASR(document.getElementById("yasr"), {
        getUsedPrefixes: yasqe.getPrefixesFromQuery,
        useGoogleCharts: false
    });
    //link both together
    yasqe.options.sparql.callbacks.complete = yasr.setResponse;
</script>