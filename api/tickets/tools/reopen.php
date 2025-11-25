<?php
require "../../../config.php";
header("Content-Type: application/json");

$ticket_id = $_POST["ticket_id"] ?? null;

if (!$AUTH_USER_ID) die(json_encode(["error"=>"NO_AUTH"]));
if (!$ticket_id) die(json_encode(["error"=>"NO_TICKET"]));

$stmt = $db->prepare("
    UPDATE tickets 
    SET status='open', closed_reason=NULL, closed_by=NULL, closed_at=NULL
    WHERE id=?
");
$stmt->execute([$ticket_id]);

$log = $db->prepare("
    INSERT INTO ticket_logs (ticket_id, action, user_id, details)
    VALUES (?, 'reopened', ?, 'Ticket wieder geöffnet')
");
$log->execute([$ticket_id, $AUTH_USER_ID]);

echo json_encode(["status"=>"OK"]);
