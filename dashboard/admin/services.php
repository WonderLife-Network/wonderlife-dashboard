<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

// Alle Services laden
$services = $db->query("
    SELECT * FROM services
    ORDER BY id ASC
")->fetchAll();
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/services.css">

<div class="content">

<h2 style="color:white;">🟢 Service Status</h2>

<a href="service_add.php" class="btn">Service hinzufügen</a>

<table class="table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Status</th>
    <th>Uptime</th>
    <th>Beschreibung</th>
    <th>Bearbeiten</th>
</tr>

<?php foreach ($services as $s): ?>
<tr>
    <td><?= $s["id"] ?></td>
    <td><?= htmlspecialchars($s["name"]) ?></td>
    <td>
        <?php if ($s["status"] == "online"): ?>
            <span class="status-online">🟢 Online</span>
        <?php elseif ($s["status"] == "maintenance"): ?>
            <span class="status-maint">🟡 Maintenance</span>
        <?php else: ?>
            <span class="status-off">🔴 Offline</span>
        <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($s["uptime"]) ?></td>
    <td><?= htmlspecialchars($s["description"]) ?></td>
    <td>
        <a class="btn" href="service_edit.php?id=<?= $s['id'] ?>">Bearbeiten</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</div>

<?php include "footer.php"; ?>
