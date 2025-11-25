<?php
$REQUIRED_PERMISSION = "permissions.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

$stmt = $db->prepare("DELETE FROM permissions WHERE id=?");
$stmt->execute([$id]);

header("Location: /dashboard/permissions/list.php");
exit;
