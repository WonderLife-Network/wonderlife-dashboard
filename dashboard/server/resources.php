<?php
$REQUIRED_PERMISSION = "server.view";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

// Monitor API Endpoint
$MONITOR_RES_API = "http://YOUR-MONITOR-IP:8090/resources"; 
?>

<h2>📦 FiveM Ressourcen – Live Übersicht</h2>
<p>Alle geladenen Ressourcen im WonderLife City Server.</p>

<div id="resources-container">
    <p>Lade Ressourcen…</p>
</div>

<script>
async function loadResources() {
    let api = "<?= $MONITOR_RES_API ?>";

    try {
        const res = await fetch(api);
        const data = await res.json();

        if (!data || !data.resources) {
            document.getElementById("resources-container").innerHTML = "<p>Keine Daten verfügbar.</p>";
            return;
        }

        let html = `
        <table class="dash-table">
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Aktionen</th>
            </tr>
        `;

        for (let r of data.resources) {
            html += `
            <tr>
                <td>${r.name}</td>
                <td>${r.status == "started" ? "🟢 Läuft" : "🔴 Gestoppt"}</td>
                <td>
                    <button class="btn-small" onclick="resourceAction('${r.name}','start')">Start</button>
                    <button class="btn-small delete" onclick="resourceAction('${r.name}','stop')">Stop</button>
                </td>
            </tr>`;
        }

        html += "</table>";

        document.getElementById("resources-container").innerHTML = html;

    } catch (err) {
        document.getElementById("resources-container").innerHTML = "<p style='color:red;'>Monitor API offline.</p>";
    }
}

// Resource starten / stoppen
async function resourceAction(name, act) {
    let url = `<?= $MONITOR_RES_API ?>?resource=${name}&action=${act}`;

    const res = await fetch(url);
    const data = await res.json();

    alert(data.message || "Aktion durchgeführt.");
    loadResources();
}

// Live-Update alle 3 Sekunden
setInterval(loadResources, 3000);
loadResources();
</script>

<style>
.delete {
    background: #ff0044;
    border-color: #ff44aa;
}

#resources-container {
    margin-top: 20px;
}

tr:hover {
    background: rgba(255,0,200,0.1);
}
</style>

<?php include "../footer.php"; ?>
