<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Chỉnh Sửa Thành Viên | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
$HN = new DATABASE();
$id_user = isset($_GET['id']) ? check_string($_GET['id']) : null;
if ($id_user != null) {
    $userOrther = $HN->get_row("SELECT * FROM `users` WHERE `id` = '$id_user'");
    if ($userOrther == false) {
        exit('Người dùng không tồn tại trong hệ thống');
    }
}
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
require_once(__DIR__ . "/../../libs/Database/User.php");

use Detection\MobileDetect;

$Mobile_Detect = new MobileDetect();
?>
<?php
if (isset($_POST['btnPlus'])) {
    $amount = check_string($_POST['amount']);
    $reason = check_string($_POST['reason']);
    $HN->insert("logs", [
        'user_id' => $user['id'],
        'created_time' => get_time(),
        'device' => $Mobile_Detect->getUserAgent(),
        'ip' => get_ip(),
        'action' => '[Admin] cộng ' . $amount . ' cho user ' . $userOrther['username'] . '[' . $userOrther['id'] . '].'
    ]);
    $DBUser = new USER;
    $addPlus = $DBUser->plus_money($id_user, $amount, '[Admin] ' . $reason);
    if ($addPlus) {
        msg_success_link('Cộng tiền thành công', "", 2000);
    } else {
        msg_error_link('Cộng tiền thất bại', "", 2000);
    }
}
if (isset($_POST['btnMinus'])) {
    $amount = check_string($_POST['amount']);
    $reason = check_string($_POST['reason']);
    $HN->insert("logs", [
        'user_id' => $user['id'],
        'created_time' => get_time(),
        'device' => $Mobile_Detect->getUserAgent(),
        'ip' => get_ip(),
        'action' => '[Admin] trừ ' . $amount . ' cho user ' . $userOrther['username'] . '[' . $userOrther['id'] . '].'
    ]);
    $DBUser = new USER;
    $addPlus = $DBUser->minus_money($id_user, $amount, '[Admin] ' . $reason);
    if ($addPlus) {
        msg_success_link('Trừ tiền thành công', "", 2000);
    } else {
        msg_error_link('Trừ tiền thất bại', "", 2000);
    }
}
if (isset($_POST['btnSave'])) {
    if (check_string($_POST['username']) != $userOrther['username']) {
        if ($HN->get_row(" SELECT * FROM `users` WHERE `username` = '" . check_string($_POST['username']) . "' AND `id` != '" . $userOrther['id'] . "' ")) {
            msg_error_link('Username này đã tồn tại trong hệ thống', "", 2000);
        }
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'created_time' => get_time(),
            'device' => $Mobile_Detect->getUserAgent(),
            'ip' => get_ip(),
            'action' => '[Admin] Thay đổi username cho thành viên ' . $userOrther['username'] . '[' . $userOrther['id'] . '] từ ' . $userOrther['username'] . ' -> ' . check_string($_POST['username']) . '.'
        ]);
        $HN->insert("logs", [
            'user_id' => $userOrther['id'],
            'created_time' => get_time(),
            'action' => 'Bạn được Admin thay đổi username.'
        ]);
    }
    if (check_string($_POST['level']) != $userOrther['level']) {
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'created_time' => get_time(),
            'device' => $Mobile_Detect->getUserAgent(),
            'ip' => get_ip(),
            'action' => '[Admin] Thay đổi quyền Admin cho thành viên ' . $userOrther['username'] . '[' . $userOrther['id'] . ']'
        ]);
        $HN->insert("logs", [
            'user_id' => $userOrther['id'],
            'created_time' => get_time(),
            'action' => 'Bạn được Admin thay cấp bậc.'
        ]);
    }
    $DBUser = new USER();
    $isUpdate = $DBUser->user_update_by_id([
        'username' => check_string($_POST['username']),
        'email' => check_string($_POST['email']),
        'token' => check_string($_POST['token']),
        'status_ban' => check_string($_POST['banned']),
        'phone_number' => check_string($_POST['phone']),
        'level' => check_string($_POST['level']),
    ], $userOrther['id']);
    if ($isUpdate) {
        if (!empty($_POST['password'])) {
            $DBUser->user_update_by_id([
                'password' => md5(check_string($_POST['password']))
            ], $userOrther['id']);
        }
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'created_time' => get_time(),
            'device' => $Mobile_Detect->getUserAgent(),
            'ip' => get_ip(),
            'action' => '[Admin] Cập nhật thông tin thành viên ' . $userOrther['username'] . '[' . $userOrther['id'] . '].'
        ]);
        $HN->insert("logs", [
            'user_id' => $userOrther['id'],
            'created_time' => get_time(),
            'action' => 'Bạn được Admin thay đổi thông tin.'
        ]);
        msg_success_link('Cập nhật thông tin thành công', "", 2000);
    }
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><a type="button"
                    class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1 waves-effect waves-light"
                    href="<?= base_url("?module=admin&action=users"); ?>"><i class="fa-solid fa-arrow-left"></i></a>
                Chỉnh sửa thành viên <strong style="color: red;"><?= $userOrther['username']; ?></strong> </h1>
        </div>
        <div class="row">
            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-success">
                                    <i class="fa-solid fa-link"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?php
                                        $linkCompleted = $HN->num_rows("SELECT * FROM `orders` WHERE `user_id` = '" . $userOrther['id'] . "' AND `status` = 'completed'") != null ? $HN->num_rows("SELECT * FROM `orders` WHERE `user_id` = '" . $userOrther['id'] . "' AND `status` = 'completed'") : 0;
                                        echo format_cash($linkCompleted); ?>
                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 fw-semibold">TỔNG SỐ ĐƠN THÀNH CÔNG</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-warning">
                                    <i class="fa-solid fa-link-slash"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?php
                                        $linkFail = $HN->num_rows("SELECT * FROM `orders` WHERE `user_id` = '" . $userOrther['id'] . "' AND `status_refund` = 'on'") != null ? $HN->num_rows("SELECT * FROM `orders` WHERE `user_id` = '" . $userOrther['id'] . "' AND `status_refund` = 'on'") : 0;
                                        echo format_cash($linkFail); ?>
                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 fw-semibold">TỔNG SỐ ĐƠN HOÀN TIỀN</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-danger">
                                    <i class="fa-solid fa-link-slash"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?php
                                        $linkFail = $HN->num_rows("SELECT * FROM `orders` WHERE `user_id` = '" . $userOrther['id'] . "' AND `status` = 'error'") != null ? $HN->num_rows("SELECT * FROM `orders` WHERE `user_id` = '" . $userOrther['id'] . "' AND `status` = 'error'") : 0;
                                        echo format_cash($linkFail); ?>
                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 fw-semibold">TỔNG SỐ ĐƠN HỦY</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col-12">
                <div class="mt-4 mt-md-0">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-addCredit"
                        class="btn btn-sm btn-wave btn-success me-1 mb-3 push waves-effect waves-light">
                        <i class="fa fa-fw fa-plus"></i> Cộng số dư
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-removeCredit"
                        class="btn btn-sm btn-wave btn-danger me-1 mb-3 push waves-effect waves-light">
                        <i class="fa fa-fw fa-minus"></i> Trừ số dư
                    </button>
                    <a type="button"
                        href="<?= base_url("?module=admin&action=logs&user_id="); ?><?= $userOrther['id']; ?>"
                        target="_blank" class="btn btn-sm btn-wave btn-primary me-1 mb-3 push waves-effect waves-light">
                        <i class="fa fa-fw fa-history"></i> Nhật ký hoạt động
                    </a>
                    <a type="button"
                        href="<?= base_url("?module=admin&action=transactions&user_id="); ?><?= $userOrther['id']; ?>"
                        target="_blank" class="btn btn-sm btn-wave btn-info me-1 mb-3 push waves-effect waves-light">
                        <i class="fa fa-fw fa-history"></i> Biến động số dư
                    </a>
                </div>
            </div>
            <div class="col-12">
                <div class="card custom-card shadow-none mb-4">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Username (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?= $userOrther['username']; ?>" name="username" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Email (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control"
                                                value="<?= $userOrther['email']; ?>" name="email" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Token (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-key"></i>
                                            </span>
                                            <input type="password" class="form-control" id="token_input"
                                                value="<?= $userOrther['token']; ?>" name="token" required="">
                                            <button type="button" id="show_token" class="btn btn-danger"
                                                onclick="toggleTokenVisibility()">Show</button>
                                        </div>
                                        <script>
                                            function toggleTokenVisibility() {
                                                var input = document.getElementById('token_input');
                                                var button = document.getElementById('show_token');
                                                if (input.type === 'password') {
                                                    input.type = 'text';
                                                    button.textContent = 'Hide';
                                                } else {
                                                    input.type = 'password';
                                                    button.textContent = 'Show';
                                                }
                                            }
                                        </script>
                                        <small>Bảo mật thông tin này vì kẻ xấu có thể thực hiện đăng nhập tài khoản bằng
                                            Token</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Mật khẩu (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-key"></i>
                                            </span>
                                            <input type="text" class="form-control" placeholder="**********"
                                                name="password">
                                        </div>
                                        <small>Nhập mật khẩu cần thay đổi, hệ thống sẽ tự động mã hóa (bỏ trống nếu
                                            không muốn thay đổi)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Phone (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-phone"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?= $userOrther['phone_number']; ?>" name="phone">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Level(<span class="text-danger">*</span>)</label>
                                        <select class="form-control" name="level">
                                            <option value="">Level</option>
                                            <option <?= $userOrther['level'] == 'user' ? 'selected' : ''; ?> value="user">
                                                User</option>
                                            <option <?= $userOrther['level'] == 'ctv' ? 'selected' : ''; ?> value="ctv">Ctv
                                            </option>
                                            <option <?= $userOrther['level'] == 'daily' ? 'selected' : ''; ?>
                                                value="daily">Đại Lý</option>
                                            <option <?= $userOrther['level'] == 'npp' ? 'selected' : ''; ?> value="npp">Nhà
                                                Phân Phối</option>
                                            <option <?= $userOrther['level'] == 'tongkho' ? 'selected' : ''; ?>
                                                value="tongkho">Tổng Kho</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <div class="mb-4">
                                            <label class="form-label">Trạng thái (<span
                                                    class="text-danger">*</span>)</label>
                                            <select class="form-control select2bs4" name="banned">
                                                <option <?= $userOrther['status_ban'] == 'off' ? 'selected' : ''; ?>
                                                    value="off">Hoạt động</option>
                                                <option <?= $userOrther['status_ban'] == 'on' ? 'selected' : ''; ?>
                                                    value="on">Banned</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Số dư</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wallet"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?= format_currency($userOrther['money']); ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Tổng tiền nạp</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-money-bill"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?= format_currency($userOrther['total_money']); ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Số dư đã sử dụng</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bx bxs-wallet-alt"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?= format_currency($userOrther['total_money'] - $userOrther['money']); ?>"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Địa chỉ IP dùng để đăng nhập</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wifi"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?= $userOrther['ip'] ?>"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Thiết bị đăng nhập</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-desktop"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?= $userOrther['device'] ?>"
                                                disabled="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Đăng ký tài khoản vào lúc</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?= $userOrther['created_time'] ?>" disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Đăng nhập gần nhất vào lúc</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?= $userOrther['updated_time'] ?>" disabled="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a type="button" class="btn btn-danger"
                                href="<?= base_url("?module=admin&action=users"); ?>"><i class="fa fa-fw fa-undo"></i>
                                Back</a>
                            <button type="submit" name="btnSave" class="btn btn-primary"><i class="bi bi-download"></i>
                                Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">THỐNG KÊ ĐƠN HÀNG THÁNG <?= date('m'); ?> CỦA USER <strong
                                style="color:red;"><?= $userOrther['username'] ?></strong></div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartjs-line" class="chartjs-chart" width="695" height="347"
                            style="display: block; box-sizing: border-box; height: 278px; width: 556px;"></canvas>
                        <script>
                            (function () {
                                const labels = [
                                    <?php
                                    $daysInMonth = date('t');
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        echo "\"$day/" . date('m/Y') . "\",";
                                    }
                                    ?>
                                ];
                                const doanhThuData = [
                                    <?php
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        $doanhThu = $HN->get_row("SELECT SUM(`price`) AS total FROM `orders` WHERE `status` = 'completed' AND `user_id` = '" . $userOrther['id'] . "' AND DAY(`created_time`) = $day AND MONTH(`created_time`) = MONTH(CURRENT_DATE()) AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['total'];
                                        echo $doanhThu != NULL ? $doanhThu : 0;
                                        echo ",";
                                    }
                                    ?>
                                ];
                                const data = {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Doanh thu',
                                        backgroundColor: 'rgb(132, 90, 223)',
                                        borderColor: 'rgb(132, 90, 223)',
                                        data: doanhThuData,
                                    },]
                                };
                                const config = {
                                    type: 'bar',
                                    data: data,
                                    options: {}
                                };
                                const myChart = new Chart(
                                    document.getElementById('chartjs-line'),
                                    config
                                );
                            })();
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">THỐNG KÊ NẠP TIỀN THÁNG <?= date('m'); ?> CỦA USER <strong
                                style="color:red;"><?= $userOrther['username'] ?></strong></div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartjs-naptien" class="chartjs-chart" width="695" height="347"
                            style="display: block; box-sizing: border-box; height: 278px; width: 556px;"></canvas>
                        <script>
                            (function () {
                                /* line chart  */
                                Chart.defaults.borderColor = "rgba(142, 156, 173,0.1)", Chart.defaults.color =
                                    "#8c9097";
                                const labels = [
                                    <?php
                                    $daysInMonth = date('t');
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        echo "\"$day/" . date('m/Y') . "\",";
                                    }
                                    ?>
                                ];
                                const tongNapData = [
                                    <?php
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        $tienNap = $HN->get_row("SELECT SUM(`amount`) AS total FROM `payment_bank` WHERE `user_id` = '" . $userOrther['id'] . "' AND DAY(`created_time`) = $day AND MONTH(`created_time`) = MONTH(CURRENT_DATE()) AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['total'];
                                        echo $tienNap;
                                        echo ",";
                                    }
                                    ?>
                                ];
                                const data = {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Tổng tiền nạp',
                                        backgroundColor: 'rgb(132, 90, 223)',
                                        borderColor: 'rgb(132, 90, 223)',
                                        data: tongNapData,
                                    }]
                                };
                                const config = {
                                    type: 'bar',
                                    data: data,
                                    options: {}
                                };
                                const myChart = new Chart(
                                    document.getElementById('chartjs-naptien'),
                                    config
                                );
                            })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modal-addCredit" tabindex="-1" aria-labelledby="modal-addCredit" data-bs-keyboard="false"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="fa fa-plus"></i> CỘNG SỐ DƯ
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label">Amount:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="amount" id="amountPlus"
                                placeholder="Nhập số tiền cần cộng" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label">Reason:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="reason" id="reasonPlus"></textarea>
                        </div>
                    </div>
                    <center>Nhấn vào nút Submit để thực hiện cộng <b id="amountDisplay" style="color:red;">0</b>
                    </center>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var amountPlus = document.getElementById('amountPlus');
                            var amountDisplay = document.getElementById('amountDisplay');
                            updateAmountDisplay();
                            amountPlus.addEventListener('input', function () {
                                updateAmountDisplay();
                            });

                            function updateAmountDisplay() {
                                var inputValue = amountPlus.value;
                                if (!inputValue || isNaN(inputValue)) {
                                    amountDisplay.textContent =
                                        '0';
                                    return;
                                }
                                var formattedAmount = formatNumber(inputValue);
                                amountDisplay.textContent = formattedAmount;
                            }

                            function formatNumber(value) {
                                return parseFloat(value).toLocaleString('vi-VN');
                            }
                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-hero btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-fw fa-times me-1"></i>
                        Close
                    </button>
                    <button type="submit" name="btnPlus" class="btn btn-hero btn-success">
                        <i class="fa fa-fw fa-plus me-1"></i>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-removeCredit" tabindex="-1" aria-labelledby="modal-removeCredit"
    data-bs-keyboard="false" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="fa fa-minus"></i> TRỪ SỐ DƯ
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label">Amount:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="amount" id="amountMinus"
                                placeholder="Nhập số tiền cần cộng" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label">Reason:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="reason" id="reasonMinus"></textarea>
                        </div>
                    </div>
                    <center>Nhấn vào nút Submit để thực hiện trừ <b id="amountDisplayMinus" style="color:red;">0</b>
                    </center>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var amountMinus = document.getElementById('amountMinus');
                            var amountDisplayMinus = document.getElementById('amountDisplayMinus');
                            updateAmountDisplay();
                            amountMinus.addEventListener('input', function () {
                                updateAmountDisplay();
                            });

                            function updateAmountDisplay() {
                                var inputValue = amountMinus.value;
                                if (!inputValue || isNaN(inputValue)) {
                                    amountDisplayMinus.textContent =
                                        '0';
                                    return;
                                }
                                var formattedAmount = formatNumber(inputValue);
                                amountDisplayMinus.textContent = formattedAmount;
                            }

                            function formatNumber(value) {
                                return parseFloat(value).toLocaleString('vi-VN');
                            }
                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-hero btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-fw fa-times me-1"></i>
                        Close
                    </button>
                    <button type="submit" name="btnMinus" class="btn btn-hero btn-success">
                        <i class="fa fa-fw fa-plus me-1"></i>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
require_once(__DIR__ . '/footer.php');

?>