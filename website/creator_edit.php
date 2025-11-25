<?php
include "header.php";
include "config.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM creators WHERE id=?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) {
    die("<h1 class='title'>Creator nicht gefunden</h1>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $avatar = $_POST["avatar"];
    $description = $_POST["description"];

    $twitch = $_POST["twitch"];
    $youtube = $_POST["youtube"];
    $tiktok = $_POST["tiktok"];
    $instagram = $_POST["instagram"];
    $twitter = $_POST["twitter"];

    $stmt = $db->prepare("
        UPDATE creators
        SET name=?, avatar=?, description=?, twitch=?, youtube=?, tiktok=?, instagram=?, twitter=?
        WHERE id=?
    ");
    $stmt->execute([
        $name, $avatar, $description,
        $twitch, $youtube, $tiktok, $instagram, $twitter,
        $id
    ]);

    echo "<script>alert('Creator aktualisiert!'); window.location='creator.php';</script>";
}
?>

<h1 class="title">Creator bearbeiten</h1>

<form method="POST" class="form-box">

    <label>Name</label>
    <input type="text" name="name" class="input"
           value="<?php echo htmlspecialchars($c['name']); ?>" required>

    <label>Avatar URL</label>
    <input type="text" name="avatar" class="input"
           value="<?php echo htmlspecialchars($c['avatar']); ?>">

    <label>Beschreibung</label>
    <textarea name="description" class="textarea"><?php echo htmlspecialchars($c['description']); ?></textarea>

    <h3>Social Media</h3>

    <label>Twitch</label>
    <input type="text" name="twitch" class="input"
           value="<?php echo htmlspecialchars($c['twitch']); ?>">

    <label>YouTube</label>
    <input type="text" name="youtube" class="input"
           value="<?php echo htmlspecialchars($c['youtube']); ?>">

    <label>TikTok</label>
    <input type="text" name="tiktok" class="input"
           value="<?php echo htmlspecialchars($c['tiktok']); ?>">

    <label>Instagram</label>
    <input type="text" name="instagram" class="input"
           value="<?php echo htmlspecialchars($c['instagram']); ?>">

    <label>X / Twitter</label>
    <input type="text" name="twitter" class="input"
           value="<?php echo htmlspecialchars($c['twitter']); ?>">

    <button class="btn-glow" type="submit">Speichern</button>

</form>

<?php include "footer.php"; ?>
