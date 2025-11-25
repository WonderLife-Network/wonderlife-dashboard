<?php
include "header.php";
include "config.php";

$pages = $db->query("SELECT * FROM wiki ORDER BY title ASC")->fetchAll();
?>

<h1 class="title">Wiki</h1>

<?php foreach ($pages as $p): ?>
<div class="wiki-item">
    <a href="wiki_view.php?id=<?php echo $p['id']; ?>">
        <?php echo htmlspecialchars($p['title']); ?>
    </a>
</div>
<?php endforeach; ?>

<?php include "footer.php"; ?>
