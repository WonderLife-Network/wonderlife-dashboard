<?php
$REQUIRED_PERMISSION = "roles.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$id = $_GET["id"];

// Rolle laden
$stmt = $db->prepare("SELECT * FROM roles WHERE id=?");
$stmt->execute([$id]);
$role = $stmt->fetch();

if (!$role) {
    die("<h2>Rolle nicht gefunden</h2>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["role_name"];

    $stmt = $db->prepare("UPDATE roles SET role_name=? WHERE id=?");
    $stmt->execute([$name, $id]);

    echo "<script>alert('Rolle aktualisiert!'); window.location='/dashboard/roles/list.php';</script>";
}
?>

<h2>Rolle bearbeiten</h2>

<form class="form-box" method="POST">
    <label>Rollenname</label>
    <input type="text" name="role_name" class="input" 
           value="<?= htmlspecialchars($role['role_name']) ?>" required>

    <button type="submit" class="btn-glow">Speichern</button>
</form>

<?php include "../footer.php"; ?>
