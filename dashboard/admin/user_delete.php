<?php
include "protect_owner.php";
require "../config.php";

if (!isset($_GET['id'])) {
    die("Keine ID angegeben.");
}

$id = intval($_GET['id']);

$stmt = $db->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

header("Location: users.php?deleted=1");
exit();
