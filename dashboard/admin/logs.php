<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

// Filter optional
$limit = isset($_GET["limit"]) ? intval($_GET["limit"]) : 200;

// Logs laden
$stmt = $db->prepare("SELECT l.*, u.username 
    FROM logs l 
    LEFT JOIN users u ON l.user_id = u.id
    ORDER BY l.id DESC 
    LIMIT ?");
$stmt->execute([$limit]);
$logs = $stmt->fetchAll();
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/logs.css">

<div class="content">

<h2 style="color:white;">📜 Logs</h2>

<div class="log-filters">
    <a href="?limit=50">50</a>
    <a href="?limit=100">100</a>
    <a href="?limit=200" class="active">200</a>
    <a href="?limit=500">500</a>
</div>

<table class="table">
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Aktion</th>
        <th>Info</th>
        <th>Zeit</th>
        <th>Details</th>
    </tr>

    <?php foreach ($logs as $l): ?>
    <tr>
        <td><?= $l["id"] ?></td>
        <td><?= htmlspecialchars($l["username"]) ?></td>
        <td><?= htmlspecialchars($l["action"]) ?></td>
        <td><?= htmlspecialchars(mb_strimwidth($l["info"], 0, 35, "...")) ?></td>
        <td><?= $l["created_at"] ?></td>
        <td>
            <a class="btn" href="log_view.php?id=<?= $l['id'] ?>">Öffnen</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

</div>

<?php include "footer.php"; ?>
