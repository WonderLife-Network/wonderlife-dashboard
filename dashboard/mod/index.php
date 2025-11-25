<?php
$REQUIRED_PERMISSION = "moderation";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>🛡 Moderation Center</h2>

<ul class="mod-links">
    <li><a href="warnings.php">⚠️ Verwarnungen</a></li>
    <li><a href="notes.php">📝 Notizen</a></li>
    <li><a href="reports.php">🚨 Reports</a></li>
    <li><a href="logs.php">📜 Moderationslog</a></li>
</ul>

<style>
.mod-links li {
    background: rgba(40,20,60,0.75);
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 10px;
}
.mod-links a {
    color: #fff;
    text-decoration: none;
    font-size: 18px;
    display: block;
}
</style>

<?php include "../footer.php"; ?>
