<?php
include "header.php";
include "config.php";

$images = $db->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
?>

<h1 class="title">Galerie</h1>

<a class="btn-glow" href="gallery_upload.php">Neues Bild hochladen</a>

<div class="gallery-grid">
<?php foreach ($images as $img): ?>
    <div class="gallery-item">
        <img src="<?php echo $img['url']; ?>" class="gallery-img">

        <div class="gallery-actions">
            <span><?php echo htmlspecialchars($img['title']); ?></span>
            <a href="gallery_delete.php?id=<?php echo $img['id']; ?>" class="delete-btn">Löschen</a>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php include "footer.php"; ?>
