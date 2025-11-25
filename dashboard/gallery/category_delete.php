<?php
$REQUIRED_PERMISSION = "gallery.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

// Bilder aus dieser Kategorie NICHT löschen
// → nur Kategorie entfernen
$db->prepare("UPDATE gallery_images SET category_id=NULL WHERE category_id=?")->execute([$id]);

// Kategorie löschen
$db->prepare("DELETE FROM gallery_categories WHERE id=?")->execute([$id]);

header("Location: /dashboard/gallery/categories.php");
exit;
