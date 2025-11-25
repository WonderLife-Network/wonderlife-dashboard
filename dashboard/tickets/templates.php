<?php
$REQUIRED_PERMISSION = "admin";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>✉️ Ticket DM Templates</h2>
<p>Diese Nachrichten werden an den User gesendet, wenn du ein Ticket schließt.</p>

<div id="list">Lade…</div>

<h3>➕ Neues Template</h3>
<label>Titel</label>
<input id="new_title">
<label>Text</label>
<textarea id="new_content"></textarea>
<button class="btn" onclick="saveTemplate()">Speichern</button>

<script>
async function loadTemplates() {
    const r = await fetch("/api/tickets/dm_template.php?mode=list");
    const d = await r.json();

    let html = "";
    for (let t of d.templates) {
        html += `
        <div class="temp-card">
            <strong>${t.title}</strong><br>
            <pre>${t.content}</pre>
        </div>`;
    }
    document.getElementById("list").innerHTML = html;
}

async function saveTemplate() {
    await fetch("/api/tickets/dm_template.php?mode=save", {
        method: "POST",
        body: new URLSearchParams({
            title: document.getElementById("new_title").value,
            content: document.getElementById("new_content").value
        })
    });

    loadTemplates();
}

loadTemplates();
</script>

<style>
.temp-card {
    background: rgba(40,20,60,0.7);
    padding: 15px;
    border: 1px solid #a44cff66;
    border-radius: 10px;
    margin-bottom: 15px;
}
pre {
    white-space: pre-wrap;
    color: #ddd;
}
textarea {
    width: 100%;
    height: 100px;
    background: #1a0f2b;
    color: white;
    border: 1px solid #a44cff66;
    border-radius: 10px;
    padding: 10px;
}
</style>

<?php include "../footer.php"; ?>
