<?php
header('Content-Type: text/html');

// Open the SQLite database
$db = new SQLite3(__DIR__ . '/../db/mka.db');

// Get today's date and use it as a seed
$today = date('yy-mm-dd');
$seed = crc32($today);
mt_srand($seed);

// Get total number of tracks
$count = $db->querySingle("SELECT COUNT(*) FROM tracks");

// Get a random track using today's seed
$randomIndex = mt_rand(1, $count);
$query = "SELECT track_title as song, track_title, album, discog, spotify_link, youtube_link FROM tracks LIMIT 1 OFFSET " . ($randomIndex - 1);
$result = $db->query($query);
$track = $result->fetchArray(SQLITE3_ASSOC); ?>

<?php if ($track): ?>
    <main-element style="position: relative;">

        <!-- Background Image -->
        <div class="background-image" style="background-image: url('/assets/covers/<?= htmlspecialchars($track['discog']) ?>/<?= htmlspecialchars($track['album']) ?>.png');"></div>

        <!-- Front -->
        <div id="daily-song">

            <img src="/assets/covers/<?= htmlspecialchars($track['discog']) ?>/<?= htmlspecialchars($track['album']) ?>.png" 
                 alt="Album Cover" id="album-cover">

            <div id="song-title">
                <h1><?= htmlspecialchars($track['track_title']) ?> - <?= htmlspecialchars($track['album']) ?></h1>

                <!-- Lazy-load audio player placeholder -->
                <div class="audio-player"
                     data-discog="<?= htmlspecialchars($track['discog']) ?>"
                     data-album="<?= htmlspecialchars($track['album']) ?>"
                     data-song="<?= htmlspecialchars($track['track_title']) ?>">
                    <div class="audio-placeholder">Loading audio...</div>
                </div>
            </div>

            <!-- Embeds -->
            <div class="embeds">
                <div id="spotify-embed">
                    <iframe src="https://open.spotify.com/embed/track/<?= basename($track['spotify_link']) ?>" 
                            width="300" 
                            height="80" 
                            frameborder="0" 
                            allowtransparency="true" 
                            allow="encrypted-media">
                    </iframe>
                </div>
                <div id="youtube-embed">
                        <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($track['youtube_link']) ?>">
                        </iframe>
                </div>
            </div>
        </div>
    </main-element>
<?php else: ?>
    <p class="error">No track found</p>
<?php endif; ?>

<?php
$db->close();
?>