<?php
include "header.php";
include "config.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM wiki WHERE id=?");
$stmt->execute([$id]);
$page = $stmt->fetch();

if (!$page) {
    die("<h1 class='title'>Seite nicht gefunden</h1>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];

    $stmt = $db->prepare("UPDATE wiki SET title=?, content=? WHERE id=?");
    $stmt->execute([$title, $content, $id]);

    echo "<script>alert('Wiki-Seite aktualisiert!'); window.location='wiki_view.php?id=$id';</script>";
}
?>

<h1 class="title">Wiki-Seite bearbeiten</h1>

<form method="POST" class="form-box">

    <label>Seitentitel</label>
    <input type="text" name="title" class="input"
           value="<?php echo htmlspecialchars($page['title']); ?>" required>

    <label>Inhalt</label>
    <textarea name="content" class="textarea" required><?php
        echo htmlspecialchars($page['content']);
    ?></textarea>

    <button class="btn-glow" type="submit">Änderungen speichern</button>

</form>

<?php include "footer.php"; ?>
