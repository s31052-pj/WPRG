<?php
include_once "includes/config.php";
include_once "includes/post.php";

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$post_id) {
    header("Location: index.php");
    exit;
}

$post = Post::getById($conn, $post_id);
if (!$post || ($post['author_id'] !== $_SESSION['user_id'] && $_SESSION['role'] !== 'admin')) {
    header("Location: index.php");
    exit;
}

// Generowanie tokenu CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Nieprawidłowy token CSRF";
        file_put_contents('debug.txt', "Nieprawidłowy token CSRF w edit_post.php: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    } else {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $image_path = $post['image_path'];

        if (empty($title) || empty($content)) {
            $error = "Tytuł i treść są wymagane.";
        } else {
            if ($_FILES['image']['size'] > 0) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                $image_path = $target_dir . basename($_FILES['image']['name']);
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $error = "Błąd podczas przesyłania obrazka.";
                    file_put_contents('debug.txt', "Błąd przesyłania obrazka: " . $_FILES['image']['error'] . "\n", FILE_APPEND);
                }
            }
            if (!$error) {
                try {
                    Post::update($conn, $post_id, $title, $content, $image_path);
                    header("Location: index.php");
                    exit;
                } catch (Exception $e) {
                    $error = "Błąd edycji wpisu: " . $e->getMessage();
                    file_put_contents('debug.txt', "Błąd edycji wpisu: " . $e->getMessage() . "\n", FILE_APPEND);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj wpis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Edytuj wpis</h1>
    </header>

    <div class="container">
        <nav>
    <ul>
        <li><a href="index.php">Strona główna</a></li>
        <!-- Pozostałe linki zależne od stanu zalogowania -->
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
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <label>Tytuł: <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required></label>
                <label>Treść: <textarea name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea></label>
                <label>Obrazek: <input type="file" name="image" accept="image/*"></label>
                <?php if ($post['image_path']): ?>
                    <p>Aktualny obrazek: <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Aktualny obrazek" style="max-width: 100px;"></p>
                <?php endif; ?>
                <button type="submit">Zapisz zmiany</button>
            </form>
        </main>
    </div>

    <footer>
        <p>Kontakt: <a href="contact.php">Formularz kontaktowy</a></p>
    </footer>
</body>
</html>