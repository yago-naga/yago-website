<?php

require_once 'includes/config.php';
require_once 'includes/sparql.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$subject = trim($_GET['subject'] ?? '');
if (!$subject) {
    echo json_encode(['error' => 'Missing subject parameter']);
    exit;
}

$dbPath = __DIR__ . '/data/excluded_facts.db';
if (!file_exists($dbPath)) {
    echo json_encode(['facts' => []]);
    exit;
}

try {
    $db = new PDO('sqlite:' . $dbPath, null, null, [PDO::SQLITE_ATTR_OPEN_FLAGS => PDO::SQLITE_OPEN_READONLY]);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare('SELECT predicate, object, reason, stage FROM excluded_facts WHERE subject = ? LIMIT 100');
    $stmt->execute([$subject]);
    $facts = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $isFullPredUri = strpos($row['predicate'], 'http://') === 0 || strpos($row['predicate'], 'https://') === 0;
        $resolvedPred = $isFullPredUri ? $row['predicate'] : resolvePrefixedUri($row['predicate']);
        $row['predicate_display'] = $isFullPredUri ? uriToPrefixedName($row['predicate']) : htmlspecialchars($row['predicate']);
        $row['predicate_url'] = uriToUrl($resolvedPred);
        $isFullUri = strpos($row['object'], 'http://') === 0 || strpos($row['object'], 'https://') === 0;
        $resolvedObj = $isFullUri ? $row['object'] : resolvePrefixedUri($row['object']);
        $isPrefixed = !$isFullUri && strpos($row['object'], ':') !== false && $resolvedObj !== $row['object'];
        if ($isFullUri) {
            $row['object_display'] = uriToPrefixedName($row['object']);
            $row['object_url'] = uriToUrl($row['object']);
        } elseif ($isPrefixed) {
            $row['object_display'] = htmlspecialchars($row['object']);
            $row['object_url'] = uriToUrl($resolvedObj);
        } else {
            $row['object_display'] = htmlspecialchars($row['object']);
            $row['object_url'] = null;
        }
        $facts[] = $row;
    }
    echo json_encode(['facts' => $facts]);
} catch (PDOException $e) {
    echo json_encode(['facts' => [], 'error' => 'Database unavailable']);
}
