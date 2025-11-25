<?php
$REQUIRED_PERMISSION = "news.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

// Bild löschen
$img = $db->prepare("SELECT image FROM news WHERE id=?");
$img->execute([$id]);
$file = $img->fetchColumn();

if ($file) {
    $path = $_SERVER["DOCUMENT_ROOT"] . "/uploads/news/" . $file;
    if (file_exists($path)) unlink($path);
}

$stmt = $db->prepare("DELETE FROM news WHERE id=?");
$stmt->execute([$id]);

header("Location: /dashboard/news/list.php");
exit;
