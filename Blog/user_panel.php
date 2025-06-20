<?php
include_once "includes/config.php";

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($new_password) || empty($confirm_password)) {
        $error = "Oba pola hasła są wymagane.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Hasła nie są identyczne.";
    } else {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->execute(['password' => $hashed_password, 'id' => $_SESSION['user_id']]);
            $success = "Hasło zostało zmienione.";
            logAction($conn, $_SESSION['user_id'], "Zmieniono hasło");
        } catch (Exception $e) {
            $error = "Błąd podczas zmiany hasła: " . $e->getMessage();
            file_put_contents('debug.txt', "Błąd zmiany hasła: " . $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel użytkownika</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Panel użytkownika</h1>
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
            <h2>Witaj, <?php echo htmlspecialchars($_SESSION['username'] ?? 'użytkowniku!'); ?></h2>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <form method="POST">
                <label>Nowe hasło: <input type="password" name="new_password" required></label><br>
                <label>Potwierdź hasło: <input type="password" name="confirm_password" required></label><br>
                <button type="submit">Zmień hasło</button>
            </form>
            <p><a href="index.php">Powrót do strony głównej</a></p>
        </main>
    </div>

    <footer>
        <p><a href="index.php">Strona główna</a> | Kontakt: <a href="contact.php">Formularz kontaktowy</a></p>
    </footer>
</body>
</html>