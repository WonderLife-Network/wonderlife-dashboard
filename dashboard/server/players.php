<?php
$REQUIRED_PERMISSION = "server.view";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

// URL zu deiner NodeJS Monitor API
$MONITOR_PLAYERS_API = "http://YOUR-MONITOR-IP:8090/players"; 
?>

<h2>👥 FiveM Spieler – Live Ansicht</h2>
<p>Zeigt alle aktuell verbundenen Spieler in Echtzeit.</p>

<div id="players-container">
    <p>Lädt Spieler…</p>
</div>

<script>
async function loadPlayers() {
    let api = "<?= $MONITOR_PLAYERS_API ?>";

    try {
        const res = await fetch(api);
        const data = await res.json();

        if (!data || !data.players) {
            document.getElementById("players-container").innerHTML = "<p>Keine Spieler online.</p>";
            return;
        }

        let html = `
        <table class="dash-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Ping</th>
                <th>License</th>
                <th>Discord</th>
                <th>Steam</th>
            </tr>
        `;

        for (let p of data.players) {
            html += `
            <tr>
                <td>${p.id}</td>
                <td>${p.name}</td>
                <td>${p.ping}</td>
                <td>${p.license}</td>
                <td>${p.discord}</td>
                <td>${p.steam}</td>
            </tr>`;
        }

        html += "</table>";

        document.getElementById("players-container").innerHTML = html;

    } catch (e) {
        document.getElementById("players-container").innerHTML = "<p style='color:red;'>Fehler: FiveM Monitor API nicht erreichbar.</p>";
    }
}

// alle 3 Sekunden aktualisieren
setInterval(loadPlayers, 3000);
loadPlayers();
</script>

<style>
#players-container {
    margin-top: 20px;
}

td {
    font-size: 14px;
}

th {
    background: rgba(164,76,255,0.2);
    text-shadow: 0 0 6px #a44cff;
}

tr:hover {
    background: rgba(255,0,200,0.1);
}
</style>

<?php include "../footer.php"; ?>
