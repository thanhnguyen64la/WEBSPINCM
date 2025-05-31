<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => 'Thông Tin Cá Nhân' . ' | ' . $HN->setting('title'),
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="account-card">
                            <h4 class="account-title">Ví của tôi</h4>
                            <div class="my-wallet">
                                <p>Số dư hiện tại</p>
                                <h3><?= format_currency($user['money']) ?></h3>
                            </div>
                            <div class="wallet-card-group">
                                <div class="wallet-card">
                                    <p>Tổng tiền nạp</p>
                                    <h3><?= format_currency($user['total_money']) ?></h3>
                                </div>
                                <div class="wallet-card">
                                    <p>Số tiền đã sử dụng</p>
                                    <h3><?= format_currency($user['total_money'] - $user['money']); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="account-card">
                            <div class="account-title">
                                <h4>Hồ sơ của bạn</h4>
                                <button data-bs-toggle="modal" data-bs-target="#profile-edit">Chỉnh sửa thông
                                    tin</button>
                            </div>
                            <div class="account-content">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">Token</label>
                                            <input type="text" class="form-control" value="<?= $user['token']; ?>"
                                                readonly>
                                            <small>Vui lòng bảo mật Token, nếu bị lộ ra ngoài có thể bị kẻ xấu hack tài
                                                khoản. Token sẽ thay đôi khi bạn đổi mật khẩu !</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label">Tên đăng nhập</label>
                                            <input type="text" class="form-control" value="<?= $user['username']; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label">Địa chỉ Email</label>
                                            <input type="email" class="form-control" value="<?= $user['email']; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group"><label class="form-label">Số điện thoại</label>
                                            <input type="text" class="form-control"
                                                value="<?= $user['phone_number']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group"><label class="form-label">Họ và tên</label>
                                            <input type="text" class="form-control" value="<?= $user['full_name']; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group"><label class="form-label">Telegram Chat ID</label>
                                            <input type="text" class="form-control"
                                                value="<?= $user['telegram_chat_id']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group"><label class="form-label">Địa chỉ IP</label>
                                            <input type="text" class="form-control" value="<?= $user['ip']; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group"><label class="form-label">Thiết bị</label>
                                            <input type="text" class="form-control" value="<?= $user['device']; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group"><label class="form-label">Đăng ký vào lúc</label>
                                            <input type="text" class="form-control"
                                                value="<?= $user['created_time']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group"><label class="form-label">Đăng nhập gần nhất</label>
                                            <input type="text" class="form-control"
                                                value="<?= $user['updated_time']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- MODAL -->
<div class="modal fade" id="profile-edit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"><button class="modal-close" data-bs-dismiss="modal"><i
                    class="icofont-close"></i></button>
            <form class="modal-form">
                <div class="form-title">
                    <h3>Chỉnh sửa thông tin</h3>
                </div>
                <div class="form-group"><label class="form-label">Số điện thoại</label>
                    <input type="hidden" class="form-control" value="<?= $user['token']; ?>" id="token">
                    <input type="text" class="form-control" value="<?= $user['phone_number']; ?>" id="phone_number">
                </div>
                <div class="form-group"><label class="form-label">Họ và Tên</label>
                    <input type="text" class="form-control" value="<?= $user['full_name']; ?>" id="full_name">
                </div>
                <div class="form-group"><label class="form-label">Telegram Chat ID</label>
                    <input type="text" class="form-control" value="<?= $user['telegram_chat_id']; ?>"
                        id="telegram_chat_id">
                </div>
                <button class="form-btn" id="btnSaveProfile" type="button">Lưu</button>
            </form>
        </div>
    </div>
</div>
<?php
require_once(__DIR__ . '/footer.php');
?>
<script type="text/javascript">
    $("#btnSaveProfile").on("click", function () {
        $('#btnSaveProfile').html('<i class="fa fa-spinner fa-spin"></i> Đang Xử Lý...').prop('disabled', true);
        $.ajax({
            url: "<?= base_url('ajaxs/client/auth.php'); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                action: 'ChangeProfile',
                token: $("#token").val(),
                phone_number: $("#phone_number").val(),
                full_name: $("#full_name").val(),
                telegram_chat_id: $("#telegram_chat_id").val()
            },
            success: function (response) {
                if (response.status == 'success') {
                    new Notify({
                        status: 'success',
                        title: 'Thành công',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    new Notify({
                        status: 'error',
                        title: 'Thất bại',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                }
                $('#btnSaveProfile').html('Lưu').prop('disabled', false);
            },
            error: function (status) {
                new Notify({
                    status: 'error',
                    title: 'Thất bại',
                    text: 'Đã có lỗi xảy ra, vui lòng thử lại sau',
                    autotimeout: 5000,
                })
                $('#btnSaveProfile').html('Lưu').prop('disabled', false);
            }
        });
    });
</script>