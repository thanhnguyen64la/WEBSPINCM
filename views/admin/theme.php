<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Cấu Hình Giao Diện | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['SaveSettings'])) {
    if (check_img('logo') == true) {
        $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 3);
        $uploads_dir = 'assets/img/theme/logo_' . $rand . '.png';
        $tmp_name = $_FILES['logo']['tmp_name'];
        $addlogo = move_uploaded_file($tmp_name, $uploads_dir);
        if ($addlogo) {
            $HN->update('settings', [
                'setting_value' => $uploads_dir
            ], " `setting_key` = 'logo' ");
        }
    }
    if (check_img('favicon') == true) {
        $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 3);
        $uploads_dir = 'assets/img/theme/favicon_' . $rand . '.png';
        $tmp_name = $_FILES['favicon']['tmp_name'];
        $addlogo = move_uploaded_file($tmp_name, $uploads_dir);
        if ($addlogo) {
            $HN->update('settings', [
                'setting_value' => $uploads_dir
            ], " `setting_key` = 'favicon' ");
        }
    }
    if (check_img('image') == true) {
        $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 3);
        $uploads_dir = 'assets/img/theme/image_' . $rand . '.png';
        $tmp_name = $_FILES['image']['tmp_name'];
        $addlogo = move_uploaded_file($tmp_name, $uploads_dir);
        if ($addlogo) {
            $HN->update('settings', [
                'setting_value' => $uploads_dir
            ], " `setting_key` = 'image_description' ");
        }
    }
    msg_success_link('Cập nhật thành công', "", 1000);
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Theme</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active" aria-current="page">Theme</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            THAY ĐỔI GIAO DIỆN WEBSITE
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-group">
                                        <label for="formFile" class="form-label">Logo</label>
                                        <input type="file" class="form-control" name="logo">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <img width="400px" src="<?= base_url($HN->setting("logo")) ?>">
                                    <hr>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-group">
                                        <label for="formFile" class="form-label">Favicon</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="form-control" name="favicon">

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <img width="100px" src="<?= base_url($HN->setting("favicon")) ?>">
                                    <hr>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-group">
                                        <label for="formFile" class="form-label">Image</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <img width="400px" src="<?= base_url($HN->setting("image_description")) ?>">
                                    <hr>
                                </div>
                            </div>
                            <button name="SaveSettings" class="btn btn-primary" type="submit"><i
                                    class="fas fa-save"></i> Lưu Ngay</button>
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