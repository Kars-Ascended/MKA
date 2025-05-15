<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <title>Mr. Kitty Archive</title>
    <style>
        @media only screen and (max-width: 768px) {
            main-element.welcome .left{
            display: none !important;
            }
        }
        
    </style>
</head>
<body>
    <main-element class="welcome" style="height: 15em;">
            <div class="left">
                <img src="../assets/icons/mk-logo.png" alt="Image">
            </div>
            <div class="right" style="margin: auto; font-size: 1.5em;">
                <h1 title>Hey, welcome to the <u>M</u>r. <u>K</u>itty <u>A</u>rchive</h1>
            </div>
    </main-element>

    <main-element>
        <h2>FAQ</h2>
        <h3>- Where are the actual songs?</h3>
        <p>Being added, most are actually there but unable to load. ill fix it once ive finished all the other features.</p>

        <h3>- What else do you plan to archive?</h3>
        <p>Everything. his music, social posts, all images/videos, live shows, snippets shown, whatever we can find.</p>

        <h3>- I found a problem / something incorrect on the site</h3>
        <p>Let me know in the discord or subreddit linked below.</p>

        <h3>- Why does it take forever to load songs?</h3>
        <p>Songs are hosted on an old laptop through a tunnel since my isp wont allow port forwarding and the website host wont allow anything bigger than 125mb on there. for now i recommend filtering as much as possible to help with the speeds, and ill find a better solution soon.</p>

        <h3>- I cant find [x] / [x] is listed incorrectly!</h3>
        <p>I have a weird listing structure that i might change eventually, ill add a page explaining the structure soon.</p>
        
        <br>

        <h3>A few songs might be missing from the database, if you find them please let me know</h3>
    </main-element>

    <main-element>
        <h2>Know Issues</h2>
        <p>- Mobile formating is... eh</p>
        <p>- Mobile lyrics wrap break above 2</p>
        <p>- Some songs just... dont load?</p>
        <p>- CSS looks weird at ratios not ~9:16 or ~16:9, so no squares like 4:3</p>
    </main-element>

    <main-element>
        <h2>Connect</h2>
        <p>Join us on the <a href="https://reddit.com/r/mrkittyfans" target="_blank">subreddit</a> and <a href="https://discord.gg/H8MWmdkGcZ" target="_blank">discord</a>.</p>
    </main-element>
</body>
</html>