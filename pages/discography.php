<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <link rel="stylesheet" href="../css/discography.css">
    <title>MKA Discography</title>
</head>
<body>
    <main-element class="welcome">
        <h1 title>Discography</h1>
    </main-element>

    <main-element class="filters">
        <form method="get">
            <div class="filter-container">
                <div class="filter-group">
                    <h3>Discography Type:</h3>
                    <label><input type="checkbox" name="discog[]" value="main" <?php echo (isset($_GET['discog']) && in_array('main', $_GET['discog'])) ? 'checked' : ''; ?>> Main Albums</label>
                    <label><input type="checkbox" name="discog[]" value="pre-2010" <?php echo (isset($_GET['discog']) && in_array('pre-2010', $_GET['discog'])) ? 'checked' : ''; ?>> Pre-2010</label>
                    <label><input type="checkbox" name="discog[]" value="other" <?php echo (isset($_GET['discog']) && in_array('other', $_GET['discog'])) ? 'checked' : ''; ?>> Other</label>
                </div>
                
                <div class="filter-group">
                    <h3>Content:</h3>
                    <label><input type="checkbox" name="has_explicit" value="1" <?php echo isset($_GET['has_explicit']) ? 'checked' : ''; ?>> Contains Explicit Tracks</label>
                    <label><input type="checkbox" name="has_features" value="1" <?php echo isset($_GET['has_features']) ? 'checked' : ''; ?>> Has Featured Artists</label>
                    <label><input type="checkbox" name="has_breakcore" value="1" <?php echo isset($_GET['has_breakcore']) ? 'checked' : ''; ?>> Contains Breakcore</label>
                </div>
            </div>
            <button type="submit">Apply Filters</button>
        </form>
    </main-element>

    <main-element discography>
        <div class="timeline-container">
            <?php
            // Connect to the database
            $db = new SQLite3(__DIR__ . '/../db/mka.db');
            
            // Build the query with filters
            $query = "SELECT DISTINCT a.album, a.release_date, a.discog 
                 FROM (
                 SELECT album, release_date, discog,
                    MAX(CASE WHEN explicit = 1 THEN 1 ELSE 0 END) as has_explicit,
                    MAX(CASE WHEN featured_artists IS NOT NULL AND featured_artists != '' THEN 1 ELSE 0 END) as has_features,
                    MAX(CASE WHEN volume = 1 THEN 1 ELSE 0 END) as has_breakcore
                 FROM tracks
                 GROUP BY album, release_date, discog
                 ) a
                 WHERE 1=1";

            // Apply filters
            if (!empty($_GET['discog'])) {
            $discogs = array_map(function($d) use ($db) { 
                return "'" . SQLite3::escapeString($d) . "'";
            }, $_GET['discog']);
            $query .= " AND a.discog IN (" . implode(",", $discogs) . ")";
            }
            
            if (isset($_GET['has_explicit'])) {
            $query .= " AND a.has_explicit = 1";
            }
            
            if (isset($_GET['has_features'])) {
            $query .= " AND a.has_features = 1";
            }
            
            if (isset($_GET['has_breakcore'])) {
            $query .= " AND a.has_breakcore = 1";
            }
            
            $query .= " ORDER BY a.release_date ASC";
            
            $results = $db->query($query);
            
            echo '<div class="timeline">';
            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $albumEncoded = urlencode($row['album']);
            echo '<a href="/pages/songs.php?album=' . $albumEncoded . '" class="timeline-item" 
                data-album="' . htmlspecialchars($row['album']) . '" 
                data-discog="' . htmlspecialchars($row['discog']) . '">';
            echo '<div class="timeline-content">';
            echo '<div class="timeline-date">' . date('Y', strtotime($row['release_date'])) . '</div>';
            echo '<h3>' . htmlspecialchars($row['album']) . '</h3>';
            echo '<div class="release-date">' . date('F j, Y', strtotime($row['release_date'])) . '</div>';
            echo '</div>';
            echo '</a>';
            }
            echo '</div>';
            
            $db->close();
            ?>
        </div>
    </main-element>
    <script>
        document.querySelectorAll('.timeline-item').forEach(item => {
            const album = item.dataset.album;
            const discog = item.dataset.discog;
            const imagePath = `../assets/covers/${discog}/${album}.png`;
            console.log('Loading image:', imagePath);
            item.style.backgroundImage = `url('${imagePath}')`;
        });
    </script>
</body>
</html>