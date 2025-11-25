<?php
$REQUIRED_PERMISSION = "moderation";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>⚠️ Verwarnungen</h2>

<label>User ID</label>
<input id="user_id">
<label>Grund</label>
<input id="reason">

<button class="btn" onclick="add()">Hinzufügen</button>

<div id="list">Lade…</div>

<script>
async function load() {
    const uid = document.getElementById("user_id").value;
    if (!uid) {
        document.getElementById("list").innerHTML = "Bitte User ID eingeben";
        return;
    }
    const res = await fetch(`/api/mod/warn_list.php?user_id=${uid}`);
    const d = await res.json();

    let html = "<table class='dash-table'><tr><th>ID</th><th>Mod</th><th>Grund</th><th>Zeit</th><th>Aktion</th></tr>";

    for (let w of d.warnings) {
        html += `
        <tr>
            <td>${w.id}</td>
            <td>${w.mod_id}</td>
            <td>${w.reason}</td>
            <td>${w.created_at}</td>
            <td><button onclick="del(${w.id})">Löschen</button></td>
        </tr>
        `;
    }
    html += "</table>";
    document.getElementById("list").innerHTML = html;
}

async function add() {
    await fetch("/api/mod/warn_add.php", {
        method: "POST",
        body: new URLSearchParams({
            user_id: document.getElementById("user_id").value,
            mod_id: <?= $USER["id"] ?>,
            reason: document.getElementById("reason").value
        })
    });
    load();
}

async function del(id) {
    await fetch("/api/mod/warn_delete.php", {
        method: "POST",
        body: new URLSearchParams({ id })
    });
    load();
}
</script>

<?php include "../footer.php"; ?>
