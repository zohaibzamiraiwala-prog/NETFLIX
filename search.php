<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$query = isset($_GET['q']) ? $_GET['q'] : '';
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
 
$sql = "SELECT * FROM content WHERE 1=1";
$params = [];
if ($query) {
    $sql .= " AND (title LIKE ? OR actors LIKE ?)";
    $params[] = "%$query%";
    $params[] = "%$query%";
}
if ($genre) {
    $sql .= " AND genre LIKE ?";
    $params[] = "%$genre%";
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <style>
        body { background: #141414; color: #fff; font-family: Arial; padding: 20px; }
        form { margin-bottom: 20px; }
        input, select { padding: 10px; background: #333; border: none; color: #fff; border-radius: 5px; }
        button { background: #e50914; color: #fff; padding: 10px; border: none; cursor: pointer; border-radius: 5px; transition: background 0.3s; }
        button:hover { background: #f40612; }
        .content-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
        .content-item { transition: transform 0.3s; }
        .content-item img { width: 100%; height: 300px; object-fit: cover; border-radius: 5px; }
        .content-item:hover { transform: scale(1.05); cursor: pointer; }
        @media (max-width: 768px) { .content-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <h1>Search Content</h1>
    <form method="GET">
        <input type="text" name="q" placeholder="Title or Actor" value="<?php echo $query; ?>">
        <select name="genre">
            <option value="">All Genres</option>
            <option value="Action" <?php if($genre=='Action') echo 'selected'; ?>>Action</option>
            <option value="Drama" <?php if($genre=='Drama') echo 'selected'; ?>>Drama</option>
            <option value="Sci-Fi" <?php if($genre=='Sci-Fi') echo 'selected'; ?>>Sci-Fi</option>
            <option value="Comedy" <?php if($genre=='Comedy') echo 'selected'; ?>>Comedy</option>
        </select>
        <button type="submit">Search</button>
    </form>
    <div class="content-grid">
        <?php foreach ($results as $item): ?>
            <div class="content-item" onclick="window.location.href='watch.php?id=<?php echo $item['id']; ?>'">
                <img src="<?php echo $item['poster_url']; ?>" alt="<?php echo $item['title']; ?>">
                <p><?php echo $item['title']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <p><a href="index.php" style="color: #e50914;">Back to Home</a></p>
</body>
</html>
