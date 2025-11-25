<?php
$REQUIRED_PERMISSION = "economy.manage";
require "../auth_check.php";
require "../permission_check.php";

include "../header.php";

$user_id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$USER = $stmt->fetch();

if (!$USER) die("<h2>User nicht gefunden</h2>");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = intval($_POST["amount"]);
    $type   = $_POST["type"];
    $reason = $_POST["reason"];

    // 1. Transaktion eintragen
    $stmt = $db->prepare("
        INSERT INTO economy_transactions (user_id, amount, type, reason, created_by)
        VALUES (?,?,?,?,?)
    ");
    $stmt->execute([$user_id, $amount, $type, $reason, $AUTH_USER["id"]]);

    // 2. Guthaben aktualisieren
    if ($type == "add") {
        $db->prepare("INSERT INTO economy_users (user_id, balance) VALUES (?,?) ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance)")
           ->execute([$user_id, $amount]);
    }

    if ($type == "remove") {
        $db->prepare("INSERT INTO economy_users (user_id, balance) VALUES (?,?) ON DUPLICATE KEY UPDATE balance = balance - VALUES(balance)")
           ->execute([$user_id, $amount]);
    }

    echo "<script>alert('Transaktion erstellt!'); window.location='/dashboard/economy/user.php?id=$user_id';</script>";
}
?>

<h2>Neue Transaktion für <?= htmlspecialchars($USER["name"]) ?></h2>

<form method="POST" class="form-box">

    <label>Betrag (positiv oder negativ)</label>
    <input type="number" name="amount" class="input" required>

    <label>Typ</label>
    <select class="input" name="type">
        <option value="add">Guthaben hinzufügen</option>
        <option value="remove">Guthaben abziehen</option>
    </select>

    <label>Grund</label>
    <input type="text" name="reason" class="input">

    <button class="btn-glow">Speichern</button>
</form>

<?php include "../footer.php"; ?>
