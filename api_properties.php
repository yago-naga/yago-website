<?php

require_once 'includes/config.php';
require_once 'includes/sparql.php';

header('Content-Type: application/json');

$resource = $_GET['resource'] ?? '';
$property = $_GET['property'] ?? '';
$reverse = !empty($_GET['reverse']);
$page = max(1, intval($_GET['page'] ?? 1));
$limit = min(50, max(1, intval($_GET['limit'] ?? PAGE_LIMIT)));

if (!$resource || !$property) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

$offset = ($page - 1) * $limit;
$lang = Locale::getPrimaryLanguage($locale);

if ($reverse) {
    $filter = '?o <' . $property . '> <' . $resource . '>';
} else {
    $filter = '<' . $resource . '> <' . $property . '> ?o';
}

$countSparql = doSparqlQuery('SELECT (COUNT(DISTINCT ?o) AS ?c) WHERE { ' . $filter . ' }');
$total = intval($countSparql['results']['bindings'][0]['c']['value'] ?? 0);

$valuesSparql = doSparqlQuery(
    'SELECT DISTINCT ?o ?label WHERE { ' . $filter .
    ' . OPTIONAL { ?o <http://www.w3.org/2000/01/rdf-schema#label> ?label . FILTER(LANG(?label) = "' . $lang . '") } }' .
    ' ORDER BY DESC(LANG(?o) = "' . $lang . '" || STRSTARTS(LANG(?o), "' . $lang . '-") || LANG(?o) = "mul") ?o' .
    ' LIMIT ' . $limit . ' OFFSET ' . $offset
);

$values = [];
foreach ($valuesSparql['results']['bindings'] as $binding) {
    $value = $binding['o'];
    if (isset($binding['label'])) {
        $value['label'] = $binding['label'];
    }
    $values[] = renderPropertyValue($value);
}

echo json_encode([
    'propertyLabel' => uriToPrefixedName($property),
    'values' => $values,
    'total' => $total,
    'page' => $page,
    'totalPages' => max(1, ceil($total / $limit)),
]);
