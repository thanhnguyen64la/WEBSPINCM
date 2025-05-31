<?php
if (!defined('REQUEST')) {
    die('The Request Not Found');
}
?>
<footer class="footer mt-auto py-3 bg-white text-center">
    <div class="container">
        <span class="text-muted">
            © All Copyrights Reserved by <a target="_blank" href="https://muaspincm.com/"><span
                    class="fw-semibold text-primary text-decoration-underline">MuaSpinCm.Com</span></a>
        </span>
        <div class="gtranslate_wrapper"></div>
        <script>
            window.gtranslateSettings = {
                "default_language": "vi",
                "languages": ["vi", "en", "th", "ms", "zh-CN", "tl", "de", "km", "ru", "my", "lo", "tr", "uk", "ko",
                    "zh-TW", "it", "fr", "ar"
                ],
                "wrapper_selector": ".gtranslate_wrapper"
            }
        </script>
        <!-- <script src="https://cdn.gtranslate.net/widgets/latest/flags.js" defer></script> -->
    </div>
</footer>
<!-- Footer End -->
</div>
<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>
<script>
    var elem = document.documentElement;

    function openFullscreen() {
        let open = document.querySelector(".full-screen-open");
        let close = document.querySelector(".full-screen-close");

        if (
            !document.fullscreenElement &&
            !document.webkitFullscreenElement &&
            !document.msFullscreenElement
        ) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                /* IE11 */
                elem.msRequestFullscreen();
            }
            close.classList.add("d-block");
            close.classList.remove("d-none");
            open.classList.add("d-none");
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                /* Safari */
                document.webkitExitFullscreen();
                console.log("working");
            } else if (document.msExitFullscreen) {
                /* IE11 */
                document.msExitFullscreen();
            }
            close.classList.remove("d-block");
            open.classList.remove("d-none");
            close.classList.add("d-none");
            open.classList.add("d-block");
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<script type="text/javascript">
    new ClipboardJS(".copy");

    function copy() {
        showMessage('Đã sao chép vào bộ nhớ tạm', 'success');
    }
</script>
<script>
    $('#datatable-basic').DataTable({
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
        },
        "pageLength": 10,
        scrollX: true,
    });
</script>

<!-- Scroll To Top -->
<!-- Popper JS -->
<script src="<?= base_url("public/theme/assets/libs/@popperjs/core/umd/popper.min.js") ?>"></script>
<!-- Bootstrap JS -->
<script src="<?= base_url("public/theme/assets/libs/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>
<!-- Defaultmenu JS -->
<script src="<?= base_url("public/theme/assets/js/defaultmenu.min.js") ?>"></script>
<!-- Node Waves JS-->
<script src="<?= base_url("public/theme/assets/libs/node-waves/waves.min.js") ?>"></script>
<!-- Sticky JS -->
<script src="<?= base_url("public/theme/assets/js/sticky.js") ?>"></script>
<!-- Simplebar JS -->
<script src="<?= base_url("public/theme/assets/libs/simplebar/simplebar.min.js") ?>"></script>
<script src="<?= base_url("public/theme/assets/js/simplebar.js") ?>"></script>
<!-- Color Picker JS -->
<script src="<?= base_url("public/theme/assets/libs/@simonwep/pickr/pickr.es5.min.js") ?>"></script>
<!-- Custom-Switcher JS -->
<script src="<?= base_url("public/theme/assets/js/custom-switcher.min.js") ?>"></script>
<!-- Internal Swiper JS -->
<script src="<?= base_url("public/theme/assets/js/swiper.js") ?>"></script>
<!-- Custom JS -->
<script src="<?= base_url("public/theme/assets/js/custom.js") ?>"></script>
<!-- Prism JS -->
<script src="<?= base_url("public/theme/assets/libs/prismjs/prism.js") ?>"></script>
<script src="<?= base_url("public/theme/assets/js/prism-custom.js") ?>"></script>
<!-- Modal JS -->
<script src="<?= base_url("public/theme/assets/js/modal.js") ?>"></script>
<!-- Date & Time Picker JS -->
<script src="<?= base_url("public/theme/assets/libs/flatpickr/flatpickr.min.js") ?>"></script>
<script src="<?= base_url("public/theme/assets/js/date&time_pickers.js") ?>"></script>
<!-- Chartjs Chart JS -->
<script src="<?= base_url("public/theme/assets/libs/chart.js/chart.min.js") ?>"></script>
<!-- Gallery JS -->
<script src="<?= base_url("public/theme/assets/libs/glightbox/js/glightbox.min.js") ?>"></script>
<!-- Choices JS -->
<script src="<?= base_url("public/theme/assets/libs/choices.js/public/assets/scripts/choices.min.js") ?>"></script>
<!-- Swiper JS -->
<script src="<?= base_url("public/theme/assets/libs/swiper/swiper-bundle.min.js") ?>"></script>
</body>

</html>