<?php
header('Content-Type: text/html');

// Open the SQLite database
$db = new SQLite3(__DIR__ . '/../db/mka.db');

// Get today's date and use it as a seed
$today = date('a');
$seed = crc32($today);
mt_srand($seed);

// Get total number of tracks
$count = $db->querySingle("SELECT COUNT(*) FROM tracks");

// Get a random track using today's seed
$randomIndex = mt_rand(1, $count);
$query = "SELECT track_title as song, track_title, album, discog, spotify_link FROM tracks LIMIT 1 OFFSET " . ($randomIndex - 1);
$result = $db->query($query);
$track = $result->fetchArray(SQLITE3_ASSOC);

if ($track) {
    echo '<main-element style="position: relative;">';

        // Display the background image
        echo '<div style="
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-position: center;
            background-image: url(\'/assets/covers/' . htmlspecialchars($track['discog']) . '/' . htmlspecialchars($track['album']) . '.png\');
            filter: brightness(0.5);
            z-index: 0;
        "></div>';
    
        // Elements
        echo '<div id="daily-song">';
            echo '<img src="/assets/covers/' . htmlspecialchars($track['discog']) . '/' . htmlspecialchars($track['album']) . '.png" 
                    alt="Album Cover" id="album-cover">';
            echo '<div id="song-title">';
                echo '<h1>' . htmlspecialchars($track['track_title']) . ' - ' .htmlspecialchars($track['album']) . '</h1>';
                // Replace the audio tag with a div that lazy_load.js will handle
                echo '<div class="audio-player" 
                        data-discog="' . htmlspecialchars($track['discog']) . '"
                        data-album="' . htmlspecialchars($track['album']) . '"
                        data-song="' . htmlspecialchars($track['track_title']) . '">
                        <div class="audio-placeholder">Loading audio...</div>
                    </div>';
            echo '</div>';
            echo '<div id="spotify-embed">';
                echo '<iframe src="https://open.spotify.com/embed/track/' . basename($track['spotify_link']) . '" 
                        width="300" 
                        height="80" 
                        frameborder="0" 
                        allowtransparency="true" 
                        allow="encrypted-media">
                </iframe>';
            echo '</div>';
        echo '</div>';
    echo '</main-element>';
} else {
    echo '<p class="error">No track found</p>';
}

$db->close();
?>