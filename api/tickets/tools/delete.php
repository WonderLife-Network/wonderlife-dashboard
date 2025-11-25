<?php
require "../../../config.php";
header("Content-Type: application/json");

if ($AUTH_ROLE !== "admin") die(json_encode(["error"=>"NO_PERMISSION"]));

$ticket_id = $_POST["ticket_id"] ?? null;
if (!$ticket_id) die(json_encode(["error"=>"NO_TICKET"]));

// Delete ticket + messages + files + logs
$db->prepare("DELETE FROM ticket_messages WHERE ticket_id=?")->execute([$ticket_id]);
$db->prepare("DELETE FROM ticket_files WHERE ticket_id=?")->execute([$ticket_id]);
$db->prepare("DELETE FROM ticket_logs WHERE ticket_id=?")->execute([$ticket_id]);
$db->prepare("DELETE FROM ticket_participants WHERE ticket_id=?")->execute([$ticket_id]);
$db->prepare("DELETE FROM tickets WHERE id=?")->execute([$ticket_id]);

echo json_encode(["status"=>"OK"]);
