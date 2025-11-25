<?php
require "_spotify.php";

$r = spotify_api("PUT", "me/player/pause");

echo json_encode([
    "status" => "OK",
    "action" => "PAUSE",
    "spotify_response" => $r
]);
