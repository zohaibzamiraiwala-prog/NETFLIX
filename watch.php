<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$content_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM content WHERE id = ?");
$stmt->execute([$content_id]);
$content = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$content) {
    echo "<script>alert('Content not found.'); window.location.href = 'index.php';</script>";
    exit;
}
 
// Increment views
$pdo->prepare("UPDATE content SET views = views + 1 WHERE id = ?")->execute([$content_id]);
 
// Get progress
$progress_stmt = $pdo->prepare("SELECT progress FROM watch_history WHERE user_id = ? AND content_id = ?");
$progress_stmt->execute([$_SESSION['user_id'], $content_id]);
$progress = $progress_stmt->fetchColumn() ?: 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Watching: <?php echo $content['title']; ?></title>
    <style>
        body { background: #000; color: #fff; font-family: Arial; margin: 0; }
        video { width: 100%; height: 100vh; object-fit: contain; }
        .controls { position: absolute; bottom: 20px; left: 20px; }
        button { background: #e50914; color: #fff; padding: 10px; border: none; cursor: pointer; border-radius: 5px; margin-right: 10px; transition: background 0.3s; }
        button:hover { background: #f40612; }
        @media (max-width: 768px) { video { height: auto; } }
    </style>
</head>
<body>
    <video id="player" controls autoplay>
        <source src="<?php echo $content['video_url']; ?>" type="video/mp4">
    </video>
    <div class="controls">
        <button onclick="window.location.href='add_to_watchlist.php?id=<?php echo $content_id; ?>&redirect=watch.php?id=<?php echo $content_id; ?>'">Add to Watchlist</button>
        <button onclick="window.location.href='index.php'">Back</button>
    </div>
    <script>
        const player = document.getElementById('player');
        player.currentTime = <?php echo $progress; ?>;
        let interval = setInterval(() => {
            if (player.currentTime > 0) {
                fetch('save_progress.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `content_id=<?php echo $content_id; ?>&progress=${Math.floor(player.currentTime)}`
                });
            }
        }, 5000); // Save every 5 seconds
    </script>
</body>
</html>
