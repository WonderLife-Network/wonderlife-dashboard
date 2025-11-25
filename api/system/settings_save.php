<?php
require "../../config.php";

header("Content-Type: application/json");

// Nur Admin oder Write
if ($API_SCOPE != "admin" && $API_SCOPE != "write") {
    die(json_encode(["error" => "FORBIDDEN_SCOPE"]));
}

// JSON Body empfangen
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !is_array($input)) {
    die(json_encode(["error" => "INVALID_PAYLOAD"]));
}

foreach ($input as $name => $value) {

    $stmt = $db->prepare("UPDATE settings SET value=? WHERE name=?");
    $stmt->execute([$value, $name]);
}

echo json_encode(["status" => "OK", "updated" => count($input)]);
