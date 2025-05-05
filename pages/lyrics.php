<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <?php include '../backend/table_display.php'; ?>
    <title>Lyrics Database</title>
</head>
<body>

    <main-element class="welcome">
        <h1 title>Lyrics Archive</h1>
    </main-element>

    <!-- Filter Form -->
    <main-element>
    <form method="get">

        <div class="parent">
            <label>
                Album:
                <input type="text" name="album" value="<?php echo htmlspecialchars($_GET['album'] ?? ''); ?>">
            </label>

            <label>
                Search Title:
                <input type="text" name="title" value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>">
            </label>

            <label>
                Search Lyrics:
                <input type="text" name="lyrics" value="<?php echo htmlspecialchars($_GET['lyrics'] ?? ''); ?>">
            </label>
        </div>
        <br>
        <div>
        <label>
            Explicit:
            <select name="explicit">
                <option value="">-- Any --</option>
                <option value="1" <?php if (($_GET['explicit'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                <option value="0" <?php if (($_GET['explicit'] ?? '') === '0') echo 'selected'; ?>>No</option>
            </select>
        </label>

        <label>
            Discog type:
            <select name="discog">
                <option value="">-- Any --</option>
                <option value="Main" <?php if (($_GET['discog'] ?? '') === 'main') echo 'selected'; ?>>Main</option>
                <option value="Pre-2010" <?php if (($_GET['discog'] ?? '') === 'beatMARIO') echo 'selected'; ?>>beatMARIO</option>
                <option value="Remix" <?php if (($_GET['discog'] ?? '') === 'Remix') echo 'selected'; ?>>Remix</option>
                <option value="Single" <?php if (($_GET['discog'] ?? '') === 'Single') echo 'selected'; ?>>Single</option>
                <option value="Cover" <?php if (($_GET['discog'] ?? '') === 'Cover') echo 'selected'; ?>>Cover</option>
            </select>
        </label>

        <label>
            Breakcore:
            <select name="volume">
                <option value="">-- Any --</option>
                <option value="1" <?php if (($_GET['volume'] ?? '') === '1') echo 'selected'; ?>>Yes</option>
                <option value="0" <?php if (($_GET['volume'] ?? '') === '0') echo 'selected'; ?>>No</option>
            </select>
        </label>

        <label>
            Wrap every:
            <select id="columnSelector">
                <option value="1">1 column</option>
                <option value="2" selected>2 columns</option>
                <option value="3">3 columns</option>
                <option value="4">4 columns</option>
                <option value="5">5 columns</option>
            </select>
        </label>
        </div>
        <br>
        <button type="submit">Filter</button>
    </form>
    </main-element>
    <!-- Results Table -->

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
