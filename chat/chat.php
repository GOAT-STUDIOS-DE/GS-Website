<?php
session_start();

// Datenbankverbindung
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
} catch (\PDOException $e) {
    echo 'Fehler: ' . $e->getMessage();
    exit;
}

// Nachrichten senden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Du bist nicht angemeldet!']);
        exit;
    }
    
    $message = trim($_POST['message']);
    $user_id = $_SESSION['user_id'];

    if (!empty($message)) {
        $stmt = $con->prepare('INSERT INTO chat_messages (user_id, message, sent_at) VALUES (:user_id, :message, NOW())');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
        echo json_encode(['success' => 'Nachricht gesendet']);
        exit;
    } else {
        echo json_encode(['error' => 'Nachricht darf nicht leer sein']);
        exit;
    }
}

// Nachrichten abrufen
$stmt = $con->prepare('
    SELECT cm.id, u.username, cm.message, cm.sent_at
    FROM chat_messages cm
    JOIN users u ON cm.user_id = u.id
    ORDER BY cm.sent_at ASC
');
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="chat-container">
        <div class="messages" id="messages">
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <span class="username" data-username="<?php echo htmlspecialchars($message['username']); ?>"><?php echo htmlspecialchars($message['username']); ?>:</span>
                    <span class="text"><?php echo htmlspecialchars($message['message']); ?></span>
                    <span class="timestamp"><?php echo htmlspecialchars($message['sent_at']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <form action="chat.php" method="POST" id="message-form" class="message-form">
            <input type="text" name="message" placeholder="Nachricht eingeben..." required>
            <button type="submit">Senden</button>
        </form>
    </div>
</body>
</html>
