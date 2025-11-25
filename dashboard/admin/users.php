<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/users.css">

<div class="content">

    <h2 style="color:white;">👤 Benutzerverwaltung</h2>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Benutzername</th>
            <th>Email</th>
            <th>Rolle</th>
            <th>Erstellt</th>
            <th>Aktionen</th>
        </tr>

        <?php
        $users = $db->query("SELECT * FROM users ORDER BY id ASC");

        foreach ($users as $u):
        ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>
            <td><?= $u['created_at'] ?></td>
            <td>
                <a class="btn" href="user_edit.php?id=<?= $u['id'] ?>">Bearbeiten</a>
                <a class="btn-danger" href="user_delete.php?id=<?= $u['id'] ?>" onclick="return confirm('Benutzer wirklich löschen?');">Löschen</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

<?php include "footer.php"; ?>
