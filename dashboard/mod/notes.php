<?php
$REQUIRED_PERMISSION = "moderation";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>📝 Notizen</h2>

<label>User ID</label>
<input id="user_id">
<label>Notiz</label>
<input id="note">

<button class="btn" onclick="add()">Hinzufügen</button>

<div id="list">Lade…</div>

<script>
async function load() {
    const uid = document.getElementById("user_id").value;
    if (!uid) return;

    const r = await fetch(`/api/mod/note_list.php?user_id=${uid}`);
    const d = await r.json();

    let html = "<table class='dash-table'><tr><th>ID</th><th>Mod</th><th>Notiz</th><th>Zeit</th><th>Aktion</th></tr>";

    for (let n of d.notes) {
        html += `
        <tr>
            <td>${n.id}</td>
            <td>${n.mod_id}</td>
            <td>${n.note}</td>
            <td>${n.created_at}</td>
            <td><button onclick="del(${n.id})">Löschen</button></td>
        </tr>`;
    }

    html += "</table>";
    document.getElementById("list").innerHTML = html;
}

async function add() {
    await fetch("/api/mod/note_add.php", {
        method: "POST",
        body: new URLSearchParams({
            user_id: document.getElementById("user_id").value,
            mod_id: <?= $USER["id"] ?>,
            note: document.getElementById("note").value
        })
    });
    load();
}

async function del(id) {
    await fetch("/api/mod/note_delete.php", {
        method: "POST",
        body: new URLSearchParams({ id })
    });
    load();
}
</script>

<?php include "../footer.php"; ?>
