<?php
// Open the SQLite database
$db = new SQLite3(__DIR__ . '../../db/mka.db');

// Path to your CSV file - you'll need to specify the correct path
$csvFile = __DIR__ . '/../backend/mka.csv';

if (!file_exists($csvFile)) {
    die("CSV file not found at: $csvFile");
}

// Read CSV file
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Skip header row
    $header = fgetcsv($handle);
    
    // Prepare the SQL statement
    $stmt = $db->prepare('INSERT OR REPLACE INTO tracks 
        (album, track_number, track_title, duration, lyrics, spotify_link, 
        youtube_link, explicit, volume, featured_artists, discog, release_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    
    // Read data rows
    while (($data = fgetcsv($handle)) !== FALSE) {
        // Convert 'true'/'false' strings to integers for boolean fields
        $explicit = strtolower(trim($data[7])) === 'true' ? 1 : 0;
        $volume = strtolower(trim($data[8])) === 'true' ? 1 : 0;
        
        // Bind parameters
        $stmt->bindParam(1, $data[0]);  // album
        $stmt->bindParam(2, $data[1]);  // track_number
        $stmt->bindParam(3, $data[2]);  // track_title
        $stmt->bindParam(4, $data[3]);  // duration
        $stmt->bindParam(5, $data[4]);  // lyrics
        $stmt->bindParam(6, $data[5]);  // spotify_link
        $stmt->bindParam(7, $data[6]);  // youtube_link
        $stmt->bindParam(8, $explicit); // explicit
        $stmt->bindParam(9, $volume);   // volume
        $stmt->bindParam(10, $data[9]); // featured_artists
        $stmt->bindParam(11, $data[10]); // discog
        $stmt->bindParam(12, $data[11]); // release_date
        
        // Execute the statement
        $result = $stmt->execute();
        
        if (!$result) {
            echo "Error inserting row: " . implode(",", $data) . "\n";
        }
    }
    
    fclose($handle);
    echo "Data import completed successfully!";
} else {
    echo "Error opening CSV file";
}

// Close the database connection
$db->close();
?>