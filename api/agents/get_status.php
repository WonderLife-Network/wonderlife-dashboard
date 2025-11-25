<?php
require "../../config.php";
header("Content-Type: application/json");

// Ticket-ID prüfen
$ticket_id = $_GET["ticket_id"] ?? null;

if (!$ticket_id) {
    echo json_encode(["error" => "NO_TICKET"]);
    exit;
}

// Agents abrufen, die dieses Ticket aktiv haben
$stmt = $db->prepare("
    SELECT 
        a.id,
        a.user_id,
        a.status,
        a.typing,
        a.last_active,
        a.current_ticket,
        u.username
    FROM agents a
    LEFT JOIN users u ON u.id = a.user_id
    WHERE a.current_ticket = ?
    ORDER BY a.last_active DESC
");
$stmt->execute([$ticket_id]);

$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$agents || count($agents) === 0) {
    echo json_encode([
        "status" => "OK",
        "agents" => []
    ]);
    exit;
}

// Status automatisch berechnen
foreach ($agents as &$agent) {

    // Wenn kein last_active eingetragen ist
    if (!$agent["last_active"]) {
        $agent["status"] = "offline";
        continue;
    }

    $last = strtotime($agent["last_active"]);
    $now  = time();
    $diff = $now - $last;

    if ($diff <= 15) {
        $agent["status"] = "online";
    } elseif ($diff <= 60) {
        $agent["status"] = "idle";
    } else {
        $agent["status"] = "offline";
    }

    // Wenn Agent offline → typing ZWINGEND auf 0
    if ($agent["status"] === "offline") {
        $agent["typing"] = 0;
    }
}

echo json_encode([
    "status" => "OK",
    "agents" => $agents
]);
