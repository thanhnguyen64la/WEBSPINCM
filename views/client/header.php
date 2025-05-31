<?php
if (!defined('REQUEST')) {
    exit('The Request Not Found');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta http-equiv="content-language" content="vi" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?= isset($head['title']) ? $head['title'] : $HN->setting('title'); ?></title>
    <link rel="icon" type="image/png" href="<?= base_url($HN->setting("favicon")); ?>" />
    <link rel="canonical" href="<?= get_url(); ?>" />
    <link rel="alternate" hreflang="vi-vn" href="<?= get_url(); ?>" />
    <meta name="title" content="<?= isset($head['title']) ? $head['title'] : $HN->setting('title'); ?>" />
    <meta name="description"
        content="<?= isset($head['description']) ? $head['description'] : $HN->setting('description'); ?>" />
    <meta name="keywords" content="<?= isset($head['keywords']) ? $head['keywords'] : $HN->setting('keywords'); ?>" />
    <!-- Open Graph Data -->
    <meta property="og:title" content="<?= isset($head['title']) ? $head['title'] : $HN->setting('title'); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= base_url(); ?>" />
    <meta property="og:image:alt" content="<?= isset($head['title']) ? $head['title'] : $HN->setting('title'); ?>" />
    <meta property="og:image"
        content="<?= isset($head['image_description']) ? $head['image_description'] : base_url($HN->setting('image_description')); ?>" />
    <meta property="og:description"
        content="<?= isset($head['description']) ? $head['description'] : $HN->setting('description'); ?>" />
    <meta property="og:site_name" content="<?= isset($head['title']) ? $head['title'] : $HN->setting('title'); ?>" />
    <meta property="article:section"
        content="<?= isset($head['description']) ? $head['description'] : $HN->setting('description'); ?>" />
    <meta property="article:tag"
        content="<?= isset($head['keywords']) ? $head['keywords'] : $HN->setting('keywords'); ?>" />
    <meta property="og:locale" content="vi_VN" />
    <!-- Twitter Card Data -->
    <meta name="twitter:title" content="<?= isset($head['title']) ? $head['title'] : $HN->setting('title'); ?>" />
    <meta name="twitter:description"
        content="<?= isset($head['description']) ? $head['description'] : $HN->setting('description'); ?>" />
    <meta name="twitter:image"
        content="<?= isset($head['image_description']) ? $head['image_description'] : base_url($HN->setting('image_description')); ?>" />
    <meta name="twitter:image:alt" content="<?= isset($head['title']) ? $head['title'] : $HN->setting('title'); ?>" />
    <!-- Google  -->
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta name="google" content="notranslate" />

    <!-- Library Css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-notify@1.0.4/dist/simple-notify.css" />
    <!-- Sweetalert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.11.0/sweetalert2.min.css"
        integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.11.0/sweetalert2.min.js"
        integrity="sha512-Wi5Ms24b10EBwWI9JxF03xaAXdwg9nF51qFUDND/Vhibyqbelri3QqLL+cXCgNYGEgokr+GA2zaoYaypaSDHLg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css"
        integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- List Css -->
    <link rel="stylesheet" href="<?= base_url("assets/fonts/flaticon/flaticon.css"); ?>" />
    <link rel="stylesheet" href="<?= base_url("assets/fonts/icofont/icofont.min.css"); ?>" />
    <link rel="stylesheet" href="<?= base_url("assets/vendor/fontawesome/css/all.min.css"); ?>" />
    <link rel="stylesheet" href="<?= base_url("assets/vendor/venobox/venobox.min.css"); ?>" />
    <link rel="stylesheet" href="<?= base_url("assets/vendor/slickslider/slick.min.css"); ?> " />
    <link rel="stylesheet" href="<?= base_url("assets/vendor/niceselect/nice-select.min.css"); ?>" />
    <link rel="stylesheet" href="<?= base_url("assets/vendor/bootstrap/bootstrap.min.css"); ?>" />
    <link rel="stylesheet" href="<?= base_url("assets/css/main.css"); ?>" />
    <link rel="stylesheet" href="<?= base_url("assets/css/user-auth.css"); ?>" />
    <link rel="stylesheet" href="<?= base_url("assets/css/index.css"); ?>" />
    <!-- Jquery -->
    <script src="<?= base_url("assets/vendor/jquery/jquery-3.7.1.min.js"); ?>"></script>
    <?php
    if (isset($head["header"])) {
        echo $head["header"];
    }
    ?>
    <style>
        :root {
            --primary:
                <?= $HN->setting("primary_color") != "" ? $HN->setting("primary_color") : "#078497"; ?>
            ;
            --secondary:
                <?= $HN->setting("secondary_color") != "" ? $HN->setting("secondary_color") : "#72cbb0"; ?>
            ;
        }
    </style>
    <!-- ANIME JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

</head>