-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 01:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test1`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `account_name` text DEFAULT NULL,
  `account_number` text DEFAULT NULL,
  `api_password` text DEFAULT NULL,
  `api_token` text DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'off'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trans_id` text DEFAULT NULL,
  `telco` varchar(255) DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `price` float NOT NULL DEFAULT 0,
  `serial` text NOT NULL,
  `pin` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_flow`
--

CREATE TABLE `cash_flow` (
  `id` int(11) NOT NULL,
  `initial_amount` float NOT NULL DEFAULT 0,
  `changed_amount` float NOT NULL DEFAULT 0,
  `current_amount` float NOT NULL DEFAULT 0,
  `created_time` datetime NOT NULL,
  `reason` text DEFAULT NULL,
  `trans_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config_api`
--

CREATE TABLE `config_api` (
  `id` int(11) NOT NULL,
  `type` text DEFAULT NULL,
  `domain` text DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `api_key` int(11) DEFAULT NULL,
  `token` int(11) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'off',
  `created_time` datetime NOT NULL,
  `money` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `currency_name` text NOT NULL,
  `currency_code` varchar(50) NOT NULL,
  `currency_rate` float NOT NULL DEFAULT 0,
  `currency_symbol` text NOT NULL,
  `currency_seperator` text NOT NULL,
  `currency_status` varchar(25) NOT NULL DEFAULT 'off',
  `currency_status_default` varchar(25) NOT NULL DEFAULT 'off',
  `currency_decimal` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency_name`, `currency_code`, `currency_rate`, `currency_symbol`, `currency_seperator`, `currency_status`, `currency_status_default`, `currency_decimal`) VALUES
(1, 'Đồng', 'VND', 1, 'đ', 'dot', 'on', 'on', 0),
(11, 'Dollar', 'USD', 24000, '$', 'dot', 'on', 'off', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ip_block_log`
--

CREATE TABLE `ip_block_log` (
  `id` int(11) NOT NULL,
  `ip` text NOT NULL,
  `attempt` int(11) NOT NULL DEFAULT 0,
  `created_time` datetime NOT NULL,
  `status_ban` varchar(25) NOT NULL DEFAULT 'off',
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ip_block_log`
--

INSERT INTO `ip_block_log` (`id`, `ip`, `attempt`, `created_time`, `status_ban`, `reason`) VALUES
(1, '5.62.63.201', 5, '2025-05-30 18:45:02', 'on', '[Warning] Đăng nhập thất bại nhiều lần');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` text DEFAULT NULL,
  `status_default` varchar(25) NOT NULL DEFAULT 'off',
  `status` varchar(25) NOT NULL DEFAULT 'off'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` text DEFAULT NULL,
  `device` text DEFAULT NULL,
  `created_time` datetime NOT NULL,
  `action` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `trans_id` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `invite_code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `api_price` float NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL,
  `remaining` int(11) NOT NULL,
  `status_refund` varchar(25) NOT NULL DEFAULT 'off',
  `api_server` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_bank`
--

CREATE TABLE `payment_bank` (
  `id` int(11) NOT NULL,
  `method` varchar(55) DEFAULT NULL,
  `tid` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `received` float NOT NULL DEFAULT 0,
  `created_time` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `bank_min` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `created_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `api_price` float NOT NULL DEFAULT 0,
  `type` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'off',
  `api_server` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'time_zone', 'Asia/Ho_Chi_Minh'),
(2, 'status', 'on'),
(3, 'title', ''),
(4, 'favicon', 'assets/img/theme/favicon_0Y3.png'),
(5, 'description', 'Dịch vụ tăng Spin Coin Master tốt nhất Việt Nam'),
(6, 'keywords', 'mua spin coin master, tang spin, mua spin gia re, mua spin, spin coin master, topup spin, link nhận spin,'),
(7, 'image_description', 'assets/img/theme/image_EY2.png'),
(8, 'primary_color', '#078497'),
(9, 'secondary_color', '#72cbb0'),
(14, 'logo', 'assets/img/theme/logo_U9V.png'),
(16, 'zalo', ''),
(17, 'hotline', ''),
(20, 'notification_footer_left', 'Dịch vụ tăng Spin Coin Master tốt nhất Việt Nam - Chúng tôi giúp bạn có thể trải nghiệm sự hiệu quả với chi phí hợp lý. Hệ thống của chúng tôi cung cấp một phương pháp nạp Spin nhanh chóng, an toàn và tự động, giúp bạn tối ưu hóa trải nghiệm chơi game mà không cần dành nhiều công sức.'),
(21, 'notification_top_left', 'Hệ thống chạy spin Coin Master tự động'),
(22, 'status_reCAPTCHA', 'off'),
(23, 'reCAPTCHA_site_key', ''),
(27, 'reCAPTCHA_secret_key', ''),
(28, 'max_register_ip', '10'),
(29, 'session_login', '2592000'),
(30, 'smtp_email', ''),
(31, 'smtp_password', ''),
(32, 'policy', '<p><strong>Ch&iacute;nh s&aacute;ch bảo mật</strong></p>\r\n\r\n<p>Ch&uacute;ng t&ocirc;i đặt rất nhiều gi&aacute; trị v&agrave;o việc bảo vệ th&ocirc;ng tin c&aacute; nh&acirc;n của bạn. Ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư n&agrave;y giải th&iacute;ch c&aacute;ch ch&uacute;ng t&ocirc;i thu thập, sử dụng v&agrave; bảo vệ th&ocirc;ng tin c&aacute; nh&acirc;n của bạn khi bạn sử dụng dịch vụ của ch&uacute;ng t&ocirc;i.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Thu thập v&agrave; sử dụng th&ocirc;ng tin</strong></p>\r\n\r\n<p>Khi bạn sử dụng trang web của ch&uacute;ng t&ocirc;i hoặc tương t&aacute;c với c&aacute;c dịch vụ của ch&uacute;ng t&ocirc;i, ch&uacute;ng t&ocirc;i c&oacute; thể thu thập một số th&ocirc;ng tin c&aacute; nh&acirc;n nhất định từ bạn. Điều n&agrave;y c&oacute; thể bao gồm t&ecirc;n, địa chỉ email, số điện thoại, địa chỉ v&agrave; th&ocirc;ng tin kh&aacute;c m&agrave; bạn cung cấp khi đăng k&yacute; hoặc sử dụng dịch vụ của ch&uacute;ng t&ocirc;i.</p>\r\n\r\n<p>Ch&uacute;ng t&ocirc;i c&oacute; thể sử dụng th&ocirc;ng tin c&aacute; nh&acirc;n của bạn để:</p>\r\n\r\n<ul>\r\n	<li>Cung cấp v&agrave; duy tr&igrave; dịch vụ</li>\r\n	<li>Th&ocirc;ng b&aacute;o về những thay đổi đối với dịch vụ của ch&uacute;ng t&ocirc;i</li>\r\n	<li>Giải quyết vấn đề hoặc tranh chấp</li>\r\n	<li>Theo d&otilde;i v&agrave; ph&acirc;n t&iacute;ch việc sử dụng dịch vụ của ch&uacute;ng t&ocirc;i</li>\r\n	<li>N&acirc;ng cao trải nghiệm người d&ugrave;ng</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Bảo vệ</strong></p>\r\n\r\n<p>Ch&uacute;ng t&ocirc;i cam kết bảo vệ th&ocirc;ng tin c&aacute; nh&acirc;n của bạn v&agrave; c&oacute; c&aacute;c biện ph&aacute;p bảo mật th&iacute;ch hợp để đảm bảo th&ocirc;ng tin của bạn được giữ an to&agrave;n khi bạn truy cập trang web của ch&uacute;ng t&ocirc;i.</p>\r\n\r\n<p>Tuy nhi&ecirc;n, h&atilde;y nhớ rằng kh&ocirc;ng c&oacute; phương thức truyền th&ocirc;ng tin n&agrave;o qua internet hoặc phương tiện điện tử l&agrave; an to&agrave;n hoặc đ&aacute;ng tin cậy 100%. Mặc d&ugrave; ch&uacute;ng t&ocirc;i cố gắng bảo vệ th&ocirc;ng tin c&aacute; nh&acirc;n của bạn nhưng ch&uacute;ng t&ocirc;i kh&ocirc;ng thể đảm bảo hoặc đảm bảo t&iacute;nh bảo mật của bất kỳ th&ocirc;ng tin n&agrave;o bạn gửi cho ch&uacute;ng t&ocirc;i hoặc từ c&aacute;c dịch vụ của ch&uacute;ng t&ocirc;i. v&agrave; bạn phải tự chịu rủi ro n&agrave;y.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Li&ecirc;n kết đến c&aacute;c trang web kh&aacute;c</strong></p>\r\n\r\n<p>Trang web của ch&uacute;ng t&ocirc;i c&oacute; thể chứa c&aacute;c li&ecirc;n kết đến c&aacute;c trang web kh&aacute;c kh&ocirc;ng do ch&uacute;ng t&ocirc;i điều h&agrave;nh. Nếu bạn nhấp v&agrave;o li&ecirc;n kết của b&ecirc;n thứ ba, bạn sẽ được chuyển hướng đến trang web của b&ecirc;n thứ ba đ&oacute;. Ch&uacute;ng t&ocirc;i khuy&ecirc;n bạn n&ecirc;n xem lại Ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư của mọi trang web bạn truy cập v&igrave; ch&uacute;ng t&ocirc;i kh&ocirc;ng c&oacute; quyền kiểm so&aacute;t hoặc chịu tr&aacute;ch nhiệm đối với c&aacute;c hoạt động hoặc nội dung về quyền ri&ecirc;ng tư của c&aacute;c trang web hoặc dịch vụ của b&ecirc;n thứ ba. .</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Thay đổi ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư</strong></p>\r\n\r\n<p>Đ&ocirc;i khi, ch&uacute;ng t&ocirc;i c&oacute; thể cập nhật Ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư n&agrave;y m&agrave; kh&ocirc;ng cần th&ocirc;ng b&aacute;o trước. Mọi thay đổi sẽ được đăng l&ecirc;n trang n&agrave;y v&agrave; được &aacute;p dụng ngay sau khi ch&uacute;ng được đăng. Bằng việc tiếp tục sử dụng dịch vụ của ch&uacute;ng t&ocirc;i sau khi những thay đổi n&agrave;y được đăng, bạn đồng &yacute; với những thay đổi đ&oacute;.</p>\r\n'),
(33, 'contact', '<h3 style=\"text-align:center\">Li&ecirc;n hệ với ch&uacute;ng t&ocirc;i qua c&aacute;c th&ocirc;ng tin b&ecirc;n tr&ecirc;n !</h3>\r\n'),
(34, 'notification_home', '<div class=\"notice_banner\">\r\n<p style=\"text-align:center\">&nbsp;</p>\r\n\r\n<p style=\"text-align:center\">&nbsp;</p>\r\n\r\n<p style=\"text-align:center\"><strong>►&nbsp;Vui l&ograve;ng đọc lưu &yacute; trước khi chạy.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style=\"text-align:center\">&nbsp;</p>\r\n</div>\r\n'),
(35, 'discount_ctv', '0'),
(36, 'discount_daily', '20'),
(37, 'discount_npp', '15'),
(38, 'discount_tongkho', '20'),
(39, 'notification_note', '<p style=\"text-align:center\"><span style=\"color:#e74c3c\"><em><strong><span style=\"font-size:18px\">Vui l&ograve;ng đọc lưu &yacute; trước khi sử dụng&nbsp;</span></strong></em></span></p>\r\n\r\n<p style=\"text-align:center\">&nbsp;</p>\r\n\r\n<p><strong>1.&nbsp;Giới Hạn Số Lượng Link Extra Cho Mỗi Acc:</strong><br />\r\n<a href=\"https://coolsymbol.com/copy/Heavy_Black_Curved_Downwards_and_Rightwards_Arrow_Symbol_%E2%9E%A5\" id=\"emoji-info-url\">➥</a>&nbsp;Mỗi t&agrave;i khoản game chỉ chạy được từ 45&nbsp;- 120&nbsp;link Extra (t&ugrave;y thuộc v&agrave;o từng t&agrave;i khoản). Đ&atilde; đạt đến giới hạn n&agrave;y th&igrave; kh&ocirc;ng chạy&nbsp;th&ecirc;m được&nbsp;nữa.<br />\r\n<a href=\"https://coolsymbol.com/copy/Heavy_Black_Curved_Downwards_and_Rightwards_Arrow_Symbol_%E2%9E%A5\" id=\"emoji-info-url\">➥</a>&nbsp;Từ mốc 45 - 120 game cho dừng ở mốc n&agrave;o l&agrave; dừng. Ch&uacute;ng t&ocirc;i kh&ocirc;ng chịu tr&aacute;ch nhiệm nếu t&agrave;i khoản bạn bị giới hạn chạy link.<br />\r\n<strong>2.&nbsp;Kiếm Tra Trước Khi Chạy:</strong><br />\r\n<a href=\"https://coolsymbol.com/copy/Heavy_Black_Curved_Downwards_and_Rightwards_Arrow_Symbol_%E2%9E%A5\" id=\"emoji-info-url\">➥</a>&nbsp;Xo&aacute; bớt bạn b&egrave; ở game để dưới 130/180, kh&ocirc;ng n&ecirc;n để qu&aacute; 150/180. Ch&uacute;ng t&ocirc;i kh&ocirc;ng chịu tr&aacute;ch nhiệm nếu bạn cố t&igrave;nh để bạn b&egrave; tr&ecirc;n 150 trước khi chạy.<br />\r\n<a href=\"https://coolsymbol.com/copy/Heavy_Black_Curved_Downwards_and_Rightwards_Arrow_Symbol_%E2%9E%A5\" id=\"emoji-info-url\">➥</a>&nbsp;Mỗi link chỉ dùng được 3 lần, tương ứng l&ecirc;n được 3 đơn, muốn chạy tiếp hãy vào game l&acirc;́y link mới.</p>\r\n\r\n<p style=\"text-align:center\">&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n'),
(40, 'status_bot_telegram', 'off'),
(41, 'telegram_chat_id', ''),
(42, 'telegram_token', ''),
(43, 'notice_bank', '<ul>\r\n	<li>\r\n	<p><span style=\"font-size:14px\"><a href=\"https://coolsymbol.com/copy/Heavy_Black_Curved_Downwards_and_Rightwards_Arrow_Symbol_%E2%9E%A5\" id=\"emoji-info-url\">➥</a>Vui l&ograve;ng&nbsp;nhập đ&uacute;ng nội dung&nbsp;chuyển khoản&nbsp;để hệ thống xử cộng tiền v&agrave;o t&agrave;i khoản bạn tự động trong v&ograve;ng 1 ph&uacute;t.</span></p>\r\n\r\n	<p><span style=\"font-size:14px\"><a href=\"https://coolsymbol.com/copy/Heavy_Black_Curved_Downwards_and_Rightwards_Arrow_Symbol_%E2%9E%A5\" id=\"emoji-info-url\">➥</a>Nạp tối thiểu 1.000đ. D&ugrave;ng bao nhi&ecirc;u nạp bấy nhi&ecirc;u.</span></p>\r\n\r\n	<p><span style=\"font-size:14px\"><a href=\"https://coolsymbol.com/copy/Heavy_Black_Curved_Downwards_and_Rightwards_Arrow_Symbol_%E2%9E%A5\" id=\"emoji-info-url\">➥</a>Tiền đ&atilde; nạp kh&ocirc;ng ho&agrave;n ngược về stk với mọi h&igrave;nh thức.</span></p>\r\n	</li>\r\n</ul>\r\n'),
(44, 'prefix_autobank', 'muaspincm'),
(45, 'check_time_cron_bank', '1748689510'),
(46, 'bank_min', '1000'),
(47, 'bank_max', '1000000000'),
(50, 'notice_napthe', '<ul>\r\n	<li><span style=\"font-size:16px\">Vui l&ograve;ng nhập đ&uacute;ng seri v&agrave; mệnh gi&aacute; thẻ, tiền sẽ được cộng trong v&agrave;i gi&acirc;y.</span></li>\r\n	<li><span style=\"font-size:16px\">Sai mệnh gi&aacute; sẽ mất thẻ n&ecirc;n c&aacute;c bạn lưu &yacute; gi&uacute;p m&igrave;nh nh&eacute;.</span></li>\r\n	<li><span style=\"font-size:16px\">Chiết khấu nạp thẻ l&agrave; 20% tức nạp 100k nhận 80k.</span></li>\r\n</ul>\r\n'),
(51, 'discount_napthe', '20'),
(52, 'status_card', 'on'),
(53, 'partner_id_card', ''),
(54, 'partner_key_card', ''),
(63, 'status_bank', 'on'),
(67, 'status_api_muaspin', 'on'),
(68, 'token_api_muaspin', '79851805e6ce8baf8eb2'),
(69, 'money_api_muaspin', '30000'),
(73, 'fanpage_link', '');

-- --------------------------------------------------------

--
-- Table structure for table `translates`
--

CREATE TABLE `translates` (
  `id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` longtext NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `telegram_chat_id` text DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime DEFAULT NULL,
  `money` float NOT NULL DEFAULT 0,
  `total_money` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `ip` text DEFAULT NULL,
  `device` text NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `status_ban` varchar(25) NOT NULL DEFAULT 'off',
  `token_forgot_password` varchar(255) DEFAULT NULL,
  `admin` varchar(25) NOT NULL DEFAULT 'off',
  `level` varchar(50) NOT NULL DEFAULT 'user',
  `attempt_login` int(11) NOT NULL DEFAULT 0,
  `utm_source` varchar(255) NOT NULL DEFAULT 'web',
  `session_time` int(11) NOT NULL DEFAULT 0,
  `request_time` int(11) NOT NULL DEFAULT 0,
  `forgot_password_time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `phone_number`, `telegram_chat_id`, `avatar`, `created_time`, `updated_time`, `money`, `total_money`, `discount`, `ip`, `device`, `token`, `status_ban`, `token_forgot_password`, `admin`, `level`, `attempt_login`, `utm_source`, `session_time`, `request_time`, `forgot_password_time`) VALUES
(1, 'adminnguyen', '61da5f54252d53a8cfa7173c21cbb093', '', NULL, NULL, NULL, NULL, '2025-05-30 15:03:07', '2025-05-31 18:08:00', 10000, 10000, 0, '1', '', 'SoupWZwTehyq063QLmHlBcd2risG58kJUOgYaEVR7Nnt91C4fXxbPMzjKvIAD1748592187dd3c746ab97114a2a6bfda1b191f1a68', 'off', NULL, 'on', 'user', 0, 'web', 1748690020, 1748689680, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_flow`
--
ALTER TABLE `cash_flow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `config_api`
--
ALTER TABLE `config_api`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ip_block_log`
--
ALTER TABLE `ip_block_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `payment_bank`
--
ALTER TABLE `payment_bank`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tid` (`tid`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `translates`
--
ALTER TABLE `translates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_flow`
--
ALTER TABLE `cash_flow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `config_api`
--
ALTER TABLE `config_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ip_block_log`
--
ALTER TABLE `ip_block_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_bank`
--
ALTER TABLE `payment_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `translates`
--
ALTER TABLE `translates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cash_flow`
--
ALTER TABLE `cash_flow`
  ADD CONSTRAINT `cash_flow_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `payment_bank`
--
ALTER TABLE `payment_bank`
  ADD CONSTRAINT `payment_bank_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
