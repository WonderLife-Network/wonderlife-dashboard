<?php
include "protect_owner.php";
require "../config.php";

function generate_api_key() {
    return bin2hex(random_bytes(32)); // 64 chars
}

if (isset($_POST["save"])) {

    $owner = $_POST["owner"];
    $perm = $_POST["permission"];
    $key = generate_api_key();

    $stmt = $db->prepare("
        INSERT INTO api_keys (api_key, owner, permission)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$key, $owner, $perm]);

    header("Location: api_keys.php?created=1");
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/api_form.css">

<div class="content">

<h2 style="color:white;">🔑 API-Key erstellen</h2>

<form method="POST" class="form">

    <label>Owner</label>
    <input type="text" name="owner" placeholder="z.B. WonderLife Bot" required>

    <label>Berechtigung</label>
    <select name="permission">
        <option value="read">Nur Lesen</option>
        <option value="write">Lesen + Schreiben</option>
        <option value="admin">Admin Vollzugriff</option>
    </select>

    <button class="btn">API-Key erstellen</button>

</form>

</div>
