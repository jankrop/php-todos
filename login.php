<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect('localhost', 'root', '', 'php_2025_1');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT password FROM Users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            $conn->close();
            header('Location: index.php');
            die();
        } else {
            $error = "Niepoprawne hasło!";
        }
    } else {
        $error = "Niepoprawna nazwa użytkownika!";
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Zaloguj się</title>
    </head>
    <body>
        <?php if (isset($error)) { ?>
            <p style="color: red;"><?= $error ?></p>
        <?php } ?>
        <form method="post" action="">
            <label for="username">Nazwa użytkownika:</label><br>
            <input id="username" name="username"><br><br>
            <label for="password">Hasło:</label><br>
            <input id="password" name="password" type="password"><br><br>
            <button type="submit">Zaloguj</button>
        </form>
    </body>
</html>
