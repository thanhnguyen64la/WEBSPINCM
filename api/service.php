<?php
define("REQUEST", true);
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../libs/Database.php";
require_once __DIR__ . "/../libs/Language.php";
require_once __DIR__ . "/../libs/Function.php";
require_once __DIR__ . "/../libs/Database/User.php";
$HN = new DATABASE;
# API
if (isset($_GET["token"])) {
    $token = check_string($_GET['token']);
    $user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `status_ban` != 'on'");
    if ($user != false) {
        $data = [];
        $services = $HN->get_list("SELECT * FROM `services`");
        foreach ($services as $service) {
            $price = $service['price'];
            if ($user['level'] == 'ctv') {
                $price = $service['price'] - $service['price'] * $HN->setting('discount_ctv') / 100;
            } elseif ($user['level'] == 'daily') {
                $price = $service['price'] - $service['price'] * $HN->setting('discount_daily') / 100;
            } elseif ($user['level'] == 'npp') {
                $price = $service['price'] - $service['price'] * $HN->setting('discount_npp') / 100;
            } elseif ($user['level'] == 'tongkho') {
                $price = $service['price'] - $service['price'] * $HN->setting('discount_tongkho') / 100;
            }

            $data[] = [
                "type" => $service["type"],
                "price" => $price,
                "name" => $service["name"],
                "description" => $service["description"],
                "status" => $service["status"],
                // "api_server" => $status,
            ];
        }
        exit(json_encode(['status' => 'success', 'msg' => 'Lấy dữ liệu thành công!', 'data' => $data], JSON_PRETTY_PRINT));
    } else {
        exit(json_encode(['status' => 'error', 'msg' => __("TOKEN không hợp lệ")]));
    }
}
exit(json_encode(['status' => 'error', 'msg' => 'Không đủ dữ liệu!']));