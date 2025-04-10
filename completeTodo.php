<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    die();
}

$conn = mysqli_connect('localhost', 'root', '', 'php_2025_1');

$stmt = $conn->prepare('UPDATE Todos SET completed = 1 WHERE id = ? AND username = ?');
$stmt->bind_param('is', $_GET['id'], $_SESSION['username']);
$stmt->execute();
$stmt->close();

$conn->close();

header('Location: index.php');
die();
