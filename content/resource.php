<?php

require 'includes/sparql.php';

if (!isset($_GET['resource']) || !$_GET['resource']) {
    include '404.php';
    return;
}

$resourceParts = explode(':', wfUrlencode($_GET['resource']), 2);
if (count($resourceParts) === 2 && isset($PREFIXES[$resourceParts[0]])) {
    $resource = $PREFIXES[$resourceParts[0]] . $resourceParts[1];
} else {
    $resource = 'http://yago-knowledge.org/resource/' . implode(':', $resourceParts);
}

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

$childrenClasses = getClassesTree($resource, Locale::getPrimaryLanguage($locale), 3, false);
$parentClasses = getClassesTree($resource, Locale::getPrimaryLanguage($locale), 4, true);

$shapeDesc = null;
$instancesCount = null;
if (in_array('http://www.w3.org/2002/07/owl#Class', $shapes)) {
    $instancesCount = intval(doSingleResultQuery('SELECT (COUNT(?i) AS ?c) WHERE { ?i a/<http://www.w3.org/2000/01/rdf-schema#subClassOf>* <' . $resource . '> }')['value']);
    $shapeDesc = getShapeDescription($resource, Locale::getPrimaryLanguage($locale));
}

$usagesCount = null;
if (in_array('http://www.w3.org/2002/07/owl#ObjectProperty', $shapes) || in_array('http://www.w3.org/2002/07/owl#DatatypeProperty', $shapes)) {
    $usagesCount = intval(doSingleResultQuery('SELECT (COUNT(*) AS ?c) WHERE { ?s <' . $resource . '> ?o }')['value']);
}

print '<div class="card horizontal">';
print '<div class="card-stacked">';
print '<div class="card-content">';
print '<span class="card-title">' . $resourceLabel . '</span>';
print '<p>' . $resourceDescription . '</p>';
print '<p>Shapes: ' . implode(', ', array_map('uriToLink', $shapes)) . '</p>';
print '<p>URI: <a href=' . htmlspecialchars($resource) . '>' . htmlspecialchars($resource) . '</a></p>';
if ($instancesCount !== null) {
    print '<p>Number of instances: ' . $numberFormatter->format($instancesCount) . '</a></p>';
}
if ($usagesCount !== null) {
    print '<p>Number of usages: ' . $numberFormatter->format($usagesCount) . '</a></p>';
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
        if($values['maxCount']) {
            print '≤ ' . $numberFormatter->format($values['maxCount']);
        }
        print '</td><td>' . $numberFormatter->format($count) . '</td></tr>';
    }
    print '</tbody></table>';
    print '</div></div>';
}

print '<div class="card"><div class="card-content"><span class="card-title">Properties</span>';
displayPropertyValuesTable($propertyValues);
print '</div></div>';

$reversePropertyValues = describeEntity($resource, $locale, true);
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
