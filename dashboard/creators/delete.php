<?php
$REQUIRED_PERMISSION = "creators.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

// Media löschen
$stmt = $db->prepare("SELECT avatar, banner FROM creators WHERE id=?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if ($c["avatar"]) unlink($_SERVER["DOCUMENT_ROOT"] . "/uploads/creators/avatar/" . $c["avatar"]);
if ($c["banner"]) unlink($_SERVER["DOCUMENT_ROOT"] . "/uploads/creators/banner/" . $c["banner"]);

$db->prepare("DELETE FROM creator_socials WHERE creator_id=?")->execute([$id]);
$db->prepare("DELETE FROM creators WHERE id=?")->execute([$id]);

header("Location: /dashboard/creators/list.php");
exit;
