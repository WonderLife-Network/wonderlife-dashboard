<?php
include "config.php";

$id = $_GET["id"];

$stmt = $db->prepare("DELETE FROM team WHERE id=?");
$stmt->execute([$id]);

header("Location: team.php");
exit;
