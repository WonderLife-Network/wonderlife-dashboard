<?php
require "../../../config.php";
header("Content-Type: application/json");

global $AUTH_USER_ID;

$ticket_id = $_POST["ticket_id"] ?? null;
if (!$ticket_id) {
    die(json_encode(["error"=>"NO_TICKET_ID"]));
}

$stmt = $db->prepare("
    REPLACE INTO agent_activity (agent_id, ticket_id, last_active)
    VALUES (?, ?, NOW())
");
$stmt->execute([$AUTH_USER_ID, $ticket_id]);

echo json_encode(["status"=>"OK"]);
