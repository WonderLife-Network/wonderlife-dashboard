<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["user"])) {
    $msg = $_POST["message"] ?? null;
    if ($msg) {
        $stmt = $db->prepare("INSERT INTO notes (user_id, message, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$_GET["user"], $msg]);
    }
}

if (!$user) return;

$stmt = $db->prepare("SELECT * FROM notes WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$user["id"]]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="neon-panel">
    <h3>📝 Notizen (<?= count($notes) ?>)</h3>

    <?php if (count($notes) == 0): ?>
        <p><i>Keine Notizen.</i></p>
    <?php endif; ?>

    <?php foreach ($notes as $n): ?>
        <div class="hist-entry">
            <b><?= $n["created_at"] ?></b><br>
            <?= nl2br(htmlspecialchars($n["message"])) ?>
        </div>
    <?php endforeach; ?>
</div>
