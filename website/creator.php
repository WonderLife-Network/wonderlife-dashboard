<?php
include "header.php";
include "config.php";

$creators = $db->query("SELECT * FROM creators ORDER BY id DESC")->fetchAll();
?>

<h1 class="title">WonderLife Creator</h1>

<a class="btn-glow" href="creator_new.php">Neuen Creator hinzufügen</a>

<div class="creator-grid">
<?php foreach ($creators as $c): ?>
    <div class="creator-card">

        <?php if ($c['avatar']): ?>
            <img src="<?php echo $c['avatar']; ?>" class="creator-avatar">
        <?php endif; ?>

        <h2><?php echo htmlspecialchars($c['name']); ?></h2>
        <p><?php echo htmlspecialchars($c['description']); ?></p>

        <div class="creator-links">

            <?php if ($c['twitch']): ?>
                <a href="<?php echo $c['twitch']; ?>" target="_blank" class="clink twitch">Twitch</a>
            <?php endif; ?>

            <?php if ($c['youtube']): ?>
                <a href="<?php echo $c['youtube']; ?>" target="_blank" class="clink youtube">YouTube</a>
            <?php endif; ?>

            <?php if ($c['tiktok']): ?>
                <a href="<?php echo $c['tiktok']; ?>" target="_blank" class="clink tiktok">TikTok</a>
            <?php endif; ?>

            <?php if ($c['instagram']): ?>
                <a href="<?php echo $c['instagram']; ?>" target="_blank" class="clink instagram">Instagram</a>
            <?php endif; ?>

            <?php if ($c['twitter']): ?>
                <a href="<?php echo $c['twitter']; ?>" target="_blank" class="clink twitter">X/Twitter</a>
            <?php endif; ?>

        </div>

        <div class="team-actions">
            <a class="btn-glow" href="creator_edit.php?id=<?php echo $c['id']; ?>">Bearbeiten</a>
            <a class="delete-btn" href="creator_delete.php?id=<?php echo $c['id']; ?>">Löschen</a>
        </div>

    </div>
<?php endforeach; ?>
</div>

<?php include "footer.php"; ?>
