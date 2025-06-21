<?php
class Comment {
    public static function getByPostId($conn, $post_id) {
        $stmt = $conn->prepare("SELECT c.*, COALESCE(u.username, c.guest_name) AS author
                                FROM comments c
                                LEFT JOIN users u ON c.user_id = u.id
                                WHERE c.post_id = :post_id
                                ORDER BY c.created_at ASC");
        $stmt->execute(['post_id' => $post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add($conn, $post_id, $content, $user_id = null, $guest_name = 'Gość') {
        try {
            $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, guest_name, content)
                                    VALUES (:post_id, :user_id, :guest_name, :content)");
            $stmt->execute([
                'post_id' => $post_id,
                'user_id' => $user_id,
                'guest_name' => $user_id ? null : $guest_name,
                'content' => $content
            ]);
            file_put_contents('debug.txt', "Komentarz dodany: post_id=$post_id, user_id=$user_id, content=$content, time=" . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        } catch (PDOException $e) {
            file_put_contents('debug.txt', "Błąd dodawania komentarza: " . $e->getMessage() . "\n", FILE_APPEND);
            throw $e;
        }
    }
}
?>