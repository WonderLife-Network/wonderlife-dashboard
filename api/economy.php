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

        $stmt = $db->prepare("SELECT balance FROM economy_users WHERE user_id=?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        break;

    case "add":
        $id = $_POST["id"];
        $amount = $_POST["amount"];

        $stmt = $db->prepare("
            INSERT INTO economy_users (user_id, balance)
            VALUES (?,?)
            ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance)
        ");
        $stmt->execute([$id,$amount]);

        echo json_encode(["success"=>true]);
        break;

    default:
        echo json_encode(["error"=>"INVALID_ACTION"]);
        break;
}
