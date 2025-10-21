<?php
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Invalid credentials.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { background: #141414 url('https://via.placeholder.com/1920x1080?text=Netflix+BG') no-repeat center/cover; color: #fff; font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background: rgba(0,0,0,0.85); padding: 40px; border-radius: 5px; width: 400px; }
        input { width: 100%; padding: 15px; margin: 10px 0; background: #333; border: none; color: #fff; border-radius: 5px; }
        button { background: #e50914; color: #fff; padding: 15px; border: none; width: 100%; cursor: pointer; font-size: 18px; border-radius: 5px; transition: background 0.3s; }
        button:hover { background: #f40612; }
        a { color: #fff; text-decoration: none; }
        @media (max-width: 768px) { form { width: 90%; } }
    </style>
</head>
<body>
    <form method="POST">
        <h1>Sign In</h1>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign In</button>
        <p>New to Netflix? <a href="signup.php">Sign up now</a></p>
    </form>
</body>
</html>
