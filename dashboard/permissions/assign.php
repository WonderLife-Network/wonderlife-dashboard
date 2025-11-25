<?php
$REQUIRED_PERMISSION = "permissions.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$role_id = $_GET["role_id"];

$stmt = $db->prepare("SELECT * FROM roles WHERE id=?");
$stmt->execute([$role_id]);
$role = $stmt->fetch();

if (!$role) die("<h2>Rolle nicht gefunden</h2>");

$perms = $db->query("SELECT * FROM permissions")->fetchAll();

$current = $db->prepare("SELECT permission_id FROM role_permissions WHERE role_id=?");
$current->execute([$role_id]);
$active = array_column($current->fetchAll(), "permission_id");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $db->prepare("DELETE FROM role_permissions WHERE role_id=?")->execute([$role_id]);

    if (isset($_POST["permissions"])) {
        foreach ($_POST["permissions"] as $pid) {
            $db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)")
               ->execute([$role_id, $pid]);
        }
    }

    echo "<script>alert('Rechte gespeichert!'); window.location='/dashboard/roles/list.php';</script>";
}
?>

<h2>Berechtigungen für: <?= htmlspecialchars($role['role_name']) ?></h2>

<form method="POST" class="form-box">

<?php foreach ($perms as $p): ?>
    <label class="perm-check">
        <input type="checkbox" name="permissions[]" value="<?= $p['id'] ?>"
        <?= in_array($p['id'], $active) ? "checked" : "" ?>>
        <span><?= htmlspecialchars($p['permission_key']) ?> – <?= htmlspecialchars($p['description']) ?></span>
    </label>
<?php endforeach; ?>

    <button class="btn-glow" type="submit">Speichern</button>
</form>

<?php include "../footer.php"; ?>
