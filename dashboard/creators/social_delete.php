<?php
$REQUIRED_PERMISSION = "creators.manage";
require "../auth_check.php";
require "../permission_check.php";

$id = $_GET["id"];
$creator = $_GET["creator"];

$db->prepare("DELETE FROM creator_socials WHERE id=?")->execute([$id]);

header("Location: /dashboard/creators/socials.php?id=".$creator);
exit;
