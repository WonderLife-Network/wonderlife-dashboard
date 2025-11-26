<?php
if (!$user) {
    echo "<p><i>Kein Benutzer ausgewählt.</i></p>";
    return;
}
?>

<div class="user-card neon-panel">
    <h3>👤 <?= $user["username"] ?></h3>
    <p>
        <b>ID:</b> <?= $user["id"] ?><br>
        <b>Erstellt:</b> <?= $user["created_at"] ?><br>
        <b>Warnungen:</b> <?= $warnCount ?><br>
        <b>Notizen:</b> <?= $noteCount ?><br>
        <b>Reports:</b> <?= $reportCount ?><br>
        <b>Bans:</b> <?= $banCount ?><br>
    </p>
</div>

<style>
.user-card {
    background: rgba(255,255,255,0.04);
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #ff0099;
    margin-bottom: 20px;
}
</style>
