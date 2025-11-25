<?php
require "../auth_check.php";
include "../header.php";

$user = $AUTH_USER;

// Passwort ändern
if (isset($_POST["change_password"])) {

    $old = $_POST["old_password"];
    $new = $_POST["new_password"];

    // Altes prüfen
    if (!password_verify($old, $user["password"])) {
        die("<script>alert('Altes Passwort falsch'); history.back();</script>");
    }

    $hash = password_hash($new, PASSWORD_BCRYPT);

    $stmt = $db->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->execute([$hash, $user["id"]]);

    echo "<script>alert('Passwort geändert!');</script>";
}

?>

<h2>Sicherheit</h2>

<form method="POST" class="form-box">

    <input type="hidden" name="change_password" value="1">

    <label>Altes Passwort</label>
    <input type="password" name="old_password" class="input" required>

    <label>Neues Passwort</label>
    <input type="password" name="new_password" class="input" required>

    <button class="btn-glow">Passwort ändern</button>

</form>

<?php include "../footer.php"; ?>
