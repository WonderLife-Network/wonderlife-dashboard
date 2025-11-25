<?php
require "../../../config.php";
header("Content-Type: application/json");

$ticket_id = $_POST["ticket_id"] ?? null;
$reason    = $_POST["reason"] ?? "Kein Grund angegeben";

if (!$AUTH_USER_ID) die(json_encode(["error"=>"NO_AUTH"]));
if (!$ticket_id) die(json_encode(["error"=>"NO_TICKET"]));

$stmt = $db->prepare("
    UPDATE tickets 
    SET status='closed', closed_reason=?, closed_by=?, closed_at=NOW()
    WHERE id=?
");
$stmt->execute([$reason, $AUTH_USER_ID, $ticket_id]);

$log = $db->prepare("
    INSERT INTO ticket_logs (ticket_id, action, user_id, details)
    VALUES (?, 'closed', ?, ?)
");
$log->execute([$ticket_id, $AUTH_USER_ID, $reason]);

echo json_encode(["status"=>"OK"]);
