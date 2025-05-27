<?php
$db = new SQLite3(__DIR__ . '/../db/mka.db');

function getRandomLyricLine($db) {
    // Only select songs with a spotify or youtube link, and volume is FALSE
    $result = $db->querySingle(
        "SELECT Lyrics FROM songs 
         WHERE Lyrics IS NOT NULL AND Lyrics != '' 
         AND (spotify_link IS NOT NULL AND spotify_link != '' OR youtube_link IS NOT NULL AND youtube_link != '')
         AND (volume IS NULL OR volume = '' OR volume = 0 OR volume = 'FALSE')
         ORDER BY RANDOM() LIMIT 1", true);

    if ($result && isset($result['Lyrics'])) {
        // Split on literal '\n'
        $lines = explode('\n', $result['Lyrics']);
        // Remove empty lines, trim, and filter for length > 10, not "[instrumental]", and not containing "oh", "ah", etc.
        $lines = array_filter(array_map('trim', $lines), function($line) {
            if (mb_strlen($line) <= 10) return false;
            $lower = mb_strtolower($line);
            if ($lower === '[instrumental]') return false;
            // Exclude lines containing "oh", "oh,", "ah", "ah-", etc. as whole words or prefixes
            return !preg_match('/\b(oh|ah)[\s,.\-!?:;)]?/i', $line);
        });
        if (count($lines) > 0) {
            // Pick a random line
            $randomLine = $lines[array_rand($lines)];
            // Remove all punctuation
            $randomLine = preg_replace('/[[:punct:]]/u', '', $randomLine);
            return $randomLine;
        }
    }
    return false;
}

// Try up to 5 times to avoid unwanted lines
$attempts = 0;
$maxAttempts = 5;
$randomLine = false;
while ($attempts < $maxAttempts && !$randomLine) {
    $randomLine = getRandomLyricLine($db);
    $attempts++;
}

echo $randomLine ? $randomLine : "";

$db->close();
?>