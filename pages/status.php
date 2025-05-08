<!DOCTYPE html>
<html>
<head>
    <title>IP Status Check</title>
</head>
<body>
    <h2>IP Status Checker</h2>
    
    <?php
    function isPingable($ip) {
        if (PHP_OS == 'WINNT') {
            // Windows
            exec("ping -n 1 -w 1 " . $ip, $output, $result);
        } else {
            // Linux/Unix/MacOS
            exec("ping -c 1 -W 1 " . $ip, $output, $result);
        }
        return $result === 0;
    }

    // Example IP address (you can make this dynamic with a form)
    $ip = "https://creative-split-curve-ingredients.trycloudflare.com"; // Google DNS server as example
    
    if (isPingable($ip)) {
        echo "<p style='color: green;'>IP Address $ip is online ✓</p>";
    } else {
        echo "<p style='color: red;'>IP Address $ip is offline ✗</p>";
    }
    ?>

</body>
</html></head>