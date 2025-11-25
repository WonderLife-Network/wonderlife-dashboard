<?php
require "../../../config.php";
header("Content-Type: application/json");

global $AUTH_USER_ID;

$ticket_id = $_POST["ticket_id"] ?? null;
if (!$ticket_id) {
    die(json_encode(["error"=>"NO_TICKET_ID"]));
}

// Tippen = 1
$stmt = $db->prepare("
    REPLACE INTO agent_status (agent_id, ticket_id, typing, last_update)
    VALUES (?, ?, 1, NOW())
");
$stmt->execute([$AUTH_USER_ID, $ticket_id]);

echo json_encode(["status"=>"OK"]);
