<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../../backend/meta-include.php'; ?>
    <link rel="stylesheet" href="/css/lyricle.css">
    <title>Lyricle</title>
</head>
<body>
    <main-element class="welcome">
        <h1 title>Lyricle [Beta]</h1>
    </main-element>

    <main-element>
        <div id="lyricle-game">
            <p id="lyric-hint"></p>
            <div id="guess-history"></div>
            <div id="input-row" class="guess-row"></div>
            <button onclick="submitGuess()">Guess</button>
            <button onclick="revealAnswer()" id="reveal-btn">Reveal Answer</button>
            <button onclick="showHint()" id="hint-btn">Hint</button>
            <p id="feedback"></p>
            <p id="hint-info"></p>
        </div>
    </main-element>
    <script src="/js/lyricle.js"></script>

    <main-element>
        <h2>Lyricle?</h2>
        <p>Lyricle is based on the game <a href="https://www.nytimes.com/games/wordle/index.html">wordle</a>, you can read how to play there or try figure it out yourself :D </p>
        <p>The lyrics are pulled from the whole database then filtered out based on criteria so they are turned into actual guessable phrases, if you happen to find a silly one let me know and ill fix it!</p>
        <p>(remove all punctuation from your answer!)</p>
</body>
</html>