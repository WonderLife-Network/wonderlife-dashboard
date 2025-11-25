<?php
include "protect_owner.php";
require "../config.php";

if (!isset($_GET["id"])) { die("Keine ID."); }
$id = intval($_GET["id"]);

if (isset($_POST["amount"])) {
    $amount = intval($_POST["amount"]);

    // Balance erhöhen
    $stmt = $db->prepare("
        INSERT INTO economy_users (user_id, balance) 
        VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance)
    ");
    $stmt->execute([$id, $amount]);

    // Transaktion speichern
    $stmt = $db->prepare("INSERT INTO economy_transactions (user_id, amount, type, message) VALUES (?, ?, 'add', ?)");
    $stmt->execute([$id, $amount, "Admin: Add Money"]);

    header("Location: economy.php?added=1");
    exit;
}
?>

<form method="POST" class="content">
    <h2 style="color:white;">💰 Betrag hinzufügen</h2>
    <input type="number" name="amount" placeholder="Betrag" required>
    <button class="btn">Hinzufügen</button>
</form>
