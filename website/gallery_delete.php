<?php
include "config.php";

$id = $_GET["id"];

// Bild-Daten holen
$stmt = $db->prepare("SELECT * FROM gallery WHERE id=?");
$stmt->execute([$id]);
$img = $stmt->fetch();

if (!$img) {
    die("Bild nicht gefunden.");
}

// Datei löschen
if (file_exists($img["url"])) {
    unlink($img["url"]);
}

// DB löschen
$stmt = $db->prepare("DELETE FROM gallery WHERE id=?");
$stmt->execute([$id]);

header("Location: gallery.php");
exit;
