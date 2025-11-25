<?php
require "_spotify.php";

$r = spotify_api("POST", "me/player/next");

echo json_encode([
    "status" => "OK",
    "action" => "SKIP_NEXT",
    "spotify_response" => $r
]);
