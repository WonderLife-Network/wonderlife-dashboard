<?php
include "protect_owner.php";
require "../config.php";

if (!isset($_GET["id"])) { die("Keine ID."); }
$id = intval($_GET["id"]);

// XP & Level zurücksetzen
$stmt = $db->prepare("UPDATE levels SET xp=0, level=1 WHERE user_id=?");
$stmt->execute([$id]);

// Reset in Logs eintragen
$stmt2 = $db->prepare("INSERT INTO levels_logs (user_id, xp_gained, reason) VALUES (?, ?, ?)");
$stmt2->execute([$id, 0, 'Reset durch Admin']);

header("Location: levels.php?reset=1");
exit;
