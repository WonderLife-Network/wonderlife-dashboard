<?php
include "protect_owner.php";
require "../config.php";

if (!isset($_GET["id"])) {
    die("Keine ID angegeben.");
}

$id = intval($_GET["id"]);

$stmt = $db->prepare("UPDATE tickets SET status='closed' WHERE id=?");
$stmt->execute([$id]);

$stmt = $db->prepare("INSERT INTO ticket_logs (ticket_id, log_type, info) VALUES (?, 'close', 'Ticket geschlossen')");
$stmt->execute([$id]);

header("Location: tickets.php?closed=1");
exit();
