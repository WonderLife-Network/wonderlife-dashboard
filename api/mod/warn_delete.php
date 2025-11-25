<?php
require "../../config.php";
header("Content-Type: application/json");

$id = $_POST["id"] ?? null;
if (!$id) die(json_encode(["error"=>"NO_ID"]));

$stmt = $db->prepare("DELETE FROM warnings WHERE id=?");
$stmt->execute([$id]);

echo json_encode(["status"=>"OK"]);
