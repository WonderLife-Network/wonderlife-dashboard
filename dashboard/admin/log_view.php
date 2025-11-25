<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

if (!isset($_GET["id"])) {
    die("Keine Log-ID angegeben.");
}

$id = intval($_GET["id"]);

$stmt = $db->prepare("SELECT l.*, u.username 
    FROM logs l 
    LEFT JOIN users u ON l.user_id = u.id
    WHERE l.id = ?");
$stmt->execute([$id]);
$log = $stmt->fetch();

if (!$log) {
    die("Log nicht gefunden.");
}
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/log_view.css">

<div class="content">

<h2 style="color:white;">📄 Log Details</h2>

<div class="log-detail-box">

    <p><b>ID:</b> <?= $log["id"] ?></p>
    <p><b>User:</b> <?= htmlspecialchars($log["username"]) ?></p>
    <p><b>Aktion:</b> <?= htmlspecialchars($log["action"]) ?></p>
    <p><b>Zeit:</b> <?= $log["created_at"] ?></p>

    <h3>📝 Info:</h3>
    <pre><?= htmlspecialchars($log["info"]) ?></pre>

</div>

<a href="logs.php" class="btn">Zurück</a>

</div>

<?php include "footer.php"; ?>
