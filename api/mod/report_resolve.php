<?php
require "../../config.php";
header("Content-Type: application/json");

$id = $_POST["id"] ?? null;
$mod = $_POST["mod_id"] ?? null;

if (!$id || !$mod) die(json_encode(["error"=>"MISSING_FIELDS"]));

$db->prepare("UPDATE reports SET status='closed' WHERE id=?")->execute([$id]);

$db->prepare("
INSERT INTO mod_logs (action, mod_id, target_id, details)
VALUES ('report_resolved', ?, ?, 'Report closed')
")->execute([$mod, $id]);

echo json_encode(["status"=>"OK"]);
