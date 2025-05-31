<?php

define("REQUEST", true);
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../libs/Database.php";
require_once __DIR__ . "/../../libs/Language.php";
require_once __DIR__ . "/../../libs/Function.php";
require_once __DIR__ . "/../../libs/Database/User.php";
$HN = new DATABASE;
$USER = new USER;

use Detection\MobileDetect;

$Mobile_Detect = new MobileDetect();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($HN->setting("status") != 'on' && !isset($_SESSION["admin_login"])) {
        exit(json_encode(["status" => "error", "msg" => __("Hệ thống đang bảo trì, vui lòng quay lại sau")]));
    }
    if (check_string($_POST["action"]) == 'order_spin') {
        $token = check_string($_POST['token']);
        if ($token == "" || $token == NULL) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng đăng nhập")]));
        }
        $user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `status_ban` = 'off' ");
        if ($user == false) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng đăng nhập")]));
        }
        $invite_code = check_invite($_POST['invite_code']);
        if ($invite_code == false) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập đúng định dạng link invite")]));
        }
        $type = check_string($_POST['type']);
        $check = check_string($_POST['check']);
        $api = "MUASPIN";
        $service = $HN->get_row("SELECT * FROM `services` WHERE `type` = '$type' AND `api_server` = '$api'");
        if ($service == false) {
            exit(json_encode(array('status' => 'error', 'msg' => __("Dịch vụ này không tồn tại trong hệ thống"))));
        }
        if ($service['status'] == 'off') {
            exit(json_encode(array('status' => 'error', 'msg' => __("Dịch vụ này đang bảo trì, vui lòng chọn dịch vụ khác"))));
        }
        $amount = check_string($_POST['amount']);
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
        $pay = $price * $amount;
        if (get_row_real_time('users', $user['id'], 'money') < $pay) {
            exit(json_encode(array('status' => 'error', 'msg' => __("Số dư của bạn không đủ, vui lòng nạp thêm để tiếp tục"))));
        }
        $api_server = $service['api_server'];
        if ($api_server == "MUASPIN") {
            if ($HN->setting("status_api_muaspin") == "on") {
                $token_api_muaspin = $HN->setting("token_api_muaspin");
                $money_api_muaspin = $HN->setting("money_api_muaspin");
                $api_price = $service['api_price'] * $amount;
                if ($api_price > $money_api_muaspin) {
                    $my_text = "*Số dư API MUASPIN đã hết.*\n";
                    $my_text .= "*Số dư còn lại: *" . number_format($money_api_muaspin) . " đ\n";
                    $my_text .= "*Thời gian: *" . get_time() . "\n";
                    send_tele_admin($my_text);
                    exit(json_encode(array('status' => 'error', 'msg' => __("Hệ thống đang quá tải, vui lòng thử lại sau ít phút"))));
                }
                $data = [
                    'action' => 'order_spin',
                    'type' => $type,
                    'amount' => $amount,
                    'invite_code' => $invite_code,
                    'token' => $token_api_muaspin
                ];
                $url = 'https://muaspin.com/ajaxs/client/create.php';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $response = json_decode($result, true);
                if ($response !== null) {
                    if ($response['status'] == 'success') {
                        $isMinus = $USER->minus_money($user['id'], $pay, __("Thanh toán đơn hàng ") . "#" . $response["trans_id"] . " - " . $invite_code);
                        if ($isMinus != false) {
                            if (get_row_real_time('users', $user['id'], 'money') < 0) {
                                $USER->set_status_ban($user['id'], __("Gian lận khi thanh toán đơn hàng"));
                                exit(json_encode(["status" => "error", "msg" => __("Tài khoản của bạn bị khóa, vì có hành vi gian lận")]));
                            }
                            $create = $HN->insert("orders", [
                                "trans_id" => $response["trans_id"],
                                'service_id' => $service['id'],
                                'invite_code' => $invite_code,
                                'status' => 'pending',
                                'created_time' => get_time(),
                                'user_id' => $user['id'],
                                'price' => $pay,
                                'api_price' => $api_price,
                                'amount' => $amount,
                                'remaining' => $amount,
                                'api_server' => "MUASPIN"
                            ]);
                            if ($create) {
                                $my_text = "*Thông báo đơn hàng mới.*\n";
                                $my_text .= "*Username: *" . htmlspecialchars($user['username']) . "\n";
                                $my_text .= "*Mã đơn hàng: *" . htmlspecialchars($response["trans_id"]) . "\n";
                                $my_text .= "*Mã invite: *" . htmlspecialchars($invite_code) . "\n";
                                $my_text .= "*Số lượng: *" . htmlspecialchars($amount) . "\n";
                                $my_text .= "*Đơn giá: *" . number_format($pay) . " đ\n";
                                $my_text .= "*Thời gian: *" . get_time() . "\n";
                                send_tele_admin($my_text);
                                exit(json_encode(array('status' => 'success', 'msg' => __("Tạo đơn hàng thành công"), 'trans_id' => $response["trans_id"])));
                            }
                        }
                    } else {
                        exit(json_encode(array('status' => 'error', 'msg' => $response["msg"])));
                    }
                } else {
                    exit(json_encode(array('status' => 'error', 'msg' => __("Dịch vụ này đang gặp sự cố, vui lòng liên hệ Admin"))));
                }
            } else {
                exit(json_encode(array('status' => 'error', 'msg' => __("Dịch vụ này đang gặp sự cố, vui lòng liên hệ Admin"))));
            }
        }
    }
    if (check_string($_POST["action"]) === 'nap_the') {
        if ($HN->setting("status_card") != 'on') {
            exit(json_encode(["status" => "error", "msg" => __("Chức năng nạp thẻ đang được tắt")]));
        }
        $token = check_string($_POST['token']);
        if ($token == "" || $token == NULL) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng đăng nhập")]));
        }
        $user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `status_ban` = 'off' ");
        if ($user == false) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng đăng nhập")]));
        }
        if (time() - $user["request_time"] < $config["max_load_time"]) {
            exit(json_encode(["status" => "error", "msg" => __("Bạn đang thao tác quá nhanh, vui lòng chờ")]));
        }
        if (empty($_POST["telco"])) {
            exit(json_encode(["status" => "error", "msg" => __("Vui lòng chọn nhà mạng")]));
        }
        if (empty($_POST["amount"])) {
            exit(json_encode(["status" => "error", "msg" => __("Vui lòng chọn mệnh giá cần nạp")]));
        }
        if ($_POST["amount"] <= 0) {
            exit(json_encode(["status" => "error", "msg" => __("Vui lòng chọn mệnh giá cần nạp")]));
        }
        if (empty($_POST["serial"])) {
            exit(json_encode(["status" => "error", "msg" => __("Vui lòng nhập serial thẻ")]));
        }
        if (empty($_POST["pin"])) {
            exit(json_encode(["status" => "error", "msg" => __("Vui lòng nhập mã thẻ")]));
        }
        $telco = check_string($_POST["telco"]);
        $amount = check_string($_POST["amount"]);
        $serial = check_string($_POST["serial"]);
        $pin = check_string($_POST["pin"]);
        if (!check_format_card($telco, $serial, $pin)["status"]) {
            exit(json_encode(["status" => "error", "msg" => check_format_card($telco, $serial, $pin)["msg"]]));
        }
        if (5 < $HN->num_rows(" SELECT * FROM `cards` WHERE `user_id` = '" . $user["id"] . "' AND `status` = 'pending'  ")) {
            exit(json_encode(["status" => "error", "msg" => __("Vui lòng không spam thẻ")]));
        }
        $trans_id = random("QWERTYUIOPASDFGHJKLZXCVBNM", 6) . time();
        $partner_id = $HN->setting('partner_id_card');
        $partner_key = $HN->setting('partner_key_card');
        $data_post = array(
            'telco' => $telco,
            'code' => $pin,
            'serial' => $serial,
            'amount' => $amount,
            'request_id' => $trans_id,
            'partner_id' => $partner_id,
            'sign' => md5($partner_key . $pin . $serial),
            'command' => 'charging'
        );
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://gachthe1s.com/chargingws/v2',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data_post),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        if ($data["status"] == 99) {
            $isInsert = $HN->insert("cards", ["trans_id" => $trans_id, "telco" => $telco, "amount" => $amount, "serial" => $serial, "pin" => $pin, "price" => 0, "user_id" => $user["id"], "status" => "pending", "reason" => "", "created_time" => get_time(), "updated_time" => get_time()]);
            if ($isInsert) {
                $HN->update("users", ["request_time" => time()], " `id` = '" . $user["id"] . "' ");
                $HN->insert("logs", ["user_id" => $user["id"], "ip" => get_ip(), "device" => $Mobile_Detect->getUserAgent(), "created_time	" => get_time(), "action" => "Thực hiện nạp thẻ Serial: " . $serial . " - Pin: " . $pin]);
                exit(json_encode(["status" => "success", "msg" => __("Gửi thẻ thành công")]));
            }
            exit(json_encode(["status" => "error", "msg" => __("Nạp thẻ thất bại, vui lòng liên hệ Admin")]));
        }
        exit(json_encode(["status" => "error", "msg" => $data["data"]["msg"]]));
    }

} else {
    exit(json_encode(array('status' => 'error', 'msg' => __('Request Does Not Exist'))));
}
