<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

// Lade User + XP + Level
$data = $db->query("
    SELECT u.id, u.username, 
    COALESCE(l.xp,0) AS xp,
    COALESCE(l.level,1) AS level
    FROM users u
    LEFT JOIN levels l ON u.id = l.user_id
    ORDER BY l.level DESC, l.xp DESC
")->fetchAll();

// Letzte XP-Logs
$logs = $db->query("
    SELECT l.*, u.username
    FROM levels_logs l
    LEFT JOIN users u ON l.user_id = u.id
    ORDER BY l.id DESC
    LIMIT 20
")->fetchAll();
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/levels.css">

<div class="content">

<h2 style="color:white;">⭐ Levelsystem</h2>

<h3 style="color:#ff00d4;">Benutzer-Level</h3>

<table class="table">
<tr>
    <th>ID</th>
    <th>Benutzer</th>
    <th>Level</th>
    <th>XP</th>
    <th>Aktionen</th>
</tr>

<?php foreach ($data as $u): ?>
<tr>
    <td><?= $u["id"] ?></td>
    <td><?= htmlspecialchars($u["username"]) ?></td>
    <td><b><?= $u["level"] ?></b></td>
    <td><?= $u["xp"] ?></td>
    <td>
        <a class="btn" href="level_add.php?id=<?= $u['id'] ?>">XP +</a>
        <a class="btn-danger" href="level_reset.php?id=<?= $u['id'] ?>">Reset</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<h3 style="color:#ff00d4; margin-top:40px;">📝 XP / Level Logs</h3>

<table class="table">
<tr>
    <th>ID</th>
    <th>Benutzer</th>
    <th>XP</th>
    <th>Grund</th>
    <th>Zeit</th>
</tr>

<?php foreach ($logs as $l): ?>
<tr>
    <td><?= $l["id"] ?></td>
    <td><?= htmlspecialchars($l["username"]) ?></td>
    <td><?= $l["xp_gained"] ?></td>
    <td><?= htmlspecialchars($l["reason"]) ?></td>
    <td><?= $l["created_at"] ?></td>
</tr>
<?php endforeach; ?>

</table>

</div>

<?php include "footer.php"; ?>
