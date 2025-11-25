<?php
require "../../config.php";
header("Content-Type: application/json");

$mod_id  = $_POST["mod_id"] ?? null;
$user_id = $_POST["user_id"] ?? null;
$note    = $_POST["note"] ?? "";

if (!$mod_id || !$user_id) die(json_encode(["error"=>"MISSING_FIELDS"]));

$stmt = $db->prepare("INSERT INTO notes (user_id, mod_id, note) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $mod_id, $note]);

$db->prepare("
INSERT INTO mod_logs (action, mod_id, target_id, details)
VALUES ('note', ?, ?, ?)
")->execute([$mod_id, $user_id, $note]);

echo json_encode(["status"=>"OK"]);
