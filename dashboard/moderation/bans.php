<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["user"])) {
    $reason = $_POST["reason"] ?? null;
    if ($reason) {
        $stmt = $db->prepare("INSERT INTO bans (user_id, reason, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$_GET["user"], $reason]);
    }
}

if (!$user) return;

$stmt = $db->prepare("SELECT * FROM bans WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$user["id"]]);
$bans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="neon-panel">
    <h3>🔒 Bans (<?= count($bans) ?>)</h3>

    <?php if (count($bans) == 0): ?>
        <p><i>Keine Bans.</i></p>
    <?php endif; ?>

    <?php foreach ($bans as $b): ?>
        <div class="hist-entry">
            <b><?= $b["created_at"] ?></b><br>
            <?= nl2br(htmlspecialchars($b["reason"])) ?>
        </div>
    <?php endforeach; ?>
</div>
