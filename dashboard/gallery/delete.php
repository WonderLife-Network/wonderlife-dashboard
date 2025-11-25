<?php
$REQUIRED_PERMISSION = "gallery.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT file_name FROM gallery_images WHERE id=?");
$stmt->execute([$id]);
$file = $stmt->fetchColumn();

if ($file) {
    $path = $_SERVER["DOCUMENT_ROOT"] . "/uploads/gallery/" . $file;
    if (file_exists($path)) unlink($path);
}

$db->prepare("DELETE FROM gallery_images WHERE id=?")->execute([$id]);

header("Location: /dashboard/gallery/list.php");
exit;
