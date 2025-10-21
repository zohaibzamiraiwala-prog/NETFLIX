<?php
include 'db.php';

$queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_username (username),
        INDEX idx_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "CREATE TABLE IF NOT EXISTS content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        genre VARCHAR(100),
        actors VARCHAR(255),
        type ENUM('movie', 'tv') NOT NULL,
        video_url VARCHAR(255) NOT NULL,
        poster_url VARCHAR(255) NOT NULL,
        views INT DEFAULT 0,
        is_featured TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_title (title),
        INDEX idx_genre (genre),
        INDEX idx_type (type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "CREATE TABLE IF NOT EXISTS watchlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        content_id INT NOT NULL,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE,
        UNIQUE KEY unique_watchlist (user_id, content_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "CREATE TABLE IF NOT EXISTS watch_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        content_id INT NOT NULL,
        progress INT DEFAULT 0,
        last_watched TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE,
        UNIQUE KEY unique_history (user_id, content_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

foreach ($queries as $query) {
    $pdo->exec($query);
}

// Insert sample data
$sample_content = [
    ['title' => 'Big Buck Bunny', 'description' => 'Animated adventure.', 'genre' => 'Animation,Comedy', 'actors' => 'Bunny', 'type' => 'movie', 'video_url' => 'https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4', 'poster_url' => 'https://via.placeholder.com/200x300?text=Big+Buck+Bunny', 'is_featured' => 1, 'views' => 100],
    ['title' => 'Sintel', 'description' => 'Fantasy story.', 'genre' => 'Fantasy,Action', 'actors' => 'Sintel', 'type' => 'movie', 'video_url' => 'https://sample-videos.com/video123/mp4/480/big_buck_bunny_480p_30mb.mp4', 'poster_url' => 'https://via.placeholder.com/200x300?text=Sintel', 'is_featured' => 0, 'views' => 50],
    ['title' => 'Tears of Steel', 'description' => 'Sci-Fi thriller.', 'genre' => 'Sci-Fi,Drama', 'actors' => 'Robots', 'type' => 'movie', 'video_url' => 'https://sample-videos.com/video123/mp4/360/big_buck_bunny_360p_10mb.mp4', 'poster_url' => 'https://via.placeholder.com/200x300?text=Tears+of+Steel', 'is_featured' => 1, 'views' => 200],
    ['title' => 'Elephants Dream', 'description' => 'Surreal dream.', 'genre' => 'Animation,Sci-Fi', 'actors' => 'Proog', 'type' => 'tv', 'video_url' => 'https://sample-videos.com/video123/mp4/240/big_buck_bunny_240p_5mb.mp4', 'poster_url' => 'https://via.placeholder.com/200x300?text=Elephants+Dream', 'is_featured' => 0, 'views' => 30],
    ['title' => 'Sample TV Show', 'description' => 'Episode 1.', 'genre' => 'Drama,Comedy', 'actors' => 'Actors', 'type' => 'tv', 'video_url' => 'https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_2mb.mp4', 'poster_url' => 'https://via.placeholder.com/200x300?text=Sample+TV', 'is_featured' => 1, 'views' => 150]
];

$stmt = $pdo->prepare("INSERT IGNORE INTO content (title, description, genre, actors, type, video_url, poster_url, is_featured, views) VALUES (:title, :description, :genre, :actors, :type, :video_url, :poster_url, :is_featured, :views)");

foreach ($sample_content as $item) {
    $stmt->execute($item);
}

echo "Tables created and sample data inserted successfully.";
?>
