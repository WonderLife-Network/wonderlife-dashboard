<?php
$REQUIRED_PERMISSION = "admin";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";
?>

<h2>⚙️ System Einstellungen</h2>
<p>Bearbeite alle globalen WonderLife Dashboard & System Settings.</p>

<div id="status"></div>

<div class="settings-grid">

    <div class="settings-card">
        <h3>🎨 Branding</h3>
        <label>Titel</label><input id="dashboard_title">
        <label>Logo URL</label><input id="dashboard_logo">
        <label>Farbe (#hex)</label><input id="dashboard_color">
        <label>Footer</label><input id="dashboard_footer">
    </div>

    <div class="settings-card">
        <h3>🧩 System</h3>
        <label>Monitor API URL</label><input id="monitor_api_url">
        <label>Sprache</label>
        <select id="default_language">
            <option value="de">Deutsch</option>
            <option value="en">English</option>
        </select>
        <label>Wartungsmodus</label>
        <select id="maintenance_mode">
            <option value="0">Aus</option>
            <option value="1">An</option>
        </select>
        <label>Debug</label>
        <select id="debug_mode">
            <option value="0">Aus</option>
            <option value="1">An</option>
        </select>
    </div>

    <div class="settings-card">
        <h3>🔐 Sicherheit</h3>
        <label>Passwort Mindestlänge</label><input type="number" id="password_min_length">
        <label>2FA Pflicht</label>
        <select id="2fa_required"><option value="0">Nein</option><option value="1">Ja</option></select>
        <label>Ratelimit</label>
        <select id="ratelimit_enabled"><option value="0">Nein</option><option value="1">Ja</option></select>
        <label>Captcha</label>
        <select id="captcha_enabled"><option value="0">Nein</option><option value="1">Ja</option></select>
    </div>

    <div class="settings-card">
        <h3>🟣 Discord</h3>
        <label>Guild ID</label><input id="discord_guild_id">
        <label>Bot Token</label><input type="password" id="discord_bot_token">
        <label>Prefix</label><input id="discord_prefix">
        <label>Logs Channel</label><input id="discord_logs_channel">
        <label>Ticket Kategorie</label><input id="discord_tickets_category">
        <label>Admin Rollen (CSV)</label><input id="discord_admin_roles">
    </div>

    <div class="settings-card">
        <h3>🚓 FiveM</h3>
        <label>Server IP</label><input id="fivem_server_ip">
        <label>Port</label><input id="fivem_server_port">
        <label>RCON Host</label><input id="fivem_rcon_host">
        <label>RCON Port</label><input id="fivem_rcon_port">
        <label>RCON Passwort</label><input type="password" id="fivem_rcon_password">
    </div>

    <div class="settings-card">
        <h3>🎵 Spotify</h3>
        <label>Client ID</label><input id="spotify_client_id">
        <label>Client Secret</label><input type="password" id="spotify_client_secret">
        <label>Refresh Token</label><input type="password" id="spotify_refresh_token">
    </div>

    <div class="settings-card">
        <h3>🔑 API Keys</h3>
        <label>ChatGPT</label><input type="password" id="chatgpt_api_key">
        <label>Creator Studio</label><input type="password" id="creatorstudio_api_key">
        <label>Mobile App</label><input type="password" id="mobile_api_key">
        <label>Webhook Master</label><input type="password" id="webhook_master_key">
    </div>

</div>

<button class="btn" onclick="save()">💾 Speichern</button>

<script>
let settings = {};

async function load() {
    const r = await fetch("/api/system/settings_get.php");
    const d = await r.json();

    settings = d.settings;

    for (let k in settings) {
        let el = document.getElementById(k);
        if (el) el.value = settings[k];
    }
}

async function save() {
    let out = {};

    for (let k in settings) {
        const el = document.getElementById(k);
        if (el) out[k] = el.value;
    }

    const res = await fetch("/api/system/settings_save.php", {
        method: "POST",
        body: JSON.stringify(out)
    });

    document.getElementById("status").innerHTML =
        "<p style='color:#0f0;'>✔ Gespeichert!</p>";

    setTimeout(() => document.getElementById("status").innerHTML = "", 3000);
}

load();
</script>

<style>
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(330px, 1fr));
    gap: 20px;
}
.settings-card {
    background: rgba(40,20,60,0.75);
    border: 1px solid #a44cff66;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 0 15px #a44cff44;
}
label { margin-top:8px; display:block; font-weight:bold; }
input, select {
    width: 100%;
    padding: 8px;
    margin-top: 4px;
    border-radius: 10px;
    border: 1px solid #a44cff55;
    background: #1a0f2b;
    color: white;
}
.btn {
    margin-top: 25px;
    padding: 12px 25px;
    background: #a44cff;
    border-radius: 10px;
    border: none;
    font-size: 18px;
    color: white;
    cursor: pointer;
    box-shadow: 0 0 15px #a44cffaa;
}
</style>

<?php include "../footer.php"; ?>
