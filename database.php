<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=movie_manager;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    $_SESSION['database_error'] = $e->getMessage();
    header('Location: database_error.php');
    exit();
}