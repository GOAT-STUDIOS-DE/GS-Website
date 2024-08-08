<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "gsuser"; // Ersetze durch deinen Datenbank-Benutzernamen
$password = "GSidBSdWDB2024_!"; // Ersetze durch dein Datenbank-Passwort
$dbname = "userdb";

// Verbindung zur Datenbank herstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung prüfen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Heutiges Datum
$today = date('Y-m-d');

// Besuch in die Datenbank eintragen oder die Anzahl erhöhen
$sql = "INSERT INTO visits (visit_date, visits) VALUES ('$today', 1)
        ON DUPLICATE KEY UPDATE visits = visits + 1";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Visit recorded"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

// Verbindung schließen
$conn->close();
?>