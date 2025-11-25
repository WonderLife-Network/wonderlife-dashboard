<?php
require "../auth_check.php";
header("Content-Type: application/json");

// Datenarrays definieren
$labels = [];
$users = [];
$tickets = [];
$creators = [];
$api = [];

// Letzte 7 Tage Daten sammeln
for ($i = 6; $i >= 0; $i--) {

    $day = date("Y-m-d", strtotime("-$i days"));
    $labels[] = date("d.m", strtotime($day));

    // Benutzer am Tag
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE DATE(created_at)=?");
    $stmt->execute([$day]);
    $users[] = (int)$stmt->fetchColumn();

    // Tickets am Tag
    $stmt = $db->prepare("SELECT COUNT(*) FROM tickets WHERE DATE(created_at)=?");
    $stmt->execute([$day]);
    $tickets[] = (int)$stmt->fetchColumn();

    // Creator am Tag
    $stmt = $db->prepare("SELECT COUNT(*) FROM creators WHERE DATE(created_at)=?");
    $stmt->execute([$day]);
    $creators[] = (int)$stmt->fetchColumn();

    // API Calls am Tag
    $stmt = $db->prepare("SELECT COUNT(*) FROM api_logs WHERE DATE(created_at)=?");
    $stmt->execute([$day]);
    $api[] = (int)$stmt->fetchColumn();
}

// JSON zurückgeben
echo json_encode([
    "labels"   => $labels,
    "users"    => $users,
    "tickets"  => $tickets,
    "creators" => $creators,
    "api"      => $api
]);
