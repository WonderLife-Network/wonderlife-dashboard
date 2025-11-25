<?php
include "protect_owner.php";
require "../config.php";

if (!isset($_GET["id"])) { die("Keine ID."); }

$id = intval($_GET["id"]);

$stmt = $db->prepare("DELETE FROM api_keys WHERE id=?");
$stmt->execute([$id]);

header("Location: api_keys.php?deleted=1");
exit;
