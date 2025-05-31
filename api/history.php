<?php
define("REQUEST", true);
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../libs/Database.php";
require_once __DIR__ . "/../libs/Language.php";
require_once __DIR__ . "/../libs/Function.php";
require_once __DIR__ . "/../libs/Database/User.php";
$HN = new DATABASE;
if (isset($_GET["token"]) && isset($_GET["limit"])) {
    $token = check_string($_GET['token']);
    $limit = check_string($_GET['limit']);
    $row = $HN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `status_ban` != 'on'");
    if ($row != false) {
        $user_id = $row['id'];
        $orders = $HN->get_list("SELECT * FROM `orders` WHERE `user_id` = '$user_id' ORDER BY `created_time` DESC LIMIT $limit");
        $data = [];
        foreach ($orders as $order) {
            $type = $HN->get_row("SELECT * FROM `services` WHERE `id` = '" . $order["service_id"] . "'")['type'];
            $data[] = [
                "trans_id" => $order["trans_id"],
                "type" => $type,
                "invite_code" => $order['invite_code'],
                "name" => $order["name"],
                "amount" => $order["amount"],
                "remaining" => $order["remaining"],
                "status" => $order["status"],
                "created_time" => $order["created_time"],
                "updated_time" => $order["updated_time"],
            ];
        }
        exit(json_encode(['status' => 'success', 'msg' => 'Lấy dữ liệu thành công!', 'data' => $data], JSON_PRETTY_PRINT));
    } else {
        exit(json_encode(['status' => 'error', 'msg' => __("TOKEN không hợp lệ")]));
    }
}
exit(json_encode(['status' => 'error', 'msg' => 'Không đủ dữ liệu!']));
