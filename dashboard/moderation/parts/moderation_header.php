<div class="mod-header neon-panel">
    <h2>🛡 Moderation Panel</h2>

    <form method="GET" class="mod-search-form">
        <input type="text" name="user" placeholder="Benutzer suchen…" value="<?= htmlspecialchars($_GET["user"] ?? "") ?>" class="mod-input">
        <button class="btn neon-btn">🔍 Suchen</button>
    </form>
</div>

<style>
.mod-header {
    padding: 20px;
    margin-bottom: 20px;
}

.mod-search-form {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.mod-input {
    flex: 1;
    padding: 10px 14px;
    background: #140a20;
    border: 1px solid #a44cff66;
    color: #fff;
    border-radius: 8px;
}
</style>
