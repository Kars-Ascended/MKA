<!-- Site-wide html tags -->
<meta charset="UTF-8">  <!-- Ensures correct text encoding -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Makes it mobile-friendly -->

<!-- SEO -->
<meta name="description" content="Mr.Kitty Archive -- Biggest online archive and database of Mr.Kitty's songs, lyrics, posts, and more.">
<meta property="og:description" content="Mr.Kitty Archive -- Biggest online archive and database of Mr.Kitty's songs, lyrics, posts, and more."> 
<meta property="og:title" content="Mr.Kitty Archive">
<meta property="og:image" content="https://mr-kitty-archive.xyz/assets/icons/mk-logo.png">

<!-- Page metadata -->
<link rel="icon" href="/assets/icons/favicon.ico" type="image/x-icon">

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
        <a button href="/songs">Songs Database</a>
        <a button href="/lyrics">Lyrics Database</a>
        <a button href="/discography">Discography</a>
        <a button href="" style="filter: brightness(0.6);">Mr.Kitty Content [Soon]</a>

    </div>
        <div class="parent dropdown-parent">
            <button class="dropdown-toggle" type="button">Fun ▼</button>
            <div class="dropdown-content">
                <a button href="/fun/dailySong.php">Daily song</a>
                <a button href="/fun/lyricle.php">Lyricle</a>
                <!--<a button href="/dictionary.php">Dictionary [Beta]</a>-->
                <a button href="/fun/card-game.php">Card Duel [Beta]</a>
            </div>
        </div>

        <div class="parent dropdown-parent">
            <button class="dropdown-toggle" type="button">Other Site Pages ▼</button>
            <div class="dropdown-content">
                <a button href="#" style="filter: brightness(0.6);">Account [Soon]</a>
                <a button id="settings-button">Settings</a>
                <a button href="/site/index.php">Site Information</a>
                <a button href="/site/site-updates.php">Updates</a>
                <a button href="/site/extras.php">Extras</a>
            </div>
        </div>
</div>

<div class="settings">
    <h1 title>Settings</h1 title>
    <h3>- Main</h3>
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
    
    <h3>- Table Settings</h3>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownParents = document.querySelectorAll('.dropdown-parent');
    dropdownParents.forEach(parent => {
        const toggle = parent.querySelector('.dropdown-toggle');
        if (toggle) {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                parent.classList.toggle('open');
            });
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        dropdownParents.forEach(parent => {
            if (!parent.contains(e.target)) {
                parent.classList.remove('open');
            }
        });
    });
});
</script>

