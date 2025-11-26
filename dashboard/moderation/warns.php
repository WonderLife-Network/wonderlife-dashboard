<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["user"])) {
    $msg = $_POST["message"] ?? null;
    if ($msg) {
        $stmt = $db->prepare("INSERT INTO warnings (user_id, message, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$_GET["user"], $msg]);
    }
}

if (!$user) return;

$stmt = $db->prepare("SELECT * FROM warnings WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$user["id"]]);
$warns = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="neon-panel">
    <h3>⚠ Warnungen (<?= count($warns) ?>)</h3>

    <?php if (count($warns) == 0): ?>
        <p><i>Keine Warnungen.</i></p>
    <?php endif; ?>

    <?php foreach ($warns as $w): ?>
        <div class="hist-entry">
            <b><?= $w["created_at"] ?></b><br>
            <?= nl2br(htmlspecialchars($w["message"])) ?>
        </div>
    <?php endforeach; ?>
</div>

<style>
.hist-entry {
    background: rgba(255,255,255,0.05);
    padding: 12px;
    border-left: 3px solid #ff0099;
    border-radius: 8px;
    margin-bottom: 10px;
}
</style>
