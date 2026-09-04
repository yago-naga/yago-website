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
$douglasAdams = 'http://yago-knowledge.org/resource/Douglas_Adams';

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

$entity = getExcludedEntity($db, $q3739104);
assertSameValue('No valid type', $entity['reasons'][0]['reason'], 'Known excluded entity should expose its reason.');
assertSameValue(null, getExcludedEntity($db, $q42), 'A surviving or unknown Wikidata ID must not become a tombstone.');
assertSameValue(null, getExcludedEntity($db, $douglasAdams), 'YAGO URIs must not become Wikidata tombstones.');
assertSameValue($q42, uriToUrl($q42), 'Ordinary Wikidata links must remain external.');

$excluded = findExcludedWikidataEntities($db, [$q3739104, $q42, $q3739104]);
assertSameValue([$q3739104 => true], $excluded, 'Only exact excluded Wikidata subjects should be linked internally.');
assertSameValue('/resource/wd:Q3739104', excludedEntityResourceUrl($q3739104), 'Tombstone URL should use the existing resource route.');

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
    echo "Production database checks passed.\n";
}
