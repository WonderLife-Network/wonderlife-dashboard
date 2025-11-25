<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

// Alle User + Economy
$users = $db->query("
    SELECT u.id, u.username, u.email, 
    COALESCE(e.balance,0) AS balance
    FROM users u
    LEFT JOIN economy_users e ON u.id = e.user_id
    ORDER BY u.id ASC
")->fetchAll();

// Letzte 20 Transaktionen
$transactions = $db->query("
    SELECT et.*, u.username
    FROM economy_transactions et
    LEFT JOIN users u ON et.user_id = u.id
    ORDER BY et.id DESC
    LIMIT 20
")->fetchAll();
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/economy.css">

<div class="content">

<h2 style="color:white;">💰 Economy Verwaltung</h2>

<h3 style="color:#ff00d4;">Benutzer & Guthaben</h3>

<table class="table">
<tr>
    <th>ID</th>
    <th>Benutzername</th>
    <th>Balance</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u["id"] ?></td>
    <td><?= htmlspecialchars($u["username"]) ?></td>
    <td><b><?= $u["balance"] ?></b> WL-Credits</td>
    <td>
        <a class="btn" href="eco_add.php?id=<?= $u['id'] ?>">+</a>
        <a class="btn" href="eco_remove.php?id=<?= $u['id'] ?>">–</a>
        <a class="btn" href="eco_set.php?id=<?= $u['id'] ?>">Set</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<h3 style="color:#ff00d4; margin-top:40px;">🔄 Letzte Transaktionen</h3>

<table class="table">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Betrag</th>
    <th>Typ</th>
    <th>Nachricht</th>
    <th>Zeit</th>
</tr>

<?php foreach ($transactions as $t): ?>
<tr>
    <td><?= $t["id"] ?></td>
    <td><?= htmlspecialchars($t["username"]) ?></td>
    <td><?= $t["amount"] ?></td>
    <td><?= $t["type"] ?></td>
    <td><?= htmlspecialchars($t["message"]) ?></td>
    <td><?= $t["created_at"] ?></td>
</tr>
<?php endforeach; ?>
</table>

</div>

<?php include "footer.php"; ?>
