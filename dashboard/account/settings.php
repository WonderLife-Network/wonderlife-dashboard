<?php
require "../auth_check.php";
include "../header.php";

$user = $AUTH_USER;

// Nutzer Settings laden
$stmt = $db->prepare("SELECT * FROM user_settings WHERE user_id=?");
$stmt->execute([$user["id"]]);
$settings = $stmt->fetch();

// Falls noch kein Settings-Datensatz existiert
if (!$settings) {
    $db->prepare("INSERT INTO user_settings (user_id) VALUES (?)")->execute([$user["id"]]);
    $stmt->execute([$user["id"]]);
    $settings = $stmt->fetch();
}

// Update Settings
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $language = $_POST["language"];
    $darkmode = isset($_POST["darkmode"]) ? 1 : 0;
    $layout   = $_POST["layout"];
    $sidebar  = $_POST["sidebar_mode"];
    $tformat  = $_POST["time_format"];
    $dformat  = $_POST["date_format"];

    $widgets = [
        "news" => isset($_POST["widget_news"]) ? 1 : 0,
        "tickets" => isset($_POST["widget_tickets"]) ? 1 : 0,
        "stats" => isset($_POST["widget_stats"]) ? 1 : 0,
        "creator" => isset($_POST["widget_creator"]) ? 1 : 0,
        "discord" => isset($_POST["widget_discord"]) ? 1 : 0
    ];

    $stmt = $db->prepare("
        UPDATE user_settings SET
        language=?,
        darkmode=?,
        dashboard_layout=?,
        sidebar_mode=?,
        time_format=?,
        date_format=?,
        widget_news=?,
        widget_tickets=?,
        widget_stats=?,
        widget_creator=?,
        widget_discord=?
        WHERE user_id=?
    ");
    $stmt->execute([
        $language,
        $darkmode,
        $layout,
        $sidebar,
        $tformat,
        $dformat,
        $widgets["news"],
        $widgets["tickets"],
        $widgets["stats"],
        $widgets["creator"],
        $widgets["discord"],
        $user["id"]
    ]);

    echo "<script>alert('Einstellungen gespeichert!'); location.reload();</script>";
}
?>

<h2>Dashboard Einstellungen</h2>

<form method="POST" class="form-box">

    <h3>🌓 Anzeige</h3>

    <label><input type="checkbox" name="darkmode" <?= $settings["darkmode"] ? "checked" : "" ?>> Dark Mode</label>

    <br><br>

    <h3>🌐 Sprache</h3>
    <select name="language" class="input">
        <option value="de" <?= $settings["language"] === "de" ? "selected" : "" ?>>Deutsch</option>
        <option value="en" <?= $settings["language"] === "en" ? "selected" : "" ?>>English</option>
    </select>

    <br><br>

    <h3>📐 Dashboard Layout</h3>
    <select name="layout" class="input">
        <option value="standard" <?= $settings["dashboard_layout"] === "standard" ? "selected" : "" ?>>Standard</option>
        <option value="compact" <?= $settings["dashboard_layout"] === "compact" ? "selected" : "" ?>>Kompakt</option>
        <option value="large" <?= $settings["dashboard_layout"] === "large" ? "selected" : "" ?>>Groß</option>
    </select>

    <br><br>

    <h3>📊 Widgets</h3>

    <label><input type="checkbox" name="widget_news" <?= $settings["widget_news"] ? "checked" : "" ?>> News</label><br>
    <label><input type="checkbox" name="widget_tickets" <?= $settings["widget_tickets"] ? "checked" : "" ?>> Tickets</label><br>
    <label><input type="checkbox" name="widget_stats" <?= $settings["widget_stats"] ? "checked" : "" ?>> Stats</label><br>
    <label><input type="checkbox" name="widget_creator" <?= $settings["widget_creator"] ? "checked" : "" ?>> Creator</label><br>
    <label><input type="checkbox" name="widget_discord" <?= $settings["widget_discord"] ? "checked" : "" ?>> Discord Events</label><br>

    <br><br>

    <h3>📅 Zeit & Datum</h3>

    <label>Zeitformat</label>
    <select name="time_format" class="input">
        <option value="24h" <?= $settings["time_format"] === "24h" ? "selected" : "" ?>>24 Stunden</option>
        <option value="12h" <?= $settings["time_format"] === "12h" ? "selected" : "" ?>>12 Stunden (AM/PM)</option>
    </select>

    <label>Datumsformat</label>
    <select name="date_format" class="input">
        <option value="DD.MM.YYYY" <?= $settings["date_format"] === "DD.MM.YYYY" ? "selected" : "" ?>>DD.MM.YYYY</option>
        <option value="MM/DD/YYYY" <?= $settings["date_format"] === "MM/DD/YYYY" ? "selected" : "" ?>>MM/DD/YYYY</option>
        <option value="YYYY-MM-DD" <?= $settings["date_format"] === "YYYY-MM-DD" ? "selected" : "" ?>>YYYY-MM-DD</option>
    </select>

    <br><br>

    <h3>📚 Sidebar Modus</h3>
    <select name="sidebar_mode" class="input">
        <option value="full" <?= $settings["sidebar_mode"] === "full" ? "selected" : "" ?>>Voll</option>
        <option value="mini" <?= $settings["sidebar_mode"] === "mini" ? "selected" : "" ?>>Minimal</option>
    </select>

    <br><br>

    <button class="btn-glow">Speichern</button>

</form>

<?php include "../footer.php"; ?>
