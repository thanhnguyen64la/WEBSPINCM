<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
?>
<div class="col-lg-3">
    <a class="sidebar_profile <?= active_sidebar_client(["profile"]) ?>" href="<?= client_url("profile") ?>">
        <h6><i class="fas fa-user"></i> <span>Thông tin cá nhân</span></h6>
    </a>
    <a class="sidebar_profile <?= active_sidebar_client(["logs"]) ?>" href="<?= client_url("logs") ?>">
        <h6><i class="fa fa-history"></i> <span>Nhật ký hoạt động</span></h6>
    </a>
    <a class="sidebar_profile <?= active_sidebar_client(["transactions"]) ?>" href="<?= client_url("transactions") ?>">
        <h6><i class="fa-solid fa-wallet"></i> <span>Biến động số dư</span></h6>
    </a>
    <a class="sidebar_profile <?= active_sidebar_client(["change-password"]) ?>"
        href="<?= client_url("change-password") ?>">
        <h6><i class="fa fa-key"></i> <span>Thay đổi mật khẩu</span></h6>
    </a>
    <a class="sidebar_profile" onclick="logout()" href="javascript:void(0)">
        <h6><i class="fa-solid fa-right-from-bracket"></i> <span>Đăng Xuất</span></h6>
    </a>
    <script type="text/javascript">
        function logout() {
            Swal.fire({
                title: 'Bạn có chắc chắn không ?',
                text: "Bạn sẽ đăng xuất ra khỏi tài khoản khi ấn đồng ý",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Huỷ bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = "<?= client_url("logout") ?>";
                }
            })
        }
    </script>
</div>