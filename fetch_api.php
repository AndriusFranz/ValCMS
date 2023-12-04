<?php
$host = 'localhost';
$dbname = 'serverside';
$username = 'serveruser';
$password = 'gorgonzola7!';

try {
  
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $api_url = "https://valorant-api.com/v1/agents";
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);


    if ($data !== null && isset($data['data'])) {
        foreach ($data['data'] as $agent) {
            $agentName = $agent['displayName'];
            $existingAgent = $pdo->prepare("SELECT * FROM agents WHERE agent_name = ?");
            $existingAgent->execute([$agentName]);

            if ($existingAgent->rowCount() === 0) {
    
                $insertAgent = $pdo->prepare("INSERT INTO agents (agent_name, role) VALUES (?, ?)");
                $insertAgent->execute([$agentName, $agent['role']['displayName']]);
                echo "Inserted agent: $agentName<br>";
            } else {
                echo "Agent already exists: $agentName<br>";
            }
        }
    } else {
        echo "Failed to fetch data from the API.";
    }
} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
}
?>
