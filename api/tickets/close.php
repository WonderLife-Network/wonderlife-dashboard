<?php
require "../../config.php";
header("Content-Type: application/json");

if ($API_SCOPE != "admin" && $API_SCOPE != "tickets" && $API_SCOPE != "write") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

$ticket_id = $_POST["ticket_id"] ?? null;
$user_id = $_POST["user_id"] ?? null;
$template_id = $_POST["template_id"] ?? 1;

if (!$ticket_id || !$user_id) {
    die(json_encode(["error"=>"MISSING_FIELDS"]));
}

/* Ticket schließen */
$stmt = $db->prepare("UPDATE tickets SET status='closed' WHERE id=?");
$stmt->execute([$ticket_id]);

/* Template laden */
$temp = $db->prepare("SELECT content FROM ticket_close_templates WHERE id=? LIMIT 1");
$temp->execute([$template_id]);
$template = $temp->fetchColumn();

/* Log */
$log = $db->prepare("
INSERT INTO ticket_logs (ticket_id, user_id, action, details)
VALUES (?, ?, 'closed', ?)
");
$log->execute([$ticket_id, $user_id, $template]);

/* Live Ticket deaktivieren */
$db->prepare("UPDATE ticket_live SET is_active=0 WHERE ticket_id=?")->execute([$ticket_id]);

/* Antwort für Dashboard */
echo json_encode([
    "status"=>"OK",
    "message"=>"Ticket wurde geschlossen",
    "dm_template"=>$template
]);
