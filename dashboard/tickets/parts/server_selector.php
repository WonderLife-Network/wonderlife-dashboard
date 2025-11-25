<?php
$tservers = $db->query("
    SELECT id, server_name
    FROM discord_servers
    WHERE is_active=1
    ORDER BY server_name ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="server-selector">
    <label>Server:</label>
    <select id="serverSelect" onchange="changeServer()">
        <?php foreach ($tservers as $s): ?>
            <option value="<?= $s['id'] ?>" <?= $s['id']==$ticket['server_id']?'selected':'' ?>>
                <?= htmlspecialchars($s['server_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<style>
.server-selector {
    margin: 10px 0;
    padding: 10px;
    background: rgba(164,76,255,0.1);
    border-radius: 8px;
    box-shadow: 0 0 8px #a44cff55;
    width: fit-content;
}
.server-selector label {
    font-weight: bold;
    margin-right: 5px;
}
</style>

<script>
function changeServer() {
    const newServer = document.getElementById("serverSelect").value;

    fetch("/api/tickets/tools/transfer.php", {
        method: "POST",
        body: new URLSearchParams({
            ticket_id: <?= $ticket_id ?>,
            server_id: newServer
        })
    }).then(() => location.reload());
}
</script>
