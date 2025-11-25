<?php
$REQUIRED_PERMISSION = "gallery.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$images = $db->query("
    SELECT gallery_images.*, gallery_categories.name AS cat_name
    FROM gallery_images
    LEFT JOIN gallery_categories ON gallery_images.category_id = gallery_categories.id
    ORDER BY gallery_images.id DESC
")->fetchAll();
?>

<h2>Galerie Verwaltung</h2>

<a href="/dashboard/gallery/new.php" class="btn-glow">Neues Bild hochladen</a>
<a href="/dashboard/gallery/categories.php" class="btn-small">Kategorien</a>

<div class="gallery-grid">
<?php foreach ($images as $img): ?>
    <div class="gallery-item">
        <img src="/uploads/gallery/<?= $img['file_name'] ?>" class="gallery-thumb">

        <div class="gallery-info">
            <b><?= htmlspecialchars($img['title'] ?: "Ohne Titel") ?></b><br>
            <span><?= htmlspecialchars($img['cat_name'] ?: "—") ?></span>
        </div>

        <div class="gallery-actions">
            <a class="btn-small" href="/dashboard/gallery/edit.php?id=<?= $img['id'] ?>">Bearbeiten</a>
            <a class="delete-btn" href="/dashboard/gallery/delete.php?id=<?= $img['id'] ?>">Löschen</a>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php include "../footer.php"; ?>
