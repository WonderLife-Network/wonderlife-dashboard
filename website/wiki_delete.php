<?php
include "config.php";

$id = $_GET["id"];

$stmt = $db->prepare("DELETE FROM wiki WHERE id=?");
$stmt->execute([$id]);

header("Location: wiki.php");
exit;
