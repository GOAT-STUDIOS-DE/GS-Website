<?php
// Session starten
session_start();

// Verbindung zur Datenbank herstellen
require 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOAT Services - Dashboard</title>
    <link rel="stylesheet" href="style_dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="dropdown.js" defer></script>
    <script src="../js/fade-in.js" defer></script>
</head>
<body>
    <div class="upper-container">
        <h1>
            <?php if(isset($_SESSION['is_developer']) && $_SESSION['is_developer']): ?>
                <div id="developerBadge" style="display: inline-block; background-image: url('../assets/dev-badge.png'); background-size: contain; background-repeat: no-repeat; padding-left: 50px; background-position: 10px center;">
                    GOAT Services
                </div>
                <span style="vertical-align: middle;"> - Entwicklermodus</span>
            <?php else: ?>
                <div id="developerBadge">GOAT Services</div>
            <?php endif; ?>
        </h1>
        <div class="profile-container">
            <img src="placeholder-person.jpg" alt="Profile Image" class="pfp" id="profileImage">
            <div id="dropdownMenu" class="dropdown-menu">
                <a href="#" class="dropdown-item"><i class='bx bx-user'></i>Profile</a>
                <a href="#" class="dropdown-item" id="last"><i class='bx bx-cog'></i>Settings</a>
                <a href="#" class="dropdown-item" id="logout-btn"><i class='bx bx-log-out'></i>Logout</a>
            </div>
        </div>
    </div>

    <section id="section-1">
        <div class="section-1-ct">
            <div class="box small hidden">
                <h1>Your Orders</h1>
                <p>Nothing to see here.</p>
                <a href="">Take an order</a>
            </div>

            <div class="box small hidden">
                <h1>Linked Apps</h1>
                <div class="linked-app-ct">
                    <div class="linked-app">
                        <i class='bx bxl-discord-alt'></i>
                        <p>
                            <?php if(isset($_SESSION["discord_username"])): ?>
                                Linked as <?php echo htmlspecialchars($_SESSION["discord_username"]); ?>
                            <?php else: ?>
                                Not Linked
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section-2">
        <div class="section-2-ct">
            <div class="box xxl hidden" id="notlinked">
                <h1>Profile Views</h1>
            </div>
            <div class="overlay-text hidden">
                <?php if(!isset($_SESSION["discord_username"])): ?>
                    You need to <a href="">link your Discord</a> account before you can gain access to this.
                <?php else: ?>
                    <a href="https://goat-services.de/discordpages/<?php echo htmlspecialchars($_SESSION["discord_username"]); ?>">https://goat-services.de/discordpages/<?php echo htmlspecialchars($_SESSION["discord_username"]); ?></a> 
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="section-3">
        <div class="section-3-ct">
            <div class="left-column">
                <div class="box small hidden">
                    <div class="application-ct">
                        <h1>Join Our Team</h1>
                        <a href="" class="btn-basic">Apply</a>
                    </div>
                </div>
                <div class="box small hidden">
                    <div class="contact-ct">
                        <h1>Contact</h1>
                        <a href="" class="btn-basic">Send us a Message</a>
                    </div>
                </div>
            </div>
            <div class="box large hidden">
                <h1>Suggested</h1>
            </div>
        </div>
    </section>
    <div class="rest"></div>

</body>
</html>
