<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    die();
}

$conn = mysqli_connect('localhost', 'root', '', 'php_2025_1');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare('
        UPDATE Todos
        SET name = ?
        WHERE id = ? AND username = ?;
    ');
    $stmt->bind_param('sis', $_POST['name'], $_GET['id'], $_SESSION['username']);
    $stmt->execute();

    $stmt->close();

    $conn->close();

    header('Location: index.php');
    die();
} else {
    $stmt = $conn->prepare('
        SELECT name FROM Todos WHERE id = ? AND username = ?;
    ');
    $stmt->bind_param('is', $_GET['id'], $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $taskName = $result->fetch_assoc()['name'];
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edytuj zadanie: <?= htmlspecialchars($taskName) ?></title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <header>Edytuj zadanie</header>
        <form method="post" action="">
            <label for="name">Nazwa:</label>
            <input id="name" name="name" value="<?= $taskName ?>" size="30"><br><br>
            <button type="submit">Zapisz</button>
        </form>
    </body>
</html>
