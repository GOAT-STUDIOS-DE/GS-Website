<?php
// Start the session to manage user authentication state
session_start();

// Include the configuration file
require_once('config.php');

// Check if the user is logged in by verifying the 'username' session variable
if (!isset($_SESSION['username'])) {
    // If the user is not logged in, redirect them to the login page (index.php)
    header("Location: index.php");
    exit(); // Exit to ensure no further code is executed
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOAT Services | Dashboard</title>
    <link rel="shortcut icon" href="../assets/services.png" type="image/x-icon">
    <link rel="stylesheet" href="style_dashboard.css">
</head>
<body>
       
<h1>
    <?php if(isset($_SESSION['is_developer']) && $_SESSION['is_developer']): ?>
        <div id="developerBadge" style="display: inline-block; background-image: url('../assets/dev-badge.png'); background-size: contain; background-repeat: no-repeat; padding-left: 50px; background-position: 10px center;">GOAT Services</div>
        <span style="vertical-align: middle;"> - Entwicklermodus</span>
    <?php else: ?>
        <div id="developerBadge">GOAT Services</div>
    <?php endif;?>
</h1>

<div class="container mt-4">
    <div class="main-content">
        <h1>Welcome to your Dashboard</h1>
    </div>
    <?php if(isset($_SESSION["username"])): ?>
        <h2>Benutzername: <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
    <?php else: ?>
        <h2>Benutzername: Unbekannt</h2>
    <?php endif; ?>
    <?php if(isset($_SESSION["email"])): ?>
        <h2>Emailadresse: <?php echo htmlspecialchars($_SESSION["email"]); ?></h2>
    <?php else: ?>
        <h2>Emailadresse: Unbekannt</h2>
    <?php endif; ?>
    <?php if(isset($_SESSION["discord_username"])): ?>
        <h2>Discord-Benutzername: <?php echo htmlspecialchars($_SESSION["discord_username"]); ?></h2>
    <?php else: ?>
        <h2>Discord-Benutzername: Nicht verfügbar</h2>
    <?php endif; ?>

    <h1><a href="settings.php">Settings</a></h1>

    <?php if(isset($_SESSION['is_developer']) && $_SESSION['is_developer']): ?>
        <h1><a href="https://goat-services.de/chat">Dev Chat</a></h1>
    <?php endif; ?>

    <h1><a href="logout.php">Logout</a></h1>
</div>
</body>
</html>
