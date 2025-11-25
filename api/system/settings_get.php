<?php
require "../../config.php";

header("Content-Type: application/json");

// API Key / Auth Check
if ($API_SCOPE != "admin" && $API_SCOPE != "write") {
    die(json_encode(["error" => "FORBIDDEN_SCOPE"]));
}

$stmt = $db->query("SELECT name, value FROM settings");
$out = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $out[$row["name"]] = $row["value"];
}

echo json_encode([
    "status" => "OK",
    "settings" => $out
]);
