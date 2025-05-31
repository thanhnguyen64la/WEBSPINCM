<?php if (!defined('REQUEST')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Cấu Hình Tiền Tệ | Quản Lý Website'
];
require_once(__DIR__ . '/../../models/is_admin.php');
if (isset($_GET["id"])) {
    $id = check_string($_GET["id"]);
    $row = $HN->get_row("SELECT * FROM `currencies` WHERE `id` = '" . $id . "' ");
    if (!$row) {
        redirect(base_url("admin/currency-list"));
    }
} else {
    redirect(base_url("admin/currency-list"));
}
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/sidebar.php');

use Detection\MobileDetect;

$Mobile_Detect = new MobileDetect();
if (isset($_POST["SaveCurrency"])) {
    $isUpdate = $HN->update("currencies", [
        "currency_name" => check_string($_POST["currency_name"]),
        "currency_code" => check_string($_POST["currency_code"]),
        "currency_symbol" => !empty($_POST["currency_symbol"]) ? $_POST["currency_symbol"] : NULL,
        "currency_rate" => !empty($_POST["currency_rate"]) ? check_string($_POST["currency_rate"]) : 0,
        "currency_decimal" => !empty($_POST["currency_decimal"]) ? check_string($_POST["currency_decimal"]) : 0,
        "currency_seperator" => !empty($_POST["currency_seperator"]) ? check_string($_POST["currency_seperator"]) : "dot",
        "currency_status" => check_string($_POST["currency_status"])
    ], " `id` = '" . $id . "' ");
    if ($isUpdate) {
        $HN->insert("logs", ["user_id" => $user["id"], "ip" => get_ip(), "device" => $Mobile_Detect->getUserAgent(), "created_time" => get_time(), "action" => __("Chỉnh sửa tiền tệ") . " (" . check_string($_POST["currency_name"]) . ")."]);
        msg_success_link("Sửa thành công", base_url("?module=admin&action=currency-list"), 2000);
    }
    msg_error_link("Sửa thất bại", "", 3000);
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Edit Currency</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a
                                href="<?= base_url("?module=admin&action=currency-list") ?>">Currencies</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Đồng</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            CHỈNH SỬA TIỀN TỆ
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="name">Name:</label>
                                        <input name="currency_name" type="text" value="<?= $row['currency_name']; ?>"
                                            class="form-control" id="currency_name" placeholder="Name" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="code">Code:</label>
                                        <select class="form-control" data-trigger name="currency_code" required>
                                            <option value="<?= $row['currency_code'] ?>"><?= $row['currency_name'] ?>
                                            </option>
                                            <option value="AED">AED - United Arab Emirates Dirham</option>
                                            <option value="AFN">AFN - Afghanistan Afghani</option>
                                            <option value="ALL">ALL - Albania Lek</option>
                                            <option value="AMD">AMD - Armenia Dram</option>
                                            <option value="ANG">ANG - Netherlands Antilles Guilder</option>
                                            <option value="AOA">AOA - Angola Kwanza</option>
                                            <option value="ARS">ARS - Argentina Peso</option>
                                            <option value="AUD">AUD - Australia Dollar</option>
                                            <option value="AWG">AWG - Aruba Guilder</option>
                                            <option value="AZN">AZN - Azerbaijan New Manat</option>
                                            <option value="BBD">BBD - Barbados Dollar</option>
                                            <option value="BDT">BDT - Bangladesh Taka</option>
                                            <option value="BGN">BGN - Bulgaria Lev</option>
                                            <option value="BHD">BHD - Bahrain Dinar</option>
                                            <option value="BIF">BIF - Burundi Franc</option>
                                            <option value="BMD">BMD - Bermuda Dollar</option>
                                            <option value="BND">BND - Brunei Darussalam Dollar</option>
                                            <option value="BOB">BOB - Bolivia Bolíviano</option>
                                            <option value="BRL">BRL - Brazil Real</option>
                                            <option value="BSD">BSD - Bahamas Dollar</option>
                                            <option value="BTC">BTC - Bitcoin</option>
                                            <option value="BTN">BTN - Bhutan Ngultrum</option>
                                            <option value="BWP">BWP - Botswana Pula</option>
                                            <option value="BYN">BYN - Belarus Ruble</option>
                                            <option value="BZD">BZD - Belize Dollar</option>
                                            <option value="CAD">CAD - Canada Dollar</option>
                                            <option value="CDF">CDF - Congo/Kinshasa Franc</option>
                                            <option value="CHF">CHF - Switzerland Franc</option>
                                            <option value="CLP">CLP - Chile Peso</option>
                                            <option value="CNY">CNY - China Yuan Renminbi</option>
                                            <option value="COP">COP - Colombia Peso</option>
                                            <option value="CRC">CRC - Costa Rica Colon</option>
                                            <option value="CUC">CUC - Cuba Convertible Peso</option>
                                            <option value="CUP">CUP - Cuba Peso</option>
                                            <option value="CVE">CVE - Cape Verde Escudo</option>
                                            <option value="CZK">CZK - Czech Republic Koruna</option>
                                            <option value="DJF">DJF - Djibouti Franc</option>
                                            <option value="DKK">DKK - Denmark Krone</option>
                                            <option value="DOP">DOP - Dominican Republic Peso</option>
                                            <option value="DZD">DZD - Algeria Dinar</option>
                                            <option value="EGP">EGP - Egypt Pound</option>
                                            <option value="ERN">ERN - Eritrea Nakfa</option>
                                            <option value="ETB">ETB - Ethiopia Birr</option>
                                            <option value="ETH">ETH - Ethereum</option>
                                            <option value="EUR">EUR - Euro Member Countries</option>
                                            <option value="FJD">FJD - Fiji Dollar</option>
                                            <option value="GBP">GBP - United Kingdom Pound</option>
                                            <option value="GEL">GEL - Georgia Lari</option>
                                            <option value="GGP">GGP - Guernsey Pound</option>
                                            <option value="GHS">GHS - Ghana Cedi</option>
                                            <option value="GIP">GIP - Gibraltar Pound</option>
                                            <option value="GMD">GMD - Gambia Dalasi</option>
                                            <option value="GNF">GNF - Guinea Franc</option>
                                            <option value="GTQ">GTQ - Guatemala Quetzal</option>
                                            <option value="GYD">GYD - Guyana Dollar</option>
                                            <option value="HKD">HKD - Hong Kong Dollar</option>
                                            <option value="HNL">HNL - Honduras Lempira</option>
                                            <option value="HRK">HRK - Croatia Kuna</option>
                                            <option value="HTG">HTG - Haiti Gourde</option>
                                            <option value="HUF">HUF - Hungary Forint</option>
                                            <option value="IDR">IDR - Indonesia Rupiah</option>
                                            <option value="ILS">ILS - Israel Shekel</option>
                                            <option value="IMP">IMP - Isle of Man Pound</option>
                                            <option value="INR">INR - India Rupee</option>
                                            <option value="IQD">IQD - Iraq Dinar</option>
                                            <option value="IRR">IRR - Iran Rial</option>
                                            <option value="ISK">ISK - Iceland Krona</option>
                                            <option value="JEP">JEP - Jersey Pound</option>
                                            <option value="JMD">JMD - Jamaica Dollar</option>
                                            <option value="JOD">JOD - Jordan Dinar</option>
                                            <option value="JPY">JPY - Japan Yen</option>
                                            <option value="KES">KES - Kenya Shilling</option>
                                            <option value="KGS">KGS - Kyrgyzstan Som</option>
                                            <option value="KHR">KHR - Cambodia Riel</option>
                                            <option value="KMF">KMF - Comoros Franc</option>
                                            <option value="KPW">KPW - Korea (North) Won</option>
                                            <option value="KRW">KRW - Korea (South) Won</option>
                                            <option value="KWD">KWD - Kuwait Dinar</option>
                                            <option value="KYD">KYD - Cayman Islands Dollar</option>
                                            <option value="KZT">KZT - Kazakhstan Tenge</option>
                                            <option value="LAK">LAK - Laos Kip</option>
                                            <option value="LBP">LBP - Lebanon Pound</option>
                                            <option value="LKR">LKR - Sri Lanka Rupee</option>
                                            <option value="LRD">LRD - Liberia Dollar</option>
                                            <option value="LSL">LSL - Lesotho Loti</option>
                                            <option value="LTC">LTC - Litecoin</option>
                                            <option value="LYD">LYD - Libya Dinar</option>
                                            <option value="MAD">MAD - Morocco Dirham</option>
                                            <option value="MDL">MDL - Moldova Leu</option>
                                            <option value="MGA">MGA - Madagascar Ariary</option>
                                            <option value="MKD">MKD - Macedonia Denar</option>
                                            <option value="MMK">MMK - Myanmar (Burma) Kyat</option>
                                            <option value="MNT">MNT - Mongolia Tughrik</option>
                                            <option value="MOP">MOP - Macau Pataca</option>
                                            <option value="MRO">MRO - Mauritania Ouguiya</option>
                                            <option value="MUR">MUR - Mauritius Rupee</option>
                                            <option value="MWK">MWK - Malawi Kwacha</option>
                                            <option value="MXN">MXN - Mexico Peso</option>
                                            <option value="MYR">MYR - Malaysia Ringgit</option>
                                            <option value="MZN">MZN - Mozambique Metical</option>
                                            <option value="NAD">NAD - Namibia Dollar</option>
                                            <option value="NGN">NGN - Nigeria Naira</option>
                                            <option value="NIO">NIO - Nicaragua Cordoba</option>
                                            <option value="NOK">NOK - Norway Krone</option>
                                            <option value="NPR">NPR - Nepal Rupee</option>
                                            <option value="NZD">NZD - New Zealand Dollar</option>
                                            <option value="OMR">OMR - Oman Rial</option>
                                            <option value="PAB">PAB - Panama Balboa</option>
                                            <option value="PEN">PEN - Peru Sol</option>
                                            <option value="PGK">PGK - Papua New Guinea Kina</option>
                                            <option value="PHP">PHP - Philippines Peso</option>
                                            <option value="PKR">PKR - Pakistan Rupee</option>
                                            <option value="PLN">PLN - Poland Zloty</option>
                                            <option value="PYG">PYG - Paraguay Guarani</option>
                                            <option value="QAR">QAR - Qatar Riyal</option>
                                            <option value="RON">RON - Romania New Leu</option>
                                            <option value="RSD">RSD - Serbia Dinar</option>
                                            <option value="RUB">RUB - Russia Ruble</option>
                                            <option value="RWF">RWF - Rwanda Franc</option>
                                            <option value="SAR">SAR - Saudi Arabia Riyal</option>
                                            <option value="SCR">SCR - Seychelles Rupee</option>
                                            <option value="SDG">SDG - Sudan Pound</option>
                                            <option value="SEK">SEK - Sweden Krona</option>
                                            <option value="SGD">SGD - Singapore Dollar</option>
                                            <option value="SHP">SHP - Saint Helena Pound</option>
                                            <option value="SLL">SLL - Sierra Leone Leone</option>
                                            <option value="SOS">SOS - Somalia Shilling</option>
                                            <option value="SPL">SPL - Seborga Luigino</option>
                                            <option value="SRD">SRD - Suriname Dollar</option>
                                            <option value="SVC">SVC - El Salvador Colon</option>
                                            <option value="SYP">SYP - Syria Pound</option>
                                            <option value="SZL">SZL - Swaziland Lilangeni</option>
                                            <option value="THB">THB - Thailand Baht</option>
                                            <option value="TJS">TJS - Tajikistan Somoni</option>
                                            <option value="TMT">TMT - Turkmenistan Manat</option>
                                            <option value="TND">TND - Tunisia Dinar</option>
                                            <option value="TOP">TOP - Tonga Pa'anga</option>
                                            <option value="TRY">TRY - Turkey Lira</option>
                                            <option value="TVD">TVD - Tuvalu Dollar</option>
                                            <option value="TWD">TWD - Taiwan New Dollar</option>
                                            <option value="TZS">TZS - Tanzania Shilling</option>
                                            <option value="UAH">UAH - Ukraine Hryvnia</option>
                                            <option value="UGX">UGX - Uganda Shilling</option>
                                            <option value="USD">USD - United States Dollar</option>
                                            <option value="UYU">UYU - Uruguay Peso</option>
                                            <option value="UZS">UZS - Uzbekistan Som</option>
                                            <option value="VEF">VEF - Venezuela Bolivar</option>
                                            <option value="VND">VND - Viet Nam Dong</option>
                                            <option value="VUV">VUV - Vanuatu Vatu</option>
                                            <option value="WST">WST - Samoa Tala</option>
                                            <option value="YER">YER - Yemen Rial</option>
                                            <option value="ZAR">ZAR - South Africa Rand</option>
                                            <option value="ZMW">ZMW - Zambia Kwacha</option>
                                            <option value="ZWD">ZWD - Zimbabwe Dollar</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Symbol:</label>
                                        <input name="currency_symbol" type="text"
                                            value="<?= $row['currency_symbol']; ?>" class="form-control" id="url"
                                            placeholder="Enter Symbol" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="value">Rate:</label>
                                        <div class="input-group">
                                            <input name="currency_rate" type="text" class="form-control" id="rate"
                                                value="<?= $row['currency_rate']; ?>"
                                                placeholder="Bao nhiêu <?= $HN->get_row(" SELECT `currency_code` FROM `currencies` WHERE `currency_status` = 'on' AND `currency_status_default` = 'on'")["currency_code"] ?>"
                                                value="" required>
                                            <span
                                                class="input-group-text"><?= $HN->get_row(" SELECT `currency_code` FROM `currencies` WHERE `currency_status` = 'on' AND `currency_status_default` = 'on'")["currency_code"]; ?></span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="value">Decimals (VND is 0 USD is 2):</label>
                                        <input name="currency_decimal" type="number"
                                            value="<?= $row['currency_decimal']; ?>" class="form-control"
                                            id="currency_decimal" placeholder="VND là 0, USD là 2" value="" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="seperator">Seperator:</label>
                                        <select class="form-control" name="currency_seperator" required>
                                            <option <?= $row["currency_seperator"] == "comma" ? "selected" : ""; ?>
                                                value="comma">
                                                Comma (,)
                                            </option>
                                            <option <?= $row["currency_seperator"] == "space" ? "selected" : ""; ?>
                                                value="space">
                                                Space ( )
                                            </option>
                                            <option <?= $row["currency_seperator"] == "dot" ? "selected" : ""; ?>
                                                value="dot">
                                                Dot (.)
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="status">Status:</label>
                                        <select class="form-control" name="currency_status" required>
                                            <option <?= $row['currency_status'] == "on" ? 'selected' : ''; ?> value="on">
                                                Show </option>
                                            <option <?= $row['currency_status'] == "off" ? 'selected' : ''; ?> value="off">
                                                Hide </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <a type="button" class="btn btn-danger shadow-danger btn-wave"
                                href="<?= base_url("?module=admin&action=currency-list") ?>"><i
                                    class="fa fa-fw fa-undo me-1"></i>
                                Back</a>
                            <button type="submit" name="SaveCurrency" class="btn btn-primary shadow-primary btn-wave"><i
                                    class="fa fa-fw fa-save me-1"></i> Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once(__DIR__ . '/footer.php');
?>