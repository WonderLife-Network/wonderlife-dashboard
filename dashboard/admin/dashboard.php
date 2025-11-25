<?php
// Owner-Schutz
include "protect_owner.php";

// Layout-Elemente
include "sidebar.php";
include "header.php";

// Datenbank
require "../config.php";
?>

<link rel="stylesheet" href="../assets/css/admin_base.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

<div class="content">

    <h2 style="color:white;">📊 Admin Übersicht</h2>

    <div class="cards">

        <!-- CARD 1: Benutzer -->
        <div class="card">
            <h3>👤 Benutzer</h3>
            <p>
                <?php 
                $count = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
                echo $count ?: 0;
                ?>
            </p>
        </div>

        <!-- CARD 2: Tickets -->
        <div class="card">
            <h3>🎫 Offene Tickets</h3>
            <p>
                <?php 
                $count = $db->query("SELECT COUNT(*) FROM tickets WHERE status='open'")->fetchColumn();
                echo $count ?: 0;
                ?>
            </p>
        </div>

        <!-- CARD 3: Logs -->
        <div class="card">
            <h3>📜 Logs Gesamt</h3>
            <p>
                <?php 
                $count = $db->query("SELECT COUNT(*) FROM logs")->fetchColumn();
                echo $count ?: 0;
                ?>
            </p>
        </div>

        <!-- CARD 4: FiveM Spieler -->
        <div class="card">
            <h3>🚔 FiveM Spieler</h3>
            <p>
                <?php
                $players = $db->query("SELECT players_online FROM fivem_stats ORDER BY id DESC LIMIT 1")->fetchColumn();
                echo $players ?: 0;
                ?>
            </p>
        </div>

    </div>

    <!-- Letzte Logs -->
    <h2 style="color:white; margin-top:40px;">📜 Letzte 10 Logs</h2>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Aktion</th>
            <th>Info</th>
            <th>Zeit</th>
        </tr>

        <?php
        $logs = $db->query("SELECT * FROM logs ORDER BY id DESC LIMIT 10");
        foreach ($logs as $l):
        ?>

        <tr>
            <td><?= $l["id"] ?></td>
            <td><?= $l["user_id"] ?></td>
            <td><?= $l["action"] ?></td>
            <td><?= $l["info"] ?></td>
            <td><?= $l["created_at"] ?></td>
        </tr>

        <?php endforeach; ?>

    </table>

</div>

<?php include "footer.php"; ?>
