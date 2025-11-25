<?php
$REQUIRED_PERMISSION = "stats.view";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

// User Settings laden
$stmt = $db->prepare("SELECT * FROM user_settings WHERE user_id=?");
$stmt->execute([$AUTH_USER["id"]]);
$SETTINGS = $stmt->fetch();

// ----------------------
// SQL — STATISTIK DATEN
// ----------------------

// Benutzer heute
$users_today = $db->prepare("SELECT COUNT(*) FROM users WHERE DATE(created_at)=CURDATE()");
$users_today->execute();
$users_today = $users_today->fetchColumn();

// Tickets heute
$tickets_today = $db->prepare("SELECT COUNT(*) FROM tickets WHERE DATE(created_at)=CURDATE()");
$tickets_today->execute();
$tickets_today = $tickets_today->fetchColumn();

// API Calls heute
$api_today = $db->prepare("SELECT COUNT(*) FROM api_logs WHERE DATE(created_at)=CURDATE()");
$api_today->execute();
$api_today = $api_today->fetchColumn();

// Creator heute
$creator_today = $db->prepare("SELECT COUNT(*) FROM creators WHERE DATE(created_at)=CURDATE()");
$creator_today->execute();
$creator_today = $creator_today->fetchColumn();

// Log-Einträge
$logs = $db->query("SELECT * FROM system_logs ORDER BY id DESC LIMIT 10")->fetchAll();

?>

<h2>📊 WonderLife Statistik-Zentrale</h2>
<p>Live-Auswertung aller wichtigen Daten des Netzwerks.</p>

<div class="stats-container">

    <div class="stats-card">
        <h3>Benutzer heute</h3>
        <div class="stat-number"><?= $users_today ?></div>
    </div>

    <div class="stats-card">
        <h3>Tickets heute</h3>
        <div class="stat-number"><?= $tickets_today ?></div>
    </div>

    <div class="stats-card">
        <h3>API-Aufrufe heute</h3>
        <div class="stat-number"><?= $api_today ?></div>
    </div>

    <div class="stats-card">
        <h3>Neue Creator</h3>
        <div class="stat-number"><?= $creator_today ?></div>
    </div>

</div>

<br><br>

<h2>📈 Verlauf (7 Tage)</h2>

<div class="stats-container">

    <div class="stats-card">
        <h3>Benutzer</h3>
        <canvas id="usersChart"></canvas>
    </div>

    <div class="stats-card">
        <h3>Tickets</h3>
        <canvas id="ticketsChart"></canvas>
    </div>

    <div class="stats-card">
        <h3>API Calls</h3>
        <canvas id="apiChart"></canvas>
    </div>

    <div class="stats-card">
        <h3>Creator</h3>
        <canvas id="creatorChart"></canvas>
    </div>

</div>

<br><br>

<h2>📜 Letzte System-Events</h2>

<div class="log-table">
    <table class="dash-table">
        <tr>
            <th>ID</th>
            <th>Event</th>
            <th>Daten</th>
            <th>Von</th>
            <th>Zeit</th>
        </tr>
        <?php foreach ($logs as $l): ?>
        <tr>
            <td><?= $l["id"] ?></td>
            <td><?= htmlspecialchars($l["event_type"]) ?></td>
            <td><?= htmlspecialchars($l["event_data"]) ?></td>
            <td><?= $l["created_by"] ?></td>
            <td><?= $l["created_at"] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script src="/assets/js/chart.4.4.js"></script>
<script>
async function loadStats() {
    const res = await fetch("api.php");
    const data = await res.json();

    new Chart(document.getElementById("usersChart"), {
        type: "line",
        data: {
            labels: data.labels,
            datasets: [{
                label: "Benutzer",
                data: data.users,
                borderColor: "#a44cff",
                backgroundColor: "rgba(164,76,255,0.2)",
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        }
    });

    new Chart(document.getElementById("ticketsChart"), {
        type: "line",
        data: {
            labels: data.labels,
            datasets: [{
                label: "Tickets",
                data: data.tickets,
                borderColor: "#ff00d4",
                backgroundColor: "rgba(255,0,212,0.2)",
                fill: true,
                borderWidth: 2,
                tension: 0.3
            }]
        }
    });

    new Chart(document.getElementById("apiChart"), {
        type: "bar",
        data: {
            labels: data.labels,
            datasets: [{
                label: "API Calls",
                data: data.api,
                backgroundColor: "rgba(96,0,255,0.5)",
                borderColor: "#6000ff",
                borderWidth: 1
            }]
        }
    });

    new Chart(document.getElementById("creatorChart"), {
        type: "line",
        data: {
            labels: data.labels,
            datasets: [{
                label: "Creator",
                data: data.creators,
                borderColor: "#00fff2",
                backgroundColor: "rgba(0,255,242,0.2)",
                fill: true,
                borderWidth: 2,
                tension: 0.3
            }]
        }
    });
}

loadStats();
</script>

<?php include "../footer.php"; ?>
