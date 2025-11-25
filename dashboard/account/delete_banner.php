<?php
require "../auth_check.php";

$user = $AUTH_USER;

if ($user["banner"]) {
    unlink($_SERVER["DOCUMENT_ROOT"] . "/uploads/users/banner/" . $user["banner"]);
}

$db->prepare("UPDATE users SET banner=NULL WHERE id=?")->execute([$user["id"]]);

header("Location: /dashboard/account/profile.php");
exit;
