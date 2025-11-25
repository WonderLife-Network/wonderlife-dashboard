<?php
$REQUIRED_PERMISSION = "wiki.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

// Prüfen: Gibt es Seiten, die diese Kategorie nutzen?
$stmt = $db->prepare("SELECT COUNT(*) FROM wiki_pages WHERE category_id=?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

// Falls ja → Verhindern oder automatisch Kategorie entfernen?
// Wir löschen einfach die Kategorie, Seiten werden zu "ohne Kategorie".
$db->prepare("UPDATE wiki_pages SET category_id=NULL WHERE category_id=?")->execute([$id]);

// Kategorie löschen
$db->prepare("DELETE FROM wiki_categories WHERE id=?")->execute([$id]);

header("Location: /dashboard/wiki/categories.php");
exit;
