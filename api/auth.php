<?php
require "../dashboard/config.php";

$ip = $_SERVER['REMOTE_ADDR'];

// ------------------------------------------
// 1) IP BAN CHECK
// ------------------------------------------
$stmt = $db->prepare("SELECT * FROM api_bans WHERE ip=?");
$stmt->execute([$ip]);
if ($stmt->fetch()) {
    die(json_encode(["error" => "IP_BANNED"]));
}

// ------------------------------------------
// 2) IP WHITELIST CHECK
// (optional, falls leer → erlauben wir alles)
// ------------------------------------------
$whitelist = $db->query("SELECT ip FROM api_whitelist")->fetchAll(PDO::FETCH_COLUMN);

if (count($whitelist) > 0 && !in_array($ip, $whitelist)) {
    die(json_encode(["error" => "IP_NOT_ALLOWED"]));
}

// ------------------------------------------
// 3) API KEY CHECK
// ------------------------------------------
if (!isset($_GET["key"])) {
    die(json_encode(["error" => "NO_KEY"]));
}

$key = $_GET["key"];

$stmt = $db->prepare("SELECT * FROM api_keys WHERE api_key=?");
$stmt->execute([$key]);
$api = $stmt->fetch();

if (!$api) {
    die(json_encode(["error" => "INVALID_KEY"]));
}

// API Scopes
$API_SCOPE = $api["permission"]; 
$API_OWNER = $api["owner"];

// ------------------------------------------
// 4) RATE LIMIT (pro API-Key)
// 60 Calls / Minute (einstellbar)
// ------------------------------------------
$minute = date("YmdHi"); // z.B. 202502151453

$stmt = $db->prepare("SELECT count FROM api_rate_limits WHERE api_key=? AND minute=?");
$stmt->execute([$key, $minute]);
$rate = $stmt->fetch();

if (!$rate) {
    $stmt = $db->prepare("INSERT INTO api_rate_limits (api_key, minute, count) VALUES (?,?,1)");
    $stmt->execute([$key,$minute]);
} else {
    if ($rate["count"] > 60) {
        die(json_encode(["error" => "RATE_LIMIT_EXCEEDED"]));
    }
    $stmt = $db->prepare("UPDATE api_rate_limits SET count = count + 1 WHERE api_key=? AND minute=?");
    $stmt->execute([$key,$minute]);
}

// ------------------------------------------
// 5) API LOGGING
// ------------------------------------------
$stmt = $db->prepare("
    INSERT INTO api_logs (ip, endpoint, method, api_key, status)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([
    $ip,
    $_SERVER["REQUEST_URI"],
    $_SERVER["REQUEST_METHOD"],
    $key,
    200
]);
