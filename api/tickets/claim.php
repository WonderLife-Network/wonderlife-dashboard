<?php
require "../../config.php";
header("Content-Type: application/json");

// Ticket-ID prüfen
$ticket_id = $_POST["ticket_id"] ?? null;

if (!$ticket_id) {
    echo json_encode(["error" => "NO_TICKET"]);
    exit;
}

// Prüfen ob User eingeloggt
if (!$AUTH_USER_ID) {
    echo json_encode(["error" => "NO_AUTH"]);
    exit;
}

// Ticket claimen
$stmt = $db->prepare("
    UPDATE tickets 
    SET claimed_by = ?
    WHERE id = ?
");
$stmt->execute([$AUTH_USER_ID, $ticket_id]);

// Log speichern
$log = $db->prepare("
    INSERT INTO ticket_logs (ticket_id, action, user_id, details)
    VALUES (?, 'claimed', ?, 'Ticket wurde übernommen')
");
$log->execute([$ticket_id, $AUTH_USER_ID]);

echo json_encode(["status" => "OK"]);
