<?php
$REQUIRED_PERMISSION = "admin";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>📁 Ticket Kategorien verwalten</h2>
<p>Lege hier Ticket-Kategorien an. Kategorien können optional server-spezifisch oder global sein.</p>

<div class="cat-grid">

    <!-- KATEGORIE-LISTE -->
    <div class="cat-list-box">
        <h3>📋 Kategorien Liste</h3>
        <div id="category_list">Lade Kategorien...</div>
    </div>

    <!-- KATEGORIE-EDITOR -->
    <div class="cat-edit-box">
        <h3>✏️ Kategorie bearbeiten / erstellen</h3>

        <input type="hidden" id="category_id">

        <!-- SERVER AUSWAHL -->
        <?php include "../components/server_select.php"; ?>

        <label>Kategoriename</label>
        <input id="category_name" placeholder="z.B. Support, Bugs, Whitelist, RP, Technik">

        <label>Icon (Emoji oder FontAwesome)</label>
        <input id="category_icon" placeholder="z.B. 🛠️ oder fa-solid fa-bug">

        <label>Farbe (HEX)</label>
        <input id="category_color" type="color" value="#a44cff">

        <label>Sortierung</label>
        <input id="category_sort" type="number" value="0">

        <button class="btn" onclick="saveCategory()">💾 Kategorie speichern</button>
        <button class="btn danger" onclick="deleteCategory()">🗑 Kategorie löschen</button>

        <div id="save_status"></div>
    </div>

</div>

<style>
.cat-grid {
    display: grid;
    grid-template-columns: 1.1fr 1.9fr;
    gap: 20px;
}

.cat-list-box, .cat-edit-box {
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

.cat-item {
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 10px;
    cursor: pointer;
    background: rgba(255,255,255,0.05);
    border-left: 4px solid #a44cff;
}
.cat-item:hover {
    background: rgba(255,255,255,0.12);
}
</style>

<script>
async function loadCategories() {
    const res = await fetch("/api/tickets/categories/list.php");
    const data = await res.json();
    const box = document.getElementById("category_list");

    if (!data.categories || data.categories.length === 0) {
        box.innerHTML = "<i>Noch keine Kategorien vorhanden.</i>";
        return;
    }

    let html = "";
    for (let c of data.categories) {
        html += `
            <div class="cat-item" onclick="selectCategory(${c.id})">
                <b>${c.name}</b> <small>${c.icon || ""}</small><br>
                <small>Server: ${c.server_name || "Global"}</small>
            </div>
        `;
    }

    box.innerHTML = html;
}

async function selectCategory(id) {
    const res = await fetch("/api/tickets/categories/get.php?id=" + id);
    const data = await res.json();
    const c = data.category;

    document.getElementById("category_id").value = c.id;
    document.getElementById("category_name").value = c.name;
    document.getElementById("category_icon").value = c.icon || "";
    document.getElementById("category_color").value = c.color || "#a44cff";
    document.getElementById("category_sort").value = c.sort_order;

    // server setzen
    document.getElementById("server_select").value = c.server_id ?? "";

    document.getElementById("save_status").innerHTML = "";
}

async function saveCategory() {
    const id = document.getElementById("category_id").value;

    const payload = {
        id: id,
        server_id: document.getElementById("server_select").value,
        name: document.getElementById("category_name").value,
        icon: document.getElementById("category_icon").value,
        color: document.getElementById("category_color").value,
        sort_order: document.getElementById("category_sort").value
    };

    const res = await fetch("/api/tickets/categories/update.php", {
        method: "POST",
        body: JSON.stringify(payload)
    });

    const data = await res.json();
    document.getElementById("save_status").innerHTML = "<b style='color:#3cff90;'>Gespeichert!</b>";

    loadCategories();
}

async function deleteCategory() {
    const id = document.getElementById("category_id").value;
    if (!id) return alert("Keine Kategorie ausgewählt");

    if (!confirm("Kategorie wirklich löschen?")) return;

    await fetch("/api/tickets/categories/delete.php", {
        method: "POST",
        body: new URLSearchParams({ id })
    });

    document.getElementById("category_id").value = "";
    loadCategories();
}

loadCategories();
</script>

<?php include "../footer.php"; ?>
