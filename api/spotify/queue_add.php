<?php
require "_spotify.php";

if (!isset($_GET["track"])) {
    die(json_encode(["error" => "NO_TRACK"]));
}

$track_id = $_GET["track"];

$r = spotify_api("POST", "me/player/queue?uri=spotify:track:$track_id");

echo json_encode([
    "status" => "OK",
    "track" => $track_id,
    "spotify_response" => $r
]);
