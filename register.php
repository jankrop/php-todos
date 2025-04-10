<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    die();
}

$conn = mysqli_connect('localhost', 'root', '', 'php_2025_1');

function createAccount() {
    global $conn, $error;

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    if ($password1 !== $password2) {
        $error = 'Hasła się nie zgadzają';
        return;
    } else {
        $password = $password1;
    }

    $emailExists = $conn->prepare("SELECT email FROM Users WHERE email = ?");
    $emailExists->bind_param('s', $email);
    $emailExists->execute();
    $emailExists->store_result();

    $usernameExists = $conn->prepare("SELECT email FROM Users WHERE username = ?");
    $usernameExists->bind_param('s', $username);
    $usernameExists->execute();
    $usernameExists->store_result();

    if ($emailExists->num_rows > 0) {
        $error = 'Użytkownik o tym e-mailu już istnieje.';
    } else if ($usernameExists->num_rows > 0) {
        $error = 'Użytkownik o tej nazwie już istnieje.';
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $createAccount = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
        $createAccount->bind_param('sss', $username, $email, $passwordHash);
        if ($createAccount->execute()) {
            session_start();
            $_SESSION['username'] = $username;
            $conn->close();
            header('Location: index.php');
            die();
        } else {
            $error = 'Błąd: ' . $createAccount->error;
        }
    }

    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    createAccount();
}
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logowanie</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <header>
            <p>Aplikacja TODO</p>
            <a href="login.php">Logowanie</a>
            <a href="register.php">Rejestracja</a>
        </header>
        <p>Rejestracja</p>
        <?php if (isset($error)) { ?>
            <p class="error"><?= $error; ?></p>
        <?php } ?>
        <form action="" method="post">
            <label for="username">Nazwa użytkownika</label><br>
            <input type="text" name="username" id="username"
                   value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>"><br>
            <br>

            <label for="email">Adres e-mail</label><br>
            <input type="email" name="email" id="email"
                   value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>"><br>
            <br>

            <label for="password1">Hasło</label><br>
            <input type="password" name="password1" id="password1"
                   value="<?= isset($_POST['password1']) ? $_POST['password1'] : '' ?>"><br>
            <br>

            <label for="password2">Powtórz hasło</label><br>
            <input type="password" name="password2" id="password2"
                   value="<?= isset($_POST['password2']) ? $_POST['password2'] : '' ?>"><br>
            <br>

            <button type="submit">Utwórz konto</button>
        </form>
</html>
