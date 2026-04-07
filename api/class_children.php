<?php
/**
 * JSON API: direct subclasses of a given class.
 * Used by the lazy-loading child classes tree on resource pages.
 *
 * GET params: class (parent class URI), lang (optional)
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/sparql.php';

header('Content-Type: application/json');

$classUri = trim($_GET['class'] ?? '');
if (!$classUri) {
    echo json_encode(['error' => 'Missing class parameter']);
    exit;
}

$lang = preg_replace('/[^a-z]/', '', $_GET['lang'] ?? 'en');

$escaped = str_replace(['\\', '"', "\n", "\r", "\t"], ['\\\\', '\\"', '\\n', '\\r', '\\t'], $classUri);

$sparql = doSparqlQuery(
    'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> '
    . 'SELECT DISTINCT ?child ?label ?comment (COUNT(DISTINCT ?grandchild) AS ?gc) WHERE { '
    . '?child rdfs:subClassOf <' . $escaped . '> . '
    . 'OPTIONAL { ?child rdfs:label ?label FILTER(LANG(?label) = "' . $lang . '") } '
    . 'OPTIONAL { ?child rdfs:comment ?comment FILTER(LANG(?comment) = "' . $lang . '") } '
    . 'OPTIONAL { ?grandchild rdfs:subClassOf ?child } '
    . '} GROUP BY ?child ?label ?comment ORDER BY ?label LIMIT 200'
);

$children = [];
foreach ($sparql['results']['bindings'] as $binding) {
    $uri = $binding['child']['value'];
    $label = $binding['label']['value'] ?? null;
    $comment = $binding['comment']['value'] ?? null;
    $hasChildren = intval($binding['gc']['value'] ?? 0) > 0;
    $children[] = [
        'uri' => $uri,
        'url' => uriToUrl($uri),
        'prefixed' => uriToPrefixedName($uri),
        'label' => $label,
        'comment' => $comment,
        'hasChildren' => $hasChildren,
    ];
}

echo json_encode(['children' => $children]);
