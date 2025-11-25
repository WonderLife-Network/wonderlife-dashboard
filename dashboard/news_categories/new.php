<?php
$REQUIRED_PERMISSION = "news.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $slug = strtolower(preg_replace("/[^a-z0-9]+/", "-", $name));

    $stmt = $db->prepare("INSERT INTO news_categories (name, slug) VALUES (?,?)");
    $stmt->execute([$name, $slug]);

    echo "<script>alert('Kategorie erstellt!'); window.location='/dashboard/news_categories/list.php';</script>";
}
?>

<h2>Kategorie erstellen</h2>

<form class="form-box" method="POST">

    <label>Kategoriename</label>
    <input type="text" name="name" class="input" required>

    <button class="btn-glow" type="submit">Speichern</button>

</form>

<?php include "../footer.php"; ?>
