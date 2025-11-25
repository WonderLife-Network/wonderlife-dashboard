<?php
require "../auth/session.php";
require "../config.php";

/* Nur Owner darf Admin-Bereich betreten */
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "owner") {
    header("Location: /dashboard/login.php?error=NoAccess");
    exit;
}
?>
