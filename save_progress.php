<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_POST['content_id']) || !isset($_POST['progress'])) {
    http_response_code(400);
    exit;
}
 
$user_id = $_SESSION['user_id'];
$content_id = $_POST['content_id'];
$progress = $_POST['progress'];
 
$stmt = $pdo->prepare("INSERT INTO watch_history (user_id, content_id, progress) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE progress = ?, last_watched = CURRENT_TIMESTAMP");
$stmt->execute([$user_id, $content_id, $progress, $progress]);
?>
