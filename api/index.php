<?php
header("Content-Type: application/json");

// API Schlüssel System
require "auth.php";

// Module laden
$module = $_GET["module"] ?? "";
$action = $_GET["action"] ?? "";

// Router
switch ($module) {

    case "discord":
        require "discord_stats.php";
        break;

    case "fivem":
        require "fivem_stats.php";
        break;

    case "tickets":
        require "tickets.php";
        break;

    case "users":
        require "users.php";
        break;

    case "economy":
        require "economy.php";
        break;

    case "levels":
        require "levels.php";
        break;

    default:
        echo json_encode(["error" => "Unknown module"]);
        break;
}
