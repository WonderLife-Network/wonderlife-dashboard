<?php
$REQUIRED_PERMISSION = "tickets";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$ticket_id = intval($_GET["id"]);
?>

<h2>🗄 Archiviertes Ticket #<?= $ticket_id ?></h2>

<div id="logs">Lade…</div>

<button class="btn" onclick="reopen()">🔄 Ticket wieder öffnen</button>
<a class="btn" href="/api/tickets/export_pdf.php?id=<?= $ticket_id ?>" target="_blank">📄 PDF Export</a>

<script>
async function loadLogs() {
    const r = await fetch("/api/tickets/logs.php?id=<?= $ticket_id ?>");
    const d = await r.json();

    let html = `
    <table class="dash-table">
        <tr><th>ID</th><th>User</th><th>Aktion</th><th>Details</th><th>Zeit</th></tr>
    `;

    for (let l of d.logs) {
        html += `
        <tr>
            <td>${l.id}</td>
            <td>${l.user_id}</td>
            <td>${l.action}</td>
            <td>${l.details}</td>
            <td>${l.created_at}</td>
        </tr>
        `;
    }

    html += "</table>";
    document.getElementById("logs").innerHTML = html;
}

async function reopen() {
    await fetch("/api/tickets/reopen.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>,
            user_id: <?= $USER["id"] ?>
        })
    });

    alert("Ticket wurde wieder geöffnet!");
    location.href = "/dashboard/tickets/live.php";
}

loadLogs();
</script>

<?php include "../footer.php"; ?>
