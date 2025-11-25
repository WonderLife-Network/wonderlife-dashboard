<?php
// LIVE UPDATE SYSTEM FÜR TICKETVIEW
?>

<script>
// ==========================================================
// BLOCK 23.13 – LIVE UPDATE LOOP
// ==========================================================

let lastMessageCount = 0;
let lastFileCount = 0;
let lastLogCount = 0;

// Hauptfunktion
async function runLiveUpdates() {
    const res = await fetch("/api/tickets/get.php?id=<?= $ticket_id ?>");
    const data = await res.json();

    if (!data || data.status !== "OK") return;

    // ------------------------------------------
    // 1) Nachrichten – nur neu laden wenn sich etwas geändert hat
    // ------------------------------------------
    if (data.messages.length !== lastMessageCount) {
        lastMessageCount = data.messages.length;
        loadMessages();
    }

    // ------------------------------------------
    // 2) Dateien – auto reload wenn sich etwas ändert
    // ------------------------------------------
    if (data.files.length !== lastFileCount) {
        lastFileCount = data.files.length;
        loadFiles();
    }

    // ------------------------------------------
    // 3) Logs – auto reload nur bei Änderungen
    // ------------------------------------------
    if (data.logs.length !== lastLogCount) {
        lastLogCount = data.logs.length;
        loadLogs();
    }

    // ------------------------------------------
    // 4) Agents – immer updaten
    // ------------------------------------------
    loadAgents();
    updateAgentBadgeBar();
}

// Alle 2 Sekunden live aktualisieren
setInterval(runLiveUpdates, 2000);

// Sofort starten
runLiveUpdates();
</script>
