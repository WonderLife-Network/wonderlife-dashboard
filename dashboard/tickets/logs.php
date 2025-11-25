<?php
$REQUIRED_PERMISSION = "tickets";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$ticket_id = intval($_GET["id"]);
?>

<h2>📜 Ticket Log – #<?= $ticket_id ?></h2>
<p>Alle Aktionen & Systemmeldungen zum Ticket.</p>

<div id="logs">Lade…</div>

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
        </tr>`;
    }

    html += "</table>";
    document.getElementById("logs").innerHTML = html;
}

loadLogs();
</script>

<?php include "../footer.php"; ?>
