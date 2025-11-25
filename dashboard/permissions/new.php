<?php
$REQUIRED_PERMISSION = "permissions.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $key = $_POST["permission_key"];
    $desc = $_POST["description"];

    $stmt = $db->prepare("INSERT INTO permissions (permission_key, description) VALUES (?, ?)");
    $stmt->execute([$key, $desc]);

    echo "<script>alert('Permission erstellt!'); window.location='/dashboard/permissions/list.php';</script>";
}
?>

<h2>Neue Berechtigung</h2>

<form method="POST" class="form-box">
    <label>Permission Key</label>
    <input type="text" name="permission_key" class="input" required>

    <label>Beschreibung</label>
    <input type="text" name="description" class="input">

    <button class="btn-glow" type="submit">Speichern</button>
</form>

<?php include "../footer.php"; ?>
