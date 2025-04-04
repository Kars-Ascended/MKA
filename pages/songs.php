<?php include '../backend/table_display.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
</head>
<body>

    <h1>Music Archive</h1>

    <!-- Filter Form -->
    <form method="get">
        <label>
            Album: <input type="text" name="album" value="<?php echo htmlspecialchars($_GET['album'] ?? ''); ?>">
        </label>
        <label>
            Explicit:
            <select name="explicit">
                <option value="">-- Any --</option>
                <option value="1" <?php if (($_GET['explicit'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                <option value="0" <?php if (($_GET['explicit'] ?? '') === '0') echo 'selected'; ?>>No</option>
            </select>
        </label>
        <label>
            Loud / Breakcore:
            <select name="volume">
                <option value="">-- Any --</option>
                <option value="1" <?php if (($_GET['volume'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                <option value="0" <?php if (($_GET['volume'] ?? '') === '0') echo 'selected'; ?>>No</option>
            </select>
        </label>
        
        <label>
            Search Title:
            <input type="text" name="title" value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>">
        </label>
        <button type="submit">Filter</button>
    </form>

    <!-- Results Table -->
    <table>
        
        <tr>
            <th>Album</th>
            <th>Track #</th>
            <th>Title</th>
            <th>Duration</th>
            <th>Spotify</th>
            <th>YouTube</th>
            <th>Explicit?</th>
            <th>Loud / Breakcore?</th>
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
        </tr>
        <?php endwhile; ?>

    </table>

</body>
</html>

<?php
// Close the database connection
$db->close();
?>
