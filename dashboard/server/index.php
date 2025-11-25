<?php
$REQUIRED_PERMISSION = "server.view";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$MONITOR_API = "http://YOUR-MONITOR-IP:8090/status"; // später ersetzen
?>

<h2>🖥️ WonderLife – FiveM Live Dashboard</h2>
<p>Echtzeit-Statistiken deines FiveM Servers.</p>

<div id="server-status" class="server-grid">
    <div class="status-card"><h3>Spieler</h3><div id="players">–</div></div>
    <div class="status-card"><h3>CPU</h3><div id="cpu">–</div></div>
    <div class="status-card"><h3>RAM</h3><div id="ram">–</div></div>
    <div class="status-card"><h3>Uptime</h3><div id="uptime">–</div></div>
    <div class="status-card"><h3>Ressourcen</h3><div id="resources">–</div></div>
</div>

<script>
async function loadStatus(){
    let api = "<?= $MONITOR_API ?>";
    try {
        const res = await fetch(api);
        const data = await res.json();
        
        document.getElementById("players").innerText = data.players + " / " + data.maxPlayers;
        document.getElementById("cpu").innerText = data.cpu + "%";
        document.getElementById("ram").innerText = data.ram + "%";
        document.getElementById("uptime").innerText = data.uptime;
        document.getElementById("resources").innerText = data.resources + " aktiv";
    } catch(e){
        document.getElementById("players").innerText = "Offline";
        document.getElementById("cpu").innerText = "Offline";
        document.getElementById("ram").innerText = "Offline";
        document.getElementById("uptime").innerText = "Offline";
        document.getElementById("resources").innerText = "Offline";
    }
}

// alle 3 Sekunden aktualisieren
setInterval(loadStatus, 3000);
loadStatus();
</script>

<style>
.server-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap: 20px;
    margin-top: 20px;
}

.status-card {
    background: rgba(40,20,60,0.75);
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    border: 1px solid #a44cff66;
    color: white;
    box-shadow: 0 0 20px #a44cff33;
}

.status-card h3 {
    margin: 0 0 10px 0;
}

.status-card div {
    font-size: 24px;
    font-weight: bold;
}
</style>

<?php include "../footer.php"; ?>
