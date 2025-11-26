<?php
// LIVE UPDATE SYSTEM FÜR TICKETVIEW (WonderLife Network)
?>

<script>
// ==========================================================
// BLOCK 23.13 + BLOCK 23.15 – LIVE UPDATE SYSTEM
// ==========================================================

// Letzte bekannte Werte merken
let lastMessageCount = 0;
let lastFileCount    = 0;
let lastLogCount     = 0;

// WICHTIG: Server-ID merken (für Multi-Server Routing)
let lastServer = <?= (int)$ticket['server_id'] ?>;

// ==========================================================
// HAUPTFUNKTION FÜR LIVE-UPDATES
// ==========================================================
async function runLiveUpdates() {
    const res = await fetch("/api/tickets/get.php?id=<?= $ticket_id ?>");
    const data = await res.json();

    if (!data || data.status !== "OK") return;

    // -------------------------------------------------------
    // (0) Server-Wechsel erkennen → Dropdown automatisch aktualisieren
    // -------------------------------------------------------
    if (data.ticket && data.ticket.server_id !== undefined) {
        if (data.ticket.server_id != lastServer) {
            lastServer = data.ticket.server_id;

            const sel = document.getElementById("serverSelect");
            if (sel) {
                sel.value = lastServer; // Dropdown umschalten
            }
        }
    }

    // -------------------------------------------------------
    // (1) Nachrichten – nur neu laden, wenn sich etwas verändert hat
    // -------------------------------------------------------
    if (data.messages && data.messages.length !== lastMessageCount) {
        lastMessageCount = data.messages.length;
        if (typeof loadMessages === "function") {
            loadMessages();
        }
    }

    // -------------------------------------------------------
    // (2) Dateien – auto reload wenn neue Dateien hochgeladen wurden
    // -------------------------------------------------------
    if (data.files && data.files.length !== lastFileCount) {
        lastFileCount = data.files.length;
        if (typeof loadFiles === "function") {
            loadFiles();
        }
    }

    // -------------------------------------------------------
    // (3) Logs – nur aktualisieren, wenn neue Logs existieren
    // -------------------------------------------------------
    if (data.logs && data.logs.length !== lastLogCount) {
        lastLogCount = data.logs.length;
        if (typeof loadLogs === "function") {
            loadLogs();
        }
    }

    // -------------------------------------------------------
    // (4) Agents – immer live aktualisieren (Status, Typing, Online/Offline)
    // -------------------------------------------------------
    if (typeof loadAgents === "function") {
        loadAgents();
    }

    // Agenten-Badges aus Block 23.12 aktualisieren
    if (typeof updateAgentBadgeBar === "function") {
        updateAgentBadgeBar();
    }
}

// ==========================================================
// TIMER – Alle 2 Sekunden Live-Update
// ==========================================================
setInterval(runLiveUpdates, 2000);

// Sofort erster Lauf
runLiveUpdates();
</script>
