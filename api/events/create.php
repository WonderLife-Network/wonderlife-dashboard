<?php
require "../_core.php";

$event = $_POST["event_type"] ?? null;
$data  = $_POST["event_data"] ?? null;
$user  = $_POST["created_by"] ?? null;

$stmt = $db->prepare("
    INSERT INTO system_logs (event_type, event_data, created_by)
    VALUES (?,?,?)
");
$stmt->execute([$event, json_encode($data), $user]);

echo json_encode(["success" => true]);
