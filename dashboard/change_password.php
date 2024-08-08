<?php
session_start(); // Start der Session

require 'connection.php'; // Einbinden der Datenbankverbindung

// Fehleranzeige für Entwicklungsumgebung aktivieren
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$errorMessage = '';
$successMessage = '';

// CSRF-Token generieren und in der Session speichern, wenn es noch nicht gesetzt ist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Überprüfen, ob das Formular abgeschickt wurde
if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];
    $csrfToken = $_POST['csrf_token'];

    // Überprüfen, ob der CSRF-Token gültig ist
    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        $errorMessage = 'Ungültige Anfrage. Bitte versuchen Sie es erneut.';
    } else {
        try {
            // Abrufen des aktuellen Passworts des Benutzers
            $stmt = $con->prepare('SELECT password FROM users WHERE username=:username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Überprüfen, ob das aktuelle Passwort korrekt ist
            if (password_verify($currentPassword, $user['password'])) {
                // Überprüfen, ob die neuen Passwörter übereinstimmen
                if ($newPassword === $confirmNewPassword) {
                    // Passwort-Sicherheitsprüfung
                    if (strlen($newPassword) < 8 || !preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[0-9]/", $newPassword)) {
                        $errorMessage = 'Das neue Passwort erfüllt nicht die Sicherheitsanforderungen!';
                    } else {
                        // Hashen des neuen Passworts
                        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                        // Aktualisieren des Passworts in der Datenbank
                        $updateStmt = $con->prepare('UPDATE users SET password=:password WHERE username=:username');
                        $updateStmt->bindParam(':password', $hashedNewPassword);
                        $updateStmt->bindParam(':username', $username);
                        $updateStmt->execute();

                        $successMessage = 'Passwort erfolgreich geändert!';
                    }
                } else {
                    $errorMessage = 'Die neuen Passwörter stimmen nicht überein!';
                }
            } else {
                $errorMessage = 'Das aktuelle Passwort ist falsch!';
            }
        } catch (PDOException $e) {
            $errorMessage = 'Fehler: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOAT Services | Passwort ändern</title>
</head>
<body>
    <div class="form-container">
        <form action="change_password.php" method="POST">
            <h1>Passwort ändern</h1>
            <div class="inputs-container">
                <!-- Eingabefeld für aktuelles Passwort -->
                <input type="password" placeholder="Aktuelles Passwort" name="current_password" autocomplete="off" class="input-box" required>
                <!-- Eingabefeld für neues Passwort -->
                <input type="password" placeholder="Neues Passwort" name="new_password" autocomplete="off" class="input-box" required>
                <!-- Eingabefeld für neues Passwort bestätigen -->
                <input type="password" placeholder="Neues Passwort bestätigen" name="confirm_new_password" autocomplete="off" class="input-box" required>
                <!-- CSRF-Token (hidden) -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <!-- Absenden-Button -->
                <button class="submit-btn" type="submit" name="submit">Ändern</button>
            </div>
            <!-- Anzeige der Fehlermeldung, falls vorhanden -->
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <!-- Anzeige der Erfolgsmeldung, falls vorhanden -->
            <?php if (!empty($successMessage)): ?>
                <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
