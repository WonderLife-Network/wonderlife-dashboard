<?php
include "header.php";
include "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $role = $_POST["role"];
    $rank = $_POST["rank"];
    $avatar = $_POST["avatar"];

    $stmt = $db->prepare("INSERT INTO team (name, role, rank, avatar) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $role, $rank, $avatar]);

    echo "<script>alert('Team-Mitglied hinzugefügt!'); window.location='team.php';</script>";
}
?>

<h1 class="title">Neues Team-Mitglied</h1>

<form method="POST" class="form-box">

    <label>Name</label>
    <input type="text" name="name" required class="input">

    <label>Rolle</label>
    <input type="text" name="role" required class="input">

    <label>Sortierung (Rank)</label>
    <input type="number" name="rank" value="100" class="input">

    <label>Avatar URL (optional)</label>
    <input type="text" name="avatar" class="input">

    <button class="btn-glow" type="submit">Speichern</button>
</form>

<?php include "footer.php"; ?>
