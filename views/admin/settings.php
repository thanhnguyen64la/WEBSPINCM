<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Cấu Hình Thông Tin | Quản Lý Website'
];
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
require_once(__DIR__ . '/../../models/is_admin.php');
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
            <h1 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-gear"></i> Cài đặt</h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-2">
                                <nav class="nav nav-tabs flex-column nav-style-5 mb-3" role="tablist">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#cai-dat-chung" aria-selected="true"><i
                                            class="bx bx-cog me-2 align-middle d-inline-block"></i>Cài đặt chung</a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#ket-noi" aria-selected="false" tabindex="-1"><i
                                            class="bx bx-plug me-2 align-middle d-inline-block"></i>Kết nối</a>
                                </nav>
                            </div>
                            <div class="col-xl-10">
                                <div class="tab-content">
                                    <div class="tab-pane text-muted active show" id="cai-dat-chung" role="tabpanel">
                                        <h4>Cài đặt chung</h4>
                                        <form action="" method="POST">
                                            <div class="row push mb-3">
                                                <div class="col-md-6">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>Title</td>
                                                                <td>
                                                                    <input type="text" name="title"
                                                                        value="<?= $HN->setting("title") ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Description</td>
                                                                <td>
                                                                    <textarea rows="4" name="description"
                                                                        class="form-control"><?= $HN->setting("description") ?></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Keywords</td>
                                                                <td>
                                                                    <textarea rows="6" name="keywords"
                                                                        class="form-control"><?= $HN->setting("keywords") ?></textarea>
                                                                    </textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Zalo</td>
                                                                <td>
                                                                    <input type="text" name="zalo"
                                                                        value="<?= $HN->setting("zalo") ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Hotline</td>
                                                                <td>
                                                                    <input type="text" name="hotline"
                                                                        value="<?= $HN->setting("hotline") ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>Màu chủ đạo</td>
                                                                <td>
                                                                    <input type="color"
                                                                        class="form-control form-control-color border-0"
                                                                        id="exampleColorInput" name="primary_color"
                                                                        value="<?= $HN->setting("primary_color") ?>"
                                                                        title="Choose your color">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Màu phụ</td>
                                                                <td>
                                                                    <input type="color"
                                                                        class="form-control form-control-color border-0"
                                                                        id="exampleColorInput" name="secondary_color"
                                                                        value="<?= $HN->setting("secondary_color") ?>"
                                                                        title="Choose your color">

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Trạng thái website</td>
                                                                <td>
                                                                    <select class="form-control" name="status">
                                                                        <option <?= $HN->setting("status") == 'on' ? 'selected' : ''; ?> value="on">ON</option>
                                                                        <option <?= $HN->setting("status") == 'off' ? 'selected' : ''; ?> value="off">OFF</option>
                                                                    </select>
                                                                    <small>Chọn OFF nếu bạn muốn bật chế độ bảo
                                                                        trì.</small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Giới hạn đăng ký tài khoản 1 IP</td>
                                                                <td>
                                                                    <input name="max_register_ip" type="text"
                                                                        class="form-control"
                                                                        value="<?= $HN->setting("max_register_ip") ?>"
                                                                        required>
                                                                    <small>1 IP chỉ được phép đăng ký tối đa
                                                                        <strong><?= $HN->setting("max_register_ip") ?></strong>
                                                                        tài khoản.</small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Fanpage Facebook</td>
                                                                <td>
                                                                    <input type="text" name="fanpage_link"
                                                                        value="<?= $HN->setting("fanpage_link") ?>"
                                                                        class="form-control"
                                                                        placeholder="https://facebook.com/tenfanpage">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-12">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>Thông báo phía trên góc trái màn hình </td>
                                                                <td>
                                                                    <textarea class="form-control"
                                                                        name="notification_top_left"><?= $HN->setting("notification_top_left") ?></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Thông báo phía dưới góc trái màn hình </td>
                                                                <td>
                                                                    <textarea rows="6" class="form-control"
                                                                        name="notification_footer_left"><?= $HN->setting("notification_footer_left") ?></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Thông báo ngoài trang chủ</td>
                                                                <td>
                                                                    <textarea id="notification_home"
                                                                        name="notification_home">
                                                                    <?= $HN->setting('notification_home'); ?>
                                                                    </textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Thông báo lưu ý</td>
                                                                <td>
                                                                    <textarea id="notification_note"
                                                                        name="notification_note">
                                                                    <?= $HN->setting('notification_note'); ?>
                                                                    </textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Nội dung trang liên hệ</td>
                                                                <td>
                                                                    <textarea id="contact" name="contact">
                                                                    <?= $HN->setting('contact'); ?>
                                                                    </textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Nội dung trang chính sách</td>
                                                                <td>
                                                                    <textarea id="policy" name="policy">
                                                                    <?= $HN->setting('policy'); ?>
                                                                    </textarea>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <button type="submit" name="SaveSettings"
                                                class="btn btn-primary w-100 mb-3">
                                                <i class="fa fa-fw fa-save me-1"></i> Save </button>
                                        </form>
                                    </div>
                                    <div class="tab-pane text-muted" id="ket-noi" role="tabpanel">
                                        <h4>Kết nối</h4>
                                        <form action="" method="POST">
                                            <div class="row push mb-3">
                                                <div class="col-md-6">
                                                    <table class="mb-3 table table-bordered table-striped table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <img src="<?= base_url("assets/img/storage/icon-smtp.png") ?>"
                                                                        width="20px"> SMTP
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>SMTP Email</td>
                                                                <td>
                                                                    <input type="text" name="smtp_email"
                                                                        value="<?= $HN->setting("smtp_email"); ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>SMTP Password</td>
                                                                <td>
                                                                    <input type="text" name="smtp_password"
                                                                        value="<?= $HN->setting("smtp_password"); ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="mb-3 table table-bordered table-striped table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <img src="<?= base_url("assets/img/storage/icon-bot-telegram.avif") ?>"
                                                                        width="25px"> Bot Telegram
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>BOT Telegram</td>
                                                                <td>
                                                                    <select class="form-control"
                                                                        name="status_bot_telegram">
                                                                        <option
                                                                            <?= $HN->setting("status_bot_telegram") == 'on' ? 'selected' : ''; ?> value="on">ON
                                                                        </option>
                                                                        <option
                                                                            <?= $HN->setting("status_bot_telegram") == 'off' ? 'selected' : ''; ?> value="off">OFF
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Telegram Token</td>
                                                                <td>
                                                                    <input type="text" name="telegram_token"
                                                                        value="<?= $HN->setting("telegram_token") ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Telegram Chat ID</td>
                                                                <td>
                                                                    <input type="text" name="telegram_chat_id"
                                                                        value="<?= $HN->setting("telegram_chat_id") ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <img src="<?= base_url("assets/img/storage/google_recaptcha.png") ?>"
                                                                        width="20px"> reCAPTCHA
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>reCAPTCHA</td>
                                                                <td>
                                                                    <select class="form-control"
                                                                        name="status_reCAPTCHA">
                                                                        <option
                                                                            <?= $HN->setting("status_reCAPTCHA") == 'on' ? 'selected' : ''; ?> value="on">ON
                                                                        </option>
                                                                        <option
                                                                            <?= $HN->setting("status_reCAPTCHA") == 'off' ? 'selected' : ''; ?> value="off">OFF
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>reCAPTCHA Site Key</td>
                                                                <td>
                                                                    <input type="text" name="reCAPTCHA_site_key"
                                                                        value="<?= $HN->setting("reCAPTCHA_site_key") ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>reCAPTCHA Secret Key</td>
                                                                <td>
                                                                    <input type="text" name="reCAPTCHA_secret_key"
                                                                        value="<?= $HN->setting("reCAPTCHA_secret_key") ?>"
                                                                        class="form-control">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <button type="submit" name="SaveSettings"
                                                class="btn btn-primary w-100 mb-3">
                                                <i class="fa fa-fw fa-save me-1"></i> Save </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace("notification_home");
    CKEDITOR.replace("popup_notification");
    CKEDITOR.replace("notification_note");
    CKEDITOR.replace("contact");
    CKEDITOR.replace("policy");
</script>
<?php
require_once(__DIR__ . '/footer.php');
?>