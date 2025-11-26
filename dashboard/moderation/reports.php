<?php
if (!$user) return;

$stmt = $db->prepare("SELECT * FROM reports WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$user["id"]]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="neon-panel">
    <h3>🚨 Reports (<?= count($reports) ?>)</h3>

    <?php if (count($reports) == 0): ?>
        <p><i>Keine Reports.</i></p>
    <?php endif; ?>

    <?php foreach ($reports as $r): ?>
        <div class="hist-entry">
            <b><?= $r["created_at"] ?></b><br>
            <?= nl2br(htmlspecialchars($r["message"])) ?>
        </div>
    <?php endforeach; ?>
</div>
