<?php
$REQUIRED_PERMISSION = "gallery.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$cats = $db->query("SELECT * FROM gallery_categories ORDER BY id DESC")->fetchAll();
?>

<h2>Galerie Kategorien</h2>

<a href="/dashboard/gallery/category_new.php" class="btn-glow">Neue Kategorie</a>

<table class="dash-table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Slug</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($cats as $c): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= htmlspecialchars($c['name']) ?></td>
    <td><?= htmlspecialchars($c['slug']) ?></td>
    <td>
        <a class="btn-small" href="/dashboard/gallery/category_edit.php?id=<?= $c['id'] ?>">Bearbeiten</a>
        <a class="delete-btn" href="/dashboard/gallery/category_delete.php?id=<?= $c['id'] ?>">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
