<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Nhật Ký Hoạt Động | Quản Lý Website'
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
$where = " `logs`.`id` > 0 ";
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$content = isset($_GET['content']) ? $_GET['content'] : '';
$ip = isset($_GET['ip']) ? $_GET['ip'] : '';
$device = isset($_GET['device']) ? $_GET['device'] : '';
$createdate = isset($_GET['createdate']) ? $_GET['createdate'] : '';
$from = ($page - 1) * $limit;
$sql = [];
if ($user_id) {
    $sql[] = "`logs`.`user_id` = $user_id";
}
if ($username) {
    $sql[] = "`users`.`username` = '$username'";
}
if ($content) {
    $sql[] = "`logs`.`action` LIKE '%$content%'";
}
if ($ip) {
    $sql[] = "`logs`.`ip` LIKE '%$ip%'";
}
if ($device) {
    $sql[] = "`logs`.`device` LIKE '%$device%'";
}
if (!empty($createdate)) {
    if (strpos($createdate, ' to ') !== false) {
        list($startDate, $endDate) = explode(' to ', $createdate);
    } else {
        $startDate = $endDate = $createdate;
    }
    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
    $sql[] = "`logs`.`created_time` BETWEEN '$startDate' AND '$endDate'";
}
if ($shortByDate) {
    if ($shortByDate == '1') {
        $sql[] = "DATE(`logs`.`created_time`) = CURDATE()";
    } elseif ($shortByDate == '2') {
        $sql[] = "YEARWEEK(`logs`.`created_time`, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($shortByDate == '3') {
        $sql[] = "MONTH(`logs`.`created_time`) = MONTH(CURDATE()) AND YEAR(`logs`.`created_time`) = YEAR(CURDATE())";
    }
}
if (!empty($sql)) {
    $sqlReal = " AND " . implode(' AND ', $sql);
} else {
    $sqlReal = '';
}
$totalRecords = $HN->num_rows("SELECT `logs`.*, `users`.`username` FROM `logs` INNER JOIN `users` ON `logs`.`user_id` = `users`.`id` WHERE $where $sqlReal ORDER BY `logs`.`id` DESC ");
$totalPages = ceil($totalRecords / $limit);
$logs = $HN->get_list("SELECT `logs`.*, `users`.`username` FROM `logs` INNER JOIN `users` ON `logs`.`user_id` = `users`.`id` WHERE $where $sqlReal  ORDER BY `logs`.`id` DESC LIMIT $limit OFFSET $from ");
$pagination = pagination(base_url("?module=admin&action=logs&user_id=$user_id&username=$username&content=$content&ip=$ip&device=$device&createdate=$createdate&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-clock-rotate-left"></i> Nhật ký hoạt
                động</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            NHẬT KÝ HOẠT ĐỘNG
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <div class="row row-cols-lg-auto g-3 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="logs">
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control form-control-sm" value="<?= $user_id ?>" name="user_id"
                                        placeholder="ID User">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control form-control-sm" value="<?= $username ?>" name="username"
                                        placeholder="Username">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control form-control-sm" value="<?= $content ?>" name="content"
                                        placeholder="Hành động">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control form-control-sm" value="<?= $ip ?>" name="ip"
                                        placeholder="Địa chỉ IP">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control form-control-sm" value="<?= $device ?>" name="device"
                                        placeholder="Thiết bị">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input type="text" name="createdate"
                                        class="form-control form-control-sm flatpickr-input" id="daterange"
                                        value="<?= $createdate ?>" placeholder="Chọn thời gian" readonly="readonly">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-hero btn-sm btn-primary"><i class="fa fa-search"></i>
                                        Search </button>
                                    <a class="btn btn-hero btn-sm btn-danger"
                                        href="<?= base_url("?module=admin&action=logs") ?>"><i class="fa fa-trash"></i>
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
                                        <th>Hành động</th>
                                        <th>Thời gian</th>
                                        <th>Địa chỉ IP</th>
                                        <th>Thiết bị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $row): ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td>
                                                <a class="text-primary"
                                                    href="<?= base_url("?module=admin&action=user-edit&id=") ?><?= $row['user_id'] ?>">
                                                    <?= $HN->get_row("SELECT * FROM `users` WHERE `id` = '" . $row['user_id'] . "'")['username'] . " " . '[ID ' . $row['user_id'] . ']' ?>
                                                </a>
                                            </td>
                                            <td><?= $row['action']; ?></td>
                                            <td><span class="badge bg-light text-dark" data-toggle="tooltip"
                                                    data-placement="bottom"
                                                    data-bs-original-title="<?= format_time($row['created_time']); ?>"><?= $row['created_time']; ?></span>
                                            </td>
                                            <td><span class="badge bg-danger-transparent"><?= $row['ip']; ?></span></td>
                                            <td><small><?= $row['device']; ?></small></td>
                                        </tr>
                                    <?php endforeach ?>
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