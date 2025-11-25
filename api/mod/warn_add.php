<?php
require "../../config.php";
header("Content-Type: application/json");

if ($API_SCOPE != "admin" && $API_SCOPE != "moderation" && $API_SCOPE != "write") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$user_id = $_POST["user_id"] ?? null;
$mod_id  = $_POST["mod_id"] ?? null;
$reason  = $_POST["reason"] ?? "";

if (!$user_id || !$mod_id) {
    die(json_encode(["error"=>"MISSING_FIELDS"]));
}

$stmt = $db->prepare("INSERT INTO warnings (user_id, mod_id, reason) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $mod_id, $reason]);

$db->prepare("
INSERT INTO mod_logs (action, mod_id, target_id, details)
VALUES ('warn', ?, ?, ?)
")->execute([$mod_id, $user_id, $reason]);

echo json_encode(["status" => "OK"]);
