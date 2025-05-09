<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <title>Status</title>
</head>
<body>
    <main-element class="welcome"> <h1 title>Status</h1> </main-element>

    <main-element>
        <?php
        $host = 'emacs-expressions-be-customers.trycloudflare.com'; // Example IP (Google DNS)
        $output = null;
        $status = -1;

        // Execute ping command based on operating system
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec("ping -n 1 " . $host, $output, $status);
            $pingable = $status === 0;
        } else {
            exec("ping -c 1 " . $host, $output, $status);
            $pingable = $status === 0;
        }

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
    </main-element>

    <main-element>
        <h3>The actual songs are stored on an old laptop and are forwarded through a tunnel. if the status is offline unfortunatly no songs nor images will load.</h3>
    </main-element>
</body>
</html>