<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <title>Goodbye.</title>
    <style>
        #goodbye-message {
            font-size: larger;
        }
    </style>
</head>
<body>
    <main-element class="welcome">
            <h1 title>Goodbye.</h1>
    </main-element>

    <button id="show-message-btn" style="font-size:1.2em;padding:1em 2em;margin:2em auto;display:block;">Click to Read [audio warning if you havent set it in settings!]</button>

    <main-element id="goodbye-message" style="display:none;">
        <p>
            Hi Everyone,
        </p>
        <p>
            I'm leaving. I've been in this community forever with you awesome people. However, as I turn 18 on the 30th, I'm realising how much of my life I'm almost 'wasting' locked behind a screen every day, dealing with drama in a dramatic community.
        </p>
        <p>
            Nobody has done any one specific thing — I mean, sure, there are some people who have done things I don't like, but you are not the sole cause of this. <strong>DON'T BLAME YOURSELF.</strong>
        </p>
        <p>
            *Incase above lines are not clear: I just need a break from the long time ive spent here and its not to do with anyone or anything done by anyone. 
        </p>

        <hr>

        <h3>Q&amp;A:</h3>
        <p>
            <strong>Q: What will happen to the Discord, subreddit, and website?</strong><br>
            A: I will turn the discord into a non-mk focused community if the people want, I will continue to manage and maintain the subreddit, and I will open-source the website once I find someone willing to take on its duty.
        </p>
        <p>
            <strong>Q: Why? I don't understand?</strong><br>
            A: I need a break, I guess? Maybe just peace for a short while? Your guess is as good as mine.
        </p>
        <p>
            <strong>Q: Are you planning to reenact the lyrics to Ephemeral track thirteen?</strong><br>
            A: No, those days are long gone. I'm OK in that regard.
        </p>
        <p>
            <strong>Q: Contact?</strong><br>
            A: Yes my dms will stay open and we can stay friends and message still, I'm just detaching myself from this specific community for a while.
        </p>
        <p>
            <strong>Q: Wishes?</strong><br>
            1: Remember me. Please. <br>
            I will probably return someday in one form or another — maybe even as Kars still. Once I do, it would be amazing to just hear myself being mentioned.<br>
            2: Credit me. <br>
            If the site gets hosted, put my name somewhere. If someone asks where we got content I found, mention me. This kind of stems from wish one.<br>
            3: Be better. <br>
            Many of you hold unjust hatred, rectify this not just for me or yourselves but for your community. I myself admit I tend to be biased towards certain people but I have made sure that does not affect me in this community.
        </p>

        <hr>

        <p>
            There is always a chance I return early. Could be a day? A week? A month? If I do return then... uh, I don't even know — that's a future Josh problem (yes, my name is Josh). Also, don't stress yourself waiting for me to return soon. I know at least one of you who will for sure.
        </p>
        <p>
            To anyone sad about this news: I'm sorry.<br>
            To anyone happy about this news: I've always tried to be understanding and stop myself from doing anything that others would not like, if I have somehow failed this then again, I'm sorry.
        </p>
        <p>
            If I'm able to put a message into words, some of you might get a special goodbye DM but I can't count on it.
        </p>
        <p>
            Anyway thanks all, it's been... unreal. no really, <em>thank you.</em><br>
            <em>In the dark, I'll do no wrong...</em>
        </p>
        <audio id="goodbye-audio" controls>
            <source src="https://gauge-flying-photos-rebecca.trycloudflare.com/discogs/main/Unreal/Perpetual.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    </main-element>

    <script>
    document.getElementById('show-message-btn').addEventListener('click', function() {
        document.getElementById('show-message-btn').style.display = 'none';
        var msg = document.getElementById('goodbye-message');
        msg.style.display = '';
        var audio = document.getElementById('goodbye-audio');
        audio.muted = false;
        audio.play().catch(function(){ /* Autoplay might still be blocked */ });
    });
    </script>
    <?php include '../backend/meta/footer.php'; ?>
</body>
</html>