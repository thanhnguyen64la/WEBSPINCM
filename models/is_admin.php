<?php
if (!defined("REQUEST")) {
    exit("The Request Not Found");
}
$HN = new DATABASE;
if (isset($_COOKIE["token"])) {
    $user = $HN->get_row(" SELECT * FROM `users` WHERE `token` = '" . check_string($_COOKIE["token"]) . "'  AND `admin` = 'on' ");
    if ($user == false) {
        redirect(client_url("login"));
        exit;
    }
    $_SESSION["admin_login"] = $user["token"];
}
if (!isset($_SESSION["admin_login"])) {
    redirect(base_url("client/login"));
} else {
    $user = $HN->get_row(" SELECT * FROM `users` WHERE `token` = '" . $_SESSION["admin_login"] . "'  AND `admin` = 'on' ");
    if ($user == false) {
        redirect(base_url("client/login"));
    }
    if ($user["status_ban"] == 'on') {
        redirect(base_url("common/banned"));
    }
    if ($user["money"] < 0) {
        $USER = new USER;
        $USER->set_status_ban($user["id"], "Số dư không đúng (Nghi vấn BUG)");
        redirect(base_url("common/banned"));
    }
    $HN->update("users", ["session_time" => time()], " `id` = '" . $user["id"] . "' ");
}
