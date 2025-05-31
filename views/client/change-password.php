<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => 'Thay Đổi Mật Khẩu' . ' | ' . $HN->setting('title'),
    'description' => $HN->setting('description'),
    'keywords' => $HN->setting('keywords')
];
$head["header"] = '<link rel="stylesheet" href="' . base_url('assets/css/wallet.css') . '" />';
if (isset($_COOKIE["token"])) {
    $user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '" . check_string($_COOKIE['token']) . "' ");
    if ($user == false) {
        redirect(client_url("logout"));
        exit();
    }
    $_SESSION['login'] = $user['token'];
}
require_once(__DIR__ . '/../../models/is_user.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
?>
<section class="py-5 inner-section profile-part">
    <div class="container">
        <div class="row">
            <?php
            require_once(__DIR__ . '/sidebar.php');
            ?>
            <div class="col-lg-9">
                <div class="account-card">
                    <div class="account-title">
                        <h4>Thay đổi mật khẩu</h4>
                    </div>
                    <div class="account-content">
                        <p class="mb-3 text-muted">
                            Thay đổi mật khẩu đăng nhập của bạn là một cách dễ dàng để giữ an toàn cho tài khoản của
                            bạn. </p>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input type="hidden" class="form-control" id="token" value="<?= $user['token']; ?>">
                                    <input type="password" class="form-control" id="old-password" name="old-password">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" id="new-password" name="new-password">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group"><label class="form-label">Nhập lại mật khẩu mới</label>
                                    <input type="password" class="form-control" id="new-password-repeat"
                                        name="new-password-repeat">
                                </div>
                            </div>
                            <center>
                                <button class="form-btn" id="btnChangePasswordProfile" type="button">Cập Nhật</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require_once(__DIR__ . '/footer.php');
?>
<script type="text/javascript">
    $("#btnChangePasswordProfile").on("click", function () {
        $('#btnChangePasswordProfile').html('<i class="fa fa-spinner fa-spin"></i> Đang Xử Lý...').prop('disabled', true);
        $.ajax({
            url: "<?= base_url('ajaxs/client/auth.php') ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                action: 'ChangePasswordProfile',
                token: $("#token").val(),
                password: $("#old-password").val(),
                newpassword: $("#new-password").val(),
                renewpassword: $("#new-password-repeat").val()
            },
            success: function (response) {
                if (response.status == 'success') {
                    new Notify({
                        status: 'success',
                        title: 'Thành công',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                    setTimeout("location.href = '<?= base_url('client/login'); ?>';", 2000);
                } else {
                    new Notify({
                        status: 'error',
                        title: 'Thất bại',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                }
                $('#btnChangePasswordProfile').html('Cập Nhật').prop('disabled', false);
            },
            error: function () {
                new Notify({
                    status: 'error',
                    title: 'Thất bại',
                    text: 'Đã có lỗi xảy ra, vui lòng thử lại sau',
                    autotimeout: 5000,
                })
                $('#btnChangePasswordProfile').html('Cập Nhật').prop('disabled', false);
            }

        });
    });
</script>