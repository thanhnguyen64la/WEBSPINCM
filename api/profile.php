<?php
define("REQUEST", true);
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../libs/Database.php";
require_once __DIR__ . "/../libs/Language.php";
require_once __DIR__ . "/../libs/Function.php";
require_once __DIR__ . "/../libs/Database/User.php";
$HN = new DATABASE;
if (isset($_GET["token"])) {
    $token = check_string($_GET['token']);
    $row = $HN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `status_ban` != 'on'");
    if ($row != false) {
        $data = [];
        $data = [
            [
                "username" => $row["username"],
                "money" => $row["money"],
            ]
        ];
        exit(json_encode(['status' => 'success', 'msg' => 'Lấy dữ liệu thành công!', 'data' => $data], JSON_PRETTY_PRINT));
    } else {
        exit(json_encode(['status' => 'error', 'msg' => __("TOKEN không hợp lệ")]));
    }
}
exit(json_encode(['status' => 'error', 'msg' => 'Không đủ dữ liệu!']));
