<?php
$REQUIRED_PERMISSION = "tickets";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>🎫 Ticket Live Monitor</h2>
<p>Alle offenen Tickets werden in Echtzeit aktualisiert.</p>

<div id="tickets-container">Lade Tickets…</div>

<script>
async function loadTickets() {
    const res = await fetch("/api/tickets/live_list.php");
    const data = await res.json();

    if (!data.tickets) return;

    let html = `
    <table class="dash-table">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Kategorie</th>
            <th>Status</th>
            <th>Agent</th>
            <th>Letzte Nachricht</th>
            <th>Aktion</th>
        </tr>
    `;

    for (let t of data.tickets) {

        let agent = t.claimed_by ? 
            `<span class="tag-green">${t.claimed_by}</span>` :
            `<span class="tag-red">Niemand</span>`;

        let agentStatus = "";
        if (t.agent_status === "typing") agentStatus = "💬 Tippt…";
        else if (t.agent_status === "busy") agentStatus = "⛔ beschäftigt";
        else if (t.agent_status === "online") agentStatus = "🟢 online";
        else agentStatus = "⚫ offline";

        html += `
        <tr>
            <td>${t.id}</td>
            <td>${t.user_id}</td>
            <td>${t.category}</td>
            <td>${t.status}</td>
            <td>${agent} <small>${agentStatus}</small></td>
            <td>${t.last_msg || "-"}</td>
            <td>
                <a class="btn-small" href="/dashboard/tickets/view_live.php?id=${t.id}">
                    Öffnen
                </a>
            </td>
        </tr>
        `;
    }

    html += "</table>";

    document.getElementById("tickets-container").innerHTML = html;
}

setInterval(loadTickets, 2000);
loadTickets();
</script>

<style>
.tag-green { color: #0f0; font-weight: bold; }
.tag-red { color: #ff005d; font-weight: bold; }
</style>

<?php include "../footer.php"; ?>
