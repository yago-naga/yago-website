<?php

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/sparql.php';
require_once __DIR__ . '/../includes/excluded_facts.php';

function assertSameValue($expected, $actual, $message)
{
    if ($expected !== $actual) {
        fwrite(STDERR, $message . "\nExpected: " . var_export($expected, true)
            . "\nActual: " . var_export($actual, true) . "\n");
        exit(1);
    }
}

$db = new PDO('sqlite::memory:');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec('CREATE TABLE excluded_facts (subject TEXT, predicate TEXT, object TEXT, reason TEXT, stage TEXT)');
$insert = $db->prepare('INSERT INTO excluded_facts VALUES (?, ?, ?, ?, ?)');

$q3739104 = 'http://www.wikidata.org/entity/Q3739104';
$q42 = 'http://www.wikidata.org/entity/Q42';
$q31 = 'http://www.wikidata.org/entity/Q31';
$douglasAdams = 'http://yago-knowledge.org/resource/Douglas_Adams';
$administrativeArea = 'http://schema.org/AdministrativeArea';
$removedExternalEntity = 'https://example.org/removed';

$insert->execute([
    $q3739104,
    'rdf:type',
    'schema:Thing',
    'No valid type',
    '03-make-facts',
]);
$insert->execute([
    $douglasAdams,
    'yago:deathCause',
    'wd:Q3739104',
    'Object not in YAGO',
    '04-make-typecheck',
]);
$insert->execute([
    $q31,
    'rdf:type',
    'schema:Country',
    'Shortcut',
    '03-make-facts',
]);
$insert->execute([
    $administrativeArea,
    'yago:partOf',
    'schema:Thing',
    'Domain check failed',
    '04-make-typecheck',
]);
$insert->execute([
    $removedExternalEntity,
    'rdf:type',
    'schema:Thing',
    'No valid label',
    '03-make-facts',
]);

$entity = getExcludedEntity($db, $q3739104);
assertSameValue('No valid type', $entity['reasons'][0]['reason'], 'Known excluded entity should expose its reason.');
assertSameValue(null, getExcludedEntity($db, $q42), 'An unknown Wikidata ID must not become a tombstone.');
assertSameValue(null, getExcludedEntity($db, $douglasAdams), 'An ordinary excluded fact must not create a tombstone.');
assertSameValue(null, getExcludedEntity($db, $q31), 'A non-sentinel rdf:type exclusion must not create a tombstone.');
assertSameValue(null, getExcludedEntity($db, $administrativeArea), 'An ordinary schema.org exclusion must not create a tombstone.');
assertSameValue('No valid label', getExcludedEntity($db, $removedExternalEntity)['reasons'][0]['reason'], 'Entity tombstones must work outside Wikidata.');
assertSameValue($q42, uriToUrl($q42), 'Ordinary Wikidata links must remain external.');
assertSameValue($q3739104, resolveExcludedFactObjectUri('wd:Q3739104'), 'Known prefixed objects should resolve to URIs.');
assertSameValue(null, resolveExcludedFactObjectUri('"70"^^xsd:decimal'), 'Typed literals must not become tombstone links.');
assertSameValue(null, resolveExcludedFactObjectUri('_:value'), 'Blank nodes must not become tombstone links.');

$excluded = findExcludedEntities($db, [$q3739104, $q42, $q3739104, $q31, $administrativeArea, $removedExternalEntity]);
assertSameValue([$q3739104 => true, $removedExternalEntity => true], $excluded, 'Only entity-abandonment records should be linked internally.');
assertSameValue('/resource/wd:Q3739104', excludedEntityResourceUrl($q3739104), 'Tombstone URL should use the existing resource route.');
assertSameValue('/resource.php?resource=https%3A%2F%2Fexample.org%2Fremoved', excludedEntityResourceUrl($removedExternalEntity), 'Unknown namespaces should use the full-URI resource route.');
assertSameValue($removedExternalEntity, resolvePrefixedUri($removedExternalEntity), 'The resource route should accept a safe full URI.');

echo "Excluded facts tests passed.\n";

if (isset($argv[1])) {
    $productionDb = openExcludedFactsDatabase($argv[1]);
    if (!$productionDb) {
        fwrite(STDERR, "Excluded facts database not found: " . $argv[1] . "\n");
        exit(1);
    }

    $entity = getExcludedEntity($productionDb, $q3739104);
    assertSameValue('No valid type', $entity['reasons'][0]['reason'], 'Production Q3739104 reason changed unexpectedly.');
    assertSameValue(null, getExcludedEntity($productionDb, $q42), 'Production Q42 must not be treated as excluded.');
    assertSameValue(null, getExcludedEntity($productionDb, $administrativeArea), 'A surviving schema.org resource must not be inferred from ordinary exclusions.');
    echo "Production database checks passed.\n";
}
