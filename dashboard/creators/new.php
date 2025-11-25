<?php
$REQUIRED_PERMISSION = "creators.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$users = $db->query("SELECT id, name FROM users ORDER BY name ASC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $bio = $_POST["bio"];
    $user = $_POST["user_id"] ?: NULL;

    // Uploads
    $avatar = NULL;
    $banner = NULL;

    if (!empty($_FILES["avatar"]["name"])) {
        $avatar = time() . "_" . $_FILES["avatar"]["name"];
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/uploads/creators/avatar/" . $avatar);
    }

    if (!empty($_FILES["banner"]["name"])) {
        $banner = time() . "_" . $_FILES["banner"]["name"];
        move_uploaded_file($_FILES["banner"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/uploads/creators/banner/" . $banner);
    }

    $stmt = $db->prepare("
        INSERT INTO creators (user_id, name, bio, avatar, banner)
        VALUES (?,?,?,?,?)
    ");
    $stmt->execute([$user, $name, $bio, $avatar, $banner]);

    echo "<script>alert('Creator angelegt'); window.location='/dashboard/creators/list.php';</script>";
}
?>

<h2>Neuer Creator</h2>

<form method="POST" enctype="multipart/form-data" class="form-box">

    <label>Name</label>
    <input type="text" name="name" class="input" required>

    <label>Verknüpfter User (optional)</label>
    <select name="user_id" class="input">
        <option value="">— keiner —</option>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Bio</label>
    <textarea name="bio" class="input" rows="4"></textarea>

    <label>Avatar</label>
    <input type="file" name="avatar" class="input">

    <label>Banner</label>
    <input type="file" name="banner" class="input">

    <button class="btn-glow">Speichern</button>

</form>

<?php include "../footer.php"; ?>
