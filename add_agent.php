<?php
session_start();
require('group/database.php');
require('admin_login.php');

// get roles
function fetchAllRoles($connect) {
    $sql = "SELECT id, role_name FROM roles";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// get users
function fetchAllUsers($connect) {
    $sql = "SELECT * FROM users";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add new agent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_agent'])) {
        // Retrieve agent details from form
        $agentName = filter_input(INPUT_POST, 'agent_name', FILTER_SANITIZE_STRING);
        $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING);
        $roleId = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);
        $ability1 = filter_input(INPUT_POST, 'ability1', FILTER_SANITIZE_STRING);
        $ability2 = filter_input(INPUT_POST, 'ability2', FILTER_SANITIZE_STRING);
        $ability3 = filter_input(INPUT_POST, 'ability3', FILTER_SANITIZE_STRING);
        $ability4 = filter_input(INPUT_POST, 'ability4', FILTER_SANITIZE_STRING);
        $releaseDate = $_POST['release_date'] ?: NULL;

        // Insert agent into db
        $sql = "INSERT INTO agents (agent_name, bio, role_id, ability1, ability2, ability3, ability4, release_date) 
                VALUES (:agent_name, :bio, :role_id, :ability1, :ability2, :ability3, :ability4, :release_date)";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':agent_name', $agentName);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':role_id', $roleId);
        $stmt->bindParam(':ability1', $ability1);
        $stmt->bindParam(':ability2', $ability2);
        $stmt->bindParam(':ability3', $ability3);
        $stmt->bindParam(':ability4', $ability4);
        $stmt->bindParam(':release_date', $releaseDate);
        $stmt->execute();
    }
    elseif (isset($_POST['add_user'])) {
        // get user info from form
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $userType = $_POST['user_type'];

        // Insert user into db
        $sql = "INSERT INTO users (username, password, email, user_type) VALUES (:username, :password, :email, :user_type)";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_type', $userType, PDO::PARAM_INT);
        $stmt->execute();
    }
    elseif (isset($_POST['delete_user'])) {
        // get user id from form
        $userId = $_POST['user_id'];

        // check if admin
        $sql = "SELECT user_type FROM users WHERE id = :user_id";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['user_type'] == '1') {
            echo "Cannot delete an admin user.";
        } else {
            $sql = "DELETE FROM users WHERE id = :user_id";
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    elseif (isset($_POST['edit_user'])) {
        $userId = $_POST['user_id'];
        $username = $_POST['edit_username'];
        $email = $_POST['edit_email'];
        $userType = $_POST['edit_user_type'];

        // Update user details in db
        $sql = "UPDATE users SET username = :username, email = :email, user_type = :user_type WHERE id = :user_id";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_type', $userType, PDO::PARAM_INT);
        $stmt->execute();
    }
}

// Check if the user is an admin
if (!isAdmin()) {
    exit('Access Denied. Only administrators can access this page. <a href="index.php">Go back</a>.');
}

// get roles and user
$roles = fetchAllRoles($connect);
$users = fetchAllUsers($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="admindash.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <?php include('group/header.php'); ?>

    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>

        <div class="add-agent-container">
            <h3>Add New Agent</h3>
            <form method="POST" class="form-agent">
                <input type="text" name="agent_name" placeholder="Agent Name" required>
                <textarea name="bio" placeholder="Bio" required></textarea>
                <select name="role_id" required>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="ability1" placeholder="Ability 1" required></textarea>
                <textarea name="ability2" placeholder="Ability 2" required></textarea>
                <textarea name="ability3" placeholder="Ability 3" required></textarea>
                <textarea name="ability4" placeholder="Ability 4" required></textarea>
                <input type="date" name="release_date" placeholder="Release Date">
                <button type="submit" name="add_agent">Add Agent</button>
            </form>
        </div>

        <div class="user-management-container">
        <h3>User Management</h3>
        <div class="scrollable-table">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Account Type</th>
                            <th colspan="2">Manage Users</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['user_type'] == '1' ? 'Admin' : 'User' ?></td>
                        <?php if ($user['user_type'] != '1'): ?>
                        <td>
                            <form method="post" class="form-user-edit">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="text" name="edit_username" value="<?= htmlspecialchars($user['username']) ?>" required>
                                <input type="email" name="edit_email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                <select name="edit_user_type" required>
                                    <option value="0" <?= $user['user_type'] == '0' ? 'selected' : '' ?>>User</option>
                                    <option value="1" <?= $user['user_type'] == '1' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit" name="edit_user">Edit</button>
                                <button type="submit" name="delete_user">Delete</button>
                            </form>
                        </td>
                        <?php else: ?>
                        <td colspan="2">Admin accounts cannot be edited or deleted.</td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>    

        <div class="add-user-container">
            <h3>Add New User</h3>
            <form method="post" class="form-add-user">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="user_type" required>
                    <option value="1">Admin</option>
                    <option value="0">User</option>
                </select>
                <button type="submit" name="add_user">Add User</button>
            </form>
        </div>
    </div>
</body>
</html>