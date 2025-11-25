<?php
$REQUIRED_PERMISSION = "roles.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

$stmt = $db->prepare("DELETE FROM roles WHERE id=?");
$stmt->execute([$id]);

header("Location: /dashboard/roles/list.php");
exit;
