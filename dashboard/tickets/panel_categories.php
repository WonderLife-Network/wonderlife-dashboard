<?php
$REQUIRED_PERMISSION = "admin";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>🔗 Kategorien einem Panel zuweisen</h2>

<p>Wähle ein Panel aus und verknüpfe Kategorien damit. Dynamisch für jeden Server.</p>

<div class="cat-panel-grid">

    <!-- PANEL AUSWAHL -->
    <div class="panel-box">
        <h3>📋 Panels</h3>
        <select id="panel_select" class="select-box" onchange="loadPanelCategories()">
            <option value="">Panel auswählen...</option>
        </select>
    </div>

    <!-- ZUGEWIESENE KATEGORIEN -->
    <div class="assigned-box">
        <h3>🎯 Zugewiesene Kategorien</h3>
        <div id="assigned_list">Bitte Panel auswählen.</div>
    </div>

    <!-- VERFÜGBARE KATEGORIEN -->
    <div class="available-box">
        <h3>➕ Kategorie hinzufügen</h3>

        <select id="available_select" class="select-box">
            <option value="">Keine Kategorien verfügbar</option>
        </select>

        <button class="btn" onclick="assignCategory()">➕ Hinzufügen</button>
    </div>

</div>

<style>
.cat-panel-grid {
    display: grid;
    grid-template-columns: 1.0fr 1.4fr 1.0fr;
    gap: 20px;
}

.select-box {
    width: 100%;
    padding: 10px;
    background: #1a0f2b;
    color: white;
    border-radius: 10px;
    border: 1px solid #a44cff66;
}

.assigned-item {
    padding: 10px;
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
    margin-bottom: 10px;
    border-left: 4px solid #a44cff;
}

.assigned-item button {
    float: right;
}
</style>

<script>
// Panels laden
async function loadPanels() {
    const res = await fetch("/api/tickets/panels/list.php");
    const data = await res.json();

    const sel = document.getElementById("panel_select");
    sel.innerHTML = '<option value="">Panel auswählen...</option>';

    for (let p of data.panels) {
        sel.innerHTML += `<option value="${p.id}">${p.title}</option>`;
    }
}

// ZUGEWIESENE Kategorien laden
async function loadPanelCategories() {
    const panel = document.getElementById("panel_select").value;
    if (!panel) return;

    // Assigned categories
    const resA = await fetch("/api/tickets/panels/categories_for_panel.php?panel_id=" + panel);
    const dataA = await resA.json();

    const assignedBox = document.getElementById("assigned_list");

    if (dataA.categories.length === 0) {
        assignedBox.innerHTML = "<i>Noch keine Kategorien zugewiesen.</i>";
    } else {
        let html = "";
        for (let c of dataA.categories) {
            html += `
                <div class="assigned-item">
                    <b>${c.name}</b> ${c.icon || ""}  
                    <small>(${c.server_name || 'Global'})</small>
                    <button class="btn danger btn-small" onclick="removeCategory(${c.id})">🗑</button>
                </div>
            `;
        }
        assignedBox.innerHTML = html;
    }

    // Available categories
    const resB = await fetch("/api/tickets/panels/categories_available.php?panel_id=" + panel);
    const dataB = await resB.json();

    const availSel = document.getElementById("available_select");
    availSel.innerHTML = "";

    if (dataB.categories.length === 0) {
        availSel.innerHTML = `<option value="">Keine Kategorien verfügbar</option>`;
    } else {
        for (let c of dataB.categories) {
            availSel.innerHTML += `
                <option value="${c.id}">
                    ${c.name} (${c.server_name || 'Global'})
                </option>
            `;
        }
    }
}

async function assignCategory() {
    const panel = document.getElementById("panel_select").value;
    const cat   = document.getElementById("available_select").value;

    if (!panel || !cat) return alert("Bitte Panel und Kategorie auswählen");

    await fetch("/api/tickets/panels/assign_category.php", {
        method: "POST",
        body: JSON.stringify({
            panel_id: panel,
            category_id: cat
        })
    });

    loadPanelCategories();
}

async function removeCategory(id) {
    if (!confirm("Kategorie entfernen?")) return;

    await fetch("/api/tickets/panels/remove_category.php", {
        method: "POST",
        body: new URLSearchParams({ id })
    });

    loadPanelCategories();
}

loadPanels();
</script>

<?php include "../footer.php"; ?>
