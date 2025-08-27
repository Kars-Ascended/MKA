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
                <h1 title>Hey, welcome to the <u>M</u>r. <u>K</u>itty <u>A</u>rchive [MKA]</h1>
            </div>
    </main-element>

    <main-element>
        <h1>Site is currently under maintainance, will be up soon!</h1>
    </main-element>

    <main-element>
        <h2>FAQ</h2>
        <h3>- Where are the actual songs?</h3>
        <p>Being added, most are actually there but unable to load. ill fix it once ive finished all the other site features.</p>

        <h3>- What else do you plan to archive?</h3>
        <p>Songs, lyrics, releases, and possibly more. Unfortunatly Forrest's wishes are that i dont archive his posts (i think) so i will not have that public for the meantime, sorry</p>

        <h3>- I found a problem / something incorrect on the site</h3>
        <p>Let me know from a link somewhere <a href="/extras.php">here</a>.</p>

        <h3>- I cant find [x] / [x] is listed incorrectly!</h3>
        <p>All content ever released to my knowledge should be here, if im missing something contact me again with the above message ^
        <br>Some content online has not been obtained in a... legal way so i will not be hosting some things</p>
    </main-element>

    <main-element>
        <h2>Known Issues: 2</h2>
        <p>- Mobile lyrics wrap break above two</p>
        <p>- Some songs just... dont load?</p>
    </main-element>
    <?php include '../backend/meta/footer.php'; ?>
</body>
</html>