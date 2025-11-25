<?php
require "auth.php";
require "../dashboard/config.php";

if ($API_SCOPE != "admin" && $API_SCOPE != "write") {
    die(json_encode(["error" => "FORBIDDEN_SCOPE"]));
}

// Bot sendet Daten (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $json = $_POST["json"] ?? null;

    if ($json) {
        $stmt = $db->prepare("INSERT INTO fivem_live (data) VALUES (?)");
        $stmt->execute([$json]);
    }

    echo json_encode(["status"=>"updated"]);
    exit;
}

// Dashboard liest Daten
$entry = $db->query("SELECT data FROM fivem_live ORDER BY id DESC LIMIT 1")->fetch();

if (!$entry) {
    echo json_encode(["error"=>"NO_DATA"]);
    exit;
}

echo $entry["data"];
