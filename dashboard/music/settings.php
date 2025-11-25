<?php
$REQUIRED_PERMISSION = "music.settings";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$stmt = $db->query("SELECT * FROM spotify_settings WHERE id=1");
$SET = $stmt->fetch();

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $client_id = $_POST["client_id"];
    $client_secret = $_POST["client_secret"];
    $redirect_uri = $_POST["redirect_uri"];

    $db->prepare("
        INSERT INTO spotify_settings (id,client_id,client_secret,redirect_uri)
        VALUES (1,?,?,?)
        ON DUPLICATE KEY UPDATE 
        client_id=VALUES(client_id),
        client_secret=VALUES(client_secret),
        redirect_uri=VALUES(redirect_uri)
    ")->execute([$client_id,$client_secret,$redirect_uri]);

    echo "<script>alert('Spotify API Daten gespeichert!');</script>";
}

?>

<h2>Spotify API Einstellungen</h2>

<form method="POST" class="form-box">

    <label>Client ID</label>
    <input class="input" name="client_id" value="<?= $SET["client_id"] ?? "" ?>">

    <label>Client Secret</label>
    <input class="input" name="client_secret" value="<?= $SET["client_secret"] ?? "" ?>">

    <label>Redirect URI</label>
    <input class="input" name="redirect_uri" 
           value="<?= $SET["redirect_uri"] ?? "https://team.wonderlife-network.net/dashboard/music/callback.php" ?>">

    <button class="btn-glow">Speichern</button>

</form>

<br><br>

<a href="https://accounts.spotify.com/authorize?client_id=<?= $SET["client_id"] ?>&response_type=code&redirect_uri=<?= urlencode($SET["redirect_uri"]) ?>&scope=user-read-playback-state%20user-modify-playback-state%20user-read-currently-playing%20user-read-private%20streaming" 
   class="btn-glow">
    Spotify verbinden
</a>

<?php include "../footer.php"; ?>
