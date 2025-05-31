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
if (!isset($_POST['action'])) {
    exit(json_encode(array('status' => 'error', 'msg' => __('Không đủ dữ liệu'))));
}
if (check_string($_POST['action']) === 'totalPayment_Spin') {
    $amount = check_string($_POST['amount']);
    $type = check_string($_POST['type']);
    $check = check_string($_POST['check']);
    $api = "MUASPIN";
    $service = $HN->get_row("SELECT * FROM `services` WHERE `type` = '$type' AND `api_server` = '$api'");
    if ($service == false) {
        exit(json_encode(array('status' => 'error', 'message' => __('Dịch vụ không tồn tại trong hệ thống'))));
    }
    $price = $service['price'];
    if (isset($_POST['token']) && (check_string($_POST['token']) != null || check_string($_POST['token']) != '')) {
        $user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '" . check_string($_POST['token']) . "' AND `status_ban` = 'off' ");
        if ($user != false) {
            if ($user['level'] == 'ctv') {
                $price = $service['price'] - $service['price'] * $HN->setting('discount_ctv') / 100;
            } elseif ($user['level'] == 'daily') {
                $price = $service['price'] - $service['price'] * $HN->setting('discount_daily') / 100;
            } elseif ($user['level'] == 'npp') {
                $price = $service['price'] - $service['price'] * $HN->setting('discount_npp') / 100;
            } elseif ($user['level'] == 'tongkho') {
                $price = $service['price'] - $service['price'] * $HN->setting('discount_tongkho') / 100;
            }
        }
    }
    $pay = $amount * $price;
    $pay_format = format_currency($pay);
    exit(json_encode(array('status' => 'success', 'type' => $service['type'], 'desc' => $service['description'], 'pay' => $pay_format)));
}
if (check_string($_POST['action']) === 'pagination_order') {
    $limit = isset($_POST['limit']) ? intval(check_string($_POST['limit'])) : 10;
    $page = isset($_POST['page']) ? intval(check_string($_POST['page'])) : 1;
    $shortByDate = isset($_POST['shortByDate']) ? check_string($_POST['shortByDate']) : '';
    $invite_code = isset($_POST['invite_code']) ? check_invite($_POST['invite_code']) : '';
    $time = isset($_POST['time']) ? check_string($_POST['time']) : '';
    $from = ($page - 1) * $limit;
    $token = check_string($_POST['token']);
    $user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `status_ban` = 'off' ");
    if ($user !== false) {
        $where = " `user_id` = '" . $user["id"] . "' ";
    } else {
        $where = " `id` > 0 ";
    }
    $sql = [];
    if (!empty($invite_code)) {
        $sql[] = "`invite_code` LIKE '%$invite_code%'";
    }
    if (!empty($time)) {
        if (strpos($time, ' to ') !== false) {
            list($startDate, $endDate) = explode(' to ', $time);
        } else {
            $startDate = $endDate = $time;
        }
        $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
        $sql[] = "`created_time` BETWEEN '$startDate' AND '$endDate'";
    }
    if ($shortByDate) {
        if ($shortByDate == 1) {
            $sql[] = "DATE(`created_time`) = CURDATE()";
        } elseif ($shortByDate == 2) {
            $sql[] = "YEARWEEK(`created_time`, 1) = YEARWEEK(CURDATE(), 1)";
        } elseif ($shortByDate == 3) {
            $sql[] = "MONTH(`created_time`) = MONTH(CURDATE()) AND YEAR(`created_time`) = YEAR(CURDATE())";
        }
    }
    if (!empty($sql)) {
        $sqlReal = " AND " . implode(' AND ', $sql);
    } else {
        $sqlReal = '';
    }
    $totalRecords = $HN->num_rows("SELECT * FROM `orders` WHERE $where $sqlReal ORDER BY `id` DESC");
    $totalPages = ceil($totalRecords / $limit);
    $orders = $HN->get_list("SELECT * FROM `orders` WHERE $where $sqlReal ORDER BY `id` DESC LIMIT $limit OFFSET $from");
    function getNameService($id)
    {
        global $HN;
        return $service_name = $HN->get_row("SELECT * FROM `services` WHERE `id` = $id")['name'];
    }
    $arrOrder = [];
    foreach ($orders as $order) {
        $service_name = getNameService($order['service_id']);
        $name = $order['name'] !== null ? $order['name'] : "";
        $status = status_link($order['status']);
        $price = format_currency($order['price']);
        $created_time = $order['created_time'];
        $arrOrder[] = [
            'id' => $order['id'],
            'invite_code' => $order['invite_code'],
            'service_name' => $service_name,
            'name' => $name,
            'amount' => $order['amount'],
            'remaining' => $order['remaining'],
            'status' => $status,
            'price' => $price,
            'created_time' => $created_time,
            'format_time' => format_time($created_time)
        ];
    }
    exit(json_encode(array('totalRecords' => $totalRecords, 'totalPages' => $totalPages, 'orders' => $arrOrder)));
}
