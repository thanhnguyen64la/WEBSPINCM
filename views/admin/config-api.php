<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Cấu Hình API | Quản Lý Website'
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-code"></i></i> API</h1>
        </div>
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Cấu hình API MUASPIN.COM</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="form-group row mb-3">
                                <label for="status_api_muaspin" class="col-sm-3 col-form-label"><b>ON / OFF:</b></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="status_api_muaspin" id="status_api_muaspin">
                                        <option value="on" <?= $HN->setting("status_api_muaspin") == 'on' ? 'selected' : ''; ?>>Hoạt động</option>
                                        <option value="off" <?= $HN->setting("status_api_muaspin") == 'off' ? 'selected' : ''; ?>>Bảo trì</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="token_api_muaspin" class="col-sm-3 col-form-label"><b>TOKEN:</b></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="token_api_muaspin"
                                        value="<?= $HN->setting("token_api_muaspin"); ?>" name="token_api_muaspin">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="money_api_muaspin"><b>Số dư API:</b></label>
                                <div class="col-sm-12">
                                    <input disabled type="text" class="form-control" id="money_api_muaspin"
                                        value="<?= format_currency($HN->setting("money_api_muaspin")); ?>"
                                        name="money_api_muaspin">
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