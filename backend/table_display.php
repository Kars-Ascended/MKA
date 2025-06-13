<?php
// Open the SQLite database
$db = new SQLite3(__DIR__ . '../../db/mka.db');

// Build the SQL query with filters
$conditions = [];
$params = [];

// Add album filter for releases table
if (!empty($_GET['album'])) {
    $conditions[] = "r.title LIKE :album";
    $params[':album'] = '%' . $_GET['album'] . '%';
}

if (!empty($_GET['title'])) {
    $conditions[] = "s.TRACK_TITLE LIKE :title";
    $params[':title'] = '%' . $_GET['title'] . '%';
}

if (isset($_GET['explicit']) && $_GET['explicit'] !== '') {
    $conditions[] = "s.explicit = :explicit";
    $params[':explicit'] = (int)$_GET['explicit'];
}

if (isset($_GET['volume']) && $_GET['volume'] !== '') {
    $conditions[] = "s.volume = :volume";
    $params[':volume'] = (int)$_GET['volume'];
}

if (!empty($_GET['lyrics'])) {
    $conditions[] = "s.Lyrics LIKE :lyrics";
    $params[':lyrics'] = '%' . $_GET['lyrics'] . '%';
}

if (isset($_GET['hide_instrumental']) && $_GET['hide_instrumental'] === '1') {
    $conditions[] = "s.Lyrics NOT LIKE '%[instrumental]%'";
}

if (!empty($_GET['featured_artists'])) {
    $conditions[] = "s.featured_artists LIKE :featured_artists";
    $params[':featured_artists'] = '%' . $_GET['featured_artists'] . '%';
}

if (!empty($_GET['era'])) {
    $conditions[] = "s.era = :era";
    $params[':era'] = $_GET['era'];
}

if (isset($_GET['hide_non_main']) && $_GET['hide_non_main'] === '1') {
    $conditions[] = "c.is_main_release = 1";
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$query = "SELECT s.*, r.title as album_title, c.track_number 
          FROM songs s 
          LEFT JOIN connections c ON s.song_ID = c.song_ID 
          LEFT JOIN releases r ON c.release_ID = r.release_ID 
          $where 
          ORDER BY r.release_ID, c.track_number";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? SQLITE3_INTEGER : SQLITE3_TEXT);
}

// Execute query
$results = $stmt->execute();
?>