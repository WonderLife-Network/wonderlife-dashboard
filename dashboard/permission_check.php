<?php
if (!isset($REQUIRED_PERMISSION)) {
    die("Permission not defined.");
}

$stmt = $db->prepare("
    SELECT COUNT(*) as cnt
    FROM role_permissions
    JOIN permissions ON permissions.id = role_permissions.permission_id
    WHERE role_permissions.role_id = ?
    AND permissions.permission_key = ?
");
$stmt->execute([$AUTH_ROLE["id"], $REQUIRED_PERMISSION]);
$perm = $stmt->fetch();

if ($perm["cnt"] == 0) {
    die("<h1>Zugriff verweigert</h1>");
}
