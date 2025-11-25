<?php
require "../../config.php";
header("Content-Type: application/json");

$user_id = $_GET["user_id"] ?? null;
if (!$user_id) die(json_encode(["error"=>"NO_USER"]));

$stmt = $db->prepare("
SELECT * FROM warnings WHERE user_id=? ORDER BY id DESC
");
$stmt->execute([$user_id]);

echo json_encode([
    "status"=>"OK",
    "warnings"=>$stmt->fetchAll(PDO::FETCH_ASSOC)
]);
