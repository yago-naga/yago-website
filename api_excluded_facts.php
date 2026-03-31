<?php

require_once 'includes/config.php';
require_once 'includes/sparql.php';

header('Content-Type: application/json');

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
    $facts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['facts' => $facts]);
} catch (PDOException $e) {
    echo json_encode(['facts' => [], 'error' => 'Database unavailable']);
}
