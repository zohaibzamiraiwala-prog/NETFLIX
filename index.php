<?php
session_start();
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
// Fetch featured and trending
$featured = $pdo->query("SELECT * FROM content WHERE is_featured = 1 LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
$trending = $pdo->query("SELECT * FROM content ORDER BY views DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Netflix Clone - Home</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #141414; color: #fff; font-family: Arial, sans-serif; }
        header { background: rgba(0,0,0,0.7); padding: 10px; display: flex; justify-content: space-between; align-items: center; position: fixed; width: 100%; z-index: 10; }
        .logo { font-size: 24px; color: #e50914; font-weight: bold; }
        nav a { color: #fff; margin: 0 15px; text-decoration: none; }
        nav a:hover { color: #e50914; }
        .hero { background: url('https://via.placeholder.com/1920x1080?text=Featured+Banner') no-repeat center/cover; height: 80vh; display: flex; align-items: center; justify-content: center; text-align: center; }
        .hero h1 { font-size: 48px; }
        .row { padding: 20px; }
        .row h2 { margin-bottom: 10px; }
        .content-grid { display: flex; overflow-x: auto; gap: 10px; }
        .content-item { min-width: 200px; transition: transform 0.3s; }
        .content-item img { width: 100%; height: 300px; object-fit: cover; border-radius: 5px; }
        .content-item:hover { transform: scale(1.1); cursor: pointer; }
        @media (max-width: 768px) { .hero h1 { font-size: 32px; } .content-grid { flex-direction: column; } }
    </style>
</head>
<body>
    <header>
        <div class="logo">Netflix</div>
        <nav>
            <a href="profile.php">Profile</a>
            <a href="search.php">Search</a>
            <a href="watchlist.php">Watchlist</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <section class="hero">
        <h1>Welcome, <?php echo $_SESSION['Zohaib Zamir']; ?>! Watch Now</h1>
    </section>
    <section class="row">
        <h2>Featured</h2>
        <div class="content-grid">
            <?php foreach ($featured as $item): ?>
                <div class="content-item" onclick="window.location.href='watch.php?id=<?php echo $item['id']; ?>'">
                    <img src="<?php echo $item['poster_url']; ?>" alt="<?php echo $item['title']; ?>">
                    <p><?php echo $item['title']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="row">
        <h2>Trending</h2>
        <div class="content-grid">
            <?php foreach ($trending as $item): ?>
                <div class="content-item" onclick="window.location.href='watch.php?id=<?php echo $item['id']; ?>'">
                    <img src="<?php echo $item['poster_url']; ?>" alt="<?php echo $item['title']; ?>">
                    <p><?php echo $item['title']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
