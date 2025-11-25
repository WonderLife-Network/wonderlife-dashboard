<?php
require "../../config.php";
header("Content-Type: application/json");

// Dashboard: braucht admin / read Rechte
if ($API_SCOPE != "admin" && $API_SCOPE != "read") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$stmt = $db->query("SELECT * FROM discord_servers ORDER BY name ASC");
$servers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "OK",
    "servers" => $servers
]);
