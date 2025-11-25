<?php
$REQUIRED_PERMISSION = "forum.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$topic_id = $_GET["id"];

$stmt = $db->prepare("
    SELECT forum_topics.*, users.name AS author_name, forum_categories.name AS cat_name
    FROM forum_topics
    JOIN users ON forum_topics.author_id = users.id
    JOIN forum_categories ON forum_categories.id = forum_topics.category_id
    WHERE forum_topics.id=?
");
$stmt->execute([$topic_id]);
$topic = $stmt->fetch();

$posts = $db->prepare("
    SELECT forum_posts.*, users.name AS author_name
    FROM forum_posts
    JOIN users ON users.id = forum_posts.author_id
    WHERE topic_id=?
    ORDER BY id ASC
");
$posts->execute([$topic_id]);
$posts = $posts->fetchAll();
?>

<h2>Beiträge: <?= htmlspecialchars($topic['title']) ?></h2>
<p>Kategorie: <b><?= htmlspecialchars($topic['cat_name']) ?></b></p>

<table class="dash-table">
<tr>
    <th>ID</th>
    <th>Autor</th>
    <th>Inhalt</th>
    <th>Datum</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($posts as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td><?= htmlspecialchars($p['author_name']) ?></td>
    <td><?= htmlspecialchars($p['content']) ?></td>
    <td><?= $p['created_at'] ?></td>
    <td>
        <a class="delete-btn" href="/dashboard/forum/post_delete.php?id=<?= $p['id'] ?>&topic=<?= $topic_id ?>">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
