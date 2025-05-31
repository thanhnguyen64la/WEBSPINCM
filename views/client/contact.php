<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => 'Liên Hệ' . ' | ' . $HN->setting('title'),
    'description' => $HN->setting('description'),
    'keywords' => $HN->setting('keywords')
];
if (isset($_COOKIE["token"])) {
    $user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '" . check_string($_COOKIE['token']) . "' ");
    if ($user == false) {
        redirect(client_url("logout"));
        exit();
    }
    $_SESSION['login'] = $user['token'];
}
if (isset($_SESSION['login'])) {
    require_once(__DIR__ . '/../../models/is_user.php');
}
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
?>
<section class="inner-section single-banner"
    style="background: url('<?= base_url('assets/img/storage/banner.jpeg') ?>') no-repeat center;">
    <div class="container">
        <h2>LIÊN HỆ</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
        </ol>
    </div>
</section>
<section class="inner-section contact-part">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <div class="contact-card">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" height="100" width="100"
                        viewBox="0 0 48 48">
                        <path fill="#2962ff"
                            d="M15,36V6.827l-1.211-0.811C8.64,8.083,5,13.112,5,19v10c0,7.732,6.268,14,14,14h10	c4.722,0,8.883-2.348,11.417-5.931V36H15z">
                        </path>
                        <path fill="#eee"
                            d="M29,5H19c-1.845,0-3.601,0.366-5.214,1.014C10.453,9.25,8,14.528,8,19	c0,6.771,0.936,10.735,3.712,14.607c0.216,0.301,0.357,0.653,0.376,1.022c0.043,0.835-0.129,2.365-1.634,3.742	c-0.162,0.148-0.059,0.419,0.16,0.428c0.942,0.041,2.843-0.014,4.797-0.877c0.557-0.246,1.191-0.203,1.729,0.083	C20.453,39.764,24.333,40,28,40c4.676,0,9.339-1.04,12.417-2.916C42.038,34.799,43,32.014,43,29V19C43,11.268,36.732,5,29,5z">
                        </path>
                        <path fill="#2962ff"
                            d="M36.75,27C34.683,27,33,25.317,33,23.25s1.683-3.75,3.75-3.75s3.75,1.683,3.75,3.75	S38.817,27,36.75,27z M36.75,21c-1.24,0-2.25,1.01-2.25,2.25s1.01,2.25,2.25,2.25S39,24.49,39,23.25S37.99,21,36.75,21z">
                        </path>
                        <path fill="#2962ff" d="M31.5,27h-1c-0.276,0-0.5-0.224-0.5-0.5V18h1.5V27z"></path>
                        <path fill="#2962ff"
                            d="M27,19.75v0.519c-0.629-0.476-1.403-0.769-2.25-0.769c-2.067,0-3.75,1.683-3.75,3.75	S22.683,27,24.75,27c0.847,0,1.621-0.293,2.25-0.769V26.5c0,0.276,0.224,0.5,0.5,0.5h1v-7.25H27z M24.75,25.5	c-1.24,0-2.25-1.01-2.25-2.25S23.51,21,24.75,21S27,22.01,27,23.25S25.99,25.5,24.75,25.5z">
                        </path>
                        <path fill="#2962ff"
                            d="M21.25,18h-8v1.5h5.321L13,26h0.026c-0.163,0.211-0.276,0.463-0.276,0.75V27h7.5	c0.276,0,0.5-0.224,0.5-0.5v-1h-5.321L21,19h-0.026c0.163-0.211,0.276-0.463,0.276-0.75V18z">
                        </path>
                    </svg>
                    <h4>Zalo</h4>
                    <p><?= $HN->setting('zalo'); ?></p>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="contact-card">
                    <img height="100" width="100" src="<?= base_url('assets/img/storage/phonecall.svg') ?>">
                    <h4>Hotline</h4>
                    <p><?= $HN->setting('hotline'); ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="account-card pt-4">
                    <?= $HN->setting('contact'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require_once(__DIR__ . '/footer.php');
?>