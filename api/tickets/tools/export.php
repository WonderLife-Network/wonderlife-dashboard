<?php
require "../../../config.php";
header("Content-Type: application/json");

$ticket_id = $_GET["ticket_id"] ?? null;
$format    = $_GET["format"] ?? "json";

if (!$ticket_id) die(json_encode(["error"=>"NO_TICKET"]));

$ticket = $db->prepare("SELECT * FROM tickets WHERE id=?");
$ticket->execute([$ticket_id]);
$ticket_data = $ticket->fetch(PDO::FETCH_ASSOC);

$messages = $db->prepare("SELECT * FROM ticket_messages WHERE ticket_id=?");
$messages->execute([$ticket_id]);
$msg_data = $messages->fetchAll(PDO::FETCH_ASSOC);

$files = $db->prepare("SELECT * FROM ticket_files WHERE ticket_id=?");
$files->execute([$ticket_id]);
$file_data = $files->fetchAll(PDO::FETCH_ASSOC);

$out = [
    "ticket" => $ticket_data,
    "messages" => $msg_data,
    "files" => $file_data
];

if ($format === "txt") {
    header("Content-Type: text/plain");
    print_r($out);
    exit;
}

echo json_encode($out, JSON_PRETTY_PRINT);
