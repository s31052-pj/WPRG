<?php
include_once "includes/config.php";

$error = '';
$success = '';

if (!isset($_SESSION['user_initialized'])) {
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $init_username]);
        if (!$stmt->fetch()) {
            $hashed_password = password_hash($init_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, 'user')");
            $success = "Użytkownik 'user' został dodany.";
            file_put_contents('debug.txt', "Inicjalizacja użytkownika 'user' zakończona\n", FILE_APPEND);
        }
        $_SESSION['user_initialized'] = true;
    } catch (Exception $e) {
        $error = "Błąd inicjalizacji użytkownika: " . $e->getMessage();
        file_put_contents('debug.txt', "Błąd inicjalizacji: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Nazwa użytkownika i hasło są wymagane.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            if ($stmt->fetch()) {
                $error = "Nazwa użytkownika jest już zajęta.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, 'user')");
                $stmt->execute(['username' => $username, 'password_hash' => $hashed_password]);
                $success = "Rejestracja zakończona sukcesem. Możesz się zalogować.";
                logAction($conn, null, "Zarejestrowano użytkownika: $username");
            }
        } catch (Exception $e) {
            $error = "Błąd podczas rejestracji: " . $e->getMessage();
            file_put_contents('debug.txt', "Błąd rejestracji: " . $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Rejestracja</h1>
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
                <label>Nazwa użytkownika: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required></label><br>
                <label>Hasło: <input type="password" name="password" required></label><br>
                <button type="submit">Zarejestruj się</button>
            </form>
            <p><a href="login.php">Zaloguj się</a></p>
            <p><a href="index.php">Powrót do strony głównej</a></p>
        </main>
    </div>

    <footer>
        <p><a href="index.php">Strona główna</a> | Kontakt: <a href="contact.php">Formularz kontaktowy</a></p>
    </footer>
</body>
</html>