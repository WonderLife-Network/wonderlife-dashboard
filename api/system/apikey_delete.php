<?php
require "../../config.php";

header("Content-Type: application/json");

if ($API_SCOPE != "admin") {
    die(json_encode(["error" => "FORBIDDEN_SCOPE"]));
}

if (!isset($_GET["id"])) {
    die(json_encode(["error" => "NO_ID"]));
}

$stmt = $db->prepare("DELETE FROM api_keys WHERE id=?");
$stmt->execute([$_GET["id"]]);

echo json_encode(["status" => "OK"]);
