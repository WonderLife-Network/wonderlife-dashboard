<?php
require "auth.php";
require "../dashboard/config.php";

if ($API_SCOPE != "admin" && $API_SCOPE != "write") {
    die(json_encode(["error" => "FORBIDDEN_SCOPE"]));
}

$action = $_GET["action"] ?? "";

switch ($action) {

    case "get":
        $id = $_GET["id"];

        $stmt = $db->prepare("SELECT xp, level FROM levels WHERE user_id=?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        break;

    case "add_xp":
        $id = $_POST["id"];
        $xp = $_POST["xp"];

        $stmt = $db->prepare("
            INSERT INTO levels (user_id,xp,level)
            VALUES (?,?,1)
            ON DUPLICATE KEY UPDATE xp = xp + VALUES(xp)
        ");
        $stmt->execute([$id,$xp]);

        echo json_encode(["success"=>true]);
        break;

    default:
        echo json_encode(["error"=>"INVALID_ACTION"]);
        break;
}
