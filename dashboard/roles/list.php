<?php
$REQUIRED_PERMISSION = "roles.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

// Rollen laden
$roles = $db->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();
?>

<h2>Rollenverwaltung</h2>
<a class="btn-glow" href="/dashboard/roles/new.php">Neue Rolle anlegen</a>

<table class="dash-table">
    <tr>
        <th>ID</th>
        <th>Rollenname</th>
        <th>Aktionen</th>
    </tr>

    <?php foreach ($roles as $r): ?>
    <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['role_name']); ?></td>
        <td>
            <a class="btn-small" href="/dashboard/roles/edit.php?id=<?= $r['id'] ?>">Bearbeiten</a>
            <a class="delete-btn" href="/dashboard/roles/delete.php?id=<?= $r['id'] ?>">Löschen</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include "../footer.php"; ?>
