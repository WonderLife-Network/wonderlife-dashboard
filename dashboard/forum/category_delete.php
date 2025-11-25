<?php
$REQUIRED_PERMISSION = "forum.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

// ALLE Themen & Beiträge löschen
$db->prepare("DELETE FROM forum_posts WHERE topic_id IN (SELECT id FROM forum_topics WHERE category_id=?)")->execute([$id]);
$db->prepare("DELETE FROM forum_topics WHERE category_id=?")->execute([$id]);

// Kategorie löschen
$db->prepare("DELETE FROM forum_categories WHERE id=?")->execute([$id]);

header("Location: /dashboard/forum/list.php");
exit;
