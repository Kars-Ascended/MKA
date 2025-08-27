<?php
$db = new SQLite3(__DIR__ . '/../db/mka.db');
$results = $db->query('SELECT * FROM posts ORDER BY date DESC');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
    <main-element>
        <h1>Posts</h1>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Platform</th>
                    <th>Text</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $results->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['platform']) ?></td>
                    <td><?= htmlspecialchars($row['optional_text']) ?></td>
                    <td>
                        <?php
                            // Replace slashes with dashes in the date for the image URL
                            $imageDate = str_replace('/', '-', $row['date']);
                        ?>
                        <img src="https://gauge-flying-photos-rebecca.trycloudflare.com/posts/<?= htmlspecialchars($row['platform']) ?>/<?= htmlspecialchars($imageDate) ?>.png" alt="Post Image" style="max-width:120px; border-radius:8px;">
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main-element>
</body>
</html>