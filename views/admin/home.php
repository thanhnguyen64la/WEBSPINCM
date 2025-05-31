<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Trang Chủ | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-2">Trang chủ</h1>
        </div>
        <?php
        if ($HN->setting("smtp_email") == "" || $HN->setting("smtp_password") == ""):
            ?>
            <div class="alert alert-warning alert-dismissible fade show custom-alert-icon shadow-sm" role="alert">
                <svg class="svg-warning" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24"
                    width="1.5rem" fill="#000000">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"></path>
                </svg>
                Vui lòng cấu hình <b>SMTP</b> gmail
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                        class="bi bi-x"></i></button>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card primary">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-primary">
                                    <i class="fa-solid fa-users fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Thành viên đăng ký</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $allUser = $HN->num_rows("SELECT * FROM `users`") != null ? $HN->num_rows("SELECT * FROM `users`") : 0;
                                    echo format_cash($allUser); ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-primary-transparent">Toàn thời gian</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card secondary">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-info">
                                    <i class="fa-solid fa-cart-shopping fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Đơn hàng thành công</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $orderSuccess = $HN->num_rows("SELECT * FROM `orders` WHERE `status` = 'completed'") != null ? $HN->num_rows("SELECT * FROM `orders` WHERE `status` = 'completed'") : 0;
                                    echo format_cash($orderSuccess);
                                    ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-info-transparent">Toàn thời gian</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card warning">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-warning">
                                    <i class="fa-solid fa-chart-simple fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Doanh thu đơn hàng</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $doanhThuAllTime = $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed'")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed'")['SUM(`price`)'] : 0;
                                    echo format_currency($doanhThuAllTime);
                                    ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-warning-transparent">Toàn thời gian</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card success">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-success">
                                    <i class="fa-solid fa-chart-simple fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Lợi nhuận đơn hàng</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $doanhThuAllTime = $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed'")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed'")['SUM(`price`)'] : 0;
                                    $tienVonAllTime = $HN->get_row("SELECT SUM(`api_price`) FROM `orders` WHERE `status` = 'completed'")['SUM(`api_price`)'] != NULL ? $HN->get_row("SELECT SUM(`api_price`) FROM `orders` WHERE `status` = 'completed'")['SUM(`api_price`)'] : 0;
                                    echo format_currency($doanhThuAllTime - $tienVonAllTime);
                                    ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-success-transparent">Toàn thời gian</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card primary">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-primary">
                                    <i class="fa-solid fa-users fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Thành viên đăng ký</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $monthUser = $HN->num_rows("SELECT * FROM `users` WHERE YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())") != null ? $HN->num_rows("SELECT * FROM `users` WHERE YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())") : 0;
                                    echo format_cash($monthUser)
                                        ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-primary-transparent">Tháng <?= date('m'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card secondary">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-info">
                                    <i class="fa-solid fa-cart-shopping fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Đơn hàng thành công</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $monthOrderSuccess = $HN->num_rows("SELECT * FROM `orders` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())") != null ? $HN->num_rows("SELECT * FROM `orders` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())") : 0;
                                    echo format_cash($monthOrderSuccess);
                                    ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-info-transparent">Tháng <?= date('m'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card warning">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-warning">
                                    <i class="fa-solid fa-chart-simple fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Doanh thu đơn hàng</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $doanhThuMonth = $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`price`)'] : 0;
                                    echo format_currency($doanhThuMonth);
                                    ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-warning-transparent">Tháng <?= date('m'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card success">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-success">
                                    <i class="fa-solid fa-chart-simple fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Lợi nhuận đơn hàng</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $doanhThuMonth = $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`price`)'] : 0;
                                    $tienVonMonth = $HN->get_row("SELECT SUM(`api_price`) FROM `orders` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`api_price`)'] != NULL ? $HN->get_row("SELECT SUM(`api_price`) FROM `orders` WHERE `status` = 'completed' AND YEAR(`created_time`) = YEAR(CURRENT_DATE()) AND MONTH(`created_time`) = MONTH(CURRENT_DATE())")['SUM(`api_price`)'] : 0;
                                    echo format_currency($doanhThuMonth - $tienVonMonth);
                                    ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-success-transparent">Tháng <?= date('m'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card primary">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-primary">
                                    <i class="fa-solid fa-users fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Thành viên đăng ký</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $dayUser = $HN->num_rows("SELECT * FROM `users` WHERE DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())") != null ? $HN->num_rows("SELECT * FROM `users` WHERE DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())") : 0;
                                    echo format_cash($dayUser)
                                        ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-primary-transparent">Hôm nay</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card secondary">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-info">
                                    <i class="fa-solid fa-cart-shopping fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Đơn hàng thành công</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $dayOrderSuccess = $HN->num_rows("SELECT * FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())") != null ? $HN->num_rows("SELECT * FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())") : 0;
                                    echo format_cash($dayOrderSuccess);
                                    ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-info-transparent">Hôm nay</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card warning">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-warning">
                                    <i class="fa-solid fa-chart-simple fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Doanh thu đơn hàng</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $doanhThuDay = $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['SUM(`price`)'] : 0;
                                    echo format_currency($doanhThuDay);
                                    ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-warning-transparent">Hôm nay</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card hrm-main-card success">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar bg-success">
                                    <i class="fa-solid fa-chart-simple fs-18"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <span class="fw-semibold text-muted d-block mb-2">Lợi nhuận đơn hàng</span>
                                <h5 class="fw-semibold mb-2">
                                    <?php
                                    $doanhThuDay = $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['SUM(`price`)'] != NULL ? $HN->get_row("SELECT SUM(`price`) FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['SUM(`price`)'] : 0;
                                    $tienVonDay = $HN->get_row("SELECT SUM(`api_price`) FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['SUM(`api_price`)'] != NULL ? $HN->get_row("SELECT SUM(`api_price`) FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = DAY(CURRENT_DATE()) AND MONTH(`created_time`) =  MONTH(CURRENT_DATE())  AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['SUM(`api_price`)'] : 0;
                                    echo format_currency($doanhThuDay - $tienVonDay);
                                    ?>
                                </h5>
                                <p class="mb-0">
                                    <span class="badge bg-success-transparent">Hôm nay</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">THỐNG KÊ ĐƠN HÀNG THÁNG <?= date('m'); ?></div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartjs-line" class="chartjs-chart" width="710" height="355"
                            style="display: block; box-sizing: border-box; height: 284px; width: 568px;"></canvas>
                        <script>
                            (function () {
                                const labels = [
                                    <?php
                                    $daysInMonth = date('t');
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        echo "\"$day/" . date('m/Y') . "\",";
                                    }
                                    ?>
                                ];
                                const doanhThuData = [
                                    <?php
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        $doanhThu = $HN->get_row("SELECT SUM(`price`) AS total FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = $day AND MONTH(`created_time`) = MONTH(CURRENT_DATE()) AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['total'];
                                        echo $doanhThu != NULL ? $doanhThu : 0;
                                        echo ",";
                                    }
                                    ?>
                                ];
                                const loiNhuanData = [
                                    <?php
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        $doanhThu = $HN->get_row("SELECT SUM(`price`) AS total FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = $day AND MONTH(`created_time`) = MONTH(CURRENT_DATE()) AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['total'];
                                        $tienVon = $HN->get_row("SELECT SUM(`api_price`) AS total FROM `orders` WHERE `status` = 'completed' AND DAY(`created_time`) = $day AND MONTH(`created_time`) = MONTH(CURRENT_DATE()) AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['total'];
                                        $doanhThu = $doanhThu != NULL ? $doanhThu : 0;
                                        $tienVon = $tienVon != NULL ? $tienVon : 0;
                                        $loiNhuan = $doanhThu - $tienVon;
                                        echo $loiNhuan;
                                        echo ",";
                                    }
                                    ?>
                                ];
                                const data = {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Doanh thu',
                                        backgroundColor: 'rgb(132, 90, 223)',
                                        borderColor: 'rgb(132, 90, 223)',
                                        data: doanhThuData,
                                    },
                                    {
                                        label: 'Lợi nhuận',
                                        backgroundColor: 'rgb(73, 182, 245)',
                                        borderColor: 'rgb(73, 182, 245)',
                                        data: loiNhuanData,
                                    }
                                    ]
                                };
                                const config = {
                                    type: 'bar',
                                    data: data,
                                    options: {}
                                };
                                const myChart = new Chart(
                                    document.getElementById('chartjs-line'),
                                    config
                                );
                            })();
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">THỐNG KÊ NẠP TIỀN THÁNG <?= date('m'); ?></div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartjs-naptien" class="chartjs-chart" width="710" height="355"
                            style="display: block; box-sizing: border-box; height: 284px; width: 568px;"></canvas>
                        <script>
                            (function () {
                                /* line chart  */
                                Chart.defaults.borderColor = "rgba(142, 156, 173,0.1)", Chart.defaults.color =
                                    "#8c9097";
                                const labels = [
                                    <?php
                                    $daysInMonth = date('t');
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        echo "\"$day/" . date('m/Y') . "\",";
                                    }
                                    ?>
                                ];
                                const tongNapBank = [
                                    <?php
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        $tienNap = $HN->get_row("SELECT SUM(`amount`) AS total FROM `payment_bank` WHERE DAY(`created_time`) = $day AND MONTH(`created_time`) = MONTH(CURRENT_DATE()) AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['total'];
                                        echo $tienNap;
                                        echo ",";
                                    }
                                    ?>
                                ];
                                const tongNapCard = [
                                    <?php
                                    for ($day = 1; $day <= $daysInMonth; $day++) {
                                        $tienNapCard = $HN->get_row("SELECT SUM(`price`) AS total FROM `cards` WHERE `status` = 'completed' AND DAY(`created_time`) = $day AND MONTH(`created_time`) = MONTH(CURRENT_DATE()) AND YEAR(`created_time`) = YEAR(CURRENT_DATE())")['total'];
                                        echo $tienNapCard;
                                        echo ",";
                                    }
                                    ?>
                                ];
                                const data = {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Nạp ngân hàng',
                                        backgroundColor: 'rgb(132, 90, 223)',
                                        borderColor: 'rgb(132, 90, 223)',
                                        data: tongNapBank,
                                    },
                                    {
                                        label: 'Nạp thẻ cào',
                                        backgroundColor: 'rgb(73, 182, 245)',
                                        borderColor: 'rgb(73, 182, 245)',
                                        data: tongNapCard,
                                    }
                                    ]
                                };
                                const config = {
                                    type: 'bar',
                                    data: data,
                                    options: {}
                                };
                                const myChart = new Chart(
                                    document.getElementById('chartjs-naptien'),
                                    config
                                );
                            })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once(__DIR__ . '/footer.php');

?>