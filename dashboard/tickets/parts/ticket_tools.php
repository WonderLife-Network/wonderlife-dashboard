<div class="ticket-tools">

    <button class="btn" onclick="openAddAgent()">+ Agent hinzufügen</button>
    <button class="btn yellow" onclick="openTransfer()">⇄ Ticket verschieben</button>
    <button class="btn red" onclick="closeTicket()">🔒 Ticket schließen</button>
    <button class="btn green" onclick="reopenTicket()">🟢 Wieder öffnen</button>
    <button class="btn red" onclick="deleteTicket()">🗑 Löschen</button>
    <button class="btn" onclick="exportTicket()">📤 Exportieren</button>

</div>

<style>
.ticket-tools {
    margin-top: 15px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
</style>

<script>
async function closeTicket() {
    const reason = prompt("Grund für Schließung:");
    if (!reason) return;

    await fetch("/api/tickets/tools/close.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>,
            reason: reason
        })
    });

    location.reload();
}

async function reopenTicket() {
    await fetch("/api/tickets/tools/reopen.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>
        })
    });

    location.reload();
}

async function deleteTicket() {
    if (!confirm("Ticket wirklich löschen?")) return;

    await fetch("/api/tickets/tools/delete.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>
        })
    });

    window.location.href="/dashboard/tickets/";
}

function exportTicket() {
    window.open("/api/tickets/tools/export.php?ticket_id=<?= $ticket_id ?>&format=json");
}
</script>
