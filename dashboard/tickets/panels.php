<?php
$REQUIRED_PERMISSION = "admin";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>🎛 Ticket Panels verwalten</h2>
<p>Hier kannst du Panels erstellen, bearbeiten und verschiedenen Discord Servern zuweisen.</p>

<div class="panel-grid">

    <!-- PANEL LISTE -->
    <div class="panel-list-box">
        <h3>📋 Panel Liste</h3>
        <div id="panel_list">Lade Panels...</div>
    </div>

    <!-- PANEL EDITOR -->
    <div class="panel-edit-box">
        <h3>✏️ Panel bearbeiten / erstellen</h3>

        <input type="hidden" id="panel_id">

        <!-- SERVER AUSWAHL -->
        <?php include "../components/server_select.php"; ?>

        <label>Panel Titel</label>
        <input id="panel_title" placeholder="z.B. Support, RP-Kategorien, Bug-Meldungen">

        <label>Beschreibung</label>
        <textarea id="panel_desc"></textarea>

        <label>Icon (Emoji oder FontAwesome)</label>
        <input id="panel_icon" placeholder="z.B. 🛠️ oder fa-solid fa-headset">

        <label>Farbe (HEX)</label>
        <input id="panel_color" type="color" value="#a44cff">

        <label>Anzeige Ort</label>
        <select id="panel_showon">
            <option value="tickets">Nur Ticketsystem</option>
            <option value="dashboard">Nur Dashboard</option>
            <option value="website">Nur Website</option>
            <option value="all">Überall</option>
        </select>

        <label>Sortierung</label>
        <input id="panel_position" type="number" value="0">

        <button class="btn" onclick="savePanel()">💾 Panel speichern</button>
        <button class="btn danger" onclick="deletePanel()">🗑 Panel löschen</button>

        <div id="save_status"></div>
    </div>

</div>

<style>
.panel-grid {
    display: grid;
    grid-template-columns: 1.1fr 1.9fr;
    gap: 20px;
}

.panel-list-box, .panel-edit-box {
    background: rgba(40,20,60,0.75);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid #a44cff66;
    box-shadow: 0 0 20px #a44cff33;
}

label {
    margin-top: 10px;
    display: block;
    font-weight: bold;
}

input, textarea, select {
    width: 100%;
    padding: 10px;
    background: #1a0f2b;
    border-radius: 10px;
    border: 1px solid #a44cff66;
    color: white;
    margin-top: 5px;
}

textarea {
    min-height: 90px;
}

.btn {
    margin-top: 15px;
    padding: 12px 18px;
    background: #a44cff;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    color: white;
    box-shadow: 0 0 15px #a44cff88;
}

.btn.danger {
    background: #ff005d;
}

.panel-item {
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 10px;
    cursor: pointer;
    background: rgba(255,255,255,0.05);
    border-left: 4px solid #a44cff;
}
.panel-item:hover {
    background: rgba(255,255,255,0.12);
}
</style>

<script>
async function loadPanels() {
    const res = await fetch("/api/tickets/panels/list.php");
    const data = await res.json();
    const box = document.getElementById("panel_list");

    if (!data.panels || data.panels.length === 0) {
        box.innerHTML = "<i>Keine Panels erstellt.</i>";
        return;
    }

    let html = "";
    for (let p of data.panels) {
        html += `
            <div class="panel-item" onclick="selectPanel(${p.id})">
                <b>${p.title}</b><br>
                <small>${p.description || ""}</small>
            </div>
        `;
    }

    box.innerHTML = html;
}

async function selectPanel(id) {
    const res = await fetch("/api/tickets/panels/get.php?id=" + id);
    const data = await res.json();

    const p = data.panel;

    document.getElementById("panel_id").value = p.id;
    document.getElementById("panel_title").value = p.title;
    document.getElementById("panel_desc").value = p.description || "";
    document.getElementById("panel_icon").value = p.icon || "";
    document.getElementById("panel_color").value = p.color || "#a44cff";
    document.getElementById("panel_position").value = p.position;
    document.getElementById("panel_showon").value = p.show_on;

    // Server Dropdown setzen (mehrere Server)
    const dropdown = document.getElementById("server_select");
    dropdown.value = p.server_id; // EIN Server für jetzt, Multi-Server kommt in 23.6

    document.getElementById("save_status").innerHTML = "";
}

async function savePanel() {
    const id = document.getElementById("panel_id").value;

    const payload = {
        id: id,
        server_id: document.getElementById("server_select").value,
        title: document.getElementById("panel_title").value,
        description: document.getElementById("panel_desc").value,
        icon: document.getElementById("panel_icon").value,
        color: document.getElementById("panel_color").value,
        position: document.getElementById("panel_position").value,
        show_on: document.getElementById("panel_showon").value
    };

    const res = await fetch("/api/tickets/panels/update.php", {
        method: "POST",
        body: JSON.stringify(payload)
    });
    const data = await res.json();

    document.getElementById("save_status").innerHTML = "<b style='color:#3cff90;'>Gespeichert!</b>";

    loadPanels();
}

async function deletePanel() {
    const id = document.getElementById("panel_id").value;
    if (!id) return alert("Kein Panel ausgewählt");

    if (!confirm("Panel wirklich löschen?")) return;

    await fetch("/api/tickets/panels/delete.php", {
        method: "POST",
        body: new URLSearchParams({ id })
    });

    document.getElementById("panel_id").value = "";
    document.getElementById("save_status").innerHTML = "";
    loadPanels();
}

loadPanels();
</script>

<?php include "../footer.php"; ?>
