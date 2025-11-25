<?php
require "../../config.php";
header("Content-Type: application/json");

$stmt = $db->query("SELECT * FROM reports ORDER BY id DESC");

echo json_encode([
    "status"=>"OK",
    "reports"=>$stmt->fetchAll(PDO::FETCH_ASSOC)
]);
