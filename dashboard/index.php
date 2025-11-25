<?php
require "auth_check.php";
include "header.php";

// User Settings laden
$stmt = $db->prepare("SELECT * FROM user_settings WHERE user_id=?");
$stmt->execute([$AUTH_USER["id"]]);
$SET = $stmt->fetch();

// Widgets je nach Nutzer anzeigen
function widget_enabled($key, $SET) {
    return isset($SET["widget_" . $key]) && $SET["widget_" . $key] == 1;
}

// --- SQL DATA LOADING ---

// Tickets
$openTickets = $db->query("SELECT COUNT(*) FROM tickets WHERE status='open'")->fetchColumn();
$myTickets   = $db->prepare("SELECT COUNT(*) FROM tickets WHERE assigned_to=? AND status='open'");
$myTickets->execute([$AUTH_USER["id"]]);
$myTickets = $myTickets->fetchColumn();

// News
$latestNews = $db->query("SELECT * FROM news ORDER BY id DESC LIMIT 3")->fetchAll();

// Creator
$creators = $db->query("SELECT * FROM creators ORDER BY id DESC LIMIT 4")->fetchAll();

// System Logs
$logs = $db->query("SELECT * FROM system_logs ORDER BY id DESC LIMIT 5")->fetchAll();

// Discord Events (Bot API später)
$discordEvents = []; // Platzhalter — Bot liefert später Daten

?>

<h2>Willkommen zurück, <?= htmlspecialchars($AUTH_USER["name"]) ?> 👋</h2>
<p>Hier ist dein WonderLife Dashboard Überblick.</p>

<div class="widget-grid">

    <!-- Ticket Widget -->
    <?php if (widget_enabled("tickets", $SET)): ?>
    <div class="widget-card glow-card">
        <h3>🎫 Tickets</h3>
        <div class="widget-row">
            <div class="widget-num"><?= $openTickets ?></div>
            <div class="widget-label">Offene Tickets</div>
        </div>
        <div class="widget-row">
            <div class="widget-num"><?= $myTickets ?></div>
            <div class="widget-label">Dir zugewiesen</div>
        </div>
        <a href="/dashboard/tickets/list.php" class="widget-btn">Tickets ansehen</a>
    </div>
    <?php endif; ?>

    <!-- News Widget -->
    <?php if (widget_enabled("news", $SET)): ?>
    <div class="widget-card glow-card">
        <h3>📰 News</h3>
        <?php foreach ($latestNews as $n): ?>
            <div class="widget-item">
                <b><?= htmlspecialchars($n["title"]) ?></b><br>
                <small><?= date("d.m.Y", strtotime($n["created_at"])) ?></small>
            </div>
        <?php endforeach; ?>
        <a href="/dashboard/news/list.php" class="widget-btn">Alle News</a>
    </div>
    <?php endif; ?>

    <!-- Stats Widget -->
    <?php if (widget_enabled("stats", $SET)): ?>
    <div class="widget-card glow-card">
        <h3>📊 Stats</h3>
        <div class="widget-row">
            <div class="widget-num"><?= $db->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?></div>
            <div class="widget-label">Benutzer</div>
        </div>
        <div class="widget-row">
            <div class="widget-num"><?= $db->query("SELECT COUNT(*) FROM creators")->fetchColumn(); ?></div>
            <div class="widget-label">Creator</div>
        </div>
        <a href="/dashboard/events/list.php" class="widget-btn">System Logs</a>
    </div>
    <?php endif; ?>

    <!-- Creator Widget -->
    <?php if (widget_enabled("creator", $SET)): ?>
    <div class="widget-card glow-card">
        <h3>🎥 Creator</h3>
        <div class="creator-grid">
            <?php foreach ($creators as $c): ?>
            <div class="creator-box">
                <img src="/uploads/creators/avatar/<?= $c["avatar"] ?>" class="creator-avatar">
                <div><?= htmlspecialchars($c["name"]) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <a href="/dashboard/creators/list.php" class="widget-btn">Alle Creator</a>
    </div>
    <?php endif; ?>

    <!-- Discord Events -->
    <?php if (widget_enabled("discord", $SET)): ?>
    <div class="widget-card glow-card">
        <h3>💬 Discord Events</h3>
        <?php if (count($discordEvents) == 0): ?>
            <p>Keine Live Events (Bot verbinden)</p>
        <?php else: ?>
            <?php foreach ($discordEvents as $e): ?>
                <div class="widget-item">
                    <?= htmlspecialchars($e["text"]) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<?php include "footer.php"; ?>
