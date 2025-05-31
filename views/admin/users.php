<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Danh Sách Thành Viên | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
?>
<?php
$limit = isset($_GET['limit']) ? intval(check_string($_GET['limit'])) : 10;
$page = isset($_GET['page']) ? intval(check_string($_GET['page'])) : 1;
$shortByDate = isset($_GET['shortByDate']) ? check_string($_GET['shortByDate']) : '';
$from = ($page - 1) * $limit;
$where = " `id` > 0 ";

$id = isset($_GET['id']) ? $_GET['id'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$phone = isset($_GET['phone']) ? $_GET['phone'] : '';
$ip = isset($_GET['ip']) ? $_GET['ip'] : '';
$level = isset($_GET['level']) ? $_GET['level'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$money = isset($_GET['money']) ? $_GET['money'] : '';
$order_by = ' ORDER BY `id` DESC ';
$sql = [];

if ($id) {
    $sql[] = "`id` = $id";
}
if ($username) {
    $sql[] = "`username` LIKE '%$username%'";
}
if ($name) {
    $sql[] = "`full_name` LIKE '%$name%'";
}
if ($email) {
    $sql[] = "`email` LIKE '%$email%'";
}
if ($phone) {
    $sql[] = "`phone_number` LIKE '%$phone%'";
}
if ($ip) {
    $sql[] = "`ip` LIKE '%$ip%'";
}
if ($level !== '') {
    $sql[] = "`level` = '$level'";
}
if ($status) {
    $sql[] = "`status_ban` = '$status'";
}
if ($money) {
    if ($money == 1) {
        $order_by = ' ORDER BY `money` ASC ';
    } else if ($money == 2) {
        $order_by = ' ORDER BY `money` DESC ';
    }
}
if ($shortByDate) {
    if ($shortByDate == '1') {
        $sql[] = "DATE(`created_time`) = CURDATE()";
    } elseif ($shortByDate == '2') {
        $sql[] = "YEARWEEK(`created_time`, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($shortByDate == '3') {
        $sql[] = "MONTH(`created_time`) = MONTH(CURDATE()) AND YEAR(`created_time`) = YEAR(CURDATE())";
    }
}
if (!empty($sql)) {
    $sqlReal = " AND " . implode(' AND ', $sql);
} else {
    $sqlReal = '';
}
$totalRecords = $HN->num_rows("SELECT * FROM `users` WHERE $where $sqlReal $order_by");
$totalPages = ceil($totalRecords / $limit);
$users = $HN->get_list("SELECT * FROM `users` WHERE $where $sqlReal $order_by LIMIT $limit OFFSET $from");
$pagination = pagination(base_url("?module=admin&action=users&id=$id&username=$username&name=$name&email=$email&phone=$phone&ip=$ip&status=$status&level=$level&money=$money&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-users"></i> Danh sách thành viên</h1>
        </div>
        <div class="row">
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-primary">
                                    <svg class="svg-white" xmlns="http://www.w3.org/2000/svg"
                                        enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px"
                                        fill="#000000">
                                        <rect fill="none" height="24" width="24"></rect>
                                        <g>
                                            <path
                                                d="M4,13c1.1,0,2-0.9,2-2c0-1.1-0.9-2-2-2s-2,0.9-2,2C2,12.1,2.9,13,4,13z M5.13,14.1C4.76,14.04,4.39,14,4,14 c-0.99,0-1.93,0.21-2.78,0.58C0.48,14.9,0,15.62,0,16.43V18l4.5,0v-1.61C4.5,15.56,4.73,14.78,5.13,14.1z M20,13c1.1,0,2-0.9,2-2 c0-1.1-0.9-2-2-2s-2,0.9-2,2C18,12.1,18.9,13,20,13z M24,16.43c0-0.81-0.48-1.53-1.22-1.85C21.93,14.21,20.99,14,20,14 c-0.39,0-0.76,0.04-1.13,0.1c0.4,0.68,0.63,1.46,0.63,2.29V18l4.5,0V16.43z M16.24,13.65c-1.17-0.52-2.61-0.9-4.24-0.9 c-1.63,0-3.07,0.39-4.24,0.9C6.68,14.13,6,15.21,6,16.39V18h12v-1.61C18,15.21,17.32,14.13,16.24,13.65z M8.07,16 c0.09-0.23,0.13-0.39,0.91-0.69c0.97-0.38,1.99-0.56,3.02-0.56s2.05,0.18,3.02,0.56c0.77,0.3,0.81,0.46,0.91,0.69H8.07z M12,8 c0.55,0,1,0.45,1,1s-0.45,1-1,1s-1-0.45-1-1S11.45,8,12,8 M12,6c-1.66,0-3,1.34-3,3c0,1.66,1.34,3,3,3s3-1.34,3-3 C15,7.34,13.66,6,12,6L12,6z">
                                            </path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?php
                                        $allUser = $HN->num_rows("SELECT * FROM `users`") != null ? $HN->num_rows("SELECT * FROM `users`") : 0;
                                        echo format_cash($allUser); ?>
                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">TỔNG THÀNH VIÊN</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-secondary">
                                    <i class="fa-solid fa-money-bill fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?php
                                        $moneyAllUser = $HN->get_row("SELECT SUM(`money`) FROM `users`")['SUM(`money`)'] != NULL ? $HN->get_row("SELECT SUM(`money`) FROM `users`")['SUM(`money`)'] : 0;
                                        echo format_currency($moneyAllUser);
                                        ?>
                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">SỐ DƯ CÒN LẠI</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-warning">
                                    <i class="fa-solid fa-user-tie fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?php
                                        $admin = $HN->num_rows("SELECT * FROM `users` WHERE `admin` = 'on'") != null ? $HN->num_rows("SELECT * FROM `users`  WHERE `admin` = 'on'") : 0;
                                        echo format_cash($admin);
                                        ?>
                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">ADMIN</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-2 bg-danger">
                                    <i class="fa-solid fa-lock fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex mb-1 align-items-top justify-content-between">
                                    <h5 class="fw-semibold mb-0 lh-1">
                                        <?php
                                        $banned = $HN->num_rows("SELECT * FROM `users` WHERE `status_ban` = 'on'") != null ? $HN->num_rows("SELECT * FROM `users`  WHERE `status_ban` = 'on'") : 0;
                                        echo format_cash($banned);
                                        ?>
                                    </h5>
                                </div>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Banned</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH THÀNH VIÊN
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <div class="row row-cols-lg-auto g-3 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="users">
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control" type="number" value="<?= $id ?>" name="id"
                                        placeholder="ID Khách hàng">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control" type="text" value="<?= $username ?>" name="username"
                                        placeholder="Username">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control" value="<?= $name ?>" name="name"
                                        placeholder="Full name">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control" value="<?= $email ?>" name="email" placeholder="Email">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control" value="<?= $phone ?>" name="phone" placeholder="Phone">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control" value="<?= $ip ?>" name="ip" placeholder="Địa chỉ IP">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <select name="status" class="form-control">
                                        <option value="">Trạng thái</option>
                                        <option <?= $status == 'off' ? 'selected' : ''; ?> value="off">Active</option>
                                        <option <?= $status == 'on' ? 'selected' : ''; ?> value="on">Banned</option>
                                    </select>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <select name="level" class="form-control">
                                        <option value="">Level</option>
                                        <option <?= $level == 'user' ? 'selected' : ''; ?> value="user">User</option>
                                        <option <?= $level == 'ctv' ? 'selected' : ''; ?> value="ctv">Ctv</option>
                                        <option <?= $level == 'daily' ? 'selected' : ''; ?> value="daily">Đại Lý</option>
                                        <option <?= $level == 'npp' ? 'selected' : ''; ?> value="npp">Nhà Phân Phối
                                        </option>
                                        <option <?= $level == 'tongkho' ? 'selected' : ''; ?> value="tongkho">Tổng Kho
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <select name="money" class="form-control">
                                        <option <?= $money == '' ? 'selected' : ''; ?> value="">Sắp xếp số dư</option>
                                        <option <?= $money == 1 ? 'selected' : ''; ?> value="1">Tăng dần</option>
                                        <option <?= $money == 2 ? 'selected' : ''; ?> value="2">Giảm dần</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-hero btn-primary"><i class="fa fa-search"></i>
                                        Search </button>
                                    <a class="btn btn-hero btn-danger"
                                        href="<?= base_url("?module=admin&action=users") ?>"><i class="fa fa-trash"></i>
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
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" class="text-center">Số dư khả dụng</th>
                                        <th scope="col" class="text-center">Tổng nạp</th>
                                        <th scope="col" class="text-center">Level</th>
                                        <th scope="col" class="text-center">Admin</th>
                                        <th scope="col" class="text-center">Trạng thái</th>
                                        <th scope="col">Thời gian</th>
                                        <th scope="col" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $row): ?>
                                        <tr>
                                            <td>
                                                <a class="text-primary"
                                                    href="<?= base_url("?module=admin&action=user-edit&id=") ?><?= $row['id'] ?>"><?= $row['username'] . " [ID " . $row['id'] . "]" ?>
                                                </a>
                                            </td>
                                            <td>
                                                <i class="fa fa-envelope" aria-hidden="true"></i> <?= $row['email'] ?>
                                            </td>
                                            <td class="text-right">
                                                <b style="color:blue;"><?= format_currency($row['money']) ?></b>
                                            </td>
                                            <td class="text-right">
                                                <b style="color:red;"><?= format_currency($row['total_money']) ?></b>
                                            </td>
                                            <td class="text-center"><?= level_user($row['level']) ?></td>
                                            <td class="text-center"><?= check_level_admin($row['admin']) ?></td>
                                            <td class="text-center">
                                                <?= status_user($row['status_ban']) ?>
                                            </td>
                                            <td><span><?= $row['created_time'] ?></span></td>
                                            <td class="text-center fs-base">
                                                <a href="<?= base_url("?module=admin&action=user-edit&id=") ?><?= $row['id'] ?>"
                                                    class="btn btn-sm btn-primary shadow-primary btn-wave waves-effect waves-light"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                    <i class="fa fa-fw fa-edit"></i> Edit
                                                </a>
                                            </td>
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