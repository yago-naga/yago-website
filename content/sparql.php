<div class="wrap">
    <h1>SPARQL</h1>
    <p>
        YAGO can be queried by a SPARQL API.
        The SPARQL endpoint URI is <code>http://yago.r2.enst.fr/sparql/query</code>.
        There is a 1 minutes timeout to ensure a responsive SPARQL endpoint for everyone.
        You can also fire a SPARQL query directly in the field below.
    </p>

    <p>
        <a class="waves-effect waves-light btn" id="query-data-wrap-toggle">
            <span id="wrap-full">FULL WIDTH</span>
            <span id="wrap-reduce">REDUCE WIDTH</span>
        </a>
    </p>
</div>

<div id="query-data-wrap" class="wrap">
    <div class="query-wikidata">
        <main>
            <div id="yasqe"></div>
            <div id="yasr"></div>
        </main>
        <link rel="stylesheet" href="//tools-static.wmflabs.org/cdnjs/ajax/libs/yasqe/2.11.22/yasqe.min.css"/>
        <link rel="stylesheet" href="//tools-static.wmflabs.org/cdnjs/ajax/libs/yasr/2.12.19/yasr.min.css"/>
        <script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/yasr/2.12.19/yasr.bundled.min.js"></script>
        <script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/yasqe/2.11.22/yasqe.bundled.min.js"></script>
        <script>
            var yasqe = YASQE(document.getElementById("yasqe"), {
                sparql: {
                    showQueryButton: true,
                    endpoint: 'http://yago.r2.enst.fr/sparql/query'
                }
            });
            var yasr = YASR(document.getElementById("yasr"), {
                getUsedPrefixes: yasqe.getPrefixesFromQuery,
                useGoogleCharts: false
            });
            //link both together
            yasqe.options.sparql.callbacks.complete = yasr.setResponse;
        </script>
    </div>
</div>
