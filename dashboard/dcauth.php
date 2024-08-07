<?php
// Starten der Session, um den Benutzerstatus zu verwalten
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['username'])) {
    // Wenn der Benutzer nicht eingeloggt ist, weiterleiten zur Login-Seite
    header("Location: index.php");
    exit();
}

// Konfigurationsdatei einbinden
require_once('config.php');

// Überprüfen, ob der Benutzer bereits mit Discord verknüpft ist
if (isset($_SESSION['discord_connected']) && $_SESSION['discord_connected']) {
    // Wenn der Benutzer bereits verknüpft ist, zur Dashboard-Seite weiterleiten
    header("Location: /dashboard/settings.php");
    exit();
}

// Definieren des OAuth2-Scopes
$scope = "identify"; // Die Berechtigungen können nach Bedarf erweitert werden
$auth_url = "https://discord.com/oauth2/authorize?client_id=" . CLIENT_ID . "&response_type=code&redirect_uri=" . urlencode(REDIRECT_URI) . "&scope=identify+email+connections+guilds+guilds.join+gdm.join";

// Weiterleiten des Benutzers zur Discord-OAuth2-Autorisierungsseite
header("Location: $auth_url");
exit(); // Sicherstellen, dass der Code nach der Weiterleitung nicht weiter ausgeführt wird
?>
