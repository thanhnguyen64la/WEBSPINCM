<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => 'Nhật Ký Hoạt Động' . ' | ' . $HN->setting('title'),
    'description' => $HN->setting('description'),
    'keywords' => $HN->setting('keywords')
];
$head["header"] = '<link rel="stylesheet" href="' . base_url('assets/css/wallet.css') . '" />';
if (isset($_COOKIE["token"])) {
    $user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '" . check_string($_COOKIE['token']) . "' ");
    if ($user == false) {
        redirect(client_url("logout"));
        exit();
    }
    $_SESSION['login'] = $user['token'];
}
require_once(__DIR__ . '/../../models/is_user.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
?>
<?php
$limit = isset($_GET['limit']) ? intval(check_string($_GET['limit'])) : 10;
$page = isset($_GET['page']) ? intval(check_string($_GET['page'])) : 1;
$shortByDate = isset($_GET['shortByDate']) ? check_string($_GET['shortByDate']) : '';
$time = isset($_GET['time']) ? check_string($_GET['time']) : '';
$content = isset($_GET['content']) ? check_string($_GET['content']) : '';
$ip = isset($_GET['ip']) ? check_string($_GET['ip']) : '';
$device = isset($_GET['device']) ? check_string($_GET['device']) : '';
$from = ($page - 1) * $limit;
$where = " `user_id` = '" . $user["id"] . "' ";
$sql = [];
if ($content) {
    $sql[] = "`action` LIKE '%$content%'";
}
if ($ip) {
    $sql[] = "`ip` LIKE '%$ip%'";
}
if ($device) {
    $sql[] = "`device` LIKE '%$device%'";
}
if (!empty($time)) {
    if (strpos($time, ' to ') !== false) {
        list($startDate, $endDate) = explode(' to ', $time);
    } else {
        $startDate = $endDate = $time;
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
$totalRecords = $HN->num_rows("SELECT * FROM `logs` WHERE $where $sqlReal ORDER BY `id` DESC");
$totalPages = ceil($totalRecords / $limit);
$logs = $HN->get_list("SELECT * FROM `logs` WHERE $where $sqlReal ORDER BY `id` DESC LIMIT $limit OFFSET $from");
$pagination = pagination_client(base_url("?action=logs&content=$content&ip=$ip&device=$device&time=$time&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
?>
<section class="py-5 inner-section profile-part">
    <div class="container">
        <div class="row">
            <?php
            require_once(__DIR__ . '/sidebar.php');
            ?>
            <div class="col-lg-9">
                <div class="account-card">
                    <h4 class="account-title">Nhật ký hoạt động</h4>
                    <form class="mb-3" method="GET">
                        <input type="hidden" name="action" value="logs">
                        <div class="row">
                            <div class="col-lg col-md-4 col-6">
                                <input class="form-control mb-2" value="<?= $content ?>" name="content"
                                    placeholder="Thao tác">
                            </div>
                            <div class="col-lg col-md-4 col-6">
                                <input class="form-control mb-2" value="<?= $ip ?>" name="ip" placeholder="Địa chỉ IP">
                            </div>
                            <div class="col-lg col-md-4 col-6">
                                <input class="form-control mb-2" value="<?= $device ?>" name="device"
                                    placeholder="Thiết bị">
                            </div>
                            <div class="col-lg col-md-4 col-6">
                                <input type="text" class="js-flatpickr form-control mb-2 flatpickr-input"
                                    id="flatpickr-range" name="time" placeholder="Chọn thời gian cần tìm"
                                    value="<?= $time ?>" data-mode="range" readonly="readonly">
                            </div>
                            <div class="col-lg col-md-4 col-6">
                                <button class="shop-widget-btn mb-2"><i class="fas fa-search"></i><span>Tìm
                                        kiếm</span></button>
                            </div>
                            <div class="col-lg col-md-4 col-6">
                                <a href="<?= base_url('?module=client&action=logs') ?>" class="shop-widget-btn mb-2">
                                    <i class="far fa-trash-alt"></i>
                                    <span>Bỏ lọc</span>
                                </a>
                            </div>
                        </div>
                        <div class="top-filter">
                            <div class="filter-show"><label class="filter-label">Show :</label>
                                <select name="limit" onchange="this.form.submit()" class="form-select filter-select">
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
                    <div class="table-scroll table-wrapper">
                        <table class="table fs-sm mb-0">
                            <thead>
                                <tr>
                                    <th width="20%">Thời gian</th>
                                    <th>Thao tác</th>
                                    <th>IP</th>
                                    <th width="10%">Thiết bị</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $row) { ?>
                                    <tr>
                                        <td><?= $row['created_time']; ?></td>
                                        <td><?= $row['action']; ?></td>
                                        <td><?= $row['ip']; ?></td>
                                        <td>
                                            <p class="hidden" id="hidden<?= $row['id']; ?>" style="display: none;">
                                                <?= $row['device'] ?>
                                            </p>
                                            <a href="javascript:void(0)" class="hidden" id="read-hide<?= $row['id']; ?>"
                                                style="display: none;">Ẩn</a>
                                            <a href="javascript:void(0)" id="read-more<?= $row['id']; ?>"
                                                style="display: inline-block;">Hiển thị</a>
                                            <script>
                                                $("#read-more<?= $row['id']; ?>").click(function () {
                                                    $("#hidden<?= $row['id']; ?>").show();
                                                    $(this).hide();
                                                    $("#read-hide<?= $row['id']; ?>").show();
                                                });
                                                $("#read-hide<?= $row['id']; ?>").click(function () {
                                                    $("#hidden<?= $row['id']; ?>").hide();
                                                    $(this).hide();
                                                    $("#read-more<?= $row['id']; ?>").show();
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($totalRecords == 0): ?>
                        <div class="empty-state">
                            <svg width="184" height="152" viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg">
                                <g fill="none" fill-rule="evenodd">
                                    <g transform="translate(24 31.67)">
                                        <ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797"
                                            ry="12.668"></ellipse>
                                        <path
                                            d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z"
                                            fill="#AEB8C2"></path>
                                        <path
                                            d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z"
                                            fill="url(#linearGradient-1)" transform="translate(13.56)"></path>
                                        <path
                                            d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z"
                                            fill="#F5F5F7"></path>
                                        <path
                                            d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z"
                                            fill="#DCE0E6"></path>
                                    </g>
                                    <path
                                        d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z"
                                        fill="#DCE0E6"></path>
                                    <g transform="translate(149.65 15.383)" fill="#FFF">
                                        <ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse>
                                        <path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path>
                                    </g>
                                </g>
                            </svg>
                            <p>Không có dữ liệu</p>
                        </div>
                    <?php endif; ?>
                    <div class="bottom-paginate">
                        <p class="page-info">Showing <?= min($limit, $totalRecords) ?> of <?= $totalRecords ?> Results
                        </p>
                        <div class="pagination">
                            <?= $limit < $totalRecords ? $pagination : ""; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require_once(__DIR__ . '/footer.php');
?>