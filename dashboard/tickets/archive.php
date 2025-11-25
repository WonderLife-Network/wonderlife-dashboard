<?php
$REQUIRED_PERMISSION = "tickets";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>🗄 Ticket Archiv</h2>

<div id="archived">Lade…</div>

<script>
async function loadArchive() {
    const r = await fetch("/api/tickets/archive_list.php");
    const d = await r.json();

    let html = `
    <table class="dash-table">
        <tr><th>ID</th><th>User</th><th>Kategorie</th><th>Status</th><th>Aktion</th></tr>
    `;

    for (let t of d.tickets) {
        html += `
        <tr>
            <td>${t.id}</td>
            <td>${t.user_id}</td>
            <td>${t.category}</td>
            <td>${t.status}</td>
            <td>
                <a class="btn-small" href="/dashboard/tickets/view_archive.php?id=${t.id}">
                    Öffnen
                </a>
            </td>
        </tr>
        `;
    }

    html += "</table>";
    document.getElementById("archived").innerHTML = html;
}

loadArchive();
</script>

<?php include "../footer.php"; ?>
