<?php
// Włączanie wyświetlania błędów PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Konfiguracja połączenia z bazą danych
$host = 'localhost';
$dbname = 'blog';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    file_put_contents('debug.txt', "Błąd połączenia z bazą: " . $e->getMessage() . "\n", FILE_APPEND);
    die("Błąd połączenia: " . htmlspecialchars($e->getMessage()));
}

// Uruchamianie sesji tylko, jeśli nie jest aktywna
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generowanie tokenu CSRF, jeśli jeszcze nie istnieje
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Funkcja do logowania akcji
function logAction($conn, $user_id, $action) {
    try {
        $stmt = $conn->prepare("INSERT INTO logs (user_id, action) VALUES (:user_id, :action)");
        $stmt->execute(['user_id' => $user_id, 'action' => $action]);
        file_put_contents('logs.txt', "[$user_id] $action: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    } catch (PDOException $e) {
        file_put_contents('debug.txt', "Błąd logowania akcji: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}
?>