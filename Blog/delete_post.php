<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Usuń wpis</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Usuń wpis</h1>
    </header>

    <div class="container">
        <section>
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
            <?php
            include_once "includes/config.php";
            include_once "includes/post.php";

            if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'author'])) {
                header("Location: index.php");
                exit;
            }

            $post_id = $_GET['id'] ?? 0;
            $post = Post::getById($conn, $post_id);
            if (!$post) {
                header("Location: index.php");
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Post::delete($conn, $post_id);
                header("Location: index.php");
                exit;
            }
            ?>
            <p>Czy na pewno chcesz usunąć wpis: <strong><?php echo htmlspecialchars($post['title']); ?></strong>?</p>
            <form method="POST">
                <button type="submit">Usuń</button>
                <a href="index.php">Anuluj</a>
            </form>
        </section>
    </div>

    <footer>
        <p>Kontakt: <a href="contact.php">Formularz kontaktowy</a></p>
    </footer>
</body>
</html>