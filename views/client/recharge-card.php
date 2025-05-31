<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => 'Nạp Thẻ' . ' | ' . $HN->setting('title'),
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
$pin = isset($_GET['pin']) ? $_GET['pin'] : '';
$serial = isset($_GET['serial']) ? $_GET['serial'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$from = ($page - 1) * $limit;
$where = " `user_id` = '" . $user["id"] . "' ";
$sql = [];
if ($pin) {
    $sql[] = "`pin` LIKE '%$pin%'";
}
if ($serial) {
    $sql[] = "`serial` LIKE '%$serial%'";
}
if ($status !== '') {
    $sql[] = "`status` = '$status'";
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
$totalRecords = $HN->num_rows("SELECT * FROM `cards` WHERE $where $sqlReal ORDER BY `id` DESC");
$totalPages = ceil($totalRecords / $limit);
$cards = $HN->get_list("SELECT * FROM `cards` WHERE $where $sqlReal ORDER BY `id` DESC LIMIT $limit OFFSET $from");
$pagination = pagination_client(base_url("?action=recharge-card&pin=$pin&serial=$serial&status=$status&time=$time&limit=$limit&shortByDate=$shortByDate"), $from, $totalRecords, $limit);
?>
<section class="py-5 inner-section profile-part">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="home-heading mb-3">
                    <h3><i class="fa-solid fa-paper-plane m-2"></i>
                        NẠP TIỀN BẰNG THẺ CÀO TỰ ĐỘNG </h3>
                </div>
                <div class="account-card pt-3">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Loại thẻ</label>
                        <div class="col-lg-8 fv-row">
                            <select class="form-control" id="telco">
                                <option value="">-- Chọn loại thẻ --</option>
                                <option value="VIETTEL">Viettel</option>
                                <option value="VINAPHONE">Vinaphone</option>
                                <option value="MOBIFONE">Mobifone</option>
                                <option value="VNMOBI">Vietnamobile</option>
                                <option value="ZING">Zing</option>
                                <option value="VCOIN">Vcoin</option>
                                <option value="GARENA">Garena</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Mệnh giá</label>
                        <div class="col-lg-8 fv-row">
                            <select class="form-control" onchange="totalPrice()" id="amount">
                                <option value="">-- Chọn mệnh giá --</option>
                                <option value="10000">10.000đ</option>
                                <option value="20000">20.000đ</option>
                                <option value="30000">30.000đ</option>
                                <option value="50000">50.000đ</option>
                                <option value="100000">100.000đ</option>
                                <option value="200000">200.000đ</option>
                                <option value="500000">500.000đ</option>
                                <option value="1000000">1.000.000đ</option>
                                <option value="2000000">2.000.000đ</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Serial</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="serial" class="form-control" placeholder="Nhập serial thẻ">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Pin</label>
                        <div class="col-lg-8 fv-row">
                            <input type="text" id="pin" class="form-control" placeholder="Nhập mã thẻ">
                            <input type="hidden" id="token" class="form-control" value="<?= $user['token']; ?>">
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="alert bg-white alert-info" role="alert">
                            <div class="iq-alert-icon">
                                <i class="ri-alert-line"></i>
                            </div>
                            <div class="iq-alert-text">Số tiền thực nhận: <b id="ketqua" style="color: red;">0</b></div>
                        </div>
                    </div>
                    <center>
                        <div class="wallet-form">
                            <button type="button" id="submit">NẠP NGAY</button>
                        </div>
                    </center>
                </div>
            </div>
            <div class="col-md-5">
                <div class="home-heading mb-3">
                    <h3><i class="fa-solid fa-triangle-exclamation m-2"></i> LƯU Ý </h3>
                </div>
                <div class="account-card p-3">
                    <?= $HN->setting("notice_napthe"); ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="home-heading mb-3">
                    <h3><i class="fa-solid fa-clock-rotate-left m-2"></i> LỊCH SỬ NẠP THẺ </h3>
                </div>
                <div class="account-card pt-3">
                    <form method="GET" class="mb-3">
                        <input type="hidden" name="action" value="recharge-card">
                        <div class="row">
                            <div class="col-lg col-md-4 col-6">
                                <input class="form-control col-sm-2 mb-2" value="<?= $pin ?>" name="pin"
                                    placeholder="Pin">
                            </div>
                            <div class="col-lg col-md-4 col-6">
                                <input class="form-control col-sm-2 mb-2" value="<?= $serial ?>" name="serial"
                                    placeholder="Serial">
                            </div>
                            <div class="col-lg col-md-4 col-6">
                                <select class="form-select mb-2" name="status">
                                    <option <?= $status == '' ? 'selected' : ''; ?> value="">Trạng thái</option>
                                    <option <?= $status == 'pending' ? 'selected' : ''; ?> value="pending">Đang chờ xử lý
                                    </option>
                                    <option <?= $status == 'completed' ? 'selected' : ''; ?> value="completed">Thành công
                                    </option>
                                    <option <?= $status == 'error' ? 'selected' : ''; ?> value="error">Thẻ lỗi</option>
                                </select>
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
                                <a href="<?= base_url("?action=recharge-card") ?>" class="shop-widget-btn mb-2"><i
                                        class="far fa-trash-alt"></i><span>Bỏ lọc</span></a>
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
                    <div class="table-scroll">
                        <table class="table fs-sm mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">Nhà mạng</th>
                                    <th class="text-center">Serial</th>
                                    <th class="text-center">Pin</th>
                                    <th class="text-center">Mệnh giá</th>
                                    <th class="text-center">Thực nhận</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Create date</th>
                                    <th class="text-center">Update date</th>
                                    <th class="text-center">Lý do</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cards as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $row['telco']; ?></td>
                                        <td class="text-center"><?= $row['serial']; ?></td>
                                        <td class="text-center"><?= $row['pin']; ?></td>
                                        <td class="text-right"><b
                                                style="color: red;"><?= format_currency($row['amount']); ?></b></td>
                                        <td class="text-right"><b
                                                style="color: green;"><?= format_currency($row['price']); ?></b></td>
                                        <td class="text-center"><?= display_card($row['status']); ?></td>
                                        <td><?= $row['created_time']; ?></td>
                                        <td><?= $row['updated_time']; ?></td>
                                        <td><?= $row['reason']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <div class="float-right">
                                            Đã thanh toán: <strong style="color:red;">
                                                <?= format_currency($HN->get_row(" SELECT SUM(`price`) FROM `cards` WHERE `status` = 'completed' AND " . $where . "")["SUM(`price`)"]); ?>
                                            </strong>
                                            | Chưa thanh toán: <strong style="color:blue;">
                                                <?= format_currency($HN->get_row(" SELECT SUM(`amount`) FROM `cards` WHERE `status` = 'pending'  AND " . $where . " ")["SUM(`amount`)"]); ?>
                                            </strong>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
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
<script>
    function totalPrice() {
        let total = 0;
        let amount = $("#amount").val();
        total = amount - amount * <?= $HN->setting('discount_napthe'); ?> / 100;
        $('#ketqua').html(total.toString().replace(/(.)(?=(\d{3})+$)/g, '$1.'));
    }
</script>
<script>
    $("#submit").on("click", function () {
        $('#submit').html('<i class="fa fa-spinner fa-spin"></i> Đang Xử Lý...').prop('disabled', true);
        $.ajax({
            url: "<?= base_url('ajaxs/client/create.php'); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                action: 'nap_the',
                token: $("#token").val(),
                serial: $('#serial').val(),
                pin: $('#pin').val(),
                telco: $('#telco').val(),
                amount: $('#amount').val()
            },
            success: function (response) {
                if (response.status == 'success') {
                    Swal.fire({
                        title: 'Thành công !',
                        text: response.msg,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    new Notify({
                        status: 'error',
                        title: 'Thất bại',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                }
                $('#submit').html('NẠP NGAY').prop('disabled', false);
            },
            error: function (status) {
                new Notify({
                    status: 'error',
                    title: 'Thất bại',
                    text: 'Đã có lỗi xảy ra, vui lòng thử lại sau',
                    autotimeout: 5000,
                })
                $('#submit').html('NẠP NGAY').prop('disabled', false);
            }

        });
    });
</script>