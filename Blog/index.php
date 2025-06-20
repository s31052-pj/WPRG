<?php
include_once "includes/config.php";
include_once "includes/post.php";
include_once "includes/comment.php";

// Generowanie tokenu CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Funkcja do sanitizacji danych
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

$debug = [];
try {
    $posts = Post::getAll($conn);
    $debug[] = "Liczba postów: " . count($posts);
} catch (Exception $e) {
    $debug[] = "Błąd ładowania postów: " . $e->getMessage();
    file_put_contents('debug.txt', "Błąd ładowania postów: " . $e->getMessage() . "\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $debug[] = "Nieprawidłowy token CSRF";
        file_put_contents('debug.txt', "Nieprawidłowy token CSRF: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    } else {
        $post_id = filter_var($_POST['post_id'], FILTER_VALIDATE_INT);
        $content = sanitize($_POST['comment_content']);
        $guest_name = isset($_POST['guest_name']) ? sanitize($_POST['guest_name']) : 'Gość';
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($post_id && $content) {
            try {
                Comment::add($conn, $post_id, $content, $user_id, $guest_name);
                header("Location: index.php");
                exit;
            } catch (Exception $e) {
                $debug[] = "Błąd dodawania komentarza: " . $e->getMessage();
                file_put_contents('debug.txt', "Błąd komentarza: " . $e->getMessage() . "\n", FILE_APPEND);
            }
        } else {
            $debug[] = "Nieprawidłowe dane komentarza";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witamy na blogu!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Witamy na blogu!</h1>
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
            <?php if (!empty($debug)): ?>
                <div style="color: red;">
                    <?php foreach ($debug as $msg): ?>
                        <p>Debug: <?php echo htmlspecialchars($msg); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($posts)): ?>
                <p>Brak postów do wyświetlenia. <a href="add_post.php">Dodaj nowy wpis!</a></p>
            <?php else: ?>
                <?php foreach ($posts as $month => $month_posts): ?>
                    <h2><?php echo htmlspecialchars($month); ?></h2>
                    <?php foreach ($month_posts as $post): ?>
                        <article>
                            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                            <?php
                            $image_path = $post['image_path'];
                            if ($image_path && file_exists($image_path)) {
                                echo "<img src='$image_path' alt='Obrazek posta' style='max-width: 100%;'>";
                            } else {
                                $debug[] = "Brak obrazka dla posta ID: {$post['id']}, ścieżka: $image_path";
                                file_put_contents('debug.txt', "Brak obrazka dla posta ID: {$post['id']}, ścieżka: $image_path\n", FILE_APPEND);
                                echo "<p>Brak obrazka.</p>";
                            }
                            ?>
                            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            <p>Autor: <?php echo htmlspecialchars($post['author']); ?>, Opublikowano: <?php echo htmlspecialchars($post['published_at']); ?></p>

                            <div class="post-nav">
                                <?php
                                $prev_post = $conn->query("SELECT id, title FROM posts WHERE id < {$post['id']} ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                                $next_post = $conn->query("SELECT id, title FROM posts WHERE id > {$post['id']} ORDER BY id ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                                if ($prev_post): ?>
                                    <a href="index.php?post_id=<?php echo $prev_post['id']; ?>">Poprzedni: <?php echo htmlspecialchars($prev_post['title']); ?></a>
                                <?php endif; ?>
                                <?php if ($next_post): ?>
                                    <a href="index.php?post_id=<?php echo $next_post['id']; ?>">Następny: <?php echo htmlspecialchars($next_post['title']); ?></a>
                                <?php endif; ?>
                            </div>

                            <h4>Komentarze:</h4>
                            <?php
                            $comments = Comment::getByPostId($conn, $post['id']);
                            if (empty($comments)): ?>
                                <p>Brak komentarzy.</p>
                            <?php else: ?>
                                <?php foreach ($comments as $comment): ?>
                                    <p><strong><?php echo htmlspecialchars($comment['author']); ?>:</strong> <?php echo htmlspecialchars($comment['content']); ?> (<?php echo htmlspecialchars($comment['created_at']); ?>)</p>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <form method="POST">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <?php if (!isset($_SESSION['user_id'])): ?>
                                    <label>Imię (gość): <input type="text" name="guest_name" value="<?php echo isset($guest_name) ? htmlspecialchars($guest_name) : ''; ?>"></label>
                                <?php endif; ?>
                                <label>Komentarz: <textarea name="comment_content" required><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea></label>
                                <button type="submit">Dodaj komentarz</button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>

    <footer>
        <p>Kontakt: <a href="contact.php">Formularz kontaktowy</a></p>
    </footer>
</body>
</html>