<?php
require "../../../config.php";
header("Content-Type: application/json");

$ticket_id = $_POST["ticket_id"] ?? null;
$agent_id  = $_POST["agent_id"] ?? null;

if (!$AUTH_USER_ID) die(json_encode(["error"=>"NO_AUTH"]));
if (!$ticket_id || !$agent_id) die(json_encode(["error"=>"MISSING_FIELDS"]));

// Prüfen ob Ticket existiert
$check = $db->prepare("SELECT id FROM tickets WHERE id=?");
$check->execute([$ticket_id]);
if (!$check->fetch()) die(json_encode(["error"=>"TICKET_NOT_FOUND"]));

// Agent in Ticket setzen
$stmt = $db->prepare("
    INSERT INTO ticket_participants (ticket_id, user_id)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE user_id=user_id
");
$stmt->execute([$ticket_id, $agent_id]);

// Log
$log = $db->prepare("
    INSERT INTO ticket_logs (ticket_id, action, user_id, details)
    VALUES (?, 'agent_added', ?, ?)
");
$log->execute([$ticket_id, $AUTH_USER_ID, "Agent hinzugefügt: $agent_id"]);

echo json_encode(["status"=>"OK"]);
