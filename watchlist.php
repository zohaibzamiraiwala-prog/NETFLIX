<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$user_id = $_SESSION['user_id'];
 
// Get watchlist
$watchlist_stmt = $pdo->prepare("SELECT c.* FROM content c JOIN watchlist w ON c.id = w.content_id WHERE w.user_id = ? ORDER BY w.added_at DESC");
$watchlist_stmt->execute([$user_id]);
$watchlist_items = $watchlist_stmt->fetchAll(PDO::FETCH_ASSOC);
 
// Recommendations: based on genres from watch history
$genres_stmt = $pdo->prepare("SELECT DISTINCT genre FROM content c JOIN watch_history h ON c.id = h.content_id WHERE h.user_id = ?");
$genres_stmt->execute([$user_id]);
$genres = $genres_stmt->fetchAll(PDO::FETCH_COLUMN);
 
// Handle recommendations
$rec_items = [];
if (!empty($genres)) {
    $placeholders = implode(',', array_fill(0, count($genres), '?'));
    $rec_sql = "SELECT * FROM content WHERE genre IN ($placeholders) AND id NOT IN (SELECT content_id FROM watchlist WHERE user_id = ?) LIMIT 5";
    $rec_stmt = $pdo->prepare($rec_sql);
    $rec_stmt->execute(array_merge($genres, [$user_id]));
    $rec_items = $rec_stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - Netflix Clone</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #141414; color: #fff; font-family: Arial, sans-serif; padding: 20px; }
        header { background: rgba(0,0,0,0.8); padding: 15px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; width: 100%; z-index: 10; }
        .logo { font-size: 26px; color: #e50914; font-weight: bold; }
        nav a { color: #fff; margin: 0 15px; text-decoration: none; font-size: 16px; }
        nav a:hover { color: #e50914; }
        .container { margin-top: 80px; }
        h1, h2 { margin-bottom: 20px; font-size: 24px; }
        .content-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; padding-bottom: 20px; }
        .content-item { position: relative; transition: transform 0.3s ease; }
        .content-item img { width: 100%; height: 300px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); }
        .content-item:hover { transform: scale(1.05); cursor: pointer; }
        .content-item p { text-align: center; margin-top: 8px; font-size: 14px; }
        .remove { position: absolute; top: 10px; right: 10px; background: #e50914; color: #fff; padding: 5px 10px; border-radius: 50%; cursor: pointer; font-size: 14px; transition: background 0.3s; }
        .remove:hover { background: #f40612; }
        .empty { text-align: center; font-size: 18px; color: #999; margin: 20px 0; }
        a.back { color: #e50914; text-decoration: none; display: block; margin-top: 20px; font-size: 16px; }
        a.back:hover { text-decoration: underline; }
        @media (max-width: 768px) {
            .content-grid { grid-template-columns: 1fr; }
            nav a { margin: 0 10px; font-size: 14px; }
            .logo { font-size: 22px; }
            h1, h2 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Netflix Clone</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="search.php">Search</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h1>Your Watchlist</h1>
        <?php if (empty($watchlist_items)): ?>
            <p class="empty">Your watchlist is empty. Add some content!</p>
        <?php else: ?>
            <div class="content-grid">
                <?php foreach ($watchlist_items as $item): ?>
                    <div class="content-item">
                        <img src="<?php echo htmlspecialchars($item['poster_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" onclick="window.location.href='watch.php?id=<?php echo $item['id']; ?>'">
                        <p><?php echo htmlspecialchars($item['title']); ?></p>
                        <div class="remove" onclick="window.location.href='add_to_watchlist.php?id=<?php echo $item['id']; ?>&remove=1&redirect=watchlist.php'">X</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <h2>Recommendations</h2>
        <?php if (empty($rec_items)): ?>
            <p class="empty">No recommendations yet. Watch some content to get personalized suggestions!</p>
        <?php else: ?>
            <div class="content-grid">
                <?php foreach ($rec_items as $item): ?>
                    <div class="content-item" onclick="window.location.href='watch.php?id=<?php echo $item['id']; ?>'">
                        <img src="<?php echo htmlspecialchars($item['poster_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <p><?php echo htmlspecialchars($item['title']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <a href="index.php" class="back">Back to Home</a>
    </div>
</body>
</html>
