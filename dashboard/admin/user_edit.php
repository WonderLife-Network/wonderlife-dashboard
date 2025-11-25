<?php
include "protect_owner.php";
include "sidebar.php";
include "header.php";
require "../config.php";

if (!isset($_GET['id'])) {
    die("Keine ID angegeben.");
}

$id = intval($_GET['id']);

$user = $db->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$id]);
$user = $user->fetch();

if (!$user) {
    die("Benutzer nicht gefunden.");
}

if (isset($_POST["save"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    $stmt = $db->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
    $stmt->execute([$username, $email, $role, $id]);

    header("Location: users.php?updated=1");
    exit();
}
?>

<div class="content">

<h2 style="color:white;">👤 Benutzer bearbeiten</h2>

<form method="POST" class="form">

    <label>Benutzername</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <label>Email</label>
    <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>">

    <label>Rolle</label>
    <select name="role">
        <option value="user"  <?= $user['role']=="user"?"selected":"" ?>>User</option>
        <option value="moderator" <?= $user['role']=="moderator"?"selected":"" ?>>Moderator</option>
        <option value="admin" <?= $user['role']=="admin"?"selected":"" ?>>Admin</option>
        <option value="owner" <?= $user['role']=="owner"?"selected":"" ?>>Owner</option>
    </select>

    <button type="submit" name="save" class="btn">Speichern</button>

</form>

</div>

<?php include "footer.php"; ?>
