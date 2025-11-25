<?php
$REQUIRED_PERMISSION = "music.panel";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>🎵 WonderLife Spotify Admin Panel</h2>
<p>Steuere Musik, Playlists, Geräte & Queue.</p>

<div class="music-menu">

    <a class="music-btn" href="/dashboard/music/player.php">🎧 Player</a>
    <a class="music-btn" href="/dashboard/music/search.php">🔍 Songs suchen</a>
    <a class="music-btn" href="/dashboard/music/queue.php">📜 Queue</a>
    <a class="music-btn" href="/dashboard/music/devices.php">📱 Geräte</a>
    <a class="music-btn" href="/dashboard/music/settings.php">⚙️ API Einstellungen</a>

</div>

<style>
.music-menu {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap: 20px;
}

.music-btn {
    display: block;
    background: rgba(40,20,60,0.7);
    border: 1px solid #a44cff;
    padding: 20px;
    text-align: center;
    border-radius: 15px;
    color: white;
    font-size: 20px;
    text-decoration: none;
    text-shadow: 0 0 8px #a44cff;
    transition: 0.2s;
}

.music-btn:hover {
    background: rgba(140,20,200,0.8);
    box-shadow: 0 0 20px #a44cff;
}
</style>

<?php include "../footer.php"; ?>
