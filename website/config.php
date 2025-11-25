<?php
$DB_HOST = "rdbms.strato.de";
$DB_USER = "DEIN_USER";
$DB_PASS = "DEIN_PASS";
$DB_NAME = "wonderlife_network";

try {
    $db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("DATABASE ERROR");
}
