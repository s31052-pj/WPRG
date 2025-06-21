<?php
include_once "includes/config.php";

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Nazwa użytkownika i hasło są wymagane.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id, username, password_hash AS password, role FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                setcookie('last_login', date('Y-m-d H:i:s'), time() + (86400 * 30));
                logAction($conn, $user['id'], "Zalogowano użytkownika: {$user['username']}");
                header("Location: index.php");
                exit;
            } else {
                $error = "Nieprawidłowa nazwa użytkownika lub hasło.";
                file_put_contents('debug.txt', "Błąd logowania: Nieprawidłowe dane dla $username\n", FILE_APPEND);
            }
        } catch (Exception $e) {
            $error = "Błąd podczas logowania: " . $e->getMessage();
            file_put_contents('debug.txt', "Błąd logowania: " . $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Logowanie</h1>
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
            <form method="POST">
                <label>Nazwa użytkownika: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required></label><br>
                <label>Hasło: <input type="password" name="password" required></label><br>
                <button type="submit">Zaloguj się</button>
            </form>
            <p><a href="register.php">Zarejestruj się</a></p>
            <p><a href="index.php">Powrót do strony głównej</a></p>
        </main>
    </div>

    <footer>
        <p><a href="index.php">Strona główna</a> | Kontakt: <a href="contact.php">Formularz kontaktowy</a></p>
    </footer>
</body>
</html>