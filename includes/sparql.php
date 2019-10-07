<?php

$locale = isset($_GET['lang']) ? $_GET['lang'] : 'en';
const VALUES_LIMIT = 20;
$locale = Locale::canonicalize($locale);
$numberFormatter = new NumberFormatter($locale, NumberFormatter::DEFAULT_STYLE);
//TODO: use Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) ?

global $PREFIXES;
$PREFIXES = [
    'bioschemas' => 'http://bioschemas.org/',
    'dbpedia' => 'http://dbpedia.org/resource/',
    'freebase' => 'http://rdf.freebase.com/ns/',
    'owl' => 'http://www.w3.org/2002/07/owl#',
    'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
    'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
    'schema' => 'http://schema.org/',
    'sh' => 'http://www.w3.org/ns/shacl#',
    'wd' => 'http://www.wikidata.org/entity/',
    'xsd' => 'http://www.w3.org/2001/XMLSchema#',
    'yago' => 'http://yago-knowledge.org/resource/',
];

global $DISPLAYED_PREFIXES;
$DISPLAYED_PREFIXES = [
    'bioschemas' => 'http://bioschemas.org/',
    'schema' => 'http://schema.org/',
    null => 'http://yago-knowledge.org/resource/',
];

global $PROPERTIES_BLACKLIST;
$PROPERTIES_BLACKLIST = [
    'http://www.w3.org/1999/02/22-rdf-syntax-ns#first',
    'http://www.w3.org/1999/02/22-rdf-syntax-ns#rest',
];

$SAME_AS_LABELS = [
    'http://dbpedia.org/resource/' => 'DBpedia',
    'http://www.wikidata.org/entity/' => 'Wikidata',
    'https://' . Locale::getPrimaryLanguage($locale) . '.wikipedia.org/wiki/' => 'Wikipedia',
];

/**
 * Executes SPARQL query $query and returns its results according to https://www.w3.org/TR/sparql11-results-json/
 */
function doSparqlQuery($query)
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'Accept: application/sparql-results+json',
                'User-Agent: YagoWebsite/1.0'
            ],
        ]
    ]);

    $url = config('sparql_endpoint') . '?query=' . urlencode($query);
    $response = file_get_contents($url, false, $context);
    if (!$response) {
        throw new Exception("HTTP error for SPARQL query:\n" . $query);
    }
    $result = json_decode($response, true);
    if ($result === null) {
        throw new Exception("Invalid response for SPARQL query:\n" . $query . "\n\n" . $response);
    }
    return $result;
}

function uriToPrefixedName($uri)
{
    global $PREFIXES;

    $edited = false;
    foreach ($PREFIXES as $k => $v) {
        if (strpos($uri, $v) === 0) {
            $uri = str_replace($v, $k . ':', $uri);
            $edited = true;
            break;
        }
    }

    if ($edited) {
        return htmlspecialchars($uri);
    } else {
        return '&lt;' . htmlspecialchars($uri) . '&gt;';
    }
}

function uriToUrl($uri)
{
    global $DISPLAYED_PREFIXES;

    foreach ($DISPLAYED_PREFIXES as $prefix => $uriStart) {
        if (strpos($uri, $uriStart) === 0) {
            if ($prefix) {
                return htmlspecialchars(str_replace($uriStart, '/resource/' . $prefix . ':', $uri));
            } else {
                return htmlspecialchars(str_replace($uriStart, '/resource/', $uri));
            }
        }
    }
    return htmlspecialchars($uri);
}

function uriToLink($uri, $label = null, $description = null)
{
    $title = strip_tags($label ? $label . ($description ? ', ' . $description : '') : ($description ?? ''));
    return '<a href="' . uriToUrl($uri) . '" title="' . htmlspecialchars($title) . '">' . uriToPrefixedName($uri) . '</a>';
}

function getValueInDisplayLanguage(array $propertyValues, $propertyUri, $locale)
{
    $values = [];
    if (isset($propertyValues[$propertyUri])) {
        foreach ($propertyValues[$propertyUri] as $value) {
            $values[$value['xml:lang']] = $value['value'];
        }
    }
    $target = Locale::lookup(array_keys($values), $locale, true, null);
    return isset($values[$target]) ? $values[$target] : null;
}

function describeEntity($resource, $locale, $reverse = false)
{
    global $PROPERTIES_BLACKLIST;

    $filter = $reverse ? '?o ?p <' . $resource . '>' : ' <' . $resource . '> ?p ?o';

    $properties = [];
    $sparql = doSparqlQuery('SELECT DISTINCT ?p WHERE { ' . $filter . ' } LIMIT 100');
    foreach ($sparql['results']['bindings'] as $binding) {
        $property = $binding['p']['value'];
        if (!in_array($property, $PROPERTIES_BLACKLIST)) {
            $properties[] = $property;
        }
    }


    $propertyValues = [];
    foreach ($properties as $property) {
        $sparql = doSparqlQuery('SELECT DISTINCT ?o ?label WHERE { BIND(<' . $property . '> AS ?p) ' . $filter . ' . OPTIONAL { ?o <http://www.w3.org/2000/01/rdf-schema#label>|<http://schema.org/name> ?label . FILTER(LANG(?label) = "' . Locale::getPrimaryLanguage($locale) . '") } } LIMIT 100');

        foreach ($sparql['results']['bindings'] as $binding) {
            $valueKey = json_encode($binding['o']);
            $propertyValues[$property][$valueKey] = $binding['o'];
            if (isset($binding['label'])) {
                $propertyValues[$property][$valueKey]['label'] = $binding['label'];
            }
        }
    }

    return $propertyValues;
}

function getResourceShapes($resource)
{
    $sparql = doSparqlQuery('
    PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    SELECT DISTINCT ?c WHERE { <' . $resource . '> rdf:type ?c . FILTER(STRSTARTS(STR(?c), "http://schema.org/") || STRSTARTS(STR(?c), "http://bioschemas.org/") || STRSTARTS(STR(?c), "http://www.w3.org/")) } LIMIT 200');

    $shapes = [];
    foreach ($sparql['results']['bindings'] as $binding) {
        $shapes[] = $binding['c']['value'];
    }

    return $shapes;
}

function doSingleResultQuery($query)
{
    $sparql = doSparqlQuery($query);
    foreach ($sparql['results']['bindings'] as $binding) {
        foreach ($binding as $key => $value) {
            return $value;
        }
    }
    return null;
}

function displayPropertyValuesTable($propertyValues, $predicateLabel = 'Predicate', $objectLabel = 'Object')
{
    print '<table><thead><tr><th scope="col" style="min-width: 40%;">' . $predicateLabel . '</th><th scope="col">' . $objectLabel . '</th></tr></thead><tbody>';
    foreach ($propertyValues as $property => $values) {
        print '<tr><th scope="row">' . uriToLink($property) . '</th><td><ul>';
        $i = 0;
        foreach ($values as $value) {
            print '<li>';
            if ($value['type'] === 'uri') {
                print uriToLink($value['value'], isset($value['label']) ? $value['label']['value'] : '');
            } elseif ($value['type'] === 'bnode') {
                print '_:' . htmlspecialchars($value['value']);
            } elseif ($value['type'] === 'literal') {
                $label = htmlspecialchars($value['value']);
                if (isset($value['xml:lang'])) {
                    $lang = htmlspecialchars($value['xml:lang']);
                    print '"<span lang="' . $lang . '">' . $label . '</span>"@' . $lang . '';
                } elseif (isset($value['datatype'])) {
                    print '"' . $label . '"^^' . uriToLink($value['datatype']) . '</a>';
                } else {
                    print '"' . $label . '"';
                }
            }
            print '</li>';
            if ($i === VALUES_LIMIT) {
                print '<li>...</li>';
                break;
            }
            $i++;
        }
        print '</ul></td></tr>';
    }
    print '</tbody></table>';
}


function getShapeDescription($shape, $language)
{
    $sparqlQuery = 'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        PREFIX sh: <http://www.w3.org/ns/shacl#>
        SELECT ?prop ?label ?comment ?datatype ?node ?pattern WHERE {
            <' . $shape . '> rdfs:subClassOf* ?shape .
            ?shape a sh:NodeShape ; sh:property ?p .
            ?p sh:path ?prop .
            OPTIONAL { ?prop rdfs:label ?label FILTER(LANG(?label) = "' . $language . '") }
            OPTIONAL { ?prop rdfs:comment ?comment FILTER(LANG(?comment) = "' . $language . '") }
            OPTIONAL { ?p sh:datatype ?datatype }
            OPTIONAL { ?p sh:or/rdf:rest*/rdf:first/sh:datatype  ?datatype }
            OPTIONAL { ?p sh:node ?node }
            OPTIONAL { ?p sh:or/rdf:rest*/rdf:first/sh:node  ?node }
            OPTIONAL { ?p sh:maxCount ?maxCount }
            OPTIONAL { ?p sh:pattern ?pattern }
        } ORDER BY ?prop';
    $shape = [];
    foreach (doSparqlQuery($sparqlQuery)['results']['bindings'] as $binding) {
        $prop = $binding['prop']['value'];
        if (!isset($shape[$prop])) {
            $shape[$prop] = [
                'datatype' => [],
                'node' => [],
                'pattern' => null,
                'maxCount' => null,
                'label' => null,
                'comment' => null,
            ];
        }
        if (isset($binding['datatype'])) {
            $dt = $binding['datatype']['value'];
            $shape[$prop]['datatype'][$dt] = $dt;
        }
        if (isset($binding['node'])) {
            $node = $binding['node']['value'];
            $shape[$prop]['node'][$node] = $node;
        }
        if (isset($binding['pattern'])) {
            $shape[$prop]['pattern'] = $binding['pattern']['value'];
        }
        if (isset($binding['maxCount'])) {
            $shape[$prop]['maxCount'] = intval($binding['maxCount']['value']);
        }
        if (isset($binding['label'])) {
            $shape[$prop]['label'] = $binding['label']['value'];
        }
        if (isset($binding['comment'])) {
            $shape[$prop]['comment'] = $binding['comment']['value'];
        }
    }
    return $shape;
}

function getClassesTree($root, $language, $levels, $superClasses = false, $constraint = null)
{
    $relation = $superClasses ? '^rdfs:subClassOf' : 'rdfs:subClassOf';
    $sparqlQuery = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
    SELECT DISTINCT';
    for ($i = 0; $i < $levels; $i++) {
        $sparqlQuery .= " ?class{$i} ?class{$i}Label ?class{$i}Comment";
    }
    $sparqlQuery .= " WHERE { ";
    for ($i = 0; $i < $levels; $i++) {
        $clause = "OPTIONAL { ?class{$i} rdfs:label ?class{$i}Label FILTER(LANG(?class{$i}Label) = '{$language}') } OPTIONAL { ?class{$i} rdfs:comment ?class{$i}Comment FILTER(LANG(?class{$i}Label) = '{$language}') }";
        if ($i === 0) {
            $sparqlQuery .= " BIND(<{$root}> AS ?class{$i}) . {$clause}";
        } else {
            $previous = $i - 1;
            $sparqlQuery .= " OPTIONAL { ?class{$i} {$relation} ?class{$previous} . {$clause}";
        }
        if ($constraint !== null) {
            $sparqlQuery .= "?class{$i} {$constraint}";
        }
    }
    for ($i = 1; $i < $levels; $i++) {
        $sparqlQuery .= '}';
    }
    $sparqlQuery .= "}";
    $sparql = doSparqlQuery($sparqlQuery);


    $tree = [
        'children' => []
    ];
    foreach ($sparql['results']['bindings'] as $binding) {
        $root = &$tree;
        for ($i = 0; $i < $levels; $i++) {
            if (!isset($binding["class{$i}"])) {
                break;
            }

            $class = $binding["class{$i}"]['value'];
            if (!isset($root['children'][$class])) {
                $root['children'][$class] = [
                    'label' => $binding["class{$i}Label"]['value'] ?? null,
                    'comment' => $binding["class{$i}Comment"]['value'] ?? null,
                    'children' => []
                ];
            }
            $root = &$root['children'][$class];
        }
    }
    return $tree;
}

function printTreeNode($name, $values)
{
    print '<li class="tree-edge">';
    print uriToLink($name, $values['label'], $values['comment']);
    if ($values['children']) {
        asort($values['children']);
        print '<ul class="tree-node">';
        foreach ($values['children'] as $childName => $childValues) {
            printTreeNode($childName, $childValues);
        }
        print '</ul>';
    }

    print '</li>';
}

function treeToPaths($name, $values)
{
    $current = uriToLink($name, $values['label'], $values['comment']);
    if ($values['children']) {
        foreach ($values['children'] as $childName => $childValues) {
            foreach (treeToPaths($childName, $childValues) as $path) {
                $path[] = $current;
                yield $path;
            }
        }
    } else {
        yield [$current];
    }
}
