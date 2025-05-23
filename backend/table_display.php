<?php
// Open the SQLite database
$db = new SQLite3(__DIR__ . '../../db/mka.db');

// Build the SQL query with filters
$conditions = [];
$params = [];

if (!empty($_GET['album'])) {
    $conditions[] = "album LIKE :album";
    $params[':album'] = '%' . $_GET['album'] . '%';
}

if (isset($_GET['explicit']) && $_GET['explicit'] !== '') {
    $conditions[] = "explicit = :explicit";
    $params[':explicit'] = (int) $_GET['explicit'];  // Convert to integer (0 or 1)
}

if (isset($_GET['volume']) && $_GET['volume'] !== '') {
    $conditions[] = "volume = :volume";
    $params[':volume'] = (int) $_GET['volume'];  // Convert to integer (0 or 1)
}

if (!empty($_GET['title'])) {
    $conditions[] = "track_title LIKE :title";
    $params[':title'] = '%' . $_GET['title'] . '%';
}

if (!empty($_GET['lyrics'])) {
    $conditions[] = "lyrics LIKE :lyrics";
    $params[':lyrics'] = '%' . $_GET['lyrics'] . '%';
}

if (isset($_GET['hide_instrumental']) && $_GET['hide_instrumental'] === '1') {
    $conditions[] = "lyrics NOT LIKE '%[instrumental]%'";
}

if (!empty($_GET['featured_artists'])) {
    $conditions[] = "featured_artists LIKE :featured_artists";
    $params[':featured_artists'] = '%' . $_GET['featured_artists'] . '%';
}

if (!empty($_GET['discog'])) {
    $conditions[] = "discog = :discog";
    $params[':discog'] = $_GET['discog'];
}

if (!empty($_GET['release_date'])) {
    $conditions[] = "release_date = :release_date";
    $params[':release_date'] = $_GET['release_date'];
}

if (isset($_GET['has_features'])) {
    if ($_GET['has_features'] === '1') {
        $conditions[] = "featured_artists IS NOT NULL AND featured_artists != '' AND featured_artists != 'FALSE'";
    } else if ($_GET['has_features'] === '0') {
        $conditions[] = "(featured_artists IS NULL OR featured_artists = '' OR featured_artists = 'FALSE')";
    }
}

// Combine conditions into SQL query
$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
$query = "SELECT * FROM tracks $where ORDER BY album, track_number";

// Prepare the query
$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? SQLITE3_INTEGER : SQLITE3_TEXT);
}

// Execute query
$results = $stmt->execute();
?>