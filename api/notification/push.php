<?php
require "../_core.php";

$user_id = $_POST["user_id"];
$type    = $_POST["type"];
$title   = $_POST["title"];
$message = $_POST["message"];

$stmt = $db->prepare("
    INSERT INTO notifications (user_id, type, title, message)
    VALUES (?,?,?,?)
");
$stmt->execute([$user_id, $type, $title, $message]);

echo json_encode(["success" => true]);
