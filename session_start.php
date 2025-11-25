<?php
// Sichere Session-Einstellungen
ini_set('session.use_strict_mode', 1);
session_start();

// Timeout nach 6 Stunden
$session_lifetime = 21600;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_lifetime)) {
    session_unset();
    session_destroy();
}

$_SESSION['LAST_ACTIVITY'] = time();
