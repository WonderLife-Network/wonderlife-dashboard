<?php
$REQUIRED_PERMISSION = "user";
require "../auth_check.php";
include "../header.php";

$ticket_id = $_GET["id"] ?? null;

if (!$ticket_id) {
    echo "<p>Ungültige Ticket-ID.</p>";
    include "../footer.php";
    exit;
}

// Ticket abrufen
$ticketStmt = $db->prepare("
SELECT t.*,
       p.title AS panel_title, p.icon AS panel_icon,
       c.name AS cat_name, c.icon AS cat_icon, c.color AS cat_color,
       ds.name AS server_name,
       u.username AS creator_name
FROM tickets t
LEFT JOIN ticket_panels p ON t.panel_id = p.id
LEFT JOIN ticket_categories c ON t.category_id = c.id
LEFT JOIN discord_servers ds ON t.server_id = ds.id
LEFT JOIN users u ON t.user_id = u.id
WHERE t.id=?
");
$ticketStmt->execute([$ticket_id]);
$ticket = $ticketStmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    echo "<p>Ticket nicht gefunden.</p>";
    include "../footer.php";
    exit;
}

$statusColors = [
    "open"    => "#32ff7e",
    "pending" => "#ffe227",
    "closed"  => "#ff4d4d"
];
?>

<h2>🎫 Ticket #<?= $ticket_id ?></h2>
<?php include "parts/server_selector.php"; ?>
<?php include "parts/agent_badges.php"; ?>

<div class="ticket-header">

    <div class="ticket-info-block">
        <h3><?= $ticket["panel_icon"] ?> <?= $ticket["panel_title"] ?></h3>
        <p class="subject-text"><?= $ticket["subject"] ?></p>

        <div class="ticket-category" style="border-left:4px solid <?= $ticket["cat_color"] ?>;">
            <?= $ticket["cat_icon"] ?> <?= $ticket["cat_name"] ?>
        </div>

        <p>
            <b>Server:</b> <?= $ticket["server_name"] ?: "Global" ?><br>
            <b>Status:</b> 
            <span class="status-label" style="background:<?= $statusColors[$ticket["status"]] ?>;">
                <?= strtoupper($ticket["status"]) ?>
            </span><br>
            <b>Erstellt von:</b> <?= $ticket["creator_name"] ?>
        </p>
    </div>

    <div class="ticket-actions">
        <button class="btn" onclick="claimTicket()">🤝 Claim</button>
        <button class="btn" onclick="unclaimTicket()">❌ Unclaim</button>

        <button class="btn yellow" onclick="setStatus('pending')">🕒 Ausstehend</button>
        <button class="btn red" onclick="setStatus('closed')">🔒 Schließen</button>
        <button class="btn green" onclick="setStatus('open')">🟢 Öffnen</button>
    </div>

</div>

<style>
.ticket-header {
    display: flex;
    gap: 25px;
    background: rgba(40,20,60,0.7);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid #a44cff66;
}

.ticket-info-block {
    flex: 2;
}

.ticket-actions {
    flex: 1;
    text-align: right;
}

.ticket-category {
    padding: 8px 12px;
    border-radius: 8px;
    background: rgba(255,255,255,0.05);
    margin-bottom: 10px;
}

.status-label {
    padding: 4px 10px;
    border-radius: 6px;
    color: black;
    font-weight: bold;
}

.subject-text {
    font-size: 16px;
    margin-bottom: 10px;
}
</style>

<hr class="divider">

<h3>💬 Nachrichten</h3>

<div id="messages_box" class="messages-container">
    Lade Nachrichten…
</div>

<div class="reply-box">
    <textarea id="reply_text" placeholder="Antwort schreiben…"></textarea>
    <button class="btn" onclick="sendReply()">📨 Senden</button>
</div>

<style>
.messages-container {
    margin-top: 15px;
    margin-bottom: 20px;
}

.message-block {
    background: rgba(255,255,255,0.05);
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 12px;
    border-left: 3px solid #a44cff;
}

.message-author {
    font-weight: bold;
    font-size: 15px;
    color: #ffffff;
}

.message-time {
    opacity: 0.6;
    font-size: 13px;
    margin-bottom: 10px;
}

.message-text {
    font-size: 15px;
    color: #dddddd;
}

.reply-box {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}

#reply_text {
    flex: 1;
    min-height: 90px;
    background: #1a0f2b;
    border: 1px solid #a44cff66;
    border-radius: 10px;
    color: white;
    padding: 12px;
    font-size: 15px;
}

.reply-box .btn {
    padding: 12px 20px;
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
// Nachrichten laden
async function loadMessages() {
    const res = await fetch("/api/tickets/get.php?id=<?= $ticket_id ?>");
    const data = await res.json();

    const box = document.getElementById("messages_box");
    let html = "";

    for (let m of data.messages) {
        html += `
            <div class="message-block">
                <div class="message-author">${m.username}</div>
                <div class="message-time">${m.created_at}</div>
                <div class="message-text">${m.message}</div>
            </div>
        `;
    }

    box.innerHTML = html;
}

// Antwort senden
async function sendReply() {
    const txt = document.getElementById("reply_text").value;
    if (!txt.trim()) return;

    await fetch("/api/tickets/reply.php", {
        method: "POST",
        body: JSON.stringify({
            ticket_id: <?= $ticket_id ?>,
            message: txt
        })
    });

    document.getElementById("reply_text").value = "";

    loadMessages();
}
</script>

<hr class="divider">

<h3>📎 Dateien</h3>

<div id="file_box" class="file-container">
    Lade Dateien…
</div>

<form id="file_upload_form" enctype="multipart/form-data" class="upload-form" onsubmit="return uploadFile()">
    <input type="file" id="file_input" name="file" class="file-input">
    <button type="submit" class="btn">⬆ Hochladen</button>
</form>

<style>
.file-container {
    margin-top: 15px;
    margin-bottom: 25px;
}

.file-entry {
    background: rgba(255,255,255,0.05);
    padding: 14px;
    border-radius: 10px;
    margin-bottom: 12px;
    border-left: 3px solid #00d4ff;
}

.file-entry b {
    color: #fff;
}

.file-entry .file-meta {
    font-size: 13px;
    opacity: 0.7;
}

.file-entry .btn {
    margin-top: 8px;
}

.file-preview {
    margin-top: 12px;
}

.upload-form {
    display: flex;
    gap: 10px;
    align-items: center;
}

.file-input {
    background: #1a0f2b;
    color: #fff;
    border: 1px solid #a44cff66;
    border-radius: 10px;
    padding: 8px;
}
</style>

<script>
// Dateien laden
async function loadFiles() {
    const res = await fetch("/api/tickets/files.php?ticket_id=<?= $ticket_id ?>");
    const data = await res.json();

    const box = document.getElementById("file_box");
    let html = "";

    for (let f of data.files) {

        html += `
            <div class="file-entry">
                <b>${f.file_name}</b>
                <div class="file-meta">
                    Typ: ${f.file_type} |
                    Größe: ${Math.round(f.file_size / 1024)} KB |
                    Hochgeladen: ${f.uploaded_at}
                </div>
                <a href="${f.file_path}" download class="btn">⬇ Download</a>
        `;

        // Bildvorschau
        if (f.file_type.startsWith("image/")) {
            html += `
                <div class="file-preview">
                    <img src="${f.file_path}" style="max-width:200px;border-radius:8px;">
                </div>
            `;
        }

        // Video-Vorschau
        if (f.file_type === "video/mp4") {
            html += `
                <div class="file-preview">
                    <video width="250" controls>
                        <source src="${f.file_path}" type="video/mp4">
                    </video>
                </div>
            `;
        }

        html += `</div>`;
    }

    box.innerHTML = html;
}

// Datei hochladen
async function uploadFile() {
    const input = document.getElementById("file_input");
    if (input.files.length === 0) return false;

    const formData = new FormData();
    formData.append("ticket_id", <?= $ticket_id ?>);
    formData.append("file", input.files[0]);

    const res = await fetch("/api/tickets/upload_file.php", {
        method: "POST",
        body: formData
    });

    const data = await res.json();

    if (data.error) {
        alert(data.error);
        return false;
    }

    input.value = "";
    loadFiles();
    return false;
}

// Dateien regelmäßig aktualisieren
setInterval(loadFiles, 8000);
loadFiles();
</script>

<hr class="divider">

<h3>👥 Aktive Agents</h3>

<div id="agent_box" class="agent-container">
    Lade Agenten…
</div>

<style>
.agent-container {
    margin-top: 15px;
    margin-bottom: 25px;
}

.agent-block {
    background: rgba(255,255,255,0.05);
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 10px;
    border-left: 3px solid #3c8dff;
}

.agent-name {
    font-weight: bold;
    color: #fff;
}

.agent-info {
    font-size: 13px;
    opacity: 0.75;
}

.agent-status-online {
    color: #32ff7e;
}

.agent-status-idle {
    color: #ffe04d;
}

.agent-status-offline {
    color: #ff4d4d;
}

.typing {
    color: #a44cff;
    font-style: italic;
    font-weight: bold;
}
</style>

<script>
// Agenten laden
async function loadAgents() {
    const res = await fetch("/api/agents/get_status.php?ticket_id=<?= $ticket_id ?>");
    const data = await res.json();

    const box = document.getElementById("agent_box");
    let html = "";

    if (!data.agents || data.agents.length === 0) {
        box.innerHTML = "<i>Keine aktiven Agents.</i>";
        return;
    }

    for (let a of data.agents) {
        let statusClass = "agent-status-offline";
        if (a.status === "online") statusClass = "agent-status-online";
        if (a.status === "idle")   statusClass = "agent-status-idle";

        html += `
            <div class="agent-block">
                <div class="agent-name">${a.username}</div>
                <div class="agent-info">
                    Status: <span class="${statusClass}">${a.status}</span><br>
                    Letzte Aktivität: ${a.last_active}<br>
                    ${a.typing == 1 ? "<span class='typing'>tippt…</span>" : ""}
                </div>
            </div>
        `;
    }

    box.innerHTML = html;
}

// Agent Aktivität im Ticket aktualisieren
setInterval(loadAgents, 5000);
loadAgents();

// Typing-Erkennung im Reply-Feld
let typingTimeout = null;

document.getElementById("reply_text").addEventListener("input", () => {
    fetch("/api/agents/typing_start.php", {method: "POST"});

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        fetch("/api/agents/typing_stop.php", {method: "POST"});
    }, 1500);
});

// Alle 10 Sekunden eigene Aktivität updaten
setInterval(() => {
    fetch("/api/agents/update_activity.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>
        })
    });
}, 10000);
</script>

<hr class="divider">

<script>
// =====================================================
// 1) Nachrichten neu laden
// =====================================================
async function loadMessages() {
    const res = await fetch("/api/tickets/get.php?id=<?= $ticket_id ?>");
    const data = await res.json();

    const box = document.getElementById("messages_box");
    let html = "";

    for (let m of data.messages) {
        html += `
            <div class="message-block">
                <div class="message-author">${m.username}</div>
                <div class="message-time">${m.created_at}</div>
                <div class="message-text">${m.message}</div>
            </div>
        `;
    }

    box.innerHTML = html;
}

// =====================================================
// 2) Dateien laden
// =====================================================
async function loadFiles() {
    const res = await fetch("/api/tickets/files.php?ticket_id=<?= $ticket_id ?>");
    const data = await res.json();

    const box = document.getElementById("file_box");
    let html = "";

    for (let f of data.files) {
        html += `
            <div class="file-entry">
                <b>${f.file_name}</b><br>
                <span class="file-meta">
                    Typ: ${f.file_type} |
                    Größe: ${Math.round(f.file_size / 1024)} KB |
                    ${f.uploaded_at}
                </span><br>
                <a href="${f.file_path}" download class="btn">⬇ Download</a>
        `;

        // Bild-Vorschau
        if (f.file_type.startsWith("image/")) {
            html += `
                <div class="file-preview">
                    <img src="${f.file_path}" style="max-width:200px;border-radius:10px;">
                </div>
            `;
        }

        // Video-Vorschau
        if (f.file_type === "video/mp4") {
            html += `
                <div class="file-preview">
                    <video width="260" controls>
                        <source src="${f.file_path}" type="video/mp4">
                    </video>
                </div>
            `;
        }

        html += `</div>`;
    }

    box.innerHTML = html;
}

// =====================================================
// 3) Datei hochladen
// =====================================================
async function uploadFile() {
    const input = document.getElementById("file_input");
    if (input.files.length === 0) return false;

    const formData = new FormData();
    formData.append("ticket_id", <?= $ticket_id ?>);
    formData.append("file", input.files[0]);

    const res = await fetch("/api/tickets/upload_file.php", {
        method: "POST",
        body: formData
    });

    const data = await res.json();
    if (data.error) {
        alert(data.error);
        return false;
    }

    input.value = "";
    loadFiles();
    return false;
}

// =====================================================
// 4) Antwort senden
// =====================================================
async function sendReply() {
    const txt = document.getElementById("reply_text").value;
    if (!txt.trim()) return;

    await fetch("/api/tickets/reply.php", {
        method: "POST",
        body: JSON.stringify({
            ticket_id: <?= $ticket_id ?>,
            message: txt
        })
    });

    document.getElementById("reply_text").value = "";
    loadMessages();
    loadAgents(); // Agenten Status aktualisieren
}

// =====================================================
// 5) Agenten laden
// =====================================================
async function loadAgents() {
    const res = await fetch("/api/agents/get_status.php?ticket_id=<?= $ticket_id ?>");
    const data = await res.json();

    const box = document.getElementById("agent_box");
    let html = "";

    for (let a of data.agents) {
        let statusClass = "agent-status-offline";
        if (a.status === "online") statusClass = "agent-status-online";
        if (a.status === "idle")   statusClass = "agent-status-idle";

        html += `
            <div class="agent-block">
                <div class="agent-name">${a.username}</div>
                <div class="agent-info">
                    Status: <span class="${statusClass}">${a.status}</span><br>
                    Letzte Aktivität: ${a.last_active}<br>
                    ${a.typing == 1 ? "<span class='typing'>tippt…</span>" : ""}
                </div>
            </div>
        `;
    }

    box.innerHTML = html || "<i>Keine aktiven Agents.</i>";
}

// =====================================================
// 6) Claim Ticket
// =====================================================
async function claimTicket() {
    await fetch("/api/tickets/claim.php", {
        method: "POST",
        body: new URLSearchParams({ ticket_id: <?= $ticket_id ?> })
    });

    loadAgents();
    loadLogs();
}

// =====================================================
// 7) Unclaim Ticket
// =====================================================
async function unclaimTicket() {
    await fetch("/api/tickets/unclaim.php", {
        method: "POST",
        body: new URLSearchParams({ ticket_id: <?= $ticket_id ?> })
    });

    loadAgents();
    loadLogs();
}

// =====================================================
// 8) Status ändern
// =====================================================
async function setStatus(status) {
    await fetch("/api/tickets/status.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>,
            status: status
        })
    });

    location.reload();
}

// =====================================================
// 9) Logs laden
// =====================================================
async function loadLogs() {
    const res = await fetch("/api/tickets/get.php?id=<?= $ticket_id ?>");
    const data = await res.json();

    const box = document.getElementById("log_box");
    let html = "";

    for (let l of data.logs) {
        html += `
            <div class="log-entry">
                <b>${l.action}</b><br>
                <small>${l.details}</small><br>
                <small>${l.created_at}</small>
            </div>
        `;
    }

    box.innerHTML = html;
}

// =====================================================
// 10) Typing detection
// =====================================================
let typingTimeout = null;

document.getElementById("reply_text").addEventListener("input", () => {
    fetch("/api/agents/typing_start.php", { method: "POST" });

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        fetch("/api/agents/typing_stop.php", { method: "POST" });
    }, 1500);
});

// =====================================================
// 11) Eigene Aktivität updaten
// =====================================================
setInterval(() => {
    fetch("/api/agents/update_activity.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>
        })
    });
}, 10000);

// =====================================================
// 12) Initial Load
// =====================================================
loadMessages();
loadFiles();
loadAgents();
loadLogs();

// =====================================================
// 13) Auto Refresh
// =====================================================
setInterval(loadMessages, 8000);
setInterval(loadFiles, 12000);
setInterval(loadAgents, 5000);
setInterval(loadLogs, 10000);
</script>

<hr class="divider">

<h3>📜 Logs</h3>

<div id="log_box" class="log-container">
    Lade Logs…
</div>

<style>
.log-container {
    margin-top: 15px;
}

.log-entry {
    background: rgba(255,255,255,0.05);
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 10px;
    border-left: 3px solid #ff00d4;
}

.log-entry b {
    color: #fff;
}

.log-entry small {
    color: #ccc;
    display: block;
}
</style>

<script>
// Logs beim Laden der Seite holen
loadLogs();
</script>

<?php include "parts/ticket_live_updates.php"; ?>
<?php include "../footer.php"; ?>

