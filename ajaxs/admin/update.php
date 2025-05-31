<?php

define("REQUEST", true);
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../libs/Database.php";
require_once __DIR__ . "/../../libs/Language.php";
require_once __DIR__ . "/../../libs/Function.php";
require_once __DIR__ . "/../../models/is_admin.php";
$HN = new DATABASE;

use Detection\MobileDetect;

$Mobile_Detect = new MobileDetect();
if (!isset($_POST["action"])) {
    $data = json_encode(["status" => "error", "msg" => "The Request Not Found"]);
    exit($data);
}
if ($_POST["action"] == "setDefaultCurrency") {
    $id = check_string($_POST["id"]);
    $row = $HN->get_row("SELECT * FROM `currencies` WHERE `id` = '" . $id . "' ");
    if (!$row) {
        $data = json_encode(["status" => "error", "msg" => "ID tiền tệ không tồn tại trong hệ thống"]);
        exit($data);
    }
    $HN->update("currencies", ["currency_status_default" => 'off'], " `id` > 0 ");
    $isUpdate = $HN->update("currencies", ["currency_status_default	" => 'on'], " `id` = '" . $id . "' ");
    if ($isUpdate) {
        $data = json_encode(["status" => "success", "msg" => "Thay đổi trạng thái tiền tệ thành công"]);
        exit($data);
    }
    exit(json_encode(["status" => "error", "msg" => "Cập nhật thất bại"]));
}
