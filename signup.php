<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $password])) {
        echo "<script>alert('Signup successful!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error: Username or email exists.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
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
        <h1>Sign Up</h1>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign Up</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>
