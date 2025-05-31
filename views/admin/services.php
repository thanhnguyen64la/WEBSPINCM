<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Danh Sách Dịch Vụ | Quản Lý Website'
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

$type = isset($_GET['type']) ? $_GET['type'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';
$api = isset($_GET['api']) ? $_GET['api'] : '';
$from = ($page - 1) * $limit;
$order_by = ' ORDER BY `id` DESC ';

$sql = [];
if ($type) {
    $sql[] = "`type` LIKE '%$type%'";
}
if ($name) {
    $sql[] = "`name` LIKE '%$name%'";
}
if ($api) {
    $sql[] = "`api_server` LIKE '%$api%'";
}
if ($status !== '') {
    $sql[] = "`status` = '$status'";
}
if ($price) {
    if ($price == 1) {
        $order_by = ' ORDER BY `price` ASC ';
    } else if ($price == 2) {
        $order_by = ' ORDER BY `price` DESC ';
    }
}
if (!empty($sql)) {
    $sqlReal = " AND " . implode(' AND ', $sql);
} else {
    $sqlReal = '';
}

$totalRecords = $HN->num_rows("SELECT * FROM `services` WHERE $where $sqlReal $order_by");
$totalPages = ceil($totalRecords / $limit);
$services = $HN->get_list("SELECT * FROM `services` WHERE $where $sqlReal $order_by LIMIT $limit OFFSET $from");
$pagination = pagination(base_url("?module=admin&action=services&type=$type&name=$name&status=$status&price=$price&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);

?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-cart-shopping"></i> Services</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH DỊCH VỤ
                        </div>
                        <div class="d-flex">
                            <a type="button" href="<?= base_url("?module=admin&action=service-add") ?>"
                                class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light"><i
                                    class="ri-add-line fw-semibold align-middle"></i> Thêm dịch vụ mới</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url("?module=admin&action=") ?>" class="align-items-center mb-3"
                            name="formSearch" method="GET">
                            <div class="row g-2 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="services">
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control" value="<?= $type ?>" name="type"
                                        placeholder="Type dịch vụ">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <input class="form-control" value="<?= $name ?>" name="name"
                                        placeholder="Tên dịch vụ">
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <select class="form-control" name="status">
                                        <option <?= $status == '' ? 'selected' : ''; ?> value="">-- Trạng thái --</option>
                                        <option <?= $status == 'off' ? 'selected' : ''; ?> value="off">Ẩn</option>
                                        <option <?= $status == 'on' ? 'selected' : ''; ?> value="on">Hiển Thị</option>
                                    </select>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <select class="form-control" name="api">
                                        <option <?= $api == '' ? 'selected' : ''; ?> value="">-- Server api --</option>
                                        <option <?= $api == 'MUASPIN' ? 'selected' : ''; ?> value="MUASPIN">MUASPIN
                                        </option>

                                    </select>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <select name="price" class="form-control">
                                        <option <?= $price == '' ? 'selected' : ''; ?> value="">Sắp xếp giá</option>
                                        <option <?= $price == 1 ? 'selected' : ''; ?> value="1">Tăng dần</option>
                                        <option <?= $price == 2 ? 'selected' : ''; ?> value="2">Giảm dần</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-hero btn-primary"><i class="fa fa-search"></i>
                                        Search </button>
                                    <a class="btn btn-hero btn-danger"
                                        href="<?= base_url("?module=admin&action=services") ?>"><i
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
                            </div>
                        </form>
                        <div class="table-responsive table-wrapper mb-3">
                            <table class="table text-nowrap table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Thao tác</th>
                                        <th class="text-center">Tên dịch vụ</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Mô tả</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Giá bán</th>
                                        <th class="text-center">Giá api</th>
                                        <th class="text-center">Server api</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $row): ?>
                                        <tr>
                                            <td class="text-center">
                                                <a type="button"
                                                    href="<?= base_url("?module=admin&action=service-edit&id=") ?><?= $row['id'] ?>"
                                                    class="btn btn-sm btn-secondary shadow-secondary btn-wave waves-effect waves-light"
                                                    data-bs-toggle="tooltip" aria-label="Chỉnh sửa"
                                                    data-bs-original-title="Chỉnh sửa">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a type="button" onclick="remove('<?= $row['id'] ?>')"
                                                    class="btn btn-sm btn-danger shadow-danger btn-wave waves-effect waves-light"
                                                    data-bs-toggle="tooltip" aria-label="Xóa" data-bs-original-title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <strong><a class="text-primary"
                                                        href="<?= base_url("?module=admin&action=service-edit&id=") ?><?= $row['id'] ?>"><?= $row['name'] ?></a>
                                                </strong>
                                            </td>
                                            <td class="text-center">
                                                <?= $row['type'] ?>
                                            </td>
                                            <td>
                                                <?php if ($row['description'] != null): ?>
                                                    <div id="short-<?= $row['id']; ?>">
                                                        <?= substr(strip_tags($row['description']), 0, 10); ?>...
                                                    </div>
                                                    <div id="hidden<?= $row['id']; ?>" style="display: none;">
                                                        <?= $row['description']; ?>
                                                    </div>
                                                    <a class="text-primary" href="javascript:void(0)"
                                                        id="read-hide<?= $row['id']; ?>" style="display: none;">Ẩn bớt</a>
                                                    <a class="text-primary" href="javascript:void(0)"
                                                        id="read-more<?= $row['id']; ?>">Hiển thị thêm</a>
                                                    <script>
                                                        $(document).ready(function () {
                                                            $("#read-more<?= $row['id']; ?>").click(function () {
                                                                $("#hidden<?= $row['id']; ?>").show();
                                                                $(this).hide();
                                                                $("#short-<?= $row['id']; ?>").hide();
                                                                $("#read-hide<?= $row['id']; ?>").show();
                                                            });
                                                            $("#read-hide<?= $row['id']; ?>").click(function () {
                                                                $("#hidden<?= $row['id']; ?>").hide();
                                                                $(this).hide();
                                                                $("#short-<?= $row['id']; ?>").show();
                                                                $("#read-more<?= $row['id']; ?>").show();
                                                            });
                                                        });
                                                    </script>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= display_service($row['status']) ?>
                                            </td>
                                            <td class="text-center">
                                                <b style="color:red;"><?= format_currency($row['price']) ?> </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color:green;"><?= format_currency($row['api_price']) ?> </b>
                                            </td>
                                            <td class="text-center">
                                                <?= $row['api_server'] ?>
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
    function remove(id) {
        Swal.fire({
            title: 'Xác nhận xóa dịch vụ',
            text: "Bạn có chắc chắn muốn xóa dịch vụ ID " + id + " không ?",
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
                        action: 'removeService'
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            new Notify({
                                status: 'success',
                                title: 'Thành công',
                                text: response.msg,
                                autotimeout: 3000,
                            })
                            setTimeout("location.href = '<?= base_url('?module=admin&action=services'); ?>';", 2000);
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