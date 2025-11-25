<?php
require "../../config.php";
header("Content-Type: application/json");

$id = $_POST["id"] ?? null;
if (!$id) die(json_encode(["error"=>"NO_ID"]));

$db->prepare("DELETE FROM notes WHERE id=?")->execute([$id]);

echo json_encode(["status"=>"OK"]);
