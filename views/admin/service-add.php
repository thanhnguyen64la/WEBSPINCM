<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Thêm Mới Dịch Vụ | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');

use Detection\MobileDetect;

$Mobile_Detect = new MobileDetect();
?>
<?php
if (isset($_POST['submit'])) {
    $isInsert = $HN->insert("services", [
        'name' => check_string($_POST['name']),
        'type' => check_string($_POST['type']),
        'price' => check_string($_POST['price']),
        'api_price' => check_string($_POST['api_price']),
        'description' => $_POST['description'],
        'price' => check_string($_POST['price']),
        'status' => check_string($_POST['status']),
        'api_server' => check_string($_POST['api_server'])
    ]);
    if ($isInsert) {
        $HN->insert("logs", [
            'user_id' => $user['id'],
            'ip' => get_ip(),
            'device' => $Mobile_Detect->getUserAgent(),
            'created_time' => get_time(),
            'action' => "Thêm dịch vụ (" . $_POST['name'] . ") vào hệ thống."
        ]);
        msg_success_link('Thêm dịch vụ thành công', base_url("?module=admin&action=services"), 2000);
    } else {
        msg_error_link('Thao tác thất bại', "", 2000);
    }
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><a type="button"
                    class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1 waves-effect waves-light"
                    href="<?= base_url("?module=admin&action=services") ?>"><i class="fa-solid fa-arrow-left"></i></a>
                Thêm dịch vụ mới
            </h1>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                THÔNG TIN DỊCH VỤ
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label">Tên dịch vụ: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="Nhập tên dịch vụ"
                                        required>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Type: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="type" placeholder="Nhập type dịch vụ"
                                        required>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Giá bán: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="price" required>
                                        <span class="input-group-text"><?= currency_default(); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label">Mô tả: <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control"></textarea>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Giá api: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="api_price" required>
                                        <span class="input-group-text"><?= currency_default(); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="form-label">Server api: <span class="text-danger">*</span></label>
                                    <select class="form-control" name="api_server" required>
                                        <option value="MUASPIN">MUASPIN</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="form-label">Trạng thái: <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="on">ON</option>
                                        <option value="off">OFF</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <a type="button"
                                        class="btn btn-danger shadow-danger btn-wave waves-effect waves-light"
                                        href="<?= base_url("?module=admin&action=services") ?>"><i
                                            class="fa fa-fw fa-undo me-1"></i>
                                        Back</a>
                                    <button type="submit" name="submit"
                                        class="btn btn-primary shadow-primary btn-wave waves-effect waves-light"><i
                                            class="fa fa-fw fa-plus me-1"></i> Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    CKEDITOR.replace("description");
</script>
<?php
require_once(__DIR__ . '/footer.php');

?>