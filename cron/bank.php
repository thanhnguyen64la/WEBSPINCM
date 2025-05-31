<?php
define("REQUEST", true);
require_once __DIR__ . "/../libs/Database.php";
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../libs/Language.php";
require_once __DIR__ . "/../libs/Function.php";
require_once __DIR__ . "/../libs/Database/User.php";
$HN = new DATABASE;
$USER = new USER;
$time_cron_bank = $HN->setting("check_time_cron_bank");
if ($time_cron_bank < time() && time() - $time_cron_bank < 5) {
    exit("Thao tác quá nhanh, vui lòng thử lại sau!");
}
$HN->update("settings", array('setting_value' => time()), "`setting_key` = 'check_time_cron_bank'");
foreach ($HN->get_list(" SELECT * FROM `banks` WHERE `status` = 'on' ") as $bank) {
    $password = $bank["api_password"];
    $token = $bank["api_token"];
    $stk = $bank["account_number"];
    if ($bank["short_name"] == "ACB" || $bank["short_name"] == "acb") {
        $result = curl_get("https://api.web2m.com/historyapiacb/$password/$stk/$token");
        $response = json_decode($result, true);
        if ($response !== null) {
            foreach ($response["transactions"] as $data) {
                $tid = check_string($data["transactionNumber"]);
                $description = check_string($data["description"]);
                $amount = check_string($data["amount"]);
                $user_id = parse_bank_id($description, $HN->setting("prefix_autobank"));
                if (!($amount < $HN->setting("bank_min") || $HN->setting("bank_max") < $amount)) {
                    if (($user = $HN->get_row(" SELECT * FROM `users` WHERE `id` = '" . $user_id . "' ")) && $HN->num_rows(" SELECT * FROM `payment_bank` WHERE `tid` = '" . $tid . "' AND `description` = '" . $description . "'  ") == 0) {
                        $received = check_promotion($amount);
                        $insertBank = $HN->insert("payment_bank", ["tid" => $tid, "method" => $bank["short_name"], "user_id" => $user["id"], "description" => $description, "amount" => $amount, "received" => $received, "created_time" => get_time()]);
                        if ($insertBank) {
                            $isPlus = $USER->plus_money($user["id"], $received, "Nạp tiền tự động qua " . $bank["short_name"] . " (#" . $tid . " - " . $description . " - " . $amount . ")");
                            if ($isPlus) {

                                echo '[<b style="color:green">-</b>] Xử lý thành công 1 hoá đơn.' . PHP_EOL;
                            }
                        }
                    }
                }
            }
        }
    } elseif ($bank["short_name"] == "MBBANK" || $bank["short_name"] == "MB" || $bank["short_name"] == 'mbbank') {
        $result = curl_get("https://api.web2m.com/historyapimb/$password/$stk/$token");
        $response = json_decode($result, true);
        if ($response !== null) {
            foreach ($response["data"] as $data) {
                $tid = check_string($data["refNo"]);
                $description = check_string($data["description"]);
                $amount = check_string($data["creditAmount"]);
                $user_id = parse_bank_id($description, $HN->setting("prefix_autobank"));
                if (!($amount < $HN->setting("bank_min") || $HN->setting("bank_max") < $amount)) {
                    if (($user = $HN->get_row(" SELECT * FROM `users` WHERE `id` = '" . $user_id . "' ")) && $HN->num_rows(" SELECT * FROM `payment_bank` WHERE `tid` = '" . $tid . "' AND `description` = '" . $description . "'  ") == 0) {
                        $received = check_promotion($amount);
                        $insertBank = $HN->insert("payment_bank", ["tid" => $tid, "method" => $bank["short_name"], "user_id" => $user["id"], "description" => $description, "amount" => $amount, "received" => $received, "created_time" => get_time()]);
                        if ($insertBank) {
                            $isPlus = $USER->plus_money($user["id"], $received, "Nạp tiền tự động qua " . $bank["short_name"] . " (#" . $tid . " - " . $description . " - " . $amount . ")");
                            if ($isPlus) {

                                echo '[<b style="color:green">-</b>] Xử lý thành công 1 hoá đơn.' . PHP_EOL;
                            }
                        }
                    }
                }
            }
        }
    } elseif ($bank["short_name"] == "VCB" || $bank["short_name"] == "Vietcombank") {
        $result = curl_get("https://api.web2m.com/historyapivcb/$password/$stk/$token");
        $response = json_decode($result, true);
        if ($response !== null) {
            foreach ($response["data"]['ChiTietGiaoDich'] as $data) {
                $tid = check_string($data["SoThamChieu"]);
                $description = check_string($data["MoTa"]);
                $amount = check_string(str_replace(',', '', $data['SoTienGhiCo']));
                $user_id = parse_bank_id($description, $HN->setting("prefix_autobank"));
                if (!($amount < $HN->setting("bank_min") || $HN->setting("bank_max") < $amount)) {
                    if (($user = $HN->get_row(" SELECT * FROM `users` WHERE `id` = '" . $user_id . "' ")) && $HN->num_rows(" SELECT * FROM `payment_bank` WHERE `tid` = '" . $tid . "' AND `description` = '" . $description . "'  ") == 0) {
                        $received = check_promotion($amount);
                        $insertBank = $HN->insert("payment_bank", ["tid" => $tid, "method" => $bank["short_name"], "user_id" => $user["id"], "description" => $description, "amount" => $amount, "received" => $received, "created_time" => get_time()]);
                        if ($insertBank) {
                            $isPlus = $USER->plus_money($user["id"], $received, "Nạp tiền tự động qua " . $bank["short_name"] . " (#" . $tid . " - " . $description . " - " . $amount . ")");
                            if ($isPlus) {

                                echo '[<b style="color:green">-</b>] Xử lý thành công 1 hoá đơn.' . PHP_EOL;
                            }
                        }
                    }
                }
            }
        }
    } elseif ($bank["short_name"] == "TCB" || $bank["short_name"] == "Techcombank") {
        $result = curl_get("https://api.web2m.com/historyapitcb/$password/$stk/$token");
        $response = json_decode($result, true);
        if ($response !== null) {
            foreach ($response["transactions"] as $data) {
                $tid = check_string($data["TransID"]);
                $description = check_string($data["Description"]);
                $amount = check_string(str_replace(',', '', $data['Amount']));
                $user_id = parse_bank_id($description, $HN->setting("prefix_autobank"));
                if (!($amount < $HN->setting("bank_min") || $HN->setting("bank_max") < $amount)) {
                    if (($user = $HN->get_row(" SELECT * FROM `users` WHERE `id` = '" . $user_id . "' ")) && $HN->num_rows(" SELECT * FROM `payment_bank` WHERE `tid` = '" . $tid . "' AND `description` = '" . $description . "'  ") == 0) {
                        $received = check_promotion($amount);
                        $insertBank = $HN->insert("payment_bank", ["tid" => $tid, "method" => $bank["short_name"], "user_id" => $user["id"], "description" => $description, "amount" => $amount, "received" => $received, "created_time" => get_time()]);
                        if ($insertBank) {
                            $isPlus = $USER->plus_money($user["id"], $received, "Nạp tiền tự động qua " . $bank["short_name"] . " (#" . $tid . " - " . $description . " - " . $amount . ")");
                            if ($isPlus) {

                                echo '[<b style="color:green">-</b>] Xử lý thành công 1 hoá đơn.' . PHP_EOL;
                            }
                        }
                    }
                }
            }
        }
    }
}
