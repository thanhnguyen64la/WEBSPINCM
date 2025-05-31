<?php
if (!defined("REQUEST")) {
    exit("The Request Not Found");
}
$HN = new DATABASE;
if (isset($_COOKIE["token"])) {
    $user = $HN->get_row(" SELECT * FROM `users` WHERE `token` = '" . check_string($_COOKIE["token"]) . "' ");
    if ($user == false) {
        redirect(client_url("logout"));
        exit;
    }
    $_SESSION["login"] = $user["token"];
}
if (isset($_SESSION["login"])) {
    $user = $HN->get_row(" SELECT * FROM `users` WHERE `token` = '" . check_string($_SESSION["login"]) . "'  ");
    if ($user["money"] < 0) {
        $USER = new USER;
        $USER->set_status_ban($user["id"], "Số dư không đúng (Nghi vấn BUG)");
        redirect(base_url("common/banned"));
    }
    if ($user == false) {
        redirect(client_url("login"));
    }
    if ($user["status_ban"] == 'on') {
        redirect(base_url("common/banned"));
    }
    if ($user["token_forgot_password"] != NULL) {
        $HN->update("users", ["token_forgot_password" => NULL], " `id` = '" . $user["id"] . "' ");
    }
    $HN->update("users", ["session_time" => time()], " `id` = '" . $user["id"] . "' ");
} else {
    redirect(client_url("login"));
}
