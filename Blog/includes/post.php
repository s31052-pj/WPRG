<?php
class Post {
    public static function getAll($conn) {
        $stmt = $conn->query("SELECT p.*, u.username AS author, DATE_FORMAT(p.published_at, '%Y-%m') AS month
                              FROM posts p
                              LEFT JOIN users u ON p.author_id = u.id
                              ORDER BY p.published_at DESC");
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $grouped = [];
        foreach ($posts as $post) {
            $grouped[$post['month']][] = $post;
        }
        return $grouped;
    }

    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT p.*, u.username AS author FROM posts p LEFT JOIN users u ON p.author_id = u.id WHERE p.id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function add($conn, $title, $content, $image_path, $author_id) {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, image_path, author_id) VALUES (:title, :content, :image_path, :author_id)");
        $stmt->execute(['title' => $title, 'content' => $content, 'image_path' => $image_path, 'author_id' => $author_id]);
        logAction($conn, $author_id, "Dodano wpis: $title");
    }

    public static function update($conn, $id, $title, $content, $image_path) {
        $stmt = $conn->prepare("UPDATE posts SET title = :title, content = :content, image_path = :image_path WHERE id = :id");
        $stmt->execute(['id' => $id, 'title' => $title, 'content' => $content, 'image_path' => $image_path]);
        logAction($conn, $_SESSION['user_id'], "Zaktualizowano wpis ID: $id");
    }

    public static function delete($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        logAction($conn, $_SESSION['user_id'], "Usunięto wpis ID: $id");
    }
}
?>