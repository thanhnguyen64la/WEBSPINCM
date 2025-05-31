<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
?>
<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="<?= admin_url() ?>" class="header-logo">
            <img src="<?= base_url("assets/img/theme/logo_1.png") ?>" alt="logo" class="desktop-logo">
            <img src="<?= base_url("assets/img/theme/logo_1.png") ?>" alt="logo" class="toggle-logo">
            <img src="<?= base_url("assets/img/theme/logo_1.png") ?>" alt="logo" class="desktop-dark">
            <img src="<?= base_url("assets/img/theme/logo_1.png") ?>" alt="logo" class="toggle-dark">
            <img src="<?= base_url("assets/img/theme/logo_1.png") ?>" alt="logo" class="desktop-white">
            <img src="<?= base_url("assets/img/theme/logo_1.png") ?>" alt="logo" class="toggle-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">
        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=home") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["home"]) ?>">
                        <i class="bx bxs-dashboard side-menu__icon"></i>
                        <span class="side-menu__label">Trang chủ</span>
                    </a>
                </li>
                <li class="slide__category"><span class="category-name">Bảo mật</span></li>
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=block-ip") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["block-ip"]) ?>">
                        <i class="bx bx-block side-menu__icon"></i>
                        <span class="side-menu__label">Khóa IP</span>
                    </a>
                </li>
                <li class="slide__category"><span class="category-name">Quản lý</span></li>
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=users") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["users"]) ?>">
                        <i class="bx bxs-user side-menu__icon"></i>
                        <span class="side-menu__label">Thành viên</span>
                    </a>
                </li>
                <li class="slide has-sub <?= show_sidebar(["logs", "transactions"]); ?>">
                    <a href="javascript:void(0);"
                        class="side-menu__item <?= active_sidebar_admin(["logs", "transactions"]) ?>">
                        <i class='bx bx-history side-menu__icon'></i>
                        <span class="side-menu__label">Lịch sử</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide ">
                            <a href="<?= base_url("?module=admin&action=logs") ?>"
                                class="side-menu__item <?= active_sidebar_admin(["logs"]) ?>">Nhật ký hoạt
                                động</a>
                        </li>
                        <li class="slide ">
                            <a href="<?= base_url("?module=admin&action=transactions") ?>"
                                class="side-menu__item <?= active_sidebar_admin(["transactions"]) ?> ">Biến động
                                số dư</a>
                        </li>
                    </ul>
                </li>
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=services") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["services"]) ?>">
                        <i class='bx bx-server side-menu__icon'></i>
                        <span class="side-menu__label">Dịch vụ</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=config-api") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["config-api"]) ?>">
                        <i class="fa-solid fa-code side-menu__icon"></i>
                        <span class="side-menu__label">Kết nối API</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=orders") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["orders"]) ?>">
                        <i class='bx bx-cart side-menu__icon'></i>
                        <span class="side-menu__label">Đơn hàng</span>
                    </a>
                </li>
                <li class="slide has-sub <?= show_sidebar(["recharge-bank", "recharge-card"]); ?>">
                    <a href="javascript:void(0);"
                        class="side-menu__item <?= active_sidebar_admin(["recharge-bank", "recharge-card", "recharge-bank-config"]) ?>">
                        <i class='bx bxs-wallet-alt side-menu__icon'></i>
                        <span class="side-menu__label">Nạp tiền</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="<?= base_url("?module=admin&action=recharge-bank") ?>"
                                class="side-menu__item <?= active_sidebar_admin(["recharge-bank"]) ?>">Ngân
                                hàng</a>
                        </li>
                        <li class="slide">
                            <a href="<?= base_url("?module=admin&action=recharge-card") ?>"
                                class="side-menu__item <?= active_sidebar_admin(["recharge-card"]) ?>">Nạp thẻ cào</a>
                        </li>
                    </ul>
                </li>
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=promotions") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["promotions"]) ?>">
                        <i class="fa-solid fa-percent side-menu__icon"></i>
                        <span class="side-menu__label">Khuyến mãi nạp tiền</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=discounts") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["discounts"]) ?>">
                        <i class="fa-solid fa-percent side-menu__icon"></i>
                        <span class="side-menu__label">% chiết khấu</span>
                    </a>
                </li>

                <li class="slide__category"><span class="category-name">Cài đặt hệ thống</span></li>
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=currency-list") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["currency-list"]) ?>">
                        <i class="bx bx-dollar side-menu__icon"></i>
                        <span class="side-menu__label">Tiền tệ</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="<?= base_url("?module=admin&action=theme") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["theme"]) ?>">
                        <i class="bx bxs-image side-menu__icon"></i>
                        <span class="side-menu__label">Giao diện</span>
                    </a>
                </li>
                <li class="slide mb-5">
                    <a href="<?= base_url("?module=admin&action=settings") ?>"
                        class="side-menu__item <?= active_sidebar_admin(["settings"]) ?>">
                        <i class="bx bx-cog side-menu__icon"></i>
                        <span class="side-menu__label">Cài đặt</span>
                    </a>
                </li>
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                    height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>
        </nav>
        <!-- End::nav -->
    </div>
    <!-- End::main-sidebar -->
</aside>
<!-- End::app-sidebar -->