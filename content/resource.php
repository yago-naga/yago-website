<?php

require_once 'includes/sparql.php';

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

$shapeDesc = null;
$instancesCount = null;
if (in_array('http://www.w3.org/2002/07/owl#Class', $shapes)) {
    $instancesCount = intval(doSingleResultQuery('SELECT (COUNT(DISTINCT ?i) AS ?c) WHERE { ?i a/<http://www.w3.org/2000/01/rdf-schema#subClassOf>* <' . $resource . '> }')['value']);
    $shapeDesc = getShapeDescription($resource, Locale::getPrimaryLanguage($GLOBALS['locale']));
}

$usagesCount = null;
if (in_array('http://www.w3.org/2002/07/owl#ObjectProperty', $shapes) || in_array('http://www.w3.org/2002/07/owl#DatatypeProperty', $shapes)) {
    $usagesCount = intval(doSingleResultQuery('SELECT (COUNT(*) AS ?c) WHERE { ?s <' . $resource . '> ?o }')['value']);
}

?>
    <form id="search" class="row">
        <div class="col s5 input-field">
            <input name="search" id="my-search-text" type="text">
            <label for="my-search-text">YAGO identifier (e.g., Elvis_Presley)</label>
        </div>
        <div class="col s3" style="margin-top: 1.5rem;">
            <button onclick="alert('now'); location.assign('/resource/' + document.getElementById('my-search-text').value)" class="waves-effect waves-light btn">Look up</button>
        </div>
    </form>

<?php


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

if ($parentClasses['children']) {
    print '';
    foreach ($parentClasses['children'] as $rootValues) {
        foreach ($rootValues['children'] as $childName => $childValues) {
            foreach (treeToPaths($childName, $childValues) as $path) {
                print '<nav><div class="nav-wrapper"><div class="col s12">';
                foreach ($path as $element) {
                    print '<span class="breadcrumb">' . $element . '</span>';
                }
                print '<span class="breadcrumb"></span></div></div></nav>';
            }
        }
    }
}

if ($shapeDesc) {
    print '<div class="card"><div class="card-content"><span class="card-title">Possible properties</span>';
    print '<table><thead><tr><th scope="col">Property</th><th scope="col">Range</th><th scope="col">Cardinality</th><th scope="col">All usages</th></tr></thead><tbody>';
    foreach ($shapeDesc as $property => $values) {
        print '<tr><th scope="row">' . uriToLink($property, $values['label'], $values['comment']) . '</th><td><ul>';
        print implode(' or ', array_map('uriToLink', array_merge($values['node'], $values['datatype'])));
        if ($values['pattern']) {
            print ' matching ' . $values['pattern'];
        }
        $count = intval(doSingleResultQuery('SELECT (COUNT(*) AS ?c) WHERE { ?s <' . $property . '> ?o }')['value']);
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
displayPropertyValuesTable($propertyValues);
print '</div></div>';

$reversePropertyValues = describeEntity($resource, true);
if ($reversePropertyValues) {
    print '<div class="card"><div class="card-content"><span class="card-title">Incoming properties</span>';
    displayPropertyValuesTable($reversePropertyValues, 'Predicate', 'Subject');
    print '</div></div>';
}

if ($childrenClasses['children'] && next($childrenClasses['children'])['children']) {
    print '<div class="card"><div class="card-content"><span class="card-title">Child classes</span><ul class="tree-node">';
    foreach ($childrenClasses['children'] as $childName => $childValues) {
        printTreeNode($childName, $childValues);
    }
    print '</ul></div></div>';
}
