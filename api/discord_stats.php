<?php
require "auth.php";
require "../dashboard/config.php";

if ($API_SCOPE != "admin" && $API_SCOPE != "write") {
    die(json_encode(["error" => "FORBIDDEN_SCOPE"]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $guild_id = $_POST["guild_id"] ?? null;
    $json = json_encode($_POST);

    if ($guild_id) {
        $stmt = $db->prepare("INSERT INTO discord_live (guild_id, data) VALUES (?,?)");
        $stmt->execute([$guild_id,$json]);
    }

    echo json_encode(["status"=>"updated"]);
    exit;
}

// Dashboard Mode
$entry = $db->query("SELECT data FROM discord_live ORDER BY id DESC LIMIT 1")->fetch();

if (!$entry) {
    echo json_encode(["error"=>"NO_DATA"]);
    exit;
}

echo $entry["data"];
