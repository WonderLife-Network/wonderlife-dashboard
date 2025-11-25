<?php
require "../../config.php";
header("Content-Type: application/json");

$stmt = $db->query("SELECT * FROM mod_logs ORDER BY id DESC");

echo json_encode([
    "status"=>"OK",
    "logs"=>$stmt->fetchAll(PDO::FETCH_ASSOC)
]);
