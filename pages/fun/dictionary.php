<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <?php include '../backend/search-dictionary.php'; ?>
    <title>Dictionary</title>
</head>
<body>
    <main-element class="welcome">
        <h1 title>Mr.Kitty Dictionary</h1>
    </main-element>

    <!-- Filter Form -->
    <main-element>
    <form method="get">
        <div style="display: flex; gap: 1em;">
        <label>
            Term:
            <input type="text" name="term" value="<?php echo htmlspecialchars($_GET['term'] ?? ''); ?>">
        </label>
        <label>
            Description:
            <input type="text" name="description" value="<?php echo htmlspecialchars($_GET['description'] ?? ''); ?>">
        </label>
        <button type="submit">Search</button>
        </div>
    </form>
    </main-element>

    <!-- Results Table -->
    <div class="dictionary-grid">
        <?php while ($row = $results->fetchArray(SQLITE3_ASSOC)): ?>
            <div class="dictionary-item">
                <h3><?php echo htmlspecialchars($row['term'] ?? ''); ?></h3>
                <div>
                    <?php echo nl2br(htmlspecialchars($row['description'] ?? '')); ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>