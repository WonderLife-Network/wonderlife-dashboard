<?php
require "../../config.php";
header("Content-Type: application/json");

if (!isset($_GET["id"])) {
    die(json_encode(["error"=>"NO_ID"]));
}

$id = intval($_GET["id"]);

$stmt = $db->prepare("SELECT * FROM reaction_panels WHERE id=?");
$stmt->execute([$id]);
$panel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$panel) {
    die(json_encode(["error"=>"NOT_FOUND"]));
}

$roles = $db->prepare("SELECT * FROM reaction_roles WHERE panel_id=? ORDER BY id ASC");
$roles->execute([$id]);

echo json_encode([
    "status" => "OK",
    "panel"  => $panel,
    "roles"  => $roles->fetchAll(PDO::FETCH_ASSOC)
]);
