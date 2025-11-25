<?php
$REQUIRED_PERMISSION = "wiki.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $slug = strtolower(preg_replace("/[^a-z0-9]+/", "-", $name));

    $stmt = $db->prepare("INSERT INTO wiki_categories (name, slug) VALUES (?,?)");
    $stmt->execute([$name, $slug]);

    echo "<script>alert('Kategorie erstellt!'); window.location='/dashboard/wiki/categories.php';</script>";
}
?>

<h2>Neue Wiki Kategorie</h2>

<form method="POST" class="form-box">

    <label>Kategoriename</label>
    <input type="text" name="name" class="input" required>

    <button class="btn-glow">Speichern</button>

</form>

<?php include "../footer.php"; ?>
