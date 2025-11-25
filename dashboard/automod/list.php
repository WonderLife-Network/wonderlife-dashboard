<?php
$REQUIRED_PERMISSION = "automod.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$rules = $db->query("SELECT * FROM automod_rules ORDER BY id DESC")->fetchAll();
?>

<h2>🤖 Automod – Regeln</h2>
<a href="/dashboard/automod/new.php" class="btn-glow">Neue Regel</a>

<table class="dash-table" style="margin-top:20px;">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Typ</th>
    <th>Aktion</th>
    <th>Status</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($rules as $r): ?>
<tr>
    <td><?= $r["id"] ?></td>
    <td><?= htmlspecialchars($r["name"]) ?></td>
    <td><?= htmlspecialchars($r["type"]) ?></td>
    <td><?= htmlspecialchars($r["action"]) ?></td>
    <td><?= $r["enabled"] ? "🟢 Aktiv" : "🔴 Aus" ?></td>

    <td>
        <a href="/dashboard/automod/edit.php?id=<?= $r["id"] ?>" class="btn-small">Bearbeiten</a>
        <a href="/dashboard/automod/delete.php?id=<?= $r["id"] ?>" class="delete-btn">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
