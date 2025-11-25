<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

$fivemApi = "https://team.wonderlife-network.net/api/fivem_stats.php";

$data = json_decode(file_get_contents($fivemApi), true);

$info = $data["info"];
$players = $data["players"];
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/fivem_panel.css">

<div class="content">

<h2 style="color:white;">🚔 FiveM Server Panel</h2>

<div class="grid">

    <div class="card">
        <h3>🔵 Spieler Online</h3>
        <p><?= $info["players"] ?> / <?= $info["max_players"] ?></p>
    </div>

    <div class="card">
        <h3>🟢 Status</h3>
        <p><?= $info["status"] ?></p>
    </div>

    <div class="card">
        <h3>⏳ Uptime</h3>
        <p><?= $info["uptime"] ?></p>
    </div>

    <div class="card">
        <h3>📡 IP</h3>
        <p><?= $info["ip"] ?></p>
    </div>

</div>

<h3 style="color:white;margin-top:40px;">👥 Spieler Liste</h3>

<table class="table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Ping</th>
</tr>

<?php foreach ($players as $p): ?>
<tr>
    <td><?= $p["id"] ?></td>
    <td><?= htmlspecialchars($p["name"]) ?></td>
    <td><?= $p["ping"] ?></td>
</tr>
<?php endforeach; ?>

</table>

</div>

<?php include "footer.php"; ?>
