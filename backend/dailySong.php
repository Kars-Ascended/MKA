<?php

// Open the SQLite database
$db = new SQLite3(__DIR__ . '/../db/mka.db');

// Get today's date and use it as a seed
$today = date('yyyy-mm-dd');
$seed = crc32($today);
mt_srand($seed);

// Get total number of songs
$count = $db->querySingle("SELECT COUNT(*) FROM songs");

// Get a random song using today's seed
$randomIndex = mt_rand(1, $count);
$query = "SELECT s.TRACK_TITLE, s.spotify_link, s.youtube_link, s.era,
                 r.title as album_title, r.type as release_type
          FROM songs s
          LEFT JOIN connections c ON s.song_ID = c.song_ID
          LEFT JOIN releases r ON c.release_ID = r.release_ID
          LIMIT 1 OFFSET " . ($randomIndex - 1);

$result = $db->query($query);
$track = $result->fetchArray(SQLITE3_ASSOC); ?>

<?php if ($track): ?>
    <main-element style="position: relative;">

        <!-- Background Image -->
        <div class="background-image" style="background-image: url('/assets/covers/<?= htmlspecialchars($track['release_type']) ?>/<?= htmlspecialchars($track['album_title']) ?>.png');"></div>

        <!-- Front -->
        <div id="daily-song">

            <img src="/assets/covers/<?= htmlspecialchars($track['release_type']) ?>/<?= htmlspecialchars($track['album_title']) ?>.png" 
                 alt="Album Cover" id="album-cover">

            <div id="song-title">
                <h1><?= htmlspecialchars($track['TRACK_TITLE']) ?> - <?= htmlspecialchars($track['album_title']) ?></h1>

                <!-- Lazy-load audio player placeholder -->
                <div class="audio-player"
                     data-era="<?= htmlspecialchars($track['era']) ?>"
                     data-album="<?= htmlspecialchars($track['album_title']) ?>"
                     data-song="<?= htmlspecialchars($track['TRACK_TITLE']) ?>">
                    <div class="audio-placeholder">Loading audio...</div>
                </div>
            </div>

            <!-- Embeds -->
            <div class="embeds">
                <?php if (!empty($track['spotify_link'])): ?>
                <div id="spotify-embed">
                    <iframe src="https://open.spotify.com/embed/track/<?= basename($track['spotify_link']) ?>" 
                            width="300" 
                            height="80" 
                            frameborder="0" 
                            allowtransparency="true" 
                            allow="encrypted-media">
                    </iframe>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($track['youtube_link'])): ?>
                <div id="youtube-embed">
                    <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($track['youtube_link']) ?>">
                    </iframe>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main-element>
<?php else: ?>
    <p class="error">No track found</p>
<?php endif; ?>

<?php
$db->close();
?>