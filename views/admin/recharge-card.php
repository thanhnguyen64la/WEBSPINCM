<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Thống Kê Thẻ Cào  | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
?>
<?php
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$shortByDate = isset($_GET['shortByDate']) ? $_GET['shortByDate'] : '';
$where = " `cards`.`id` > 0 ";
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$pin = isset($_GET['pin']) ? $_GET['pin'] : '';
$serial = isset($_GET['serial']) ? $_GET['serial'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$create_date = isset($_GET['create_date']) ? $_GET['create_date'] : '';
$from = ($page - 1) * $limit;
$sql = [];
if ($user_id) {
    $sql[] = "`cards`.`user_id` = $user_id";
}
if ($username) {
    $sql[] = "`users`.`username` = '$username'";
}
if ($pin !== "") {
    $sql[] = "`cards`.`pin` LIKE '%$pin%'";
}
if ($serial !== "") {
    $sql[] = "`cards`.`serial` LIKE '%$serial%'";
}
if ($status !== '') {
    $sql[] = "`cards`.`status` = '$status'";
}
if (!empty($create_date)) {
    if (strpos($create_date, ' to ') !== false) {
        list($startDate, $endDate) = explode(' to ', $create_date);
    } else {
        $startDate = $endDate = $create_date;
    }
    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
    $sql[] = "`cards`.`created_time` BETWEEN '$startDate' AND '$endDate'";
}
if ($shortByDate) {
    if ($shortByDate == '1') {
        $sql[] = "DATE(`cards`.`created_time`) = CURDATE()";
    } elseif ($shortByDate == '2') {
        $sql[] = "YEARWEEK(`cards`.`created_time`, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($shortByDate == '3') {
        $sql[] = "MONTH(`cards`.`created_time`) = MONTH(CURDATE()) AND YEAR(`cards`.`created_time`) = YEAR(CURDATE())";
    }
}
if (!empty($sql)) {
    $sqlReal = " AND " . implode(' AND ', $sql);
} else {
    $sqlReal = '';
}
$totalRecords = $HN->num_rows("SELECT `cards`.*, `users`.`username` FROM `cards` INNER JOIN `users` ON `cards`.`user_id` = `users`.`id` WHERE $where $sqlReal ORDER BY `cards`.`id` DESC ");
$totalPages = ceil($totalRecords / $limit);
$cards = $HN->get_list("SELECT `cards`.*, `users`.`username` FROM `cards` INNER JOIN `users` ON `cards`.`user_id` = `users`.`id` WHERE $where $sqlReal  ORDER BY `cards`.`id` DESC LIMIT $limit OFFSET $from ");
$pagination = pagination(base_url("?module=admin&action=recharge-card&user_id=$user_id&username=$username&pin=$pin&serial=$serial&status=$status&create_date=$create_date&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['SaveSettings'])) {
    foreach ($_POST as $key => $value) {
        $HN->update("settings", array(
            'setting_value' => $value
        ), " `setting_key` = '$key' ");
    }
    msg_success_link('Lưu thành công', "", 2000);
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Thẻ cào</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Nạp tiền</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thẻ cào</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="text-right">
                    <button type="button" id="open-card-config" class="btn btn-primary label-btn mb-3">
                        <i class="ri-settings-4-line label-btn-icon me-2"></i> CẤU HÌNH
                    </button>
                </div>
            </div>
            <div class="col-xl-12" id="card-config" style="display: none;">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="row mb-4">
                                        <label class="col-sm-4 col-form-label">Trạng thái</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="status_card">
                                                <option <?= $HN->setting("status_card") == 'on' ? 'selected' : ''; ?>
                                                    value="on">ON </option>
                                                <option <?= $HN->setting("status_card") == 'off' ? 'selected' : ''; ?>
                                                    value="off">OF </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-4 col-form-label">Partner ID
                                            <small><a target="_blank" class="text-primary"
                                                    href="https://gachthe1s.com/">GACHTHE1S.COM</a></small></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control"
                                                value="<?= $HN->setting("partner_id_card") ?>" name="partner_id_card">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-4 col-form-label">Partner Key
                                            <small><a target="_blank" class="text-primary"
                                                    href="https://gachthe1s.com/">GACHTHE1S.COM</a></small></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control"
                                                value="<?= $HN->setting("partner_key_card") ?>" name="partner_key_card">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="row mb-4">
                                        <label class="col-sm-6 col-form-label">Chiết khấu nạp thẻ:</label>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    value="<?= $HN->setting("discount_napthe") ?>"
                                                    name="discount_napthe" placeholder="VD: 10 = 10%">
                                                <span class="input-group-text">
                                                    %
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="row mb-4">
                                        <label class="col-sm-6 col-form-label">Note</label>
                                        <div class="col-sm-12">
                                            <textarea id="notice_napthe" name="notice_napthe">
                                            <?= $HN->setting("notice_napthe") ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mb-4">
                                <button type="submit" name="SaveSettings" class="btn btn-primary btn-block">
                                    <i class="fa fa-fw fa-save me-1"></i>
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-fill">
                                        <p class="mb-1 fs-5 fw-semibold text-default">
                                            <?php
                                            $cardAll = $HN->get_row("SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed'")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed'")['SUM(`price`)'] : 0;
                                            echo format_currency($cardAll);
                                            ?>
                                        </p>
                                        <p class="mb-0 text-muted">Toàn thời gian</p>
                                    </div>
                                    <div class="ms-2">
                                        <span class="avatar text-bg-danger rounded-circle fs-20"><i
                                                class='bx bxs-wallet-alt'></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-fill">
                                        <p class="mb-1 fs-5 fw-semibold text-default">
                                            <?php
                                            $cardMonth = $HN->get_row("SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`price`)'] : 0;
                                            echo format_currency($cardMonth);
                                            ?>
                                        </p>
                                        <p class="mb-0 text-muted">Tháng <?= date('m'); ?></p>
                                    </div>
                                    <div class="ms-2">
                                        <span class="avatar text-bg-info rounded-circle fs-20"><i
                                                class='bx bxs-wallet-alt'></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-fill">
                                        <p class="mb-1 fs-5 fw-semibold text-default">
                                            <?php
                                            $cardWeek = $HN->get_row("SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND WEEK(`created_time`, 1) = WEEK(CURRENT_DATE(), 1)")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND WEEK(`created_time`, 1) = WEEK(CURRENT_DATE(), 1)")['SUM(`price`)'] : 0;
                                            echo format_currency($cardWeek);
                                            ?>
                                        </p>
                                        <p class="mb-0 text-muted">Trong tuần</p>
                                    </div>
                                    <div class="ms-2">
                                        <span class="avatar text-bg-warning rounded-circle fs-20"><i
                                                class='bx bxs-wallet-alt'></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-fill">
                                        <p class="mb-1 fs-5 fw-semibold text-default">
                                            <?php
                                            $cardToday = $HN->get_row("SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed' AND DATE(`created_time`) = CURDATE()")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed' AND DATE(`created_time`) = CURDATE()")['SUM(`price`)'] : 0;
                                            echo format_currency($cardToday);
                                            ?>
                                        </p>
                                        <p class="mb-0 text-muted">Hôm nay
                                        </p>
                                    </div>
                                    <div class="ms-2">
                                        <span class="avatar text-bg-primary rounded-circle fs-20"><i
                                                class='bx bxs-wallet-alt'></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">THỐNG KÊ NẠP THẺ THÁNG 05</div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartjs-line" class="chartjs-chart"></canvas>
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
                                        $tienNap = $HN->get_row("SELECT SUM(`price`) AS total FROM `cards` WHERE `status` = 'completed' AND DAY(`created_time`) = $day AND MONTH(`created_time`) = MONTH(CURRENT_DATE()) AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['total'];
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
                                    document.getElementById('chartjs-line'),
                                    config
                                );



                            })();
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            LỊCH SỬ NẠP THẺ CÀO
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <div class="row row-cols-lg-auto g-3 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="recharge-card">
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="<?= $user_id ?>" name="user_id"
                                        placeholder="Search ID User">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="<?= $username ?>" name="username"
                                        placeholder="Search Username">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="<?= $pin ?>" name="pin"
                                        placeholder="Search Pin">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="<?= $serial ?>" name="serial"
                                        placeholder="Search Serial">
                                </div>
                                <div class="col-md-3 col-6">
                                    <select class="form-control form-control-sm mb-1" name="status">
                                        <option <?= $status == '' ? 'selected' : ''; ?> value="">Trạng thái</option>
                                        <option <?= $status == 'pending' ? 'selected' : ''; ?> value="pending">Đang chờ xử
                                            lý</option>
                                        <option <?= $status == 'completed' ? 'selected' : ''; ?> value="completed">Thành
                                            công</option>
                                        <option <?= $status == 'error' ? 'selected' : ''; ?> value="error">Thẻ lỗi</option>
                                    </select>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input type="text" name="create_date" class="form-control form-control-sm"
                                        id="daterange" value="<?= $create_date ?>" placeholder="Chọn thời gian">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i>
                                        Search </button>
                                    <a class="btn btn-sm btn-danger"
                                        href="<?= base_url("?module=admin&action=recharge-card") ?>"><i
                                            class="fa fa-trash"></i>
                                        Clear filter </a>
                                </div>
                            </div>
                            <div class="top-filter">
                                <div class="filter-show">
                                    <label class="filter-label">Show :</label>
                                    <select name="limit" onchange="this.form.submit()"
                                        class="form-select filter-select">
                                        <option <?= $limit == 5 ? 'selected' : ''; ?> value="5">5</option>
                                        <option <?= $limit == 10 ? 'selected' : ''; ?> value="10">10</option>
                                        <option <?= $limit == 20 ? 'selected' : ''; ?> value="20">20</option>
                                        <option <?= $limit == 50 ? 'selected' : ''; ?> value="50">50</option>
                                        <option <?= $limit == 100 ? 'selected' : ''; ?> value="100">100</option>
                                        <option <?= $limit == 500 ? 'selected' : ''; ?> value="500">500</option>
                                        <option <?= $limit == 1000 ? 'selected' : ''; ?> value="1000">1000</option>
                                    </select>
                                </div>
                                <div class="filter-short">
                                    <label class="filter-label">Short by Date:</label>
                                    <select name="shortByDate" onchange="this.form.submit()"
                                        class="form-select filter-select">
                                        <option <?= $shortByDate == '' ? 'selected' : ''; ?> value="">Tất cả</option>
                                        <option <?= $shortByDate == 1 ? 'selected' : ''; ?> value="1">Hôm nay </option>
                                        <option <?= $shortByDate == 2 ? 'selected' : ''; ?> value="2">Tuần này </option>
                                        <option <?= $shortByDate == 3 ? 'selected' : ''; ?> value="3">Tháng này </option>
                                    </select>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive mb-3">
                            <table class="table text-nowrap table-striped table-hover table-bordered">
                                <thead class="table">
                                    <tr>
                                        <th>Username</th>
                                        <th class="text-center">Telco</th>
                                        <th class="text-center">Serial</th>
                                        <th class="text-center">Pin</th>
                                        <th class="text-center">Mệnh giá</th>
                                        <th class="text-center">Thực nhận</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Create date</th>
                                        <th class="text-center">Update date</th>
                                        <th class="text-center">Lý do</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cards as $row): ?>
                                        <tr>
                                            <td class="text-center">
                                                <a class="text-primary"
                                                    href="<?= base_url("?module=admin&action=user-edit&id=") ?><?= $row['user_id'] ?>">
                                                    <?= $HN->get_row("SELECT * FROM `users` WHERE `id` = '" . $row['user_id'] . "'")['username'] . " " . '[ID ' . $row['user_id'] . ']' ?>
                                                </a>
                                            </td>
                                            <td class="text-center"><?= $row['telco'] ?></td>
                                            <td class="text-center"><?= $row['serial'] ?></td>
                                            <td class="text-center"><?= $row['pin'] ?></td>
                                            <td class="text-right"><b
                                                    style="color: red;"><?= format_currency($row['amount']) ?></b></td>
                                            <td class="text-right"><b
                                                    style="color: green;"><?= format_currency($row['price']) ?></b></td>
                                            <td class="text-center"><?= display_card($row['status']) ?></td>
                                            <td><?= $row['created_time'] ?></td>
                                            <td><?= $row['updated_time'] ?></td>
                                            <td><?= $row['reason'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <div class="float-right">
                                                Tổng nạp: <strong style="color:red;">
                                                    <?= format_currency($HN->get_row("SELECT SUM(`amount`) FROM `cards` WHERE `status` = 'completed'")['SUM(`amount`)']); ?>
                                                </strong>
                                                | Thực nhận: <strong
                                                    style="color:blue;"><?= format_currency($HN->get_row("SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed'")['SUM(`price`)']); ?></strong>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <p class="page-info">Showing <?= min($limit, $totalRecords) ?> of <?= $totalRecords ?>
                                    Results</p>
                            </div>
                            <div class="col-sm-12 col-md-7 mb-3">
                                <div class="pagination-style-1">
                                    <ul class="pagination mb-0">
                                        <?= $limit < $totalRecords ? $pagination : ""; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace("notice_napthe");
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var button = document.getElementById('open-card-config');
        var card = document.getElementById('card-config');
        button.addEventListener('click', function () {
            if (card.style.display === 'none' || card.style.display === '') {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
<?php
require_once(__DIR__ . '/footer.php');

?>