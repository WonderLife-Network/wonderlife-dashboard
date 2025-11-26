<?php
$REQUIRED_PERMISSION = "user";
require "../auth_check.php";
include "../header.php";

// Serverliste für Filter
$servers = $db->query("
    SELECT id, name
    FROM discord_servers
    ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);

$server_id = $_GET["server_id"] ?? "";

$query = "
SELECT a.*, u.username, ds.name AS server_name
FROM agents a
LEFT JOIN users u ON u.id=a.user_id
LEFT JOIN discord_servers ds ON ds.id=a.server_id
WHERE 1=1
";

$params = [];

if ($server_id != "") {
    $query .= " AND a.server_id = ? ";
    $params[] = $server_id;
}

$query .= " ORDER BY a.last_active DESC ";

$stmt = $db->prepare($query);
$stmt->execute($params);
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>👥 Agent Übersicht</h2>

<div class="neon-panel">
<form method="GET">
    <select name="server_id" class="filter-select">
        <option value="">Alle Server</option>
        <?php foreach ($servers as $s): ?>
        <option value="<?= $s["id"] ?>" <?= $server_id == $s["id"] ? "selected" : "" ?>>
            <?= $s["name"] ?>
        </option>
        <?php endforeach; ?>
    </select>
    <button class="btn neon-btn">🔍 Filtern</button>
</form>
</div>

<style>
.agent-card {
    background: rgba(255,255,255,0.04);
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 15px;
    border-left: 4px solid #3c8dff;
}
.agent-name {
    font-size: 18px;
    font-weight: bold;
}
.agent-meta {
    opacity: 0.75;
    font-size: 14px;
}
.status-online { color:#32ff7e; }
.status-idle   { color:#ffe227; }
.status-offline { color:#ff4d4d; }
.typing { color:#a44cff; font-weight:bold; }
</style>

<h3>📡 Active Agents</h3>

<?php if (count($agents) == 0): ?>
    <p><i>Keine Agents gefunden.</i></p>
<?php endif; ?>

<?php foreach ($agents as $a): 
    $statusClass = "status-offline";
    if ($a["status"] == "online") $statusClass = "status-online";
    if ($a["status"] == "idle")   $statusClass = "status-idle";
?>
<div class="agent-card">
    <div class="agent-name"><?= $a["username"] ?></div>
    <div class="agent-meta">
        Server: <?= $a["server_name"] ?: "Unbekannt" ?><br>
        Status: <span class="<?= $statusClass ?>"><?= $a["status"] ?></span><br>
        <?= $a["typing"] ? "<span class='typing'>Tippt…</span><br>" : "" ?>
        Letzte Aktivität: <?= $a["last_active"] ?><br>
        Aktuelles Ticket: <?= $a["current_ticket"] ?: "<i>Keins</i>" ?>
    </div>
</div>
<?php endforeach; ?>

<script>
// Auto Refresh Agents
setInterval(() => location.reload(), 10000);
</script>

<?php include "../footer.php"; ?>
