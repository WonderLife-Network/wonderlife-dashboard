<?php
require "../../config.php";
header("Content-Type: application/json");

// Nur erlaubt für Bots / Admin API
if ($API_SCOPE != "admin" && $API_SCOPE != "bot" && $API_SCOPE != "write") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$id   = $_POST["id"]   ?? null;     // Guild ID
$name = $_POST["name"] ?? null;     // Servername
$icon = $_POST["icon"] ?? null;     // Icon URL oder NULL

if (!$id || !$name) {
    die(json_encode(["error"=>"MISSING_FIELDS"]));
}

// Prüfen ob Server bereits existiert
$check = $db->prepare("SELECT id FROM discord_servers WHERE id = ?");
$check->execute([$id]);

if ($check->rowCount() > 0) {
    // Update Name / Icon
    $upd = $db->prepare("UPDATE discord_servers SET name=?, icon=? WHERE id=?");
    $upd->execute([$name, $icon, $id]);
    echo json_encode(["status"=>"UPDATED"]);
    exit;
}

// Neuen Server anlegen
$stmt = $db->prepare("INSERT INTO discord_servers (id, name, icon) VALUES (?,?,?)");
$stmt->execute([$id, $name, $icon]);

echo json_encode(["status"=>"CREATED"]);
