
    <form id="my-search" class="row">
        <div class="col s5 input-field">
            <input name="search" id="my-search-text" type="text">
            <label for="my-search-text">Search (use correct uppercase for named entities)</label>
        </div>
        <div class="col s3" style="margin-top: 1.5rem;">
            <button class="waves-effect waves-light btn">Search</button>
        </div>
    </form>

<?php 

require_once 'includes/sparql.php';

if (isset($_GET['search']) && $_GET['search']) {

	$entityname = $_GET['search'];

	print "Entities called " . $entityname . ":\n<ul>\n";

	$sparql = doSparqlQuery('PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> SELECT DISTINCT ?s WHERE { ?s rdfs:label "' . $entityname . '"@en } LIMIT 100');
		foreach ($sparql['results']['bindings'] as $binding) {
			$entity = $binding['s']['value'];
			print "<li><a href='" . $entity ."'>" . uriToPrefixedName($entity) ."</a>\n";
		}

	print "</ul>";
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

$resourceLabel = getValueInDisplayLanguage($propertyValues, 'http://www.w3.org/2000/01/rdf-schema#label')
    ?: uriToPrefixedName($resource);

$resourceDescription = getValueInDisplayLanguage($propertyValues, 'http://www.w3.org/2000/01/rdf-schema#comment') ?: '';

$sameAsLinks = [
    'Graph visualization' => uriToUrl($resource, 'graph')
];
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

$childrenClasses = getClassesTree($resource, Locale::getPrimaryLanguage($GLOBALS['locale']), 3, false);
$parentClasses = getClassesTree($resource, Locale::getPrimaryLanguage($GLOBALS['locale']), 4, true);

$isClass = in_array('http://www.w3.org/2002/07/owl#Class', $shapes) || in_array('http://www.w3.org/2000/01/rdf-schema#Class', $shapes);
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
print '<p>' . $resourceDescription . '</p>';
print '<p>Shapes: ' . implode(', ', array_map('uriToLink', $shapes)) . '</p>';
print '<p>URI: <a href=' . htmlspecialchars($resource) . '>' . htmlspecialchars($resource) . '</a></p>';
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
    print '<div class="image"><img src="' . $resourceImage . '" height="200"></div>';
}
print '</div>';

if (!empty($taxonomyData['edges'])):
?>
<div class="card" id="taxonomy-card">
  <div class="card-content">
    <span class="card-title">Class Hierarchy <i class="material-icons collapse-toggle" id="taxonomy-toggle">expand_less</i></span>
    <div id="taxonomy-dag"></div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof dagre === 'undefined') return;

    var taxonomyNodes = <?php echo json_encode($taxonomyData['nodes']); ?>;
    var taxonomyEdges = <?php echo json_encode($taxonomyData['edges']); ?>;
    var entityUri = <?php echo json_encode($resource); ?>;
    var entityLabel = <?php echo json_encode(strip_tags($resourceLabel)); ?>;
    var directTypes = <?php echo json_encode($directTypes); ?>;
    var isClass = <?php echo json_encode($isClass); ?>;

    var g = new dagre.graphlib.Graph();
    g.setGraph({ rankdir: 'BT', nodesep: 20, ranksep: 40, marginx: 15, marginy: 15 });
    g.setDefaultEdgeLabel(function() { return {}; });

    // Measure text widths using a temporary SVG
    var tmpSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    tmpSvg.style.position = 'absolute';
    tmpSvg.style.visibility = 'hidden';
    document.body.appendChild(tmpSvg);
    var tmpText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    tmpText.setAttribute('font-family', 'Open Sans, sans-serif');
    tmpText.setAttribute('font-size', '12');
    tmpSvg.appendChild(tmpText);

    function measureText(label) {
        tmpText.textContent = label;
        return tmpText.getBBox().width;
    }

    for (var uri in taxonomyNodes) {
        var node = taxonomyNodes[uri];
        var w = measureText(node.label) + 30;
        g.setNode(uri, { label: node.label, width: Math.max(w, 50), height: 30, url: node.url, isSchema: node.isSchema });
    }

    if (!isClass) {
        var ew = measureText(entityLabel) + 30;
        g.setNode(entityUri, { label: entityLabel, width: Math.max(ew, 50), height: 30, url: null, isEntity: true });
        for (var i = 0; i < directTypes.length; i++) {
            if (g.hasNode(directTypes[i])) {
                g.setEdge(entityUri, directTypes[i]);
            }
        }
    }

    for (var i = 0; i < taxonomyEdges.length; i++) {
        g.setEdge(taxonomyEdges[i].child, taxonomyEdges[i].parent);
    }

    document.body.removeChild(tmpSvg);

    dagre.layout(g);

    var graph = g.graph();
    var svgWidth = graph.width;
    var svgHeight = graph.height;

    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + svgWidth + '" height="' + svgHeight + '">';
    svg += '<defs><marker id="dag-arrow" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto">';
    svg += '<path d="M 0 0 L 10 5 L 0 10 z" fill="#9e9e9e" opacity="0.5"/></marker></defs>';

    // Render edges
    g.edges().forEach(function(e) {
        var edge = g.edge(e);
        var points = edge.points;
        if (points && points.length >= 2) {
            var d = 'M ' + points[0].x + ' ' + points[0].y;
            for (var j = 1; j < points.length; j++) {
                d += ' L ' + points[j].x + ' ' + points[j].y;
            }
            svg += '<g class="dag-edge"><path d="' + d + '" fill="none" stroke="#9e9e9e" stroke-width="1.2" marker-end="url(#dag-arrow)"/></g>';
        }
    });

    // Render nodes
    g.nodes().forEach(function(v) {
        var node = g.node(v);
        var x = node.x - node.width / 2;
        var y = node.y - node.height / 2;
        var fill, stroke, textFill;

        if (node.isEntity) {
            fill = '#02aed7'; stroke = '#0288a7'; textFill = '#ffffff';
        } else if (directTypes.indexOf(v) !== -1) {
            fill = '#e8f5e9'; stroke = '#43a047'; textFill = '#2e7d32';
        } else if (node.isSchema) {
            fill = '#fff3e0'; stroke = '#ef6c00'; textFill = '#e65100';
        } else {
            fill = '#e3f2fd'; stroke = '#1565c0'; textFill = '#0d47a1';
        }

        var hasLink = !!node.url;

        svg += '<g class="dag-node">';
        if (hasLink) svg += '<a href="' + node.url + '">';
        svg += '<rect x="' + x + '" y="' + y + '" width="' + node.width + '" height="' + node.height + '" rx="4" ry="4" fill="' + fill + '" stroke="' + stroke + '" stroke-width="1.5"/>';
        svg += '<text x="' + node.x + '" y="' + (node.y + 5) + '" text-anchor="middle" dominant-baseline="middle" font-family="Open Sans, sans-serif" font-size="12" fill="' + textFill + '">' + node.label.replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</text>';
        if (hasLink) svg += '</a>';
        svg += '</g>';
    });

    svg += '</svg>';
    document.getElementById('taxonomy-dag').innerHTML = svg;

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

$nextChild = $childrenClasses['children'] ? next($childrenClasses['children']) : false;
if ($nextChild && $nextChild['children']) {
    print '<div class="card"><div class="card-content"><span class="card-title">Child classes</span><ul class="tree-node">';
    foreach ($childrenClasses['children'] as $childName => $childValues) {
        printTreeNode($childName, $childValues);
    }
    print '</ul></div></div>';
}
