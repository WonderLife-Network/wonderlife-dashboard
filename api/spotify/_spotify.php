<?php
require "../../config.php";

// Spotify Settings laden
$stmt = $db->query("SELECT * FROM spotify_settings WHERE id=1");
$SET = $stmt->fetch();

if (!$SET) {
    die(json_encode(["error"=>"NO_SPOTIFY_CONFIG"]));
}

// Token erneuern
function spotify_refresh_token() {
    global $SET, $db;

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

    if (isset($res["access_token"])) {
        $SET["access_token"] = $res["access_token"];
        $SET["expires_at"] = time() + $res["expires_in"];

        $db->prepare("UPDATE spotify_settings SET access_token=?, expires_at=? WHERE id=1")
           ->execute([$SET["access_token"], $SET["expires_at"]]);
    }
}

// Spotify API Aufruf
function spotify_api($method, $endpoint, $body=null) {
    global $SET;

    if (time() > $SET["expires_at"]) {
        spotify_refresh_token();
    }

    $headers = [
        "Authorization: Bearer ".$SET["access_token"],
        "Content-Type: application/json"
    ];

    $url = "https://api.spotify.com/v1/$endpoint";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method !== "GET") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    return json_decode(curl_exec($ch), true);
}
