<?php
require "../../config.php";
header("Content-Type: application/json");

if ($API_SCOPE != "admin" && $API_SCOPE != "tickets") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$uid = $_POST["user_id"] ?? null;
$status = $_POST["status"] ?? null;
$ticket_id = $_POST["ticket_id"] ?? null;

if (!$uid || !$status) {
    die(json_encode(["error"=>"MISSING_FIELDS"]));
}

$stmt = $db->prepare("
INSERT INTO agent_status (user_id, status, current_ticket, last_active)
VALUES (?, ?, ?, NOW())
ON DUPLICATE KEY UPDATE 
    status=VALUES(status),
    current_ticket=VALUES(current_ticket),
    last_active=NOW()
");
$stmt->execute([$uid, $status, $ticket_id]);

echo json_encode(["status"=>"OK"]);
