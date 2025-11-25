<?php
include "header.php";
include "config.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM wiki WHERE id=?");
$stmt->execute([$id]);
$page = $stmt->fetch();

if (!$page) {
    die("<h1 class='title'>Seite nicht gefunden</h1>");
}
?>

<h1 class="title"><?php echo htmlspecialchars($page['title']); ?></h1>

<div class="wiki-content">
    <?php echo nl2br($page['content']); ?>
</div>

<a class="btn-glow" href="wiki_edit.php?id=<?php echo $page['id']; ?>">Bearbeiten</a>
<a class="delete-btn" href="wiki_delete.php?id=<?php echo $page['id']; ?>">Löschen</a>

<?php include "footer.php"; ?>
