<?php
include "header.php";
include "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $content = $_POST["content"];

    $stmt = $db->prepare("INSERT INTO forum_threads (title, author, content) VALUES (?, ?, ?)");
    $stmt->execute([$title, $author, $content]);

    echo "<script>alert('Thema erstellt!'); window.location='forum.php';</script>";
}
?>

<h1 class="title">Neues Thema erstellen</h1>

<form method="POST" class="form-box">
    <label>Titel</label>
    <input type="text" name="title" required class="input">

    <label>Author / Name</label>
    <input type="text" name="author" required class="input">

    <label>Inhalt</label>
    <textarea name="content" required class="textarea"></textarea>

    <button class="btn-glow" type="submit">Thema speichern</button>
</form>

<?php include "footer.php"; ?>
