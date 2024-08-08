<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "username"; // Ersetze durch deinen Datenbank-Benutzernamen
$password = "password"; // Ersetze durch dein Datenbank-Passwort
$dbname = "website_stats";

// Verbindung zur Datenbank herstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung prüfen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Daten der letzten 30 Tage abrufen
$sql = "SELECT visit_date, visits FROM visits WHERE visit_date >= CURDATE() - INTERVAL 30 DAY";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// JSON-Daten zurückgeben
echo json_encode($data);

// Verbindung schließen
$conn->close();
?>