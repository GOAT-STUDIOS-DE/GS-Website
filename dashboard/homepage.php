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
       
<div class="upper-container">
<h1>
    <?php if(isset($_SESSION['is_developer']) && $_SESSION['is_developer']): ?>
        <div id="developerBadge" style="display: inline-block; background-image: url('../assets/dev-badge.png'); background-size: contain; background-repeat: no-repeat; padding-left: 50px; background-position: 10px center;">GOAT Services</div>
        <span style="vertical-align: middle;"> - Entwicklermodus</span>
    <?php else: ?>
        <div id="developerBadge">GOAT Services</div>
    <?php endif;?>
</h1>
<div class="profile-container">
    <img src="placeholder-person.jpg" alt="" class="pfp" id="profilePicture">
    <div id="dropdownMenu" class="dropdown-content">
        <a href="settings.php">Settings</a>
        <?php if(isset($_SESSION['is_developer']) && $_SESSION['is_developer']): ?>
        <a href="https://goat-services.de/chat">Dev Chat</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>

</div>
</div>
</div>

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




</div>

<style>
    body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to bottom, #272727, #808080); /* Grau zu helleres Grau Verlauf */
    background-color: #808080;
    background-size: 100% 100vh; /* Die Größe des Hintergrunds auf 100% der Breite und 100vh der Höhe setzen */
    background-repeat: no-repeat; /* Verhindert das Wiederholen des Hintergrunds */
    margin: 0;
    padding: 0;
    color: #fff; /* Textfarbe auf Weiß setzen für bessere Lesbarkeit */
}

h1 {
    text-align: center;
    margin: 20px 0;
}

h1 a {
    color: #fff;
    text-decoration: none;
    font-size: 1.5rem;
}

h1 a:hover {
    text-decoration: underline;
}

.container {
    padding: 20px;
}

h2 {
    text-align: center;
    margin: 10px 0;
    color: #fff; /* Textfarbe auf Weiß setzen */
}
.pfp {
    width: 40px;
    height: 40px;
    border-radius: 33px;
    display: flex;
    align-self: center;
    cursor: pointer;
}
.upper-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px;
}
.upper-container h1 {
    flex-grow: 1;
    text-align: center;
    margin: 0;
}
.profile-container {
    position: relative;
    display: inline-block;
    margin: 5px;
}
.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #fff;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}
.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}
.dropdown-content a:hover {
    background-color: #ddd;
}
.show {
    display: block;
}

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var profilePicture = document.getElementById('profilePicture');
        var dropdownMenu = document.getElementById('dropdownMenu');

        profilePicture.addEventListener('click', function() {
            dropdownMenu.classList.toggle('show');
        });

        window.addEventListener('click', function(event) {
            if (!event.target.matches('.pfp')) {
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                }
            }
        });
    });
</script>
</body>
</html>
