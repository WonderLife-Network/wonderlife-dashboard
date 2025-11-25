<?php
include "header.php";

$fivem = file_get_contents("https://team.wonderlife-network.net/api/index.php?module=fivem&key=DEIN_API_KEY");
$discord = file_get_contents("https://team.wonderlife-network.net/api/index.php?module=discord&key=DEIN_API_KEY");

$fivem = json_decode($fivem, true);
$discord = json_decode($discord, true);
?>

<h1 class="title">Server Status</h1>

<div class="status-grid">
    <div class="card">
        <h2>FiveM</h2>
        <p>Spieler: <?php echo $fivem['info']['players']; ?></p>
    </div>

    <div class="card">
        <h2>Discord</h2>
        <p>Mitglieder: <?php echo $discord['member_count']; ?></p>
    </div>
</div>

<?php include "footer.php"; ?>
