<?php
include "protect_owner.php";
require "../config.php";

if (!isset($_GET["id"])) {
    die("Keine Service-ID angegeben.");
}

$id = intval($_GET["id"]);

$stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    die("Service nicht gefunden.");
}

if (isset($_POST["save"])) {

    $name = $_POST["name"];
    $status = $_POST["status"];
    $uptime = $_POST["uptime"];
    $desc = $_POST["description"];

    $stmt = $db->prepare("
        UPDATE services 
        SET name=?, status=?, uptime=?, description=?
        WHERE id=?
    ");
    $stmt->execute([$name, $status, $uptime, $desc, $id]);

    header("Location: services.php?updated=1");
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/services_form.css">

<div class="content">
    <h2 style="color:white;">🟢 Service bearbeiten</h2>

    <form method="POST" class="form">

        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($service['name']) ?>" required>

        <label>Status</label>
        <select name="status">
            <option value="online" <?= $service['status']=="online"?"selected":"" ?>>Online</option>
            <option value="offline" <?= $service['status']=="offline"?"selected":"" ?>>Offline</option>
            <option value="maintenance" <?= $service['status']=="maintenance"?"selected":"" ?>>Maintenance</option>
        </select>

        <label>Uptime</label>
        <input type="text" name="uptime" value="<?= htmlspecialchars($service['uptime']) ?>">

        <label>Beschreibung</label>
        <textarea name="description"><?= htmlspecialchars($service["description"]) ?></textarea>

        <button class="btn">Speichern</button>
    </form>
</div>
