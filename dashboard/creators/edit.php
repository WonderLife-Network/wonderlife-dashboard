<?php
$REQUIRED_PERMISSION = "creators.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM creators WHERE id=?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) die("<h2>Creator nicht gefunden</h2>");

$users = $db->query("SELECT id, name FROM users ORDER BY name ASC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $bio = $_POST["bio"];
    $user = $_POST["user_id"] ?: NULL;

    $avatar = $c["avatar"];
    $banner = $c["banner"];

    // Neues Avatar?
    if (!empty($_FILES["avatar"]["name"])) {
        if ($avatar) unlink($_SERVER["DOCUMENT_ROOT"] . "/uploads/creators/avatar/" . $avatar);
        $avatar = time() . "_" . $_FILES["avatar"]["name"];
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/uploads/creators/avatar/" . $avatar);
    }

    // Neues Banner?
    if (!empty($_FILES["banner"]["name"])) {
        if ($banner) unlink($_SERVER["DOCUMENT_ROOT"] . "/uploads/creators/banner/" . $banner);
        $banner = time() . "_" . $_FILES["banner"]["name"];
        move_uploaded_file($_FILES["banner"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/uploads/creators/banner/" . $banner);
    }

    $stmt = $db->prepare("
        UPDATE creators 
        SET user_id=?, name=?, bio=?, avatar=?, banner=?
        WHERE id=?
    ");
    $stmt->execute([$user, $name, $bio, $avatar, $banner, $id]);

    echo "<script>alert('Creator aktualisiert'); window.location='/dashboard/creators/list.php';</script>";
}
?>

<h2>Creator bearbeiten</h2>

<form method="POST" enctype="multipart/form-data" class="form-box">

    <label>Name</label>
    <input type="text" name="name" class="input" value="<?= htmlspecialchars($c['name']) ?>" required>

    <label>Verknüpfter User</label>
    <select name="user_id" class="input">
        <option value="">— keiner —</option>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>" <?= $c['user_id']==$u['id']?"selected":"" ?>>
                <?= htmlspecialchars($u['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Bio</label>
    <textarea name="bio" class="input"><?= htmlspecialchars($c['bio']) ?></textarea>

    <label>Avatar</label>
    <?php if ($c["avatar"]): ?>
        <img src="/uploads/creators/avatar/<?= $c['avatar'] ?>" style="width:80px;border-radius:50%;margin-bottom:10px;">
    <?php endif; ?>
    <input type="file" name="avatar" class="input">

    <label>Banner</label>
    <?php if ($c["banner"]): ?>
        <img src="/uploads/creators/banner/<?= $c['banner'] ?>" style="width:200px;border-radius:10px;margin-bottom:10px;">
    <?php endif; ?>
    <input type="file" name="banner" class="input">

    <button class="btn-glow">Speichern</button>

</form>

<?php include "../footer.php"; ?>
