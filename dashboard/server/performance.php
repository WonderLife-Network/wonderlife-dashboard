<?php
$REQUIRED_PERMISSION = "server.view";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

// Monitor API
$MONITOR_API = "http://YOUR-MONITOR-IP:8090/status";
?>

<h2>📈 FiveM Performance – CPU & RAM Verlauf</h2>
<p>Echtzeit-Performance Daten deines WonderLife City Servers (aktualisiert jede Sekunde).</p>

<div class="chart-container">
    <h3>🧠 CPU Auslastung (%)</h3>
    <canvas id="cpuChart"></canvas>
</div>

<div class="chart-container">
    <h3>💾 RAM Auslastung (%)</h3>
    <canvas id="ramChart"></canvas>
</div>

<script src="/assets/js/chart.4.4.js"></script>

<script>
// Datenarrays
let cpuData = [];
let ramData = [];
let labels = [];

// Chart erstellen
const cpuChart = new Chart(document.getElementById("cpuChart"), {
    type: "line",
    data: {
        labels: labels,
        datasets: [{
            label: "CPU (%)",
            data: cpuData,
            borderColor: "#a44cff",
            backgroundColor: "rgba(164, 76, 255, 0.2)",
            tension: 0.25,
            borderWidth: 2,
            fill: true
        }]
    }
});

const ramChart = new Chart(document.getElementById("ramChart"), {
    type: "line",
    data: {
        labels: labels,
        datasets: [{
            label: "RAM (%)",
            data: ramData,
            borderColor: "#ff00d4",
            backgroundColor: "rgba(255, 0, 212, 0.2)",
            tension: 0.25,
            borderWidth: 2,
            fill: true
        }]
    }
});

// Daten laden
async function loadPerformance() {
    const api = "<?= $MONITOR_API ?>";

    try {
        const res = await fetch(api);
        const data = await res.json();

        const cpu = parseFloat(data.cpu);
        const ram = parseFloat(data.ram);

        // Daten in Arrays pushen
        const timestamp = new Date().toLocaleTimeString();
        labels.push(timestamp);
        cpuData.push(cpu);
        ramData.push(ram);

        // Länge auf max 60 Einträge begrenzen
        if (labels.length > 60) {
            labels.shift();
            cpuData.shift();
            ramData.shift();
        }

        cpuChart.update();
        ramChart.update();

    } catch (err) {
        console.error("Monitor API Fehler:", err);
    }
}

// Update jede Sekunde
setInterval(loadPerformance, 1000);
loadPerformance();
</script>

<style>
.chart-container {
    background: rgba(40,20,60,0.75);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid #a44cff55;
    margin-bottom: 30px;
    box-shadow: 0 0 20px #a44cff33;
}

h3 {
    margin-bottom: 10px;
    color: #d8b2ff;
    text-shadow: 0 0 6px #a44cff;
}
</style>

<?php include "../footer.php"; ?>
