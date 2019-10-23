<h1>Graph visualization</h1>
<?php

require 'includes/sparql.php';

function getSparqlQueryXmlDocument(string $query) {
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

function processDocumentWithXslt(DOMDocument $inputDocument, string $xsltFile) {
    $xslt = new XSLTProcessor();
    $stylesheet = new DOMDocument();
    $stylesheet->load($xsltFile);
    $xslt->importStyleSheet($stylesheet);
    return $xslt->transformToXML($inputDocument);
}

if (!isset($_GET['resource']) || !$_GET['resource']) {
    include '404.php';
    return;
}

$resource = resolvePrefixedUri($_GET['resource']);

$relation = isset($_GET['relation']) ? resolvePrefixedUri($_GET['relation']) : null;
$inverse = isset($_GET['inverse']) && is_numeric($_GET['inverse']) ? intval($_GET['inverse']) : 0;
$cursor = isset($_GET['cursor']) && is_numeric($_GET['cursor']) ? intval($_GET['cursor']) : 0;

if($relation !== null) {
    $sparqlQuery = 'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

SELECT ?s ?p ?o (' . $cursor . ' AS ?page) (' . $inverse . ' AS ?inverse) (' . $relation . ' AS ?relation) WHERE {
 BIND(<' . $resource . '> AS ?s)' . ($relation=='all'?'':'BIND(<' . $relation . '> AS ?p)') .
($inverse>0?'?o ?p ?s':'?s ?p ?o') .'} LIMIT 20 OFFSET ' . $cursor * 20;
    print processDocumentWithXslt(getSparqlQueryXmlDocument($sparqlQuery), __DIR__ . '/../includes/relation_builder.xslt');

} else {
    $sparqlQuery = 'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

SELECT ?s ?p ?o ?count WHERE {
 {SELECT DISTINCT ?s (rdfs:subClassOf AS ?p) ?o (-1 AS ?count) WHERE {
   <' . $resource . '> rdf:type ?c .
   ?c rdfs:subClassOf* ?s .
   ?s rdfs:subClassOf ?o .
 }}
 UNION
 {SELECT ?s ?p (SAMPLE(?o) AS ?o) (COUNT(?o) AS ?count) WHERE {
   BIND(<' . $resource . '> AS ?s)
   ?s ?p ?o .
   FILTER(?p != rdf:type)
   FILTER(?p != rdfs:subClassOf)
 } GROUP BY ?s ?p }
 UNION
 {SELECT DISTINCT ?s (rdfs:subClassOf AS ?p) ?o (-2 AS ?count) WHERE {
   <' . $resource . '> rdfs:subClassOf+ ?s .
   ?s rdfs:subClassOf ?o .
 }}
}
';
    print processDocumentWithXslt(getSparqlQueryXmlDocument($sparqlQuery), __DIR__ . '/../includes/graph_builder.xslt');
}
?>