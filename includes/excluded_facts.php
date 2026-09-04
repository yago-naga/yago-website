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

// Legacy entity-abandonment marker emitted by YAGO 4.6.
const ENTITY_EXCLUSION_FILTER = "predicate = 'rdf:type' AND object = 'schema:Thing' "
    . "AND reason IN ('No valid type', 'No valid label')";

/** Resolve a URI-valued excluded-fact object, or null for literals and blank nodes. */
function resolveExcludedFactObjectUri($object)
{
    global $PREFIXES;

    if (preg_match('/^https?:\/\/[^\s<>"{}|\\\\^`]+$/u', $object)) {
        return $object;
    }
    if (!$object || $object[0] === '"' || strpos($object, '_:') === 0) {
        return null;
    }

    $parts = explode(':', $object, 2);
    if (count($parts) === 2 && isset($PREFIXES[$parts[0]])) {
        return $PREFIXES[$parts[0]] . $parts[1];
    }
    return null;
}

/**
 * Fetch excluded facts for an exact subject URI.
 *
 * Subjects that survived construction are generally changed from their source
 * URI to their YAGO URI, but callers must use getExcludedEntity() to
 * distinguish ordinary rejected facts from entity-abandonment records.
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
 * Return the recorded reasons for excluding an entity, or null when the URI
 * has no legacy entity-abandonment record.
 */
function getExcludedEntity(PDO $db, $uri)
{
    $stmt = $db->prepare(
        'SELECT DISTINCT reason, stage FROM excluded_facts WHERE subject = ? AND '
        . ENTITY_EXCLUSION_FILTER . ' ORDER BY stage, reason LIMIT 100'
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
 * Find which URIs in a list have legacy entity-abandonment records. The
 * indexed lookup is batched to avoid one query per displayed fact.
 *
 * @return array  Set represented as URI => true
 */
function findExcludedEntities(PDO $db, array $uris)
{
    $uris = array_values(array_unique(array_filter($uris)));
    if (!$uris) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($uris), '?'));
    $stmt = $db->prepare(
        'SELECT DISTINCT subject FROM excluded_facts WHERE subject IN (' . $placeholders . ') AND '
        . ENTITY_EXCLUSION_FILTER
    );
    $stmt->execute($uris);

    $result = [];
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $uri) {
        $result[$uri] = true;
    }
    return $result;
}

/** Build the local resource-page URL for a known excluded entity. */
function excludedEntityResourceUrl($uri)
{
    global $PREFIXES;

    foreach ($PREFIXES as $prefix => $uriStart) {
        if (strpos($uri, $uriStart) === 0) {
            $localName = substr($uri, strlen($uriStart));
            $resourceName = $prefix === 'yago' ? $localName : $prefix . ':' . $localName;
            return config('site_url') . '/resource/' . $resourceName;
        }
    }

    return config('site_url') . '/resource.php?resource=' . rawurlencode($uri);
}
