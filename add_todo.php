<?php
session_start();

$name = $_POST['name'];

$conn = mysqli_connect('localhost', 'root', '', 'php_2025_1');

$stmt = $conn->prepare('
    INSERT INTO Todos (name, username)
    VALUES (?, ?);
');

$stmt->bind_param('ss', $name, $_SESSION['username']);

$stmt->execute();

$conn->close();

header('Location: index.php');
die();
