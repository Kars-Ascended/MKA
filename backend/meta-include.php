<?php $root = $_SERVER['DOCUMENT_ROOT']; ?>

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
<link rel="stylesheet" href="/css/base.css?v=<?= filemtime($root . '/css/base.css') ?>">
<link rel="stylesheet" href="/css/meta-include.css?v=<?= filemtime($root . '/css/meta-include.css') ?>">
<link rel="stylesheet" href="/css/table.css?v=<?= filemtime($root . '/css/table.css') ?>">

<!-- JS Scripts -->
<script src="/js/column_hide.js?v=<?= filemtime(filename: 'column_hide.js') ?>" defer></script>
<script src="/js/lazy_load.js?v=<?= filemtime(filename: 'lazy_load.js') ?>" defer></script>
<script src="/js/settings.js?v=<?= filemtime(filename: 'settings.js') ?>" defer></script>

<!-- NAV -->
<div class="nav">
    <a button href="/home">
        <span class="icon">🏠</span>
        <span class="label">Home</span>
    </a>
    <a button href="/songs">
        <span class="icon">🎵</span>
        <span class="label">Songs Database</span>
    </a>
    <a button href="/lyrics">
        <span class="icon">📝</span>
        <span class="label">Lyrics Database</span>
    </a>
    <a button href="/discography">
        <span class="icon">💿</span>
        <span class="label">Discography</span>
    </a>
    <a button href="#" style="filter: brightness(0.6);">
        <span class="icon">⏳</span>
        <span class="label">Mr.Kitty Content</span>
    </a>
    <a button href="/fun/dailySong.php">
        <span class="icon">🌞</span>
        <span class="label">Daily Song</span>
    </a>
    <a button href="/fun/lyricle.php">
        <span class="icon">🎤</span>
        <span class="label">Lyricle</span>
    </a>
    <a button href="#" style="filter: brightness(0.6);">
        <span class="icon">🃏</span>
        <span class="label">Card Duel [Beta]</span>
    </a>
    <a button href="#" style="filter: brightness(0.6);">
        <span class="icon">👤</span>
        <span class="label">Account [Soon]</span>
    </a>
    <a button id="settings-button">
        <span class="icon">⚙️</span>
        <span class="label">Settings</span>
    </a>
    <a button href="/site/index.php">
        <span class="icon">ℹ️</span>
        <span class="label">Site Information</span>
    </a>
    <a button href="/site/site-updates.php">
        <span class="icon">📰</span>
        <span class="label">Updates</span>
    </a>
    <a button href="/site/extras.php">
        <span class="icon">✨</span>
        <span class="label">Extras</span>
    </a>
    <a button href="/underground/" style="filter: brightness(0.6);">
        <span class="icon">🕳️</span>
        <span class="label">Underground [Soon]</span>
    </a>
</div> <!-- End of .nav -->

<!-- Mobile Top Navbar Button -->
<div class="mobile-navbar-top">
    <button id="open-mobile-navbar">☰ Navbar</button>
</div>
<!-- Mobile Fullscreen Navbar Overlay -->
<div class="mobile-navbar-overlay" id="mobile-navbar-overlay">
    <div class="mobile-navbar-content">
        <button id="close-mobile-navbar" class="close-btn">✕</button>
        <nav>
            <a href="/home" class="mobile-nav-btn"><span class="icon">🏠</span> Home</a>
            <a href="/songs" class="mobile-nav-btn"><span class="icon">🎵</span> Songs Database</a>
            <a href="/lyrics" class="mobile-nav-btn"><span class="icon">📝</span> Lyrics Database</a>
            <a href="/discography" class="mobile-nav-btn"><span class="icon">💿</span> Discography</a>
            <a href="#" class="mobile-nav-btn" style="filter: brightness(0.6);"><span class="icon">⏳</span> Mr.Kitty Posts [Soon]</a>
            <a href="/fun/dailySong.php" class="mobile-nav-btn"><span class="icon">🌞</span> Daily Song</a>
            <a href="/fun/lyricle.php" class="mobile-nav-btn"><span class="icon">🎤</span> Lyricle</a>
            <a href="#" class="mobile-nav-btn" style="filter: brightness(0.6);" ><span class="icon">🃏</span> Card Duel [Beta]</a>
            <a href="#" class="mobile-nav-btn" style="filter: brightness(0.6);"><span class="icon">👤</span> Account [Soon]</a>
            <a href="#" id="mobile-settings" class="mobile-nav-btn"><span class="icon">⚙️</span> Settings</a>
            <a href="/site/index.php" class="mobile-nav-btn"><span class="icon">ℹ️</span> Site Information</a>
            <a href="/site/site-updates.php" class="mobile-nav-btn"><span class="icon">📰</span> Updates</a>
            <a href="/site/extras.php" class="mobile-nav-btn"><span class="icon">✨</span> Extras</a>
            <a href="/underground/" class="mobile-nav-btn" style="filter: brightness(0.6);"><span class="icon">🕳️</span> Underground [Soon]</a>
        </nav>
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
            <option value="blue">The Blues [Default]</option>
            <option value="lightblue">Light Blues</option>
        </select>
    </div>
    ^ Light mode half broken right now, sorry!
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

    <p>Mobile users please refresh the page to close the settings, sorry again!</p>
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

    // Mobile settings button toggles settings panel
    document.addEventListener('DOMContentLoaded', function() {
        var mobileSettings = document.getElementById('mobile-settings');
        var settingsPanel = document.querySelector('.settings');
        if (mobileSettings && settingsPanel) {
            mobileSettings.addEventListener('click', function(e) {
                e.preventDefault();
                settingsPanel.classList.add('active');
                settingsPanel.classList.remove('inactive');
            });
        }
    });

    // Mobile navbar overlay open/close
    document.addEventListener('DOMContentLoaded', function() {
        var openBtn = document.getElementById('open-mobile-navbar');
        var closeBtn = document.getElementById('close-mobile-navbar');
        var overlay = document.getElementById('mobile-navbar-overlay');
        if (openBtn && overlay) {
            openBtn.addEventListener('click', function() {
                overlay.classList.add('active');
            });
        }
        if (closeBtn && overlay) {
            closeBtn.addEventListener('click', function() {
                overlay.classList.remove('active');
            });
        }
        // Optional: close overlay when clicking outside nav
        overlay && overlay.addEventListener('click', function(e) {
            if (e.target === overlay) overlay.classList.remove('active');
        });
    });
</script>

