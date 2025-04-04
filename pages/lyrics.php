<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <?php include '../backend/table_display.php'; ?>
</head>
<body>
    <h1>Music Archive</h1>

    <!-- Filter Form -->
    <form method="get">
        <label>
            Search Title:
            <input type="text" name="title" value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>">
        </label>
        <label>
            Search Lyrics:
            <input type="text" name="lyrics" value="<?php echo htmlspecialchars($_GET['lyrics'] ?? ''); ?>">
        </label>
        <button type="submit">Filter</button>
    </form>

    <!-- Results Table -->
    <style>
    </style>

    <div class="lyrics-grid">
        <?php while ($row = $results->fetchArray(SQLITE3_ASSOC)): ?>
            <div class="lyrics-item">
                <h3><?php echo htmlspecialchars($row['album']); ?> - 
                    <?php echo htmlspecialchars($row['track_title']); ?></h3>
                <div>
                    <?php 
                        $lyrics = str_replace('\n', "\n", $row['lyrics']); 
                        $lyrics = trim($lyrics, '"'); // Remove quotes from start and end
                        echo nl2br(htmlspecialchars($lyrics)); 
                    ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>

<?php
// Close the database connection
$db->close();
?>
