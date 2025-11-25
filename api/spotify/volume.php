<?php
require "_spotify.php";

if (!isset($_GET["v"])) die(json_encode(["error"=>"NO_VOLUME"]));

$vol = intval($_GET["v"]);
if ($vol < 0) $vol = 0;
if ($vol > 100) $vol = 100;

$r = spotify_api("PUT", "me/player/volume?volume_percent=$vol");

echo json_encode([
    "status" => "OK",
    "action" => "SET_VOLUME",
    "volume" => $vol,
    "spotify_response" => $r
]);
