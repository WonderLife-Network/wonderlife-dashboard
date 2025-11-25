<?php
include "header.php";
include "config.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM team WHERE id=?");
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) {
    die("<h1 class='title'>Mitglied nicht gefunden</h1>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $role = $_POST["role"];
    $rank = $_POST["rank"];
    $avatar = $_POST["avatar"];

    $stmt = $db->prepare("UPDATE team SET name=?, role=?, rank=?, avatar=? WHERE id=?");
    $stmt->execute([$name, $role, $rank, $avatar, $id]);

    echo "<script>alert('Team-Mitglied aktualisiert!'); window.location='team.php';</script>";
}
?>

<h1 class="title">Team-Mitglied bearbeiten</h1>

<form method="POST" class="form-box">

    <label>Name</label>
    <input type="text" name="name" class="input"
           value="<?php echo htmlspecialchars($t['name']); ?>" required>

    <label>Rolle</label>
    <input type="text" name="role" class="input"
           value="<?php echo htmlspecialchars($t['role']); ?>" required>

    <label>Sortierung (Rank)</label>
    <input type="number" name="rank"
           value="<?php echo htmlspecialchars($t['rank']); ?>"
           class="input">

    <label>Avatar URL</label>
    <input type="text" name="avatar"
           value="<?php echo htmlspecialchars($t['avatar']); ?>"
           class="input">

    <button class="btn-glow" type="submit">Änderungen speichern</button>
</form>

<?php include "footer.php"; ?>
