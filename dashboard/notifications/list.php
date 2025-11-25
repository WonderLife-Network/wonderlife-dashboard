<?php
require "../auth_check.php";
include "../header.php";

$user = $AUTH_USER;

$stmt = $db->prepare("
    SELECT * FROM notifications
    WHERE user_id=?
    ORDER BY id DESC
");
$stmt->execute([$user["id"]]);
$notes = $stmt->fetchAll();
?>

<h2>Benachrichtigungen</h2>

<table class="dash-table">
<tr>
    <th>Status</th>
    <th>Titel</th>
    <th>Nachricht</th>
    <th>Datum</th>
    <th>Aktion</th>
</tr>

<?php foreach ($notes as $n): ?>
<tr class="<?= $n['read_at'] ? '' : 'notif-unread' ?>">
    <td><?= $n['read_at'] ? 'Gelesen' : 'Neu' ?></td>
    <td><?= htmlspecialchars($n['title']) ?></td>
    <td><?= htmlspecialchars($n['message']) ?></td>
    <td><?= $n['created_at'] ?></td>
    <td>
        <?php if (!$n['read_at']): ?>
        <a class="btn-small" href="/dashboard/notifications/read.php?id=<?= $n['id'] ?>">Markieren als gelesen</a>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
