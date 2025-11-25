<?php
include "protect_owner.php";
require "../config.php";

if (!isset($_GET["id"])) { die("Keine ID."); }
$id = intval($_GET["id"]);

if (isset($_POST["amount"])) {
    $amount = intval($_POST["amount"]);

    // Balance setzen
    $stmt = $db->prepare("
        INSERT INTO economy_users (user_id, balance) 
        VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE balance = VALUES(balance)
    ");
    $stmt->execute([$id, $amount]);

    // Transaktion speichern
    $stmt = $db->prepare("INSERT INTO economy_transactions (user_id, amount, type, message) VALUES (?, ?, 'set', ?)");
    $stmt->execute([$id, $amount, "Admin: Set Money"]);

    header("Location: economy.php?set=1");
    exit;
}
?>

<form method="POST" class="content">
    <h2 style="color:white;">💰 Balance setzen</h2>
    <input type="number" name="amount" placeholder="Neuer Betrag" required>
    <button class="btn">Setzen</button>
</form>
