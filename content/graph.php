<?php

require 'includes/sparql.php';

function getSparqlQueryXmlDocument(string $query)
{
    global $PREFIXES;

    // We do the SPARQL query: custom code to get XML results
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'Accept: application/sparql-results+xml',
                'User-Agent: YagoWebsite/1.0'
            ],
        ]
    ]);
    $url = config('sparql_endpoint') . '?query=' . urlencode($query);
    $response = file_get_contents($url, false, $context);
    if (!$response) {
        throw new Exception("HTTP error for SPARQL query:\n" . $query);
    }

    // We replace expended URIs by their prefix (very hacky):
    foreach ($PREFIXES as $prefix => $base) {
        $response = str_replace($base, $prefix . ':', $response);
    }


    $document = new DOMDocument();
    $document->loadXML($response);
    return $document;
}

function processDocumentWithXslt(DOMDocument $inputDocument, string $xsltFile)
{
    $xslt = new XSLTProcessor();
    $stylesheet = new DOMDocument();
    $stylesheet->load($xsltFile);
    $xslt->importStyleSheet($stylesheet);
    return $xslt->transformToXML($inputDocument);
}

if ( !isset( $_GET['resource'] ) || !$_GET['resource'] ) {
    // We pick something randomly
    $resourcesCount = intval(doSingleResultQuery('SELECT (COUNT(*) AS ?c) WHERE { ?c a  <http://schema.org/Thing> }')['value']);
    $offset = random_int(0, $resourcesCount - 1);
    $resource = doSingleResultQuery('SELECT ?r WHERE { ?r a <http://schema.org/Thing> } OFFSET ' . $offset . ' LIMIT 1')['value'];
    header('Location: /graph/' . uriToPrefixedName($resource), true, 302);
    exit();
}

$resource = isset($_GET['resource']) ? $_GET['resource'] : '';
$searchText = '';
$searchLang = 'en';
if (preg_match('/^"(.*)"$/', $resource, $m)) {
    // Plain literal ok
    $searchText = $m[1];
} else if (preg_match('/^"(.*)"@([a-zA-Z\-]+)$/', $resource, $m)) {
    // Plain lang literal ok
    $searchText = $m[1];
    $searchLang = $m[2];
} else if (preg_match('/^"(.*)"\^\^<.+>$/', $resource, $m)) {
    //Literal with full datatype URI ok
    $searchText = $m[1];
} else if (preg_match('/^"(.*)"\^\^(.+)$/', $resource, $m)) {
    //Literal with relative datatype URI
    $resource = '"' . $m[1] . '"^^<' . resolvePrefixedUri($m[2]) . '>';
    $searchText = $m[1];
} elseif (preg_match('/^<.+>$/', $resource)) {
    // URI
    $resource = '<' . resolvePrefixedUri(substr($resource, 1, -1)) . '>';
} else {
    $resource = '<' . resolvePrefixedUri($resource) . '>';
}

$relation = isset($_GET['relation']) ? resolvePrefixedUri($_GET['relation']) : null;
$inverse = isset($_GET['inverse']) && $_GET['inverse'];
$cursor = isset($_GET['cursor']) && is_numeric($_GET['cursor']) ? intval($_GET['cursor']) : 0;

?>
    <h1 style="text-align: center;">Graph visualization</h1>

    <form id="search" class="row">
        <div class="col s6 input-field">
            <input name="search" id="search-text" type="text" value="<?php echo $searchText; ?>">
            <label for="search-text">Search Yago</label>
        </div>
        <div class="col s3 input-field">
            <select id="search-lang">
                <?php
                $languages = array_unique(array_map(function ($locale) {
                    return Locale::getPrimaryLanguage($locale);
                }, ResourceBundle::getLocales('')));
                foreach ($languages as $language) {
                    if ($language === $searchLang) {
                        print '<option value="' . $language . '" selected>' . Locale::getDisplayLanguage($language, $language) . '</option>';
                    } else {
                        print '<option value="' . $language . '">' . Locale::getDisplayLanguage($language, $language) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="col s3" style="margin-top: 1.5rem;">
            <button type="submit" class="waves-effect waves-light btn">search</button>
            <button id="random" class="waves-effect waves-light btn">random</button>
        </div>
        </div>
    </form>
    <script>
        window.onload = function () {
            $('#search-lang').formSelect();

            $('#search').submit(function () {
                window.location.href = '/graph/"' + $('#search-text').val() + '"@' + $('#search-lang').val() + '?relation=all&inverse=1';
                return false;
            });

            $('#random').click(function () {
                window.location.href = '/graph/';
                return false;
            });
        };
    </script>

<?php
if ($resource === '') {
    return;
}

if ($relation !== null) {
    $sparqlQuery = '
SELECT ?s ?p ?o (' . $cursor . ' AS ?page) (' . ($inverse ? '1' : '0') . ' AS ?inverse) (\'' . $relation . '\' AS ?relation) WHERE {
 BIND(' . $resource . ' AS ?s)' . ($relation == 'http://yago-knowledge.org/resource/all' ? '' : 'BIND(<' . $relation . '> AS ?p)') .
        ($inverse ? '?o ?p ?s' : '?s ?p ?o') . '} LIMIT 20 OFFSET ' . $cursor * 20;
    print processDocumentWithXslt(getSparqlQueryXmlDocument($sparqlQuery), __DIR__ . '/../includes/relation_builder.xslt');
} else {
    $sparqlQuery = 'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

SELECT ?s ?p ?o ?count WHERE {
 {SELECT DISTINCT ?s (rdfs:subClassOf AS ?p) ?o (-1 AS ?count) WHERE {
   ' . $resource . ' rdf:type ?c .
   ?c rdfs:subClassOf* ?s .
   ?s rdfs:subClassOf ?o .
 }}
 UNION
 {SELECT ?s ?p (SAMPLE(?o) AS ?o) (COUNT(?o) AS ?count) WHERE {
   BIND(' . $resource . ' AS ?s)
   ?s ?p ?o .
   FILTER(?p != rdf:type)
   FILTER(?p != rdfs:subClassOf)
 } GROUP BY ?s ?p }
 UNION
 {SELECT DISTINCT ?s (rdfs:subClassOf AS ?p) ?o (-2 AS ?count) WHERE {
   ' . $resource . ' rdfs:subClassOf+ ?s .
   ?s rdfs:subClassOf ?o .
 }}
}
';
    print processDocumentWithXslt(getSparqlQueryXmlDocument($sparqlQuery), __DIR__ . '/../includes/graph_builder.xslt');
}
?>