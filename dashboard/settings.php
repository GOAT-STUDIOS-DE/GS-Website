<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['username'])) {
    header("Location: /dashboard/login.php");
    exit();
}

// Überprüfen, ob der Benutzer mit Discord verknüpft ist
if (!isset($_SESSION['discord_connected']) || !$_SESSION['discord_connected']) {
    // Wenn der Benutzer nicht verknüpft ist, nur Links anzeigen
    $showKey = false;
} else {
    // Wenn der Benutzer verknüpft ist, Schlüssel anzeigen
    $showKey = true;
    $file = '/var/www/goat-services.de/html/discordpages/keys.txt';

    // Funktion, um einen zufälligen Schlüssel zu erhalten
    function getRandomKey($file) {
        if (!file_exists($file)) {
            http_response_code(500);
            return 'Datei nicht gefunden.';
        }

        $keys = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($keys)) {
            http_response_code(500);
            return 'Keine Schlüssel verfügbar.';
        }

        return $keys[array_rand($keys)];
    }

    if (!isset($_SESSION['key'])) {
        $_SESSION['key'] = getRandomKey($file);
    }
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
    <h1><a href="dcauth.php">Connect via Discord</a></h1>
    <h1><a href="https://goat-services.de/dashboard/homepage.php">Zurück</a></h1>
    
    <?php if ($showKey): ?>
        <div>
            <p><strong>Du hast jetzt einen Schlüssel für den Bot „GS | Profile Management“ auf dem offiziellen GOAT Services Discord-Server. Verwende den Befehl /create &lt;key&gt;, wobei du &lt;key&gt; durch den dir zugewiesenen Schlüssel ersetzt. Viel Spaß mit deiner eigenen GOAT Services Profilseite!</strong></p>
            <p><strong>Dein Schlüssel:</strong> <?= htmlspecialchars($_SESSION['key']) ?></p>
        </div>
    <?php else: ?>
        <p>Bitte verknüpfen Sie Ihr Discord-Konto, um einen Schlüssel zu erhalten.</p>
    <?php endif; ?>
</body>
</html>
