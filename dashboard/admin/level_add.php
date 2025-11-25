<?php
include "protect_owner.php";
require "../config.php";

if (!isset($_GET["id"])) { die("Keine ID."); }
$id = intval($_GET["id"]);

if (isset($_POST["xp"])) {

    $xp = intval($_POST["xp"]);
    $reason = $_POST["reason"];

    // XP hinzufügen
    $stmt = $db->prepare("
        INSERT INTO levels (user_id, xp, level)
        VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE xp = xp + VALUES(xp)
    ");
    $stmt->execute([$id, $xp]);

    // XP Log speichern
    $stmt2 = $db->prepare("
        INSERT INTO levels_logs (user_id, xp_gained, reason)
        VALUES (?, ?, ?)
    ");
    $stmt2->execute([$id, $xp, $reason]);

    header("Location: levels.php?added=1");
    exit;
}
?>

<form method="POST" class="content">

    <h2 style="color:white;">⭐ XP hinzufügen</h2>

    <input type="number" name="xp" placeholder="XP Menge" required>

    <input type="text" name="reason" placeholder="Grund (optional)">

    <button class="btn">Hinzufügen</button>

</form>
