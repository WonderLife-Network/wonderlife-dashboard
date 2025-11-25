<?php
$REQUIRED_PERMISSION = "news.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

$stmt = $db->prepare("DELETE FROM news_categories WHERE id=?");
$stmt->execute([$id]);

header("Location: /dashboard/news_categories/list.php");
exit;
