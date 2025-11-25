<?php
include "protect_owner.php";
require "../config.php";

function generate_api_key() {
    return bin2hex(random_bytes(32));
}

if (!isset($_GET["id"])) { die("Keine ID."); }

$id = intval($_GET["id"]);
$new_key = generate_api_key();

$stmt = $db->prepare("UPDATE api_keys SET api_key=? WHERE id=?");
$stmt->execute([$new_key, $id]);

header("Location: api_keys.php?rotated=1");
exit;
