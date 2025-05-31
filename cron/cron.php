<?php
define("REQUEST", true);
require_once __DIR__ . "/../libs/Database.php";
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../libs/Language.php";
require_once __DIR__ . "/../libs/Function.php";
require_once __DIR__ . "/../libs/Database/User.php";
$HN = new DATABASE;
$USER = new USER;

# CRON XÓA LỊCH SỬ ĐƠN HÀNG THEO THỜI GIAN
if ($HN->setting("status_delete_order") == 1) {
    $timeToDelete = $HN->setting("time_delete_order");
    $currentTime = time();
    foreach ($HN->get_list("SELECT * FROM `orders`") as $delete) {
        $createDateTime = strtotime($delete['created_time']);
        if ($currentTime - $createDateTime >= $timeToDelete) {
            $HN->remove("orders", "`id` = '" . $delete['id'] . "'");
        }
    }
}
# CRON XÓA NHẬT KÝ THEO THỜI GIAN
if ($HN->setting("status_delete_log") == 1) {
    $timeToDelete = $HN->setting("time_delete_log");
    $currentTime = time();
    foreach ($HN->get_list("SELECT * FROM `logs`") as $delete) {
        $createDateTime = strtotime($delete['created_time']);
        if ($currentTime - $createDateTime >= $timeToDelete) {
            $HN->remove("logs", "`id` = '" . $delete['id'] . "'");
        }
    }
}
# CRON XÓA BIẾN ĐỘNG SỐ DƯ THEO THỜI GIAN
if ($HN->setting("status_delete_transaction") == 1) {
    $timeToDelete = $HN->setting("time_delete_transaction");
    $currentTime = time();
    foreach ($HN->get_list("SELECT * FROM `cash_flow`") as $delete) {
        $createDateTime = strtotime($delete['created_time']);
        if ($currentTime - $createDateTime >= $timeToDelete) {
            $HN->remove("cash_flow", "`id` = '" . $delete['id'] . "'");
        }
    }
}
