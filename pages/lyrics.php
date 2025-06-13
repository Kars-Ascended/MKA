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
                    Discog type:
                    <select name="discog">
                        <option value="">-- Any --</option>
                        <option value="main" <?php if (($_GET['discog'] ?? '') === 'main') echo 'selected'; ?>>Main</option>
                        <option value="pre-2010" <?php if (($_GET['discog'] ?? '') === 'pre-2010') echo 'selected'; ?>>Pre-2010</option>
                        <option value="other" <?php if (($_GET['discog'] ?? '') === 'other') echo 'selected'; ?>>Other</option>
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
                <h3><?php echo htmlspecialchars($row['title'] ?? ''); ?> - 
                    <a href="https://genius.com/Mrkitty-<?php 
                        $clean_title = preg_replace('/[^a-zA-Z0-9\s-]/', '', $row['TRACK_TITLE']);
                        echo str_replace(' ', '-', strtolower(trim($clean_title))); 
                    ?>-lyrics" target="_blank"><?php echo htmlspecialchars($row['TRACK_TITLE'] ?? ''); ?></a></h3>
                <div>
                    <?php 
                        if (!is_null($row['Lyrics'])) {
                            $lyrics = str_replace('\n', "\n", $row['Lyrics']); 
                            $lyrics = trim($lyrics, '"'); // Remove quotes from start and end
                            echo nl2br(htmlspecialchars($lyrics)); 
                        }
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
