<?php
require "../../config.php";
header("Content-Type: application/json");

$mode = $_GET["mode"] ?? "list";

/* Nur Admin */
if ($API_SCOPE != "admin") {
    die(json_encode(["error"=>"FORBIDDEN_SCOPE"]));
}

/* Liste */
if ($mode === "list") {
    $stmt = $db->query("SELECT * FROM ticket_close_templates ORDER BY id DESC");
    echo json_encode(["status"=>"OK", "templates"=>$stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

/* Speichern */
if ($mode === "save") {
    $id = $_POST["id"] ?? null;
    $title = $_POST["title"] ?? "";
    $content = $_POST["content"] ?? "";

    if (!$id) {
        $stmt = $db->prepare("INSERT INTO ticket_close_templates (title, content) VALUES (?, ?)");
        $stmt->execute([$title, $content]);
    } else {
        $stmt = $db->prepare("UPDATE ticket_close_templates SET title=?, content=? WHERE id=?");
        $stmt->execute([$title, $content, $id]);
    }

    echo json_encode(["status"=>"OK"]);
}
