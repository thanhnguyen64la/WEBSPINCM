<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$head = [
    'title' => $HN->setting('title'),
    'description' => $HN->setting('description'),
    'keywords' => $HN->setting('keywords')
];
if (isset($_COOKIE["token"])) {
    $user = $HN->get_row("SELECT * FROM `users` WHERE `token` = '" . check_string($_COOKIE['token']) . "' ");
    if ($user == false) {
        redirect(client_url("logout"));
        exit();
    }
    $_SESSION['login'] = $user['token'];
}
if (isset($_SESSION['login'])) {
    require_once(__DIR__ . '/../../models/is_user.php');
}
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
?>
<section class="section feature-part mt-5">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="account-card py-3">
                    <?= $HN->setting("notification_home"); ?>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="home-heading mb-3">
                    <h3><i class="fa-solid fa-cart-plus m-2"></i> TẠO ĐƠN CHẠY LINK INVITE</h3>
                </div>
                <div class="account-card" id="formLoader" style="display:none;">
                    <center>
                        <img src="<?= base_url("assets/img/storage/bg_loader.png") ?>" width="200px">
                        <h4>Vui lòng không tắt trang cho đến khi hoàn tất</h4>
                    </center>
                </div>
                <div class="account-card" id="formBuy">
                    <div class="row py-4">
                        <label class="col-sm-5 col-form-label">Số dư của bạn:</label>
                        <div class="col-sm-7">
                            <strong class="text_shadow">
                                <?php
                                if (isset($user)):
                                    echo format_currency($user['money']);
                                else:
                                    ?>
                                    0
                                <?php endif; ?>
                            </strong>
                        </div>
                    </div>
                    <marquee> Vui lòng xóa bớt bạn bè để tránh gặp lỗi không nhận được invite, nên để bạn bè < 100
                            người. </marquee>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="row mb-3">
                                        <?php
                                        $i = 0;
                                        $hasActive = false;
                                        foreach ($HN->get_list("SELECT * FROM `services` WHERE `status` = 'on'") as $service):
                                            $active = ($service['status'] == 'on' && !$hasActive) ? 'active' : '';
                                            $maintenance = ($service['status'] == 'off') ? 'style="pointer-events: none;"' : '';
                                            ?>
                                            <div class="col-md-6 col-lg-6 alert fade show mb-2">
                                                <div <?= $maintenance ?> class="loailink profile-card contact <?= $active ?>"
                                                    data-check="<?= $service['api_server'] == "MUASPIN" ? 1 : 0 ?>"
                                                    data-type="<?= $service['type'] ?>">
                                                    <h7>Link
                                                        <strong style="color: #f44336;"><?= $service['name']; ?></strong>
                                                        <?php if ($service['status'] == 'off'): ?>
                                                            <small style="font-size:12px;">(Đang bảo trì)</small>
                                                        <?php endif ?>
                                                    </h7>
                                                    <p>Giá <b style="color:blue;">
                                                            <?php
                                                            $price = $service['price'];
                                                            if (isset($user)) {
                                                                if ($user['level'] == 'ctv') {
                                                                    $price = $service['price'] - $service['price'] * $HN->setting('discount_ctv') / 100;
                                                                } elseif ($user['level'] == 'daily') {
                                                                    $price = $service['price'] - $service['price'] * $HN->setting('discount_daily') / 100;
                                                                } elseif ($user['level'] == 'npp') {
                                                                    $price = $service['price'] - $service['price'] * $HN->setting('discount_npp') / 100;
                                                                } elseif ($user['level'] == 'tongkho') {
                                                                    $price = $service['price'] - $service['price'] * $HN->setting('discount_tongkho') / 100;
                                                                }
                                                            }
                                                            echo format_currency($price);
                                                            ?>
                                                        </b> / lượt
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                            if ($service['status'] == 'on') {
                                                $hasActive = true;
                                                $i++;
                                            }
                                        endforeach; ?>
                                    </div>
                                    <div class="alert1" style="display: block;" id="showDesc">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 py-3">
                                <label class="col-sm-5 col-form-label">Số lượng cần tăng:</label>
                                <div class="col-sm-7">
                                    <div class="product-action" style="display: flex;">
                                        <?php if (isset($user)): ?>
                                            <input type="hidden" id="token" value="<?= $user['token']; ?>">
                                        <?php endif; ?>
                                        <button class="action-minus1" title="Quantity Minus">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <input class="action-input" oninput="validateInput()" max="3"
                                            placeholder="Tối đa 3 lời mời" type="text" id="amount" value="1">
                                        <button class="action-plus1" title="Quantity Plus">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                    <marquee>
                                        <small>Nên chạy trước 1 đến 2 link xem có thành công và nhận spin hay không rồi
                                            mới chạy thêm, tránh game lỗi không nhận được spin. Mỗi mã mời chỉ chạy được
                                            tối đa 3 lần, vui lòng lấy thêm mã mời nếu bạn cần chạy nhiều lần.</small>
                                    </marquee>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-5 col-form-label">Link mời bạn bè:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control mb-2" id="invite_code"
                                            placeholder="https://GetCoinMaster.com/~XXXXX" max="3">
                                    </div>

                                    <button onclick="pasteFromClipboard()" class="shop-widget-btn mb-2"><i
                                            class="fa-solid fa-clipboard"></i><span>Dán từ clipboard</span></button>
                                </div>
                            </div>
                            <center>
                                <div class="mb-3">Tổng tiền thanh toán: <strong id="into_pay"
                                        style="color: red;">0</strong>
                                </div>
                                <button class="btn-buy" onclick="buySpin()">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span>Thanh toán</span>
                                </button>
                            </center>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="home-heading mb-3">
                    <h3><i class="fa-solid fa-triangle-exclamation m-2"></i> LƯU Ý</h3>
                </div>
                <div class="account-card">
                    <?= $HN->setting("notification_note") ?>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="home-heading mb-3">
                    <h3><i class="fa-solid fa-clock-rotate-left m-2"></i>
                        LỊCH SỬ CHẠY LINK CỦA BẠN</h3>
                </div>
                <div class="account-card">
                    <form id="form_order" method="POST" class="py-4">
                        <div class="row">
                            <div class="col-lg col-md-4 col-6">
                                <input class="form-control mb-2" type="text" name="invite_code" placeholder="Mã mời">
                            </div>
                            <div class="col-lg col-md-4 col-6">
                                <input type="text" class="js-flatpickr form-control mb-2 flatpickr-input"
                                    id="flatpickr-range" name="time" placeholder="Chọn thời gian cần tìm"
                                    data-mode="range" readonly="readonly">
                            </div>
                            <div class="col-lg col-md-4 col-4">
                                <button class="shop-widget-btn mb-2" type="submit"><i
                                        class="fas fa-search"></i><span>Tìm kiếm</span></button>
                            </div>
                            <div class="col-lg col-md-4 col-4">
                                <a href="<?= base_url() ?>" class="shop-widget-btn mb-2"><i
                                        class="far fa-trash-alt"></i><span>Bỏ lọc</span></a>
                            </div>
                            <div class="col-lg col-md-4 col-4">
                                <button class="shop-widget-btn mb-2" style="background-color: #03A9F4;color:#fff;"><i
                                        class="fa-solid fa-rotate-right"></i><span>Reload</span></button>
                            </div>
                        </div>
                        <div class="top-filter">
                            <div class="filter-show"><label class="filter-label">Show :</label>
                                <select name="limit" onchange="form_order()" class="form-select filter-select">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                    <option value="1000">1000</option>
                                </select>
                            </div>
                            <div class="filter-short">
                                <label class="filter-label">Short by Date:</label>
                                <select name="shortByDate" onchange="form_order()" class="form-select filter-select">
                                    <option value="">Tất cả</option>
                                    <option value="1">Hôm nay </option>
                                    <option value="2">Tuần này </option>
                                    <option value="3">Tháng này </option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="table-scroll" id="table_order">
                    </div>
                    <div class="bottom-paginate">
                        <p class="page-info" id="page_order">
                        </p>
                        <div class="pagination">
                            <div class="paging_simple_numbers">
                                <ul class="pagination" id="pagination_order">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function buySpin() {
        const amount = $("#amount").val();
        const invite_code = $("#invite_code").val();
        const tokenElement = $("#token");
        const token = tokenElement.length ? tokenElement.val() : null;
        var formLoader = document.getElementById('formLoader');
        var formBuy = document.getElementById('formBuy');
        var activeElement = document.querySelector('.profile-card.active');
        var type = '';
        var check = '';
        if (activeElement) {
            type = activeElement.getAttribute('data-type');
            check = activeElement.getAttribute('data-check');
        } else {
            new Notify({
                status: 'error',
                title: 'Thất bại',
                text: 'Vui lòng chọn dịch vụ cần mua',
                autotimeout: 3000,
            })
            return;
        }
        if (invite_code == '') {
            new Notify({
                status: 'error',
                title: 'Thất bại',
                text: 'Vui lòng nhập Link Invite',
                autotimeout: 3000,
            })
            return;
        }
        formLoader.style.display = 'block';
        formBuy.style.display = 'none';
        $.ajax({
            url: "<?= base_url("ajaxs/client/create.php"); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                action: 'order_spin',
                type: type,
                check: check,
                amount: amount,
                invite_code: invite_code,
                token: token
            },
            success: function (response) {
                formLoader.style.display = 'none';
                formBuy.style.display = 'block';
                if (response.status == 'success') {
                    Swal.fire({
                        title: 'Thành công',
                        text: response.msg,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = location.href;
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
            },
            error: function (status, xhr, error) {
                formLoader.style.display = 'none';
                formBuy.style.display = 'block';
                new Notify({
                    status: 'error',
                    title: 'Thất bại',
                    text: 'Vui lòng liên hệ Developer',
                    autotimeout: 3000,
                })
            }
        });
    }
</script>
<script>
    function calculateRemainingPercentage(amount, remaining) {
        if (amount <= 0 || remaining < 0) {
            return 'Invalid input. Please provide valid values.';
        }
        return ((amount - remaining) / amount) * 100;
    }

    function updateProgressBar(id, amount, remaining) {
        const progressBar = document.getElementById('myProgressBar' + id);
        const progressText = document.getElementById('progressText' + id);
        const value = calculateRemainingPercentage(amount, remaining);
        anime({
            targets: progressBar,
            width: value + '%',
            easing: 'easeInOutQuad',
            duration: 800
        });
        anime({
            targets: progressText,
            innerText: `${amount - remaining}/${amount} (${value.toFixed(2)}%)`,
            round: 1,
            easing: 'easeInOutQuad',
            duration: 800
        });
    }

    function form_order(page = 1) {
        const form = $('#form_order');
        const tokenElement = $("#token");
        const token = tokenElement.length ? tokenElement.val() : null;
        const formData = form.serializeArray();
        formData.push({
            name: 'action',
            value: 'pagination_order'
        });
        formData.push({
            name: 'page',
            value: page
        });
        formData.push({
            name: 'token',
            value: token
        });
        $.ajax({
            url: "<?= base_url('ajaxs/client/view.php'); ?>",
            method: "POST",
            dataType: "JSON",
            data: formData,
            success: function (data) {
                const table_order = $('#table_order');
                const page_order = $('#page_order');
                const pagination_order = $('#pagination_order');
                let order_html = '<table class="table fs-sm"><thead><tr><th class="text-center">Mã mời</th><th class="text-center">Loại</th><th class="text-center">Tên người chơi</th><th class="text-center">Số lượng</th><th class="text-center">Trạng thái</th><th class="text-center">Thanh toán</th><th class="text-center">Thời gian</th></tr></thead><tbody>';
                data.orders.forEach(order => {
                    order_html += `
                    <tr>
                        <td class="text-center">${order.invite_code}</td>
                        <td class="text-center"><span class="badge bg-success">${order.service_name}</span></td>
                        <td class="text-center"><b>${order.name}</b></td>
                        <td class="text-center">
                            <div class="progress-container" title="Số link còn lại: ${order.remaining}" data-toggle="tooltip" data-placement="bottom" data-bs-original-title="Số link còn lại: ${order.remaining}">
                                <div class="progress-bar" id="myProgressBar${order.id}">
                                    <div id="progressText${order.id}"></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">${order.status}</td>
                        <td class="text-center"><strong>${order.price}</strong></td>
                        <td class="text-center"><span class="badge bg-dark" data-toggle="tooltip" data-placement="bottom" title="${order.created_time}">${order.format_time}</span></td>
                    </tr>`;
                });
                order_html += '</tbody></table>';
                table_order.html(order_html);
                data.orders.forEach(order => {
                    updateProgressBar(order.id, order.amount, order.remaining);
                });
                page_order.html(`Showing ${Math.min(data.orders.length, data.totalRecords)} of ${data.totalRecords} Results`);
                let paginationHTML = '';
                const maxVisiblePages = 5;
                if (data.totalPages > maxVisiblePages) {
                    let startPage = Math.max(1, page - Math.floor(maxVisiblePages / 2));
                    let endPage = Math.min(data.totalPages, startPage + maxVisiblePages - 1);
                    if (endPage - startPage < maxVisiblePages - 1) {
                        startPage = Math.max(1, endPage - maxVisiblePages + 1);
                    }
                    if (startPage > 1) {
                        paginationHTML += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
                        if (startPage > 2) {
                            paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                        }
                    }
                    for (let i = startPage; i <= endPage; i++) {
                        paginationHTML += `<li class="page-item${i === page ? ' active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }
                    if (endPage < data.totalPages) {
                        if (endPage < data.totalPages - 1) {
                            paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                        }
                        paginationHTML += `<li class="page-item"><a class="page-link" href="#" data-page="${data.totalPages}">${data.totalPages}</a></li>`;
                    }
                } else {
                    for (let i = 1; i <= data.totalPages; i++) {
                        paginationHTML += `<li class="page-item${i === page ? ' active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }
                }
                pagination_order.html(paginationHTML);
                $('#pagination_order a').click(function (e) {
                    e.preventDefault();
                    let pageNum = parseInt($(this).attr('data-page'));
                    form_order(pageNum);
                });
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                })
            }
        });
    }
    $('#form_order button[type="submit"]').on('click', function (e) {
        e.preventDefault();
        form_order();
    });
    $(document).ready(function () {
        form_order();
    });
    setInterval(function () {
        form_order();
    }, 10000);
</script>
<style>
    .modal-content {
        position: relative;
        background-color: #fff;
        border-radius: 10px;
    }
</style>

<?php
require_once(__DIR__ . '/footer.php');
?>