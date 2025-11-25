<?php
require "../../config.php";
header("Content-Type: application/json");

$ticket_id = $_POST["ticket_id"] ?? null;
$agent_id  = $_POST["agent_id"] ?? null;

if (!$ticket_id || !$agent_id) {
    die(json_encode(["error"=>"MISSING_FIELDS"]));
}

/* Agent übernehmen */
$db->prepare("
INSERT INTO agents (ticket_id, user_id) 
VALUES (?, ?)
ON DUPLICATE KEY UPDATE user_id=VALUES(user_id);
")->execute([$ticket_id, $agent_id]);

/* Status Update */
$db->prepare("
UPDATE agent_status SET status='online', current_ticket=? WHERE user_id=?
")->execute([$ticket_id, $agent_id]);

echo json_encode(["status"=>"OK"]);
