<?php
include "config.php";

$id = $_GET["id"];

$stmt = $db->prepare("DELETE FROM creators WHERE id=?");
$stmt->execute([$id]);

header("Location: creator.php");
exit;
