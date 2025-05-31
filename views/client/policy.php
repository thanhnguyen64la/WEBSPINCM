<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => 'Chính Sách' . ' | ' . $HN->setting('title'),
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
        <h2>CHÍNH SÁCH</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chính sách</li>
        </ol>
    </div>
</section>
<section class="inner-section contact-part">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="account-card pt-4">
                    <?= $HN->setting('policy'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require_once(__DIR__ . '/footer.php');
?>