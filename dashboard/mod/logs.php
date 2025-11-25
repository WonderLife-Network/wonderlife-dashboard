<?php
$REQUIRED_PERMISSION = "moderation";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>📜 Moderationslog</h2>

<div id="logs">Lade…</div>

<script>
async function load() {
    const r = await fetch("/api/mod/logs.php");
    const d = await r.json();

    let html = "<table class='dash-table'><tr><th>ID</th><th>Mod</th><th>Ziel</th><th>Aktion</th><th>Details</th><th>Zeit</th></tr>";

    for (let l of d.logs) {
        html += `
        <tr>
            <td>${l.id}</td>
            <td>${l.mod_id}</td>
            <td>${l.target_id}</td>
            <td>${l.action}</td>
            <td>${l.details}</td>
            <td>${l.created_at}</td>
        </tr>`;
    }

    html += "</table>";
    document.getElementById("logs").innerHTML = html;
}

load();
</script>

<?php include "../footer.php"; ?>
