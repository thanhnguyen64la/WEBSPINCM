<body>
    <div class="backdrop"></div><a class="backtop" href="#"><i class="fa-sharp fa-solid fa-chevron-up"></i></a>
    <!-- HEADER TOP -->
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-5">
                    <div class="header-top-welcome">
                        <p><?= $HN->setting("notification_top_left"); ?></p>
                    </div>
                </div>
                <div class="col-md-5 col-lg-3">
                    <div class="header-top-select">
                        <div class="header-select">
                            <i class="icofont-world"></i>
                            <div class="gtranslate_wrapper"></div>
                        </div>
                        <div class="header-select">
                            <i class="icofont-money"></i>
                            <select class="select" id="changeCurrency" onchange="change_currency()">
                                <?php foreach ($HN->get_list("SELECT * FROM `currencies` WHERE `currency_status` = 'on' ") as $currency): ?>
                                    <option value="<?= $currency["id"]; ?>" <?= get_currency() == $currency["id"] ? "selected" : ""; ?>>
                                        <?= $currency["currency_code"]; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HEADER -->
    <header class="header-part">
        <div class="container">
            <div class="header-content">
                <div class="header-media-group">
                    <a class="header-user" href="<?= client_url("profile") ?>">
                        <img src="<?= base_url("assets/img/theme/avatar.jpg") ?>" alt="user">
                    </a>
                    <a href="<?= base_url() ?>">
                        <img src="<?= base_url($HN->setting("logo")) ?>" style="height:60px;" alt="logo">
                    </a>
                    <button class="header-src"><i class="fas fa-search"></i></button>
                </div>
                <a href="<?= base_url() ?>" class="header-logo">
                    <img style="height:80px;" src="<?= base_url($HN->setting("logo")) ?>" alt="logo">
                </a>
                <form class="header-form">
                    <input type="text" placeholder="Tìm kiếm gì đó..." /><button>
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <div class="header-widget-group">
                    <a href="<?= client_url("history-order") ?>" class="header-widget" title="Đơn hàng">
                        <i class="fa-solid fa-cart-arrow-down"></i>
                    </a>
                    <button class="header-widget header-cart" title="Phương thức thanh toán">
                        <i class="fa-solid fa-building-columns"></i>
                    </button>
                    <?php
                    if (isset($user)):
                        ?>
                        <a href="<?= client_url("profile") ?>" class="header-widget" title="Đăng nhập">
                            <img src="<?= base_url("assets/img/theme/avatar.jpg") ?>" alt="user" />
                            <span><?= $user['username']; ?></span>
                        </a>
                    <?php else: ?>
                        <a href="<?= client_url("login") ?>" class="header-widget" title="Đăng nhập">
                            <img src="<?= base_url("assets/img/theme/avatar.jpg") ?>" alt="user" />
                            <span>Đăng nhập</span>
                        </a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </header>
    <!-- NAVBAR -->
    <nav class="navbar-part">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="navbar-content">
                        <ul class="navbar-list">
                            <li class="navbar-item">
                                <a class="navbar-link" href="<?= base_url() ?>">Trang chủ</a>
                            </li>
                            <li class="navbar-item dropdown">
                                <a class="navbar-link dropdown-arrow" href="#">Nạp tiền</a>
                                <ul class="dropdown-position-list">
                                    <li>
                                        <a href="<?= client_url("recharge-bank") ?>">
                                            <img width="20px" src="<?= base_url("assets/img/storage/icon-bank.svg") ?>">
                                            Ngân hàng
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= client_url("recharge-card") ?>">
                                            <img width="20px" src="<?= base_url("assets/img/storage/icon-card.png") ?>">
                                            Thẻ cào
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-item dropdown">
                                <a class="navbar-link dropdown-arrow" href="#">Lịch sử</a>
                                <ul class="dropdown-position-list">
                                    <li><a href="<?= client_url("logs") ?>">Nhật ký hoạt động</a></li>
                                    <li><a href="<?= client_url("transactions") ?>">Biến động số dư</a></li>
                                    <li><a href="<?= client_url("history-order") ?>">Lịch sử đơn hàng</a></li>
                                </ul>
                            </li>
                            <li>
                                <?php if (isset($user) && $user['admin'] == 'on'): ?>
                                    <a class="navbar-link" href="<?= admin_url() ?>">Trang Quản Trị</a>
                                <?php endif; ?>
                            </li>
                        </ul>
                        <div class="navbar-info-group">
                            <div class="navbar-info">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" height="35" width="35"
                                    style="margin-right: 15px;" viewBox="0 0 48 48">
                                    <path fill="#2962ff"
                                        d="M15,36V6.827l-1.211-0.811C8.64,8.083,5,13.112,5,19v10c0,7.732,6.268,14,14,14h10	c4.722,0,8.883-2.348,11.417-5.931V36H15z">
                                    </path>
                                    <path fill="#eee"
                                        d="M29,5H19c-1.845,0-3.601,0.366-5.214,1.014C10.453,9.25,8,14.528,8,19	c0,6.771,0.936,10.735,3.712,14.607c0.216,0.301,0.357,0.653,0.376,1.022c0.043,0.835-0.129,2.365-1.634,3.742	c-0.162,0.148-0.059,0.419,0.16,0.428c0.942,0.041,2.843-0.014,4.797-0.877c0.557-0.246,1.191-0.203,1.729,0.083	C20.453,39.764,24.333,40,28,40c4.676,0,9.339-1.04,12.417-2.916C42.038,34.799,43,32.014,43,29V19C43,11.268,36.732,5,29,5z">
                                    </path>
                                    <path fill="#2962ff"
                                        d="M36.75,27C34.683,27,33,25.317,33,23.25s1.683-3.75,3.75-3.75s3.75,1.683,3.75,3.75	S38.817,27,36.75,27z M36.75,21c-1.24,0-2.25,1.01-2.25,2.25s1.01,2.25,2.25,2.25S39,24.49,39,23.25S37.99,21,36.75,21z">
                                    </path>
                                    <path fill="#2962ff" d="M31.5,27h-1c-0.276,0-0.5-0.224-0.5-0.5V18h1.5V27z"></path>
                                    <path fill="#2962ff"
                                        d="M27,19.75v0.519c-0.629-0.476-1.403-0.769-2.25-0.769c-2.067,0-3.75,1.683-3.75,3.75	S22.683,27,24.75,27c0.847,0,1.621-0.293,2.25-0.769V26.5c0,0.276,0.224,0.5,0.5,0.5h1v-7.25H27z M24.75,25.5	c-1.24,0-2.25-1.01-2.25-2.25S23.51,21,24.75,21S27,22.01,27,23.25S25.99,25.5,24.75,25.5z">
                                    </path>
                                    <path fill="#2962ff"
                                        d="M21.25,18h-8v1.5h5.321L13,26h0.026c-0.163,0.211-0.276,0.463-0.276,0.75V27h7.5	c0.276,0,0.5-0.224,0.5-0.5v-1h-5.321L21,19h-0.026c0.163-0.211,0.276-0.463,0.276-0.75V18z">
                                    </path>
                                </svg>
                                <p><small>Zalo</small><a href="https://zalo.me/<?= $HN->setting('zalo'); ?>"
                                        target="_blank"><span><?= $HN->setting('zalo'); ?></span></a></p>
                            </div>
                            <div class="navbar-info">
                                <img style="margin-right: 15px;" height="35" width="35"
                                    src="<?= base_url('assets/img/storage/phonecall.svg') ?>">
                                <p><small>Hotline</small><span><?= $HN->setting('hotline'); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- NAVBAR MOBILE -->
    <div class="mobile-menu">
        <a href="<?= client_url() ?>" title="Trang chủ" class="<?= active_sidebar_client(["home", ""]) ?>">
            <i class="fas fa-home"></i>
            <span>Trang chủ</span>
        </a>
        <button class="cart-btn" title="Phương thức nạp tiền">
            <i class="fa-solid fa-building-columns"></i>
            <span>Nạp tiền</span>
        </button>
        <a href="<?= client_url("profile") ?>" title="Thông tin" class="<?= active_sidebar_client(["profile"]) ?>">
            <i class="fa-solid fa-user"></i>
            <span>Thông tin</span>
        </a>
        <?php if (isset($user) && $user['admin'] == 'on'): ?>
            <a href="<?= admin_url() ?>" title="Trang Quản Trị">
                <i class="fa-solid fa-toolbox"></i>
                <span>Trang quản trị</span>
            </a>
        <?php endif; ?>
    </div>
    <!-- Sidebar Payment Methods -->
    <aside class="cart-sidebar">
        <div class="cart-header">
            <div class="cart-total">
                <i class="fa-solid fa-building-columns"></i><span>Chọn phương thức nạp tiền</span>
            </div>
            <button class="cart-close"><i class="icofont-close"></i></button>
        </div>
        <ul class="category-list">
            <li class="category-item">
                <a class="category-link" href="<?= client_url("recharge-bank") ?>">
                    <img style="width: 30px; margin-right: 10px;"
                        src="<?= base_url("assets/img/storage/icon-bank.svg") ?>" alt="icon-bank" />
                    Ngân hàng
                </a>
            </li>
            <li class="category-item">
                <a class="category-link" href="<?= client_url("recharge-card") ?>">
                    <img style="width: 30px; margin-right: 10px;"
                        src="<?= base_url("assets/img/storage/icon-card.png") ?>" alt="icon-card" />
                    Nạp thẻ
                </a>
            </li>
        </ul>
        <div class="category-footer">
            <!-- Copyright -->
            <p>© All Copyrights Reserved By <a target="_blank" href="https://muaspincm.com/">MuaSpinCm.Com</a></p>
        </div>
    </aside>