<?php
$REQUIRED_PERMISSION = "news.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$news = $db->query("
    SELECT news.*, news_categories.name AS cat_name, users.name AS author_name
    FROM news
    LEFT JOIN news_categories ON news.category_id = news_categories.id
    LEFT JOIN users ON news.author_id = users.id
    ORDER BY news.id DESC
")->fetchAll();
?>

<h2>News Verwaltung</h2>
<a href="/dashboard/news/new.php" class="btn-glow">Neue News erstellen</a>

<table class="dash-table">
<tr>
    <th>ID</th>
    <th>Titel</th>
    <th>Kategorie</th>
    <th>Status</th>
    <th>Autor</th>
    <th>Datum</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($news as $n): ?>
<tr>
    <td><?= $n['id'] ?></td>
    <td><?= htmlspecialchars($n['title']) ?></td>
    <td><?= htmlspecialchars($n['cat_name'] ?? '–') ?></td>
    <td><?= $n['status'] ?></td>
    <td><?= htmlspecialchars($n['author_name']) ?></td>
    <td><?= $n['publish_date'] ?></td>

    <td>
        <a class="btn-small" href="/dashboard/news/edit.php?id=<?= $n['id'] ?>">Bearbeiten</a>
        <a class="delete-btn" href="/dashboard/news/delete.php?id=<?= $n['id'] ?>">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
