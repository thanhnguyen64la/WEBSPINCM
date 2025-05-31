<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Khuyến Mãi Nạp Tiền  | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
?>
<?php
if (isset($_POST["AddPromotion"])) {
    if (empty($_POST["bank_min"])) {
        msg_error_link('Vui lòng nhập số tiền', "", 2000);
    }
    $bank_min = check_string($_POST["bank_min"]);
    if ($bank_min <= 0) {
        msg_error_link('Số tiền không hợp lệ', "", 2000);
    }
    if (empty($_POST["discount"])) {
        msg_error_link('Vui lòng nhập phần trăm khuyến mãi', "", 2000);
    }
    $discount = check_string($_POST["discount"]);
    $isInsert = $HN->insert("promotions", ["discount" => $discount, "created_time" => get_time(), "bank_min" => $bank_min]);
    if ($isInsert) {
        msg_success_link('Thêm thành công', "", 2000);
    }
    msg_error_link('Thêm thất bại', "", 2000);
}
?>
<?php
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$shortByDate = isset($_GET['shortByDate']) ? $_GET['shortByDate'] : '';
$where = " `promotions`.`id` > 0 ";
$from = ($page - 1) * $limit;
$sql = [];
if ($shortByDate) {
    if ($shortByDate == '1') {
        $sql[] = "DATE(`promotions`.`created_time`) = CURDATE()";
    } elseif ($shortByDate == '2') {
        $sql[] = "YEARWEEK(`promotions`.`created_time`, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($shortByDate == '3') {
        $sql[] = "MONTH(`promotions`.`created_time`) = MONTH(CURDATE()) AND YEAR(`promotions`.`created_time`) = YEAR(CURDATE())";
    }
}
if (!empty($sql)) {
    $sqlReal = " AND " . implode(' AND ', $sql);
} else {
    $sqlReal = '';
}
$totalRecords = $HN->num_rows("SELECT * FROM `promotions`WHERE $where $sqlReal ORDER BY `id` DESC ");
$totalPages = ceil($totalRecords / $limit);
$promotions = $HN->get_list("SELECT * FROM `promotions` WHERE $where $sqlReal  ORDER BY `id` DESC LIMIT $limit OFFSET $from ");
$pagination = pagination(base_url("?module=admin&action=promotions&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-tags"></i> Khuyến Mãi Nạp Tiền</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH MỐC NẠP TIỀN
                        </div>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable2"
                            class="btn btn-sm btn-primary shadow-primary"><i
                                class="ri-add-line fw-semibold align-middle"></i> Tạo mốc nạp mới</button>
                    </div>
                    <div class="card-body">
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <div class="row row-cols-lg-auto g-3 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="promotions">
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
                                        <th class="text-center">Số tiền nạp tổi thiểu</th>
                                        <th class="text-center">Khuyến mãi thêm</th>
                                        <th class="text-center">Thời gian</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($promotions as $row): ?>
                                        <tr>
                                            <td class="text-center">
                                                <b style="font-size:15px;">
                                                    >= <?= format_currency($row['bank_min']); ?>
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <span style="font-size: 15px;" class="badge bg-primary">
                                                    <?= $row['discount']; ?>%
                                                </span>
                                            </td>
                                            <td class="text-center"> <?= $row['created_time']; ?></td>
                                            <td class="text-center">
                                                <a type="button" onclick="remove('<?= $row['id']; ?>')"
                                                    class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="fas fa-trash"></i> Delete
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
<div class="modal fade" id="exampleModalScrollable2" tabindex="-1" aria-labelledby="exampleModalScrollable2"
    data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel2"><i class="fa-solid fa-plus"></i> Tạo mốc nạp mới
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label">Nạp tối thiểu (<span
                                class="text-danger">*</span>)</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" class="form-control" name="bank_min" required>
                                <span class="input-group-text">
                                    <?= currency_default(); ?>
                                </span>
                            </div>
                            <small>Số tiền nạp tối thiểu để được nhận khuyến mãi</small>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Khuyến mãi (<span
                                class="text-danger">*</span>)</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" class="form-control" name="discount" required>
                                <span class="input-group-text">
                                    <i class="fa-solid fa-percent"></i>
                                </span>
                            </div>
                            <small>Nhập chiết khấu khuyến mãi VD: 10 (tức khuyến mãi 10% khi nhập nạp tiền đủ
                                mốc)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light " data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="AddPromotion" class="btn btn-primary shadow-primary btn-wave"><i
                            class="fa fa-fw fa-plus me-1"></i>
                        Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once(__DIR__ . '/footer.php');
?>
<script>
    function remove(id) {
        Swal.fire({
            title: 'Xác nhận xóa khuyến mãi nạp tiền',
            text: "Bạn có chắc chắn muốn xóa khuyến mãi nạp tiền ID " + id + " không ?",
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
                        action: 'removePromotion'
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            new Notify({
                                status: 'success',
                                title: 'Thành công',
                                text: response.msg,
                                autotimeout: 3000,
                            })
                            setTimeout("location.href = '<?= base_url('?module=admin&action=promotions'); ?>';", 2000);
                        } else {
                            new Notify({
                                status: 'error',
                                title: 'Thất bại',
                                text: response.msg,
                                autotimeout: 4000,
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