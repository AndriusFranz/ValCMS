<?php 
session_start();
require('group/database.php');
include('admin_login.php');


$agentName = filter_input(INPUT_GET, 'agent_name', FILTER_SANITIZE_STRING);

// Fetch agent details and comments if agentName is set
if ($agentName) {
    $agentDetails = fetchAgentDetails($connect, $agentName);
    $comments = fetchComments($connect, $agentName);
}

function fetchAgentDetails($pdo, $agentName) {
    $sql = "SELECT agents.*, roles.role_name 
            FROM agents 
            JOIN roles ON agents.role_id = roles.id 
            WHERE agents.agent_name = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$agentName]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function fetchComments($pdo, $agentName) {
    $sql = "SELECT * FROM comments WHERE agent_name = ? ORDER BY comment_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$agentName]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function AddComment($pdo, $agentName, $commenterName, $comment) {
    $sql = "INSERT INTO comments (agent_name, commenter_name, comment, comment_date) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$agentName, $commenterName, $comment]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    // Sanitize POST
    $agentName = filter_input(INPUT_POST, 'agent_name', FILTER_SANITIZE_STRING);
    $commenterName = isset($_SESSION['user']) ? filter_var($_SESSION['user'], FILTER_SANITIZE_STRING) : filter_input(INPUT_POST, 'commenter_name', FILTER_SANITIZE_STRING);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

    // Check if the required fields are not empty
    if (!empty($agentName) && !empty($commenterName) && !empty($comment)) {
        AddComment($connect, $agentName, $commenterName, $comment);
        // redirect
        header("Location: agents_details.php?agent_name=" . urlencode($agentName));
        exit;
    } else {
        echo "Error: All fields are required.";
    }
}

function deleteComment($pdo, $commentId) {
    $sql = "DELETE FROM comments WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$commentId]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment']) && isAdmin()) {
    $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
    if ($commentId) {
        deleteComment($connect, $commentId);
        header("Location: agents_details.php?agent_name=" . urlencode($agentName));
        exit;
    }
}

$agentName = '';

if (isset($_GET['agent_name'])) {
    $agentName = $_GET['agent_name']; 
    $agentDetails = fetchAgentDetails($connect, $agentName);
    $comments = fetchComments($connect, $agentName);
}

// Fetch agent icon from API
$valorantAgentsData = json_decode(file_get_contents('https://valorant-api.com/v1/agents'), true);
$agentIcon = '';
foreach ($valorantAgentsData['data'] as $valorantAgent) {
    if ($valorantAgent['displayName'] == $agentName && isset($valorantAgent['displayIcon'])) {
        $agentIcon = $valorantAgent['displayIcon'];
        break;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="agentdetails.css">
    <title>Agent Details</title>
</head>
<body>
<?php include('group/header.php'); ?>
<div class= "details-container">
    <div class="agent-details">
        <?php if (!empty($agentDetails)): ?>
            <?php if (isAdmin()): ?>
                <a href="agent_edit.php?agent_name=<?= urlencode($agentName) ?>">Edit</a>
            <?php endif; ?>

            <h1><?= htmlspecialchars($agentDetails['agent_name']) ?></h1>
            <?php if ($agentIcon): ?>
                <img src="<?= htmlspecialchars($agentIcon) ?>" alt="<?= htmlspecialchars($agentDetails['agent_name']) ?>">
            <?php endif; ?>
            <p>Bio: <?= htmlspecialchars($agentDetails['bio']) ?></p>
            <p>Role: <?= htmlspecialchars($agentDetails['role_name']) ?></p>
            <p>Abilities:</p>

                <a><?= htmlspecialchars($agentDetails['ability1']) ?></a>
                <br>
                <a><?= htmlspecialchars($agentDetails['ability2']) ?></a>
                <br>
                <a><?= htmlspecialchars($agentDetails['ability3']) ?></a>
                <br>
                <a><?= htmlspecialchars($agentDetails['ability4']) ?></a>
        <?php else: ?>
            <p>Agent not found.</p>
        <?php endif; ?>

        <div class="comment-form">
            <h3>Leave a Comment</h3>
            <form method="post" action="">
                <input type="hidden" name="agent_name" value="<?= htmlspecialchars($agentName) ?>">
                <?php if (!isset($_SESSION['user'])): ?>
                    <div>
                        <label for="commenter_name">Your Name:</label>
                        <input type="text" id="commenter_name" name="commenter_name" required>
                    </div>
                <?php endif; ?>
                <div>
                    <label for="comment">Comment:</label>
                    <textarea id="comment" name="comment" required></textarea>
                </div>
                <div>
                    <button type="submit" name="submit_comment">Post Comment</button>
                </div>
            </form>
        </div>

        <div class="comments-section">
            <?php if (!empty($comments)): ?>
                <h3>Comments</h3>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <p><strong><?= htmlspecialchars($comment['commenter_name']) ?>:</strong></p>
                        <p><?= htmlspecialchars($comment['comment']) ?></p>
                        <p><em>Posted on: <?= htmlspecialchars($comment['comment_date']) ?></em></p>
                        <?php if (isAdmin()): ?>
                        <div>
                            <form class="delete" method="post" action="">
                                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id']) ?>">
                                <button type="submit" name="delete_comment">Delete Comment</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
