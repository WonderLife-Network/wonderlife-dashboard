<?php
require "../../../config.php";
header("Content-Type: application/json");

$ticket_id = $_POST["ticket_id"] ?? null;
$agent_id  = $_POST["agent_id"] ?? null;

if (!$AUTH_USER_ID) die(json_encode(["error"=>"NO_AUTH"]));
if (!$ticket_id || !$agent_id) die(json_encode(["error"=>"MISSING_FIELDS"]));

$stmt = $db->prepare("
    DELETE FROM ticket_participants
    WHERE ticket_id=? AND user_id=?
");
$stmt->execute([$ticket_id, $agent_id]);

$log = $db->prepare("
    INSERT INTO ticket_logs (ticket_id, action, user_id, details)
    VALUES (?, 'agent_removed', ?, ?)
");
$log->execute([$ticket_id, $AUTH_USER_ID, "Agent entfernt: $agent_id"]);

echo json_encode(["status"=>"OK"]);
