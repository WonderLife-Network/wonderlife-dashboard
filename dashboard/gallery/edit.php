<?php
$REQUIRED_PERMISSION = "gallery.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM gallery_images WHERE id=?");
$stmt->execute([$id]);
$img = $stmt->fetch();

if (!$img) die("<h2>Bild nicht gefunden</h2>");

$cats = $db->query("SELECT * FROM gallery_categories ORDER BY name ASC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $desc = $_POST["description"];
    $cat = $_POST["category_id"] ?: NULL;

    $file_name = $img["file_name"];

    // Neues Bild?
    if (!empty($_FILES["image"]["name"])) {

        // Altes löschen
        $old = $_SERVER["DOCUMENT_ROOT"] . "/uploads/gallery/" . $file_name;
        if (file_exists($old)) unlink($old);

        // Neues speichern
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target = $_SERVER["DOCUMENT_ROOT"] . "/uploads/gallery/" . $file_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);
    }

    $stmt = $db->prepare("
        UPDATE gallery_images
        SET category_id=?, title=?, description=?, file_name=?
        WHERE id=?
    ");
    $stmt->execute([$cat, $title, $desc, $file_name, $id]);

    echo "<script>alert('Bild aktualisiert!'); window.location='/dashboard/gallery/list.php';</script>";
}
?>

<h2>Bild bearbeiten</h2>

<form method="POST" enctype="multipart/form-data" class="form-box">

    <label>Kategorie</label>
    <select name="category_id" class="input">
        <option value="">— Keine —</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>" 
                <?= $img['category_id'] == $c['id'] ? "selected" : "" ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Titel</label>
    <input type="text" name="title" class="input" 
           value="<?= htmlspecialchars($img['title']) ?>">

    <label>Beschreibung</label>
    <textarea name="description" class="input"><?= htmlspecialchars($img['description']) ?></textarea>

    <label>Aktuelles Bild</label>
    <img src="/uploads/gallery/<?= $img['file_name'] ?>" style="width:200px;border-radius:10px;margin-bottom:10px;">

    <label>Neues Bild (optional)</label>
    <input type="file" name="image" class="input">

    <button class="btn-glow">Speichern</button>
</form>

<?php include "../footer.php"; ?>
