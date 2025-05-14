<!-- Site-wide html tags -->
<meta charset="UTF-8">  <!-- Ensures correct text encoding -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Makes it mobile-friendly -->

<!-- Page metadata -->
<link rel="icon" href="assets\icons\favicon.ico" type="image/x-icon">

<!-- CSS Stylesheets -->
<link rel="stylesheet" href="../css/base.css">
<link rel="stylesheet" href="../css/meta-include.css">
<link rel="stylesheet" href="../css/table.css">

<!-- JS Scripts -->
<script src="/js/column_hide.js" defer></script>
<script src="/js/lazy_load.js" defer></script>
<script src="/js/settings.js" defer></script>


<!-- NAV -->
<div class="nav">
    <a button href="/home">Home</a>
    <a button href="/songs.php">Songs Database</a>
    <a button href="/lyrics.php">Lyrics Database</a>
    <a button href="/site/index.php">Site Information</a>
    <a button href="#">Mr.Kitty Content [soon]</a>
    <a button href="/dailySong.php">Daily song [soon]</a>
    <a button href="" id="settings-button" style="margin-left: auto;">Settings</a>
</div>

<div class="settings">
    <div class="volume-control">
    <label for="volumeSlider">Volume:</label>
    <input type="range" id="volumeSlider" min="0" max="1" step="0.1" value="1">
</div>
</div>