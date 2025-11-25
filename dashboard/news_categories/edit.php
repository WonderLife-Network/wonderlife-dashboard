<?php
$REQUIRED_PERMISSION = "news.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM news_categories WHERE id=?");
$stmt->execute([$id]);
$cat = $stmt->fetch();

if (!$cat) die("<h2>Kategorie nicht gefunden</h2>");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $slug = strtolower(preg_replace("/[^a-z0-9]+/", "-", $name));

    $stmt = $db->prepare("UPDATE news_categories SET name=?, slug=? WHERE id=?");
    $stmt->execute([$name, $slug, $id]);

    echo "<script>alert('Kategorie aktualisiert!'); window.location='/dashboard/news_categories/list.php';</script>";
}
?>

<h2>Kategorie bearbeiten</h2>

<form class="form-box" method="POST">

    <label>Name</label>
    <input type="text" name="name" class="input"
           value="<?= htmlspecialchars($cat['name']) ?>" required>

    <button class="btn-glow" type="submit">Speichern</button>

</form>

<?php include "../footer.php"; ?>
