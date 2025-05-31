<?php if (!defined('REQUEST')) {
    exit('The Request Not Found');
}
$body = [
    'title' => 'Cấu Hình Ngân Hàng | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['SaveSettings'])) {
    foreach ($_POST as $key => $value) {
        $HN->update("settings", array(
            'setting_value' => $value
        ), " `setting_key` = '$key' ");
    }
    msg_success_link('Lưu thành công', "", 2000);
} else {
    if (isset($_POST["ThemNganHang"])) {
        $url_image = "";
        if (check_img("image")) {
            $rand = random("0123456789QWERTYUIOPASDGHJKLZXCVBNM", 3);
            $uploads_dir = "assets/img/storage/" . "bank_" . $rand . ".png";
            $tmp_name = $_FILES["image"]["tmp_name"];
            $addlogo = move_uploaded_file($tmp_name, $uploads_dir);
            if ($addlogo) {
                $url_image = "assets/img/storage/" . "bank_" . $rand . ".png";
            }
        }
        $isInsert = $HN->insert("banks", ["image" => $url_image, "short_name" => check_string($_POST["short_name"]), "account_number" => check_string($_POST["account_number"]), "api_token" => check_string($_POST["api_token"]), "api_password" => check_string($_POST["api_password"]), "account_name" => check_string($_POST["account_name"]), "status" => check_string($_POST["status"])]);
        if ($isInsert) {
            msg_success_link('Thêm thành công', "", 2000);
            ;
        }
        msg_error_link('Thêm thất bại', "", 2000);
    }
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Cấu hình ngân hàng</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Nạp tiền</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url("?module=admin&action=recharge-bank") ?>">Ngân
                                hàng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cấu hình</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="text-right">
                    <a class="btn btn-danger label-btn mb-3"
                        href="<?= base_url("?module=admin&action=recharge-bank") ?>">
                        <i class="ri-arrow-go-back-line label-btn-icon me-2"></i> QUAY LẠI
                    </a>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            DANH SÁCH NGÂN HÀNG
                        </div>
                        <div class="d-flex">
                            <button data-bs-toggle="modal" data-bs-target="#exampleModalScrollable2"
                                class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light"><i
                                    class="ri-add-line fw-semibold align-middle"></i> Thêm ngân hàng</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="datatable-basic" class="table text-nowrap table-striped table-hover table-bordered"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Ngân hàng</th>
                                    <th class="text-center">Số tài khoản</th>
                                    <th class="text-center">Chủ tài khoản</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($HN->get_list("SELECT * FROM `banks`") as $row): ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['short_name']; ?></td>
                                        <td class="text-center"><?= $row['account_number']; ?></td>
                                        <td><?= $row['account_name']; ?></td>
                                        <td>
                                            <?= status_bank($row['status']); ?>
                                        </td>
                                        <td>
                                            <a href="<?= admin_url("recharge-bank-edit&id=") . $row['id'] ?>"
                                                style="color:white;" class="btn btn-info btn-sm btn-icon-left m-b-10"
                                                type="button">
                                                <i class="fas fa-edit mr-1"></i><span class=""> Edit</span>
                                            </a>
                                            <button style="color:white;" onclick="remove('<?= $row['id'] ?>')"
                                                class="btn btn-danger btn-sm btn-icon-left m-b-10" type="button">
                                                <i class="fas fa-trash mr-1"></i><span class=""> Delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            CẤU HÌNH
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="row mb-4">
                                        <label class="col-sm-4 col-form-label">Trạng
                                            thái</label>
                                        <div class="col-sm-8">
                                            <select class="form-control form-control-sm" name="status_bank">
                                                <option <?= $HN->setting('status_bank') == 'off' ? 'selected' : '' ?>
                                                    value="off">OFF</option>
                                                <option <?= $HN->setting('status_bank') == 'on' ? 'selected' : '' ?>
                                                    value="on">ON
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-4 col-form-label">Prefix</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm"
                                                    value="<?= $HN->setting('prefix_autobank') ?>"
                                                    name="prefix_autobank" placeholder="VD: NAPTIEN">
                                                <span class="input-group-text">
                                                    <?= $user['id']; ?>
                                                </span>
                                            </div>
                                            <small>Không được để trống Prefix, Prefix là nội dung nạp tiền vào hệ
                                                thống.</small>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="row mb-4">
                                        <label class="col-sm-6 col-form-label">Số tiền
                                            nạp tối thiểu</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $HN->setting('bank_min') ?>" name="bank_min">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-6 col-form-label">Số tiền
                                            nạp tối đa</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $HN->setting('bank_max') ?>" name="bank_max">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="row mb-4">
                                        <label class="col-sm-6 col-form-label">Lưu ý nạp tiền</label>
                                        <div class="col-sm-12">
                                            <textarea id="notice_bank" name="notice_bank">
                                                <?= $HN->setting('notice_bank') ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a type="button" class="btn btn-danger" href=""><i class="fa fa-fw fa-undo me-1"></i>
                                Reload</a>
                            <button type="submit" name="SaveSettings" class="btn btn-success">
                                <i class="fa fa-fw fa-save me-1"></i> Save </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalScrollable2" tabindex="-1" aria-labelledby="exampleModalScrollable2"
        data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel2">Thêm ngân hàng mới</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label">Ngân hàng <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm" name="short_name">
                                    <option value="ACB">ACB</option>
                                    <option value="MBBANK">MBBANK</option>
                                    <option value="VCB">VIETCOMBANK</option>
                                    <option value="TCB">TECHCOMBANK</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label">Hiển thị<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm" name="status">
                                    <option value="on">ON</option>
                                    <option value="off">OFF
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label">Image <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" name="image" required>
                                <small>Khi VietQR không hoạt động, hệ thống sẽ hiện ảnh này thay cho mã QR</small>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label">Số tài khoản <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="account_number"
                                    placeholder="Nhập số tài khoản" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label">Chủ tài khoản <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="account_name"
                                    placeholder="Nhập tên chủ tài khoản" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label">Password Internet Banking</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="api_password"
                                    placeholder="Áp dụng khi cấu hình nạp tiền tự động.">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label">Token</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="api_token"
                                    placeholder="Áp dụng khi cấu hình nạp tiền tự động.">
                            </div>
                        </div>
                        <p>API BANKING: <a style="color: blue;" href="https://api.sieuthicode.net/reffer/1295">Tại
                                đây</a></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="ThemNganHang" class="btn btn-primary btn-sm"><i
                                class="fa fa-fw fa-plus me-1"></i>
                            Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace("notice_bank");
</script>
<?php
require_once(__DIR__ . '/footer.php');
?>
<script>
    function remove(id) {
        Swal.fire({
            title: 'Xác nhận xóa bank',
            text: "Bạn có chắc chắn muốn xóa bank ID " + id + " không ?",
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
                        action: 'removeBank'
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            new Notify({
                                status: 'success',
                                title: 'Thành công',
                                text: response.msg,
                                autotimeout: 3000,
                            })
                            setTimeout("location.href = '<?= base_url('?module=admin&action=recharge-bank-config'); ?>';", 2000);
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