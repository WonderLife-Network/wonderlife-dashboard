<?php
require "_spotify.php";

$r = spotify_api("GET", "me/player/currently-playing");

echo json_encode([
    "status" => "OK",
    "nowplaying" => $r
]);
