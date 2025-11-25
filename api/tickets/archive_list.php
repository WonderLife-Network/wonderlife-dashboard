<?php
require "../../config.php";
header("Content-Type: application/json");

if ($API_SCOPE != "admin" && $API_SCOPE != "tickets" && $API_SCOPE != "read") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$stmt = $db->query("
SELECT id, user_id, category, status, created_at 
FROM tickets 
WHERE archived=1 
ORDER BY id DESC
");

echo json_encode([
    "status"=>"OK",
    "tickets"=>$stmt->fetchAll(PDO::FETCH_ASSOC)
]);
