<?php
$db = new SQLite3(__DIR__ . '/../db/mka.db');

function getRandomLyricLine($db) {
    $result = $db->querySingle(
        "SELECT Lyrics, TRACK_TITLE FROM songs 
         WHERE Lyrics IS NOT NULL AND Lyrics != '' 
         AND (spotify_link IS NOT NULL AND spotify_link != '' OR youtube_link IS NOT NULL AND youtube_link != '')
         AND (volume IS NULL OR volume = '' OR volume = 0 OR volume = 'FALSE')
         ORDER BY RANDOM() LIMIT 1", true);

    if ($result && isset($result['Lyrics'])) {
        $lines = explode('\n', $result['Lyrics']);
        $lines = array_filter(array_map('trim', $lines), function($line) {
            if (mb_strlen($line) <= 5 || mb_strlen($line) > 31) return false;
            $lower = mb_strtolower($line);
            if ($lower === '[instrumental]') return false;
            return !preg_match('/\b(oh|ah)[\s,.\-!?:;)]?/i', $line);
        });
        if (count($lines) > 0) {
            $randomLine = $lines[array_rand($lines)];
            $randomLine = preg_replace('/[[:punct:]]/u', '', $randomLine);
            return [
                'lyric' => $randomLine,
                'album' => $result['album'] ?? '',
                'track_title' => $result['TRACK_TITLE'] ?? ''
            ];
        }
    }
    return false;
}

$attempts = 0;
$maxAttempts = 5;
$randomLine = false;
while ($attempts < $maxAttempts && !$randomLine) {
    $randomLine = getRandomLyricLine($db);
    $attempts++;
}

header('Content-Type: application/json');
echo $randomLine ? json_encode($randomLine) : json_encode(['lyric' => '']);

$db->close();
?>