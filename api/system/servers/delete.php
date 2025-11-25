<?php
require "../../config.php";
header("Content-Type: application/json");

if ($API_SCOPE != "admin") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$id = $_POST["id"] ?? null;

if (!$id)
    die(json_encode(["error"=>"NO_ID"]));

$stmt = $db->prepare("DELETE FROM discord_servers WHERE id=?");
$stmt->execute([$id]);

echo json_encode(["status"=>"OK"]);
