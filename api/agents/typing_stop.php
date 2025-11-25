<?php
require "../../../config.php";
header("Content-Type: application/json");

global $AUTH_USER_ID;

$ticket_id = $_POST["ticket_id"] ?? null;
if (!$ticket_id) {
    die(json_encode(["error"=>"NO_TICKET_ID"]));
}

$stmt = $db->prepare("
    UPDATE agent_status
    SET typing = 0, last_update = NOW()
    WHERE agent_id = ? AND ticket_id = ?
");
$stmt->execute([$AUTH_USER_ID, $ticket_id]);

echo json_encode(["status"=>"OK"]);
