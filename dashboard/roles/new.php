<?php
$REQUIRED_PERMISSION = "roles.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $name = $_POST["role_name"];

    $stmt = $db->prepare("INSERT INTO roles (role_name) VALUES (?)");
    $stmt->execute([$name]);

    echo "<script>alert('Rolle wurde erstellt!'); window.location='/dashboard/roles/list.php';</script>";
}
?>

<h2>Neue Rolle anlegen</h2>

<form class="form-box" method="POST">
    <label>Rollenname</label>
    <input type="text" name="role_name" class="input" required>

    <button type="submit" class="btn-glow">Speichern</button>
</form>

<?php include "../footer.php"; ?>
