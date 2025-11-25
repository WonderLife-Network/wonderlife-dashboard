<?php
$REQUIRED_PERMISSION = "forum.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$cat_id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM forum_categories WHERE id=?");
$stmt->execute([$cat_id]);
$cat = $stmt->fetch();

$topics = $db->prepare("
    SELECT forum_topics.*, users.name AS author
    FROM forum_topics
    JOIN users ON forum_topics.author_id = users.id
    WHERE category_id=?
    ORDER BY id DESC
");
$topics->execute([$cat_id]);
$topics = $topics->fetchAll();
?>

<h2>Themen: <?= htmlspecialchars($cat['name']) ?></h2>

<table class="dash-table">
<tr>
    <th>ID</th>
    <th>Titel</th>
    <th>Autor</th>
    <th>Datum</th>
    <th>Beiträge</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($topics as $t): 
$count = $db->prepare("SELECT COUNT(*) FROM forum_posts WHERE topic_id=?");
$count->execute([$t['id']]);
$post_count = $count->fetchColumn();
?>
<tr>
    <td><?= $t['id'] ?></td>
    <td><?= htmlspecialchars($t['title']) ?></td>
    <td><?= htmlspecialchars($t['author']) ?></td>
    <td><?= $t['created_at'] ?></td>
    <td><?= $post_count ?></td>
    <td>
        <a class="btn-small" href="/dashboard/forum/posts.php?id=<?= $t['id'] ?>">Beiträge</a>
        <a class="delete-btn" href="/dashboard/forum/topic_delete.php?id=<?= $t['id'] ?>">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
