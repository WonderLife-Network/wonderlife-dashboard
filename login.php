<?php
include "config.php";
include "session_start.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Nutzer laden
    $stmt = $db->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {

        // Session Token generieren
        $token = bin2hex(random_bytes(32));

        // In DB speichern
        $stmt = $db->prepare("INSERT INTO user_sessions (user_id, session_token) VALUES (?, ?)");
        $stmt->execute([$user["id"], $token]);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["session_token"] = $token;

        header("Location: /dashboard/index.php");
        exit;

    } else {
        $error = "Login fehlgeschlagen!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WonderLife Login</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="login-body">

<div class="login-box">
    <h1>WonderLife Dashboard</h1>

    <?php if ($error): ?>
        <div class="login-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Benutzername</label>
        <input type="text" name="username" required class="input">

        <label>Passwort</label>
        <input type="password" name="password" required class="input">

        <button class="btn-glow" type="submit">Login</button>

    </form>
</div>

</body>
</html>
