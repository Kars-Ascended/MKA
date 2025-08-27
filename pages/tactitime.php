<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <title>How long?</title>
</head>
<body>
    <main-element class="welcome">
            <h1 title>Time since "ill do eternity later"</h1>
    </main-element>
    <main-element>
    <?php
    $targetDate = strtotime('2025-04-09 00:14:00');
    ?>
    <h1 title id="countdown"></h1>
    <script>
    function updateCountdown() {
        const targetDate = <?php echo $targetDate ?> * 1000;
        const now = new Date().getTime();
        const diff = now - targetDate;

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('countdown').innerHTML = 
            days + " days, " + hours + " hours, " + 
            minutes + " minutes, " + seconds + " seconds";
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
    </script>
    <img src="../assets/later.png">
    </main-element>
</body>
</html>