<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => 'Quên Mật Khẩu' . ' | ' . $HN->setting('title'),
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
    redirect(client_url());
}
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
?>
<section class="py-5 inner-section profile-part">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="user-form-card">
                    <div class="user-form-title">
                        <h2>Bạn quên mật khẩu ?</h2>
                        <p>Vui lòng nhập thông tin vào ô dưới đây để xác minh</p>
                    </div>
                    <form class="user-form">
                        <div class="form-group">
                            <input type="hidden" id="csrf_token" value="<?= create_csrf_token(); ?>">
                            <input type="email" id="email" class="form-control"
                                placeholder="Vui lòng nhập địa chỉ Email">
                        </div>
                        <?php
                        if ($HN->setting("status_reCAPTCHA") == 'on'):
                            ?>
                            <center class="mb-3">
                                <div class="g-recaptcha" id="g-recaptcha-response"
                                    data-sitekey="<?= $HN->setting('reCAPTCHA_site_key'); ?>"></div>
                            </center>
                        <?php endif; ?>
                        <div class="form-button"><button type="button" id="btnForgotPassword">Xác Minh</button></div>
                    </form>
                </div>
                <div class="user-form-remind">
                    <p>Bạn đã có tài khoản ? <a href="<?= client_url("login") ?>">Đăng Nhập</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require_once(__DIR__ . '/footer.php');
?>
<script type="text/javascript">
    $("#btnForgotPassword").on("click", function () {
        $('#btnForgotPassword').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);
        $.ajax({
            url: "<?= base_url('ajaxs/client/auth.php'); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                csrf_token: $("#csrf_token").val(),
                email: $("#email").val(),
                recaptcha: $("#g-recaptcha-response").val(),
                action: 'ForgotPassword'
            },
            success: function (response) {
                if (response.status == 'success') {
                    new Notify({
                        status: 'success',
                        title: 'Thành công',
                        text: response.msg,
                        autotimeout: 4000,
                    })
                    setTimeout("location.href = '<?= base_url(''); ?>';", 4000);
                } else {
                    new Notify({
                        status: 'error',
                        title: 'Thất bại',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                }
                $('#btnForgotPassword').html('Xác Minh').prop('disabled', false);
            },
            error: function (status) {
                new Notify({
                    status: 'error',
                    title: 'Thất bại',
                    text: 'Đã có lỗi xảy ra, vui lòng thử lại sau',
                    autotimeout: 5000,
                })
                $('#btnForgotPassword').html('Xác Minh').prop('disabled', false);
            }
        });
    });
</script>