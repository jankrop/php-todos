<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    die();
}

$conn = mysqli_connect('localhost', 'root', '', 'php_2025_1');

$tasksStmt = $conn->prepare("
    SELECT * FROM Todos 
    WHERE username = ?
    ORDER BY date_created DESC;
");
$tasksStmt->bind_param('s', $_SESSION['username']);
$tasksStmt->execute();
$tasks = $tasksStmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Strona główna</title>
    </head>
    <body>
        <h1>Witaj <?= $_SESSION['username'] ?></h1>
        <p><a href="logout.php">Wyloguj się</a></p>
        <?php while ($row = $tasks->fetch_assoc()) {
            if ($row['completed']) {
                echo $row['date_created'] . ': ' . '<s>' . $row['name'] . '</s>';
            } else {
                echo $row['date_created'] . ': ' . $row['name'] . ' <a href="completeTodo.php?id=' . $row['id'] . '">Zakończ</a>';
            }
            echo ' <a href="deleteTodo.php?id=' . $row['id'] . '">Usuń</a><br>';
        } ?>
        <form method="post" action="addTodo.php">
            <input type="text" name="name" placeholder="Dodaj zadanie">
            <input type="submit" value="Dodaj">
        </form>
    </body>
</html>

