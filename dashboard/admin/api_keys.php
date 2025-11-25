<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

$keys = $db->query("SELECT * FROM api_keys ORDER BY id DESC")->fetchAll();
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/api_keys.css">

<div class="content">

<h2 style="color:white;">🔑 API-Key Verwaltung</h2>

<a class="btn" href="api_key_add.php">Neuen API-Key erstellen</a>

<table class="table">
<tr>
    <th>ID</th>
    <th>Key</th>
    <th>Owner</th>
    <th>Berechtigung</th>
    <th>Erstellt</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($keys as $k): ?>
<tr>
    <td><?= $k["id"] ?></td>
    <td><?= htmlspecialchars($k["api_key"]) ?></td>
    <td><?= htmlspecialchars($k["owner"]) ?></td>
    <td><?= htmlspecialchars($k["permission"]) ?></td>
    <td><?= $k["created_at"] ?></td>
    <td>
        <a class="btn" href="api_key_reset.php?id=<?= $k['id'] ?>">Rotieren</a>
        <a class="btn-danger" href="api_key_delete.php?id=<?= $k['id'] ?>" onclick="return confirm('Wirklich löschen?');">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</div>

<?php include "footer.php"; ?>
