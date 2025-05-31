<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Danh Sách Khóa IP | Quản Lý Website'
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

$where = " `id` > 0 ";
$ip = isset($_GET['ip']) ? $_GET['ip'] : '';
$create_gettime = isset($_GET['create_gettime']) ? $_GET['create_gettime'] : '';
$from = ($page - 1) * $limit;
$sql = [];
if ($ip) {
    $sql[] = "`ip` LIKE '%$ip%'";
}
if (!empty($create_gettime)) {
    if (strpos($create_gettime, ' to ') !== false) {
        list($startDate, $endDate) = explode(' to ', $create_gettime);
    } else {
        $startDate = $endDate = $create_gettime;
    }
    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
    $sql[] = "`created_time` BETWEEN '$startDate' AND '$endDate'";
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
$totalRecords = $HN->num_rows("SELECT * FROM `ip_block_log` WHERE $where $sqlReal ORDER BY `id` DESC ");
$totalPages = ceil($totalRecords / $limit);
$list_ip = $HN->get_list("SELECT * FROM `ip_block_log` WHERE $where $sqlReal  ORDER BY `id` DESC LIMIT $limit OFFSET $from ");
$pagination = pagination(base_url("?module=admin&action=block-ip&ip=$ip&create_gettime=$create_gettime&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-ban"></i> Block IP</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH IP BỊ CHẶN
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <div class="row row-cols-lg-auto g-3 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="block-ip">
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control form-control-sm" value="<?= $ip ?>" name="ip"
                                        placeholder="Tìm IP">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input type="text" name="create_gettime" class="form-control form-control-sm"
                                        id="daterange" value="<?= $create_gettime ?>" placeholder="Chọn thời gian">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-hero btn-sm btn-primary"><i class="fa fa-search"></i>
                                        Search </button>
                                    <a class="btn btn-hero btn-sm btn-danger" href="<?= admin_url("block-ip") ?>"><i
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
                                        <th class="text-center">Địa chỉ IP</th>
                                        <th class="text-center">Attempts</th>
                                        <th class="text-center">Banned</th>
                                        <th class="text-center">Lý do</th>
                                        <th class="text-center">Thời gian</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list_ip as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $row['ip']; ?></td>
                                            <td class="text-center"><?= $row['attempt']; ?></td>
                                            <td class="text-center">
                                                <?= status_user($row['status_ban']); ?>
                                            </td>
                                            <td class="text-center"><?= $row['reason']; ?></td>
                                            <td class="text-center"><?= $row['created_time']; ?></td>
                                            <td class="text-center">
                                                <button type="button" onclick="deleteRow(`<?= $row['id'] ?>`)"
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
    function deleteRow(id) {
        Swal.fire({
            title: 'Xác nhận xóa IP',
            text: "Bạn có chắc chắn muốn xóa IP này không ?",
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
                        action: 'removeIP'
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            new Notify({
                                status: 'success',
                                title: 'Thành công',
                                text: response.msg,
                                autotimeout: 3000,
                            })
                            setTimeout("location.href = '<?= base_url('?module=admin&action=block-ip'); ?>';", 2000);
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