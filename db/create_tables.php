<?php
$db = new SQLite3(__DIR__ . '../../db/mka.db');

// Create songs table
$db->exec("CREATE TABLE IF NOT EXISTS songs (
    song_ID INTEGER PRIMARY KEY,
    TRACK_TITLE TEXT,
    DURATION TEXT,
    Lyrics TEXT,
    spotify_link TEXT,
    youtube_link TEXT,
    explicit BOOLEAN,
    volume BOOLEAN,
    featured_artists TEXT,
    era TEXT,
    sub_era TEXT,
    release_date TEXT
)");

// Create releases table
$db->exec("CREATE TABLE IF NOT EXISTS releases (
    release_ID TEXT PRIMARY KEY,
    title TEXT,
    type TEXT,
    release_date TEXT,
    era TEXT,
    sub_era TEXT
)");

// Create connections table
$db->exec("CREATE TABLE IF NOT EXISTS connections (
    song_ID INTEGER,
    release_ID INTEGER,
    track_number INTEGER,
    is_main_release BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (song_ID) REFERENCES songs(song_ID),
    FOREIGN KEY (release_ID) REFERENCES releases(release_ID),
    PRIMARY KEY (song_ID, release_ID)
)");

// Create dictionary table
$db->exec("CREATE TABLE IF NOT EXISTS dictionary (
    term TEXT PRIMARY KEY,
    description TEXT
)");

$db->close();
echo "Tables created successfully!";
?>