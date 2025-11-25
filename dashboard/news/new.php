<?php
$REQUIRED_PERMISSION = "news.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$cats = $db->query("SELECT * FROM news_categories ORDER BY name ASC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $teaser = $_POST["teaser"];
    $content = $_POST["content"];
    $cat = $_POST["category_id"] ?: NULL;
    $status = $_POST["status"];
    $publish = $_POST["publish_date"];
    $seo_title = $_POST["seo_title"];
    $seo_desc = $_POST["seo_description"];
    $author = $AUTH_USER["id"];

    // Bild-Upload
    $image_name = NULL;
    if (!empty($_FILES["image"]["name"])) {
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target = $_SERVER["DOCUMENT_ROOT"] . "/uploads/news/" . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);
    }

    $stmt = $db->prepare("
        INSERT INTO news (category_id, title, teaser, content, image, status, publish_date, seo_title, seo_description, author_id)
        VALUES (?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([$cat, $title, $teaser, $content, $image_name, $status, $publish, $seo_title, $seo_desc, $author]);

    echo "<script>alert('News erstellt!'); window.location='/dashboard/news/list.php';</script>";
}
?>

<h2>Neue News erstellen</h2>

<form method="POST" enctype="multipart/form-data" class="form-box">

    <label>Kategorie</label>
    <select name="category_id" class="input">
        <option value="">– Keine Kategorie –</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Titel</label>
    <input type="text" name="title" class="input" required>

    <label>Kurzbeschreibung (Teaser)</label>
    <textarea name="teaser" class="input" maxlength="300"></textarea>

    <label>Inhalt</label>
    <textarea id="editor" name="content" class="input textarea-large"></textarea>

    <label>Header-Bild</label>
    <input type="file" name="image" class="input">

    <label>Status</label>
    <select name="status" class="input">
        <option value="draft">Entwurf</option>
        <option value="published">Veröffentlicht</option>
        <option value="archived">Archiviert</option>
    </select>

    <label>Veröffentlichungsdatum</label>
    <input type="datetime-local" name="publish_date" class="input" value="<?= date("Y-m-d\TH:i") ?>">

    <h3>SEO</h3>

    <label>SEO Titel</label>
    <input type="text" name="seo_title" class="input">

    <label>SEO Beschreibung</label>
    <input type="text" name="seo_description" class="input">

    <button class="btn-glow" type="submit">Speichern</button>

</form>

<!-- TinyMCE Editor -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#editor',
    plugins: 'image autolink lists link table media',
    height: 400,
    menubar: false,
    toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media'
});
</script>

<?php include "../footer.php"; ?>
