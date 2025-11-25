<?php
require "../../config.php";
header("Content-Type: application/json");

// Ticket-ID prüfen
$ticket_id = $_GET["ticket_id"] ?? null;
if (!$ticket_id) {
    echo json_encode(["error" => "NO_TICKET"]);
    exit;
}

// Dateien aus DB holen
$stmt = $db->prepare("
    SELECT id, ticket_id, user_id, file_name, file_path, file_type, file_size, uploaded_at
    FROM ticket_files
    WHERE ticket_id = ?
    ORDER BY uploaded_at DESC
");
$stmt->execute([$ticket_id]);

$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Antwort
echo json_encode([
    "status" => "OK",
    "files" => $files
]);
