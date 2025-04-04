<?php
$db = new SQLite3(__DIR__ . '../../db/mka.db');

// Start a transaction for faster execution
$db->exec('BEGIN TRANSACTION');

// Open the CSV file
$file = fopen('../backend/mka.csv', 'r');
$headers = fgetcsv($file);  // Skip the header row

// Prepare the SQL statement for upsert (INSERT OR REPLACE)
$stmt = $db->prepare("
    INSERT OR REPLACE INTO tracks (
        album, track_number, track_title, duration, lyrics, spotify_link, youtube_link, path, explicit, volume
    ) VALUES (
        :album, :track_number, :track_title, :duration, :lyrics, :spotify_link, :youtube_link, :path, :explicit, :volume
    )
");

// Loop through the CSV rows
while (($row = fgetcsv($file)) !== FALSE) {
    // Bind values to the SQL statement
    $stmt->bindValue(':album', $row[0]);
    $stmt->bindValue(':track_number', $row[1]);
    $stmt->bindValue(':track_title', $row[2]);
    $stmt->bindValue(':duration', $row[3]);
    $stmt->bindValue(':lyrics', $row[4]);
    $stmt->bindValue(':spotify_link', $row[5]);
    $stmt->bindValue(':youtube_link', $row[6]);
    $stmt->bindValue(':path', $row[7]);
    $stmt->bindValue(':explicit', $row[8] == 'TRUE' ? 1 : 0);  // Convert TRUE/FALSE to 1/0
    $stmt->bindValue(':volume', $row[9] == 'TRUE' ? 1 : 0);  // Convert TRUE/FALSE to 1/0

    // Execute the SQL statement
    $stmt->execute();
}

// Commit the transaction
$db->exec('COMMIT');

// Close the file and database connection
fclose($file);
$db->close();

echo "CSV data upserted successfully!";
?>
