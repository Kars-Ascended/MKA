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

    <main-element>
        <form method="get"> <!-- Search Filters -->
            <div class="parent">
            <label>
                Album:
                <input type="text" name="album" value="<?php echo htmlspecialchars($_GET['album'] ?? ''); ?>">
            </label>

            <label>
                Search Title:
                <input type="text" name="title" value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>">
            </label>
            </div>

            <br>

            <div style="display: flex; gap: 1em;">
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
                    Era:
                    <select name="era">
                        <option value="">-- Any --</option>
                        <option value="main" <?php if (($_GET['era'] ?? '') === 'main') echo 'selected'; ?>>Main</option>
                        <option value="pre-2010" <?php if (($_GET['era'] ?? '') === 'pre-2010') echo 'selected'; ?>>Pre-2010</option>
                        <option value="other" <?php if (($_GET['era'] ?? '') === 'other') echo 'selected'; ?>>Other</option>
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
                    Features:
                    <select name="has_features">
                        <option value="">-- Any --</option>
                        <option value="1" <?php if (($_GET['has_features'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                        <option value="0" <?php if (($_GET['has_features'] ?? '') === '0') echo 'selected'; ?>>No</option>
                    </select>
                </div>
            </label>

            <label>
            Instrumentals:
            <select name="hide_instrumental">
                <option value="">-- Any --</option>
                <option value="1" <?php if (($_GET['hide_instrumental'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                <option value="0" <?php if (($_GET['hide_instrumental'] ?? '') === '0') echo 'selected'; ?>>No</option>
            </select>
            </label>

            <label>
            Non-main releases:
            <select name="hide_non_main">
                <option value="">-- Any --</option>
                <option value="1" <?php if (($_GET['hide_non_main'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                <option value="0" <?php if (($_GET['hide_non_main'] ?? '') === '0') echo 'selected'; ?>>No</option>
            </select>
            </label>

            </div>
            <br>
            <button type="submit">Filter</button>
        </form>
        <br>
        <!-- Column Visibility Controls -->
        <label><input type="checkbox" class="toggle-column" data-column="0" checked> Album</label>
        <label><input type="checkbox" class="toggle-column" data-column="1" checked> Track #</label>
        <label><input type="checkbox" class="toggle-column" data-column="2" checked> Title</label>
        <label><input type="checkbox" class="toggle-column" data-column="3" checked> Song</label>
        <label><input type="checkbox" class="toggle-column" data-column="4" checked> Duration</label>
        <label><input type="checkbox" class="toggle-column" data-column="5" checked> Links</label>
        <label><input type="checkbox" class="toggle-column" data-column="6" checked> Explicit</label>
        <label><input type="checkbox" class="toggle-column" data-column="7" checked> Breakcore</label>
        <label><input type="checkbox" class="toggle-column" data-column="8" checked> Featured Artists</label>
        <label><input type="checkbox" class="toggle-column" data-column="9" checked> Era</label>
        <label><input type="checkbox" class="toggle-column" data-column="10" checked> Release Date</label>
    </main-element>

    <!-- Results Table -->
    <table>
        <tr>
            <th>Album</th>
            <th>Track #</th>
            <th>Title</th>
            <th>Song</th>
            <th>Duration</th>
            <th>Links</th>
            <th>Explicit?</th>
            <th>Loud / Breakcore?</th>
            <th>Featured Artists</th>
            <th>Era Type</th>
            <th>Release Date</th>
        </tr>

        <?php while ($row = $results->fetchArray(SQLITE3_ASSOC)): ?>
        <tr>
            <td><?php echo !is_null($row['album_title']) ? htmlspecialchars($row['album_title']) : ''; ?></td>
            <td><?php echo !is_null($row['track_number']) ? htmlspecialchars($row['track_number']) : ''; ?></td>
            <td><?php echo !is_null($row['TRACK_TITLE']) ? htmlspecialchars($row['TRACK_TITLE']) : ''; ?></td>
            <td>
                <?php if (!is_null($row['TRACK_TITLE']) && !is_null($row['album_title'])): ?>
                    <div class='audio-player' 
                         data-album='<?php echo htmlspecialchars($row['album_title'] ?? '', ENT_QUOTES); ?>' 
                         data-song='<?php echo htmlspecialchars($row['TRACK_TITLE'] ?? '', ENT_QUOTES); ?>' 
                         data-era='<?php echo htmlspecialchars($row['era'] ?? '', ENT_QUOTES); ?>'>
                        <div class='audio-placeholder'>Click to load audio</div>
                    </div>
                <?php endif; ?>
            </td>
            <td><?php echo !is_null($row['DURATION']) ? htmlspecialchars($row['DURATION']) : ''; ?></td>
            <td class="links-cell">
                <?php if (!empty($row['spotify_link'])): ?>
                    <a href="<?php echo htmlspecialchars($row['spotify_link']); ?>" target="_blank">
                        <img src="/assets/spotify.png" alt="Spotify" title="Listen on Spotify" class="platform-icon">
                    </a>
                <?php endif; ?>
                <?php if (!empty($row['youtube_link'])): ?>
                    <a href="<?php echo htmlspecialchars($row['youtube_link']); ?>" target="_blank">
                        <img src="/assets/youtube.png" alt="YouTube" title="Watch on YouTube" class="platform-icon">
                    </a>
                <?php endif; ?>
            </td>
            <td><?php echo isset($row['explicit']) ? ($row['explicit'] ? 'Yes' : 'No') : ''; ?></td>
            <td><?php echo isset($row['volume']) ? ($row['volume'] ? 'Yes' : 'No') : ''; ?></td>
            <td><?php echo (!empty($row['featured_artists']) && $row['featured_artists'] !== 'FALSE') ? htmlspecialchars($row['featured_artists']) : ''; ?></td>
            <td><?php echo !is_null($row['era']) ? htmlspecialchars($row['era']) : ''; ?></td>
            <td><?php echo !is_null($row['release_date']) ? htmlspecialchars($row['release_date']) : ''; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

<?php
// Close the database connection
$db->close();
?>

</body>
</html>
