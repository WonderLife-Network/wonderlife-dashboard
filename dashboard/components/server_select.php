<?php
/*
==================================================
    SERVER SELECT COMPONENT
    Dynamische Server-Auswahl
    - zeigt nur Servernamen
    - speichert Guild-ID unsichtbar
    - lädt Server aus API
==================================================
*/

// Wichtig: API-URL abhängig von deiner Struktur
$SERVER_API = "/api/system/servers/list.php";
?>

<style>
.server-select-box {
    margin: 10px 0;
}

.server-select-label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
    color: #fff;
}

.server-dropdown {
    width: 100%;
    padding: 10px;
    background: #1a0f2b;
    border: 1px solid #a44cff66;
    border-radius: 10px;
    color: white;
    font-size: 16px;
}

.server-dropdown option {
    background: #1a0f2b;
    color: white;
}
</style>

<div class="server-select-box">
    <label class="server-select-label">Server auswählen</label>
    <select id="server_select" class="server-dropdown">
        <option value="">Lade Server…</option>
    </select>
</div>

<script>
async function loadServersIntoDropdown() {
    try {
        const res = await fetch("<?= $SERVER_API ?>");
        const data = await res.json();

        const dropdown = document.getElementById("server_select");

        dropdown.innerHTML = ""; // leeren

        if (!data.servers || data.servers.length === 0) {
            dropdown.innerHTML = `<option value="">Keine Server gefunden</option>`;
            return;
        }

        for (let s of data.servers) {
            let icon = s.icon ? s.icon : null;

            dropdown.innerHTML += `
                <option value="${s.id}">
                    ${s.name}
                </option>
            `;
        }
    } catch (err) {
        console.error("Server laden fehlgeschlagen:", err);
        const dropdown = document.getElementById("server_select");
        dropdown.innerHTML = `<option value="">Fehler beim Laden</option>`;
    }
}

// beim Seitenstart laden
loadServersIntoDropdown();
</script>
