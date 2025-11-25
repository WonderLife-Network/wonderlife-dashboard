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

$socials = $db->prepare("SELECT * FROM creator_socials WHERE creator_id=?");
$socials->execute([$id]);
$socials = $socials->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $platform = $_POST["platform"];
    $url = $_POST["url"];

    $stmt = $db->prepare("INSERT INTO creator_socials (creator_id, platform, url) VALUES (?,?,?)");
    $stmt->execute([$id, $platform, $url]);

    echo "<script>location.reload();</script>";
}
?>

<h2>Social Links von <?= htmlspecialchars($c["name"]) ?></h2>

<form method="POST" class="form-box">

    <label>Plattform</label>
    <select name="platform" class="input">
        <option value="twitch">Twitch</option>
        <option value="youtube">YouTube</option>
        <option value="tiktok">TikTok</option>
        <option value="instagram">Instagram</option>
        <option value="x">X (Twitter)</option>
        <option value="discord">Discord</option>
    </select>

    <label>URL</label>
    <input type="text" name="url" class="input">

    <button class="btn-glow">Hinzufügen</button>
</form>

<h3>Vorhandene Social Links</h3>

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
        <a class="delete-btn" href="/dashboard/creators/social_delete.php?id=<?= $s['id'] ?>&creator=<?= $c['id'] ?>">
            Löschen
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php include "../footer.php"; ?>
