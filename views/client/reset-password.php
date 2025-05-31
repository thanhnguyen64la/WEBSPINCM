<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => 'Thay Đổi Mật Khẩu' . ' | ' . $HN->setting('title'),
    'description' => $HN->setting('description'),
    'keywords' => $HN->setting('keywords')
];
if (empty($_GET["token"])) {
    redirect(base_url());
}
$user = $HN->get_row(" SELECT * FROM `users` WHERE `token_forgot_password` = '" . check_string($_GET["token"]) . "' AND `token_forgot_password` IS NOT NULL ");
if ($user == false) {
    if (empty($user["token_forgot_password"])) {
        redirect(base_url());
    }
    redirect(base_url());
}
require_once(__DIR__ . '/header.php');
?>

<body>
    <section class="user-form-part">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                    <div class="user-form-card">
                        <div class="user-form-title">
                            <h2>Thay đổi mật khẩu</h2>
                        </div>
                        <form class="user-form">
                            <input type="hidden" id="csrf_token" value="<?= create_csrf_token(); ?>">
                            <input type="hidden" id="ChangePassword_token"
                                value="<?= $user['token_forgot_password']; ?>">
                            <div class="form-group">
                                <input type="password" id="ChangePassword_password" class="form-control"
                                    placeholder="Vui lòng nhập mật khẩu mới">
                            </div>
                            <div class="form-group">
                                <input type="password" id="ChangePassword_repassword" class="form-control"
                                    placeholder="Nhập lại mật khẩu mới">
                            </div>
                            <?php
                            if ($HN->setting("status_reCAPTCHA") == 'on'):
                                ?>
                                <center class="mb-3">
                                    <div class="g-recaptcha" id="g-recaptcha-response"
                                        data-sitekey="<?= $HN->setting('reCAPTCHA_site_key'); ?>"></div>
                                </center>
                            <?php endif; ?>
                            <div class="form-button">
                                <button type="button" id="btnChangePassword">Thay đổi mật khẩu</button>
                            </div>
                        </form>
                    </div>
                    <div class="user-form-remind">
                        <p>Bạn đã có tài khoản ? <a href="<?= client_url('login') ?>">Đăng Nhập</a></p>
                    </div>
                    <div class="user-form-footer">
                        <p>&COPY; Copyright By <a href="<?= base_url() ?>"><?= $HN->setting('title'); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Jquery -->
    <script src="<?= base_url("assets/vendor/jquery/jquery-3.7.1.min.js"); ?>"></script>
    <!-- Bootstrap -->
    <script src="<?= base_url("assets/vendor/bootstrap/popper.min.js"); ?>"></script>
    <script src="<?= base_url("assets/vendor/bootstrap/bootstrap.min.js"); ?>"></script>
    <!-- Countdown -->
    <script src="<?= base_url("assets/vendor/countdown/countdown.min.js"); ?>"></script>
    <!-- Nice Select -->
    <script src="<?= base_url("assets/vendor/niceselect/nice-select.min.js"); ?>"></script>
    <!-- Slick Slider -->
    <script src="<?= base_url("assets/vendor/slickslider/slick.min.js"); ?>"></script>
    <!-- Venobox -->
    <script src="<?= base_url("assets/vendor/venobox/venobox.min.js"); ?>"></script>
    <!-- Lazyload -->
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    <!-- Simple-notify -->
    <script src="https://cdn.jsdelivr.net/npm/simple-notify@1.0.4/dist/simple-notify.min.js"></script>
    <!-- Sweetalert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.11.0/sweetalert2.min.js"
        integrity="sha512-Wi5Ms24b10EBwWI9JxF03xaAXdwg9nF51qFUDND/Vhibyqbelri3QqLL+cXCgNYGEgokr+GA2zaoYaypaSDHLg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Google Recaptcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Main Js -->
    <script src="<?= base_url("assets/js/nice-select.js"); ?>"></script>
    <script src="<?= base_url("assets/js/countdown.js"); ?>"></script>
    <script src="<?= base_url("assets/js/accordion.js"); ?>"></script>
    <script src="<?= base_url("assets/js/venobox.js"); ?>"></script>
    <script src="<?= base_url("assets/js/slick.js"); ?>"></script>
    <script src="<?= base_url("assets/js/main.js"); ?>"></script>
</body>

</html>
<script type="text/javascript">
    $("#btnChangePassword").on("click", function () {
        $('#btnChangePassword').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);
        $.ajax({
            url: "<?= base_url('ajaxs/client/auth.php'); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                action: 'ChangePassword',
                csrf_token: $("#csrf_token").val(),
                token: $("#ChangePassword_token").val(),
                newpassword: $("#ChangePassword_password").val(),
                renewpassword: $("#ChangePassword_repassword").val(),
                recaptcha: $("#g-recaptcha-response").val(),
            },
            success: function (response) {
                if (response.status == 'success') {
                    new Notify({
                        status: 'success',
                        title: 'Thành công',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                    setTimeout("location.href = '<?= client_url('login'); ?>'", 2000);
                } else {
                    new Notify({
                        status: 'error',
                        title: 'Thất bại',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                }
                $('#btnChangePassword').html('Thay đổi mật khẩu').prop('disabled', false);
            },
            error: function (status) {
                new Notify({
                    status: 'error',
                    title: 'Thất bại',
                    text: 'Đã có lỗi xảy ra, vui lòng thử lại sau',
                    autotimeout: 5000,
                })
                $('#btnChangePassword').html('Thay đổi mật khẩu').prop('disabled', false);
            }

        });
    });
</script>