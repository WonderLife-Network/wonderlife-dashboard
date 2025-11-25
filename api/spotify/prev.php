<?php
require "_spotify.php";

$r = spotify_api("POST", "me/player/previous");

echo json_encode([
    "status" => "OK",
    "action" => "SKIP_PREVIOUS",
    "spotify_response" => $r
]);
