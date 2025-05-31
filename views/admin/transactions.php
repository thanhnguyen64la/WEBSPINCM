<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Biến Động Số Dư | Quản Lý Website'
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
$where = " `cash_flow`.`id` > 0 ";
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$noidung = isset($_GET['noidung']) ? $_GET['noidung'] : '';
$createdate = isset($_GET['create_date']) ? $_GET['create_date'] : '';
$from = ($page - 1) * $limit;
$sql = [];
if ($user_id) {
    $sql[] = "`cash_flow`.`user_id` = $user_id";
}
if ($username) {
    $sql[] = "`users`.`username` = '$username'";
}
if ($noidung) {
    $sql[] = "`cash_flow``reason` LIKE '%$noidung%'";
}
if (!empty($createdate)) {
    if (strpos($createdate, ' to ') !== false) {
        list($startDate, $endDate) = explode(' to ', $createdate);
    } else {
        $startDate = $endDate = $createdate;
    }
    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
    $sql[] = "`cash_flow`.`created_time` BETWEEN '$startDate' AND '$endDate'";
}
if ($shortByDate) {
    if ($shortByDate == '1') {
        $sql[] = "DATE(`cash_flow`.`created_time`) = CURDATE()";
    } elseif ($shortByDate == '2') {
        $sql[] = "YEARWEEK(`cash_flow`.`created_time`, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($shortByDate == '3') {
        $sql[] = "MONTH(`cash_flow`.`created_time`) = MONTH(CURDATE()) AND YEAR(`cash_flow`.`created_time`) = YEAR(CURDATE())";
    }
}
if (!empty($sql)) {
    $sqlReal = " AND " . implode(' AND ', $sql);
} else {
    $sqlReal = '';
}
$totalRecords = $HN->num_rows("SELECT `cash_flow`.*, `users`.`username` FROM `cash_flow` INNER JOIN `users` ON `cash_flow`.`user_id` = `users`.`id` WHERE $where $sqlReal ORDER BY `cash_flow`.`id` DESC ");
$totalPages = ceil($totalRecords / $limit);
$transactions = $HN->get_list("SELECT `cash_flow`.*, `users`.`username` FROM `cash_flow` INNER JOIN `users` ON `cash_flow`.`user_id` = `users`.`id` WHERE $where $sqlReal ORDER BY `cash_flow`.`id` DESC  LIMIT $limit OFFSET $from");
$pagination = pagination(base_url("?module=admin&action=transactions&user_id=$user_id&username=$username&noidung=$noidung&create_date=$createdate&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-money-bill-transfer"></i> Biến động số
                dư</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            NHẬT KÝ THAY ĐỔI SỐ DƯ
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <div class="row row-cols-lg-auto g-3 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="transactions">
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control form-control-sm" value="<?= $user_id ?>" name="user_id"
                                        placeholder="ID User">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control form-control-sm" value="<?= $username ?>" name="username"
                                        placeholder="Username">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control form-control-sm" value="<?= $noidung ?>" name="noidung"
                                        placeholder="Lý do">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input type="text" name="create_date"
                                        class="form-control form-control-sm flatpickr-input" id="daterange"
                                        value="<?= $createdate ?>" placeholder="Chọn thời gian" readonly="readonly">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-hero btn-sm btn-primary"><i class="fa fa-search"></i>
                                        Search </button>
                                    <a class="btn btn-hero btn-sm btn-danger"
                                        href="<?= base_url("?module=admin&action=transactions") ?>"><i
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
                        <div class="table-responsive table-wrapper mb-3">
                            <table class="table text-nowrap table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Số dư trước</th>
                                        <th>Số dư thay đổi</th>
                                        <th>Số dư hiện tại</th>
                                        <th>Thời gian</th>
                                        <th>Lý do</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $row): ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td>
                                                <a class="text-primary"
                                                    href="<?= base_url("?module=admin&action=user-edit&id=") ?><?= $row['user_id'] ?>">
                                                    <?= $HN->get_row("SELECT * FROM `users` WHERE `id` = '" . $row['user_id'] . "'")['username'] . " " . '[ID ' . $row['user_id'] . ']' ?>
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                <span
                                                    class="badge bg-success-gradient"><?= format_currency($row['initial_amount']); ?></span>
                                            </td>
                                            <td class="text-right"><span
                                                    class="badge bg-danger-gradient"><?= format_currency($row['changed_amount']); ?></span>
                                            </td>
                                            <td class="text-right"><span
                                                    class="badge bg-primary-gradient"><?= format_currency($row['current_amount']); ?></span>
                                            </td>
                                            <td><span class="badge bg-light text-dark" data-toggle="tooltip"
                                                    data-placement="bottom"
                                                    data-bs-original-title="<?= format_time($row['created_time']); ?>"><?= $row['created_time']; ?></span>
                                            </td>
                                            <td><i><?= $row['reason']; ?></i></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
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