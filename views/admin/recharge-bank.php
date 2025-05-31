<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Thống Kê Ngân Hàng | Quản Lý Website'
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
$where = " `payment_bank`.`id` > 0 ";

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$tid = isset($_GET['tid']) ? $_GET['tid'] : '';
$bankName = isset($_GET['bankName']) ? $_GET['bankName'] : '';
$description = isset($_GET['description']) ? $_GET['description'] : '';
$create_time = isset($_GET['create_time']) ? $_GET['create_time'] : '';


$from = ($page - 1) * $limit;
$sql = [];
if ($user_id) {
    $sql[] = "`payment_bank`.`user_id` = $user_id";
}
if ($username) {
    $sql[] = "`users`.`username` = '$username'";
}
if ($tid) {
    $sql[] = "`payment_bank`.`tid` LIKE '%$tid%'";
}
if ($bankName) {
    $sql[] = "`payment_bank`.`method` LIKE '%$bankName%'";
}
if ($description) {
    $sql[] = "`payment_bank`.`description` LIKE '%$description%'";
}
if (!empty($create_time)) {
    if (strpos($create_time, ' to ') !== false) {
        list($startDate, $endDate) = explode(' to ', $create_time);
    } else {
        $startDate = $endDate = $create_time;
    }
    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
    $sql[] = "`payment_bank`.`created_time` BETWEEN '$startDate' AND '$endDate'";
}
if ($shortByDate) {
    if ($shortByDate == '1') {
        $sql[] = "DATE(`payment_bank`.`created_time`) = CURDATE()";
    } elseif ($shortByDate == '2') {
        $sql[] = "YEARWEEK(`payment_bank`.`created_time`, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($shortByDate == '3') {
        $sql[] = "MONTH(`payment_bank`.`created_time`) = MONTH(CURDATE()) AND YEAR(`payment_bank`.`created_time`) = YEAR(CURDATE())";
    }
}
if (!empty($sql)) {
    $sqlReal = " AND " . implode(' AND ', $sql);
} else {
    $sqlReal = '';
}
$totalRecords = $HN->num_rows("SELECT `payment_bank`.*, `users`.`username` FROM `payment_bank` INNER JOIN `users` ON `payment_bank`.`user_id` = `users`.`id` WHERE $where $sqlReal ORDER BY `payment_bank`.`id` DESC ");
$totalPages = ceil($totalRecords / $limit);
$payment_bank = $HN->get_list("SELECT `payment_bank`.*, `users`.`username` FROM `payment_bank` INNER JOIN `users` ON `payment_bank`.`user_id` = `users`.`id` WHERE $where $sqlReal  ORDER BY `payment_bank`.`id` DESC LIMIT $limit OFFSET $from ");
$pagination = pagination(base_url("?module=admin&action=recharge-bank&user_id=$user_id&username=$username&tid=$tid&bankName=$bankName&description=&create_time=$create_time&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Ngân hàng</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Nạp tiền</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ngân hàng</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="text-right">
                    <a class="btn btn-primary label-btn mb-3"
                        href="<?= base_url("?module=admin&action=recharge-bank-config") ?>">
                        <i class="ri-settings-4-line label-btn-icon me-2"></i> CẤU HÌNH
                    </a>
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
                                            $bankAll = $HN->get_row("SELECT SUM(`amount`) FROM `payment_bank`")['SUM(`amount`)'] != NULL ? $HN->get_row("SELECT SUM(`amount`) FROM `payment_bank`")['SUM(`amount`)'] : 0;
                                            echo format_currency($bankAll);
                                            ?>
                                        </p>
                                        <p class="mb-0 text-muted">Toàn thời gian</p>
                                    </div>
                                    <div class="ms-2">
                                        <span class="avatar text-bg-danger rounded-circle fs-20"><i
                                                class="bx bxs-wallet-alt"></i></span>
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
                                            $bankMonth = $HN->get_row("SELECT SUM(`amount`) FROM `payment_bank` WHERE YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`amount`)'] != NULL ? $HN->get_row("SELECT SUM(`amount`) FROM `payment_bank` WHERE YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`amount`)'] : 0;
                                            echo format_cash($bankMonth) . " đ";
                                            ?>
                                        </p>
                                        <p class="mb-0 text-muted">Tháng <?= date('m'); ?></p>
                                    </div>
                                    <div class="ms-2">
                                        <span class="avatar text-bg-info rounded-circle fs-20"><i
                                                class="bx bxs-wallet-alt"></i></span>
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
                                            $bankWeek = $HN->get_row("SELECT SUM(`amount`) FROM `payment_bank` WHERE YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND WEEK(`created_time`, 1) = WEEK(CURRENT_DATE(), 1)")['SUM(`amount`)'] != NULL ? $HN->get_row("SELECT SUM(`amount`) FROM `payment_bank` WHERE YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND WEEK(`created_time`, 1) = WEEK(CURRENT_DATE(), 1)")['SUM(`amount`)'] : 0;
                                            echo format_currency($bankWeek);
                                            ?>

                                        </p>
                                        <p class="mb-0 text-muted">Trong tuần</p>
                                    </div>
                                    <div class="ms-2">
                                        <span class="avatar text-bg-warning rounded-circle fs-20"><i
                                                class="bx bxs-wallet-alt"></i></span>
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
                                            $bankToday = $HN->get_row("SELECT SUM(`amount`) FROM `payment_bank` WHERE DATE(`created_time`) = CURDATE()")['SUM(`amount`)'] != NULL ? $HN->get_row("SELECT SUM(`amount`) FROM `payment_bank` WHERE DATE(`created_time`) = CURDATE()")['SUM(`amount`)'] : 0;
                                            echo format_currency($bankToday);
                                            ?>
                                        </p>
                                        <p class="mb-0 text-muted">Hôm nay
                                        </p>
                                    </div>
                                    <div class="ms-2">
                                        <span class="avatar text-bg-primary rounded-circle fs-20"><i
                                                class="bx bxs-wallet-alt"></i></span>
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
                        <div class="card-title">THỐNG KÊ NẠP TIỀN THÁNG 05</div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartjs-line" class="chartjs-chart" width="842" height="375"
                            style="display: block; box-sizing: border-box; height: 300px; width: 674px;"></canvas>
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
                                        $tienNap = $HN->get_row("SELECT SUM(`amount`) AS total FROM `payment_bank` WHERE DAY(`created_time`) = $day AND MONTH(`created_time`) = MONTH(CURRENT_DATE()) AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['total'];
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
                            LỊCH SỬ NẠP TIỀN TỰ ĐỘNG
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <div class="row row-cols-lg-auto g-3 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="recharge-bank">
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="<?= $user_id ?>" name="user_id"
                                        placeholder="Tìm ID thành viên">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="<?= $username ?>" name="username"
                                        placeholder="Tìm Username">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="<?= $tid ?>" name="tid"
                                        placeholder="Mã giao dịch">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="<?= $bankName ?>" name="bankName"
                                        placeholder="Ngân hàng">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="<?= $description ?>"
                                        name="description" placeholder="Nội dung chuyển khoản">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input type="text" name="create_time"
                                        class="form-control form-control-sm flatpickr-input" id="daterange"
                                        value="<?= $create_time ?>" placeholder="Chọn thời gian" readonly="readonly">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i>
                                        Search </button>
                                    <a class="btn btn-sm btn-danger"
                                        href="<?= base_url("?module=admin&action=recharge-bank") ?>"><i
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
                                <thead>
                                    <tr>
                                        <th class="text-center">Username</th>
                                        <th>Thời gian</th>
                                        <th class="text-right">Số tiền nạp</th>
                                        <th class="text-right">Thực nhận</th>
                                        <th class="text-center">Ngân hàng</th>
                                        <th class="text-center">Mã giao dịch</th>
                                        <th>Nội dung chuyển khoản</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payment_bank as $row): ?>
                                        <tr>
                                            <td class="text-center">
                                                <a class="text-primary"
                                                    href="<?= base_url("?module=admin&action=user-edit&id=") ?><?= $row['user_id'] ?>">
                                                    <?= $HN->get_row("SELECT * FROM `users` WHERE `id` = '" . $row['user_id'] . "'")['username'] . " " . '[ID ' . $row['user_id'] . ']' ?>
                                                </a>
                                            </td>
                                            <td><?= $row['created_time']; ?></td>
                                            <td class="text-right"><b
                                                    style="color: green;"><?= format_currency($row['amount']); ?></b>
                                            </td>
                                            <td class="text-right"><b
                                                    style="color: red;"><?= format_currency($row['received']); ?></b>
                                            </td>
                                            <td class="text-center"><b><?= $row['method']; ?></b></td>
                                            <td class="text-center"><b><?= $row['tid']; ?></b></td>
                                            <td><small><?= $row['description']; ?></small></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <div class="float-right">
                                                Đã thanh toán:
                                                <strong style="color:red;">
                                                    <?= format_currency($HN->get_row("SELECT SUM(`amount`) FROM `payment_bank`")['SUM(`amount`)']); ?>
                                                </strong>
                                                |
                                                Thực nhận: <strong style="color:blue;">
                                                    <?= format_currency($HN->get_row("SELECT SUM(`received`) FROM `payment_bank`")['SUM(`received`)']); ?>
                                                </strong>
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
<?php
require_once(__DIR__ . '/footer.php');

?>