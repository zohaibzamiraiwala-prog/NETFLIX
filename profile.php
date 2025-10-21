<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

// Update profile (simple: change password)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $_SESSION['user_id']]);
    echo "<script>alert('Password updated!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <style>
        body { background: #141414; color: #fff; font-family: Arial; padding: 20px; }
        form { max-width: 400px; margin: auto; }
        input { width: 100%; padding: 15px; margin: 10px 0; background: #333; border: none; color: #fff; border-radius: 5px; }
        button { background: #e50914; color: #fff; padding: 15px; border: none; width: 100%; cursor: pointer; border-radius: 5px; transition: background 0.3s; }
        button:hover { background: #f40612; }
        a { color: #e50914; text-decoration: none; }
        @media (max-width: 768px) { form { width: 100%; } }
    </style>
</head>
<body>
    <h1>Profile: <?php echo $_SESSION['username']; ?></h1>
    <form method="POST">
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit">Update Password</button>
    </form>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
