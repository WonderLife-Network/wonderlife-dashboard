<?php
require "../../config.php";
header("Content-Type: application/json");

if ($API_SCOPE != "admin" && $API_SCOPE != "tickets") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$stmt = $db->query("SELECT * FROM agent_status ORDER BY last_active DESC");

echo json_encode([
    "status" => "OK",
    "agents" => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);
