<?php
$REQUIRED_PERMISSION = "user"; 
require "../auth_check.php";
include "../header.php";
?>

<h2>🎫 Ticket erstellen</h2>
<p>Wähle zuerst einen Server, dann ein Panel, dann eine Kategorie.</p>

<!-- SERVER SELECT (dynamisch) -->
<?php include "../components/server_select.php"; ?>

<hr style="border-color:#a44cff55;">

<h3>📌 Panels</h3>
<div id="panel_box" style="display:flex; flex-wrap:wrap; gap:20px;">
    <p>Lade Panels…</p>
</div>

<hr style="border-color:#a44cff33;">

<h3>📁 Kategorien</h3>
<div id="category_box" style="display:flex; flex-wrap:wrap; gap:20px;">
    <p>Bitte Panel auswählen.</p>
</div>

<style>
.panel-card, .category-card {
    width: 260px;
    padding: 20px;
    background: rgba(40,20,60,0.7);
    border-radius: 15px;
    border: 1px solid #a44cff55;
    box-shadow: 0 0 20px #a44cff33;
    cursor: pointer;
    transition: 0.25s;
}
.panel-card:hover, .category-card:hover {
    background: rgba(60,30,100,0.9);
    transform: scale(1.03);
    box-shadow: 0 0 30px #a44cff66;
}

.panel-title {
    font-size: 20px;
    font-weight: bold;
    color: white;
}

.panel-desc {
    font-size: 14px;
    opacity: 0.8;
    color: #ddd;
}

.cat-title {
    font-size: 18px;
    font-weight: bold;
    color: white;
}

.cat-icon {
    font-size: 22px;
}
</style>

<script>
let selectedPanel = null;

// Wenn Server geändert wird → Panels neu laden
document.getElementById("server_select").addEventListener("change", loadPanels);

// PANELS LADEN
async function loadPanels() {
    const server = document.getElementById("server_select").value;
    const box = document.getElementById("panel_box");

    if (!server) {
        box.innerHTML = "<p>Bitte Server auswählen.</p>";
        return;
    }

    const res = await fetch("/api/tickets/panels/list.php");
    const data = await res.json();

    // Panels filtern nach server_id oder global
    const panels = data.panels.filter(p => 
        p.server_id === server || p.server_id === null
    );

    if (panels.length === 0) {
        box.innerHTML = "<p>Keine Panels für diesen Server vorhanden.</p>";
        return;
    }

    let html = "";
    for (let p of panels) {
        html += `
            <div class="panel-card" onclick="selectPanel(${p.id})" style="border-left:4px solid ${p.color || '#a44cff'};">
                <div class="panel-title">${p.icon || ''} ${p.title}</div>
                <div class="panel-desc">${p.description || ''}</div>
            </div>
        `;
    }

    box.innerHTML = html;
}

// PANEL AUSWÄHLEN
async function selectPanel(id) {
    selectedPanel = id;

    const box = document.getElementById("category_box");
    box.innerHTML = "<p>Lade Kategorien…</p>";

    const res = await fetch("/api/tickets/panels/categories_for_panel.php?panel_id=" + id);
    const data = await res.json();

    if (data.categories.length === 0) {
        box.innerHTML = "<p>Keine Kategorien zugeordnet.</p>";
        return;
    }

    let html = "";
    for (let c of data.categories) {
        html += `
            <div class="category-card"
                 onclick="startTicket(${c.id})"
                 style="border-left:4px solid ${c.color || '#a44cff'};">
                <div class="cat-icon">${c.icon || ''}</div>
                <div class="cat-title">${c.name}</div>
                <small>${c.server_name || 'Global'}</small>
            </div>
        `;
    }

    box.innerHTML = html;
}

// KATEGORIE ANKLICKEN → TICKET STARTEN
function startTicket(category_id) {
    window.location.href = "create_ticket.php?cat=" + category_id + "&panel=" + selectedPanel;
}
</script>

<?php include "../footer.php"; ?>
