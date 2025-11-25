<?php
require "../auth_check.php";

$id = $_GET["id"];

$stmt = $db->prepare("UPDATE notifications SET read_at=NOW() WHERE id=? AND user_id=?");
$stmt->execute([$id, $AUTH_USER["id"]]);

header("Location: /dashboard/notifications/list.php");
exit;
