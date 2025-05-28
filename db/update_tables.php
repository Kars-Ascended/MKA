<?php
$db = new SQLite3(__DIR__ . '../../db/mka.db');

// Import songs
$songsFile = __DIR__ . '/../data/songs.csv';
if (($handle = fopen($songsFile, "r")) !== FALSE) {
    $header = fgetcsv($handle);
    
    $stmt = $db->prepare('INSERT OR REPLACE INTO songs 
        (song_ID, TRACK_TITLE, DURATION, Lyrics, spotify_link, youtube_link, 
        explicit, volume, featured_artists, discog, release_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        $explicit = strtolower(trim($data[6])) === 'true' ? 1 : 0;
        $volume = strtolower(trim($data[7])) === 'true' ? 1 : 0;
        
        $stmt->bindValue(1, $data[0]); // song_ID
        $stmt->bindValue(2, $data[1]); // TRACK_TITLE
        $stmt->bindValue(3, $data[2]); // DURATION
        $stmt->bindValue(4, $data[3]); // Lyrics
        $stmt->bindValue(5, $data[4]); // spotify_link
        $stmt->bindValue(6, $data[5]); // youtube_link
        $stmt->bindValue(7, $explicit); // explicit
        $stmt->bindValue(8, $volume);   // volume
        $stmt->bindValue(9, $data[8]);  // featured_artists
        $stmt->bindValue(10, $data[9]); // discog
        $stmt->bindValue(11, $data[10]); // release_date
        
        $result = $stmt->execute();
    }
    fclose($handle);
}

// Import releases
$releasesFile = __DIR__ . '/../data/releases.csv';
if (($handle = fopen($releasesFile, "r")) !== FALSE) {
    $header = fgetcsv($handle);
    
    $stmt = $db->prepare('INSERT OR REPLACE INTO releases 
        (release_ID, title, type, release_date)
        VALUES (?, ?, ?, ?)');
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        $stmt->bindValue(1, $data[0]); // release_ID
        $stmt->bindValue(2, $data[1]); // title
        $stmt->bindValue(3, $data[2]); // type
        $stmt->bindValue(4, $data[3]); // release_date
        
        $result = $stmt->execute();
    }
    fclose($handle);
}

// Import connections
$connectionsFile = __DIR__ . '/../data/connections.csv';
if (($handle = fopen($connectionsFile, "r")) !== FALSE) {
    $header = fgetcsv($handle);
    
    $stmt = $db->prepare('INSERT OR REPLACE INTO connections 
        (song_ID, release_ID, track_number)
        VALUES (?, ?, ?)');
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        if (!empty($data[0]) && !empty($data[1])) { // Only import if both IDs exist
            $stmt->bindValue(1, $data[0]); // song_ID
            $stmt->bindValue(2, $data[1]); // release_ID
            $stmt->bindValue(3, $data[2]); // track_number
            
            $result = $stmt->execute();
        }
    }
    fclose($handle);
}

$db->close();
echo "Data import completed successfully!";
?>