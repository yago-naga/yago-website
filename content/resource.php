<?php

$locale = isset($_GET['lang']) ? $_GET['lang'] : 'en';
$locale = Locale::canonicalize($locale);
//TODO: use Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) ?

$GLOBALS['PREFIXES'] = [
    'dbpedia' => 'http://dbpedia.org/resource/',
    'freebase' => 'http://rdf.freebase.com/ns/',
    'owl' => 'http://www.w3.org/2002/07/owl#',
    'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
    'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
    'schema' => 'http://schema.org/',
    'wd' => 'http://www.wikidata.org/entity/',
    'xsd' => 'http://www.w3.org/2001/XMLSchema#',
    'yago' => 'http://yago-knowledge.org/resource/',
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
                'Accept: application/sparql-results+json'
            ],
        ]
    ]);

    $url = config('sparql_endpoint') . '?query=' . urlencode($query);
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
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
    return htmlspecialchars(str_replace('http://yago-knowledge.org/', '/', $uri));
}

function uriToLink($uri)
{
    return '<a href="' . uriToUrl($uri) . '">' . uriToPrefixedName($uri) . '</a>';
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
    $filter = $reverse ? '?o ?p <' . $resource . '>' : ' <' . $resource . '> ?p ?o';
    $sparql = doSparqlQuery('
    PREFIX schema: <http://schema.org/> 
    PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
    SELECT DISTINCT ?p ?o ?label WHERE { ' . $filter . ' . OPTIONAL { ?o rdfs:label|schema:name ?label . FILTER(LANG(?label) = "' . Locale::getPrimaryLanguage($locale) . '") } } LIMIT 1000');

    $propertyValues = [];
    foreach ($sparql['results']['bindings'] as $binding) {
        $property = $binding['p']['value'];
        $valueKey = json_encode($binding['o']);
        $propertyValues[$property][$valueKey] = $binding['o'];
        if (isset($binding['label'])) {
            $propertyValues[$property][$valueKey]['label'] = $binding['label'];
        }
    }

    return $propertyValues;
}

function getResourceShapes($resource)
{
    $sparql = doSparqlQuery('
    PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
    SELECT DISTINCT ?c WHERE { <' . $resource . '> rdf:type/rdfs:subClassOf* ?c . FILTER(STRSTARTS(STR(?c), "http://schema.org/")) } LIMIT 200');

    $shapes = [];
    foreach ($sparql['results']['bindings'] as $binding) {
        $shapes[] = $binding['c']['value'];
    }

    return $shapes;
}

function displayPropertyValuesTable($propertyValues, $predicateLabel = 'Predicate', $objectLabel = 'Object')
{
    print '<table><thead><tr><th scope="col" style="min-width: 40%;">' . $predicateLabel . '</th><th scope="col">' . $objectLabel . '</th></tr></thead><tbody>';
    foreach ($propertyValues as $property => $values) {
        print '<tr><th scope="row">' . uriToLink($property) . '</th><td><ul>';
        foreach ($values as $value) {
            print '<li>';
            if ($value['type'] === 'uri') {
                $label = uriToPrefixedName($value['value']);
                $description = isset($value['label']) ? $value['label']['value'] : '';
                print '<a href="' . uriToUrl($value['value']) . '" title="' . $description . '">' . $label . '</a>';
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
        }
        print '</ul></td></tr>';
    }
    print '</tbody></table>';
}

function wfUrlencode($s)
{
    //Clone of MediaWiki wfUrlencode
    return str_ireplace(
        ['%3B', '%40', '%24', '%21', '%2A', '%28', '%29', '%2C', '%2F', '%7E', '%3A'],
        [';', '@', '$', '!', '*', '(', ')', ',', '/', '~', ':'],
        urlencode($s)
    );
}

if (!isset($_GET['resource']) || !$_GET['resource']) {
    include '404.php';
    return;
}

$resource = 'http://yago-knowledge.org/resource/' . wfUrlencode($_GET['resource']);

$propertyValues = describeEntity($resource, $locale);

if (!$propertyValues) {
    include '404.php';
    return;
}

$resourceLabel = getValueInDisplayLanguage($propertyValues, 'http://schema.org/name', $locale)
    ?: getValueInDisplayLanguage($propertyValues, 'http://www.w3.org/2000/01/rdf-schema#label', $locale)
        ?: uriToPrefixedName($resource);

$resourceDescription = getValueInDisplayLanguage($propertyValues, 'http://schema.org/description', $locale)
    ?: getValueInDisplayLanguage($propertyValues, 'http://www.w3.org/2000/01/rdf-schema#comment', $locale)
        ?: '';

$sameAsLinks = [];
if (isset($propertyValues['http://www.w3.org/2002/07/owl#sameAs'])) {
    foreach ($propertyValues['http://www.w3.org/2002/07/owl#sameAs'] as $value) {
        foreach ($SAME_AS_LABELS as $prefix => $label) {
            if (strpos($value['value'], $prefix) === 0) {
                $sameAsLinks[$label] = uriToUrl($value['value']);
            }
        }
    }
}
if (isset($propertyValues['http://schema.org/sameAs'])) {
    foreach ($propertyValues['http://schema.org/sameAs'] as $value) {
        foreach ($SAME_AS_LABELS as $prefix => $label) {
            if (strpos($value['value'], $prefix) === 0) {
                $sameAsLinks[$label] = uriToUrl($value['value']);
            }
        }
    }
}
if (isset($propertyValues['http://schema.org/url'])) {
    foreach ($propertyValues['http://schema.org/url'] as $value) {
        $sameAsLinks['Official Website'] = uriToUrl($value['value']);
    }
}

$resourceImage = null;
if (isset($propertyValues['http://schema.org/image'])) {
    foreach ($propertyValues['http://schema.org/image'] as $value) {
        $resourceImage = uriToUrl($value['value']);
    }
}

$shapes = getResourceShapes($resource);
sort($shapes);

print '<div class="card horizontal">';
print '<div class="card-stacked">';
print '<div class="card-content">';
print '<span class="card-title">' . $resourceLabel . '</span>';
print '<p>' . $resourceDescription . '</p>';
print '<p>Shapes: ' . implode(', ', array_map('uriToLink', $shapes)) . '</p>';
print '</div>';
if ($sameAsLinks) {
    print '<div class="card-action">';
    foreach ($sameAsLinks as $label => $uri) {
        print '<a href="' . $uri . '">' . $label . '</a>';
    }
    print '</div>';
}
print '</div>';
if ($resourceImage !== null) {
    print '<div class="image"><img src="' . $resourceImage . '" height="200"></div>';
}
print '</div>';

print '<div class="card"><div class="card-content"><span class="card-title">Properties</span>';
displayPropertyValuesTable($propertyValues);
print '</div></div>';

$reversePropertyValues = describeEntity($resource, $locale, true);
if ($reversePropertyValues) {
    print '<div class="card"><div class="card-content"><span class="card-title">Incoming properties</span>';
    displayPropertyValuesTable($reversePropertyValues, 'Predicate', 'Subject');
    print '</div></div>';
}
