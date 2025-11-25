<?php
$REQUIRED_PERMISSION = "creators.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$creators = $db->query("
    SELECT creators.*, users.name AS user_name
    FROM creators
    LEFT JOIN users ON users.id = creators.user_id
    ORDER BY creators.id DESC
")->fetchAll();
?>

<h2>Creator Verwaltung</h2>

<a href="/dashboard/creators/new.php" class="btn-glow">Neuen Creator anlegen</a>

<table class="dash-table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>User</th>
    <th>Avatar</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($creators as $c): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= htmlspecialchars($c['name']) ?></td>
    <td><?= htmlspecialchars($c['user_name'] ?: "—") ?></td>
    <td>
        <?php if ($c['avatar']): ?>
            <img src="/uploads/creators/avatar/<?= $c['avatar'] ?>" style="width:50px;border-radius:50%;">
        <?php else: ?>
            —
        <?php endif; ?>
    </td>
    <td>
        <a class="btn-small" href="/dashboard/creators/edit.php?id=<?= $c['id'] ?>">Bearbeiten</a>
        <a class="btn-small" href="/dashboard/creators/socials.php?id=<?= $c['id'] ?>">Socials</a>
        <a class="delete-btn" href="/dashboard/creators/delete.php?id=<?= $c['id'] ?>">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
