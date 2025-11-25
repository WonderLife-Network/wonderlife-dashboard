<?php
$REQUIRED_PERMISSION = "music.panel";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$stmt = $db->query("SELECT * FROM spotify_settings WHERE id=1");
$SET = $stmt->fetch();

// Spotify API Handler
function spotify_api($method, $endpoint, $body = null) {
    global $SET, $db;

    // Token erneuern falls abgelaufen
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
        $SET["expires_at"]   = time() + $res["expires_in"];

        $db->prepare("UPDATE spotify_settings SET access_token=?, expires_at=? WHERE id=1")
            ->execute([$SET["access_token"], $SET["expires_at"]]);
    }

    $headers = [
        "Authorization: Bearer ".$SET["access_token"],
        "Content-Type: application/json"
    ];

    $url = "https://api.spotify.com/v1/$endpoint";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method != "GET") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    return json_decode(curl_exec($ch), true);
}

// Track aus Queue entfernen
if (isset($_GET["del"])) {
    $db->prepare("DELETE FROM spotify_queue WHERE id=?")->execute([$_GET["del"]]);
    echo "<script>window.location='/dashboard/music/queue.php';</script>";
    exit;
}

// Queue komplett löschen
if (isset($_GET["clear"])) {
    $db->query("TRUNCATE spotify_queue");
    echo "<script>alert('Queue gelöscht!'); window.location='/dashboard/music/queue.php';</script>";
    exit;
}

// Track abspielen
if (isset($_GET["play"])) {
    $track_id = $_GET["play"];

    spotify_api("PUT", "me/player/play", [
        "uris" => ["spotify:track:$track_id"]
    ]);

    echo "<script>alert('Track abgespielt!'); window.location='/dashboard/music/queue.php';</script>";
    exit;
}

// Queue laden
$q = $db->query("
    SELECT spotify_queue.*, users.name AS added_by_name
    FROM spotify_queue
    LEFT JOIN users ON users.id = spotify_queue.added_by
    ORDER BY spotify_queue.id ASC
")->fetchAll();
?>

<h2>📜 Spotify Queue</h2>

<?php if (!$q): ?>
<p>Keine Tracks in der Queue.</p>
<?php else: ?>

<a class="btn-glow" href="/dashboard/music/queue.php?clear=1">❌ Queue löschen</a>
<br><br>

<div class="queue-grid">

<?php 
foreach ($q as $item): 

    $track = spotify_api("GET", "tracks/".$item["track_id"]);

    if (!$track) continue;
?>

    <div class="queue-card">

        <img src="<?= $track["album"]["images"][1]["url"] ?>" class="queue-cover">

        <div class="queue-info">
            <h3><?= htmlspecialchars($track["name"]) ?></h3>
            <p><?= htmlspecialchars($track["artists"][0]["name"]) ?></p>
            <p class="added-by">Hinzugefügt von: <?= htmlspecialchars($item["added_by_name"]) ?></p>
        </div>

        <div class="queue-buttons">
            <a class="btn-small" href="/dashboard/music/queue.php?play=<?= $item["track_id"] ?>">▶️ Abspielen</a>
            <a class="btn-small delete" href="/dashboard/music/queue.php?del=<?= $item["id"] ?>">🗑 Entfernen</a>
        </div>

    </div>

<?php endforeach; ?>

</div>

<?php endif; ?>

<style>
.queue-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.queue-card {
    background: rgba(40,20,60,0.75);
    border: 1px solid #a44cff55;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 0 20px #a44cff33;
    transition: 0.25s;
}

.queue-card:hover {
    box-shadow: 0 0 30px #a44cffaa;
}

.queue-cover {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 10px;
}

.queue-info h3 {
    margin: 0;
    color: white;
}

.queue-info p {
    margin: 2px 0;
    opacity: .8;
}

.added-by {
    font-size: 12px;
    color: #ccc;
}

.queue-buttons {
    margin-top: 15px;
}

.queue-buttons .delete {
    background: #ff0055;
    border-color: #ff0099;
}

.queue-buttons a {
    margin-right: 10px;
}
</style>

<?php include "../footer.php"; ?>
