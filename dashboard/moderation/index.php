<?php
$REQUIRED_PERMISSION = "user";
require "../auth_check.php";
include "../header.php";

$user_id = $_GET["user"] ?? null;
$user = null;

if ($user_id) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Counter laden
    $warnCount = $db->query("SELECT COUNT(*) FROM warnings WHERE user_id=$user_id")->fetchColumn();
    $noteCount = $db->query("SELECT COUNT(*) FROM notes WHERE user_id=$user_id")->fetchColumn();
    $reportCount = $db->query("SELECT COUNT(*) FROM reports WHERE user_id=$user_id")->fetchColumn();
    $banCount = $db->query("SELECT COUNT(*) FROM bans WHERE user_id=$user_id")->fetchColumn();
}

include "parts/moderation_header.php";
?>

<div class="mod-layout">

    <div class="mod-left">
        <?php include "parts/moderation_user_info.php"; ?>
    </div>

    <div class="mod-center">
        <h3>📝 Historie</h3>
        <?php include "warns.php"; ?>
        <?php include "notes.php"; ?>
        <?php include "reports.php"; ?>
        <?php include "bans.php"; ?>
    </div>

    <div class="mod-right">
        <?php include "parts/moderation_actions.php"; ?>
    </div>

</div>

<style>
.mod-layout {
    display: grid;
    grid-template-columns: 30% 40% 30%;
    gap: 20px;
}

.mod-left, .mod-center, .mod-right {
    background: rgba(20,10,35,0.5);
    padding: 15px;
    border-radius: 12px;
    border: 1px solid #a44cff55;
}
</style>

<?php include "../footer.php"; ?>
