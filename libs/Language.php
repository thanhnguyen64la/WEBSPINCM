<?php
if (!defined('REQUEST')) {
    exit('The Request Not Found');
}
function set_language($id)
{
    global $HN;
    $row = $HN->get_row("SELECT * FROM `languages` WHERE `id` = '" . $id . "' AND `status` = 'on'");
    if ($row) {
        $isSet = setcookie("language", $row["name"], time() + 2592000, "/");
        return $isSet;
    }
    return false;
}
function get_language()
{
    global $HN;
    if (isset($_COOKIE["language"])) {
        $language = check_string($_COOKIE["language"]);
        $row = $HN->get_row("SELECT * FROM `languages` WHERE `name` = '" . $language . "' AND `status` = 'on'");
        if ($row) {
            return $row["name"];
        }
    }
    $row = $HN->get_row("SELECT * FROM `languages` WHERE `status_default` = 'on' ");
    if ($row) {
        return $row["name"];
    }
    return false;
}
function __($name)
{
    global $HN;
    if (isset($_COOKIE["language"])) {
        $language = check_string($_COOKIE["language"]);
        $row_name = $HN->get_row("SELECT * FROM `languages` WHERE `name` = '" . $language . "' AND `status` = 'on' ");
        if ($row_name) {
            $row_translate = $HN->get_row("SELECT * FROM `translates` WHERE `language_id` = '" . $row_name["id"] . "' AND `name` = '" . $name . "' ");
            if ($row_translate) {
                return $row_translate["value"];
            }
        }
    }
    $row_name = $HN->get_row("SELECT * FROM `languages` WHERE `status_default` = 'on' ");
    if ($row_name) {
        $row_translate = $HN->get_row("SELECT * FROM `translates` WHERE `language_id` = '" . $row_name["id"] . "' AND `name` = '" . $name . "' ");
        if ($row_translate) {
            return $row_translate["value"];
        }
    }
    return $name;
}
