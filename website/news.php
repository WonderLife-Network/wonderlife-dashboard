<?php
include "header.php";
include "config.php";

$posts = $db->query("SELECT * FROM news ORDER BY id DESC")->fetchAll();
?>

<h1 class="title">News & Updates</h1>

<?php foreach ($posts as $n): ?>
<div class="news-item">
    <h2><?php echo htmlspecialchars($n['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($n['content'])); ?></p>
    <small><?php echo $n['created_at']; ?></small>
</div>
<?php endforeach; ?>

<?php include "footer.php"; ?>
