<?php
$REQUIRED_PERMISSION = "wiki.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$cats = $db->query("SELECT * FROM wiki_categories ORDER BY name ASC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $slug = strtolower(preg_replace("/[^a-z0-9]+/", "-", $title));
    $content = $_POST["content"];
    $cat_id = $_POST["category_id"] ?: NULL;
    $seo_title = $_POST["seo_title"];
    $seo_desc = $_POST["seo_description"];

    $stmt = $db->prepare("
        INSERT INTO wiki_pages (category_id, title, slug, content, seo_title, seo_description)
        VALUES (?,?,?,?,?,?)
    ");
    $stmt->execute([$cat_id, $title, $slug, $content, $seo_title, $seo_desc]);

    echo "<script>alert('Wiki Seite erstellt!'); window.location='/dashboard/wiki/list.php';</script>";
}
?>

<h2>Neue Wiki Seite</h2>

<form method="POST" class="form-box">

    <label>Kategorie</label>
    <select name="category_id" class="input">
        <option value="">— Keine —</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Titel</label>
    <input type="text" name="title" class="input" required>

    <label>Inhalt</label>
    <textarea id="editor" name="content" class="input textarea-large"></textarea>

    <h3>SEO</h3>
    <label>SEO Titel</label>
    <input type="text" name="seo_title" class="input">

    <label>SEO Beschreibung</label>
    <input type="text" name="seo_description" class="input">

    <button class="btn-glow">Speichern</button>

</form>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#editor',
    plugins: 'image lists link table code media',
    height: 400,
    menubar: false,
    toolbar: 'undo redo | bold italic underline | bullist numlist | link image table'
});
</script>

<?php include "../footer.php"; ?>
