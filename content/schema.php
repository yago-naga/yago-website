<h1>Schema</h1>

<?php

require 'includes/sparql.php';

$shapeClasses = getClassesTree('http://schema.org/Thing', Locale::getPrimaryLanguage($locale), 10, false, 'a <http://www.w3.org/ns/shacl#NodeShape>');
print '<ul class="tree-node">';
foreach ($shapeClasses['children'] as $childName => $childValues) {
    printTreeNode($childName, $childValues);
}
print '</ul>';