<?php
$REQUIRED_PERMISSION = "automod.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$logs = $db->query("
    SELECT automod_logs.*, automod_rules.name AS rule_name
    FROM automod_logs
    LEFT JOIN automod_rules ON automod_rules.id = automod_logs.rule_id
    ORDER BY automod_logs.id DESC
    LIMIT 200
")->fetchAll();
?>

<h2>🤖 Automod Logs</h2>

<table class="dash-table">
<tr>
    <th>ID</th>
    <th>Regel</th>
    <th>User</th>
    <th>Aktion</th>
    <th>Nachricht</th>
    <th>Zeit</th>
</tr>

<?php foreach ($logs as $l): ?>
<tr>
    <td><?= $l["id"] ?></td>
    <td><?= htmlspecialchars($l["rule_name"]) ?></td>
    <td><?= htmlspecialchars($l["user_id"]) ?></td>
    <td><?= htmlspecialchars($l["action"]) ?></td>
    <td><?= htmlspecialchars($l["message"]) ?></td>
    <td><?= $l["created_at"] ?></td>
</tr>
<?php endforeach; ?>

</table>

<?php include "../footer.php"; ?>
