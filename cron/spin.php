<?php
define("REQUEST", true);
require_once __DIR__ . "/../libs/Database.php";
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../libs/Language.php";
require_once __DIR__ . "/../libs/Function.php";
require_once __DIR__ . "/../libs/Database/User.php";
$HN = new DATABASE;
$USER = new USER;

if ($HN->setting("status_api_muaspin") == 'on') {
    $token = $HN->setting("token_api_muaspin");
    # UPDATE HISTORY
    $result = curl_get("https://muaspin.com/api/history.php?token=$token&limit=300");
    $response = json_decode($result, true);
    if ($response !== null) {
        if ($response['status'] === 'success') {
            foreach ($response['data'] as $data) {
                $trans_id = $data['trans_id'];
                $order = $HN->get_row("SELECT * FROM `orders` WHERE `status` = 'pending' AND `api_server` = 'MUASPIN' AND `trans_id` = '$trans_id'");
                if ($order !== FALSE) {
                    if ($data['status'] == 'refund') {
                        $remaining = $data['remaining'];
                        $level_user = $HN->get_row("SELECT * FROM `users` WHERE `id` = '" . $order['user_id'] . "'")['level'];
                        $price_service = $HN->get_row("SELECT * FROM `services` WHERE `id` = '" . $order['service_id'] . "'")['price'];
                        if ($level_user == 'ctv') {
                            $price_refund = ($price_service - $price_service * $HN->setting('discount_ctv') / 100) * $remaining;
                        } elseif ($level_user == 'daily') {
                            $price_refund = ($price_service - $price_service * $HN->setting('discount_daily') / 100) * $remaining;
                        } elseif ($level_user == 'npp') {
                            $price_refund = ($price_service - $price_service * $HN->setting('discount_npp') / 100) * $remaining;
                        } elseif ($level_user == 'tongkho') {
                            $price_refund = ($price_service - $price_service * $HN->setting('discount_tongkho') / 100) * $remaining;
                        } else {
                            $price_refund = $price_service * $remaining;
                        }
                        $reason = "Hoàn tiền một phần đơn hàng #" . $trans_id . " - " . $data['invite_code'];
                        $isRefund = $USER->refund_money($order['user_id'], $price_refund, $reason);
                        if ($isRefund !== false) {
                            $HN->update("orders", array(
                                'name' => check_string($data['name']),
                                'status' => 'refund',
                                'remaining' => $remaining,
                                'updated_time' => get_time(),
                                'status_refund' => 'on'
                            ), " `id` = '" . $order['id'] . "' ");
                            $username = $HN->get_row("SELECT * FROM `users` WHERE `id` = '" . $order['user_id'] . "'")['username'];
                            $my_text = "*Thông báo đơn hàng hoàn tiền.*\n";
                            $my_text .= "*Username: *" . htmlspecialchars($username) . "\n";
                            $my_text .= "*Mã đơn hàng: *" . htmlspecialchars($trans_id) . "\n";
                            $my_text .= "*Mã invite: *" . htmlspecialchars($data['invite_code']) . "\n";
                            $my_text .= "*Số lượng hoàn: *" . htmlspecialchars($remaining) . "\n";
                            $my_text .= "*Số tiền hoàn: *" . number_format($price_refund) . " đ\n";
                            $my_text .= "*Thời gian: *" . get_time() . "\n";
                            send_tele_admin($my_text);
                        }
                    } elseif ($data['status'] == 'pending') {
                        $HN->update("orders", array(
                            'name' => check_string($data['name']),
                            'remaining' => $data['remaining'],
                            'updated_time' => get_time(),
                        ), " `id` = '" . $order['id'] . "' ");
                    } elseif ($data['status'] == 'completed') {
                        $HN->update("orders", array(
                            'name' => check_string($data['name']),
                            'status' => 'completed',
                            'remaining' => $data['remaining'],
                            'updated_time' => get_time(),
                        ), " `id` = '" . $order['id'] . "' ");
                    } else {
                        $HN->update("orders", array(
                            'status' => 'error',
                            'updated_time' => get_time(),
                        ), " `id` = '" . $order['id'] . "' ");
                    }
                }
            }
        }
    }

    # UPDATE MONEY
    $result = curl_get("https://muaspin.com/api/profile.php?token=$token");
    $response = json_decode($result, true);
    if ($response !== null) {
        if ($response['status'] === 'success') {
            foreach ($response['data'] as $data) {
                $money = $data['money'];
                if ($money <= 20000) {
                    $my_text = "*Số dư khả dụng bên API MUASPIN sắp hết.*\n";
                    $my_text .= "*Số dư khả dụng: *" . number_format($money) . " đ\n";
                    $my_text .= "*Thời gian: *" . get_time() . "\n";
                    send_tele_admin($my_text);
                }
                $HN->update("settings", array(
                    'setting_value' => $money,
                ), " `setting_key` = 'money_api_muaspin' ");
            }
        }
    }

    #UPDATE DỊCH VỤ
    $result = curl_get("https://muaspin.com/api/service.php?token=$token");
    $response = json_decode($result, true);
    if ($response !== null) {
        if ($response['status'] === 'success') {
            foreach ($response['data'] as $data) {
                if ($HN->num_rows("SELECT * FROM `services` WHERE `type` = '" . $data['type'] . "' AND `api_server` = 'MUASPIN'") == 0) {
                    $HN->insert(
                        "services",
                        array(
                            "name" => $data['name'],
                            "type" => $data['type'],
                            "price" => $data['rate'],
                            "api_price" => $data['rate'],
                            "status" => $data['status'] == 1 ? 'on' : 'off',
                            "api_server" => "MUASPIN"
                        )
                    );
                } else {
                    $service = $HN->get_row("SELECT * FROM `services` WHERE `api_server` = 'MUASPIN' AND `type` = '" . $data['type'] . "'");
                    $api_price = $service['api_price'];
                    if ($api_price != $data['rate']) {
                        $my_text = "*Thông báo API MUASPIN cập nhật giá tiền mới.*\n";
                        $my_text .= "*Tên dịch vụ: *" . $data['name'] . "\n";
                        $my_text .= "*Type dịch vụ: *" . $data['type'] . "\n";
                        $my_text .= "*Giá ban đầu: *" . number_format($api_price) . " đ\n";
                        $my_text .= "*Giá cập nhật: *" . number_format($data['rate']) . " đ\n";
                        send_tele_admin($my_text);
                    }
                    $update = $HN->update("services", array(
                        "status" => $data['status'] == 1 ? 'on' : 'off',
                        "api_price" => $data['rate'],
                    ), "`id` = '" . $service['id'] . "'");
                }
            }
        }
    }
}

