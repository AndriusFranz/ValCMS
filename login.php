<?php
session_start();

require('group/database.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $stmt = $connect->prepare('SELECT id, username, password, user_type FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;
        $_SESSION['user_type'] = $user['user_type'];

        header('Location: index.php');
        exit;
    } else {
        echo "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Log In</title>
</head>
<body id="login-background">
<?php include('group/header.php'); ?>
    <div class="login-form">
        <h2>Login</h2>
        <form method="POST">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required class="form-input">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required class="form-input">
            </div>
            <div>
                <button type="submit" class="form-button">Login</button>
            </div>
        </form>
        <div class="register-link">
            <a href="register.php" class="button">Register</a>
        </div>
    </div>
</body>
</html>
