<?php
include "header.php";
include "config.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM forum_threads WHERE id=?");
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) {
    die("Thema nicht gefunden");
}
?>

<h1 class="title"><?php echo htmlspecialchars($t['title']); ?></h1>

<div class="thread-view">
    <p><strong><?php echo htmlspecialchars($t['author']); ?></strong></p>
    <p><?php echo nl2br(htmlspecialchars($t['content'])); ?></p>
    <small><?php echo $t['created_at']; ?></small>
</div>

<?php include "footer.php"; ?>
