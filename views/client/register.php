<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => 'Đăng Ký' . ' | ' . $HN->setting('title'),
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
            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="user-form-card">
                    <div class="user-form-title">
                        <h2>Đăng ký tài khoản</h2>
                        <p>Vui lòng nhập thông tin đăng ký</p>
                    </div>
                    <div class="user-form-group">
                        <form class="user-form">
                            <div class="form-group">
                                <input type="hidden" id="csrf_token" value="<?= create_csrf_token(); ?>">
                                <input type="text" class="form-control" id="register-username"
                                    placeholder="Tài khoản đăng nhập">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" id="register-email"
                                    placeholder="Địa chỉ Email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="register-password"
                                    placeholder="Mật khẩu">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="register-password-confirm"
                                    placeholder="Nhập lại mật khẩu">
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
                                <button type="button" id="btnRegister">Đăng Ký</button>
                                <p>Bạn đã có tài khoản ? <a href="<?= client_url("login") ?>">Đăng Nhập</a></p>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="user-form-remind">
                    <p>Bạn chưa có tài khoản ? <a href="<?= client_url("register") ?>">Đăng Ký Ngay</a></p>
                </div> -->
            </div>
        </div>
    </div>
</section>
<?php
require_once(__DIR__ . '/footer.php');
?>
<script type="text/javascript">
    $("#btnRegister").on("click", function () {
        $('#btnRegister').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);
        $.ajax({
            url: "<?= base_url('ajaxs/client/auth.php'); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                csrf_token: $("#csrf_token").val(),
                username: $("#register-username").val(),
                email: $("#register-email").val(),
                password: $("#register-password").val(),
                repassword: $("#register-password-confirm").val(),
                recaptcha: $("#g-recaptcha-response").val(),
                action: 'Register'
            },
            success: function (response) {
                if (response.status == 'success') {
                    new Notify({
                        status: 'success',
                        title: 'Thành công',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                    setTimeout("location.href = '<?= client_url(); ?>'", 2000);
                } else {
                    new Notify({
                        status: 'error',
                        title: 'Thất bại',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                }
                $('#btnRegister').html('Đăng Ký').prop('disabled', false);
            },
            error: function (status) {
                new Notify({
                    status: 'error',
                    title: 'Thất bại',
                    text: 'Đã có lỗi xảy ra, vui lòng thử lại sau',
                    autotimeout: 5000,
                })
                $('#btnRegister').html('Đăng Ký').prop('disabled', false);
            }
        });
    });
</script>