<?php
$REQUIRED_PERMISSION = "server.view";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

// Monitor API Endpoint (info.json)
$FIVEM_INFO = "http://YOUR-MONITOR-IP:30120/info.json";
?>

<h2>ℹ️ FiveM Server Informationen</h2>
<p>Alle wichtigen Daten deines WonderLife City Servers – Live aus info.json.</p>

<div id="serverinfo-container">
    <p>Serverinformationen werden geladen…</p>
</div>

<script>
async function loadInfo() {
    let api = "<?= $FIVEM_INFO ?>";

    try {
        const res = await fetch(api);
        const data = await res.json();

        let tags = data.vars?.tags || "";
        tags = tags.length ? tags.split(",") : [];

        let vars_html = "";
        if (data.vars) {
            for (let k in data.vars) {
                vars_html += `
                <tr>
                    <td>${k}</td>
                    <td>${data.vars[k]}</td>
                </tr>`;
            }
        }

        let icon_html = "";
        if (data.icon) {
            icon_html = `<img src="data:image/png;base64,${data.icon}" class="server-icon">`;
        }

        let html = `
        <div class="info-card">
            <h3>🖥️ Allgemein</h3>
            ${icon_html}
            <p><strong>Server Name:</strong> ${data.hostname || "Unbekannt"}</p>
            <p><strong>Max Spieler:</strong> ${data.vars?.sv_maxClients || "-"}</p>
            <p><strong>Build Version:</strong> ${data.server || "?"}</p>
            <p><strong>Enhanced Host:</strong> ${data.enhancedHostSupport}</p>
            <p><strong>txAdmin:</strong> ${data.vars?.txAdminVersion || "?"}</p>
        </div>

        <div class="info-card">
            <h3>🏷️ Tags</h3>
            <p>${tags.map(t => `<span class='tag'>${t}</span>`).join(" ")}</p>
        </div>

        <div class="info-card">
            <h3>⚙️ Server Variablen (server.cfg)</h3>
            <table class="dash-table">
                <tr>
                    <th>Variable</th>
                    <th>Wert</th>
                </tr>
                ${vars_html}
            </table>
        </div>

        <div class="info-card">
            <h3>📦 Ressourcen</h3>
            <p><strong>Anzahl:</strong> ${data.resources?.length || 0}</p>
        </div>
        `;

        document.getElementById("serverinfo-container").innerHTML = html;

    } catch (err) {
        console.error("Fehler:", err);
        document.getElementById("serverinfo-container").innerHTML =
            "<p style='color:red;'>Fehler: FiveM info.json nicht erreichbar.</p>";
    }
}

loadInfo();
</script>

<style>
.info-card {
    background: rgba(40,20,60,0.75);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid #a44cff55;
    margin-bottom: 30px;
    box-shadow: 0 0 20px #a44cff33;
    color: white;
}

.server-icon {
    width: 128px;
    height: 128px;
    border-radius: 10px;
    margin-bottom: 10px;
    box-shadow: 0 0 10px #ff00d4;
}

.tag {
    display: inline-block;
    background: rgba(255,0,212,0.2);
    padding: 5px 10px;
    border-radius: 10px;
    border: 1px solid #ff00d4;
    margin-right: 5px;
    box-shadow: 0 0 10px #ff00d4;
}
</style>

<?php include "../footer.php"; ?>
