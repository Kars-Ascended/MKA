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
                    <label><input type="checkbox" name="type[]" value="Album" <?php echo (isset($_GET['type']) && in_array('Album', $_GET['type'])) ? 'checked' : ''; ?>> Albums</label>
                    <label><input type="checkbox" name="type[]" value="EP" <?php echo (isset($_GET['type']) && in_array('EP', $_GET['type'])) ? 'checked' : ''; ?>> EPs</label>
                    <label><input type="checkbox" name="type[]" value="Single" <?php echo (isset($_GET['type']) && in_array('Single', $_GET['type'])) ? 'checked' : ''; ?>> Singles</label>
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
            $db = new SQLite3(__DIR__ . '/../db/mka.db');
            
            // Build the query with filters
            $query = "SELECT DISTINCT r.release_ID, r.title, r.type, r.release_date,
                        MAX(s.explicit) as has_explicit,
                        MAX(CASE WHEN s.featured_artists IS NOT NULL AND s.featured_artists != '' 
                            AND s.featured_artists != 'FALSE' THEN 1 ELSE 0 END) as has_features,
                        MAX(s.volume) as has_breakcore
                     FROM releases r
                     LEFT JOIN connections c ON r.release_ID = c.release_ID
                     LEFT JOIN songs s ON c.song_ID = s.song_ID
                     WHERE r.title IS NOT NULL AND r.title != ''
                     GROUP BY r.release_ID";

            // Apply filters
            $conditions = [];
            $params = [];

            if (!empty($_GET['type'])) {
                $types = array_map(function($t) use ($db) { 
                    return "'" . SQLite3::escapeString($t) . "'";
                }, $_GET['type']);
                $conditions[] = "r.type IN (" . implode(",", $types) . ")";
            }
            
            if (isset($_GET['has_explicit'])) {
                $conditions[] = "MAX(s.explicit) = 1";
            }
            
            if (isset($_GET['has_features'])) {
                $conditions[] = "MAX(CASE WHEN s.featured_artists IS NOT NULL AND s.featured_artists != '' 
                                AND s.featured_artists != 'FALSE' THEN 1 ELSE 0 END) = 1";
            }
            
            if (isset($_GET['has_breakcore'])) {
                $conditions[] = "MAX(s.volume) = 1";
            }

            if (!empty($conditions)) {
                $query .= " HAVING " . implode(" AND ", $conditions);
            }
            
            $query .= " ORDER BY r.release_date ASC";
            
            $results = $db->query($query);
            
            echo '<div class="timeline">';
            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                if (!empty($row['title'])) {
                    $titleEncoded = urlencode($row['title']);
                    echo '<a href="/pages/songs.php?album=' . $titleEncoded . '" class="timeline-item" 
                        data-album="' . htmlspecialchars($row['title']) . '" 
                        data-type="' . htmlspecialchars($row['type']) . '">';
                    echo '<div class="timeline-content">';
                    echo '<div class="timeline-date">' . date('Y', strtotime($row['release_date'])) . '</div>';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<div class="release-date">' . date('F j, Y', strtotime($row['release_date'])) . '</div>';
                    echo '<div class="release-type">' . htmlspecialchars($row['type']) . '</div>';
                    echo '</div>';
                    echo '</a>';
                }
            }
            echo '</div>';
            
            $db->close();
            ?>
        </div>
    </main-element>
    <script>
        document.querySelectorAll('.timeline-item').forEach(item => {
            const album = item.dataset.album;
            const type = item.dataset.type;
            const imagePath = `../assets/covers/${type}/${album}.png`;
            console.log('Loading image:', imagePath);
            item.style.backgroundImage = `url('${imagePath}')`;
        });
    </script>
</body>
</html>