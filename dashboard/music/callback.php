<?php
require "../../config.php";

$code = $_GET["code"];

$stmt = $db->query("SELECT * FROM spotify_settings WHERE id=1");
$SET = $stmt->fetch();

$post = [
    "grant_type" => "authorization_code",
    "code" => $code,
    "redirect_uri" => $SET["redirect_uri"],
    "client_id" => $SET["client_id"],
    "client_secret" => $SET["client_secret"]
];

$ch = curl_init("https://accounts.spotify.com/api/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$response = json_decode(curl_exec($ch), true);

$access = $response["access_token"];
$refresh = $response["refresh_token"];
$expires = time() + $response["expires_in"];

$db->prepare("
    UPDATE spotify_settings SET access_token=?, refresh_token=?, expires_at=? WHERE id=1
")->execute([$access,$refresh,$expires]);

echo "<script>alert('Spotify verbunden!');window.location='/dashboard/music/index.php';</script>";
