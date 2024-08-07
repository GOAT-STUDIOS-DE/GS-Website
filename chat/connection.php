<?php
$host = 'localhost';  
$db   = 'userdb';
$user = 'gsuser';  
$pass = 'GSidBSdWDB2024_!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $con = new PDO($dsn, $user, $pass, $options);
    echo "Verbindung erfolgreich!";
} catch (\PDOException $e) {
    echo 'Fehler: ' . $e->getMessage();
    // Protokolliere den Fehler in eine Datei (Optional)
    file_put_contents('pdo_errors.log', $e->getMessage(), FILE_APPEND);
}
?>
