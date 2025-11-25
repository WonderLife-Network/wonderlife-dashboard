<?php
$REQUIRED_PERMISSION = "music.panel";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

// Spotify Settings laden
$stmt = $db->query("SELECT * FROM spotify_settings WHERE id=1");
$SET = $stmt->fetch();

function spotify_api($endpoint) {
    global $SET, $db;

    // Access Token abgelaufen? -> erneuern
    if (time() > $SET["expires_at"]) {
        $post = [
            "grant_type" => "refresh_token",
            "refresh_token" => $SET["refresh_token"],
            "client_id" => $SET["client_id"],
            "client_secret" => $SET["client_secret"]
        ];

        $ch = curl_init("https://accounts.spotify.com/api/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $res = json_decode(curl_exec($ch), true);

        $SET["access_token"] = $res["access_token"];
        $SET["expires_at"] = time() + $res["expires_in"];

        $db->prepare("UPDATE spotify_settings SET access_token=?, expires_at=? WHERE id=1")
           ->execute([$SET["access_token"], $SET["expires_at"]]);
    }

    $headers = ["Authorization: Bearer ".$SET["access_token"]];

    $ch = curl_init("https://api.spotify.com/v1/$endpoint");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    return json_decode(curl_exec($ch), true);
}

// Track zur Queue hinzufügen
if (isset($_GET["add"])) {
    $track_id = $_GET["add"];

    $stmt = $db->prepare("
        INSERT INTO spotify_queue (track_id, added_by)
        VALUES (?, ?)
    ");
    $stmt->execute([$track_id, $AUTH_USER["id"]]);

    echo "<script>alert('Track zur Queue hinzugefügt!');</script>";
}

// Suche ausführen
$results = [];
if (isset($_GET["q"])) {
    $query = urlencode($_GET["q"]);
    $search = spotify_api("search?q=$query&type=track&limit=20");

    if (isset($search["tracks"]["items"])) {
        $results = $search["tracks"]["items"];
    }
}
?>

<h2>🔍 Spotify Songs suchen</h2>

<form method="GET" class="form-box">
    <input type="text" name="q" class="input" placeholder="Song, Künstler oder Album suchen..." 
           value="<?= htmlspecialchars($_GET["q"] ?? "") ?>" required>
    <button class="btn-glow">Suchen</button>
</form>

<br>

<?php if ($results): ?>
<div class="track-grid">
<?php foreach ($results as $t): ?>
    <div class="track-card">

        <img src="<?= $t["album"]["images"][1]["url"] ?>" class="track-cover">

        <div class="track-info">
            <h3><?= htmlspecialchars($t["name"]) ?></h3>
            <p><?= htmlspecialchars($t["artists"][0]["name"]) ?></p>
        </div>

        <div class="track-buttons">
            <?php if ($t["preview_url"]): ?>
            <audio controls class="preview-player">
                <source src="<?= $t["preview_url"] ?>" type="audio/mpeg">
            </audio>
            <?php else: ?>
            <p style="color:#ff0099;">Keine Vorschau verfügbar</p>
            <?php endif; ?>

            <a class="btn-small" 
               href="/dashboard/music/search.php?q=<?= urlencode($_GET["q"]) ?>&add=<?= $t["id"] ?>">
               ➕ Zur Queue
            </a>
        </div>

    </div>
<?php endforeach; ?>
</div>

<?php endif; ?>

<style>
.track-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
}

.track-card {
    background: rgba(40,20,60,0.7);
    border: 1px solid #a44cff88;
    padding: 15px;
    border-radius: 15px;
    box-shadow: 0 0 15px #a44cff44;
    transition: 0.2s;
}

.track-card:hover {
    box-shadow: 0 0 30px #a44cffaa;
}

.track-cover {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 10px;
}

.track-info h3 {
    font-size: 18px;
    margin: 0;
    color: #fff;
}

.track-info p {
    margin: 0;
    opacity: .8;
}

.track-buttons {
    margin-top: 15px;
}

.preview-player {
    width: 100%;
    margin-bottom: 10px;
}
</style>

<?php include "../footer.php"; ?>
