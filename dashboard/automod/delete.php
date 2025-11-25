<?php
$REQUIRED_PERMISSION = "automod.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

// Logs entfernen
$db->prepare("DELETE FROM automod_logs WHERE rule_id=?")->execute([$id]);

// Regel löschen
$db->prepare("DELETE FROM automod_rules WHERE id=?")->execute([$id]);

header("Location: /dashboard/automod/list.php");
exit;
