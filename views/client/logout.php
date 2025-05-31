<?php
if (!defined("REQUEST")) {
    exit("The Request Not Found");
}
$HN = new DATABASE;
setcookie("token", "", -1, "/");
session_destroy();
redirect(base_url());
