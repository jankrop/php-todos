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
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
<!--        <main>-->
<!--            <p><a href="logout.php">Wyloguj się</a></p>-->
<!--            --><?php //while ($row = $tasks->fetch_assoc()) {
//                if ($row['completed']) {
//                    echo $row['date_created'] . ': ' . '<s>' . $row['name'] . '</s>';
//                } else {
//                    echo $row['date_created'] . ': ' . $row['name'] . ' <a href="completeTodo.php?id=' . $row['id'] . '">Zakończ</a>';
//                }
//                echo ' <a href="deleteTodo.php?id=' . $row['id'] . '">Usuń</a><br>';
//            } ?>
<!--            <form method="post" action="addTodo.php">-->
<!--                <input type="text" name="name" placeholder="Dodaj zadanie">-->
<!--                <input type="submit" value="Dodaj">-->
<!--            </form>-->
<!--        </main>-->
        <header>Cześć <?= $_SESSION['username'] ?> &nbsp;&nbsp;&nbsp; <a href="logout.php">Wyloguj się</a></header>
        <main>
            <?php if ($tasks->num_rows == 0): ?>
                <div>Brak zadań!</div>
            <?php endif; ?>

            <?php while ($row = $tasks->fetch_assoc()): ?>
                <div class="task <?= $row['completed'] ? 'done' : '' ?>">
                    <div class="name">
                        <?php if ($row['completed']): ?>
                            (zrobione)
                        <?php endif; ?>
                        <?= $row['name'] ?>
                    </div>
                    <div class="actions">
                        <?php if (!$row['completed']): ?>
                            <a href="completeTodo.php?id=<?= $row['id'] ?>">Zakończ</a>
                        <?php endif; ?>
                        <a href="deleteTodo.php?id=<?= $row['id'] ?>">Usuń</a>
                    </div>
                </div>
            <?php endwhile; ?>
            <p></p>
            <form method="post" action="addTodo.php" class="task">
                <div class="name">
                    <input name="name" size="30" placeholder="Dodaj zadanie...">
                </div>
                <div class="actions">
                    <button type="submit">Dodaj</button>
                </div>
            </form>
        </main>
    </body>
</html>

