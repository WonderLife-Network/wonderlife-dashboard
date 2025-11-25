<?php
require "../../config.php";
header("Content-Type: application/json");

if ($API_SCOPE != "user" && $API_SCOPE != "admin" && $API_SCOPE != "write") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$input = json_decode(file_get_contents("php://input"), true);

$panel_id    = $input["panel_id"] ?? null;
$category_id = $input["category_id"] ?? null;
$subject     = trim($input["subject"] ?? "");
$message     = trim($input["message"] ?? "");

if (!$panel_id || !$category_id || !$subject || !$message) {
    die(json_encode(["error"=>"MISSING_FIELDS"]));
}

// Kategorie laden (enthält server_id)
$cat_res = $db->prepare("SELECT * FROM ticket_categories WHERE id=?");
$cat_res->execute([$category_id]);
$category = $cat_res->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die(json_encode(["error"=>"INVALID_CATEGORY"]));
}

$server_id = $category["server_id"]; // kann NULL = global

// Panel prüfen
$panel_res = $db->prepare("SELECT * FROM ticket_panels WHERE id=?");
$panel_res->execute([$panel_id]);
$panel = $panel_res->fetch(PDO::FETCH_ASSOC);

if (!$panel) {
    die(json_encode(["error"=>"INVALID_PANEL"]));
}

// Ticket anlegen
$stmt = $db->prepare("
    INSERT INTO tickets (user_id, panel_id, category_id, server_id, subject, status)
    VALUES (?, ?, ?, ?, ?, 'open')
");
$stmt->execute([
    $AUTH_USER_ID,
    $panel_id,
    $category_id,
    $server_id,
    $subject
]);

$ticket_id = $db->lastInsertId();

// Startmessage speichern
$msg = $db->prepare("
    INSERT INTO ticket_messages (ticket_id, user_id, message)
    VALUES (?, ?, ?)
");
$msg->execute([
    $ticket_id,
    $AUTH_USER_ID,
    $message
]);

// Log speichern
$log = $db->prepare("
    INSERT INTO ticket_logs (ticket_id, action, user_id, details)
    VALUES (?, 'ticket_created', ?, ?)
");
$log->execute([
    $ticket_id,
    $AUTH_USER_ID,
    "Ticket erstellt"
]);

echo json_encode([
    "status"=>"OK",
    "ticket_id"=>$ticket_id
]);
