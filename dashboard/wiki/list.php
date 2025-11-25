<?php
$REQUIRED_PERMISSION = "wiki.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$pages = $db->query("
    SELECT wiki_pages.*, wiki_categories.name AS cat_name
    FROM wiki_pages
    LEFT JOIN wiki_categories ON wiki_pages.category_id = wiki_categories.id
    ORDER BY wiki_pages.id DESC
")->fetchAll();
?>

<h2>Wiki Seiten</h2>

<a href="/dashboard/wiki/new.php" class="btn-glow">Neue Seite</a>
<a href="/dashboard/wiki/categories.php" class="btn-small">Kategorien</a>

<table class="dash-table">
<tr>
    <th>ID</th>
    <th>Titel</th>
    <th>Kategorie</th>
    <th>Slug</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($pages as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td><?= htmlspecialchars($p['title']) ?></td>
    <td><?= htmlspecialchars($p['cat_name'] ?? '–') ?></td>
    <td><?= htmlspecialchars($p['slug']) ?></td>
    <td>
        <a class="btn-small" href="/dashboard/wiki/edit.php?id=<?= $p['id'] ?>">Bearbeiten</a>
        <a class="delete-btn" href="/dashboard/wiki/delete.php?id=<?= $p['id'] ?>">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
