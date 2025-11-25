<?php
require "../auth_check.php";

$user = $AUTH_USER;

if ($user["avatar"]) {
    unlink($_SERVER["DOCUMENT_ROOT"] . "/uploads/users/avatar/" . $user["avatar"]);
}

$db->prepare("UPDATE users SET avatar=NULL WHERE id=?")->execute([$user["id"]]);

header("Location: /dashboard/account/profile.php");
exit;
