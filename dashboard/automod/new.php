<?php
$REQUIRED_PERMISSION = "automod.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $type = $_POST["type"];
    $trigger = $_POST["trigger_text"];
    $action = $_POST["action"];
    $enabled = isset($_POST["enabled"]) ? 1 : 0;

    $stmt = $db->prepare("
        INSERT INTO automod_rules (name, type, trigger_text, action, enabled)
        VALUES (?,?,?,?,?)
    ");
    $stmt->execute([$name, $type, $trigger, $action, $enabled]);

    echo "<script>alert('Regel erstellt!'); window.location='/dashboard/automod/list.php';</script>";
}
?>

<h2>Neue Automod Regel</h2>

<form method="POST" class="form-box">

    <label>Name der Regel</label>
    <input type="text" name="name" class="input" required>

    <label>Regel-Typ</label>
    <select name="type" class="input">
        <option value="word_block">Wort blockieren</option>
        <option value="link_block">Links blockieren</option>
        <option value="caps_filter">Caps Lock Filter</option>
        <option value="spam_filter">Spam Filter</option>
    </select>

    <label>Trigger Text (für Wortblock / Spam)</label>
    <textarea name="trigger_text" class="input"></textarea>

    <label>Aktion</label>
    <select name="action" class="input">
        <option value="delete">Nachricht löschen</option>
        <option value="warn">Warnen</option>
        <option value="timeout">Timeout</option>
        <option value="log">Nur loggen</option>
    </select>

    <label><input type="checkbox" name="enabled" checked> Regel aktiv</label>

    <button class="btn-glow">Speichern</button>
</form>

<?php include "../footer.php"; ?>
