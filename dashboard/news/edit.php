<?php
$REQUIRED_PERMISSION = "news.manage";
require "../auth_check.php";
require "../permission_check.php";
include "../header.php";

$id = $_GET["id"];

// News laden
$stmt = $db->prepare("
    SELECT news.*, users.name AS author_name
    FROM news
    JOIN users ON news.author_id = users.id
    WHERE news.id=?
");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) die("<h2>News nicht gefunden</h2>");

// Kategorien laden
$cats = $db->query("SELECT * FROM news_categories ORDER BY name ASC")->fetchAll();

// POST Verarbeitung
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $teaser = $_POST["teaser"];
    $content = $_POST["content"];
    $cat = $_POST["category_id"] ?: NULL;
    $status = $_POST["status"];
    $publish = $_POST["publish_date"];
    $seo_title = $_POST["seo_title"];
    $seo_desc = $_POST["seo_description"];

    $image_name = $news["image"];

    // NEUES BILD HOCHGELADEN?
    if (!empty($_FILES["image"]["name"])) {

        // Altes Bild löschen
        if ($image_name) {
            $old_path = $_SERVER["DOCUMENT_ROOT"] . "/uploads/news/" . $image_name;
            if (file_exists($old_path)) unlink($old_path);
        }

        // Neues speichern
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target = $_SERVER["DOCUMENT_ROOT"] . "/uploads/news/" . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);
    }

    // Update DB
    $stmt = $db->prepare("
        UPDATE news
        SET category_id=?, title=?, teaser=?, content=?, image=?, status=?, publish_date=?, seo_title=?, seo_description=?
        WHERE id=?
    ");
    $stmt->execute([
        $cat, $title, $teaser, $content, $image_name,
        $status, $publish, $seo_title, $seo_desc, $id
    ]);

    echo "<script>alert('News aktualisiert!'); window.location='/dashboard/news/list.php';</script>";
}
?>

<h2>News bearbeiten</h2>

<form method="POST" enctype="multipart/form-data" class="form-box">

    <label>Kategorie</label>
    <select name="category_id" class="input">
        <option value="">– Keine Kategorie –</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>" 
                <?= $news['category_id'] == $c['id'] ? "selected" : "" ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Titel</label>
    <input type="text" name="title" value="<?= htmlspecialchars($news['title']) ?>" class="input" required>

    <label>Kurzbeschreibung (Teaser)</label>
    <textarea name="teaser" class="input" maxlength="300"><?= 
        htmlspecialchars($news['teaser']) ?></textarea>

    <label>Inhalt</label>
    <textarea id="editor" name="content" class="input textarea-large"><?= 
        htmlspecialchars($news['content']) ?></textarea>

    <label>Aktuelles Bild</label>
    <?php if ($news['image']): ?>
        <img src="/uploads/news/<?= $news['image'] ?>" 
             style="width:200px;border-radius:10px;margin-bottom:10px;">
    <?php else: ?>
        <p>Kein Bild vorhanden</p>
    <?php endif; ?>

    <label>Neues Bild hochladen</label>
    <input type="file" name="image" class="input">

    <label>Status</label>
    <select name="status" class="input">
        <option value="draft"      <?= $news['status']=="draft" ? "selected" : "" ?>>Entwurf</option>
        <option value="published"  <?= $news['status']=="published" ? "selected" : "" ?>>Veröffentlicht</option>
        <option value="archived"   <?= $news['status']=="archived" ? "selected" : "" ?>>Archiviert</option>
    </select>

    <label>Veröffentlichungsdatum</label>
    <input type="datetime-local" name="publish_date" 
           value="<?= date("Y-m-d\TH:i", strtotime($news['publish_date'])) ?>" class="input">

    <h3>SEO</h3>

    <label>SEO Titel</label>
    <input type="text" name="seo_title" value="<?= htmlspecialchars($news['seo_title']) ?>" class="input">

    <label>SEO Beschreibung</label>
    <input type="text" name="seo_description" value="<?= htmlspecialchars($news['seo_description']) ?>" class="input">

    <label>Autor</label>
    <input type="text" value="<?= htmlspecialchars($news['author_name']) ?>" 
           class="input" disabled>

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
