<?php
require "../../config.php";

header("Content-Type: application/json");

if ($API_SCOPE != "admin") {
    die(json_encode(["error" => "FORBIDDEN_SCOPE"]));
}

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || empty($input["label"]) || empty($input["scopes"])) {
    die(json_encode(["error" => "MISSING_FIELDS"]));
}

$api_key = "WL-" . bin2hex(random_bytes(16));

$stmt = $db->prepare("
    INSERT INTO api_keys (api_key, label, scopes, expires_at)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([
    $api_key,
    $input["label"],
    implode(",", $input["scopes"]),
    $input["expires_at"] ?? null
]);

echo json_encode([
    "status" => "OK",
    "api_key" => $api_key
]);

