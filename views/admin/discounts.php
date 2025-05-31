<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Danh sách chiết khấu | Quản Lý Website'
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
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-clock-rotate-left"></i> Danh sách
                discount</h1>
        </div>
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách discount</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="form-group">
                                        <label for="discount_ctv"><b>Discount CTV (%)</b></label>
                                        <input type="number" class="form-control" name="discount_ctv" id="discount_ctv"
                                            value="<?= $HN->setting("discount_ctv") ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group">
                                        <label for="discount_daily"><b>Discount Đại Lý (%)</b></label>
                                        <input type="number" class="form-control" name="discount_daily"
                                            id="discount_daily" value="<?= $HN->setting("discount_daily") ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group">
                                        <label for="discount_npp"><b>Discount Nhà Phân Phối (%)</b></label>
                                        <input type="number" class="form-control" name="discount_npp" id="discount_npp"
                                            value="<?= $HN->setting("discount_npp") ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group">
                                        <label for="discount_tongkho"><b>Discount Nhà Tổng Kho (%)</b></label>
                                        <input type="number" class="form-control" name="discount_tongkho"
                                            id="discount_tongkho" value="<?= $HN->setting("discount_tongkho") ?>">
                                    </div>
                                </div>

                            </div>
                            <br>
                            <button type="submit" name="SaveSettings" class="btn btn-primary btn-block">
                                <span>Lưu ngay</span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once(__DIR__ . '/footer.php');
?>