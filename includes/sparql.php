<?php
/**
 * SPARQL abstraction layer: query execution, URI/prefix handling, entity description,
 * taxonomy tree building, and property value rendering.
 */

global $locale;
$locale = isset($_GET['lang']) ? $_GET['lang'] : 'en';
const INLINE_DISPLAY_LIMIT = 4;
const PAGE_LIMIT = 10;

// SPARQL query limits
const DESCRIBE_QUERY_LIMIT = 10000;   // Max triples fetched when describing an entity
const MAX_VALUES_PER_PROPERTY = 100;  // Max values stored per property in describeEntity()
const TREE_CHILDREN_LIMIT = 500;      // Max children fetched per level in getClassesTree()
const TREE_FRONTIER_CAP = 50;         // Max nodes to expand per level (prevents fan-out on broad classes)
const MAX_TAXONOMY_DEPTH = 15;        // Max levels to traverse when building taxonomy graph
const MAX_TAXONOMY_EDGES = 200;       // Max edges in taxonomy graph before stopping traversal
$locale = Locale::canonicalize($locale);
global $numberFormatter;
$numberFormatter = new NumberFormatter($locale, NumberFormatter::DEFAULT_STYLE);
//TODO: use Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) ?

// Known namespace prefixes for URI shortening and expansion
global $PREFIXES;
$PREFIXES = [
    'bioschemas' => 'http://bioschemas.org/',
    'dbpedia' => 'http://dbpedia.org/resource/',
    'freebase' => 'http://rdf.freebase.com/ns/',
    'owl' => 'http://www.w3.org/2002/07/owl#',
    'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
    'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
    'schema' => 'http://schema.org/',
	'geo' => 'http://www.opengis.net/ont/geosparql#',
    'sh' => 'http://www.w3.org/ns/shacl#',
    'yago-shape-prop' => 'http://yago-knowledge.org/value/shape-prop-',
    'yago-value' => 'http://yago-knowledge.org/value/',
	'ys' => 'http://yago-knowledge.org/schema#',
    'wd' => 'http://www.wikidata.org/entity/',
    'xsd' => 'http://www.w3.org/2001/XMLSchema#',
    'yago' => 'http://yago-knowledge.org/resource/',
];

// Subset of prefixes that get internal site URLs (others link externally)
global $DISPLAYED_PREFIXES;
$DISPLAYED_PREFIXES = [
    'schema' => 'http://schema.org/',
    'ys' => 'http://yago-knowledge.org/schema#',
    null => 'http://yago-knowledge.org/resource/',
];

// RDF list internals - hidden from entity property tables
global $PROPERTIES_BLACKLIST;
$PROPERTIES_BLACKLIST = [
    'http://www.w3.org/1999/02/22-rdf-syntax-ns#first',
    'http://www.w3.org/1999/02/22-rdf-syntax-ns#rest',
];

// URI prefixes mapped to human-friendly link labels for owl:sameAs / schema:sameAs values
global $SAME_AS_LABELS;
$SAME_AS_LABELS = [
    'http://dbpedia.org/resource/' => 'DBpedia',
    'http://www.wikidata.org/entity/' => 'Wikidata',
    'https://' . Locale::getPrimaryLanguage($locale) . '.wikipedia.org/wiki/' => 'Wikipedia',
];

/**
 * Execute a SPARQL SELECT query and return parsed JSON results.
 *
 * Uses POST for queries whose URL-encoded form exceeds 4000 bytes.
 *
 * @param string $query  SPARQL query string
 * @return array  Decoded JSON per https://www.w3.org/TR/sparql11-results-json/
 * @throws Exception on HTTP or JSON parse failure
 */
function doSparqlQuery($query)
{
    $encoded = urlencode($query);
    $url = config('sparql_endpoint');

    // Use POST for large queries to avoid URL length limits
    if (strlen($encoded) > 4000) {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Accept: application/sparql-results+json',
                    'Content-Type: application/x-www-form-urlencoded',
                    'User-Agent: YagoWebsite/1.0'
                ],
                'content' => 'query=' . $encoded,
            ]
        ]);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Accept: application/sparql-results+json',
                    'User-Agent: YagoWebsite/1.0'
                ],
            ]
        ]);
        $url .= '?query=' . $encoded;
    }

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

/**
 * Convert a full URI to a prefixed name (e.g. "http://schema.org/Person" → "schema:Person").
 * Unknown namespaces are returned as "&lt;uri&gt;". Output is HTML-escaped.
 *
 * @param string $uri  Full URI
 * @return string  HTML-safe prefixed name
 */
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

/**
 * Convert a full URI to an internal site URL path for browsing.
 * Only URIs matching $DISPLAYED_PREFIXES get internal paths; others pass through.
 *
 * @param string $uri             Full URI
 * @param string $visualization   URL prefix: 'resource' (default) or 'graph'
 * @return string  HTML-escaped URL path
 */
function uriToUrl($uri, $visualization = 'resource')
{
    global $DISPLAYED_PREFIXES;

    foreach ($DISPLAYED_PREFIXES as $prefix => $uriStart) {
        if (strpos($uri, $uriStart) === 0) {
            if ($prefix) {
                return htmlspecialchars(str_replace($uriStart, '/' . $visualization . '/' . $prefix . ':', $uri));
            } else {
                return htmlspecialchars(str_replace($uriStart, '/' . $visualization . '/', $uri));
            }
        }
    }
    return htmlspecialchars($uri);
}

/**
 * Build an HTML <a> link to an entity's resource page.
 *
 * @param string      $uri          Full URI
 * @param string|null $label        Human-readable label (used in title attr)
 * @param string|null $description  Short description (appended to title attr)
 * @return string  HTML anchor element
 */
function uriToLink($uri, $label = null, $description = null)
{
    $title = strip_tags($label ? $label . ($description ? ', ' . $description : '') : ($description ?? ''));
    return '<a href="' . uriToUrl($uri) . '" title="' . htmlspecialchars($title) . '">' . uriToPrefixedName($uri) . '</a>';
}

/**
 * Expand a prefixed name to a full URI (e.g. "schema:Person" → "http://schema.org/Person").
 * If the prefix is unknown, defaults to the yago: namespace.
 *
 * @param string $prefixed  Prefixed name (e.g. "schema:Person" or "Elvis_Presley")
 * @return string  Full URI
 */
function resolvePrefixedUri($prefixed)
{
    global $PREFIXES;

    $parts = explode(':', $prefixed, 2);
    if (count($parts) === 2 && isset($PREFIXES[$parts[0]])) {
        return $PREFIXES[$parts[0]] . $parts[1];
    } else {
        return $PREFIXES['yago'] . implode(':', $parts);
    }
}

/**
 * Pick the best language match for a property value using ICU locale lookup.
 *
 * @param array  $propertyValues  Property values grouped by property URI
 * @param string $propertyUri     The property to look up
 * @return string|null  Best-matching value string, or null if not found
 */
function getValueInDisplayLanguage(array $propertyValues, $propertyUri)
{
    global $locale;
    $values = [];
    if (isset($propertyValues[$propertyUri])) {
        foreach ($propertyValues[$propertyUri] as $value) {
            $values[array_key_exists( 'xml:lang', $value ) ? $value['xml:lang'] : 'und'] = $value['value'];
        }
    }
    $target = Locale::lookup(array_keys($values), $locale, true, null);
    return isset($values[$target]) ? $values[$target] : null;
}

/**
 * Fetch all property values for an entity (or all incoming references if $reverse is true).
 * Properties in $PROPERTIES_BLACKLIST are excluded. Values are capped at MAX_VALUES_PER_PROPERTY each.
 *
 * @param string $resource  Full URI of the entity
 * @param bool   $reverse   If true, fetch triples where this entity is the object
 * @return array  Map of property URI → array of value bindings
 */
function describeEntity($resource, $reverse = false)
{
    global $PROPERTIES_BLACKLIST, $locale;
    $lang = Locale::getPrimaryLanguage($locale);

    $filter = $reverse ? '?o ?p <' . $resource . '>' : ' <' . $resource . '> ?p ?o';

    $sparql = doSparqlQuery('SELECT ?p ?o ?label WHERE { ' . $filter
        . ' . OPTIONAL { ?o <http://www.w3.org/2000/01/rdf-schema#label> ?label . FILTER(LANG(?label) = "' . $lang . '") } } LIMIT ' . DESCRIBE_QUERY_LIMIT);

    $propertyValues = [];
    foreach ($sparql['results']['bindings'] as $binding) {
        $property = $binding['p']['value'];
        if (in_array($property, $PROPERTIES_BLACKLIST)) continue;
        if (isset($propertyValues[$property]) && count($propertyValues[$property]) >= MAX_VALUES_PER_PROPERTY) continue;

        $valueKey = json_encode($binding['o']);
        $propertyValues[$property][$valueKey] = $binding['o'];
        if (isset($binding['label'])) {
            $propertyValues[$property][$valueKey]['label'] = $binding['label'];
        }
    }

    return $propertyValues;
}

/**
 * Get the RDF types (shapes) of a resource, filtered to schema.org, bioschemas, and W3C namespaces.
 *
 * @param string $resource  Full URI
 * @return array  List of type URIs
 */
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

/**
 * Execute a SPARQL query and return the first binding value, or null if empty.
 *
 * @param string $query  SPARQL query expected to return a single value
 * @return array|null  The first binding's value array (with 'value', 'type', etc.), or null
 */
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

/**
 * Render a single SPARQL binding value as HTML (URI link, blank node, or literal with lang/datatype).
 *
 * @param array $value  SPARQL binding array with 'type', 'value', and optional 'xml:lang'/'datatype'/'label'
 * @return string  HTML fragment
 */
function renderPropertyValue($value)
{
    $html = '';
    if ($value['type'] === 'uri') {
        $html .= uriToLink($value['value'], isset($value['label']) ? $value['label']['value'] : '');
    } elseif ($value['type'] === 'bnode') {
        $html .= '_:' . htmlspecialchars($value['value']);
    } elseif ($value['type'] === 'literal') {
        $raw = $value['value'];
        $label = htmlspecialchars($raw);
        $isUrl = preg_match('#^https?://#', $raw);
        if (isset($value['xml:lang'])) {
            $lang = htmlspecialchars($value['xml:lang']);
            $text = $isUrl ? '<a href="' . $label . '" target="_blank" rel="noopener">' . $label . '</a>' : $label;
            $html .= '"<span lang="' . $lang . '">' . $text . '</span>"@' . $lang;
        } elseif (isset($value['datatype'])) {
            $text = $isUrl ? '<a href="' . $label . '" target="_blank" rel="noopener">' . $label . '</a>' : $label;
            $html .= '"' . $text . '"^^' . uriToLink($value['datatype']);
        } else {
            $html .= $isUrl ? '<a href="' . $label . '" target="_blank" rel="noopener">' . $label . '</a>' : '"' . $label . '"';
        }
    }
    return $html;
}

/**
 * Print an HTML table of property→values. Values matching the display language are sorted first.
 * Properties exceeding INLINE_DISPLAY_LIMIT show a "more..." link that opens a paginated modal.
 *
 * @param array  $propertyValues  Map of property URI → value bindings (from describeEntity)
 * @param string $predicateLabel  Column header for predicates
 * @param string $objectLabel     Column header for objects
 * @param string $tableId         'out' for outgoing or 'in' for incoming (controls reverse flag on modal)
 * @param string $resource        Entity URI (passed to modal for pagination API calls)
 */
function displayPropertyValuesTable($propertyValues, $predicateLabel = 'Predicate', $objectLabel = 'Object', $tableId = 'tbl', $resource = '')
{
    $reverse = ($tableId === 'in') ? 1 : 0;

    print '<table><thead><tr><th scope="col" style="min-width: 40%;">' . $predicateLabel . '</th><th scope="col">' . $objectLabel . '</th></tr></thead><tbody>';
    foreach ($propertyValues as $property => $values) {
        $valuesArray = array_values($values);
        $lang = Locale::getPrimaryLanguage($GLOBALS['locale']);
        usort($valuesArray, function($a, $b) use ($lang) {
            $aLang = $a['xml:lang'] ?? '';
            $bLang = $b['xml:lang'] ?? '';
            $aMatch = ($aLang === $lang || strpos($aLang, $lang . '-') === 0 || $aLang === 'mul');
            $bMatch = ($bLang === $lang || strpos($bLang, $lang . '-') === 0 || $bLang === 'mul');
            if ($aMatch === $bMatch) return 0;
            return $aMatch ? -1 : 1;
        });
        $totalValues = count($valuesArray);

        print '<tr><th scope="row">' . uriToLink($property) . '</th><td><ul>';

        $limit = min($totalValues, INLINE_DISPLAY_LIMIT);
        for ($i = 0; $i < $limit; $i++) {
            print '<li>' . renderPropertyValue($valuesArray[$i]) . '</li>';
        }

        if ($totalValues > INLINE_DISPLAY_LIMIT) {
            print '<li><a href="#!" class="more-values-link"'
                . ' data-resource="' . htmlspecialchars($resource) . '"'
                . ' data-property="' . htmlspecialchars($property) . '"'
                . ' data-reverse="' . $reverse . '"'
                . '>more&hellip;</a></li>';
        }

        print '</ul></td></tr>';
    }
    print '</tbody></table>';
}

/**
 * Print the Materialize modal HTML used for paginated property value browsing.
 * Works with js/property-modal.js and api/properties.php.
 */
function printPropertyValuesModal()
{
    $apiUrl = config('site_url') . '/api/properties.php';
    print '<div id="property-values-modal" class="modal property-values-modal" data-api-url="' . htmlspecialchars($apiUrl) . '">';
    print '<div class="modal-content">';
    print '<h5 id="modal-property-title"></h5>';
    print '<ul id="modal-values-list" class="property-values-list"></ul>';
    print '</div>';
    print '<div class="modal-footer">';
    print '<ul id="modal-pagination" class="pagination"></ul>';
    print '<a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>';
    print '</div>';
    print '</div>';
}


/**
 * Fetch the SHACL shape description for a class: its allowed properties, datatypes, and constraints.
 * Walks up rdfs:subClassOf* to collect inherited properties.
 *
 * @param string $shape     Full URI of the class/shape
 * @param string $language  ISO language code for labels/comments
 * @return array  Map of property URI → ['datatype' => [...], 'node' => [...], 'pattern', 'maxCount', 'label', 'comment']
 */
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
            OPTIONAL { ?p sh:or ?orList . ?orList rdf:rest* ?rest . ?rest rdf:first ?orEntry . ?orEntry sh:datatype ?datatype }
            OPTIONAL { ?p sh:node ?node }
            OPTIONAL { ?p sh:or ?orList2 . ?orList2 rdf:rest* ?rest2 . ?rest2 rdf:first ?orEntry2 . ?orEntry2 sh:node ?node }
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

/**
 * Build a tree of sub/superclasses by iterative level-by-level SPARQL queries.
 *
 * @param string      $root          Full URI of the root class
 * @param string      $language      ISO language code for labels/comments
 * @param int         $levels        Max depth to traverse
 * @param bool        $superClasses  If true, walk up (superclasses); if false, walk down (subclasses)
 * @param string|null $constraint    Optional extra triple pattern to filter children (e.g. "rdf:type owl:Class")
 * @return array  Nested tree: ['children' => [uri => ['label', 'comment', 'children' => [...]]]]
 */
function getClassesTree($root, $language, $levels, $superClasses = false, $constraint = null)
{
    $relation = $superClasses ? 'rdfs:subClassOf' : '^rdfs:subClassOf';
    $tree = ['children' => []];

    // Iterative level-by-level queries instead of one massive nested-OPTIONAL query
    // Each level: find direct children/parents of nodes at the current frontier
    $frontier = [$root]; // URIs to expand at this level

    $parentMap = []; // Maps each URI to its parent node reference in the tree

    for ($level = 0; $level < $levels && !empty($frontier); $level++) {
        if ($level === 0) {
            // Level 0: just fetch label/comment for the root
            $sparql = doSparqlQuery('PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
                SELECT ?label ?comment WHERE {
                    OPTIONAL { <' . $root . '> rdfs:label ?label FILTER(LANG(?label) = "' . $language . '") }
                    OPTIONAL { <' . $root . '> rdfs:comment ?comment FILTER(LANG(?comment) = "' . $language . '") }
                } LIMIT 1');
            $label = $sparql['results']['bindings'][0]['label']['value'] ?? null;
            $comment = $sparql['results']['bindings'][0]['comment']['value'] ?? null;
            $tree['children'][$root] = ['label' => $label, 'comment' => $comment, 'children' => []];
            $parentMap[$root] = &$tree['children'][$root];
            continue;
        }

        // Build VALUES clause for frontier URIs
        $values = implode(' ', array_map(function($uri) { return '<' . $uri . '>'; }, $frontier));
        $constraintClause = $constraint !== null ? "?child {$constraint}" : '';
        $sparql = doSparqlQuery('PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
            SELECT DISTINCT ?parent ?child ?childLabel ?childComment WHERE {
                VALUES ?parent { ' . $values . ' }
                ?child ' . $relation . ' ?parent .
                ' . $constraintClause . '
                OPTIONAL { ?child rdfs:label ?childLabel FILTER(LANG(?childLabel) = "' . $language . '") }
                OPTIONAL { ?child rdfs:comment ?childComment FILTER(LANG(?childComment) = "' . $language . '") }
            } LIMIT ' . TREE_CHILDREN_LIMIT);

        $nextFrontier = [];
        foreach ($sparql['results']['bindings'] as $binding) {
            $parentUri = $binding['parent']['value'];
            $childUri = $binding['child']['value'];
            if (!isset($parentMap[$parentUri])) continue;

            if (!isset($parentMap[$parentUri]['children'][$childUri])) {
                $parentMap[$parentUri]['children'][$childUri] = [
                    'label' => $binding['childLabel']['value'] ?? null,
                    'comment' => $binding['childComment']['value'] ?? null,
                    'children' => []
                ];
                $parentMap[$childUri] = &$parentMap[$parentUri]['children'][$childUri];
                $nextFrontier[] = $childUri;
            }
        }
        // Cap frontier to avoid massive VALUES clauses for broad nodes like schema:Thing
        $frontier = array_slice($nextFrontier, 0, TREE_FRONTIER_CAP);
    }

    return $tree;
}

/**
 * Recursively print a tree node and its children as nested HTML <ul>/<li> elements.
 *
 * @param string $name    URI of the node
 * @param array  $values  Node data: ['label', 'comment', 'children' => [...]]
 */
function printTreeNode($name, $values)
{
    print '<li class="tree-edge">';
    print uriToLink($name, $values['label'], $values['comment']);
    if ($values['children']) {
        ksort($values['children']);
        print '<ul class="tree-node">';
        foreach ($values['children'] as $childName => $childValues) {
            printTreeNode($childName, $childValues);
        }
        print '</ul>';
    }

    print '</li>';
}

/**
 * Clean a class local name for display: decode _UXXXX_ unicode escapes,
 * strip _Qnnn Wikidata suffixes, replace underscores with spaces.
 *
 * @param string $name  Raw class local name
 * @return string  Human-readable label
 */
function cleanClassName($name)
{
    // Decode _UXXXX_ unicode escapes
    $name = preg_replace_callback('/_U([0-9A-Fa-f]{4,6})_/', function ($m) {
        return mb_chr(hexdec($m[1]), 'UTF-8');
    }, $name);
    // Strip _Qnnn Wikidata ID suffixes
    $name = preg_replace('/_Q\d+$/', '', $name);
    return str_replace('_', ' ', $name);
}

/**
 * Build the full class hierarchy graph for an entity by walking rdfs:subClassOf upward.
 * For non-class entities, starts from their rdf:type values. Traverses iteratively
 * with safety limits on depth (MAX_TAXONOMY_DEPTH) and edge count (MAX_TAXONOMY_EDGES).
 *
 * @param string $resource  Full URI of the entity or class
 * @param string $language  ISO language code for labels
 * @param bool   $isClass   If true, start from the resource itself; if false, start from its types
 * @return array  ['edges' => [['child' => uri, 'parent' => uri], ...], 'nodes' => [uri => ['label', 'url', 'isSchema']]]
 */
function getTaxonomyEdges($resource, $language, $isClass = false)
{
    // Iterative upward traversal instead of rdfs:subClassOf* transitive closure
    // Start from the entity's types (or the class itself), walk up level by level
    $nodes = [];
    $edges = [];

    if ($isClass) {
        $frontier = [$resource];
    } else {
        // Get direct types first
        $sparql = doSparqlQuery('PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            SELECT DISTINCT ?c WHERE { <' . $resource . '> rdf:type ?c } LIMIT 50');
        $frontier = [];
        foreach ($sparql['results']['bindings'] as $binding) {
            $frontier[] = $binding['c']['value'];
        }
    }

    $visited = [];
    $totalEdges = 0;

    for ($level = 0; $level < MAX_TAXONOMY_DEPTH && !empty($frontier) && $totalEdges < MAX_TAXONOMY_EDGES; $level++) {
        $toExpand = [];
        foreach ($frontier as $uri) {
            if (!isset($visited[$uri])) {
                $visited[$uri] = true;
                $toExpand[] = $uri;
            }
        }
        if (empty($toExpand)) break;

        $values = implode(' ', array_map(function($uri) { return '<' . $uri . '>'; }, $toExpand));
        $sparql = doSparqlQuery('PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
            SELECT DISTINCT ?s ?o ?sLabel ?oLabel WHERE {
                VALUES ?s { ' . $values . ' }
                ?s rdfs:subClassOf ?o .
                OPTIONAL { ?s rdfs:label ?sLabel FILTER(LANG(?sLabel) = "' . $language . '") }
                OPTIONAL { ?o rdfs:label ?oLabel FILTER(LANG(?oLabel) = "' . $language . '") }
            } LIMIT ' . MAX_TAXONOMY_EDGES);

        $nextFrontier = [];
        foreach ($sparql['results']['bindings'] as $binding) {
            $child = $binding['s']['value'];
            $parent = $binding['o']['value'];

            $edges[] = ['child' => $child, 'parent' => $parent];
            $totalEdges++;

            foreach ([['uri' => $child, 'labelKey' => 'sLabel'], ['uri' => $parent, 'labelKey' => 'oLabel']] as $item) {
                $uri = $item['uri'];
                if (!isset($nodes[$uri])) {
                    $hasLabel = isset($binding[$item['labelKey']]);
                    $label = $hasLabel
                        ? $binding[$item['labelKey']]['value']
                        : cleanClassName(preg_replace('`^.*/|^.*#`', '', $uri));
                    $isSchema = strpos($uri, 'http://schema.org/') === 0 || strpos($uri, 'http://bioschemas.org/') === 0;
                    $nodes[$uri] = ['label' => $label, 'url' => uriToUrl($uri), 'isSchema' => $isSchema, 'hasLabel' => $hasLabel];
                } elseif (isset($binding[$item['labelKey']]) && empty($nodes[$uri]['hasLabel'])) {
                    $nodes[$uri]['label'] = $binding[$item['labelKey']]['value'];
                    $nodes[$uri]['hasLabel'] = true;
                }
            }

            if (!isset($visited[$parent])) {
                $nextFrontier[] = $parent;
            }
        }
        $frontier = $nextFrontier;
    }

    foreach ($nodes as &$node) {
        unset($node['hasLabel']);
    }

    return ['edges' => $edges, 'nodes' => $nodes];
}

/**
 * Convert a tree node into all root-to-leaf paths (as arrays of HTML link strings).
 * Uses PHP generators (yield) for memory efficiency.
 *
 * @param string $name    URI of the node
 * @param array  $values  Node data: ['label', 'comment', 'children' => [...]]
 * @return Generator  Yields arrays of HTML link strings (leaf-first, root-last)
 */
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
