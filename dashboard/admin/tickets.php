<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

// Filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : "all";

$sql = "SELECT t.*, u.username 
        FROM tickets t 
        LEFT JOIN users u ON t.user_id = u.id";

if ($filter === "open") {
    $sql .= " WHERE t.status = 'open'";
}
if ($filter === "closed") {
    $sql .= " WHERE t.status = 'closed'";
}

$sql .= " ORDER BY t.id DESC";

$tickets = $db->query($sql);
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/tickets.css">

<div class="content">

<h2 style="color:white;">🎫 Ticketsystem</h2>

<div class="ticket-filters">
    <a href="?filter=all" class="<?= $filter=='all'?'active':'' ?>">Alle</a>
    <a href="?filter=open" class="<?= $filter=='open'?'active':'' ?>">Offen</a>
    <a href="?filter=closed" class="<?= $filter=='closed'?'active':'' ?>">Geschlossen</a>
</div>

<table class="table">
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Kategorie</th>
        <th>Status</th>
        <th>Erstellt</th>
        <th>Aktion</th>
    </tr>

    <?php foreach ($tickets as $t): ?>
    <tr>
        <td><?= $t["id"] ?></td>
        <td><?= htmlspecialchars($t["username"]) ?></td>
        <td><?= htmlspecialchars($t["category"]) ?></td>
        <td>
            <?php if ($t["status"] == "open"): ?>
                <span class="status-open">Offen</span>
            <?php else: ?>
                <span class="status-closed">Geschlossen</span>
            <?php endif; ?>
        </td>
        <td><?= $t["created_at"] ?></td>
        <td>
            <a class="btn" href="ticket_view.php?id=<?= $t['id'] ?>">Öffnen</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

</div>

<?php include "footer.php"; ?>
