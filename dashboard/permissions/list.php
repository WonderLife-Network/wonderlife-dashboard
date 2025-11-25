<?php
$REQUIRED_PERMISSION = "permissions.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$perms = $db->query("SELECT * FROM permissions ORDER BY id ASC")->fetchAll();
?>

<h2>Berechtigungen</h2>

<a class="btn-glow" href="/dashboard/permissions/new.php">Neue Berechtigung</a>

<table class="dash-table">
    <tr>
        <th>ID</th>
        <th>Permission Key</th>
        <th>Beschreibung</th>
        <th>Aktionen</th>
    </tr>

<?php foreach ($perms as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['permission_key']) ?></td>
        <td><?= htmlspecialchars($p['description']) ?></td>
        <td>
            <a class="btn-small" href="/dashboard/permissions/edit.php?id=<?= $p['id'] ?>">Bearbeiten</a>
            <a class="delete-btn" href="/dashboard/permissions/delete.php?id=<?= $p['id'] ?>">Löschen</a>
        </td>
    </tr>
<?php endforeach; ?>
</table>

<?php include "../footer.php"; ?>
