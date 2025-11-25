<?php
require "../../config.php";
header("Content-Type: application/json");

$stmt = $db->query("
    SELECT id, server_name, icon_url
    FROM discord_servers
    WHERE is_active = 1
    ORDER BY server_name ASC
");

echo json_encode([
    "status" => "OK",
    "servers" => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);
