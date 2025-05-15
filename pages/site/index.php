<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../../backend/meta-include.php'; ?>
    <?php include '../../backend/navs/site-nav.php'; ?>
    <title>Information</title>
</head>
<body>
    <main-element class="welcome"> <h1 title>Information</h1> </main-element>

    <main-element>
        <?php
        $host = 'https://emacs-expressions-be-customers.trycloudflare.com';
        
        $ch = curl_init($host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $pingable = ($httpCode >= 200 && $httpCode < 300);

        echo "<h2>Archive Status</h2>";
        echo "<p style='font-size: 1.2em;'>";
        if ($pingable) {
            echo "<span style='color: #2ecc71;'>●</span> ";  // Green dot
            echo "<strong style='color: #2ecc71;'>Online</strong>";
        } else {
            echo "<span style='color: #e74c3c;'>●</span> ";  // Red dot
            echo "<strong style='color: #e74c3c;'>Offline</strong>";
        }
        echo "</p>";
        ?>

        
        <h3>The actual songs are stored on an old laptop and are forwarded through a tunnel. if the status is offline unfortunatly no songs nor images will load.</h3>
    </main-element>

    <main-element>
        <h2>Roadmap</h2>
        <p>Soon.</p>
    </main-element>
</body>
</html>