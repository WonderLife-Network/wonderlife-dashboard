<?php
$REQUIRED_PERMISSION = "music.panel";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

// Spotify Settings laden
$stmt = $db->query("SELECT * FROM spotify_settings WHERE id=1");
$SET = $stmt->fetch();

function spotify_api($method, $endpoint, $body = null) {
    global $SET, $db;

    // Token abgelaufen → Refresh
    if (time() > $SET["expires_at"]) {

        $post = [
            "grant_type" => "refresh_token",
            "refresh_token" => $SET["refresh_token"],
            "client_id" => $SET["client_id"],
            "client_secret" => $SET["client_secret"]
        ];

        $ch = curl_init("https://accounts.spotify.com/api/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

        $res = json_decode(curl_exec($ch), true);

        $SET["access_token"] = $res["access_token"];
        $SET["expires_at"]   = time() + $res["expires_in"];

        $db->prepare("UPDATE spotify_settings SET access_token=?, expires_at=? WHERE id=1")
           ->execute([$SET["access_token"], $SET["expires_at"]]);
    }

    $url = "https://api.spotify.com/v1/$endpoint";

    $headers = [
        "Authorization: Bearer ".$SET["access_token"],
        "Content-Type: application/json"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method !== "GET") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    return json_decode(curl_exec($ch), true);
}

// Aktives Gerät setzen
if (isset($_GET["set"])) {

    $device = $_GET["set"];

    spotify_api("PUT", "me/player", [
        "device_ids" => [$device],
        "play" => false
    ]);

    echo "<script>alert('Aktives Gerät gesetzt!'); window.location='/dashboard/music/devices.php';</script>";
    exit;
}

// Geräte laden
$data = spotify_api("GET", "me/player/devices");
$devices = $data["devices"] ?? [];
?>

<h2>📱 Spotify Geräte</h2>
<p>Wähle ein Gerät aus, das der WonderLife Player verwenden soll.</p>

<div class="device-grid">

<?php foreach ($devices as $d): ?>
    <div class="device-card <?= $d["is_active"] ? "active-device" : "" ?>">

        <h3><?= htmlspecialchars($d["name"]) ?></h3>
        <p>Typ: <?= htmlspecialchars($d["type"]) ?></p>
        <p>Lautstärke: <?= $d["volume_percent"] ?>%</p>

        <?php if ($d["is_active"]): ?>
            <p class="device-status">🟢 Aktiv</p>
        <?php else: ?>
            <a href="/dashboard/music/devices.php?set=<?= $d["id"] ?>" class="btn-small">
                Aktivieren
            </a>
        <?php endif; ?>

    </div>
<?php endforeach; ?>

</div>

<style>
.device-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.device-card {
    background: rgba(40, 20, 60, 0.75);
    border: 1px solid #a44cff55;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 0 20px #a44cff33;
    color: white;
    transition: 0.25s;
}

.device-card:hover {
    box-shadow: 0 0 30px #a44cffaa;
}

.active-device {
    border-color: #00ffbf;
    box-shadow: 0 0 20px #00ffbf99;
}

.device-status {
    font-weight: bold;
    color: #00ffbf;
}
</style>

<?php include "../footer.php"; ?>
