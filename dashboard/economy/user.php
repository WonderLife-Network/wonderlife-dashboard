<?php
$REQUIRED_PERMISSION = "economy.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$user_id = $_GET["id"];

// User laden
$stmt = $db->prepare("
    SELECT users.*, economy_users.balance
    FROM users
    LEFT JOIN economy_users ON users.id = economy_users.user_id
    WHERE users.id=?
");
$stmt->execute([$user_id]);
$USER = $stmt->fetch();

if (!$USER) die("<h2>User nicht gefunden!</h2>");

$transactions = $db->prepare("
    SELECT economy_transactions.*, users.name AS staff
    FROM economy_transactions
    LEFT JOIN users ON users.id = economy_transactions.created_by
    WHERE user_id=?
    ORDER BY id DESC
");
$transactions->execute([$user_id]);
$transactions = $transactions->fetchAll();
?>

<h2>💰 Economy – <?= htmlspecialchars($USER["name"]) ?></h2>

<p><b>Aktuelles Guthaben:</b> <?= $USER["balance"] ?? 0 ?> WL-Credits</p>

<a href="/dashboard/economy/transaction_new.php?id=<?= $USER["id"] ?>" class="btn-glow">Neue Transaktion</a>

<br><br>

<h3>📜 Transaktionen</h3>

<table class="dash-table">
<tr>
    <th>Betrag</th>
    <th>Typ</th>
    <th>Grund</th>
    <th>Von</th>
    <th>Datum</th>
</tr>

<?php foreach ($transactions as $t): ?>
<tr>
    <td><?= $t["amount"] ?></td>
    <td><?= htmlspecialchars($t["type"]) ?></td>
    <td><?= htmlspecialchars($t["reason"]) ?></td>
    <td><?= htmlspecialchars($t["staff"]) ?></td>
    <td><?= $t["created_at"] ?></td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
