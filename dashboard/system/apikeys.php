<?php
$REQUIRED_PERMISSION = "admin";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>🔑 API Keys Verwaltung</h2>
<p>Erstelle, verwalte oder lösche API-Schlüssel für Bots, Tools oder externe Services.</p>

<div id="keys-container">Lade API Keys…</div>

<hr style="margin:30px 0; border-color:#a44cff40;">

<h3>➕ Neuen API Key erstellen</h3>

<label>Label</label>
<input type="text" id="label">

<label>Scopes (Mehrfachauswahl möglich)</label>
<select id="scopes" multiple>
    <option value="admin">admin</option>
    <option value="write">write</option>
    <option value="read">read</option>
    <option value="bot">bot</option>
    <option value="tickets">tickets</option>
    <option value="server">server</option>
    <option value="music">music</option>
</select>

<label>Ablaufdatum (optional)</label>
<input type="datetime-local" id="expires_at">

<button class="btn" onclick="createKey()">API Key erstellen</button>

<script>
async function loadKeys() {
    const res = await fetch("/api/system/apikey_list.php");
    const data = await res.json();

    if (!data.keys) {
        document.getElementById("keys-container").innerHTML =
            "<p style='color:red;'>Fehler beim Laden der API Keys.</p>";
        return;
    }

    let html = `
    <table class="dash-table">
        <tr>
            <th>ID</th>
            <th>Label</th>
            <th>API Key</th>
            <th>Scopes</th>
            <th>Aktiv</th>
            <th>Ablaufdatum</th>
            <th>Aktionen</th>
        </tr>
    `;

    for (let k of data.keys) {
        html += `
        <tr>
            <td>${k.id}</td>
            <td>${k.label}</td>
            <td>${k.api_key}</td>
            <td>${k.scopes}</td>
            <td>${k.active == 1 ? "🟢 aktiv" : "🔴 aus"}</td>
            <td>${k.expires_at ?? "-"}</td>
            <td>
                <button class="btn-small delete" onclick="delKey(${k.id})">Löschen</button>
            </td>
        </tr>
        `;
    }

    html += "</table>";

    document.getElementById("keys-container").innerHTML = html;
}

async function createKey() {
    const label = document.getElementById("label").value.trim();
    const scopes = Array.from(document.getElementById("scopes").selectedOptions).map(o => o.value);
    const expires = document.getElementById("expires_at").value;

    if (!label || scopes.length === 0) {
        alert("Bitte Label und mindestens 1 Scope auswählen.");
        return;
    }

    const payload = { label, scopes, expires_at: expires };

    const res = await fetch("/api/system/apikey_create.php", {
        method: "POST",
        body: JSON.stringify(payload)
    });

    const out = await res.json();

    if (out.api_key) {
        alert("Neuer API Key:\n\n" + out.api_key + "\n\nBitte sofort kopieren!");
    }

    loadKeys();
}

async function delKey(id) {
    if (!confirm("API Key wirklich löschen?")) return;

    await fetch("/api/system/apikey_delete.php?id=" + id);
    loadKeys();
}

loadKeys();
</script>

<style>
label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}
input, select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    background: #1a0f2b;
    color: white;
    border-radius: 10px;
    border: 1px solid #a44cff66;
}
.btn {
    margin-top: 15px;
    background: #a44cff;
    border: none;
    padding: 10px 18px;
    font-size: 16px;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    box-shadow: 0 0 15px #a44cff88;
}
.btn-small {
    padding: 5px 12px;
    border-radius: 8px;
    background: #4c2a70;
    color: white;
    cursor: pointer;
    border: 1px solid #a44cff66;
}
.delete {
    background: #ff005d !important;
    border-color: #ff4cbf !important;
}
</style>

<?php include "../footer.php"; ?>
