<?php
include "header.php";
include "config.php";

$team = $db->query("SELECT * FROM team ORDER BY rank ASC")->fetchAll();
?>

<h1 class="title">Unser Team</h1>

<a class="btn-glow" href="team_new.php">Neues Mitglied hinzufügen</a>

<div class="team-grid">
<?php foreach ($team as $t): ?>
    <div class="team-card">

        <?php if ($t['avatar']): ?>
            <img src="<?php echo $t['avatar']; ?>" class="team-avatar">
        <?php endif; ?>

        <h2><?php echo htmlspecialchars($t['name']); ?></h2>
        <p><?php echo htmlspecialchars($t['role']); ?></p>

        <div class="team-actions">
            <a class="btn-glow" href="team_edit.php?id=<?php echo $t['id']; ?>">Bearbeiten</a>
            <a class="delete-btn" href="team_delete.php?id=<?php echo $t['id']; ?>">Löschen</a>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php include "footer.php"; ?>
