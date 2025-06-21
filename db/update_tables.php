<?php
$db = new SQLite3(__DIR__ . '../../db/mka.db');

// Import songs
$songsFile = __DIR__ . '/../data/songs.csv';
if (($handle = fopen($songsFile, "r")) !== FALSE) {
    $header = fgetcsv($handle);
    
    $stmt = $db->prepare('INSERT OR REPLACE INTO songs 
        (song_ID, TRACK_TITLE, DURATION, Lyrics, spotify_link, youtube_link, 
        explicit, volume, featured_artists, era, sub_era, release_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    
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
        $stmt->bindValue(10, $data[9]); // era
        $stmt->bindValue(11, $data[10]); // sub_era
        $stmt->bindValue(12, $data[11]); // release_date
        
        $result = $stmt->execute();
    }
    fclose($handle);
}

// Import releases
$releasesFile = __DIR__ . '/../data/releases.csv';
if (($handle = fopen($releasesFile, "r")) !== FALSE) {
    $header = fgetcsv($handle);
    
    $stmt = $db->prepare('INSERT OR REPLACE INTO releases 
        (release_ID, title, type, release_date, era, sub_era)
        VALUES (?, ?, ?, ?, ?, ?)');
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        $stmt->bindValue(1, $data[0]); // release_ID
        $stmt->bindValue(2, $data[1]); // title
        $stmt->bindValue(3, $data[2]); // type
        $stmt->bindValue(4, $data[3]); // release_date
        $stmt->bindValue(5, $data[4]); // era
        $stmt->bindValue(6, $data[5]); // sub_era
        
        $result = $stmt->execute();
    }
    fclose($handle);
}

// Import connections
$connectionsFile = __DIR__ . '/../data/connections.csv';
if (($handle = fopen($connectionsFile, "r")) !== FALSE) {
    $header = fgetcsv($handle);
    
    $stmt = $db->prepare('INSERT OR REPLACE INTO connections 
        (song_ID, release_ID, track_number, is_main_release)
        VALUES (?, ?, ?, ?)');
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        if (!empty($data[0]) && !empty($data[1])) { // Only import if both IDs exist
            $stmt->bindValue(1, $data[0]); // song_ID
            $stmt->bindValue(2, $data[1]); // release_ID
            $stmt->bindValue(3, $data[2]); // track_number
            $stmt->bindValue(4, strtoupper($data[3]) === 'TRUE' ? 1 : 0); // is_main_release
            
            $result = $stmt->execute();
        }
    }
    fclose($handle);
}

// Import dictionary
$dictionaryFile = __DIR__ . '/../data/dictionary.csv';
if (($handle = fopen($dictionaryFile, 'r')) !== FALSE) {
    $stmt = $db->prepare('INSERT OR REPLACE INTO dictionary (term, description) VALUES (?, ?)');
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        if (count($data) >= 2) { // Ensure we have both term and description
            $stmt->bindValue(1, $data[0]); // term
            $stmt->bindValue(2, $data[1]); // description
            
            $result = $stmt->execute();
        }
    }
    fclose($handle);
}

// Import cards
$cardsFile = __DIR__ . '/../data/cards.csv';
if (($handle = fopen($cardsFile, "r")) !== FALSE) {
    $header = fgetcsv($handle);
    $stmt = $db->prepare('INSERT OR REPLACE INTO cards 
        (card_ID, name, song, description, type, rarity, mana_cost, image, dealing)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    while (($data = fgetcsv($handle)) !== FALSE) {
        $stmt->bindValue(1, $data[0]); // card_ID
        $stmt->bindValue(2, $data[1]); // name
        $stmt->bindValue(3, $data[2]); // song
        $stmt->bindValue(3, $data[2]); // description
        $stmt->bindValue(4, $data[3]); // type
        $stmt->bindValue(5, $data[4]); // rarity
        $stmt->bindValue(6, $data[5]); // mana_cost
        $stmt->bindValue(7, $data[6]); // image
        $stmt->bindValue(8, $data[7]); // dealing
        $stmt->execute();
    }
    fclose($handle);
}

// Import posts
$postsFile = __DIR__ . '/../data/posts.csv';
if (file_exists($postsFile) && ($handle = fopen($postsFile, "r")) !== FALSE) {
    $header = fgetcsv($handle);
    $stmt = $db->prepare('INSERT OR REPLACE INTO posts (date, platform, optional_text) VALUES (?, ?, ?)');
    while (($data = fgetcsv($handle)) !== FALSE) {
        $stmt->bindValue(1, $data[0]); // date
        $stmt->bindValue(2, $data[1]); // platform
        $stmt->bindValue(3, $data[2]); // optional_text
        $stmt->execute();
    }
    fclose($handle);
}

$db->close();
echo "Data import completed successfully!";
?>