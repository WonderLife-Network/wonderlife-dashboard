<?php
require "../auth.php";
require "../header.php";

// Ticket-ID holen
$ticket_id = $_GET["id"] ?? null;
if (!$ticket_id) {
    echo "<h2>Ticket nicht gefunden.</h2>";
    require "../footer.php";
    exit;
}

// Ticket laden
$stmt = $db->prepare("SELECT * FROM tickets WHERE id=?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    echo "<h2>Ticket existiert nicht.</h2>";
    require "../footer.php";
    exit;
}
?>

<div class="content-container">
    <h2>🎫 Ticket #<?= $ticket_id ?></h2>

    <!-- SERVER SELECTOR (Block 23.15) -->
    <?php include "parts/server_selector.php"; ?>

    <!-- AGENT BADGES (Block 23.12) -->
    <?php include "parts/agent_badges.php"; ?>

    <!-- TICKET TOOLS (Block 23.14) -->
    <?php include "parts/ticket_tools.php"; ?>

    <div class="ticket-view-container">

        <!-- ============================= -->
        <!--  NACHRICHTEN (Chatverlauf)    -->
        <!-- ============================= -->
        <div class="ticket-messages-box">
            <h3>Nachrichten</h3>
            <div id="ticket_messages" class="ticket-messages"></div>

            <form id="replyForm" onsubmit="sendReply(event)">
                <textarea id="replyText" placeholder="Antwort schreiben..." required></textarea>
                <button type="submit" class="btn">Senden</button>
            </form>
        </div>

        <!-- ============================= -->
        <!--     DATEIEN (Uploads)         -->
        <!-- ============================= -->
        <div class="ticket-files-box">
            <h3>Dateien</h3>
            <div id="ticket_files"></div>

            <form id="uploadForm" enctype="multipart/form-data">
                <input type="file" id="uploadFile" name="file" required>
                <button type="button" onclick="uploadFile()" class="btn">Hochladen</button>
            </form>
        </div>

        <!-- ============================= -->
        <!--            LOGS               -->
        <!-- ============================= -->
        <div class="ticket-logs-box">
            <h3>Logs</h3>
            <div id="ticket_logs"></div>
        </div>

    </div>
</div>

<style>
.content-container {
    padding: 20px;
}

.ticket-view-container {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 20px;
}

.ticket-messages {
    background: rgba(0,0,0,0.3);
    padding: 10px;
    height: 500px;
    overflow-y: auto;
    border-radius: 8px;
    box-shadow: 0 0 10px #a44cff55;
}

.ticket-files-box, .ticket-logs-box {
    background: rgba(0,0,0,0.3);
    padding: 10px;
    height: 500px;
    overflow-y: auto;
    border-radius: 8px;
    box-shadow: 0 0 10px #a44cff55;
}

#replyForm {
    margin-top: 10px;
}

#replyText {
    width: 100%;
    height: 80px;
    border-radius: 8px;
    padding: 10px;
}
</style>

<script>
// =========================================================
// Nachrichten laden
// =========================================================
async function loadMessages() {
    const res = await fetch("/api/tickets/get.php?id=<?= $ticket_id ?>");
    const data = await res.json();

    let box = document.getElementById("ticket_messages");
    box.innerHTML = "";

    if (!data.messages) return;

    for (let msg of data.messages) {
        box.innerHTML += `
            <div class="message">
                <b>${msg.username}</b><br>
                ${msg.message}<br>
                <small>${msg.created_at}</small>
                <hr>
            </div>
        `;
    }

    box.scrollTop = box.scrollHeight;
}

// =========================================================
// Dateien laden
// =========================================================
async function loadFiles() {
    const res = await fetch("/api/tickets/get.php?id=<?= $ticket_id ?>");
    const data = await res.json();

    let box = document.getElementById("ticket_files");
    box.innerHTML = "";

    if (!data.files) return;

    for (let f of data.files) {
        box.innerHTML += `
            <div class="file">
                <a href="${f.file_url}" target="_blank">${f.filename}</a>
                <small>${f.created_at}</small>
                <hr>
            </div>
        `;
    }
}

// =========================================================
// Logs laden
// =========================================================
async function loadLogs() {
    const res = await fetch("/api/tickets/get.php?id=<?= $ticket_id ?>");
    const data = await res.json();

    let box = document.getElementById("ticket_logs");
    box.innerHTML = "";

    if (!data.logs) return;

    for (let lg of data.logs) {
        box.innerHTML += `
            <div class="log">
                <b>${lg.action}</b> – ${lg.details}<br>
                <small>${lg.created_at}</small>
                <hr>
            </div>
        `;
    }
}

// =========================================================
// Nachricht senden
// =========================================================
async function sendReply(e) {
    e.preventDefault();

    let text = document.getElementById("replyText").value;

    await fetch("/api/tickets/reply.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>,
            message: text
        })
    });

    document.getElementById("replyText").value = "";
    loadMessages();
}

// =========================================================
// Datei hochladen
// =========================================================
async function uploadFile() {
    let fileInput = document.getElementById("uploadFile");
    if (!fileInput.files.length) return;

    let formData = new FormData();
    formData.append("ticket_id", <?= $ticket_id ?>);
    formData.append("file", fileInput.files[0]);

    await fetch("/api/tickets/upload_file.php", {
        method: "POST",
        body: formData
    });

    fileInput.value = "";
    loadFiles();
}

// =========================================================
// Agents laden (Status, Typing)
// =========================================================
async function loadAgents() {
    await fetch("/api/agents/get_status.php?ticket_id=<?= $ticket_id ?>")
}

// =========================================================
// LIVE UPDATES (Block 23.13 + 23.15)
// =========================================================
<?php include "parts/ticket_live_updates.php"; ?>

</script>

<?php require "../footer.php"; ?>
