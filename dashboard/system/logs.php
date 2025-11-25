<?php
$REQUIRED_PERMISSION = "admin";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>📜 System Log Viewer</h2>
<p>Überwache alle System-, API-, Auth- und Sicherheitsereignisse in Echtzeit.</p>

<div class="filter-row">
    <div>
        <label>Log Typ</label>
        <select id="log_type" onchange="loadLogs()">
            <option value="">Alle</option>
            <option value="info">Info</option>
            <option value="warning">Warning</option>
            <option value="error">Error</option>
            <option value="system">System</option>
            <option value="api">API</option>
            <option value="auth">Auth</option>
            <option value="security">Security</option>
        </select>
    </div>

    <div>
        <label>Limit</label>
        <select id="log_limit" onchange="loadLogs()">
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200" selected>200</option>
            <option value="500">500</option>
        </select>
    </div>
</div>

<div id="logs-container">Lade Logs…</div>

<script>
async function loadLogs() {
    const type = document.getElementById("log_type").value;
    const limit = document.getElementById("log_limit").value;

    let url = `/api/system/logs_list.php?limit=${limit}`;
    if (type) url += `&type=${type}`;

    const res = await fetch(url);
    const data = await res.json();

    if (!data.logs) {
        document.getElementById("logs-container").innerHTML =
            "<p style='color:red;'>Fehler beim Laden der Logs.</p>";
        return;
    }

    let html = `
    <table class="dash-table">
        <tr>
            <th>ID</th>
            <th>Typ</th>
            <th>Nachricht</th>
            <th>Kontext</th>
            <th>User</th>
            <th>IP</th>
            <th>Zeit</th>
        </tr>
    `;

    for (let log of data.logs) {
        html += `
        <tr>
            <td>${log.id}</td>
            <td>${log.type}</td>
            <td>${log.message}</td>
            <td>${log.context || "-"}</td>
            <td>${log.user_id || "-"}</td>
            <td>${log.ip || "-"}</td>
            <td>${log.created_at}</td>
        </tr>
        `;
    }

    html += "</table>";

    document.getElementById("logs-container").innerHTML = html;
}

// Auto-Reload alle 5 Sekunden
setInterval(loadLogs, 5000);
loadLogs();
</script>

<style>
.filter-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

select {
    width: 200px;
    padding: 8px;
    background: #1a0f2b;
    color: white;
    border: 1px solid #a44cff66;
    border-radius: 10px;
}

table td, table th {
    font-size: 14px;
}

tr:hover {
    background: rgba(255, 0, 212, 0.1);
    transition: 0.2s;
}
</style>

<?php include "../footer.php"; ?>
