<?php
session_start();
require('group/database.php');
require('admin_login.php');


$agentName = '';
$message = '';

//fetch agent details
function AgentDetails($pdo, $agentName) {
    $sql = "SELECT * FROM agents WHERE agent_name = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$agentName]);
    $agentDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    return $agentDetails;
}

if (isset($_GET['agent_name'])) {
    $agentName = $_GET['agent_name'];

    try {
        $pdo = $connect; 
        $agentDetails = AgentDetails($pdo, $agentName);

        if (!$agentDetails) {
            $message = "Agent not found.";
        }
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
// update/delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $bio = $_POST['bio'] ?? '';
        $roleId = $_POST['role_id'] ?? '';
        $ability1 = $_POST['ability1'] ?? '';
        $ability2 = $_POST['ability2'] ?? '';
        $ability3 = $_POST['ability3'] ?? '';
        $ability4 = $_POST['ability4'] ?? '';

        try {
            $updateSql = "UPDATE agents SET bio = ?, role_id = ?, ability1 = ?, ability2 = ?, ability3 = ?, ability4 = ? WHERE agent_name = ?";
            $stmt = $pdo->prepare($updateSql);
            $stmt->execute([$bio, $roleId, $ability1, $ability2, $ability3, $ability4, $agentName]);

            $message = "Agent updated successfully.";
        } catch (PDOException $e) {
            $message = "Error updating agent: " . $e->getMessage();
        }
    } elseif (isset($_POST['delete'])) {
        try {
            $deleteSql = "DELETE FROM agents WHERE agent_name = ?";
            $stmt = $pdo->prepare($deleteSql);
            $stmt->execute([$agentName]);

            $message = "Agent deleted successfully.";
        } catch (PDOException $e) {
            $message = "Error deleting agent: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Edit Agent</title>
</head>
<body>
<?php include('group/header.php') ?>

    <?php if ($agentName && $agentDetails): ?>
        <h1>Edit Agent: <?= htmlspecialchars($agentName) ?></h1>
        <form method="post">
            <label for="bio">Bio:</label>
            <textarea name="bio" id="bio"><?= htmlspecialchars($agentDetails['bio'] ?? '') ?></textarea><br>

            <label for="role_id">Role ID:</label>
            <input type="text" name="role_id" id="role_id" value="<?= htmlspecialchars($agentDetails['role_id'] ?? '') ?>"><br>

            <label for="ability1">Ability 1:</label>
            <input type="text" name="ability1" id="ability1" value="<?= htmlspecialchars($agentDetails['ability1'] ?? '') ?>"><br>

            <label for="ability2">Ability 2:</label>
            <input type="text" name="ability2" id="ability2" value="<?= htmlspecialchars($agentDetails['ability2'] ?? '') ?>"><br>

            <label for="ability3">Ability 3:</label>
            <input type="text" name="ability3" id="ability3" value="<?= htmlspecialchars($agentDetails['ability3'] ?? '') ?>"><br>

            <label for="ability4">Ability 4:</label>
            <input type="text" name="ability4" id="ability4" value="<?= htmlspecialchars($agentDetails['ability4'] ?? '') ?>"><br>

            <input type="submit" name="update" value="Update Agent">
        </form>

        <form method="post" onsubmit="return confirm('Are you sure you want to delete this agent?');">
            <input type="submit" name="delete" value="Delete Agent">
        </form>
    <?php else: ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <?php if ($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>
</body>
</html>
