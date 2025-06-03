<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8';
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $reason = $_POST['reason'];
    $ban_until = (int)$_POST['ban_until'];

    $ban_until_date = $ban_until > 0 ? date('Y-m-d H:i:s', strtotime("+$ban_until days")) : null;

    $stmt = $pdo->prepare("INSERT INTO bans (user_id, reason, ban_until) VALUES (:user_id, :reason, :ban_until)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':reason' => $reason,
        ':ban_until' => $ban_until_date,
    ]);

    header('Location: admin.php');
    exit();
}
?>
