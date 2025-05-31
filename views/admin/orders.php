<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Danh Sách Đơn Hàng | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
?>
<?php
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$shortByDate = isset($_GET['shortByDate']) ? $_GET['shortByDate'] : '';
$where = " `orders`.`id` > 0 ";
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$trans_id = isset($_GET['trans_id']) ? $_GET['trans_id'] : '';
$invite_code = isset($_GET['invite_code']) ? $_GET['invite_code'] : '';
$service = isset($_GET['service']) ? $_GET['service'] : '';
$createdate = isset($_GET['createdate']) ? $_GET['createdate'] : '';
$from = ($page - 1) * $limit;
$sql = [];
if ($user_id) {
    $sql[] = "`orders`.`user_id` = $user_id";
}
if ($username) {
    $sql[] = "`users`.`username` = '$username'";
}
if ($trans_id) {
    $sql[] = "`orders`.`trans_id` LIKE '%$trans_id%'";
}
if ($invite_code) {
    $sql[] = "`orders`.`invite_code` LIKE '%$invite_code%'";
}
if ($service) {
    $sql[] = "`orders`.`service_id` = '$service'";
}
if ($status !== '') {
    $sql[] = "`orders`.`status` = '$status'";
}
if (!empty($createdate)) {
    if (strpos($createdate, ' to ') !== false) {
        list($startDate, $endDate) = explode(' to ', $createdate);
    } else {
        $startDate = $endDate = $createdate;
    }
    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
    $sql[] = "`orders`.`created_time` BETWEEN '$startDate' AND '$endDate'";
}
if ($shortByDate) {
    if ($shortByDate == '1') {
        $sql[] = "DATE(`orders`.`created_time`) = CURDATE()";
    } elseif ($shortByDate == '2') {
        $sql[] = "YEARWEEK(`orders`.`created_time`, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($shortByDate == '3') {
        $sql[] = "MONTH(`orders`.`created_time`) = MONTH(CURDATE()) AND YEAR(`orders`.`created_time`) = YEAR(CURDATE())";
    }
}
if (!empty($sql)) {
    $sqlReal = " AND " . implode(' AND ', $sql);
} else {
    $sqlReal = '';
}
$totalRecords = $HN->num_rows("SELECT `orders`.*, `users`.`username` FROM `orders` INNER JOIN `users` ON `orders`.`user_id` = `users`.`id` WHERE $where $sqlReal ORDER BY `orders`.`id` DESC ");
$totalPages = ceil($totalRecords / $limit);
$orders = $HN->get_list("SELECT `orders`.*, `users`.`username` FROM `orders` INNER JOIN `users` ON `orders`.`user_id` = `users`.`id` WHERE $where $sqlReal  ORDER BY `orders`.`id` DESC LIMIT $limit OFFSET $from ");
$pagination = pagination(base_url("?module=admin&action=orders&user_id=$user_id&username=$username&trans_id=$trans_id&service=$service&status=$status&createdate=$createdate&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-cart-shopping"></i> Đơn hàng</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH ĐƠN HÀNG
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <div class="row g-2 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="orders">
                                <div class="col-md-4 col-6">
                                    <input class="form-control" value="<?= $user_id ?>" name="user_id"
                                        placeholder="ID User">
                                </div>
                                <div class="col-md-4 col-6">
                                    <input class="form-control" value="<?= $username ?>" name="username"
                                        placeholder="Username">
                                </div>
                                <div class="col-md-4 col-6">
                                    <input class="form-control" value="<?= $trans_id ?>" name="trans_id"
                                        placeholder="Mã đơn hàng">
                                </div>
                                <div class="col-md-4 col-6">
                                    <input class="form-control" value="<?= $invite_code ?>" name="invite_code"
                                        placeholder="Mã Invite">
                                </div>
                                <div class="col-md-4 col-6 ">
                                    <select class="form-control form-control-md" name="service">
                                        <option value="">Dịch vụ</option>
                                        <?php foreach ($HN->get_list("SELECT * FROM `services` ") as $row): ?>
                                            <option <?= $service == $row['id'] ? 'selected' : ''; ?>
                                                value="<?= $row['id']; ?>">
                                                <?= $row['name']; ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-6 ">
                                    <select class="form-control form-control-md" name="status">
                                        <option <?= $status == '' ? 'selected' : ''; ?> value="">Trạng thái</option>
                                        <option <?= $status == "pending" ? 'selected' : ''; ?> value="pending">Đang chạy
                                        </option>
                                        <option <?= $status == "completed" ? 'selected' : ''; ?> value="completed">Thành
                                            công </option>
                                        <option <?= $status == "refund" ? 'selected' : ''; ?> value="refund">Hoàn tiền
                                        </option>
                                        <option <?= $status == "error" ? 'selected' : ''; ?> value="error">Hủy</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-6">
                                    <input type="text" name="createdate" class="form-control flatpickr-input"
                                        id="daterange" value="<?= $createdate ?>" placeholder="Chọn thời gian"
                                        readonly="readonly">
                                </div>
                                <div class="col-md-3 col-6">
                                    <button class="btn btn-hero btn-primary"><i class="fa fa-search"></i>
                                        Search </button>
                                    <a class="btn btn-hero btn-danger"
                                        href="<?= base_url("?module=admin&action=orders") ?>"><i
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
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Đơn hàng</th>
                                        <th class="text-center">Thông tin</th>
                                        <th class="text-center">Thanh toán</th>
                                        <th class="text-center">Thời gian</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $row['id'] ?></td>
                                            <td>
                                                <a class="text-primary"
                                                    href="<?= base_url("?module=admin&action=user-edit&id=") ?><?= $row['user_id'] ?>">
                                                    <?= $HN->get_row("SELECT * FROM `users` WHERE `id` = '" . $row['user_id'] . "'")['username'] . " " . '[ID ' . $row['user_id'] . ']' ?>
                                                </a>
                                            </td>
                                            <td>
                                                Mã đơn hàng: #<strong><?= $row['trans_id']; ?></strong><br>
                                                Tên dịch vụ: <a class="text-primary"
                                                    href="<?= base_url("?module=admin&action=service-edit&id=") ?><?= $row['service_id'] ?>">
                                                    <?= $HN->get_row("SELECT * FROM `services` WHERE `id` = '" . $row['service_id'] . "'")['name'] ?>
                                                </a><br>
                                                Server api:
                                                <strong><?= $HN->get_row("SELECT * FROM `services` WHERE `id` = '" . $row['service_id'] . "'")['api_server'] ?></strong>
                                            </td>
                                            <td>
                                                Mã invite: <strong><?= $row['invite_code'] ?></strong></br>
                                                Name: <strong><?= $row['name'] ?></strong></br>
                                                Số lượng: <strong><?= format_cash($row['amount']) ?></strong></br>
                                                Đang chạy: <strong><?= format_cash($row['remaining']) ?></strong></br>
                                            </td>
                                            <td>
                                                Đơn giá: <strong
                                                    style="color:red;"><?= format_currency($row['price']) ?></strong><br>
                                                Giá api: <strong
                                                    style="color:gren;"><?= format_currency($row['api_price']) ?></strong><br>
                                                Tiền lãi: <strong
                                                    style="color:blue;"><?= format_currency($row['price'] - $row['api_price']) ?></strong><br>

                                            </td>
                                            <td>
                                                Created-time: <strong data-toggle="tooltip" data-placement="bottom"
                                                    data-bs-original-title="<?= format_time($row['created_time']) ?>">
                                                    <?= $row['created_time'] ?>
                                                </strong>
                                                <br>
                                                Updated-time: <strong data-toggle="tooltip" data-placement="bottom"
                                                    data-bs-original-title="<?= format_time($row['updated_time']) ?>">
                                                    <?= $row['updated_time'] ?>
                                                </strong>
                                            </td>
                                            <td class="text-center">
                                                <?= status_link($row['status']) ?>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" onclick="deleteOrder(`<?= $row['id'] ?>`)"
                                                    class="btn btn-danger btn-sm shadow-danger btn-wave waves-effect waves-light">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
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
<script>
    function deleteOrder(id) {
        Swal.fire({
            title: 'Xác nhận xóa đơn hàng',
            text: "Bạn có chắc chắn muốn xóa đơn hàng ID " + id + " không ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Huỷ bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url("ajaxs/admin/remove.php") ?>",
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        action: 'removeOrder'
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            new Notify({
                                status: 'success',
                                title: 'Thành công',
                                text: response.msg,
                                autotimeout: 3000,
                            })
                            setTimeout("location.href = '<?= base_url('?module=admin&action=orders'); ?>';", 2000);
                        } else {
                            new Notify({
                                status: 'error',
                                title: 'Thất bại',
                                text: response.msg,
                                autotimeout: 3000,
                            })
                        }
                    },
                    error: function (status) {
                        console.log(status);
                        new Notify({
                            status: 'error',
                            title: 'Thất bại',
                            text: 'Đã có lỗi xảy ra, vui lòng thử lại sau',
                            autotimeout: 5000,
                        })
                    }
                });
            }
        });
    }
</script>