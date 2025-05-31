<?php
if (!defined('REQUEST')) {
    exit('The Request Not Found');
}
?>
<!-- FOOTER -->
<footer class="footer-part">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-lg-4">
                <div class="footer-widget">
                    <a class="footer-logo" href="<?= base_url(); ?>"><img src="<?= base_url($HN->setting("logo")) ?>"
                            alt="logo" /></a>
                    <p class="footer-desc"><?= $HN->setting('notification_footer_left'); ?></p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="footer-widget contact">
                    <h3 class="footer-title">Liên hệ</h3>
                    <ul class="footer-contact">
                        <li>
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
                            <p>
                                <span><a target="_blank"
                                        href="https://zalo.me/<?= $HN->setting('zalo'); ?>"><?= $HN->setting('zalo'); ?></a></span>
                            </p>
                        </li>
                        <li>
                            <img style="margin-right: 15px;" height="35" width="35"
                                src="<?= base_url('assets/img/storage/phonecall.svg') ?>">
                            <p>
                                <span><?= $HN->setting('hotline'); ?></span>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="footer-widget ">
                    <h3 class="footer-title">Liên kết</h3>
                    <div class="footer-links">
                        <ul>
                            <li><a href="<?= client_url('policy') ?>">Chính sách</a></li>
                            <li><a href="<?= client_url('contact') ?>">Liên hệ</a></li>
                            <li><a href="<?= $HN->setting('fanpage_link') ?>" target="_blank">Fanpage</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="footer-bottom">
                    <p class="footer-copytext">
                        <!-- Copyright -->
                        &copy; All Copyrights Reserved By <a href="https://muaspincm.com/">MUASPINCM.COM</a>
                    </p>
                    <div class="footer-card">
                        <a>
                            <img src="<?= base_url("assets/img/payment/jpg/01.jpg"); ?>" alt="payment" />
                        </a>
                        <a>
                            <img src="<?= base_url("assets/img/payment/jpg/02.jpg"); ?>" alt="payment" />
                        </a>
                        <a>
                            <img src="<?= base_url("assets/img/payment/jpg/03.jpg"); ?>" alt="payment" />
                        </a>
                        <a>
                            <img src="<?= base_url("assets/img/payment/jpg/04.jpg"); ?>" alt="payment" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>


<!-- Bootstrap -->
<script src="<?= base_url("assets/vendor/bootstrap/popper.min.js"); ?>"></script>
<script src="<?= base_url("assets/vendor/bootstrap/bootstrap.min.js"); ?>"></script>
<!-- Countdown -->
<script src="<?= base_url("assets/vendor/countdown/countdown.min.js"); ?>"></script>
<!-- Nice Select -->
<script src="<?= base_url("assets/vendor/niceselect/nice-select.min.js"); ?>"></script>
<!-- Slick Slider -->
<script src="<?= base_url("assets/vendor/slickslider/slick.min.js"); ?>"></script>
<!-- Venobox -->
<script src="<?= base_url("assets/vendor/venobox/venobox.min.js"); ?>"></script>
<!-- Simple-notify -->
<script src="https://cdn.jsdelivr.net/npm/simple-notify@1.0.4/dist/simple-notify.min.js"></script>
<!-- Flatpickr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"
    integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Google Recaptcha -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- Clipboard -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
<!-- Translate -->
<script src="https://cdn.gtranslate.net/widgets/latest/dropdown.js" defer></script>
<!-- Main Js -->
<script src="<?= base_url("assets/js/nice-select.js"); ?>"></script>
<script src="<?= base_url("assets/js/countdown.js"); ?>"></script>
<script src="<?= base_url("assets/js/accordion.js"); ?>"></script>
<script src="<?= base_url("assets/js/venobox.js"); ?>"></script>
<script src="<?= base_url("assets/js/slick.js"); ?>"></script>
<script src="<?= base_url("assets/js/main.js"); ?>"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    flatpickr("#flatpickr-range");
    new ClipboardJS(".copy");

    function copy() {
        new Notify({
            status: 'success',
            title: 'Thành công',
            text: 'Sao chép thành công !',
            autotimeout: 3000,
        })
    }
    window.gtranslateSettings = {
        "default_language": "vi",
        "languages": ["vi", "en", "de", "it", "es", "zh-CN", "ar", "tr", "ru", "uk", "km", "th", "fr"],
        "wrapper_selector": ".gtranslate_wrapper"
    }


    function change_currency() {
        var id = document.getElementById("changeCurrency").value;
        $.ajax({
            url: "<?= base_url("ajaxs/client/update.php") ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                action: 'changeCurrency',
                id: id
            },
            success: function (response) {
                if (response.status == 'success') {
                    location.reload();
                } else {
                    new Notify({
                        status: 'error',
                        title: 'Error',
                        text: response.msg,
                        autotimeout: 3000,
                    })
                }
            },
            error: function (status) {
                location.reload();
            }
        });
    }

    function pasteFromClipboard() {
        navigator.clipboard.readText().then(function (clipboardData) {
            document.getElementById('invite_code').value = clipboardData;
        }).catch(function (err) {
            new Notify({
                status: 'error',
                title: 'Thất bại',
                text: 'Thao tác thất bại',
                autotimeout: 3000,
            })
        });
    }

    function totalPayment() {
        const amount = $("#amount").val();
        const tokenElement = $("#token");
        const token = tokenElement.length ? tokenElement.val() : null;
        var activeElement = document.querySelector('.profile-card.active');
        const type = activeElement.getAttribute('data-type');
        const check = activeElement.getAttribute('data-check');
        $.ajax({
            url: "<?= base_url("ajaxs/client/view.php") ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                action: 'totalPayment_Spin',
                type: type,
                check: check,
                amount: amount,
                token: token
            },
            success: function (data) {
                if (data.status == 'success') {
                    const into_pay = $("#into_pay");
                    into_pay.html(data.pay);
                    document.getElementById('showDesc').innerHTML = data.desc;
                    document.getElementById('showDesc').style.display = 'block';

                } else {
                    new Notify({
                        status: 'error',
                        title: 'Thất bại',
                        text: data.msg,
                        autotimeout: 3000,
                    })
                }
            },
            error: function (status) {
                new Notify({
                    status: 'error',
                    title: 'Thất bại',
                    text: 'Vui lòng liên hệ Developer',
                    autotimeout: 3000,
                })
            }
        });
    }
    totalPayment();
    document.addEventListener('DOMContentLoaded', function () {
        var loaiLinks = document.querySelectorAll('.loailink');
        loaiLinks.forEach(function (loaiLink) {
            loaiLink.addEventListener('click', function () {
                totalPayment();
            });
        });
    });

    function validateInput() {
        const inputElement = document.querySelector('#amount');
        let currentValue = parseInt(inputElement.value);
        if (isNaN(currentValue) || currentValue < 1) {
            currentValue = 1;
        } else if (currentValue > 3) {
            currentValue = 3;
        }
        inputElement.value = currentValue;
        totalPayment();
    }
    const inputElement = document.querySelector('#amount');
    const plusButton = document.querySelector('.action-plus1');
    const minusButton = document.querySelector('.action-minus1');
    plusButton.addEventListener('click', function () {
        let currentValue = parseInt(inputElement.value);
        if (currentValue < 3) {
            currentValue++;
            inputElement.value = currentValue;
            totalPayment();
        }
    });
    minusButton.addEventListener('click', function () {
        let currentValue = parseInt(inputElement.value);
        currentValue = Math.max(1, currentValue - 1);
        inputElement.value = currentValue;
        totalPayment();
    });


</script>
</body>

</html>