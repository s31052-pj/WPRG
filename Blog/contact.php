<?php
include_once "includes/config.php";

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $contact_info = trim($_POST['contact_info'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($contact_info) || empty($message)) {
        $error = "Wszystkie pola są wymagane.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO contacts (name, contact_info, message) VALUES (:name, :contact_info, :message)");
            $stmt->execute(['name' => $name, 'contact_info' => $contact_info, 'message' => $message]);
            $success = "Wiadomość została wysłana.";
            logAction($conn, isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null, "Wysłano wiadomość kontaktową");
        } catch (Exception $e) {
            $error = "Błąd podczas wysyłania wiadomości: " . $e->getMessage();
            file_put_contents('debug.txt', "Błąd wysyłania wiadomości: " . $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Kontakt</h1>
    </header>

    <div class="container">
        <nav>
            <ul>
                <li><a href="index.php">Strona główna</a></li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="login.php">Logowanie</a></li>
                    <li><a href="register.php">Rejestracja</a></li>
                <?php else: ?>
                    <li><a href="user_panel.php">Panel użytkownika</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin_panel.php">Panel admina</a></li>
                    <?php endif; ?>
                    <li><a href="add_post.php">Dodaj wpis</a></li>
                    <li><a href="logout.php">Wyloguj</a></li>
                <?php endif; ?>
                <li><a href="contact.php">Kontakt</a></li>
            </ul>
        </nav>

        <main>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <form method="POST">
                <label>Imię: <input type="text" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required></label><br>
                <label>Informacje kontaktowe (np. telefon): <input type="text" name="contact_info" value="<?php echo isset($contact_info) ? htmlspecialchars($contact_info) : ''; ?>" required></label><br>
                <label>Wiadomość: <textarea name="message" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea></label><br>
                <button type="submit">Wyślij</button>
            </form>
            <p><a href="index.php">Powrót do strony głównej</a></p>
        </main>
    </div>

    <footer>
        <p><a href="index.php">Strona główna</a> | Kontakt: <a href="contact.php">Formularz kontaktowy</a></p>
    </footer>
</body>
</html>