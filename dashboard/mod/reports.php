<?php
$REQUIRED_PERMISSION = "moderation";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>🚨 Reports</h2>
<div id="list">Lade…</div>

<script>
async function load() {
    const r = await fetch("/api/mod/report_list.php");
    const d = await r.json();

    let html = `
    <table class="dash-table">
    <tr><th>ID</th><th>Reporter</th><th>Ziel</th><th>Nachricht</th><th>Status</th><th>Zeit</th><th>Aktion</th></tr>
    `;

    for (let rp of d.reports) {
        html += `
        <tr>
            <td>${rp.id}</td>
            <td>${rp.reporter_id}</td>
            <td>${rp.target_id}</td>
            <td>${rp.message}</td>
            <td>${rp.status}</td>
            <td>${rp.created_at}</td>
            <td>
                <button onclick="resolve(${rp.id})">Schließen</button>
            </td>
        </tr>
        `;
    }

    html += "</table>";
    document.getElementById("list").innerHTML = html;
}

async function resolve(id) {
    await fetch("/api/mod/report_resolve.php", {
        method: "POST",
        body: new URLSearchParams({
            id: id,
            mod_id: <?= $USER["id"] ?>
        })
    });
    load();
}

load();
</script>

<?php include "../footer.php"; ?>
