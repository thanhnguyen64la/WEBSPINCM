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
    $data = json_encode(["status" => "error", "msg" => __("The Request Not Found")]);
    exit($data);
}
if (!isset($_POST["id"])) {
    $data = json_encode(["status" => "error", "msg" => __("The ID to delete does not exist")]);
    exit($data);
}
if ($_POST['action'] == 'removeService') {
    $id = check_string($_POST['id']);
    $row = $HN->get_row("SELECT * FROM `services` WHERE `id` = '$id' ");
    if ($row == false) {
        $data = json_encode([
            'status' => 'error',
            'msg' => 'ID services không tồn tại trong hệ thống'
        ]);
        exit($data);
    }
    if ($HN->num_rows("SELECT * FROM `orders` WHERE `service_id` = '$id'") > 0) {
        $data = json_encode(["status" => "error", "msg" => __("ID dịch vụ này đã có đơn hàng, vui lòng xóa đơn hàng trước")]);
        exit($data);
    }
    ;
    $isRemove = $HN->remove("services", " `id` = '$id' ");
    if ($isRemove) {
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'ip' => get_ip(),
            'device' => $Mobile_Detect->getUserAgent(),
            'created_time' => get_time(),
            'action' => 'Xoá dịch vụ (' . $row['name'] . ' ID ' . $row['id'] . ')'
        ]);
        $data = json_encode([
            'status' => 'success',
            'msg' => 'Xóa thành công'
        ]);
        die($data);
    } else {
        $data = json_encode(["status" => "error", "msg" => __("Xóa thất bại")]);
        exit($data);
    }
}
if ($_POST['action'] == 'removeOrder') {
    $id = check_string($_POST['id']);
    $row = $HN->get_row("SELECT * FROM `orders` WHERE `id` = '$id' ");
    if ($row == false) {
        $data = json_encode([
            'status' => 'error',
            'msg' => 'ID orders không tồn tại trong hệ thống'
        ]);
        exit($data);
    }
    $isRemove = $HN->remove("orders", " `id` = '$id' ");
    if ($isRemove) {
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'ip' => get_ip(),
            'device' => $Mobile_Detect->getUserAgent(),
            'created_time' => get_time(),
            'action' => 'Xoá đơn hàng ( Trans_id: ' . $row['trans_id'] . ' - Id ' . $row['id'] . ')'
        ]);
        $data = json_encode([
            'status' => 'success',
            'msg' => 'Xóa thành công'
        ]);
        die($data);
    } else {
        $data = json_encode(["status" => "error", "msg" => __("Xóa thất bại")]);
        exit($data);
    }
}
if ($_POST['action'] == 'removeBank') {
    $id = check_string($_POST['id']);
    $row = $HN->get_row("SELECT * FROM `banks` WHERE `id` = '$id' ");
    if ($row == false) {
        $data = json_encode([
            'status' => 'error',
            'msg' => 'ID bank không tồn tại trong hệ thống'
        ]);
        exit($data);
    }
    $isRemove = $HN->remove("banks", " `id` = '$id' ");
    if ($isRemove) {
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'ip' => get_ip(),
            'device' => $Mobile_Detect->getUserAgent(),
            'created_time' => get_time(),
            'action' => 'Xoá ngân hàng (' . 'Id ' . $row['id'] . ')'
        ]);
        $data = json_encode([
            'status' => 'success',
            'msg' => 'Xóa thành công'
        ]);
        die($data);
    } else {
        $data = json_encode(["status" => "error", "msg" => __("Xóa thất bại")]);
        exit($data);
    }
}
if ($_POST['action'] == 'removePromotion') {
    $id = check_string($_POST['id']);
    $row = $HN->get_row("SELECT * FROM `promotions` WHERE `id` = '$id' ");
    if ($row == false) {
        $data = json_encode([
            'status' => 'error',
            'msg' => 'ID khuyến mãi nạp tiền không tồn tại trong hệ thống'
        ]);
        exit($data);
    }
    $isRemove = $HN->remove("promotions", " `id` = '$id' ");
    if ($isRemove) {
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'ip' => get_ip(),
            'device' => $Mobile_Detect->getUserAgent(),
            'created_time' => get_time(),
            'action' => 'Xoá khuyến mãi nạp tiền (' . 'Id ' . $row['id'] . ')'
        ]);
        $data = json_encode([
            'status' => 'success',
            'msg' => 'Xóa thành công'
        ]);
        die($data);
    } else {
        $data = json_encode(["status" => "error", "msg" => __("Xóa thất bại")]);
        exit($data);
    }
}
if ($_POST['action'] == 'removeIP') {
    $id = check_string($_POST['id']);
    $row = $HN->get_row("SELECT * FROM `ip_block_log` WHERE `id` = '$id' ");
    if ($row == false) {
        $data = json_encode([
            'status' => 'error',
            'msg' => 'ID IP này không tồn tại trong hệ thống'
        ]);
        exit($data);
    }
    $isRemove = $HN->remove("ip_block_log", " `id` = '$id' ");
    if ($isRemove) {
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'ip' => get_ip(),
            'device' => $Mobile_Detect->getUserAgent(),
            'created_time' => get_time(),
            'action' => 'Xoá IP (' . 'Id ' . $row['id'] . ')'
        ]);
        $data = json_encode([
            'status' => 'success',
            'msg' => 'Xóa thành công'
        ]);
        die($data);
    } else {
        $data = json_encode(["status" => "error", "msg" => __("Xóa thất bại")]);
        exit($data);
    }
}

if ($_POST['action'] == 'removeCurrency') {
    $id = check_string($_POST['id']);
    $row = $HN->get_row("SELECT * FROM `currencies` WHERE `id` = '$id' ");
    if ($row == false) {
        $data = json_encode([
            'status' => 'error',
            'msg' => 'ID  này không tồn tại trong hệ thống'
        ]);
        exit($data);
    }
    $isRemove = $HN->remove("currencies", " `id` = '$id' ");
    if ($isRemove) {
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'ip' => get_ip(),
            'device' => $Mobile_Detect->getUserAgent(),
            'created_time' => get_time(),
            'action' => 'Xoá tiền tệ (' . 'Id ' . $row['id'] . ')'
        ]);
        $data = json_encode([
            'status' => 'success',
            'msg' => 'Xóa thành công'
        ]);
        die($data);
    } else {
        $data = json_encode(["status" => "error", "msg" => __("Xóa thất bại")]);
        exit($data);
    }
}
