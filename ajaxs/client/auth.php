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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["action"])) {
    if ($HN->setting("status") != 'on' && !isset($_SESSION["admin_login"])) {
        exit(json_encode(["status" => "error", "msg" => __("Hệ thống đang bảo trì, vui lòng quay lại sau !")]));
    }
    if (check_string($_POST["action"]) === "Register") {
        $csrf_token = check_string($_POST["csrf_token"]);
        $username = check_string($_POST['username']);
        $email = check_string($_POST['email']);
        $password = check_string($_POST['password']);
        $repassword = check_string($_POST['repassword']);
        $csrf_token = check_string($_POST['csrf_token']);
        if (!isset($csrf_token) || $csrf_token !== check_string($_SESSION["csrf_token"])) {
            exit(json_encode(["status" => "error", "msg" => __("Mã CSRF không hợp lệ")]));
        }
        if (empty($username)) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập tài khoản")]));
        }
        if (empty($password)) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập mật khẩu")]));
        }
        if (empty($email)) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập email")]));
        }
        if (empty($repassword)) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập lại mật khẩu")]));
        }
        if ($password !== $repassword) {
            exit(json_encode(['status' => 'error', 'msg' => __("Xác minh mật khẩu không đúng")]));
        }
        if (check_email($email) != true) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập đúng định dạng email")]));
        }
        if ($HN->setting('status_reCAPTCHA') == 'on') {
            if (empty($_POST['recaptcha'])) {
                exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng xác minh captcha")]));
            }
            $recaptcha = check_string($_POST['recaptcha']);
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $HN->setting('reCAPTCHA_secret_key') . "&response=$recaptcha";
            $verify = file_get_contents($url);
            $captcha_success = json_decode($verify);
            if ($captcha_success->success == false) {
                exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng xác minh captcha")]));
            }
        }
        if ($HN->num_rows("SELECT * FROM `users` WHERE `username` = '$username' ") > 0) {
            exit(json_encode(['status' => 'error', 'msg' => __("Tên đăng nhập đã tồn tại trong hệ thống")]));
        }
        if ($HN->num_rows("SELECT * FROM `users` WHERE `email` = '$email' ") > 0) {
            exit(json_encode(['status' => 'error', 'msg' => __("Email đã tồn tại trong hệ thống")]));
        }
        if ($HN->num_rows("SELECT * FROM `users` WHERE `ip` = '" . get_ip() . "' ") >= $HN->setting('max_register_ip')) {
            exit(json_encode(['status' => 'error', 'msg' => __("IP của bạn đã đạt giới hạn đăng ký cho phép")]));
        }
        $token = random("QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789", 64) . time() . md5(random("QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789", 32));
        $isCreate = $HN->insert("users", [
            "utm_source" => isset($_COOKIE["utm_source"]) ? check_string($_COOKIE["utm_source"]) : "web",
            'username' => $username,
            'password' => md5($password),
            'email' => $email,
            'token' => $token,
            'ip' => get_ip(),
            'device' => $Mobile_Detect->getUserAgent(),
            'created_time' => get_time(),
            'updated_time' => get_time(),
            'session_time' => time(),
        ]);
        if ($isCreate) {
            $HN->insert("logs", [
                'user_id' => $HN->get_row("SELECT * FROM `users` WHERE `token` = '$token' ")['id'],
                'ip' => get_ip(),
                'device' => $Mobile_Detect->getUserAgent(),
                'created_time' => get_time(),
                'action' => __("[Action] Đăng ký tài khoản")
            ]);
            setcookie("token", $token, time() + $HN->setting('session_login'), "/");
            $_SESSION['login'] = $token;
            exit(json_encode(['status' => 'success', 'msg' => __("Đăng ký thành công")]));
        } else {
            exit(json_encode(['status' => 'error', 'msg' => __("Đăng ký thất bại, vui lòng thử lại")]));
        }
    }
    if (check_string($_POST["action"]) === "Login") {
        $csrf_token = check_string($_POST["csrf_token"]);
        $username = check_string($_POST['username']);
        $password = check_string($_POST['password']);
        if (!isset($csrf_token) || $csrf_token !== check_string($_SESSION["csrf_token"])) {
            exit(json_encode(["status" => "error", "msg" => __("Mã CSRF không hợp lệ")]));
        }
        if (empty($username)) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập tài khoản")]));
        }
        if (empty($password)) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập mật khẩu")]));
        }
        if ($HN->setting('status_reCAPTCHA') == 'on') {
            if (empty($_POST['recaptcha'])) {
                exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng xác minh captcha")]));
            }
            $recaptcha = check_string($_POST['recaptcha']);
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $HN->setting('reCAPTCHA_secret_key') . "&response=$recaptcha";
            $verify = file_get_contents($url);
            $captcha_success = json_decode($verify);
            if ($captcha_success->success == false) {
                exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng xác minh captcha")]));
            }
        }
        $user = $HN->get_row("SELECT * FROM `users` WHERE `username` = '" . $username . "' ");
        if ($user == false) {
            exit(json_encode(["status" => "error", "msg" => __("Thông tin đăng nhập không chính xác")]));
        }
        if ($user["request_time"] < time() && time() - $user["request_time"] < $config["max_load_time"]) {
            exit(json_encode(["status" => "error", "msg" => __("Bạn đang thao tác quá nhanh, vui lòng đợi")]));
        }
        if ($user['password'] != md5($password)) {
            if ($config["count_ip_login"] <= $user["attempt_login"]) {
                $HN->insert("ip_block_log", ["ip" => get_ip(), "attempt" => $user["attempt_login"], "created_time" => get_time(), "status_ban" => 'on', "reason" => __("[Warning] Đăng nhập thất bại nhiều lần")]);
                exit(json_encode(["status" => "ban_ip", "msg" => __("IP của bạn đã bị khóa, do đăng nhập thất bại nhiều lần")]));
            }
            if ($config["count_client_login"] <= $user["attempt_login"]) {
                $USER = new USER;
                $USER->set_status_ban($user["id"], __("[Warning] Đăng nhập thất bại nhiều lần"));
                exit(json_encode(["status" => "ban", "msg" => __("Tài khoản của bạn đã bị khóa, do đăng nhập thất bại nhiều lần")]));
            }
            $HN->plus("users", "attempt_login", 1, " `id` = '" . $user["id"] . "' ");
            exit(json_encode(['status' => 'error', 'msg' => __("Thông tin đăng nhập không chính xác")]));
        }
        if ($user['status_ban'] == 'on') {
            exit(json_encode(['status' => 'error', 'msg' => __("Tài khoản của bạn đã bị khóa")]));
        }
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'ip' => get_ip(),
            'device' => $Mobile_Detect->getUserAgent(),
            'created_time' => get_time(),
            'action' => __("[Action] Đăng nhập vào hệ thống")
        ]);
        $HN->update("users", [
            'ip' => get_ip(),
            'request_time' => time(),
            'session_time' => time(),
            'updated_time' => get_time(),
            'device' => $Mobile_Detect->getUserAgent()
        ], " `id` = '" . $user['id'] . "' ");
        setcookie("token", $user['token'], time() + $HN->setting('session_login'), "/");
        $_SESSION['login'] = $user['token'];
        exit(json_encode([
            'status' => 'success',
            'msg' => __("Đăng nhập thành công")
        ]));
    }
    if (check_string($_POST["action"]) === "ForgotPassword") {
        $csrf_token = check_string($_POST["csrf_token"]);
        if (!isset($csrf_token) || $csrf_token !== check_string($_SESSION["csrf_token"])) {
            exit(json_encode(["status" => "error", "msg" => __("Mã CSRF không hợp lệ")]));
        }
        if (empty(check_string($_POST['email']))) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập địa chỉ Email")]));
        }
        if ($HN->setting('status_reCAPTCHA') == 'on') {
            if (empty($_POST['recaptcha'])) {
                exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng xác minh captcha")]));
            }
            $recaptcha = check_string($_POST['recaptcha']);
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $HN->setting('reCAPTCHA_secret_key') . "&response=$recaptcha";
            $verify = file_get_contents($url);
            $captcha_success = json_decode($verify);
            if ($captcha_success->success == false) {
                exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng xác minh captcha")]));
            }
        }
        $user = $HN->get_row(" SELECT * FROM `users` WHERE `email` = '" . check_string($_POST['email']) . "' ");
        if ($user == false) {
            exit(json_encode(['status' => 'error', 'msg' => __("Địa chỉ Email này không tồn tại trong hệ thống")]));
        }
        if (time() - $user['forgot_password_time'] < 60) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng thử lại trong ít phút")]));
        }
        if ($HN->setting('smtp_password') == '' || $HN->setting('smtp_password') == null) {
            exit(json_encode(['status' => 'error', 'msg' => __("Website chưa được cấu hình SMTP, vui lòng liên hệ Admin")]));
        }
        $token = md5(random('QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 6) . time());
        $title = __("Xác nhận khôi phục mật khẩu !");
        $content = __('Bạn có yêu cầu khôi phục mật khẩu, nếu là bạn hãy truy cập liên kết bên dưới.');
        $link = base_url('?action=reset-password&token=' . $token);
        $footer = __('Nếu không phải bạn hãy liên hệ ADMIN để được hỗ trợ về bảo mật.');
        $sendMail = file_get_contents(base_url('libs/Mail/Notification.php'));
        $sendMail = str_replace('{title}', $title, $sendMail);
        $sendMail = str_replace('{content}', $content, $sendMail);
        $sendMail = str_replace('{link}', $link, $sendMail);
        $sendMail = str_replace('{footer}', $footer, $sendMail);
        $bcc = $HN->setting('title');
        $topic = __('Khôi phục lại mật khẩu') . ' - ' . $HN->setting('title');
        send_gmail($user['email'], $user['username'], $topic, $sendMail, $bcc);
        $isUpdate = $HN->update('users', [
            'token_forgot_password' => $token,
            'forgot_password_time' => time()
        ], " `id` = '" . $user['id'] . "' ");
        if ($isUpdate) {
            exit(json_encode(['status' => 'success', 'msg' => __("Vui lòng kiểm tra Email để hoàn tất quá trình khôi phục mật khẩu")]));
        } else {
            exit(json_encode(['status' => 'error', 'msg' => __("Xảy ra lỗi, liên hệ Developer")]));
        }
    }
    if (check_string($_POST["action"]) === "ChangePassword") {
        $csrf_token = check_string($_POST["csrf_token"]);
        if (!isset($csrf_token) || $csrf_token !== check_string($_SESSION["csrf_token"])) {
            exit(json_encode(["status" => "error", "msg" => __("Mã CSRF không hợp lệ")]));
        }
        if ($HN->setting('status_reCAPTCHA') == 'on') {
            if (empty($_POST['recaptcha'])) {
                exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng xác minh captcha")]));
            }
            $recaptcha = check_string($_POST['recaptcha']);
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $HN->setting('reCAPTCHA_secret_key') . "&response=$recaptcha";
            $verify = file_get_contents($url);
            $captcha_success = json_decode($verify);
            if ($captcha_success->success == false) {
                exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng xác minh captcha")]));
            }
        }
        $token = check_string($_POST["token"]);
        if (empty($token)) {
            exit(json_encode(["status" => "error", "msg" => __("Liên kết không hợp lệ")]));
        }
        $user = $HN->get_row("SELECT * FROM `users` WHERE `token_forgot_password` = '" . $token . "' AND `token_forgot_password` IS NOT NULL ");
        if ($user == false) {
            exit(json_encode(["status" => "error", "msg" => __("Liên kết không tồn tại")]));
        }
        if (empty($user["token_forgot_password"])) {
            exit(json_encode(["status" => "error", "msg" => __("Liên kết không tồn tại")]));
        }
        if (empty(check_string($_POST['newpassword']))) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập mật khẩu mới")]));
        }
        if (empty(check_string($_POST['renewpassword']))) {
            exit(json_encode(['status' => 'error', 'msg' => __("Vui lòng nhập lại mật khẩu")]));
        }
        if (check_string($_POST["renewpassword"]) != check_string($_POST["newpassword"])) {
            exit(json_encode(["status" => "error", "msg" => __("Xác nhận mật khẩu không chính xác")]));
        }
        $token = random("QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789", 64) . time() . md5(random("QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789", 32));
        $isUpdate = $HN->update("users", [
            'token_forgot_password' => NULL,
            'password' => isset($_POST['newpassword']) ? md5(check_string($_POST['newpassword'])) : null,
            'token' => $token
        ], " `id` = '" . $user['id'] . "' ");
        if ($isUpdate) {
            $HN->insert("logs", [
                'user_id' => $user['id'],
                'ip' => get_ip(),
                'device' => $Mobile_Detect->getUserAgent(),
                'created_time' => get_time(),
                'action' => __('[Action] Thay đổi mật khẩu')
            ]);
            exit(json_encode(['status' => 'success', 'msg' => __('Thay đổi mật khẩu thành công')]));
        } else {
            exit(json_encode(['status' => 'error', 'msg' => __('Thay đổi mật khẩu thất bại, vui lòng thử lại')]));
        }
    }
    if (check_string($_POST["action"]) === "ChangeProfile") {
        if (empty(check_string($_POST['token']))) {
            exit(json_encode(['status' => 'error', 'msg' => __('Vui lòng đăng nhập')]));
        }
        if (!$user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '" . check_string($_POST['token']) . "' ")) {
            exit(json_encode(['status' => 'error', 'msg' => __('Vui lòng đăng nhập')]));
        }
        $isUpdate = $HN->update("users", [
            'phone_number' => isset($_POST['phone_number']) ? check_string($_POST['phone_number']) : null,
            'full_name' => isset($_POST['full_name']) ? check_string($_POST['full_name']) : null,
            'telegram_chat_id' => isset($_POST['telegram_chat_id']) ? check_string($_POST['telegram_chat_id']) : null
        ], " `token` = '" . check_string($_POST['token']) . "' ");
        if ($isUpdate) {
            $HN->insert("logs", [
                'user_id' => $user['id'],
                'ip' => get_ip(),
                'device' => $Mobile_Detect->getUserAgent(),
                'created_time' => get_time(),
                'action' => __("[Action] Thay đổi thông tin cá nhân")
            ]);
            exit(json_encode(['status' => 'success', 'msg' => __('Cập nhật thông tin thành công')]));
        }
        exit(json_encode(['status' => 'error', 'msg' => __('Cập nhật thông tin thất bại')]));
    }
    if (check_string($_POST["action"]) === "ChangePasswordProfile") {
        if (empty(check_string($_POST['token']))) {
            exit(json_encode(['status' => 'error', 'msg' => __('Vui lòng đăng nhập')]));
        }
        if (!$user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '" . check_string($_POST['token']) . "' ")) {
            exit(json_encode(['status' => 'error', 'msg' => __('Vui lòng đăng nhập')]));
        }
        if (empty(check_string($_POST["password"]))) {
            exit(json_encode(["status" => "error", "msg" => __("Vui lòng nhập mật khẩu hiện tại")]));
        }
        if (empty(check_string($_POST["newpassword"]))) {
            exit(json_encode(["status" => "error", "msg" => __("Vui lòng nhập mật khẩu mới")]));
        }
        if (strlen(check_string($_POST["newpassword"])) < 5) {
            exit(json_encode(["status" => "error", "msg" => __("Mật khẩu mới quá ngắn")]));
        }
        if (empty(check_string($_POST["renewpassword"]))) {
            exit(json_encode(["status" => "error", "msg" => __("Xác nhận mật khẩu không chính xác")]));
        }
        if (check_string($_POST["renewpassword"]) != check_string($_POST["newpassword"])) {
            exit(json_encode(["status" => "error", "msg" => __("Xác nhận mật khẩu không chính xác")]));
        }
        $password = check_string($_POST["password"]);
        if ($user["password"] != md5($password)) {
            exit(json_encode(["status" => "error", "msg" => __("Mật khẩu hiện tại không đúng")]));
        }
        $token = random("QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789", 64) . time() . md5(random("QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789", 32));
        $isUpdate = $HN->update("users", [
            "password" => md5(check_string($_POST["newpassword"])),
            "token" => $token
        ], " `token` = '" . check_string($_POST["token"]) . "' ");
        if ($isUpdate) {
            $HN->insert("logs", [
                'user_id' => $user['id'],
                'ip' => get_ip(),
                'device' => $Mobile_Detect->getUserAgent(),
                'created_time' => get_time(),
                'action' => __("[Action] Thay đổi mật khẩu")
            ]);
            exit(json_encode(["status" => "success", "msg" => __("Thay đổi mật khẩu thành công")]));
        }
        exit(json_encode(["status" => "error", "msg" => __("Thay đổi mật khẩu thất bại")]));
    }

}
