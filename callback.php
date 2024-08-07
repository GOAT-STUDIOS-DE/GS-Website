<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['username'])) {
    header("Location: /dashboard/login.php");
    exit();
}

// Konfigurationsdaten
$client_id = "1245490737131425866";
$client_secret = "f0SPkGcN6vuXRczfCpNs5z2qLWeAvvo5";
$redirect_uri = "https://goat-services.de/callback.php";
$token_url = "https://discord.com/api/oauth2/token";

// Parameter für die Token-Anfrage
$params = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'authorization_code',
    'code' => $_GET['code'],
    'redirect_uri' => $redirect_uri,
);

// Anfrage an Discord zur Token-Aktivierung
$curl = curl_init($token_url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$token_data = json_decode($response, true);

if (isset($token_data['access_token'])) {
    // Zugriffstoken ist vorhanden, Benutzerinformationen abrufen
    $user_url = "https://discord.com/api/v10/users/@me";
    $headers = array('Authorization: Bearer ' . $token_data['access_token']);
    $curl = curl_init($user_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $user_response = curl_exec($curl);
    curl_close($curl);

    $user_data = json_decode($user_response, true);

    // Überprüfen, ob Benutzerdaten abgerufen werden konnten
    if (isset($user_data['id'])) {
        $_SESSION['discord_connected'] = true;
        $_SESSION['discord_user'] = $user_data;

        // Optional: Speichern des Discord-Benutzernamens
        $_SESSION['discord_username'] = $user_data['username'];

        // Zurück zur settings.php weiterleiten
        header("Location: https://goat-services.de/dashboard/settings.php");
        exit();
    } else {
        // Fehler beim Abrufen der Benutzerdaten
        echo "Fehler beim Abrufen der Benutzerdaten von Discord.";
    }
} else {
    // Fehler beim Abrufen des Tokens
    echo "Fehler beim Abrufen des Tokens von Discord.";
}
?>
