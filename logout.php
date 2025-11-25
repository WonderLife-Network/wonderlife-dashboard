<?php
include "config.php";
include "session_start.php";

if (isset($_SESSION["session_token"])) {

    $stmt = $db->prepare("DELETE FROM user_sessions WHERE session_token=?");
    $stmt->execute([$_SESSION["session_token"]]);
}

session_unset();
session_destroy();

header("Location: login.php");
exit;
