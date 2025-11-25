<?php
$REQUIRED_PERMISSION = "permissions.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM permissions WHERE id=?");
$stmt->execute([$id]);
$perm = $stmt->fetch();

if (!$perm) {
    die("<h2>Permission nicht gefunden</h2>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $key = $_POST["permission_key"];
    $desc = $_POST["description"];

    $stmt = $db->prepare("UPDATE permissions SET permission_key=?, description=? WHERE id=?");
    $stmt->execute([$key, $desc, $id]);

    echo "<script>alert('Permission aktualisiert!'); window.location='/dashboard/permissions/list.php';</script>";
}
?>

<h2>Berechtigung bearbeiten</h2>

<form method="POST" class="form-box">
    <label>Permission Key</label>
    <input type="text" name="permission_key" class="input"
           value="<?= htmlspecialchars($perm['permission_key']) ?>" required>

    <label>Beschreibung</label>
    <input type="text" name="description" class="input"
           value="<?= htmlspecialchars($perm['description']) ?>">

    <button class="btn-glow" type="submit">Speichern</button>
</form>

<?php include "../footer.php"; ?>
