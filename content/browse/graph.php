<?php

require 'includes/sparql.php';

$resourceParts = explode(':', $_GET['resource'], 2);
if (count($resourceParts) === 2 && isset($PREFIXES[$resourceParts[0]])) {
    $resource = $PREFIXES[$resourceParts[0]] . $resourceParts[1];
} else {
    $resource = 'http://yago-knowledge.org/resource/' . implode(':', $resourceParts);
}

$stylesheet = new DOMDocument();
$stylesheet->load(__DIR__ . '/../../includes/graph_builder.xslt');

$sparqlQuery = 'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

SELECT ?s ?p ?o ?count WHERE {
 {
 SELECT DISTINCT ?s (rdfs:subClassOf AS ?p) ?o (-1 AS ?count) WHERE {
   $resource rdf:type ?c .
   ?c rdfs:subClassOf* ?s .
   ?s rdfs:subClassOf ?o .
 }
 }
 UNION
 {
   SELECT ?s ?p (SAMPLE(?o) AS ?o) (COUNT(?o)
AS ?count) WHERE {
     BIND($resource AS ?s)
     ?s ?p ?o .
     FILTER(?p != rdf:type)
     FILTER(?p != rdfs:subClassOf)
   }
   GROUP BY ?s ?p
 }
 UNION {
	SELECT DISTINCT ?s (rdfs:subClassOf AS ?p) ?o (-2 AS ?count) WHERE {
   $resource rdfs:subClassOf+ ?s .
   ?s rdfs:subClassOf ?o .
 }
 }
}
';

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
$url = config('sparql_endpoint') . '?query=' . urlencode($sparqlQuery);
$response = file_get_contents($url, false, $context);
if (!$response) {
    throw new Exception("HTTP error for SPARQL query:\n" . $sparqlQuery);
}

// We replace expended URIs by their prefix (very hacky):
foreach ($PREFIXES as $prefix => $base) {
    $response = str_replace($base, $prefix . ':', $response);
}


$input = new DOMDocument();
$input->loadXML($response);

$xslt = new XSLTProcessor();
$xslt->importStyleSheet($stylesheet);
print $xslt->transformToXML($input);

?>