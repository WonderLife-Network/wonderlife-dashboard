<?php
require "../../config.php";

header("Content-Type: application/json");

if ($API_SCOPE != "admin") {
    die(json_encode(["error" => "FORBIDDEN_SCOPE"]));
}

$type  = $_GET["type"]  ?? null;
$limit = $_GET["limit"] ?? 200;

$sql = "SELECT * FROM system_logs";
$params = [];

if ($type) {
    $sql .= " WHERE type=?";
    $params[] = $type;
}

$sql .= " ORDER BY id DESC LIMIT ?";
$params[] = (int)$limit;

$stmt = $db->prepare($sql);
$stmt->execute($params);

echo json_encode([
    "status" => "OK",
    "logs" => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);
