<?php
$REQUIRED_PERMISSION = "economy.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$users = $db->query("
    SELECT users.id, users.name, economy_users.balance
    FROM users
    LEFT JOIN economy_users ON users.id = economy_users.user_id
    ORDER BY economy_users.balance DESC
")->fetchAll();
?>

<h2>💰 Economy – Benutzer Übersicht</h2>

<table class="dash-table">
<tr>
    <th>User</th>
    <th>Guthaben</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
    <td><?= htmlspecialchars($u["name"]) ?></td>
    <td><?= $u["balance"] ?? 0 ?> WL-Credits</td>
    <td>
        <a href="/dashboard/economy/user.php?id=<?= $u["id"] ?>" class="btn-small">Ansehen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
