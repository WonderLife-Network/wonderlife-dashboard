<?php
require "../../config.php";
header("Content-Type: application/json");

$ticket_id = $_POST["ticket_id"] ?? null;
$agent_id  = $_POST["agent_id"] ?? null;

if (!$ticket_id || !$agent_id) {
    die(json_encode(["error"=>"MISSING_FIELDS"]));
}

$db->prepare("DELETE FROM agents WHERE ticket_id=? AND user_id=?")
   ->execute([$ticket_id, $agent_id]);

$db->prepare("
UPDATE agent_status SET status='online', current_ticket=NULL 
WHERE user_id=?
")->execute([$agent_id]);

echo json_encode(["status"=>"OK"]);
