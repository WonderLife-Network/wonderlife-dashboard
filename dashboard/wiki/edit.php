<?php
$REQUIRED_PERMISSION = "wiki.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM wiki_pages WHERE id=?");
$stmt->execute([$id]);
$page = $stmt->fetch();

if (!$page) die("<h2>Seite nicht gefunden</h2>");

$cats = $db->query("SELECT * FROM wiki_categories ORDER BY name ASC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $slug = strtolower(preg_replace("/[^a-z0-9]+/", "-", $title));
    $content = $_POST["content"];
    $cat = $_POST["category_id"] ?: NULL;
    $seo_title = $_POST["seo_title"];
    $seo_desc = $_POST["seo_description"];

    $stmt = $db->prepare("
        UPDATE wiki_pages
        SET category_id=?, title=?, slug=?, content=?, seo_title=?, seo_description=?
        WHERE id=?
    ");
    $stmt->execute([$cat, $title, $slug, $content, $seo_title, $seo_desc, $id]);

    echo "<script>alert('Seite aktualisiert!'); window.location='/dashboard/wiki/list.php';</script>";
}
?>

<h2>Wiki Seite bearbeiten</h2>

<form method="POST" class="form-box">

    <label>Kategorie</label>
    <select name="category_id" class="input">
        <option value="">— Keine —</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>" 
                <?= $page['category_id'] == $c['id'] ? "selected" : "" ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Titel</label>
    <input type="text" name="title" class="input" 
        value="<?= htmlspecialchars($page['title']) ?>" required>

    <label>Inhalt</label>
    <textarea id="editor" name="content" class="input textarea-large">
        <?= htmlspecialchars($page['content']) ?>
    </textarea>

    <h3>SEO</h3>

    <label>SEO Titel</label>
    <input type="text" name="seo_title" class="input" 
        value="<?= htmlspecialchars($page['seo_title']) ?>">

    <label>SEO Beschreibung</label>
    <input type="text" name="seo_description" class="input" 
        value="<?= htmlspecialchars($page['seo_description']) ?>">

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
