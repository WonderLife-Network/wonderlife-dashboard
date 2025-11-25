<?php
include "header.php";
include "config.php";

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
        INSERT INTO creators (name, avatar, description, twitch, youtube, tiktok, instagram, twitter)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $avatar, $description, $twitch, $youtube, $tiktok, $instagram, $twitter]);

    echo "<script>alert('Creator hinzugefügt!'); window.location='creator.php';</script>";
}
?>

<h1 class="title">Neuen Creator hinzufügen</h1>

<form method="POST" class="form-box">

    <label>Name</label>
    <input type="text" name="name" required class="input">

    <label>Avatar URL (Bild)</label>
    <input type="text" name="avatar" class="input">

    <label>Beschreibung</label>
    <textarea name="description" class="textarea"></textarea>

    <h3>Social Media Links</h3>

    <label>Twitch</label>
    <input type="text" name="twitch" class="input">

    <label>YouTube</label>
    <input type="text" name="youtube" class="input">

    <label>TikTok</label>
    <input type="text" name="tiktok" class="input">

    <label>Instagram</label>
    <input type="text" name="instagram" class="input">

    <label>X / Twitter</label>
    <input type="text" name="twitter" class="input">

    <button class="btn-glow" type="submit">Erstellen</button>

</form>

<?php include "footer.php"; ?>
