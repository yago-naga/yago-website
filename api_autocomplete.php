<?php

require_once 'includes/config.php';
require_once 'includes/sparql.php';

header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 3) {
    http_response_code(400);
    echo json_encode(['error' => 'Query too short']);
    exit;
}

// Title-case before escaping so escape sequences aren't mangled
$q = mb_convert_case($q, MB_CASE_TITLE);
// Escape SPARQL special characters to prevent injection
$q = str_replace(['\\', '"', "\n", "\r", "\t"], ['\\\\', '\\"', '\\n', '\\r', '\\t'], $q);
$lang = Locale::getPrimaryLanguage($locale);

$sparql = doSparqlQuery(
    'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> '
    . 'SELECT ?entity ?label ?siteLinks WHERE { '
    . '?entity rdfs:label ?label . '
    . 'FILTER((LANG(?label) = "' . $lang . '" || LANG(?label) = "mul") && STRSTARTS(?label, "' . $q . '")) '
    . 'OPTIONAL { ?entity <http://yago-knowledge.org/resource/siteLinks> ?siteLinks } '
    . '} ORDER BY DESC(?siteLinks) LIMIT 10'
);

$results = [];
foreach ($sparql['results']['bindings'] as $binding) {
    $results[] = [
        'uri' => $binding['entity']['value'],
        'label' => $binding['label']['value'],
        'url' => uriToUrl($binding['entity']['value']),
        'prefixedName' => strip_tags(uriToPrefixedName($binding['entity']['value'])),
    ];
}

echo json_encode(['results' => $results]);
