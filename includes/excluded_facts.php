<?php
/**
 * Access helpers for facts and entities excluded during YAGO construction.
 */

/**
 * Open the excluded-facts database in read-only mode.
 *
 * @param string|null $dbPath  Optional database path, primarily for tests
 * @return PDO|null            Null when the database has not been installed
 */
function openExcludedFactsDatabase($dbPath = null)
{
    $dbPath = $dbPath ?: __DIR__ . '/../data/excluded_facts.db';
    if (!file_exists($dbPath)) {
        return null;
    }

    $db = new PDO('sqlite:' . $dbPath, null, null, [PDO::SQLITE_ATTR_OPEN_FLAGS => PDO::SQLITE_OPEN_READONLY]);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

/** Return whether a URI identifies a Wikidata item. */
function isWikidataEntityUri($uri)
{
    return preg_match('/^http:\/\/www\.wikidata\.org\/entity\/Q[1-9][0-9]*$/', $uri) === 1;
}

/**
 * Fetch excluded facts for an exact subject URI.
 *
 * During database generation, subjects that survived construction are changed
 * from their Wikidata URI to their YAGO URI. An exact wd: subject therefore
 * represents an entity for which no surviving YAGO identifier was generated.
 */
function getExcludedFacts(PDO $db, $subject, $limit = 100)
{
    $limit = max(1, intval($limit));
    $stmt = $db->prepare(
        'SELECT predicate, object, reason, stage FROM excluded_facts WHERE subject = ? LIMIT ' . $limit
    );
    $stmt->execute([$subject]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Return the recorded reasons for excluding a Wikidata entity, or null when
 * the URI is not a known excluded entity.
 */
function getExcludedEntity(PDO $db, $uri)
{
    if (!isWikidataEntityUri($uri)) {
        return null;
    }

    $stmt = $db->prepare(
        'SELECT DISTINCT reason, stage FROM excluded_facts WHERE subject = ? ORDER BY stage, reason LIMIT 100'
    );
    $stmt->execute([$uri]);
    $reasons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$reasons) {
        return null;
    }

    return [
        'uri' => $uri,
        'reasons' => $reasons,
    ];
}

/**
 * Find which Wikidata URIs in a list have exact subject records in the
 * exclusion database. The indexed lookup is batched to avoid one query per
 * displayed fact.
 *
 * @return array  Set represented as URI => true
 */
function findExcludedWikidataEntities(PDO $db, array $uris)
{
    $uris = array_values(array_unique(array_filter($uris, 'isWikidataEntityUri')));
    if (!$uris) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($uris), '?'));
    $stmt = $db->prepare(
        'SELECT DISTINCT subject FROM excluded_facts WHERE subject IN (' . $placeholders . ')'
    );
    $stmt->execute($uris);

    $result = [];
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $uri) {
        $result[$uri] = true;
    }
    return $result;
}

/** Build the local resource-page URL for a known excluded Wikidata entity. */
function excludedEntityResourceUrl($uri)
{
    return config('site_url') . '/resource/wd:' . substr($uri, strlen('http://www.wikidata.org/entity/'));
}
