<?php
include "header.php";
include "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];

    $stmt = $db->prepare("INSERT INTO news (title, content) VALUES (?, ?)");
    $stmt->execute([$title, $content]);

    echo "<script>alert('News erstellt!'); window.location='news.php';</script>";
}
?>

<h1 class="title">Neue News erstellen</h1>

<form method="POST" class="form-box">
    <label>Titel</label>
    <input type="text" name="title" required class="input">

    <label>Inhalt</label>
    <textarea name="content" required class="textarea"></textarea>

    <button class="btn-glow" type="submit">News speichern</button>
</form>

<?php include "footer.php"; ?>
