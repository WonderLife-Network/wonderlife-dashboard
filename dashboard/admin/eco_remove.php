<?php
include "protect_owner.php";
require "../config.php";

if (!isset($_GET["id"])) { die("Keine ID."); }
$id = intval($_GET["id"]);

if (isset($_POST["amount"])) {
    $amount = intval($_POST["amount"]);

    // Balance verringern
    $stmt = $db->prepare("
        UPDATE economy_users 
        SET balance = GREATEST(balance - ?, 0)
        WHERE user_id = ?
    ");
    $stmt->execute([$amount, $id]);

    // Transaktion speichern
    $stmt = $db->prepare("INSERT INTO economy_transactions (user_id, amount, type, message) VALUES (?, ?, 'remove', ?)");
    $stmt->execute([$id, $amount, "Admin: Remove Money"]);

    header("Location: economy.php?removed=1");
    exit;
}
?>

<form method="POST" class="content">
    <h2 style="color:white;">💰 Betrag entfernen</h2>
    <input type="number" name="amount" placeholder="Betrag" required>
    <button class="btn">Entfernen</button>
</form>
