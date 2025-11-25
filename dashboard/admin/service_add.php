<?php
include "protect_owner.php";
require "../config.php";

if (isset($_POST["save"])) {

    $name = $_POST["name"];
    $status = $_POST["status"];
    $uptime = $_POST["uptime"];
    $desc = $_POST["description"];

    $stmt = $db->prepare("
        INSERT INTO services (name, status, uptime, description)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$name, $status, $uptime, $desc]);

    header("Location: services.php?added=1");
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/services_form.css">

<div class="content">
    <h2 style="color:white;">🟢 Neuen Service hinzufügen</h2>

    <form method="POST" class="form">

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Status</label>
        <select name="status">
            <option value="online">Online</option>
            <option value="offline">Offline</option>
            <option value="maintenance">Maintenance</option>
        </select>

        <label>Uptime</label>
        <input type="text" name="uptime">

        <label>Beschreibung</label>
        <textarea name="description"></textarea>

        <button class="btn">Speichern</button>
    </form>
</div>
