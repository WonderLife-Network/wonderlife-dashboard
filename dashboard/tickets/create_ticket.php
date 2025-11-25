<?php
$REQUIRED_PERMISSION = "user";
require "../auth_check.php";
include "../header.php";

$cat_id   = $_GET["cat"]   ?? null;
$panel_id = $_GET["panel"] ?? null;

if (!$cat_id || !$panel_id) {
    echo "<p>Fehler: Ungültige Ticketdaten.</p>";
    include "../footer.php";
    exit;
}

// Kategorie laden
$cat_res = $db->prepare("SELECT tc.*, ds.name AS server_name 
                         FROM ticket_categories tc 
                         LEFT JOIN discord_servers ds ON tc.server_id = ds.id 
                         WHERE tc.id=?");
$cat_res->execute([$cat_id]);
$category = $cat_res->fetch(PDO::FETCH_ASSOC);

// Panel laden
$panel_res = $db->prepare("SELECT * FROM ticket_panels WHERE id=?");
$panel_res->execute([$panel_id]);
$panel = $panel_res->fetch(PDO::FETCH_ASSOC);
?>

<h2>📨 Ticket erstellen</h2>
<div class="ticket-info-box">
    <h3><?= $panel["icon"] ?> <?= $panel["title"] ?></h3>
    <p><?= $panel["description"] ?></p>

    <hr>

    <h4>Kategorie: <?= $category["icon"] ?> <?= $category["name"] ?></h4>
    <p><small>Server: <?= $category["server_name"] ?? "Global" ?></small></p>
</div>

<form id="ticket_form" onsubmit="return submitTicket()">

    <label>Betreff</label>
    <input id="subject" placeholder="Worum geht es?" required>

    <label>Nachricht</label>
    <textarea id="message" required placeholder="Beschreibe dein Anliegen."></textarea>

    <button class="btn">🎫 Ticket jetzt erstellen</button>

    <div id="status_box"></div>
</form>

<style>
.ticket-info-box {
    background: rgba(40,20,60,0.7);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid #a44cff66;
    box-shadow: 0 0 20px #a44cff33;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-top: 15px;
}

input, textarea {
    width: 100%;
    padding: 12px;
    background: #1a0f2b;
    border: 1px solid #a44cff66;
    border-radius: 10px;
    color: white;
    margin-top: 5px;
}

textarea {
    min-height: 120px;
}

.btn {
    margin-top: 20px;
    padding: 12px 22px;
    background: #a44cff;
    border: none;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    font-size: 16px;
    box-shadow: 0 0 15px #a44cff88;
}
</style>

<script>
async function submitTicket() {
    const panel_id = "<?= $panel_id ?>";
    const category_id = "<?= $cat_id ?>";
    const subject = document.getElementById("subject").value;
    const message = document.getElementById("message").value;

    const res = await fetch("/api/tickets/create.php", {
        method: "POST",
        body: JSON.stringify({
            panel_id: panel_id,
            category_id: category_id,
            subject: subject,
            message: message
        })
    });

    const data = await res.json();
    console.log(data);

    if (data.error) {
        document.getElementById("status_box").innerHTML =
            "<p style='color:#ff005d;'>" + data.error + "</p>";
        return false;
    }

    // Weiterleitung zu Ticket Ansicht
    window.location.href = "ticket_view.php?id=" + data.ticket_id;

    return false;
}
</script>

<?php include "../footer.php"; ?>
