<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <title>Template</title>
    <style>
        /* ...existing code... */

/* Footer Styles */
.main-footer {
    position: fixed;
    left: 4em; /* Align with navbar width */
    right: 0;
    bottom: 0;
    height: 20vw; /* 384/1920 = 0.2, so 20vw keeps aspect ratio */
    min-height: 3em; /* Prevents too small on mobile */
    max-height: 25vh; /* Prevents too tall on huge screens */
    background: url('../assets/footer.png') center/100% 100% no-repeat;
    /* Stretches image to fill footer, no cropping, no empty space */
    z-index: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 -2px 16px rgba(0,0,0,0.3);
    border-top-right-radius: var(--main-br-radius-x2);
    padding: 0;
    margin: 0;
}

/* On mobile, make footer full width */
@media (max-width: 800px) {
    .main-footer {
        left: 0;
        border-top-left-radius: 0;
    }
}

/* Ensure body doesn't hide footer content */
body {
    padding-bottom: 5em !important; /* Match footer height */
}
/* ...existing code... */
    </style>
</head>
<body>
    <main-element class="welcome">
            <h1 title>Template</h1>
    </main-element>

    <main-element>
    </main-element>

    <!-- Footer -->
    <footer class="main-footer">
        abac
    </footer>
</body>
</html>