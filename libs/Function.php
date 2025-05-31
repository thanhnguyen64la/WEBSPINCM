<?php
if (!defined('REQUEST')) {
    exit('The Request Not Found');
}
$HN = new DATABASE();
date_default_timezone_set($HN->setting("time_zone"));

use PHPMailer\PHPMailer\PHPMailer;

if ($HN->get_row(" SELECT * FROM `ip_block_log` WHERE `ip` = '" . get_ip() . "' AND `status_ban` = 'on' ")) {
    require_once __DIR__ . "/../views/common/block-ip.php";
    exit;
}
function base_url($url = '')
{
    $server = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
    if ($server == "http://localhost") {
        $server = "http://localhost/DOANWEB";
    }
    return $server . "/" . $url;
}
function client_url($url = '')
{
    $server = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
    if ($server == "http://localhost") {
        $server = "http://localhost/DOANWEB";
    }
    return $server . "/?module=client&action=" . $url;
}
function admin_url($url = '')
{
    $server = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
    if ($server == "http://localhost") {
        $server = "http://localhost/DOANWEB";
    }
    return $server . "/?module=admin&action=" . $url;
}
function redirect($url)
{
    header("Location: " . $url);
    exit;
}
function check_string($data)
{
    if ($data != null) {
        $data = trim($data);
        $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $data = preg_replace('/[^\p{L}\p{N}_\-@.\s]/u', '', $data);
        return $data;
    } else {
        return null;
    }
}
function get_time()
{
    return date("Y/m/d H:i:s", time());
}
function get_user($id, $row)
{
    $HN = new DATABASE();
    return $HN->get_row("SELECT * FROM `users` WHERE `id` = '" . $id . "' ")[$row];
}
function get_ip()
{
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip_address = $_SERVER["HTTP_CLIENT_IP"];
    } else {
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip_address = $_SERVER["REMOTE_ADDR"];
        }
    }
    if (isset(explode(",", $ip_address)[1])) {
        return explode(",", $ip_address)[0];
    }
    return check_string($ip_address);
}
function check_path($path)
{
    return preg_replace("/[^A-Za-z0-9_-]/", "", check_string($path));
}
function get_url()
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://";
    } else {
        $url = "http://";
    }
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
    return $url;
}
function get_currency()
{
    global $HN;
    if (isset($_COOKIE["currency"])) {
        $currency = check_string($_COOKIE["currency"]);
        $rowcurrency = $HN->get_row("SELECT * FROM `currencies` WHERE `id` = '" . $currency . "' AND `currency_status` = 'on' ");
        if ($rowcurrency) {
            return $rowcurrency["id"];
        }
    }
    $rowcurrency = $HN->get_row("SELECT * FROM `currencies` WHERE `currency_status_default` = 'on' ");
    if ($rowcurrency) {
        return $rowcurrency["id"];
    }
    return false;
}
function set_currency($id)
{
    global $HN;
    if ($row = $HN->get_row("SELECT * FROM `currencies` WHERE `id` = '" . $id . "' AND `currency_status` = 'on' ")) {
        $isSet = setcookie("currency", $row["id"], time() + 946080000, "/");
        if ($isSet) {
            return true;
        }
        return false;
    }
    return false;
}
function currency_default()
{
    $HN = new DATABASE();
    return $HN->get_row(" SELECT `currency_code` FROM `currencies` WHERE `currency_status` = 'on' AND `currency_status_default` = 'on'")["currency_code"];
}
function create_csrf_token()
{
    if (!isset($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = base64_encode(openssl_random_pseudo_bytes(32));
    }
    return $_SESSION["csrf_token"];
}
function check_email($data)
{
    if (preg_match('/^.+@.+$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}
function random($string, $int)
{
    return substr(str_shuffle($string), 0, $int);
}
function send_gmail($mail_received, $name_received, $topic, $content, $bcc = '', $path = '')
{
    $HN = new DATABASE();
    if ($HN->setting('smtp_password') != '' || $HN->setting('smtp_password') != null) {
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = "html";
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $HN->setting('smtp_email');
        $mail->Password = $HN->setting('smtp_password');
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom($HN->setting('smtp_email'), $bcc);
        $mail->addAddress($mail_received, $name_received);
        $mail->addAttachment($path);
        $mail->addReplyTo($HN->setting('smtp_email'), $bcc);
        $mail->isHTML(true);
        $mail->Subject = $topic;
        $mail->Body = $content;
        $mail->CharSet = 'UTF-8';
        $send = $mail->send();
        return $send;
    }
    return false;
}
function format_cash($number, $suffix = '')
{
    if ($number != null) {
        return number_format($number, 0, ',', '.') . "{$suffix}";
    } else {
        return 0;
    }
}
function format_currency($amount)
{
    $HN = new DATABASE();
    if ($amount != null) {
        if (isset($_COOKIE["currency"])) {
            $currency = check_string($_COOKIE["currency"]);
            $rowCurrency = $HN->get_row("SELECT * FROM `currencies` WHERE `id` = '" . $currency . "' AND `currency_status` = 'on' ");
            if ($rowCurrency) {
                if ($rowCurrency["currency_seperator"] == "comma") {
                    $seperator = ",";
                }
                if ($rowCurrency["currency_seperator"] == "space") {
                    $seperator = "";
                }
                if ($rowCurrency["currency_seperator"] == "dot") {
                    $seperator = ".";
                }
                return number_format($amount / $rowCurrency["currency_rate"], $rowCurrency["currency_decimal"], ".", $seperator) . $rowCurrency["currency_symbol"];
            }
        }
        $rowCurrency = $HN->get_row("SELECT * FROM `currencies` WHERE `currency_status_default` = 'on' ");
        if ($rowCurrency) {
            if ($rowCurrency["currency_seperator"] == "comma") {
                $seperator = ",";
            }
            if ($rowCurrency["currency_seperator"] == "space") {
                $seperator = "";
            }
            if ($rowCurrency["currency_seperator"] == "dot") {
                $seperator = ".";
            }
            return number_format($amount / $rowCurrency["currency_rate"], $rowCurrency["currency_decimal"], ".", $seperator) . $rowCurrency["currency_symbol"];
        }
        return format_cash($amount) . "đ";
    } else {
        return 0;
    }
}
function pagination($url, $start, $total, $kmess)
{
    $out[] = ' <div class="paging_simple_numbers"><ul class="pagination">';
    $neighbors = 2;
    $url = preg_replace('/(&|\?)page=\d+/', '', $url);
    if ($start >= $total)
        $start = max(0, $total - (($total % $kmess) == 0 ? $kmess : ($total % $kmess)));
    else
        $start = max(0, (int) $start - ((int) $start % (int) $kmess));
    $base_link = '<li class="paginate_button page-item previous "><a class="page-link" href="' . strtr($url, array('%' => '%%')) . '&page=%d">%s</a></li>';
    $out[] = $start == 0 ? '' : sprintf($base_link, $start / $kmess, 'Previous');
    if ($start > $kmess * $neighbors)
        $out[] = sprintf($base_link, 1, '1');
    if ($start > $kmess * ($neighbors + 1))
        $out[] = '<li class="paginate_button page-item previous disabled"><a class="page-link">...</a></li>';
    for ($nCont = $neighbors; $nCont >= 1; $nCont--)
        if ($start >= $kmess * $nCont) {
            $tmpStart = $start - $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
    $out[] = '<li class="paginate_button page-item previous active"><a class="page-link">' . ($start / $kmess + 1) . '</a></li>';
    $tmpMaxPages = (int) (($total - 1) / $kmess) * $kmess;
    for ($nCont = 1; $nCont <= $neighbors; $nCont++)
        if ($start + $kmess * $nCont <= $tmpMaxPages) {
            $tmpStart = $start + $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
    if ($start + $kmess * ($neighbors + 1) < $tmpMaxPages)
        $out[] = '<li class="paginate_button page-item previous disabled"><a class="page-link">...</a></li>';
    if ($start + $kmess * $neighbors < $tmpMaxPages)
        $out[] = sprintf($base_link, $tmpMaxPages / $kmess + 1, $tmpMaxPages / $kmess + 1);
    if ($start + $kmess < $total) {
        $display_page = ($start + $kmess) > $total ? $total : ($start / $kmess + 2);
        $out[] = sprintf($base_link, $display_page, 'Next');
    }
    $out[] = '</ul></div>';
    return implode('', $out);
}
function pagination_client($url, $start, $total, $kmess)
{
    $out[] = '<div class="paging_simple_numbers"><ul class="pagination">';
    $neighbors = 2;
    $url = preg_replace('/(&|\?)page=\d+/', '', $url);
    $url .= (strpos($url, '?') !== false ? '&' : '?') . 'page=';
    if ($start >= $total) {
        $start = max(0, $total - (($total % $kmess) == 0 ? $kmess : ($total % $kmess)));
    } else {
        $start = max(0, (int) $start - ((int) $start % (int) $kmess));
    }
    $base_link = '<li class="paginate_button page-item"><a class="page-link %s" href="' . strtr($url, array('%' => '%%')) . '%d">%s</a></li>';
    $out[] = $start == 0 ? '' : sprintf($base_link, '', $start / $kmess, '<i class="fas fa-long-arrow-alt-left"></i>');
    if ($start > $kmess * $neighbors) {
        $out[] = sprintf($base_link, '', 1, '1');
    }
    if ($start > $kmess * ($neighbors + 1)) {
        $out[] = '<li class="paginate_button page-item disabled"><a class="page-link">...</a></li>';
    }
    for ($nCont = $neighbors; $nCont >= 1; $nCont--) {
        if ($start >= $kmess * $nCont) {
            $tmpStart = $start - $kmess * $nCont;
            $out[] = sprintf($base_link, '', $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
    }
    $out[] = sprintf($base_link, 'active', $start / $kmess + 1, $start / $kmess + 1);
    $tmpMaxPages = (int) (($total - 1) / $kmess) * $kmess;
    for ($nCont = 1; $nCont <= $neighbors; $nCont++) {
        if ($start + $kmess * $nCont <= $tmpMaxPages) {
            $tmpStart = $start + $kmess * $nCont;
            $out[] = sprintf($base_link, '', $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
    }
    if ($start + $kmess * ($neighbors + 1) < $tmpMaxPages) {
        $out[] = '<li class="paginate_button page-item disabled"><a class="page-link">...</a></li>';
    }
    if ($start + $kmess * $neighbors < $tmpMaxPages) {
        $out[] = sprintf($base_link, '', $tmpMaxPages / $kmess + 1, $tmpMaxPages / $kmess + 1);
    }
    if ($start + $kmess < $total) {
        $out[] = sprintf($base_link, '', $start / $kmess + 2, '<i class="fas fa-long-arrow-alt-right"></i>');
    }
    $out[] = '</ul></div>';
    return implode('', $out);
}


function status_link($data)
{
    if ($data == 'completed') {
        $show = '<span class="badge bg-success">Hoàn tất</span>';
    } else if ($data == 'pending') {
        $show = '<span class="badge bg-info">Đang chạy</span>';
    } else if ($data == 'refund') {
        $show = '<span class="badge bg-warning">Hoàn tiền</span>';
    } else if ($data == 'huy') {
        $show = '<span class="badge bg-danger">Hủy</span>';
    } else if ($data == 'waitting') {
        $show = '<span class="badge bg-info">Đang chờ</span>';
    } else {
        $show = '<span class="badge bg-warning">Khác</span>';
    }
    return $show;
}
function format_time($time_ago)
{
    if (empty($time_ago) || strtotime($time_ago) === false) {
        return "--";
    }
    $time_ago = is_numeric($time_ago) ? $time_ago : strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    if ($time_elapsed < 0) {
        return "--";
    }
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    if ($seconds < 60) {
        return $seconds . " " . __("giây trước");
    }
    if ($minutes < 60) {
        return $minutes . " " . __("phút trước");
    }
    if ($hours < 24) {
        return $hours . " " . __("tiếng trước");
    }
    if ($days < 7) {
        if ($days == 1) {
            return __("Hôm qua");
        }
        return $days . " " . __("ngày trước");
    }
    if ($weeks < 4) {
        return $weeks . " " . __("tuần trước");
    }
    if ($months < 12) {
        return $months . " " . __("tháng trước");
    }
    return $years . " " . __("năm trước");
}
function check_invite($input)
{
    preg_match('/[~\/]([a-zA-Z0-9]{10})$/', $input, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    }
    if (preg_match('/^[a-zA-Z0-9]{10}$/', $input)) {
        return $input;
    }
    return false;
}
function get_row_real_time($table, $id, $row)
{
    global $HN;
    return $HN->get_row("SELECT `" . $row . "` FROM `" . $table . "` WHERE `id` = '" . $id . "' ")[$row];
}
// function send_tele_admin($message)
// {
//     $HN = new DATABASE;
//     if ($HN->setting('status_bot_telegram') == 'on') {
//         $token = $HN->setting('telegram_token');
//         $id = $HN->setting('telegram_chat_id');
//         if ($token != "" && $id != "") {
//             $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
//             $data = array(
//                 'chat_id' => $id,
//                 'text' => $message,
//                 'parse_mode' => 'Markdown',
//             );
//             $options = array(
//                 CURLOPT_URL => $url,
//                 CURLOPT_POST => 1,
//                 CURLOPT_POSTFIELDS => http_build_query($data),
//                 CURLOPT_RETURNTRANSFER => true,
//             );
//             $ch = curl_init();
//             curl_setopt_array($ch, $options);
//             $result = curl_exec($ch);
//             curl_close($ch);
//             if ($result === false) {
//                 return false;
//             }
//             $resultData = json_decode($result, true);
//             if ($resultData && $resultData['ok'] === true) {
//                 return true;
//             } else {
//                 return false;
//             }
//         }
//     }
//     return false;
// }

function send_tele_admin($message)
{
    $HN = new DATABASE;
    if ($HN->setting('status_bot_telegram') == 'on') {
        $token = $HN->setting('telegram_token');
        $id = $HN->setting('telegram_chat_id');
        if ($token != "" && $id != "") {
            $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
            $data = array(
                'chat_id' => $id,
                'text' => $message,
                'parse_mode' => 'Markdown',
            );

            $proxy = '66.225.234.51:26834'; // thay bằng proxy thật
            $proxy_userpwd = 'nguyen:nguyen'; // nếu proxy cần đăng nhập

            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => http_build_query($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_PROXY => $proxy,
                CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5, // hoặc CURLPROXY_HTTP
                CURLOPT_PROXYUSERPWD => $proxy_userpwd, // nếu có user/pass
            );

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            curl_close($ch);

            if ($result === false) {
                return false;
            }

            $resultData = json_decode($result, true);
            if ($resultData && $resultData['ok'] === true) {
                return true;
            } else {
                return false;
            }
        }
    }
    return false;
}

function active_sidebar_client($actions)
{
    foreach ($actions as $action) {
        if (isset($_GET['action']) && $_GET['action'] == $action) {
            return 'mobile-menu-active';
        }
    }
    return '';
}
function active_sidebar_admin($actions)
{
    foreach ($actions as $action) {
        if (isset($_GET['action']) && $_GET['action'] == $action) {
            return 'active';
        }
    }
    return '';
}
function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
function curl_get_2($url)
{
    $arrContextOptions = ["ssl" => ["verify_peer" => false, "verify_peer_name" => false]];
    return file_get_contents($url, false, stream_context_create($arrContextOptions));
}
function parse_bank_id($des, $MEMO_PREFIX)
{
    $re = "/" . $MEMO_PREFIX . "\\d+/im";
    preg_match_all($re, $des, $matches, PREG_SET_ORDER, 0);
    if (count($matches) == 0) {
        return NULL;
    }
    $orderCode = $matches[0][0];
    $prefixLength = strlen($MEMO_PREFIX);
    $orderId = (int) substr($orderCode, $prefixLength);
    return $orderId;
}
function check_promotion($amount)
{
    global $HN;
    foreach ($HN->get_list("SELECT * FROM `promotions` WHERE `bank_min` <= '" . $amount . "' ORDER by `bank_min` DESC ") as $promotion) {
        $received = $amount + $amount * $promotion["discount"] / 100;
        return $received;
    }
    return $amount;
}
function display_card($status)
{
    if ($status == "pending") {
        return '<span class="badge bg-info">' . __("Đang chờ xử lý") . "</span>";
    }
    if ($status == "completed") {
        return '<span class="badge bg-success">' . __("Thành công") . '</span>';
    }
    if ($status == "error") {
        return '<span class="badge bg-danger">' . __("Thất bại") . '</span>';
    }
    return '<span class="badge bg-warning">Khác</span>';
}
function check_format_card($type, $seri, $pin)
{
    $seri = strlen($seri);
    $pin = strlen($pin);
    $data = [];
    if ($type == "Viettel" || $type == "viettel" || $type == "VT" || $type == "VIETTEL") {
        if ($seri != 11 && $seri != 14) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 13 && $pin != 15) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Mobifone" || $type == "mobifone" || $type == "Mobi" || $type == "MOBIFONE") {
        if ($seri != 15) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 12) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "VNMB" || $type == "Vnmb" || $type == "VNM" || $type == "VNMOBI") {
        if ($seri != 16) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 12) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Vinaphone" || $type == "vinaphone" || $type == "Vina" || $type == "VINAPHONE") {
        if ($seri != 14) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 14) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Garena" || $type == "garena") {
        if ($seri != 9) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 16) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Zing" || $type == "zing" || $type == "ZING") {
        if ($seri != 12) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 9) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    if ($type == "Vcoin" || $type == "VTC") {
        if ($seri != 12) {
            $data = ["status" => false, "msg" => "Độ dài seri không phù hợp"];
            return $data;
        }
        if ($pin != 12) {
            $data = ["status" => false, "msg" => "Độ dài mã thẻ không phù hợp"];
            return $data;
        }
    }
    $data = ["status" => true, "msg" => "Success"];
    return $data;
}

function status_user($status)
{
    if ($status == 'on') {
        return '<span class="badge bg-danger">Banned</span>';
    } elseif ($status == 'off') {
        return '<span class="badge bg-success">Active</span>';
    }
}
function check_level_admin($status)
{
    if ($status == 'on') {
        return '<span class="badge bg-success">Có</span>';
    } elseif ($status == 'off') {
        return '<span class="badge bg-danger">Không</span>';
    }
}
function level_user($level)
{
    if ($level == 'user') {
        return '<span class="badge bg-success">User</span>';
    } elseif ($level == 'ctv') {
        return '<span class="badge bg-danger">Ctv</span>';
    } elseif ($level == 'daily') {
        return '<span class="badge bg-info">Đại lý</span>';
    } elseif ($level == 'npp') {
        return '<span class="badge bg-warning">Nhà phân phối</span>';
    } elseif ($level == 'tongkho') {
        return '<span class="badge bg-warning">Tổng kho</span>';
    }
}
function msg_success_link($text, $url, $time)
{
    echo '<script type="text/javascript">
        new Notify({
            status: "success",
            title: "Thành công",
            text:  "' . $text . '",
            autotimeout: ' . $time . ',
        })
        setTimeout(function(){ 
            window.location.href = "' . $url . '"; 
        }, ' . $time . ');
    </script>';
    die();
}
function msg_error_link($text, $url, $time)
{
    echo '<script type="text/javascript">
        new Notify({
            status: "error",
            title: "Thất bại",
            text:  "' . $text . '",
            autotimeout: ' . $time . ',
        })
        setTimeout(function(){ 
            window.location.href = "' . $url . '"; 
        }, ' . $time . ');
    </script>';
    die();
}
function display_service($status)
{
    if ($status == 'off') {
        return '<span class="badge bg-danger">Bảo trì</span>';
    } elseif ($status == 'on') {
        return '<span class="badge bg-success">Hoạt động</span>';
    } else {
        return '<span class="badge bg-warning">Khác</span>';
    }
}
function status_bank($status)
{
    if ($status == 'off') {
        return '<span class="badge bg-danger">Bảo trì</span>';
    } elseif ($status == 'on') {
        return '<span class="badge bg-success">Hoạt động</span>';
    } else {
        return '<span class="badge bg-warning">Khác</span>';
    }
}
function check_img($img)
{
    $filename = $_FILES[$img]['name'];
    $ext = explode(".", $filename);
    $ext = end($ext);
    $valid_ext = array("png", "jpeg", "jpg", "PNG", "JPEG", "JPG", "gif", "GIF");
    if (in_array($ext, $valid_ext)) {
        return true;
    }
}

function show_sidebar($action)
{
    foreach ($action as $row) {
        if (isset($_GET["action"]) && $_GET["action"] == $row) {
            return "active open";
        }
    }
    return "";
}

function display_mark($data)
{
    if ($data == 'on') {
        $show = '<span class="badge bg-success">Có</span>';
    } elseif ($data == 'off') {
        $show = '<span class="badge bg-danger">Không</span>';
    }
    return $show;
}
function display_status($data)
{
    if ($data == 'on') {
        $show = '<span class="badge bg-success">Hiển thị</span>';
    } elseif ($data == 'off') {
        $show = '<span class="badge bg-danger">Ẩn</span>';
    }
    return $show;
}
