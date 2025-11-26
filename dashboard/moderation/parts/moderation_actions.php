<?php if (!$user) return; ?>

<div class="actions-card neon-panel">
    <h3>⚠ Aktionen</h3>

    <form method="POST" action="warns.php?user=<?= $user["id"] ?>">
        <textarea name="message" placeholder="Warnung eingeben…" class="mod-textarea"></textarea>
        <button class="btn neon-btn">⚠ Warnung hinzufügen</button>
    </form>

    <hr class="divider">

    <form method="POST" action="notes.php?user=<?= $user["id"] ?>">
        <textarea name="message" placeholder="Notiz eingeben…" class="mod-textarea"></textarea>
        <button class="btn neon-btn-secondary">📝 Notiz hinzufügen</button>
    </form>

    <hr class="divider">

    <form method="POST" action="bans.php?user=<?= $user["id"] ?>">
        <textarea name="reason" placeholder="Ban-Grund…" class="mod-textarea"></textarea>
        <button class="btn red-btn">🔒 Benutzer bannen</button>
    </form>
</div>

<style>
.actions-card {
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #a44cff66;
}

.mod-textarea {
    width: 100%;
    min-height: 90px;
    background: #140a20;
    color: #fff;
    border: 1px solid #a44cff66;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 10px;
}

.red-btn {
    background: #ff003c;
    padding: 10px 18px;
    border-radius: 10px;
    box-shadow: 0 0 12px #ff003c88;
    border: none;
    cursor: pointer;
    color: white;
}
</style>
