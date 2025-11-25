<?php
require "../../../config.php";
header("Content-Type: application/json");

// Alle Agenten (alle User mit Team-Rolle)
$agentStmt = $db->query("
SELECT id, username
FROM users
WHERE role IN ('admin','team','support','mod')
");
$agents = $agentStmt->fetchAll(PDO::FETCH_ASSOC);

// Presence
$presenceStmt = $db->query("
SELECT ap.agent_id, ap.status, ap.last_update
FROM agent_presence ap
");
$presence = $presenceStmt->fetchAll(PDO::FETCH_ASSOC);

// Activity
$activityStmt = $db->query("
SELECT agent_id, ticket_id, last_active
FROM agent_activity
");
$activity = $activityStmt->fetchAll(PDO::FETCH_ASSOC);

// Typing
$typingStmt = $db->query("
SELECT agent_id, ticket_id
FROM agent_status
WHERE typing=1
");
$typing = $typingStmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status"=>"OK",
    "agents"=>$agents,
    "presence"=>$presence,
    "activity"=>$activity,
    "typing"=>$typing
]);
