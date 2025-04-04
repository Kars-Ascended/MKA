<?php
// Open the SQLite database
$db = new SQLite3(__DIR__ . '../../db/mka.db');

// Create table if it doesn't exist
$db->exec("CREATE TABLE IF NOT EXISTS tracks (
    id INTEGER PRIMARY KEY,
    album TEXT,
    track_number TEXT,
    track_title TEXT,
    duration TEXT,
    lyrics TEXT,
    spotify_link TEXT,
    youtube_link TEXT,
    path TEXT,
    explicit BOOLEAN,
    volume BOOLEAN,
    discog TEXT, -- Main | Pre-2010 | Remix | Single | Cover --
    UNIQUE(album, track_number)  -- This ensures no duplicate album/track entries
)");

// Close the database connection
$db->close();
?>
