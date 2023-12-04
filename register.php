<?php
session_start();
require('group/database.php');

$errors = []; // store array of erros

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    // Sanitize input
    $registerUsername = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $registerPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Check if the username is already taken
    $stmt = $connect->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
    $stmt->bindParam(':username', $registerUsername, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $errors[] = "Username is already taken.";
    } elseif ($registerPassword === $confirmPassword) {
        $hashedPassword = password_hash($registerPassword, PASSWORD_DEFAULT);

        $stmt = $connect->prepare('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)');
        $stmt->bindParam(':username', $registerUsername, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        try {
            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            }
        } catch (PDOException $e) {
            $errors[] = "Error: Registration failed.";
        }
    } else {
        $errors[] = "Error: Password and confirm password do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Register</title>
</head>
<body>
    <?php include('group/header.php'); ?>
    <div class="register-form">
        <h2>Register</h2>
        <?php
        // Display errors
        if (!empty($errors)) {
            echo '<div class="error-messages">';
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
            echo '</div>';
        }
        ?>
        
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
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required class="form-input">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required class="form-input">
            </div>
            <div>
                <button type="submit" class="form-button">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
