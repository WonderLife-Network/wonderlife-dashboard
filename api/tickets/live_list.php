<?php
require "../../config.php";
header("Content-Type: application/json");

if ($API_SCOPE != "admin" && $API_SCOPE != "tickets" && $API_SCOPE != "read") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$sql = "
SELECT 
    t.id,
    t.user_id,
    t.category,
    t.status,
    t.created_at,
    (SELECT message FROM ticket_messages WHERE ticket_id=t.id ORDER BY id DESC LIMIT 1) AS last_msg,
    (SELECT sender_id FROM ticket_messages WHERE ticket_id=t.id ORDER BY id DESC LIMIT 1) AS last_sender,
    (SELECT user_id FROM agents WHERE ticket_id=t.id LIMIT 1) AS claimed_by,
    (SELECT status FROM agent_status WHERE current_ticket=t.id LIMIT 1) AS agent_status
FROM tickets t
WHERE t.status='open'
ORDER BY t.id DESC
";

$stmt = $db->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "OK",
    "tickets" => $rows
]);
