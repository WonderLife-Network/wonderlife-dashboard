<?php
$REQUIRED_PERMISSION = "gallery.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$cats = $db->query("SELECT * FROM gallery_categories ORDER BY name ASC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $desc = $_POST["description"];
    $cat = $_POST["category_id"] ?: NULL;

    if (!empty($_FILES["image"]["name"])) {
        $file = time() . "_" . basename($_FILES["image"]["name"]);
        $target = $_SERVER["DOCUMENT_ROOT"] . "/uploads/gallery/" . $file;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);

        $stmt = $db->prepare("
            INSERT INTO gallery_images (category_id, title, description, file_name)
            VALUES (?,?,?,?)
        ");
        $stmt->execute([$cat, $title, $desc, $file]);

        echo "<script>alert('Bild hochgeladen!'); window.location='/dashboard/gallery/list.php';</script>";
    }
}
?>

<h2>Neues Bild hochladen</h2>

<form method="POST" enctype="multipart/form-data" class="form-box">

    <label>Kategorie</label>
    <select name="category_id" class="input">
        <option value="">— Keine —</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Titel</label>
    <input type="text" name="title" class="input">

    <label>Beschreibung</label>
    <textarea name="description" class="input"></textarea>

    <label>Bild</label>
    <input type="file" name="image" class="input" required>

    <button class="btn-glow">Hochladen</button>
</form>

<?php include "../footer.php"; ?>
