<?php
define("REQUEST", true);
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../libs/Database.php";
require_once __DIR__ . "/../libs/Language.php";
require_once __DIR__ . "/../libs/Function.php";
require_once __DIR__ . "/../libs/Database/User.php";
$HN = new DATABASE;
if ($HN->setting('status_card') != 'on') {
    exit('Status Card Off');
}
if (isset($_GET['request_id']) && isset($_GET['callback_sign'])) {
    $trans_id = check_string($_GET['trans_id']);
    $request_id = check_string($_GET['request_id']);
    $amount = check_string($_GET['amount']);
    $declared_value = check_string($_GET['declared_value']);
    $telco = check_string($_GET['telco']);
    $serial = check_string($_GET['serial']);
    $code = check_string($_GET['code']);
    $status = check_string($_GET['status']);
    $message = check_string($_GET['message']);
    $value = check_string($_GET['value']);
    $callback_sign = check_string($_GET['callback_sign']);
    if ($callback_sign != md5($HN->setting('partner_key_card') . $code . $serial)) {
        exit('Callback Sign Error');
    }
    if (!$row = $HN->get_row(" SELECT * FROM `cards` WHERE `trans_id` = '$request_id' AND `status` = 'pending'")) {
        exit('Request Id Error');
    }
    if ($status == 1) {
        if ($HN->setting('discount_napthe') == 0) {
            $price = $amount;
        } else {
            $price = $value - $value * $HN->setting('discount_napthe') / 100;
        }
        $HN->update("cards", array(
            'status' => 'completed',
            'price' => $price,
            'updated_time' => get_time()
        ), " `id` = '" . $row['id'] . "' ");
        $USER = new USER;
        $USER->plus_money($row['user_id'], $price, "Nạp thẻ cào Seri " . $row['serial'] . " - Pin " . $row['pin'] . " ");
        exit('Completed Callback');
    } else {
        $HN->update("cards", array(
            'status' => 'error',
            'price' => 0,
            'updated_time' => get_time(),
            'reason' => 'Thẻ cào không hợp lệ hoặc đã được sử dụng.'
        ), " `id` = '" . $row['id'] . "' ");
        exit('Card Error');
    }
}
