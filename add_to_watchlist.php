<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$user_id = $_SESSION['user_id'];
$content_id = (int)$_GET['id'];
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
 
// Validate content_id
$stmt = $pdo->prepare("SELECT id FROM content WHERE id = ?");
$stmt->execute([$content_id]);
if (!$stmt->fetch()) {
    echo "<script>alert('Content not found.'); window.location.href = 'index.php';</script>";
    exit;
}
 
// Add or remove from watchlist
if (isset($_GET['remove']) && $_GET['remove'] == '1') {
    $stmt = $pdo->prepare("DELETE FROM watchlist WHERE user_id = ? AND content_id = ?");
    $stmt->execute([$user_id, $content_id]);
    echo "<script>alert('Removed from watchlist.'); window.location.href = '$redirect';</script>";
} else {
    $stmt = $pdo->prepare("INSERT IGNORE INTO watchlist (user_id, content_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $content_id]);
    echo "<script>alert('Added to watchlist.'); window.location.href = '$redirect';</script>";
}
?>
