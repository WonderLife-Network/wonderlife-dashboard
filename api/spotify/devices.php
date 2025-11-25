<?php
require "_spotify.php";

$r = spotify_api("GET", "me/player/devices");

echo json_encode([
    "status" => "OK",
    "devices" => $r["devices"]
]);
