<?php
// Open the SQLite database
$db = new SQLite3(__DIR__ . '../../db/mka.db');

// Build the SQL query with filters
$conditions = [];
$params = [];

if (!empty($_GET['term'])) {
    $conditions[] = "term LIKE :term";
    $params[':term'] = '%' . $_GET['term'] . '%';
}
if (!empty($_GET['description'])) {
    $conditions[] = "description LIKE :description";
    $params[':description'] = '%' . $_GET['description'] . '%';
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$query = "SELECT * FROM Dictionary $where ORDER BY term COLLATE NOCASE ASC";
$stmt = $db->prepare($query);

foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, SQLITE3_TEXT);
}

$results = $stmt->execute();
?>