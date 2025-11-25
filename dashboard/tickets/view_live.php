<?php
$REQUIRED_PERMISSION = "tickets";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$ticket_id = intval($_GET["id"]);
?>

<h2>🎫 Live Ticket #<?= $ticket_id ?></h2>

<div class="ticket-container">

    <div class="chat-window" id="chatWindow">
        <div id="messages">Lade Nachrichten…</div>
    </div>

    <div class="sidebar">
        <h3>👥 Teilnehmer</h3>
        <div id="participants">Lade…</div>

        <h3>🛠 Agent Status</h3>
        <div id="agentStatus">Lade…</div>

        <button class="btn-small close" onclick="closeTicket()">❌ Ticket schließen</button>
    </div>

</div>

<div class="input-row">
    <input type="text" id="msgInput" placeholder="Nachricht eingeben…" oninput="typing()">
    <button class="btn" onclick="sendMessage()">Senden</button>
</div>

<script>
let lastMessageId = 0;

async function loadView() {
    const res = await fetch(`/api/tickets/live_view.php?id=<?= $ticket_id ?>`);
    const data = await res.json();

    let phtml = "";
    for (let p of data.participants) {
        phtml += `<div class="user-tag">${p}</div>`;
    }
    document.getElementById("participants").innerHTML = phtml;
}

async function loadMessages() {
    const res = await fetch(`/api/tickets/messages_live.php?id=<?= $ticket_id ?>`);
    const data = await res.json();

    let html = "";

    for (let m of data.messages) {
        html += `
        <div class="msg msg-${m.msg_type}">
            <strong>${m.sender_name}</strong><br>
            ${m.message}
            <div class="timestamp">${m.created_at}</div>
        </div>`;
    }

    document.getElementById("messages").innerHTML = html;

    let chat = document.getElementById("chatWindow");
    chat.scrollTop = chat.scrollHeight;
}

async function loadAgents() {
    const res = await fetch("/api/tickets/agent_status.php");
    const data = await res.json();

    let html = "";
    for (let a of data.agents) {
        let icon = "⚫";

        if (a.status === "online") icon = "🟢";
        if (a.status === "busy")   icon = "⛔";
        if (a.status === "typing") icon = "💬";

        html += `<div>${icon} ${a.user_id}</div>`;
    }
    document.getElementById("agentStatus").innerHTML = html;
}

async function sendMessage() {
    const msg = document.getElementById("msgInput").value.trim();
    if (!msg) return;

    await fetch("/api/tickets/send_message.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>,
            user_id: <?= $USER["id"] ?>,
            username: "<?= $USER["username"] ?>",
            message: msg
        })
    });

    document.getElementById("msgInput").value = "";
    loadMessages();
}

async function typing() {
    await fetch("/api/tickets/agent_update.php", {
        method: "POST",
        body: new URLSearchParams({
            user_id: <?= $USER["id"] ?>,
            status: "typing",
            ticket_id: <?= $ticket_id ?>
        })
    });
}

setInterval(loadAgents, 2000);
setInterval(loadMessages, 1500);
loadView();
loadAgents();
loadMessages();
</script>

<style>
.ticket-container {
    display: flex;
    gap: 20px;
}
.chat-window {
    flex: 4;
    background: rgba(30,10,50,0.8);
    height: 500px;
    overflow-y: auto;
    border-radius: 15px;
    padding: 15px;
    border: 1px solid #a44cff66;
}
.sidebar {
    flex: 1;
    background: rgba(40,20,60,0.75);
    padding: 15px;
    border-radius: 15px;
    border: 1px solid #a44cff66;
    height: 500px;
}
.msg {
    background: rgba(255,255,255,0.05);
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 10px;
    border-left: 3px solid #a44cff;
}
.msg-agent { border-left-color: #00bfff; }
.msg-system { border-left-color: #ff00d4; opacity: 0.8; }
.timestamp {
    font-size: 10px;
    opacity: 0.6;
    margin-top: 2px;
}
.user-tag {
    padding: 5px;
    background: rgba(255,0,200,0.2);
    border-radius: 8px;
    margin-bottom: 6px;
}
.input-row {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}
input {
    flex: 1;
    padding: 12px;
    border-radius: 10px;
    background: #150a22;
    color: white;
    border: 1px solid #a44cff66;
}
.btn {
    padding: 12px 20px;
    background: #a44cff;
    border: none;
    border-radius: 10px;
    color: white;
    cursor: pointer;
}
.close {
    margin-top: 20px;
    background: #ff005d;
}
</style>

<?php include "../footer.php"; ?>
