<?php
require "../../config.php";
header("Content-Type: application/json");

if ($API_SCOPE != "admin" && $API_SCOPE != "reactionroles" && $API_SCOPE != "write") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$stmt = $db->query("SELECT * FROM reaction_panels ORDER BY id DESC");

echo json_encode([
    "status" => "OK",
    "panels" => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);
