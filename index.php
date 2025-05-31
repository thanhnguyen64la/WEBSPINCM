<?php
define("REQUEST", true);
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/libs/Database.php";
require_once __DIR__ . "/libs/Language.php";
require_once __DIR__ . "/libs/Function.php";
require_once __DIR__ . "/libs/Database/User.php";
$HN = new DATABASE;
if ($HN->setting("status") != 'on' && !isset($_SESSION["admin_login"])) {
    require_once __DIR__ . "/views/common/maintenance.php";
    exit;
}
if (isset($_GET["utm_source"])) {
    $utm_source = check_string($_GET["utm_source"]);
    setcookie("utm_source", $utm_source, time() + 2592000, "/");
}
$module = !empty($_GET["module"]) ? check_path($_GET["module"]) : "client";
$action = !empty($_GET["action"]) ? check_path($_GET["action"]) : "home";
if ($action == "footer" || $action == "header" || $action == "sidebar" || $action == "nav") {
    require_once __DIR__ . "/views/common/404.php";
    exit;
}
$path = "views/" . $module . "/" . $action . ".php";
if (file_exists($path)) {
    require_once __DIR__ . "/" . $path;
    exit;
}
require_once __DIR__ . "/views/common/404.php";
exit;
