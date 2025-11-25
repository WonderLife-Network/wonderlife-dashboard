<?php
require "../../config.php";
header("Content-Type: application/json");

// Diese Datei wird per Cronjob ausgeführt
// Beispiel Cronjob:
// * * * * * curl -s https://team.wonderlife-network.net/api/agents/update_presence.php >/dev/null 2>&1

// Alle Agents abrufen
$stmt = $db->query("
    SELECT id, user_id, last_active, status, current_ticket
    FROM agents
");
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);

$updated = 0;

foreach ($agents as $agent) {

    // Schutz: Wenn last_active NULL ist → sofort offline
    if (!$agent["last_active"]) {
        $newStatus = "offline";

        // Ticket-Zuweisung entfernen
        $clear = $db->prepare("
            UPDATE agents
            SET current_ticket = NULL,
                typing = 0
            WHERE id = ?
        ");
        $clear->execute([$agent["id"]]);

        // Status speichern
        $upd = $db->prepare("
            UPDATE agents
            SET status = ?
            WHERE id = ?
        ");
        $upd->execute([$newStatus, $agent["id"]]);

        $updated++;
        continue;
    }

    $last = strtotime($agent["last_active"]);
    $now  = time();
    $diff = $now - $last;

    $newStatus = $agent["status"];

    // Online (aktive Nutzung 0–15s)
    if ($diff <= 15) {
        $newStatus = "online";
    }

    // Idle (15–60s)
    elseif ($diff > 15 && $diff <= 60) {
        $newStatus = "idle";
    }

    // Offline (mehr als 60s)
    else {
        $newStatus = "offline";

        // Offline Agents verlieren Ticket-Zuweisung
        $clear = $db->prepare("
            UPDATE agents
            SET current_ticket = NULL,
                typing = 0
            WHERE id = ?
        ");
        $clear->execute([$agent["id"]]);
    }

    // Wenn Status unverändert → nichts tun
    if ($newStatus === $agent["status"]) continue;

    // Status aktualisieren
    $upd = $db->prepare("
        UPDATE agents
        SET status = ?
        WHERE id = ?
    ");
    $upd->execute([$newStatus, $agent["id"]]);

    $updated++;
}

// Antwort für Debug / Log
echo json_encode([
    "status" => "OK",
    "agents_checked" => count($agents),
    "agents_updated" => $updated
]);
