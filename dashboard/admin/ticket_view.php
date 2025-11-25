<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

if (!isset($_GET['id'])) {
    die("Keine Ticket-ID übergeben.");
}

$id = intval($_GET["id"]);

// Ticketdaten laden
$stmt = $db->prepare("SELECT t.*, u.username 
                      FROM tickets t
                      LEFT JOIN users u ON t.user_id = u.id
                      WHERE t.id = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) { die("Ticket nicht gefunden."); }

// Neue Nachricht senden
if (isset($_POST["message"])) {
    $msg = $_POST["message"];
    $sender = $_SESSION["id"];

    $stmt = $db->prepare("INSERT INTO ticket_messages (ticket_id, sender_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$id, $sender, $msg]);

    header("Location: ticket_view.php?id=$id");
    exit;
}

// Nachrichten laden
$messages = $db->prepare(
    "SELECT tm.*, u.username 
    FROM ticket_messages tm 
    LEFT JOIN users u ON tm.sender_id = u.id
    WHERE tm.ticket_id = ?
    ORDER BY tm.id ASC"
);
$messages->execute([$id]);
?>

<link rel="stylesheet" href="../assets/css/ticket_view.css">

<div class="content">

<h2 style="color:white;">🎫 Ticket #<?= $ticket["id"] ?></h2>

<div class="ticket-info">
    <p><b>User:</b> <?= htmlspecialchars($ticket["username"]) ?></p>
    <p><b>Kategorie:</b> <?= htmlspecialchars($ticket["category"]) ?></p>
    <p><b>Status:</b> 
        <?php if ($ticket["status"] == "open"): ?>
            <span class="status-open">Offen</span>
        <?php else: ?>
            <span class="status-closed">Geschlossen</span>
        <?php endif; ?>
    </p>

    <?php if ($ticket["status"] == "open"): ?>
    <a class="btn-danger" href="ticket_close.php?id=<?= $ticket['id'] ?>">Ticket schließen</a>
    <?php endif; ?>
</div>

<h3 style="color:white;">📝 Nachrichten</h3>

<div class="msg-box">

<?php foreach ($messages as $m): ?>
    <div class="msg">
        <b><?= htmlspecialchars($m["username"]) ?>:</b><br>
        <?= nl2br(htmlspecialchars($m["message"])) ?>
        <div class="msg-time"><?= $m["created_at"] ?></div>
    </div>
<?php endforeach; ?>

</div>

<?php if ($ticket["status"] == "open"): ?>
<form method="POST" class="msg-form">
    <textarea name="message" placeholder="Antwort schreiben..." required></textarea>
    <button class="btn">Senden</button>
</form>
<?php endif; ?>

</div>

<?php include "footer.php"; ?>
