<?php
include_once "includes/config.php";

if (isset($_SESSION['user_id'])) {
    logAction($conn, $_SESSION['user_id'], "Wylogowano użytkownika: {$_SESSION['username']}");
    session_destroy();
    setcookie('last_login', '', time() - 3600);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wylogowano</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Wylogowano</h1>
    </header>

    <div class="container">
        <nav>
            <ul>
                <li><a href="index.php">Strona główna</a></li>
                <li><a href="login.php">Logowanie</a></li>
                <li><a href="register.php">Rejestracja</a></li>
                <li><a href="contact.php">Kontakt</a></li>
            </ul>
        </nav>

        <main>
            <p>Pomyślnie wylogowano. <a href="login.php">Zaloguj się ponownie</a></p>
        </main>
    </div>

    <footer>
        <p>Kontakt: <a href="contact.php">Formularz kontaktowy</a></p>
    </footer>
</body>
</html>