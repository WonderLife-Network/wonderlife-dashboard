<?php
$REQUIRED_PERMISSION = "wiki.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

$stmt = $db->prepare("DELETE FROM wiki_pages WHERE id=?");
$stmt->execute([$id]);

header("Location: /dashboard/wiki/list.php");
exit;
