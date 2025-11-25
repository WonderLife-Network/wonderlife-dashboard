<?php
$REQUIRED_PERMISSION = "news.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$cats = $db->query("SELECT * FROM news_categories ORDER BY id DESC")->fetchAll();
?>

<h2>News Kategorien</h2>
<a href="/dashboard/news_categories/new.php" class="btn-glow">Neue Kategorie</a>

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
        <a class="btn-small" href="/dashboard/news_categories/edit.php?id=<?= $c['id'] ?>">Bearbeiten</a>
        <a class="delete-btn" href="/dashboard/news_categories/delete.php?id=<?= $c['id'] ?>">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
