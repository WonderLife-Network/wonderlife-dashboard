<?php
$REQUIRED_PERMISSION = "user";
require "../auth_check.php";
include "../header.php";

// SERVER-LISTE LADEN
$servers = $db->query("
    SELECT id, name
    FROM discord_servers
    ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);

// PANELS LADEN
$panels = $db->query("
    SELECT id, title, icon
    FROM ticket_panels
    ORDER BY title ASC
")->fetchAll(PDO::FETCH_ASSOC);

// KATEGORIEN LADEN
$categories = $db->query("
    SELECT id, name, color, icon
    FROM ticket_categories
    ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);

// FILTER AUS URL
$server_id   = $_GET["server_id"] ?? "";
$panel_id    = $_GET["panel_id"] ?? "";
$category_id = $_GET["category_id"] ?? "";
$status      = $_GET["status"] ?? "";
$search      = $_GET["search"] ?? "";

// SQL BASISAUFBAU
$query = "
SELECT 
    t.id, t.subject, t.status, t.created_at,
    p.title AS panel_title, p.icon AS panel_icon,
    c.name AS category_name, c.color AS category_color, c.icon AS category_icon,
    ds.name AS server_name,
    u.username AS creator_name
FROM tickets t
LEFT JOIN ticket_panels p ON p.id=t.panel_id
LEFT JOIN ticket_categories c ON c.id=t.category_id
LEFT JOIN discord_servers ds ON ds.id=t.server_id
LEFT JOIN users u ON u.id=t.user_id
WHERE 1=1
";

$params = [];

// FILTER LOGIK
if ($server_id != "") {
    $query .= " AND t.server_id = ? ";
    $params[] = $server_id;
}
if ($panel_id != "") {
    $query .= " AND t.panel_id = ? ";
    $params[] = $panel_id;
}
if ($category_id != "") {
    $query .= " AND t.category_id = ? ";
    $params[] = $category_id;
}
if ($status != "") {
    $query .= " AND t.status = ? ";
    $params[] = $status;
}
if ($search != "") {
    $query .= " AND (t.subject LIKE ? OR u.username LIKE ?) ";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY t.id DESC ";

$stmt = $db->prepare($query);
$stmt->execute($params);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>🎫 Ticketsystem</h2>

<div class="ticket-filters neon-panel">
    <form method="GET">

        <select name="server_id" class="filter-select">
            <option value="">Alle Server</option>
            <?php foreach ($servers as $s): ?>
                <option value="<?= $s["id"] ?>" <?= $server_id == $s["id"] ? "selected" : "" ?>>
                    <?= $s["name"] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="panel_id" class="filter-select">
            <option value="">Alle Panels</option>
            <?php foreach ($panels as $p): ?>
                <option value="<?= $p["id"] ?>" <?= $panel_id == $p["id"] ? "selected" : "" ?>>
                    <?= $p["icon"] ?> <?= $p["title"] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="category_id" class="filter-select">
            <option value="">Alle Kategorien</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c["id"] ?>" <?= $category_id == $c["id"] ? "selected" : "" ?>>
                    <?= $c["icon"] ?> <?= $c["name"] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="status" class="filter-select">
            <option value="">Status</option>
            <option value="open"    <?= $status=="open"?"selected":"" ?>>🟢 Offen</option>
            <option value="pending" <?= $status=="pending"?"selected":"" ?>>🕒 Ausstehend</option>
            <option value="closed"  <?= $status=="closed"?"selected":"" ?>>🔒 Geschlossen</option>
        </select>

        <input type="text" name="search" placeholder="Suche…" value="<?= htmlspecialchars($search) ?>" class="filter-input">

        <button class="btn neon-btn">🔍 Filtern</button>
        <a href="list.php" class="btn neon-btn-secondary">❌ Reset</a>
    </form>
</div>

<style>
.neon-panel {
    background: rgba(30,15,45,0.7);
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 1px solid #a44cff55;
    box-shadow: 0 0 15px #a44cff40;
}

.filter-select, .filter-input {
    padding: 8px 12px;
    border-radius: 8px;
    background: #140a20;
    border: 1px solid #a44cff66;
    color: #fff;
    margin-right: 10px;
}

.neon-btn {
    background: #a44cff;
    color: #fff;
    padding: 8px 15px;
    border-radius: 8px;
    box-shadow: 0 0 12px #a44cffaa;
    cursor: pointer;
}
.neon-btn-secondary {
    background: #ff0099;
    color: #fff;
    padding: 8px 15px;
    border-radius: 8px;
    box-shadow: 0 0 12px #ff0099aa;
}
.ticket-card {
    background: rgba(255,255,255,0.04);
    padding: 15px;
    border-radius: 15px;
    margin-bottom: 15px;
    border-left: 4px solid #a44cff;
    transition: 0.2s;
}
.ticket-card:hover {
    transform: translateX(5px);
    background: rgba(255,255,255,0.07);
}
.ticket-title {
    font-size: 18px;
    font-weight: bold;
}
.ticket-meta {
    opacity: 0.7;
    font-size: 14px;
}
.status-open { color:#32ff7e; }
.status-pending { color:#ffe227; }
.status-closed { color:#ff4d4d; }
</style>

<h3>📋 Ticketliste</h3>

<?php if (count($tickets) == 0): ?>
    <p><i>Keine Tickets gefunden.</i></p>
<?php endif; ?>

<?php foreach ($tickets as $t): ?>
    <a href="ticket_view.php?id=<?= $t["id"] ?>" class="ticket-card">
        <div class="ticket-title">
            <?= $t["panel_icon"] ?> <?= $t["panel_title"] ?> — #<?= $t["id"] ?>
        </div>
        <div class="ticket-meta">
            <b><?= $t["subject"] ?></b><br>
            <?= $t["category_icon"] ?> <?= $t["category_name"] ?><br>
            Server: <?= $t["server_name"] ?><br>
            Von: <?= $t["creator_name"] ?><br>
            <span class="status-<?= $t["status"] ?>">
                Status: <?= strtoupper($t["status"]) ?>
            </span><br>
            Erstellt: <?= $t["created_at"] ?>
        </div>
    </a>
<?php endforeach; ?>

<?php include "../footer.php"; ?>
