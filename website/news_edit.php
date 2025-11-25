<?php
include "header.php";
include "config.php";

$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM news WHERE id=?");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    die("News nicht gefunden");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];

    $stmt = $db->prepare("UPDATE news SET title=?, content=? WHERE id=?");
    $stmt->execute([$title, $content, $id]);

    echo "<script>alert('News aktualisiert!'); window.location='news.php';</script>";
}
?>

<h1 class="title">News bearbeiten</h1>

<form method="POST" class="form-box">
    <label>Titel</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required class="input">

    <label>Inhalt</label>
    <textarea name="content" required class="textarea"><?php echo htmlspecialchars($news['content']); ?></textarea>

    <button class="btn-glow" type="submit">Änderungen speichern</button>
</form>

<?php include "footer.php"; ?>
