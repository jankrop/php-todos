<?php

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    die();
}

$conn = mysqli_connect('localhost', 'root', '', 'php_2025_1');

$stmt = $conn->prepare('DELETE FROM Todos WHERE id = ? AND username = ?');
$stmt->bind_param('is', $_GET['id'], $_SESSION['username']);
$stmt->execute();
$stmt->close();

$conn->close();

header('Location: index.php');
die();
