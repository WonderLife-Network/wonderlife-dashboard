<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

// API Endpoint – später ersetzbar durch deinen Bot
$discordApi = "https://team.wonderlife-network.net/api/discord_stats.php";

// Daten holen
$data = json_decode(file_get_contents($discordApi), true);

$guild = $data["guild"];
$roles = $data["roles"];
$channels = $data["channels"];
$members = $data["members"];
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/discord_panel.css">

<div class="content">

<h2 style="color:white;">🤖 Discord Server Panel</h2>

<div class="grid">

    <div class="card">
        <h3>👥 Mitglieder</h3>
        <p><?= $guild["member_count"] ?></p>
    </div>

    <div class="card">
        <h3>📢 Channels</h3>
        <p><?= count($channels) ?></p>
    </div>

    <div class="card">
        <h3>🎭 Rollen</h3>
        <p><?= count($roles) ?></p>
    </div>

    <div class="card">
        <h3>🌐 Server</h3>
        <p><?= htmlspecialchars($guild["name"]) ?></p>
    </div>

</div>

<h3 style="color:white;margin-top:40px;">🎭 Rollen</h3>

<table class="table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Farbe</th>
</tr>

<?php foreach ($roles as $r): ?>
<tr>
    <td><?= $r["id"] ?></td>
    <td><?= htmlspecialchars($r["name"]) ?></td>
    <td><div class="role-color" style="background:#<?= $r["color"] ?>"></div></td>
</tr>
<?php endforeach; ?>

</table>

<h3 style="color:white;margin-top:40px;">📢 Channels</h3>

<table class="table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Typ</th>
</tr>

<?php foreach ($channels as $c): ?>
<tr>
    <td><?= $c["id"] ?></td>
    <td><?= htmlspecialchars($c["name"]) ?></td>
    <td><?= $c["type"] ?></td>
</tr>
<?php endforeach; ?>

</table>

</div>

<?php include "footer.php"; ?>
