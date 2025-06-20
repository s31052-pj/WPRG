<?php
include_once "config.php";

class User {
    public static function register($conn, $username, $password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
        $stmt->execute([
            'username' => $username,
            'password_hash' => $password_hash
        ]);
        return true;
    }

    public static function login($conn, $username, $password) {
        $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            setcookie('last_login', date('Y-m-d H:i:s'), time() + (86400 * 30));
            logAction($conn, $user['id'], "Zalogowano użytkownika: {$user['username']}");
            return true;
        }
        return false;
    }

    public static function resetPassword($conn, $user_id, $password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password_hash = :password_hash, reset_token = NULL WHERE id = :id");
        $stmt->execute(['password_hash' => $password_hash, 'id' => $user_id]);
        logAction($conn, $user_id, "Zresetowano hasło użytkownika ID: $user_id");
        return true;
    }
}
?>