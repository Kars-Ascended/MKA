<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?> <?php include '../backend/table_display.php'; ?>
    <title>Song Database</title>
</head>
<body>

    <main-element class="welcome">
        <h1 title>Music Archive</h1>
    </main-element>

    <div class="column-controls">
        <form method="get"> <!-- Search Filters -->
            <label>
                Album:
                <input type="text" name="album" value="<?php echo htmlspecialchars($_GET['album'] ?? ''); ?>">
            </label>

            <label>
                Search Title:
                <input type="text" name="title" value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>">
            </label>

            <label>
                <div class="custom-select">
                    Explicit:
                    <select name="explicit">
                        <option value="">-- Any --</option>
                        <option value="1" <?php if (($_GET['explicit'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                        <option value="0" <?php if (($_GET['explicit'] ?? '') === '0') echo 'selected'; ?>>No</option>
                    </select>
                </div>
            </label>

            <label>
                <div class="custom-select">
                    Discog type:
                    <select name="discog">
                        <option value="">-- Any --</option>
                        <option value="Main" <?php if (($_GET['discog'] ?? '') === 'Main') echo 'selected'; ?>>Main</option>
                        <option value="Pre-2010" <?php if (($_GET['discog'] ?? '') === 'Pre-2010') echo 'selected'; ?>>Pre-2010</option>
                        <option value="Remix" <?php if (($_GET['discog'] ?? '') === 'Remix') echo 'selected'; ?>>Remix</option>
                        <option value="Single" <?php if (($_GET['discog'] ?? '') === 'Single') echo 'selected'; ?>>Single</option>
                        <option value="Cover" <?php if (($_GET['discog'] ?? '') === 'Cover') echo 'selected'; ?>>Cover</option>
                    </select>
                </div>
            </label>

            <label>
                <div class="custom-select">
                    Breakcore:
                    <select name="volume">
                        <option value="">-- Any --</option>
                        <option value="1" <?php if (($_GET['volume'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                        <option value="0" <?php if (($_GET['volume'] ?? '') === '0') echo 'selected'; ?>>No</option>
                    </select>
                </div>
            </label>

            <label> 
                <div class="custom-select">
                    Has Features:
                    <select name="has_features">
                        <option value="">-- Any --</option>
                        <option value="1" <?php if (($_GET['has_features'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                        <option value="0" <?php if (($_GET['has_features'] ?? '') === '0') echo 'selected'; ?>>No</option>
                    </select>
                </div>
            </label>
            
            <button type="submit">Filter</button>
        </form>
        <br>
        <!-- Column Visibility Controls -->
        <label><input type="checkbox" class="toggle-column" data-column="0" checked> Album</label>
        <label><input type="checkbox" class="toggle-column" data-column="1" checked> Track #</label>
        <label><input type="checkbox" class="toggle-column" data-column="2" checked> Title</label>
        <label><input type="checkbox" class="toggle-column" data-column="3" checked> Duration</label>
        <label><input type="checkbox" class="toggle-column" data-column="4" checked> Spotify</label>
        <label><input type="checkbox" class="toggle-column" data-column="5" checked> YouTube</label>
        <label><input type="checkbox" class="toggle-column" data-column="6" checked> Explicit</label>
        <label><input type="checkbox" class="toggle-column" data-column="7" checked> Breakcore</label>
        <label><input type="checkbox" class="toggle-column" data-column="8" checked> Featured Artists</label>
        <label><input type="checkbox" class="toggle-column" data-column="9" checked> Discog Type</label>
    </div>

    <!-- Results Table -->
    <table>
        
        <tr> <!-- Table Header -->
            <th>Album</th>
            <th>Track #</th>
            <th>Title</th>
            <th>Duration</th>
            <th>Spotify</th>
            <th>YouTube</th>
            <th>Explicit?</th>
            <th>Loud / Breakcore?</th>
            <th>Featured Artists</th>
            <th>Discog Type</th>
        </tr>

        <?php while ($row = $results->fetchArray(SQLITE3_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['album']); ?></td>
            <td><?php echo htmlspecialchars($row['track_number']); ?></td>
            <td><?php echo htmlspecialchars($row['track_title']); ?></td>
            <td><?php echo htmlspecialchars($row['duration']); ?></td>
            <td><?php echo !empty($row['spotify_link']) ? "<a href=\"" . htmlspecialchars($row['spotify_link']) . "\" target=\"_blank\">Spotify</a>" : ""; ?></td>
            <td><?php echo !empty($row['youtube_link']) ? "<a href=\"" . htmlspecialchars($row['youtube_link']) . "\" target=\"_blank\">YouTube</a>" : ""; ?></td>
            <td><?php echo $row['explicit'] ? 'Yes' : 'No'; ?></td>
            <td><?php echo $row['volume'] ? 'Yes' : 'No'; ?></td>
            <td><?php echo ($row['featured_artists'] && $row['featured_artists'] !== 'FALSE') ? htmlspecialchars($row['featured_artists']) : ''; ?></td>
            <td><?php echo htmlspecialchars($row['discog']); ?></td>
        </tr>
        <?php endwhile; ?>

    </table>

</body>
</html>

<?php
// Close the database connection
$db->close();
?>
