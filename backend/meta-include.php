<!-- Site-wide html tags -->
<meta charset="UTF-8">  <!-- Ensures correct text encoding -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Makes it mobile-friendly -->

<!-- SEO -->
<meta name="description" content="Mr.Kitty Archive -- Biggest online archive and database of Mr.Kitty's songs, lyrics, posts, and more.">
<meta property="og:description" content="Mr.Kitty Archive -- Biggest online archive and database of Mr.Kitty's songs, lyrics, posts, and more."> 
<meta property="og:title" content="Mr.Kitty Archive">
<meta property="og:image" content="https://mr-kitty-archive.xyz/assets/icons/mk-logo.png">

<!-- Page metadata -->
<link rel="icon" href="assets\icons\favicon.ico" type="image/x-icon">

<!-- CSS Stylesheets -->
<link rel="stylesheet" href="/css/base.css">
<link rel="stylesheet" href="/css/meta-include.css">
<link rel="stylesheet" href="/css/table.css">

<!-- JS Scripts -->
<script src="/js/column_hide.js" defer></script>
<script src="/js/lazy_load.js" defer></script>
<script src="/js/settings.js" defer></script>

<!-- NAV -->
<div class="nav">
    <button id="mobile-menu-btn" style="display:none;">Navbar</button>
    <div class="parent">
        <a button href="/home">Home</a>
        <a button href="/songs.php">Songs Database</a>
        <a button href="/lyrics.php">Lyrics Database</a>
        <a button href="/discography.php">Discography [soon]</a>
    </div>
    <div class="parent">
        <a button href="/dailySong.php">Daily song</a>
        <a button href="/lyricle.php">Lyricle</a>
    </div>
    <div class="parent">
    <a button href="/site/index.php">Site Information</a>
    <a button href="" style="filter: brightness(0.2);">Mr.Kitty Content [soon]</a>
    </div>
    <div class="parent" style="margin-left: auto;"> 
        <a button id="settings-button">Settings</a>
    </div>
</div>

<div class="settings">
    <h1 title>Settings</h1 title>
    <h3>Main</h3>
    <div class="volume-control">
        <label for="volumeSlider">Volume: </label>
        <input type="range" id="volumeSlider" min="0" max="1" step="0.01" value="1">
    </div>

    <div class="theme-control">
        <label for="themeSelect">Theme: </label>
        <select id="themeSelect">
            <option value="default">Kurple [Default]</option>
            <option value="blue">The Blues</option>
            <option value="mono">Mono</option>
            <option value="light">Kurple Light [Beta]</option>
        </select>
    </div>
    <p>Table themes coming soon maybe ^^</p>
    
    <div class="table-options">
        <label><input type="checkbox" id="tableAltRows" checked> Alternating Row Color</label>
        <label>Borders:
            <select id="tableBorderStyle">
                <option value="none">None</option>
                <option value="row">Row</option>
                <option value="column">Column</option>
                <option value="all">All</option>
            </select>
        </label>
        <label>Table Theme:
            <select id="tableThemeSelect">
                <option value="dark">Dark</option>
                <option value="light">Light</option>
                <option value="kurple">Kurple</option>
            </select>
        </label>
    </div>

    <img src="/assets/settings.png" alt="Forrest">
</div>

