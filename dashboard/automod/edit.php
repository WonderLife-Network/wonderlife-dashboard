<?php
$REQUIRED_PERMISSION = "automod.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM automod_rules WHERE id=?");
$stmt->execute([$id]);
$rule = $stmt->fetch();

if (!$rule) die("<h2>Regel nicht gefunden</h2>");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $type = $_POST["type"];
    $trigger = $_POST["trigger_text"];
    $action = $_POST["action"];
    $enabled = isset($_POST["enabled"]) ? 1 : 0;

    $stmt = $db->prepare("
        UPDATE automod_rules
        SET name=?, type=?, trigger_text=?, action=?, enabled=?
        WHERE id=?");
    $stmt->execute([$name, $type, $trigger, $action, $enabled, $id]);

    echo "<script>alert('Regel gespeichert!'); window.location='/dashboard/automod/list.php';</script>";
}
?>

<h2>Automod Regel bearbeiten</h2>

<form method="POST" class="form-box">

    <label>Name der Regel</label>
    <input type="text" name="name" class="input" value="<?= htmlspecialchars($rule["name"]) ?>" required>

    <label>Regel-Typ</label>
    <select name="type" class="input">
        <option value="word_block" <?= $rule["type"]=="word_block"?"selected":"" ?>>Wort blockieren</option>
        <option value="link_block" <?= $rule["type"]=="link_block"?"selected":"" ?>>Links blockieren</option>
        <option value="caps_filter" <?= $rule["type"]=="caps_filter"?"selected":"" ?>>Caps Filter</option>
        <option value="spam_filter" <?= $rule["type"]=="spam_filter"?"selected":"" ?>>Spam Filter</option>
    </select>

    <label>Trigger Text</label>
    <textarea name="trigger_text" class="input"><?= htmlspecialchars($rule["trigger_text"]) ?></textarea>

    <label>Aktion</label>
    <select name="action" class="input">
        <option value="delete" <?= $rule["action"]=="delete"?"selected":"" ?>>Nachricht löschen</option>
        <option value="warn" <?= $rule["action"]=="warn"?"selected":"" ?>>Warnen</option>
        <option value="timeout" <?= $rule["action"]=="timeout"?"selected":"" ?>>Timeout</option>
        <option value="log" <?= $rule["action"]=="log"?"selected":"" ?>>Nur loggen</option>
    </select>

    <label><input type="checkbox" name="enabled" <?= $rule["enabled"] ? "checked" : "" ?>> Regel aktiv</label>

    <button class="btn-glow">Speichern</button>
</form>

<?php include "../footer.php"; ?>
