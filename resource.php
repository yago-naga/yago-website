<?php

// Comment these lines to hide errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Hack if we move back to index.php
$_GET['page'] = 'resource';

require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/sparql.php';

if (!isset($_GET['resource']) || !$_GET['resource']) {
    include 'index.php';
    return;
}

$resource = resolvePrefixedUri($_GET['resource']);

// Content negotiation
$typeMap = [ // Customized for Blazegraph
    '*/*' => 'text/html',
    'text/*' => 'text/html',
    'text/html' => 'text/html',
    'text/turtle' => 'application/x-turtle',
    'text/n3' => 'text/rdf+n3',
    'application/json' => 'application/ld+json',
    'application/ld+json' => 'application/ld+json',
    'application/n-quads' => 'text/x-nquads',
    'application/n-triples' => 'text/plain',
    'application/rdf+xml' => 'application/rdf+xml',
    'application/trig' => 'application/x-trig',
    'application/xml' => 'application/rdf+xml',
    'application/xhtml+xml' => 'text/html'
];

$accept = $_SERVER['HTTP_ACCEPT'] ?: '*/*';
foreach (parse_accept_header($accept) as $type) {
    if (!array_key_exists($type, $typeMap)) {
        continue;
    }
    $mappedType = $typeMap[$type];
    if ($mappedType === 'text/html') {
        //We use html
        include 'index.php';
        return;
    } else {
        //We use Blazegraph
        header('Content-Type: ' . $type . '; charset=UTF-8');
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Accept: ' . $mappedType . '; charset=UTF-8',
                    'User-Agent: YagoWebsite/1.0'
                ],
            ]
        ]);

        $url = config('sparql_endpoint') . '?query=' . urlencode('DESCRIBE <' . $resource . '>');
        echo file_get_contents($url, false, $context);
        return;
    }
}