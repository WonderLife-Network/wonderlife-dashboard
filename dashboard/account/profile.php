<?php
require "../auth_check.php";
include "../header.php";

$user = $AUTH_USER;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $bio  = $_POST["bio"];

    // --- AVATAR ---
    $avatar = $user["avatar"];
    if (!empty($_FILES["avatar"]["name"])) {

        if ($avatar) {
            unlink($_SERVER["DOCUMENT_ROOT"] . "/uploads/users/avatar/" . $avatar);
        }

        $avatar = time() . "_" . basename($_FILES["avatar"]["name"]);
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/uploads/users/avatar/" . $avatar);
    }

    // --- BANNER ---
    $banner = $user["banner"];
    if (!empty($_FILES["banner"]["name"])) {

        if ($banner) {
            unlink($_SERVER["DOCUMENT_ROOT"] . "/uploads/users/banner/" . $banner);
        }

        $banner = time() . "_" . basename($_FILES["banner"]["name"]);
        move_uploaded_file($_FILES["banner"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/uploads/users/banner/" . $banner);
    }

    // Update DB
    $stmt = $db->prepare("
        UPDATE users
        SET name=?, bio=?, avatar=?, banner=?
        WHERE id=?
    ");
    $stmt->execute([$name, $bio, $avatar, $banner, $user["id"]]);

    echo "<script>alert('Profil aktualisiert!'); window.location='/dashboard/account/profile.php';</script>";
}

// Reload User
$stmt = $db->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user["id"]]);
$user = $stmt->fetch();
?>

<h2>Mein Profil</h2>

<form method="POST" enctype="multipart/form-data" class="form-box">

    <label>Name</label>
    <input type="text" class="input" name="name" value="<?= htmlspecialchars($user['name']) ?>">

    <label>Bio</label>
    <textarea name="bio" class="input"><?= htmlspecialchars($user['bio']) ?></textarea>

    <label>Avatar</label>
    <?php if ($user["avatar"]): ?>
        <img src="/uploads/users/avatar/<?= $user['avatar'] ?>" 
             style="width:80px;border-radius:50%;margin-bottom:10px;">
        <br><a href="/dashboard/account/delete_avatar.php" class="delete-btn-small">Avatar löschen</a>
    <?php endif; ?>
    <input type="file" name="avatar" class="input">

    <label>Banner</label>
    <?php if ($user["banner"]): ?>
        <img src="/uploads/users/banner/<?= $user['banner'] ?>" 
             style="width:200px;border-radius:10px;margin-bottom:10px;">
        <br><a href="/dashboard/account/delete_banner.php" class="delete-btn-small">Banner löschen</a>
    <?php endif; ?>
    <input type="file" name="banner" class="input">

    <button class="btn-glow">Speichern</button>

</form>

<?php include "../footer.php"; ?>
