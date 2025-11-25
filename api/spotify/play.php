<?php
require "_spotify.php";

$r = spotify_api("PUT", "me/player/play");

echo json_encode([
    "status" => "OK",
    "action" => "PLAY",
    "spotify_response" => $r
]);
