<?php
require "../auth_check.php";
include "../header.php";

$user = $AUTH_USER;

$socials = $db->prepare("SELECT * FROM user_socials WHERE user_id=?");
$socials->execute([$user["id"]]);
$socials = $socials->fetchAll();

// Neuer Eintrag
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $platform = $_POST["platform"];
    $url = $_POST["url"];

    $stmt = $db->prepare("INSERT INTO user_socials (user_id, platform, url) VALUES (?,?,?)");
    $stmt->execute([$user["id"], $platform, $url]);

    echo "<script>location.reload();</script>";
}
?>

<h2>Social Links</h2>

<form method="POST" class="form-box">

    <label>Plattform</label>
    <select name="platform" class="input">
        <option value="twitch">Twitch</option>
        <option value="youtube">YouTube</option>
        <option value="tiktok">TikTok</option>
        <option value="instagram">Instagram</option>
        <option value="x">X (Twitter)</option>
        <option value="discord">Discord</option>
        <option value="website">Website</option>
    </select>

    <label>URL</label>
    <input type="text" name="url" class="input">

    <button class="btn-glow">Hinzufügen</button>
</form>

<h3>Verknüpfte Links</h3>

<table class="dash-table">
<tr>
    <th>Plattform</th>
    <th>URL</th>
    <th>Aktion</th>
</tr>

<?php foreach ($socials as $s): ?>
<tr>
    <td><?= htmlspecialchars($s['platform']) ?></td>
    <td><?= htmlspecialchars($s['url']) ?></td>
    <td>
        <a class="delete-btn" href="/dashboard/account/delete_social.php?id=<?= $s['id'] ?>">Löschen</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
