<?php
$host = 'localhost'; // Assume localhost; change if different
$dbname = 'dbgobykn03yakt';
$username = 'uiumzmgo1eg2q';
$password = 'kuqi5gwec3tv';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
