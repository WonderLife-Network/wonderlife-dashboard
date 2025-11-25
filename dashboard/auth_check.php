<?php
include "../config.php";
include "../session_start.php";

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["session_token"])) {
    header("Location: /login.php");
    exit;
}

// Session gültig?
$stmt = $db->prepare("SELECT * FROM user_sessions WHERE session_token=? AND user_id=?");
$stmt->execute([$_SESSION["session_token"], $_SESSION["user_id"]]);
$sess = $stmt->fetch();

if (!$sess) {
    header("Location: /login.php");
    exit;
}

// User laden
$stmt = $db->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION["user_id"]]);
$AUTH_USER = $stmt->fetch();

// Rolle laden
$stmt = $db->prepare("SELECT * FROM roles WHERE id=?");
$stmt->execute([$AUTH_USER["role_id"]]);
$AUTH_ROLE = $stmt->fetch();
