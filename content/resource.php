
    <form id="my-search" class="row">
        <div class="col s5 input-field">
            <input name="search" id="my-search-text" type="text" autocomplete="off" data-autocomplete-url="<?php echo config('site_url'); ?>/api/autocomplete.php">
            <label for="my-search-text">Search for entities by name</label>
        </div>
        <div class="col s3" style="margin-top: 1.5rem;">
            <button class="waves-effect waves-light btn">Search</button>
        </div>
    </form>

<?php 

require_once 'includes/sparql.php';

if (isset($_GET['search']) && $_GET['search']) {

	$entityname = trim($_GET['search']);
	// Title-case before escaping so escape sequences aren't mangled
	$escaped = mb_convert_case($entityname, MB_CASE_TITLE);
	$escaped = str_replace(['\\', '"', "\n", "\r", "\t"], ['\\\\', '\\"', '\\n', '\\r', '\\t'], $escaped);
	$lang = Locale::getPrimaryLanguage($GLOBALS['locale']);

	$perPage = SEARCH_RESULTS_PER_PAGE;
	$page = max(1, intval($_GET['p'] ?? 1));
	$offset = ($page - 1) * $perPage;

	$sparql = doSparqlQuery(
		'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> '
		. 'PREFIX schema: <http://schema.org/> '
		. 'SELECT ?entity ?label ?comment ?image WHERE { '
		. '{ SELECT DISTINCT ?entity ?label ?siteLinks WHERE { '
		. '?entity rdfs:label ?label . '
		. 'FILTER((LANG(?label) = "' . $lang . '" || LANG(?label) = "mul") && STRSTARTS(?label, "' . $escaped . '")) '
		. 'OPTIONAL { ?entity <http://yago-knowledge.org/resource/siteLinks> ?siteLinks } '
		. '} ORDER BY DESC(?siteLinks) LIMIT ' . ($perPage + 1) . ' OFFSET ' . $offset . ' } '
		. 'OPTIONAL { ?entity rdfs:comment ?comment . FILTER(LANG(?comment) = "' . $lang . '") } '
		. 'OPTIONAL { ?entity schema:image ?image } '
		. '} ORDER BY DESC(?siteLinks)'
	);

	$entities = [];
	foreach ($sparql['results']['bindings'] as $binding) {
		$uri = $binding['entity']['value'];
		if (!isset($entities[$uri])) {
			$entities[$uri] = [
				'label' => $binding['label']['value'],
				'comment' => isset($binding['comment']) ? $binding['comment']['value'] : null,
				'image' => isset($binding['image']) ? $binding['image']['value'] : null,
			];
		} elseif (!$entities[$uri]['image'] && isset($binding['image'])) {
			$entities[$uri]['image'] = $binding['image']['value'];
		}
	}

	$hasMore = count($entities) > $perPage;
	if ($hasMore) {
		array_pop($entities);
	}
	$count = count($entities);

	print '<div class="card"><div class="card-content">';
	print '<span class="card-title">Search results for "' . htmlspecialchars($entityname) . '"</span>';

	if ($count === 0 && $page === 1) {
		print '<p>No entities found matching "' . htmlspecialchars($entityname) . '".</p>';
	} else {
		$first = $offset + 1;
		$last = $offset + $count;
		print '<p>Showing results ' . $first . '–' . $last . '</p>';
		print '<ul class="search-results">';
		foreach ($entities as $uri => $entity) {
			$url = uriToUrl($uri);
			print '<li class="search-result-item">';
			if ($entity['image']) {
				print '<div class="search-result-image"><img src="' . str_replace('http://', 'https://', htmlspecialchars($entity['image'])) . '" alt=""></div>';
			}
			print '<div class="search-result-content">';
			print '<a href="' . $url . '" class="search-result-label">' . htmlspecialchars($entity['label']) . '</a>';
			print '<span class="search-result-uri">' . uriToPrefixedName($uri) . '</span>';
			if ($entity['comment']) {
				$desc = $entity['comment'];
				if (mb_strlen($desc) > 200) {
					$desc = mb_substr($desc, 0, 200) . '…';
				}
				print '<p class="search-result-description">' . htmlspecialchars($desc) . '</p>';
			}
			print '</div>';
			print '</li>';
		}
		print '</ul>';

		$searchParam = urlencode($entityname);
		print '<div class="pagination-nav" style="display: flex; justify-content: space-between; margin-top: 1rem;">';
		if ($page > 1) {
			print '<a class="btn waves-effect waves-light" href="' . config('site_url') . '/resource?search=' . $searchParam . '&p=' . ($page - 1) . '">&laquo; Previous</a>';
		} else {
			print '<span></span>';
		}
		if ($hasMore) {
			print '<a class="btn waves-effect waves-light" href="' . config('site_url') . '/resource?search=' . $searchParam . '&p=' . ($page + 1) . '">Next &raquo;</a>';
		}
		print '</div>';
	}

	print '</div></div>';
	return;
}


if (!isset($_GET['resource']) || !$_GET['resource']) {
    include '404.php';
    return;
}

$resource = resolvePrefixedUri($_GET['resource']);
$propertyValues = describeEntity($resource);

if (!$propertyValues) {
    include '404.php';
    return;
}

$lang = Locale::getPrimaryLanguage($GLOBALS['locale']);
$resourceLabel = getValueInDisplayLanguage($propertyValues, 'http://www.w3.org/2000/01/rdf-schema#label');
$resourceDescription = getValueInDisplayLanguage($propertyValues, 'http://www.w3.org/2000/01/rdf-schema#comment');
// Targeted query for label/comment when not found in bulk data (common with large multilingual datasets)
if (!$resourceLabel || !$resourceDescription) {
    $langFilter = 'LANG(?v) = "' . $lang . '" || STRSTARTS(LANG(?v), "' . $lang . '-") || LANG(?v) = "mul"';
    $parts = [];
    if (!$resourceLabel) {
        $parts[] = '{ SELECT ?label WHERE { <' . $resource . '> rdfs:label ?v BIND(?v AS ?label) FILTER(' . str_replace('?v', '?v', $langFilter) . ') } LIMIT 1 }';
    }
    if (!$resourceDescription) {
        $parts[] = '{ SELECT ?comment WHERE { <' . $resource . '> rdfs:comment ?v BIND(?v AS ?comment) FILTER(' . $langFilter . ') } LIMIT 1 }';
    }
    $langQuery = doSparqlQuery('PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> SELECT ?label ?comment WHERE { '
        . implode(' ', $parts) . ' } LIMIT 1');
    if (!empty($langQuery['results']['bindings'])) {
        $b = $langQuery['results']['bindings'][0];
        if (!$resourceLabel && isset($b['label'])) $resourceLabel = $b['label']['value'];
        if (!$resourceDescription && isset($b['comment'])) $resourceDescription = $b['comment']['value'];
    }
}
$resourceLabel = $resourceLabel ?: uriToPrefixedName($resource);
$resourceDescription = $resourceDescription ?: '';
$GLOBALS['page_title'] = strip_tags($resourceLabel);

$sameAsLinks = [];
if (isset($propertyValues['http://www.w3.org/2002/07/owl#sameAs'])) {
    foreach ($propertyValues['http://www.w3.org/2002/07/owl#sameAs'] as $value) {
        foreach ($GLOBALS['SAME_AS_LABELS'] as $prefix => $label) {
            if (strpos($value['value'], $prefix) === 0) {
                $sameAsLinks[$label] = uriToUrl($value['value']);
            }
        }
    }
}
if (isset($propertyValues['http://schema.org/sameAs'])) {
    foreach ($propertyValues['http://schema.org/sameAs'] as $value) {
        foreach ($GLOBALS['SAME_AS_LABELS'] as $prefix => $label) {
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

$parentClasses = getClassesTree($resource, Locale::getPrimaryLanguage($GLOBALS['locale']), 4, false);

$isClass = in_array('http://www.w3.org/2002/07/owl#Class', $shapes) || in_array('http://www.w3.org/2000/01/rdf-schema#Class', $shapes)
    || isset($propertyValues['http://www.w3.org/2000/01/rdf-schema#subClassOf']);
$taxonomyData = getTaxonomyEdges($resource, Locale::getPrimaryLanguage($GLOBALS['locale']), $isClass);
$directTypes = [];
if (isset($propertyValues['http://www.w3.org/1999/02/22-rdf-syntax-ns#type'])) {
    foreach ($propertyValues['http://www.w3.org/1999/02/22-rdf-syntax-ns#type'] as $val) {
        if ($val['type'] === 'uri') $directTypes[] = $val['value'];
    }
}

$shapeDesc = null;
$instancesCount = null;
if (in_array('http://www.w3.org/2002/07/owl#Class', $shapes)) {
    $instancesCount = intval(doSingleResultQuery('PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> SELECT (COUNT(DISTINCT ?i) AS ?c) WHERE { ?i rdf:type ?t . ?t rdfs:subClassOf* <' . $resource . '> }')['value']);
    $shapeDesc = getShapeDescription($resource, Locale::getPrimaryLanguage($GLOBALS['locale']));
}

$usagesCount = null;
if (in_array('http://www.w3.org/2002/07/owl#ObjectProperty', $shapes) || in_array('http://www.w3.org/2002/07/owl#DatatypeProperty', $shapes)) {
    $usagesCount = intval(doSingleResultQuery('SELECT (COUNT(*) AS ?c) WHERE { ?s <' . $resource . '> ?o }')['value']);
}


print '<div class="card horizontal">';
print '<div class="card-stacked">';
print '<div class="card-content">';
if (strpos($resource, 'http://yago-knowledge.org/') === 0) {
    print '<span class="card-title">' . $resourceLabel . '</span>';
} else {
    print '<span class="card-title"><a href="' . $resource . '">' . uriToPrefixedName($resource) . '</a></span>';
}
print '<p>URI: <a href="' . uriToUrl($resource) . '">' . htmlspecialchars($resource) . '</a></p>';
print '<p>' . $resourceDescription . '</p>';
if ($shapes) print '<p>Shapes: ' . implode(', ', array_map('uriToLink', $shapes)) . '</p>';
if ($instancesCount !== null) {
    print '<p>Number of instances: ' . $GLOBALS['numberFormatter']->format($instancesCount) . '</a></p>';
}
if ($usagesCount !== null) {
    print '<p>Number of usages: ' . $GLOBALS['numberFormatter']->format($usagesCount) . '</a></p>';
}
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
    $imgSrc = str_replace('http://', 'https://', $resourceImage);
    print '<div class="image"><img src="' . $imgSrc . '" height="200"></div>';
}
print '</div>';

if (!empty($taxonomyData['edges'])):
?>
<div class="card" id="taxonomy-card">
  <div class="card-content">
    <span class="card-title">Class Hierarchy <i class="material-icons collapse-toggle" id="taxonomy-toggle">expand_less</i></span>
    <div id="taxonomy-dag" class="card-body"></div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    renderDag({
        containerId: 'taxonomy-dag',
        nodes: <?php echo json_encode($taxonomyData['nodes']); ?>,
        edges: <?php echo json_encode($taxonomyData['edges']); ?>,
        entityUri: <?php echo json_encode($resource); ?>,
        entityLabel: <?php echo json_encode(strip_tags($resourceLabel)); ?>,
        directTypes: <?php echo json_encode($directTypes); ?>,
        isClass: <?php echo json_encode($isClass); ?>,
        linkNodes: true
    });

    var toggle = document.getElementById('taxonomy-toggle');
    var cardContent = toggle.closest('.card-content');
    toggle.addEventListener('click', function() {
        cardContent.classList.toggle('collapsed');
        toggle.classList.toggle('collapsed');
        toggle.textContent = cardContent.classList.contains('collapsed') ? 'expand_more' : 'expand_less';
    });
});
</script>
<?php
endif;

if ($shapeDesc) {
    // Batch-fetch usage counts for all properties in one query
    $propUris = array_keys($shapeDesc);
    $valuesClause = implode(' ', array_map(function($u) { return '<' . $u . '>'; }, $propUris));
    $countResults = doSparqlQuery('SELECT ?p (COUNT(*) AS ?c) WHERE { VALUES ?p { ' . $valuesClause . ' } ?s ?p ?o } GROUP BY ?p');
    $propertyCounts = [];
    foreach ($countResults['results']['bindings'] as $binding) {
        $propertyCounts[$binding['p']['value']] = intval($binding['c']['value']);
    }

    print '<div class="card"><div class="card-content"><span class="card-title">Possible properties</span>';
    print '<table><thead><tr><th scope="col">Property</th><th scope="col">Range</th><th scope="col">Cardinality</th><th scope="col">All usages</th></tr></thead><tbody>';
    foreach ($shapeDesc as $property => $values) {
        print '<tr><th scope="row">' . uriToLink($property, $values['label'], $values['comment']) . '</th><td><ul>';
        print implode(' or ', array_map('uriToLink', array_merge($values['node'], $values['datatype'])));
        if ($values['pattern']) {
            print ' matching ' . $values['pattern'];
        }
        $count = $propertyCounts[$property] ?? 0;
        print '</ul></td><td>';
        if ($values['maxCount']) {
            print '≤ ' . $GLOBALS['numberFormatter']->format($values['maxCount']);
        }
        print '</td><td>' . $GLOBALS['numberFormatter']->format($count) . '</td></tr>';
    }
    print '</tbody></table>';
    print '</div></div>';
}

print '<div class="card"><div class="card-content"><span class="card-title">Properties</span>';
displayPropertyValuesTable($propertyValues, 'Predicate', 'Object', 'out', $resource);
print '</div></div>';

$reversePropertyValues = describeEntity($resource, true);
if ($reversePropertyValues) {
    print '<div class="card"><div class="card-content"><span class="card-title">Incoming properties</span>';
    displayPropertyValuesTable($reversePropertyValues, 'Predicate', 'Subject', 'in', $resource);
    print '</div></div>';
}

printPropertyValuesModal();

// Excluded facts - loaded on demand via AJAX
print '<div class="card" id="excluded-card" style="display:none"><div class="card-content collapsed">';
print '<span class="card-title">Excluded facts <i class="material-icons collapse-toggle collapsed" id="excluded-toggle">expand_more</i></span>';
print '<div class="card-body">';
print '<p style="color: #999; font-size: 0.9em;">These facts were removed during YAGO construction due to type or consistency checks.</p>';
print '<div id="excluded-table"></div>';
print '</div></div></div>';
print '<script>initExcludedFacts(' . json_encode($resource) . ', ' . json_encode(config('excluded_facts_api')) . ');</script>';

if ($isClass) {
    print '<div class="card" id="children-card" style="display:none"><div class="card-content collapsed">';
    print '<span class="card-title">Child classes <i class="material-icons collapse-toggle collapsed" id="children-toggle">expand_more</i></span>';
    print '<ul class="tree-node card-body" id="children-tree"></ul>';
    print '</div></div>';
    print '<script>initChildClasses(' . json_encode($resource) . ', ' . json_encode(Locale::getPrimaryLanguage($GLOBALS['locale'])) . ');</script>';
}
