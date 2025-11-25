<?php
// Authentication & Database must already be loaded in parent file
// If not, ensure they are included here:
if (!isset($AUTH_USER)) {
    require __DIR__ . "/auth_check.php";
}

// Load settings for the logged in user
$stmt = $db->prepare("SELECT * FROM user_settings WHERE user_id=?");
$stmt->execute([$AUTH_USER["id"]]);
$USER_SETTINGS = $stmt->fetch();

// Wenn Settings nicht existieren → automatisch anlegen
if (!$USER_SETTINGS) {
    $db->prepare("INSERT INTO user_settings (user_id) VALUES (?)")
       ->execute([$AUTH_USER["id"]]);

    $stmt->execute([$AUTH_USER["id"]]);
    $USER_SETTINGS = $stmt->fetch();
}

// Notification count
$stmt = $db->prepare("
    SELECT COUNT(*) FROM notifications
    WHERE user_id=? AND read_at IS NULL
");
$stmt->execute([$AUTH_USER["id"]]);
$NOTIFICATION_COUNT = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>WonderLife Dashboard</title>

    <!-- CSS -->
    <link rel="stylesheet" href="/dashboard/assets/css/admin_base.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/dashboard/assets/css/dashboard.css?v=<?= time() ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            background: #0b0b12;
            margin: 0;
            padding: 0;
            color: #fff;
            font-family: 'Inter', sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: rgba(20, 20, 35, 0.95);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            border-right: 2px solid #a44cff;
            box-shadow: 0 0 25px #8c3aff55;
            backdrop-filter: blur(12px);
            padding-top: 20px;
            z-index: 90;
        }

        .sidebar-title {
            text-align: center;
            font-size: 23px;
            font-weight: 700;
            color: #d9b3ff;
            margin-bottom: 30px;
            text-shadow: 0 0 8px #a44cff;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #bbb;
            text-decoration: none;
            transition: .2s;
            font-size: 15px;
        }

        .sidebar a:hover {
            background: rgba(164,76,255,0.12);
            color: #fff;
            padding-left: 27px;
        }

        /* HEADER */
        .header {
            height: 60px;
            background: rgba(25,25,40,0.75);
            border-bottom: 2px solid #a44cff;
            backdrop-filter: blur(12px);
            margin-left: 250px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        /* Notification ICON */
        .notif-icon {
            position: relative;
            margin-right: 25px;
            cursor: pointer;
        }

        .notif-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ff005d;
            color: white;
            padding: 3px 7px;
            font-size: 12px;
            border-radius: 50%;
            box-shadow: 0 0 10px #ff0099;
        }

        .user-menu {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user-menu img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid #a44cff;
            box-shadow: 0 0 10px #a44cffaa;
        }

        .user-menu-name {
            font-size: 15px;
            font-weight: 600;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
        }
    </style>

    <?php if ($USER_SETTINGS["darkmode"] == 1): ?>
    <style>
        body { background: #050509; }
    </style>
    <?php endif; ?>

    <?php if ($USER_SETTINGS["sidebar_mode"] == "mini"): ?>
    <style>
        .sidebar {
            width: 85px;
        }
        .sidebar a {
            font-size: 0;
        }
        .sidebar a::after {
            content: attr(data-short);
            font-size: 20px;
            margin-left: 5px;
            color: #ddd;
        }
        .main-content {
            margin-left: 85px;
        }
        .header {
            margin-left: 85px;
        }
    </style>
    <?php endif; ?>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="sidebar-title">WONDERLIFE</div>

    <a href="/dashboard/index.php" data-short="🏠">🏠 Dashboard</a>

    <a href="/dashboard/account/profile.php" data-short="👤">👤 Mein Profil</a>
    <a href="/dashboard/account/settings.php" data-short="⚙">⚙ Einstellungen</a>
    <a href="/dashboard/account/security.php" data-short="🔐">🔐 Sicherheit</a>
    <a href="/dashboard/account/socials.php" data-short="🌐">🌐 Socials</a>

    <div style="margin-top:15px;border-top:1px solid #333;"></div>

    <a href="/dashboard/users/list.php" data-short="👥">👥 Benutzer</a>
    <a href="/dashboard/roles/list.php" data-short="🛡">🛡 Rollen</a>
    <a href="/dashboard/permissions/list.php" data-short="⚙">⚙ Berechtigungen</a>

    <div style="margin-top:15px;border-top:1px solid #333;"></div>

    <a href="/dashboard/tickets/list.php" data-short="🎫">🎫 Tickets</a>
    <a href="/dashboard/moderation/logs.php" data-short="🚨">🚨 Moderation</a>
    <a href="/dashboard/events/list.php" data-short="📜">📜 Logs</a>

    <div style="margin-top:15px;border-top:1px solid #333;"></div>

    <a href="/dashboard/news/list.php" data-short="📰">📰 News</a>
    <a href="/dashboard/wiki/list.php" data-short="📚">📚 Wiki</a>
    <a href="/dashboard/forum/list.php" data-short="💬">💬 Forum</a>
    <a href="/dashboard/gallery/list.php" data-short="🖼">🖼 Galerie</a>
    <a href="/dashboard/creators/list.php" data-short="🎥">🎥 Creator</a>

    <div style="margin-top:15px;border-top:1px solid #333;"></div>

    <a href="/dashboard/logout.php" data-short="⛔" style="color:#ff3377;">⛔ Abmelden</a>
</div>

<!-- HEADER -->
<div class="header">

    <div></div>

    <div class="header-right">

        <!-- Notifications -->
        <a href="/dashboard/notifications/list.php" class="notif-icon">
            <img src="/assets/img/icons/bell.png" style="width:23px;">
            <?php if ($NOTIFICATION_COUNT > 0): ?>
                <span class="notif-badge"><?= $NOTIFICATION_COUNT ?></span>
            <?php endif; ?>
        </a>

        <!-- USER MENU -->
        <div class="user-menu">
            <img src="/uploads/users/avatar/<?= $AUTH_USER["avatar"] ?? "default.png" ?>">
            <div class="user-menu-name"><?= htmlspecialchars($AUTH_USER["name"]) ?></div>
        </div>

    </div>
</div>

<div class="main-content">
