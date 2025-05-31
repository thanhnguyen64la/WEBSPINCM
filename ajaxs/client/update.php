<?php

define("REQUEST", true);
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../libs/Database.php";
require_once __DIR__ . "/../../libs/Language.php";
require_once __DIR__ . "/../../libs/Function.php";
require_once __DIR__ . "/../../libs/Database/User.php";
$HN = new DATABASE;

use Detection\MobileDetect;

$Mobile_Detect = new MobileDetect();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST["action"] == "changeCurrency") {
    if (empty(check_string($_POST["id"]))) {
        exit(json_encode(["status" => "error", "msg" => __("Data does not exist")]));
    }
    $id = check_string($_POST["id"]);
    $row = $HN->get_row("SELECT * FROM `currencies` WHERE `id` = '" . $id . "' ");
    if ($row == false) {
        exit(json_encode(["status" => "error", "msg" => __("Data does not exist")]));
    }
    $isUpdate = set_currency($id);
    if ($isUpdate) {
        $data = json_encode(["status" => "success", "msg" => __("Successful currency change")]);
        exit($data);
    }
}
