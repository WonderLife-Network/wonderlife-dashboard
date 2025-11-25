<?php
include "header.php";
include "config.php";

$threads = $db->query("SELECT * FROM forum_threads ORDER BY id DESC")->fetchAll();
?>

<h1 class="title">Forum</h1>

<a class="btn-glow" href="forum_new.php">Neues Thema erstellen</a>

<?php foreach ($threads as $t): ?>
<div class="thread">
    <a href="forum_view.php?id=<?php echo $t['id']; ?>">
        <h2><?php echo htmlspecialchars($t['title']); ?></h2>
    </a>
    <p><?php echo htmlspecialchars($t['author']); ?></p>
</div>
<?php endforeach; ?>

<?php include "footer.php"; ?>
