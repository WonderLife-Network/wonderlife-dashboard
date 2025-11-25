<?php
$REQUIRED_PERMISSION = "forum.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$cats = $db->query("
    SELECT forum_categories.*, 
    (SELECT COUNT(*) FROM forum_topics WHERE category_id = forum_categories.id) AS topic_count
    FROM forum_categories
    ORDER BY id DESC
")->fetchAll();
?>

<h2>Forum Kategorien</h2>

<a href="/dashboard/forum/category_new.php" class="btn-glow">Neue Kategorie</a>

<table class="dash-table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Themen</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($cats as $c): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= htmlspecialchars($c['name']) ?></td>
    <td><?= $c['topic_count'] ?></td>
    <td>
        <a class="btn-small" href="/dashboard/forum/category_edit.php?id=<?= $c['id'] ?>">Bearbeiten</a>
        <a class="btn-small" href="/dashboard/forum/topics.php?id=<?= $c['id'] ?>">Themen</a>
        <a class="delete-btn" href="/dashboard/forum/category_delete.php?id=<?= $c['id'] ?>">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
