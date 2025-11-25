<?php
require "../../../config.php";
header("Content-Type: application/json");

$ticket_id = $_POST["ticket_id"] ?? null;
$new_server = $_POST["server_id"] ?? null;

if (!$AUTH_USER_ID) die(json_encode(["error"=>"NO_AUTH"]));
if (!$ticket_id || !$new_server) die(json_encode(["error"=>"MISSING_FIELDS"]));

// Server-ID prüfen
$serverCheck = $db->prepare("SELECT id FROM discord_servers WHERE id=?");
$serverCheck->execute([$new_server]);
if (!$serverCheck->fetch()) die(json_encode(["error"=>"SERVER_NOT_FOUND"]));

$stmt = $db->prepare("
    UPDATE tickets 
    SET server_id = ?
    WHERE id = ?
");
$stmt->execute([$new_server, $ticket_id]);

$log = $db->prepare("
    INSERT INTO ticket_logs (ticket_id, action, user_id, details)
    VALUES (?, 'transfer', ?, ?)
");
$log->execute([$ticket_id, $AUTH_USER_ID, "Ticket verschoben zu Server: $new_server"]);

echo json_encode(["status"=>"OK"]);
