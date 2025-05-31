<?php if (!defined('REQUEST')) {
    exit('The Request Not Found');
}
$body = [
    'title' => 'Chỉnh Sửa Ngân Hàng | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
$id_bank = isset($_GET['id']) ? check_string($_GET['id']) : null;
if ($id_bank != null) {
    $bank = $HN->get_row("SELECT * FROM `banks` WHERE `id` = '$id_bank'");
    if ($bank === false) {
        exit('Ngân hàng không tồn tại trong hệ thống');
    }
}
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
?>
<?php
if (isset($_POST["LuuNganHang"])) {
    if (check_img("image")) {
        unlink($row["image"]);
        $rand = random("0123456789QWERTYUIOPASDGHJKLZXCVBNM", 3);
        $uploads_dir = "assets/img/storage/" . "bank_" . $rand . ".png";
        $tmp_name = $_FILES["image"]["tmp_name"];
        $addlogo = move_uploaded_file($tmp_name, $uploads_dir);
        if ($addlogo) {
            $HN->update("banks", ["image" => "assets/img/storage/" . "bank_" . $rand . ".png"], " `id` = '" . $id_bank . "' ");
        }
    }
    $isUpdate = $HN->update("banks", ["short_name" => check_string($_POST["short_name"]), "account_number" => check_string($_POST["account_number"]), "status" => check_string($_POST["status"]), "api_token" => check_string($_POST["api_token"]), "api_password" => check_string($_POST["api_password"]), "account_name" => check_string($_POST["account_name"])], " `id` = '" . $id_bank . "' ");
    if ($isUpdate) {
        msg_success_link('Cập nhật thành công', "", 2000);
    }
    msg_error_link('Cập nhật thất bại', "", 2000);
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Chỉnh sửa ngân hàng <?= $bank['short_name']; ?></h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Nạp tiền</a></li>
                        <li class="breadcrumb-item"><a
                                href="<?= base_url("?module=admin&action=recharge-bank-config") ?>">Ngân hàng</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa ngân hàng
                            <?= $bank['short_name']; ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            CHỈNH SỬA NGÂN HÀNG
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Ngân hàng <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="short_name">
                                    <option <?= $bank['short_name'] == 'ACB' ? 'selected' : '' ?> value="ACB">ACB</option>
                                    <option <?= $bank['short_name'] == 'MBBANK' ? 'selected' : '' ?> value="MBBANK">MBBANK
                                    </option>
                                    <option <?= $bank['short_name'] == 'VCB' ? 'selected' : '' ?> value="VCB">VIETCOMBANK
                                    </option>
                                    <option <?= $bank['short_name'] == 'TCB' ? 'selected' : '' ?> value="TCB">TECHCOMBANK
                                    </option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label for="exampleInputFile">Image</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Account number</label>
                                <input type="text" class="form-control" name="account_number"
                                    value="<?= $bank['account_number'] ?>" placeholder="Nhập số tài khoản" required>
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Account name</label>
                                <input type="text" class="form-control" name="account_name"
                                    value="<?= $bank['account_name'] ?>" placeholder="Nhập tên chủ tài khoản" required>
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Trạng thái</label>
                                <select class="form-control" name="status">
                                    <option <?= $bank['status'] == 'on' ? 'selected' : '' ?> value="on">ON</option>
                                    <option <?= $bank['status'] == 'off' ? 'selected' : '' ?> value="off">OFF</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Password Internet Banking</label>
                                <input type="text" class="form-control" name="api_password"
                                    value="<?= $bank['api_password'] ?>"
                                    placeholder="Áp dụng khi cấu hình nạp tiền tự động.">
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Token</label>
                                <input type="text" class="form-control" name="api_token"
                                    value="<?= $bank['api_token'] ?>"
                                    placeholder="Áp dụng khi cấu hình nạp tiền tự động.">
                            </div>
                            <a type="button" class="btn btn-hero btn-danger"
                                href="<?= admin_url("recharge-bank-config") ?>"><i class="fa fa-fw fa-undo me-1"></i>
                                Back</a>
                            <button type="submit" name="LuuNganHang" class="btn btn-hero btn-success"><i
                                    class="fa fa-fw fa-save me-1"></i> Save</button>
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