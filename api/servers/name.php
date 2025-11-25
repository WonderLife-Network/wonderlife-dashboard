<?php
require "../../config.php";
header("Content-Type: application/json");

$id = $_GET["id"] ?? null;
if (!$id) die(json_encode(["error"=>"NO_ID"]));

$stmt = $db->prepare("SELECT server_name FROM discord_servers WHERE id=?");
$stmt->execute([$id]);
$name = $stmt->fetchColumn();

echo json_encode([
    "status" => "OK",
    "name" => $name ?: "Unbekannt"
]);
