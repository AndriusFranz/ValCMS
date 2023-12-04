<?php
session_start();
require('group/database.php');
include('admin_login.php');



$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'agent_name';
$sortOrder = isset($_GET['order']) && in_array($_GET['order'], ['asc', 'desc']) ? $_GET['order'] : 'asc';
$selectedRole = isset($_GET['role']) ? $_GET['role'] : '';

function fetchAgentsWithRoles($connect, $sortField, $sortOrder, $selectedRole) {
    $validSortFields = ['agent_name', 'role_name', 'release_date'];
    $sortField = in_array($sortField, $validSortFields) ? $sortField : 'agent_name';

    $sql = "SELECT agents.agent_name, roles.role_name, agents.release_date 
            FROM agents 
            JOIN roles ON agents.role_id = roles.id";

    // role filter
    if (!empty($selectedRole)) {
        $sql .= " WHERE roles.role_name = :selectedRole";
    }

    $sql .= " ORDER BY {$sortField} {$sortOrder}";

    $stmt = $connect->prepare($sql);

    if (!empty($selectedRole)) {
        $stmt->bindParam(':selectedRole', $selectedRole, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchAllRoles($connect) {
    $sql = "SELECT DISTINCT role_name FROM roles";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$agents = fetchAgentsWithRoles($connect, $sortField, $sortOrder, $selectedRole);
$roles = fetchAllRoles($connect);

// Fetch agents icon from api
$valorantAgentsData = json_decode(file_get_contents('https://valorant-api.com/v1/agents'), true);
$agentIcons = [];
foreach ($valorantAgentsData['data'] as $valorantAgent) {
    if (isset($valorantAgent['displayIcon']) && $valorantAgent['isPlayableCharacter']) {
        $agentIcons[$valorantAgent['displayName']] = $valorantAgent['displayIcon'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Agents List</title>
</head>
<body>
<?php include('group/header.php') ?>

    <h1>Agents List</h1>
    <form action="" method="get">

        <label for="sort">Sort by:</label>
        <select name="sort" id="sort">
            <option value="agent_name" <?= $sortField == 'agent_name' ? 'selected' : '' ?>>Agent Name</option>
            <option value="role_name" <?= $sortField == 'role_name' ? 'selected' : '' ?>>Role</option>
            <option value="release_date" <?= $sortField == 'release_date' ? 'selected' : '' ?>>Release Date</option>
        </select>

        <label for="order">Order:</label>
        <select name="order" id="order">
            <option value="asc" <?= $sortOrder == 'asc' ? 'selected' : '' ?>>Ascending</option>
            <option value="desc" <?= $sortOrder == 'desc' ? 'selected' : '' ?>>Descending</option>
        </select>

        <label for="role">Filter by Role:</label>
        <select name="role" id="role">
            <option value="">All Roles</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['role_name'] ?>" <?= $selectedRole == $role['role_name'] ? 'selected' : '' ?>><?= $role['role_name'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>
<br>
    <div class="agents-list">
        <?php foreach ($agents as $agent): ?>
            <a class="agent-item" href="agents_details.php?agent_name=<?= urlencode($agent['agent_name']) ?>">
                <?php if (isset($agentIcons[$agent['agent_name']])): ?>
                    <img src="<?= htmlspecialchars($agentIcons[$agent['agent_name']]) ?>" alt="<?= htmlspecialchars($agent['agent_name']) ?>">
                <?php endif; ?>
                <div class="agent-name-role">
                    <div><?= htmlspecialchars($agent['agent_name']) ?></div>
                    <div><?= htmlspecialchars($agent['role_name']) ?></div>
                    <div><?= htmlspecialchars($agent['release_date']) ?></div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</body>
</html>
