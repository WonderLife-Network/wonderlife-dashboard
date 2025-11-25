<?php
require "../../config.php";
header("Content-Type: application/json");

// Ticket-ID prüfen
$ticket_id = $_GET["id"] ?? null;

if (!$ticket_id) {
    echo json_encode(["error" => "NO_TICKET"]);
    exit;
}

// Ticket laden
$ticketStmt = $db->prepare("
    SELECT t.*,
           p.title AS panel_title, p.icon AS panel_icon,
           c.name AS cat_name, c.icon AS cat_icon, c.color AS cat_color,
           ds.name AS server_name
    FROM tickets t
    LEFT JOIN ticket_panels p ON t.panel_id = p.id
    LEFT JOIN ticket_categories c ON t.category_id = c.id
    LEFT JOIN discord_servers ds ON t.server_id = ds.id
    WHERE t.id = ?
");
$ticketStmt->execute([$ticket_id]);
$ticket = $ticketStmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    echo json_encode(["error" => "TICKET_NOT_FOUND"]);
    exit;
}

// Nachrichten laden
$msgStmt = $db->prepare("
    SELECT tm.*, u.username
    FROM ticket_messages tm
    LEFT JOIN users u ON tm.user_id = u.id
    WHERE tm.ticket_id = ?
    ORDER BY tm.created_at ASC
");
$msgStmt->execute([$ticket_id]);
$messages = $msgStmt->fetchAll(PDO::FETCH_ASSOC);

// Logs laden
$logStmt = $db->prepare("
    SELECT *
    FROM ticket_logs
    WHERE ticket_id = ?
    ORDER BY created_at ASC
");
$logStmt->execute([$ticket_id]);
$logs = $logStmt->fetchAll(PDO::FETCH_ASSOC);

// Dateien laden
$fileStmt = $db->prepare("
    SELECT id, file_name, file_path, file_type, file_size, uploaded_at, user_id
    FROM ticket_files
    WHERE ticket_id = ?
    ORDER BY uploaded_at DESC
");
$fileStmt->execute([$ticket_id]);
$files = $fileStmt->fetchAll(PDO::FETCH_ASSOC);

// Ausgabe
echo json_encode([
    "status"   => "OK",
    "ticket"   => $ticket,
    "messages" => $messages,
    "logs"     => $logs,
    "files"    => $files
]);
