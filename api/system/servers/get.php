<?php
require "../../config.php";
header("Content-Type: application/json");

$id = $_GET["id"] ?? null;

if (!$id)
    die(json_encode(["error"=>"NO_ID"]));

$stmt = $db->prepare("SELECT * FROM discord_servers WHERE id=?");
$stmt->execute([$id]);

$server = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$server)
    die(json_encode(["error"=>"NOT_FOUND"]));

echo json_encode([
    "status"=>"OK",
    "server"=>$server
]);
