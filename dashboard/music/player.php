<?php
$REQUIRED_PERMISSION = "music.panel";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$stmt = $db->query("SELECT * FROM spotify_settings WHERE id=1");
$SET = $stmt->fetch();

function spotify_api($method,$endpoint,$body=null){
    global $SET,$db;

    if(time() > $SET["expires_at"]){
        // Token erneuern
        $post = [
            "grant_type"=>"refresh_token",
            "refresh_token"=>$SET["refresh_token"],
            "client_id"=>$SET["client_id"],
            "client_secret"=>$SET["client_secret"]
        ];
        $ch = curl_init("https://accounts.spotify.com/api/token");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        $ret = json_decode(curl_exec($ch),true);

        $SET["access_token"] = $ret["access_token"];
        $SET["expires_at"] = time()+$ret["expires_in"];

        $db->prepare("UPDATE spotify_settings SET access_token=?, expires_at=? WHERE id=1")
           ->execute([$SET["access_token"],$SET["expires_at"]]);
    }

    $headers = ["Authorization: Bearer ".$SET["access_token"]];
    $ch = curl_init("https://api.spotify.com/v1/$endpoint");
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

    if($method=="PUT" || $method=="POST"){
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$method);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($body));
        curl_setopt($ch,CURLOPT_HTTPHEADER, array_merge($headers,["Content-Type: application/json"]));
    }

    return json_decode(curl_exec($ch),true);
}

$now = spotify_api("GET","me/player/currently-playing");
?>

<h2>🎧 Spotify Player</h2>

<?php if(!$now || !$now["is_playing"]): ?>
<p>Keine Musik läuft aktuell.</p>
<?php else: ?>

<div class="now-playing">
    <img src="<?= $now["item"]["album"]["images"][1]["url"] ?>" class="cover">
    <div>
        <h3><?= htmlspecialchars($now["item"]["name"]) ?></h3>
        <p><?= htmlspecialchars($now["item"]["artists"][0]["name"]) ?></p>
    </div>
</div>

<?php endif; ?>

<div class="player-controls">
    <a class="btn-glow" href="?act=pause">⏸ Pause</a>
    <a class="btn-glow" href="?act=play">▶️ Play</a>
    <a class="btn-glow" href="?act=next">⏭ Skip</a>
</div>

<?php
if(isset($_GET["act"])){
    if($_GET["act"]=="pause") spotify_api("PUT","me/player/pause");
    if($_GET["act"]=="play") spotify_api("PUT","me/player/play");
    if($_GET["act"]=="next") spotify_api("POST","me/player/next");
    echo "<meta http-equiv='refresh' content='0'>";
}
?>

<style>
.now-playing {
    display: flex;
    align-items: center;
    gap: 20px;
    margin: 20px 0;
}

.cover {
    width: 100px;
    border-radius: 10px;
    box-shadow: 0 0 20px #a44cff;
}

.player-controls a {
    margin-right: 15px;
    padding: 12px 20px;
}
</style>

<?php include "../footer.php"; ?>
