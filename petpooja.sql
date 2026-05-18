-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 05:31 PM
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
-- Database: `petpooja`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `area_slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `area_name`, `area_slug`, `created_at`, `updated_at`) VALUES
(4, 'Ahmedabad', 'ahmedabad', '2025-01-08 05:16:45', '2025-01-08 05:16:45'),
(5, 'Chandkheda', 'chandkheda', '2025-01-09 06:51:01', '2025-01-09 06:51:01'),
(6, 'Prahladnagar', 'prahladnagar', '2025-01-10 23:50:03', '2025-01-10 23:50:03'),
(7, 'Shahibaug', 'shahibaug', '2025-01-16 08:47:47', '2025-01-16 08:47:47');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `text`, `author`, `created_at`, `updated_at`) VALUES
(3, 'Article test', 'Test content', 'John Doe', '2025-01-27 04:06:58', '2025-01-27 04:06:58'),
(4, 'Hello World', 'hello world', 'Mukesh Bhavsar', '2025-01-27 04:07:15', '2025-01-27 04:07:15');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Navrangpura', 'Darpan char rasta, Navrangpura', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:5:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:15:\"Create category\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:13:\"Edit Category\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"Create Menu\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:9:\"Edit Menu\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:2;}}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"Edit Roles\";s:1:\"c\";s:5:\"admin\";}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:5:\"admin\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:6:\"Waiter\";s:1:\"c\";s:5:\"admin\";}}}', 1779195875);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `order_list` int(3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `order_list`, `created_at`, `updated_at`) VALUES
(187, 'Dosa', 'dosa', 'lunch.webp', 3, '2025-01-11 05:06:23', '2025-01-11 05:06:23'),
(188, 'Biriyani', 'biriyani', 'dinner.jpg', 4, '2025-01-11 05:06:33', '2025-01-11 05:06:33'),
(189, 'Desserts', 'desserts', 'dessert.jpg', 5, '2025-01-11 05:06:42', '2025-01-11 05:06:43'),
(197, 'Popular', 'popular', 'popular.png', 1, '2025-01-11 05:06:42', '2025-01-11 05:06:43'),
(198, 'Roti', 'roti', 'roti.jpg', 2, '2026-05-08 00:55:40', '2026-05-08 00:55:40'),
(199, 'Poha', 'poha', 'breakfast_1778221540.jpg', 2, '2026-05-08 00:55:40', '2026-05-08 00:55:40'),
(200, 'Tea', 'tea', 'tea.jpg', 2, '2026-05-08 00:55:40', '2026-05-08 00:55:40');

-- --------------------------------------------------------

--
-- Table structure for table `category_menu`
--

CREATE TABLE `category_menu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_menu`
--

INSERT INTO `category_menu` (`id`, `menu_id`, `category_id`) VALUES
(5, 30, 188),
(6, 31, 188),
(7, 28, 189),
(16, 40, 198),
(17, 41, 200),
(18, 42, 198),
(19, 43, 187),
(20, 44, 187),
(21, 45, 187),
(22, 46, 187);

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE `configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`id`, `name`, `logo`, `email`, `phone`, `address`, `theme`, `created_at`, `updated_at`) VALUES
(2, 'South Diaries', 'South Diaries.png', 'southdiaries@gmail.com', '09538135005', '7, Surayansh Vedura, TP 44, Chandkheda, Chandkheda', NULL, '2026-05-18 08:05:26', '2026-05-18 08:05:26');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'United States', 'US', NULL, NULL),
(2, 'Canada', 'CA', NULL, NULL),
(3, 'Afghanistan', 'AF', NULL, NULL),
(4, 'Albania', 'AL', NULL, NULL),
(5, 'Algeria', 'DZ', NULL, NULL),
(6, 'American Samoa', 'AS', NULL, NULL),
(7, 'Andorra', 'AD', NULL, NULL),
(8, 'Angola', 'AO', NULL, NULL),
(9, 'Anguilla', 'AI', NULL, NULL),
(10, 'Antarctica', 'AQ', NULL, NULL),
(11, 'Antigua and/or Barbuda', 'AG', NULL, NULL),
(12, 'Argentina', 'AR', NULL, NULL),
(13, 'Armenia', 'AM', NULL, NULL),
(14, 'Aruba', 'AW', NULL, NULL),
(15, 'Australia', 'AU', NULL, NULL),
(16, 'Austria', 'AT', NULL, NULL),
(17, 'Azerbaijan', 'AZ', NULL, NULL),
(18, 'Bahamas', 'BS', NULL, NULL),
(19, 'Bahrain', 'BH', NULL, NULL),
(20, 'Bangladesh', 'BD', NULL, NULL),
(21, 'Barbados', 'BB', NULL, NULL),
(22, 'Belarus', 'BY', NULL, NULL),
(23, 'Belgium', 'BE', NULL, NULL),
(24, 'Belize', 'BZ', NULL, NULL),
(25, 'Benin', 'BJ', NULL, NULL),
(26, 'Bermuda', 'BM', NULL, NULL),
(27, 'Bhutan', 'BT', NULL, NULL),
(28, 'Bolivia', 'BO', NULL, NULL),
(29, 'Bosnia and Herzegovina', 'BA', NULL, NULL),
(30, 'Botswana', 'BW', NULL, NULL),
(31, 'Bouvet Island', 'BV', NULL, NULL),
(32, 'Brazil', 'BR', NULL, NULL),
(33, 'British lndian Ocean Territory', 'IO', NULL, NULL),
(34, 'Brunei Darussalam', 'BN', NULL, NULL),
(35, 'Bulgaria', 'BG', NULL, NULL),
(36, 'Burkina Faso', 'BF', NULL, NULL),
(37, 'Burundi', 'BI', NULL, NULL),
(38, 'Cambodia', 'KH', NULL, NULL),
(39, 'Cameroon', 'CM', NULL, NULL),
(40, 'Cape Verde', 'CV', NULL, NULL),
(41, 'Cayman Islands', 'KY', NULL, NULL),
(42, 'Central African Republic', 'CF', NULL, NULL),
(43, 'Chad', 'TD', NULL, NULL),
(44, 'Chile', 'CL', NULL, NULL),
(45, 'China', 'CN', NULL, NULL),
(46, 'Christmas Island', 'CX', NULL, NULL),
(47, 'Cocos (Keeling) Islands', 'CC', NULL, NULL),
(48, 'Colombia', 'CO', NULL, NULL),
(49, 'Comoros', 'KM', NULL, NULL),
(50, 'Congo', 'CG', NULL, NULL),
(51, 'Cook Islands', 'CK', NULL, NULL),
(52, 'Costa Rica', 'CR', NULL, NULL),
(53, 'Croatia (Hrvatska)', 'HR', NULL, NULL),
(54, 'Cuba', 'CU', NULL, NULL),
(55, 'Cyprus', 'CY', NULL, NULL),
(56, 'Czech Republic', 'CZ', NULL, NULL),
(57, 'Democratic Republic of Congo', 'CD', NULL, NULL),
(58, 'Denmark', 'DK', NULL, NULL),
(59, 'Djibouti', 'DJ', NULL, NULL),
(60, 'Dominica', 'DM', NULL, NULL),
(61, 'Dominican Republic', 'DO', NULL, NULL),
(62, 'East Timor', 'TP', NULL, NULL),
(63, 'Ecudaor', 'EC', NULL, NULL),
(64, 'Egypt', 'EG', NULL, NULL),
(65, 'El Salvador', 'SV', NULL, NULL),
(66, 'Equatorial Guinea', 'GQ', NULL, NULL),
(67, 'Eritrea', 'ER', NULL, NULL),
(68, 'Estonia', 'EE', NULL, NULL),
(69, 'Ethiopia', 'ET', NULL, NULL),
(70, 'Falkland Islands (Malvinas)', 'FK', NULL, NULL),
(71, 'Faroe Islands', 'FO', NULL, NULL),
(72, 'Fiji', 'FJ', NULL, NULL),
(73, 'Finland', 'FI', NULL, NULL),
(74, 'France', 'FR', NULL, NULL),
(75, 'France, Metropolitan', 'FX', NULL, NULL),
(76, 'French Guiana', 'GF', NULL, NULL),
(77, 'French Polynesia', 'PF', NULL, NULL),
(78, 'French Southern Territories', 'TF', NULL, NULL),
(79, 'Gabon', 'GA', NULL, NULL),
(80, 'Gambia', 'GM', NULL, NULL),
(81, 'Georgia', 'GE', NULL, NULL),
(82, 'Germany', 'DE', NULL, NULL),
(83, 'Ghana', 'GH', NULL, NULL),
(84, 'Gibraltar', 'GI', NULL, NULL),
(85, 'Greece', 'GR', NULL, NULL),
(86, 'Greenland', 'GL', NULL, NULL),
(87, 'Grenada', 'GD', NULL, NULL),
(88, 'Guadeloupe', 'GP', NULL, NULL),
(89, 'Guam', 'GU', NULL, NULL),
(90, 'Guatemala', 'GT', NULL, NULL),
(91, 'Guinea', 'GN', NULL, NULL),
(92, 'Guinea-Bissau', 'GW', NULL, NULL),
(93, 'Guyana', 'GY', NULL, NULL),
(94, 'Haiti', 'HT', NULL, NULL),
(95, 'Heard and Mc Donald Islands', 'HM', NULL, NULL),
(96, 'Honduras', 'HN', NULL, NULL),
(97, 'Hong Kong', 'HK', NULL, NULL),
(98, 'Hungary', 'HU', NULL, NULL),
(99, 'Iceland', 'IS', NULL, NULL),
(100, 'India', 'IN', NULL, NULL),
(101, 'Indonesia', 'ID', NULL, NULL),
(102, 'Iran (Islamic Republic of)', 'IR', NULL, NULL),
(103, 'Iraq', 'IQ', NULL, NULL),
(104, 'Ireland', 'IE', NULL, NULL),
(105, 'Israel', 'IL', NULL, NULL),
(106, 'Italy', 'IT', NULL, NULL),
(107, 'Ivory Coast', 'CI', NULL, NULL),
(108, 'Jamaica', 'JM', NULL, NULL),
(109, 'Japan', 'JP', NULL, NULL),
(110, 'Jordan', 'JO', NULL, NULL),
(111, 'Kazakhstan', 'KZ', NULL, NULL),
(112, 'Kenya', 'KE', NULL, NULL),
(113, 'Kiribati', 'KI', NULL, NULL),
(114, 'Korea, Democratic People\'s Republic of', 'KP', NULL, NULL),
(115, 'Korea, Republic of', 'KR', NULL, NULL),
(116, 'Kuwait', 'KW', NULL, NULL),
(117, 'Kyrgyzstan', 'KG', NULL, NULL),
(118, 'Lao People\'s Democratic Republic', 'LA', NULL, NULL),
(119, 'Latvia', 'LV', NULL, NULL),
(120, 'Lebanon', 'LB', NULL, NULL),
(121, 'Lesotho', 'LS', NULL, NULL),
(122, 'Liberia', 'LR', NULL, NULL),
(123, 'Libyan Arab Jamahiriya', 'LY', NULL, NULL),
(124, 'Liechtenstein', 'LI', NULL, NULL),
(125, 'Lithuania', 'LT', NULL, NULL),
(126, 'Luxembourg', 'LU', NULL, NULL),
(127, 'Macau', 'MO', NULL, NULL),
(128, 'Macedonia', 'MK', NULL, NULL),
(129, 'Madagascar', 'MG', NULL, NULL),
(130, 'Malawi', 'MW', NULL, NULL),
(131, 'Malaysia', 'MY', NULL, NULL),
(132, 'Maldives', 'MV', NULL, NULL),
(133, 'Mali', 'ML', NULL, NULL),
(134, 'Malta', 'MT', NULL, NULL),
(135, 'Marshall Islands', 'MH', NULL, NULL),
(136, 'Martinique', 'MQ', NULL, NULL),
(137, 'Mauritania', 'MR', NULL, NULL),
(138, 'Mauritius', 'MU', NULL, NULL),
(139, 'Mayotte', 'TY', NULL, NULL),
(140, 'Mexico', 'MX', NULL, NULL),
(141, 'Micronesia, Federated States of', 'FM', NULL, NULL),
(142, 'Moldova, Republic of', 'MD', NULL, NULL),
(143, 'Monaco', 'MC', NULL, NULL),
(144, 'Mongolia', 'MN', NULL, NULL),
(145, 'Montserrat', 'MS', NULL, NULL),
(146, 'Morocco', 'MA', NULL, NULL),
(147, 'Mozambique', 'MZ', NULL, NULL),
(148, 'Myanmar', 'MM', NULL, NULL),
(149, 'Namibia', 'NA', NULL, NULL),
(150, 'Nauru', 'NR', NULL, NULL),
(151, 'Nepal', 'NP', NULL, NULL),
(152, 'Netherlands', 'NL', NULL, NULL),
(153, 'Netherlands Antilles', 'AN', NULL, NULL),
(154, 'New Caledonia', 'NC', NULL, NULL),
(155, 'New Zealand', 'NZ', NULL, NULL),
(156, 'Nicaragua', 'NI', NULL, NULL),
(157, 'Niger', 'NE', NULL, NULL),
(158, 'Nigeria', 'NG', NULL, NULL),
(159, 'Niue', 'NU', NULL, NULL),
(160, 'Norfork Island', 'NF', NULL, NULL),
(161, 'Northern Mariana Islands', 'MP', NULL, NULL),
(162, 'Norway', 'NO', NULL, NULL),
(163, 'Oman', 'OM', NULL, NULL),
(164, 'Pakistan', 'PK', NULL, NULL),
(165, 'Palau', 'PW', NULL, NULL),
(166, 'Panama', 'PA', NULL, NULL),
(167, 'Papua New Guinea', 'PG', NULL, NULL),
(168, 'Paraguay', 'PY', NULL, NULL),
(169, 'Peru', 'PE', NULL, NULL),
(170, 'Philippines', 'PH', NULL, NULL),
(171, 'Pitcairn', 'PN', NULL, NULL),
(172, 'Poland', 'PL', NULL, NULL),
(173, 'Portugal', 'PT', NULL, NULL),
(174, 'Puerto Rico', 'PR', NULL, NULL),
(175, 'Qatar', 'QA', NULL, NULL),
(176, 'Republic of South Sudan', 'SS', NULL, NULL),
(177, 'Reunion', 'RE', NULL, NULL),
(178, 'Romania', 'RO', NULL, NULL),
(179, 'Russian Federation', 'RU', NULL, NULL),
(180, 'Rwanda', 'RW', NULL, NULL),
(181, 'Saint Kitts and Nevis', 'KN', NULL, NULL),
(182, 'Saint Lucia', 'LC', NULL, NULL),
(183, 'Saint Vincent and the Grenadines', 'VC', NULL, NULL),
(184, 'Samoa', 'WS', NULL, NULL),
(185, 'San Marino', 'SM', NULL, NULL),
(186, 'Sao Tome and Principe', 'ST', NULL, NULL),
(187, 'Saudi Arabia', 'SA', NULL, NULL),
(188, 'Senegal', 'SN', NULL, NULL),
(189, 'Serbia', 'RS', NULL, NULL),
(190, 'Seychelles', 'SC', NULL, NULL),
(191, 'Sierra Leone', 'SL', NULL, NULL),
(192, 'Singapore', 'SG', NULL, NULL),
(193, 'Slovakia', 'SK', NULL, NULL),
(194, 'Slovenia', 'SI', NULL, NULL),
(195, 'Solomon Islands', 'SB', NULL, NULL),
(196, 'Somalia', 'SO', NULL, NULL),
(197, 'South Africa', 'ZA', NULL, NULL),
(198, 'South Georgia South Sandwich Islands', 'GS', NULL, NULL),
(199, 'Spain', 'ES', NULL, NULL),
(200, 'Sri Lanka', 'LK', NULL, NULL),
(201, 'St. Helena', 'SH', NULL, NULL),
(202, 'St. Pierre and Miquelon', 'PM', NULL, NULL),
(203, 'Sudan', 'SD', NULL, NULL),
(204, 'Suriname', 'SR', NULL, NULL),
(205, 'Svalbarn and Jan Mayen Islands', 'SJ', NULL, NULL),
(206, 'Swaziland', 'SZ', NULL, NULL),
(207, 'Sweden', 'SE', NULL, NULL),
(208, 'Switzerland', 'CH', NULL, NULL),
(209, 'Syrian Arab Republic', 'SY', NULL, NULL),
(210, 'Taiwan', 'TW', NULL, NULL),
(211, 'Tajikistan', 'TJ', NULL, NULL),
(212, 'Tanzania, United Republic of', 'TZ', NULL, NULL),
(213, 'Thailand', 'TH', NULL, NULL),
(214, 'Togo', 'TG', NULL, NULL),
(215, 'Tokelau', 'TK', NULL, NULL),
(216, 'Tonga', 'TO', NULL, NULL),
(217, 'Trinidad and Tobago', 'TT', NULL, NULL),
(218, 'Tunisia', 'TN', NULL, NULL),
(219, 'Turkey', 'TR', NULL, NULL),
(220, 'Turkmenistan', 'TM', NULL, NULL),
(221, 'Turks and Caicos Islands', 'TC', NULL, NULL),
(222, 'Tuvalu', 'TV', NULL, NULL),
(223, 'Uganda', 'UG', NULL, NULL),
(224, 'Ukraine', 'UA', NULL, NULL),
(225, 'United Arab Emirates', 'AE', NULL, NULL),
(226, 'United Kingdom', 'GB', NULL, NULL),
(227, 'United States minor outlying islands', 'UM', NULL, NULL),
(228, 'Uruguay', 'UY', NULL, NULL),
(229, 'Uzbekistan', 'UZ', NULL, NULL),
(230, 'Vanuatu', 'VU', NULL, NULL),
(231, 'Vatican City State', 'VA', NULL, NULL),
(232, 'Venezuela', 'VE', NULL, NULL),
(233, 'Vietnam', 'VN', NULL, NULL),
(234, 'Virgin Islands (British)', 'VG', NULL, NULL),
(235, 'Virgin Islands (U.S.)', 'VI', NULL, NULL),
(236, 'Wallis and Futuna Islands', 'WF', NULL, NULL),
(237, 'Western Sahara', 'EH', NULL, NULL),
(238, 'Yemen', 'YE', NULL, NULL),
(239, 'Yugoslavia', 'YU', NULL, NULL),
(240, 'Zaire', 'ZR', NULL, NULL),
(241, 'Zambia', 'ZM', NULL, NULL),
(242, 'Zimbabwe', 'ZW', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_addresses`
--

INSERT INTO `customer_addresses` (`id`, `user_id`, `name`, `mobile`, `email`, `country_id`, `address`, `created_at`, `updated_at`) VALUES
(1, 3, 'Dhruv', '09978835005', 'dhruvbhavsar210@gmail.com', 100, 'Keerthi Royal Palms, Keerthi Royal Palms, Keerthi Royal Palms, Keerthi Royal Palms,', '2023-11-25 04:10:25', '2023-12-29 06:13:59');

-- --------------------------------------------------------

--
-- Table structure for table `dining_carts`
--

CREATE TABLE `dining_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_coupons`
--

CREATE TABLE `discount_coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `max_uses` varchar(255) DEFAULT NULL,
  `max_uses_user` varchar(255) DEFAULT NULL,
  `type` enum('percent','fixed') NOT NULL DEFAULT 'fixed',
  `discount_amount` double(10,2) NOT NULL,
  `min_amount` double(10,2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discount_coupons`
--

INSERT INTO `discount_coupons` (`id`, `code`, `name`, `description`, `max_uses`, `max_uses_user`, `type`, `discount_amount`, `min_amount`, `status`, `starts_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(4, 'IND30', 'Dummy name', 'Hello1', '10', '3', 'percent', 10.00, 15.00, 1, '2023-11-27 11:36:57', '2024-12-30 18:30:59', '2023-11-28 06:07:01', '2024-12-28 01:43:53'),
(8, 'MIRADA99', 'Avinash Marriage', 'Avinash Marrriage on Discount', '1000', '1', 'fixed', 99.00, 300.00, 1, '2024-11-21 05:16:45', '2024-12-30 18:30:53', '2024-11-20 23:46:58', '2024-12-28 01:42:20'),
(9, 'ENDYEAR99', 'Year End Offer', 'Year End Sale', '1000', '1', 'fixed', 99.00, 300.00, 1, '2024-12-28 07:10:51', '2024-12-30 18:30:53', '2024-12-28 01:41:08', '2024-12-28 01:43:22');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `photo`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Android smartphone with a 6.5', 'Android smartphone with a 6.5-inch display, octa-core processor, 4GB of RAM, 64GB storage (expandable), a triple rear camera setup (13MP main, 2MP depth, 2MP macro), an approximate 8MP front camera.', 'http://example.com/storage/app/public/products/phone.jpeg', 698.88, NULL, NULL),
(2, 'Digital Camera EOS', 'Canon cameras come in various models with diverse features, but generally, they offer high-quality imaging, a range of resolutions, interchangeable lenses, advanced autofocus systems.', 'http://example.com/storage/app/public/products/canon.jpg', 983.00, NULL, NULL),
(3, 'LOIS CARON Watch', 'The Lois Caron watch typically features a stainless steel case, quartz movement, analog display, synthetic leather or metal strap, and water resistance at varying depths.', 'http://example.com/storage/app/public/products/watch.jpg', 675.00, NULL, NULL),
(4, 'Elegante unisex adult square', 'Sunglasses come in a wide variety of styles, but they generally feature UV-protective lenses housed in plastic or metal frames.', 'http://example.com/storage/app/public/products/sunclass.jpg', 159.99, NULL, NULL),
(5, 'Large Capacity Folding Bag', 'A typical travel bag is designed with durable materials, multiple compartments, sturdy handles, and often includes wheels for easy maneuverability.', 'http://example.com/storage/app/public/products/bag.jpg', 68.00, NULL, NULL),
(6, 'Lenovo Smartchoice Ideapad 3', 'Lenovo laptops typically offer various configurations with features such as Intel or AMD processors.', 'http://example.com/storage/app/public/products/laptop.jpg', 129.99, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `veg_nonveg` enum('Veg','Non-veg','Egg','NA') NOT NULL DEFAULT 'NA',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `slug`, `veg_nonveg`, `created_at`, `updated_at`) VALUES
(28, 'Ice cream', 'ice-cream', 'Veg', '2026-04-30 06:53:29', '2026-04-30 06:53:29'),
(30, 'Veg Biriyani', 'veg-biriyani', 'Veg', '2026-04-30 06:57:32', '2026-04-30 06:57:32'),
(31, 'Mutton Biriyani', 'mutton-biriyani', 'Non-veg', '2026-04-30 06:58:32', '2026-04-30 06:58:32'),
(33, 'Dosa', 'dosa', 'Veg', '2026-05-07 06:42:40', '2026-05-07 06:42:44'),
(35, 'Poha', 'poha', 'Veg', '2026-05-08 00:10:27', '2026-05-08 00:10:27'),
(36, 'Poha', 'poha', 'Veg', '2026-05-08 00:46:48', '2026-05-08 00:46:49'),
(40, 'Rumali Roti', 'rumali_roti', 'Veg', '2026-05-08 06:49:10', '2026-05-08 06:49:10'),
(41, 'Lemon Tea', 'lemon-tea', 'NA', '2026-05-09 00:33:11', '2026-05-09 00:33:14'),
(42, 'Butter Roti', 'butter-roti', 'Veg', '2026-05-09 00:44:39', '2026-05-09 00:44:39'),
(43, 'Masala Dosa', 'masala-dosa', 'Veg', '2026-05-09 00:48:03', '2026-05-09 00:48:04'),
(44, 'Plain Dosa', 'plain-dosa', 'Veg', '2026-05-09 00:48:32', '2026-05-09 00:48:32'),
(45, 'Mysore Masala Dosa', 'mysore-masala-dosa', 'NA', '2026-05-09 00:49:14', '2026-05-09 00:49:14'),
(46, 'Spring Dosa', 'spring-dosa', 'NA', '2026-05-09 01:04:36', '2026-05-09 01:04:36');

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_11_18_051106_alter_users_table', 2),
(6, '2023_11_20_052052_create_categories_table', 3),
(7, '2023_11_20_091142_create_temp_images_table', 4),
(8, '2023_11_20_123339_create_sub_categories_table', 5),
(9, '2023_11_21_045811_create_brands_table', 6),
(10, '2023_11_21_063746_create_products_table', 7),
(11, '2023_11_21_063811_create_product_images_table', 7),
(12, '2023_11_23_101727_alter_categories_table', 8),
(13, '2023_11_23_102759_alter_products_table', 9),
(14, '2023_11_23_103442_alter_sub_categories_table', 10),
(15, '2023_11_24_064315_alter_products_table', 11),
(16, '2023_11_25_072939_create_countries_table', 12),
(17, '2023_11_25_075119_create_orders_table', 13),
(18, '2023_11_25_075155_create_orders_items_table', 13),
(19, '2023_11_25_075250_create_customer_addresses_table', 13),
(20, '2023_11_25_135444_create_shipping_charges_table', 14),
(21, '2023_11_28_090521_create_discount_coupons_table', 15),
(22, '2023_11_28_091637_create_discount_coupons_table', 16),
(23, '2023_11_28_091724_create_discount_coupons_table', 17),
(24, '2023_11_28_092025_create_discount_coupons_table', 18),
(25, '2023_11_28_092115_create_discount_coupons_table', 19),
(26, '2023_11_28_092301_create_discount_coupons_table', 20),
(27, '2023_11_29_084104_alter_orders_table', 21),
(28, '2023_11_29_104758_alter_orders_table', 22),
(29, '2023_11_30_051729_create_wishlists_table', 23),
(30, '2023_12_01_060717_alter_users_table', 24),
(31, '2023_12_01_072404_create_pages_table', 25),
(32, '2023_12_02_111056_create_product_ratings_table', 26),
(33, '2023_12_29_074318_create_payments_table', 27),
(34, '2025_01_08_135229_create_menu_categories_table', 28),
(35, '2025_01_09_053549_create_products_table', 28),
(36, '2025_01_09_054428_create_menus_table', 29),
(37, '2025_01_09_070304_create_areas_table', 30),
(38, '2025_01_09_082552_create_tables_table', 31),
(39, '2025_01_10_065444_create_branches_table', 32),
(40, '2025_01_10_065526_create_settings_table', 33),
(41, '2025_01_10_101525_alter_users_table', 34),
(42, '2025_01_15_103703_create_sessions_table', 35),
(43, '2025_01_16_053512_create_items_table', 36),
(44, '2025_01_17_105545_create_cart_table', 37),
(45, '2025_01_18_070223_create_dining_carts_table', 38),
(46, '2025_01_20_123902_create_order_items_table', 38),
(47, '2025_01_22_061205_create_products_table', 39),
(48, '2025_01_22_082454_create_products_table', 40),
(49, '2025_01_22_091941_create_product_images_table', 41),
(50, '2025_01_26_122245_create_product_views_table', 42),
(51, '2025_01_26_130415_create_user_roles_table', 43),
(52, '2025_01_28_115518_create_orders_table', 44),
(53, '2025_01_29_123429_create_taxes_table', 45),
(54, '2025_02_01_071017_create_permission_tables', 46),
(55, '2026_04_29_064706_add_product_id_to_orders_table', 47),
(56, '2026_04_29_122843_add_product_id_to_order_items_table', 48),
(57, '2026_05_07_120813_create_variants_table', 49),
(58, '2026_05_08_055754_create_category_menu_table', 50),
(59, '2026_05_18_132433_create_configurations_table', 51);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_type` enum('Dinein','Takeaway','Delivery') NOT NULL DEFAULT 'Dinein',
  `session_id` varchar(255) DEFAULT NULL,
  `seat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dinein_time` varchar(10) DEFAULT NULL,
  `customer_name` varchar(50) DEFAULT NULL,
  `customer_phone` varchar(10) DEFAULT NULL,
  `customer_email` varchar(50) DEFAULT NULL,
  `ready_time` varchar(255) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `gst` int(5) NOT NULL DEFAULT 18,
  `sgst` int(5) NOT NULL DEFAULT 9,
  `cgst` int(5) NOT NULL DEFAULT 9,
  `total_amount` double(10,2) DEFAULT NULL,
  `payment` enum('paid','not paid') NOT NULL DEFAULT 'not paid',
  `status` enum('running','pending','shipped','delivered') NOT NULL DEFAULT 'running',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_type`, `session_id`, `seat_id`, `dinein_time`, `customer_name`, `customer_phone`, `customer_email`, `ready_time`, `delivery_address`, `notes`, `gst`, `sgst`, `cgst`, `total_amount`, `payment`, `status`, `created_at`, `updated_at`) VALUES
(56, 'Dinein', '4657319048', 42, '10', NULL, NULL, NULL, 'When Ready', NULL, 'test', 18, 9, 9, 610.00, 'not paid', 'running', '2026-05-16 07:11:24', '2026-05-16 07:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  `total` double(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(102, 56, 28, 'Poha', 2, 55.00, 110.00, '2026-05-16 07:11:24', '2026-05-16 07:11:24'),
(103, 56, 12, 'Veg Biriyani', 1, 500.00, 500.00, '2026-05-16 07:11:24', '2026-05-16 07:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `content`, `created_at`, `updated_at`) VALUES
(2, 'About us', 'about-us', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p><p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\"><br></span><br></p>', '2023-12-01 03:33:50', '2023-12-01 03:33:50'),
(3, 'Contact', 'contact-us', '<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content.</p>\r\n                    <address>\r\n                    Mukesh Bhavsar<br>\r\n                    711-2880 Nulla St.<br>\r\n                    Mankato Mississippi 96522<br>\r\n                    <a href=\"tel:+xxxxxxxx\">(XXX) 555-2368</a><br>\r\n                    <a href=\"mailto:jim@rock.com\">jim@rock.com</a>\r\n                    </address>', '2023-12-01 03:44:47', '2024-11-20 23:54:11'),
(4, 'Terms', 'terms', '<p>terms</p>', '2023-12-27 08:59:35', '2023-12-27 08:59:35');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_id`, `product_name`, `quantity`, `amount`, `currency`, `customer_name`, `customer_email`, `payment_status`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 'pay_NI6tbuUjO3Y0NK', 'Laptop', '1', '2', 'INR', 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 'captured', 'Razorpay', '2023-12-29 03:53:31', '2023-12-29 03:53:31'),
(2, 'pay_NI6vRRqAA8QuSo', 'Laptop', '1', '2', 'INR', 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 'captured', 'Razorpay', '2023-12-29 03:55:15', '2023-12-29 03:55:15'),
(3, 'pay_NI9CBn83DXvVCo', 'Laptop', '1', '20', 'INR', 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 'captured', 'Razorpay', '2023-12-29 06:08:32', '2023-12-29 06:08:32'),
(4, 'pay_NI9RXepNn0DvuX', 'Laptop', '1', '20', 'INR', 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 'captured', 'Razorpay', '2023-12-29 06:23:02', '2023-12-29 06:23:02'),
(5, 'pay_NI9TDm9Ol5VNP1', 'Laptop', '1', '20', 'INR', 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 'captured', 'Razorpay', '2023-12-29 06:24:36', '2023-12-29 06:24:36'),
(6, 'pay_NIAFj17qtlJHrk', 'Laptop', '1', '50', 'INR', 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 'captured', 'Razorpay', '2023-12-29 07:10:33', '2023-12-29 07:10:33'),
(7, 'pay_PNpg6ofAw6MYWY', 'Laptop', '1', '20', 'INR', 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 'captured', 'Razorpay', '2024-11-20 23:33:11', '2024-11-20 23:33:11'),
(8, 'pay_Pio71yak0XsJ6l', 'Laptop', '1', '20', 'INR', 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 'captured', 'Razorpay', '2025-01-12 23:40:26', '2025-01-12 23:40:26'),
(9, 'pay_Pio8eoC2jpmUdJ', 'Laptop', '1', '20', 'INR', 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 'captured', 'Razorpay', '2025-01-12 23:41:53', '2025-01-12 23:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `guard_name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Create category', 'admin', '2025-02-01 02:20:28', '2025-02-01 02:20:28'),
(2, 'Edit Category', 'admin', '2025-02-01 02:20:48', '2025-02-01 02:20:48'),
(3, 'Create Menu', 'admin', '2025-02-01 02:24:34', '2025-02-01 02:24:34'),
(4, 'Edit Menu', 'admin', '2025-02-01 03:30:41', '2025-02-01 03:30:41'),
(5, 'Edit Roles', 'admin', '2025-02-01 03:30:41', '2025-02-01 03:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` double(10,2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `category_id`, `menu_id`, `product_id`, `description`, `price`, `status`, `created_at`, `updated_at`) VALUES
(11, 'Mutton Biriyani', 'mutton-biriyani', 188, 31, NULL, 'test', 500.00, 1, '2026-04-30 07:57:36', '2026-04-30 07:57:36'),
(12, 'Veg Biriyani', 'veg-biriyani', 188, 30, NULL, 'test', 500.00, 1, '2026-04-30 07:57:36', '2026-04-30 07:57:36'),
(15, 'Plain Dosa', 'plain-dosa', 187, 44, NULL, 'South indian dosa', 0.00, 1, '2026-05-07 06:43:34', '2026-05-07 06:43:34'),
(16, 'Masala Dosa', 'masala-dosa', 187, 43, NULL, 'South indian dosa', 0.00, 1, '2026-05-07 06:43:34', '2026-05-07 06:43:34'),
(22, 'Roti', 'roti', 198, 40, NULL, 'best roti', NULL, 1, '2026-05-08 08:45:30', '2026-05-08 08:45:30'),
(24, 'Lemon Tea', 'lemon-tea', 200, 41, NULL, 'best', 55.00, 1, '2026-05-09 00:33:56', '2026-05-09 00:33:56'),
(25, 'Masala Tea', 'masala-tea', 200, NULL, NULL, 'awesome', 30.00, 1, '2026-05-09 00:35:35', '2026-05-09 00:35:35'),
(26, 'Butter Roti', 'butter-roti', 198, 42, NULL, 'butter roti', 45.00, 1, '2026-05-09 00:45:08', '2026-05-09 00:45:08'),
(28, 'Poha', 'poha', 197, NULL, NULL, 'Awesome Poha', 55.00, 1, '2026-05-09 08:38:41', '2026-05-09 08:38:41'),
(29, 'Mysore Masala Dosa', 'mysore-masala-dosa', 187, 45, NULL, 'Awesome Mysore Masala Dosa', 380.00, 1, '2026-05-09 08:40:41', '2026-05-09 08:40:41');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `created_at`, `updated_at`) VALUES
(14, 11, '11-Mutton Biriyani-14.jpg', '2026-04-30 07:57:36', '2026-04-30 07:57:36'),
(15, 11, '11-Mutton Biriyani-15.jpg', '2026-04-30 07:57:36', '2026-04-30 07:57:37'),
(16, 11, '11-Mutton Biriyani-16.jpg', '2026-04-30 07:57:37', '2026-04-30 07:57:37'),
(17, 11, '11-Mutton Biriyani-17.jpg', '2026-04-30 07:57:37', '2026-04-30 07:57:37'),
(18, 12, '11-Mutton Biriyani-17.jpg', '2026-04-30 07:57:37', '2026-04-30 07:57:37'),
(19, 12, '11-Mutton Biriyani-17.jpg', '2026-04-30 07:57:37', '2026-04-30 07:57:37'),
(20, 15, '15-Masala Dosa-20.jpg', '2026-05-07 06:43:34', '2026-05-07 06:43:34'),
(21, 16, '15-Masala Dosa-20.jpg', '2026-05-07 06:43:34', '2026-05-07 06:43:34'),
(34, 22, '22-Roti-34.jpg', '2026-05-08 08:45:31', '2026-05-08 08:45:31'),
(35, 22, '22-Roti-35.jpg', '2026-05-08 08:45:31', '2026-05-08 08:45:31'),
(36, 22, '22-Roti-36.jpg', '2026-05-08 08:45:31', '2026-05-08 08:45:31'),
(37, 22, '22-Roti-37.jpg', '2026-05-08 08:45:31', '2026-05-08 08:45:31'),
(38, 24, '24-Lemon Tea-38.jpg', '2026-05-09 00:33:56', '2026-05-09 00:33:56'),
(39, 25, '25-Masala Tea-39.jpg', '2026-05-09 00:35:35', '2026-05-09 00:35:35'),
(40, 26, '26-Butter Roti-40.jpg', '2026-05-09 00:45:08', '2026-05-09 00:45:08'),
(41, 28, '28-Poha-41.jpg', '2026-05-09 08:38:41', '2026-05-09 08:38:41'),
(42, 29, '29-Mysore Masala Dosa-42.jpg', '2026-05-09 08:40:41', '2026-05-09 08:40:41');

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `rating` double(3,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_views`
--

CREATE TABLE `product_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'table_view',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_views`
--

INSERT INTO `product_views` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'grid_view', '2025-01-26 06:54:44', '2025-01-26 06:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', '2025-02-01 02:37:43', '2025-02-01 03:11:20'),
(2, 'Waiter', 'admin', '2025-02-01 03:37:47', '2025-02-01 03:37:47'),
(3, 'Waiter 3', 'web', '2026-05-18 07:34:34', '2026-05-18 07:34:34');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(3, 2),
(4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `table_slug` varchar(100) NOT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('available','running','pending','shipped','delivered') NOT NULL DEFAULT 'available',
  `capacity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `table_name`, `table_slug`, `area_id`, `status`, `capacity`, `created_at`, `updated_at`) VALUES
(3, 'Table_03', 'table_03', 5, 'running', NULL, NULL, NULL),
(25, 'Table 02', 'table-02', 6, 'shipped', 2, '2025-01-20 23:03:37', '2025-01-20 23:03:37'),
(42, 'Table 1', 'table-1', 5, 'running', 2, '2025-01-28 05:39:44', '2025-01-28 05:39:44'),
(43, 'Table 2', 'table-2', 6, 'running', 2, '2025-01-28 05:41:47', '2025-01-28 05:41:47'),
(44, 'Table 3', 'table-3', 5, 'pending', 6, '2025-01-28 05:44:55', '2025-01-28 05:44:55'),
(45, 'Table 4', 'table-4', NULL, 'delivered', 2, '2025-01-28 06:02:48', '2025-01-28 06:02:48'),
(46, 'Table 5', 'table-5', NULL, 'running', 10, '2025-01-29 08:53:56', '2025-01-29 08:53:56'),
(47, 'Table 9999Mukesh', 'table-9999mukesh', 7, 'available', 2, '2025-01-30 05:44:11', '2025-01-30 05:44:11'),
(48, 'Table 6', 'table-6', NULL, 'available', 8, '2025-01-30 05:48:20', '2025-01-30 05:48:20'),
(49, 'Table 1', 'table-1', NULL, 'available', NULL, '2026-05-18 09:50:18', '2026-05-18 09:50:18'),
(50, 'Table 1', 'table-1', 4, 'available', NULL, '2026-05-18 09:53:35', '2026-05-18 09:53:35'),
(51, 'Table 2', 'table-2', 4, 'available', NULL, '2026-05-18 09:55:04', '2026-05-18 09:55:04');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_name` varchar(255) NOT NULL,
  `restaurant_email` varchar(255) NOT NULL,
  `restaurant_phone` varchar(255) NOT NULL,
  `restaurant_logo` varchar(255) NOT NULL,
  `restaurant_theme` varchar(255) NOT NULL,
  `restaurant_address` text NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `restaurant_name`, `restaurant_email`, `restaurant_phone`, `restaurant_logo`, `restaurant_theme`, `restaurant_address`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 'Lemon Tree', 'info@lemontree.com', '089-26546576', 'logo', '#ff0000', 'Navrangpura', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_charges`
--

CREATE TABLE `shipping_charges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` varchar(255) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_charges`
--

INSERT INTO `shipping_charges` (`id`, `country_id`, `amount`, `created_at`, `updated_at`) VALUES
(13, '100', 50.00, '2023-11-28 00:49:44', '2024-11-20 23:41:30'),
(14, '1', 1000.00, '2023-11-28 00:50:12', '2023-11-28 00:50:12'),
(16, '2', 50.00, '2023-11-28 02:47:32', '2023-11-28 02:47:32'),
(17, 'rest_of_world', 100.00, '2023-11-28 03:11:04', '2023-11-28 03:11:04'),
(18, '8', 80.00, '2024-12-28 01:38:55', '2024-12-28 01:38:55');

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gst` int(11) NOT NULL,
  `sgst` int(11) NOT NULL,
  `cgst` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `gst`, `sgst`, `cgst`, `created_at`, `updated_at`) VALUES
(1, 18, 9, 9, '2025-01-29 12:36:59', '2025-01-29 12:36:59');

-- --------------------------------------------------------

--
-- Table structure for table `temp_images`
--

CREATE TABLE `temp_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `temp_images`
--

INSERT INTO `temp_images` (`id`, `name`, `created_at`, `updated_at`) VALUES
(176, '1709984216.JPG', '2024-03-09 06:06:56', '2024-03-09 06:06:56'),
(177, '1732165529.jpg', '2024-11-20 23:35:29', '2024-11-20 23:35:29'),
(178, '1732165690.jpg', '2024-11-20 23:38:10', '2024-11-20 23:38:10'),
(179, '1736075481.jpg', '2025-01-05 05:41:21', '2025-01-05 05:41:21'),
(180, '1736075963.jpg', '2025-01-05 05:49:23', '2025-01-05 05:49:23'),
(181, '1736075996.jpg', '2025-01-05 05:49:56', '2025-01-05 05:49:56'),
(182, '1736076505.jpg', '2025-01-05 05:58:25', '2025-01-05 05:58:25'),
(183, '1736076783.jpg', '2025-01-05 06:03:03', '2025-01-05 06:03:03'),
(184, '1736077695.jpg', '2025-01-05 06:18:15', '2025-01-05 06:18:15'),
(185, '1736077820.jpg', '2025-01-05 06:20:20', '2025-01-05 06:20:20'),
(186, '1736077992.jpg', '2025-01-05 06:23:12', '2025-01-05 06:23:12'),
(187, '1736078246.jpg', '2025-01-05 06:27:26', '2025-01-05 06:27:26'),
(188, '1736078517.jpg', '2025-01-05 06:31:57', '2025-01-05 06:31:57'),
(189, '1736078551.jpg', '2025-01-05 06:32:31', '2025-01-05 06:32:31'),
(190, '1736078690.jpg', '2025-01-05 06:34:50', '2025-01-05 06:34:50'),
(191, '1736078719.jpg', '2025-01-05 06:35:19', '2025-01-05 06:35:19'),
(192, '1736078764.jpg', '2025-01-05 06:36:04', '2025-01-05 06:36:04'),
(193, '1736078778.jpg', '2025-01-05 06:36:18', '2025-01-05 06:36:18'),
(194, '1736078788.jpg', '2025-01-05 06:36:28', '2025-01-05 06:36:28'),
(195, '1736078799.jpg', '2025-01-05 06:36:39', '2025-01-05 06:36:39'),
(196, '1736078856.jpg', '2025-01-05 06:37:36', '2025-01-05 06:37:36'),
(197, '1736078895.jpg', '2025-01-05 06:38:15', '2025-01-05 06:38:15'),
(198, '1736079031.jpg', '2025-01-05 06:40:31', '2025-01-05 06:40:31'),
(199, '1736143742.jpg', '2025-01-06 00:39:02', '2025-01-06 00:39:02'),
(200, '1736143775.jpg', '2025-01-06 00:39:35', '2025-01-06 00:39:35'),
(201, '1736143887.jpg', '2025-01-06 00:41:27', '2025-01-06 00:41:27'),
(202, '1736143971.jpg', '2025-01-06 00:42:51', '2025-01-06 00:42:51'),
(203, '1736144037.jpg', '2025-01-06 00:43:57', '2025-01-06 00:43:57'),
(204, '1736144107.jpg', '2025-01-06 00:45:07', '2025-01-06 00:45:07'),
(205, '1736144227.jpg', '2025-01-06 00:47:07', '2025-01-06 00:47:07'),
(206, '1736144550.jpg', '2025-01-06 00:52:30', '2025-01-06 00:52:30'),
(207, '1736144578.jpg', '2025-01-06 00:52:58', '2025-01-06 00:52:58'),
(208, '1736144601.jpg', '2025-01-06 00:53:21', '2025-01-06 00:53:21'),
(209, '1736144811.jpg', '2025-01-06 00:56:51', '2025-01-06 00:56:51'),
(210, '1736144899.jpg', '2025-01-06 00:58:19', '2025-01-06 00:58:19'),
(211, '1736144931.jpg', '2025-01-06 00:58:51', '2025-01-06 00:58:51'),
(212, '1736145300.jpg', '2025-01-06 01:05:00', '2025-01-06 01:05:00'),
(213, '1736145344.jpg', '2025-01-06 01:05:44', '2025-01-06 01:05:44'),
(214, '1736145406.jpg', '2025-01-06 01:06:46', '2025-01-06 01:06:46'),
(215, '1736145716.jpg', '2025-01-06 01:11:56', '2025-01-06 01:11:56'),
(216, '1736145787.jpg', '2025-01-06 01:13:07', '2025-01-06 01:13:07'),
(217, '1736145877.jpg', '2025-01-06 01:14:37', '2025-01-06 01:14:37'),
(218, '1736146075.jpg', '2025-01-06 01:17:55', '2025-01-06 01:17:55'),
(219, '1736146213.jpg', '2025-01-06 01:20:13', '2025-01-06 01:20:13'),
(220, '1736146327.jpg', '2025-01-06 01:22:07', '2025-01-06 01:22:07'),
(221, '1736146327.jpg', '2025-01-06 01:22:07', '2025-01-06 01:22:07'),
(222, '1736146327.jpg', '2025-01-06 01:22:07', '2025-01-06 01:22:07'),
(223, '1736146328.jpg', '2025-01-06 01:22:08', '2025-01-06 01:22:08'),
(224, '1736320282.jpg', '2025-01-08 01:41:22', '2025-01-08 01:41:22'),
(225, '1736320340.jpg', '2025-01-08 01:42:20', '2025-01-08 01:42:20'),
(226, '1736346358.JPG', '2025-01-08 08:55:58', '2025-01-08 08:55:58'),
(227, '1736346459.JPG', '2025-01-08 08:57:39', '2025-01-08 08:57:39'),
(228, '1736346507.JPG', '2025-01-08 08:58:27', '2025-01-08 08:58:27'),
(229, '1736399654.jpg', '2025-01-08 23:44:14', '2025-01-08 23:44:14'),
(230, '1736400046.jpg', '2025-01-08 23:50:46', '2025-01-08 23:50:46'),
(231, '1736402299.jpg', '2025-01-09 00:28:19', '2025-01-09 00:28:19'),
(232, '1736402447.jpg', '2025-01-09 00:30:47', '2025-01-09 00:30:47'),
(233, '1736405653.jpg', '2025-01-09 01:24:13', '2025-01-09 01:24:13'),
(234, '1736405691.jpg', '2025-01-09 01:24:51', '2025-01-09 01:24:51'),
(235, '1736572692.jpg', '2025-01-10 23:48:12', '2025-01-10 23:48:12'),
(236, '1736572738.jpg', '2025-01-10 23:48:58', '2025-01-10 23:48:58'),
(237, '1736579823.png', '2025-01-11 01:47:03', '2025-01-11 01:47:03'),
(238, '1736579949.png', '2025-01-11 01:49:09', '2025-01-11 01:49:09'),
(239, '1736580721.png', '2025-01-11 02:02:01', '2025-01-11 02:02:01'),
(240, '1736580773.png', '2025-01-11 02:02:53', '2025-01-11 02:02:53'),
(241, '1736580789.png', '2025-01-11 02:03:09', '2025-01-11 02:03:09'),
(242, '1736581324.png', '2025-01-11 02:12:04', '2025-01-11 02:12:04'),
(243, '1736591138.png', '2025-01-11 04:55:38', '2025-01-11 04:55:38'),
(244, '1736591193.png', '2025-01-11 04:56:33', '2025-01-11 04:56:33'),
(245, '1736591286.png', '2025-01-11 04:58:06', '2025-01-11 04:58:06'),
(246, '1736591438.png', '2025-01-11 05:00:38', '2025-01-11 05:00:38'),
(247, '1736591531.png', '2025-01-11 05:02:11', '2025-01-11 05:02:11'),
(248, '1736591782.png', '2025-01-11 05:06:22', '2025-01-11 05:06:22'),
(249, '1736591791.png', '2025-01-11 05:06:31', '2025-01-11 05:06:31'),
(250, '1736591801.png', '2025-01-11 05:06:41', '2025-01-11 05:06:41'),
(251, '1736591810.png', '2025-01-11 05:06:50', '2025-01-11 05:06:50'),
(252, '1736591888.png', '2025-01-11 05:08:08', '2025-01-11 05:08:08'),
(253, '1736591900.png', '2025-01-11 05:08:20', '2025-01-11 05:08:20'),
(254, '1736591913.png', '2025-01-11 05:08:33', '2025-01-11 05:08:33'),
(255, '1736591969.jpg', '2025-01-11 05:09:29', '2025-01-11 05:09:29'),
(256, '1736592154.jpg', '2025-01-11 05:12:34', '2025-01-11 05:12:34'),
(257, '1736592293.jpg', '2025-01-11 05:14:53', '2025-01-11 05:14:53'),
(258, '1736596123.jpg', '2025-01-11 06:18:43', '2025-01-11 06:18:43'),
(259, '1736600188.jpg', '2025-01-11 07:26:28', '2025-01-11 07:26:28'),
(260, '1737036258.jpg', '2025-01-16 08:34:18', '2025-01-16 08:34:18'),
(261, '1737036344.jpg', '2025-01-16 08:35:44', '2025-01-16 08:35:44'),
(262, '1737036382.jpg', '2025-01-16 08:36:22', '2025-01-16 08:36:22'),
(263, '1737036434.jpg', '2025-01-16 08:37:14', '2025-01-16 08:37:14'),
(264, '1737036473.jpg', '2025-01-16 08:37:53', '2025-01-16 08:37:53'),
(265, '1737036691.jpg', '2025-01-16 08:41:31', '2025-01-16 08:41:31'),
(266, '1737036824.jpg', '2025-01-16 08:43:44', '2025-01-16 08:43:44'),
(267, '1737528604.png', '2025-01-22 01:20:04', '2025-01-22 01:20:04'),
(268, '1737528943.jpg', '2025-01-22 01:25:43', '2025-01-22 01:25:43'),
(269, '1737533193.jpg', '2025-01-22 02:36:33', '2025-01-22 02:36:33'),
(270, '1737533270.jpg', '2025-01-22 02:37:50', '2025-01-22 02:37:50'),
(271, '1737533350.jpg', '2025-01-22 02:39:10', '2025-01-22 02:39:10'),
(272, '1737533471.jpg', '2025-01-22 02:41:11', '2025-01-22 02:41:11'),
(273, '1737533683.jpg', '2025-01-22 02:44:43', '2025-01-22 02:44:43'),
(274, '1737533719.jpg', '2025-01-22 02:45:19', '2025-01-22 02:45:19'),
(275, '1737537070.jpg', '2025-01-22 03:41:10', '2025-01-22 03:41:10'),
(276, '1737537131.jpg', '2025-01-22 03:42:11', '2025-01-22 03:42:11'),
(277, '1737537192.png', '2025-01-22 03:43:12', '2025-01-22 03:43:12'),
(278, '1737537346.jpg', '2025-01-22 03:45:46', '2025-01-22 03:45:46'),
(279, '1737537694.jpg', '2025-01-22 03:51:34', '2025-01-22 03:51:34'),
(280, '1737541956.jpg', '2025-01-22 05:02:36', '2025-01-22 05:02:36'),
(281, '1737541996.jpg', '2025-01-22 05:03:16', '2025-01-22 05:03:16'),
(282, '1737542040.jpg', '2025-01-22 05:04:00', '2025-01-22 05:04:00'),
(283, '1737542210.png', '2025-01-22 05:06:50', '2025-01-22 05:06:50'),
(284, '1777554548.jpg', '2026-04-30 07:39:08', '2026-04-30 07:39:08'),
(285, '1777554685.jpg', '2026-04-30 07:41:25', '2026-04-30 07:41:25'),
(286, '1777554734.jpg', '2026-04-30 07:42:14', '2026-04-30 07:42:14'),
(287, '1777554734.jpg', '2026-04-30 07:42:14', '2026-04-30 07:42:14'),
(288, '1777554735.jpg', '2026-04-30 07:42:15', '2026-04-30 07:42:15'),
(289, '1777554813.jpg', '2026-04-30 07:43:33', '2026-04-30 07:43:33'),
(290, '1777554813.jpg', '2026-04-30 07:43:33', '2026-04-30 07:43:33'),
(291, '1777554813.jpg', '2026-04-30 07:43:33', '2026-04-30 07:43:33'),
(292, '1777554837.jpg', '2026-04-30 07:43:57', '2026-04-30 07:43:57'),
(293, '1777554837.jpg', '2026-04-30 07:43:57', '2026-04-30 07:43:57'),
(294, '1777554838.jpg', '2026-04-30 07:43:58', '2026-04-30 07:43:58'),
(295, '1777554907.jpg', '2026-04-30 07:45:07', '2026-04-30 07:45:07'),
(296, '1777554907.jpg', '2026-04-30 07:45:07', '2026-04-30 07:45:07'),
(297, '1777554907.jpg', '2026-04-30 07:45:07', '2026-04-30 07:45:07'),
(298, '1777554934.jpg', '2026-04-30 07:45:34', '2026-04-30 07:45:34'),
(299, '1777554934.jpg', '2026-04-30 07:45:34', '2026-04-30 07:45:34'),
(300, '1777554934.jpg', '2026-04-30 07:45:34', '2026-04-30 07:45:34'),
(301, '1777554972.jpg', '2026-04-30 07:46:12', '2026-04-30 07:46:12'),
(302, '1777554972.jpg', '2026-04-30 07:46:12', '2026-04-30 07:46:12'),
(303, '1777554973.jpg', '2026-04-30 07:46:13', '2026-04-30 07:46:13'),
(304, '1777555116.jpg', '2026-04-30 07:48:36', '2026-04-30 07:48:36'),
(305, '1777555116.jpg', '2026-04-30 07:48:36', '2026-04-30 07:48:36'),
(306, '1777555116.jpg', '2026-04-30 07:48:36', '2026-04-30 07:48:36'),
(307, '1777555117.jpg', '2026-04-30 07:48:37', '2026-04-30 07:48:37'),
(308, '1777555197.jpg', '2026-04-30 07:49:57', '2026-04-30 07:49:57'),
(309, '1777555197.jpg', '2026-04-30 07:49:57', '2026-04-30 07:49:57'),
(310, '1777555198.jpg', '2026-04-30 07:49:58', '2026-04-30 07:49:58'),
(311, '1777555198.jpg', '2026-04-30 07:49:58', '2026-04-30 07:49:58'),
(312, '1777555225.jpg', '2026-04-30 07:50:25', '2026-04-30 07:50:25'),
(313, '1777555226.jpg', '2026-04-30 07:50:26', '2026-04-30 07:50:26'),
(314, '1777555226.jpg', '2026-04-30 07:50:26', '2026-04-30 07:50:26'),
(315, '1777555226.jpg', '2026-04-30 07:50:26', '2026-04-30 07:50:26'),
(316, '1777555249.jpg', '2026-04-30 07:50:49', '2026-04-30 07:50:49'),
(317, '1777555249.jpg', '2026-04-30 07:50:49', '2026-04-30 07:50:49'),
(318, '1777555249.jpg', '2026-04-30 07:50:49', '2026-04-30 07:50:49'),
(319, '1777555250.jpg', '2026-04-30 07:50:50', '2026-04-30 07:50:50'),
(320, '1777555540.jpg', '2026-04-30 07:55:40', '2026-04-30 07:55:40'),
(321, '1777555540.jpg', '2026-04-30 07:55:40', '2026-04-30 07:55:40'),
(322, '1777555541.jpg', '2026-04-30 07:55:41', '2026-04-30 07:55:41'),
(323, '1777555541.jpg', '2026-04-30 07:55:41', '2026-04-30 07:55:41'),
(324, '1777555654.jpg', '2026-04-30 07:57:34', '2026-04-30 07:57:34'),
(325, '1777555654.jpg', '2026-04-30 07:57:34', '2026-04-30 07:57:34'),
(326, '1777555655.jpg', '2026-04-30 07:57:35', '2026-04-30 07:57:35'),
(327, '1777555655.jpg', '2026-04-30 07:57:35', '2026-04-30 07:57:35'),
(328, '1778156010.jpg', '2026-05-07 06:43:30', '2026-05-07 06:43:30'),
(329, '1778242841.jpg', '2026-05-08 06:50:41', '2026-05-08 06:50:41'),
(330, '1778242841.jpg', '2026-05-08 06:50:41', '2026-05-08 06:50:41'),
(331, '1778242842.jpg', '2026-05-08 06:50:42', '2026-05-08 06:50:42'),
(332, '1778242842.jpg', '2026-05-08 06:50:42', '2026-05-08 06:50:42'),
(333, '1778244660.jpg', '2026-05-08 07:21:00', '2026-05-08 07:21:00'),
(334, '1778244661.jpg', '2026-05-08 07:21:01', '2026-05-08 07:21:01'),
(335, '1778244661.jpg', '2026-05-08 07:21:01', '2026-05-08 07:21:01'),
(336, '1778244661.jpg', '2026-05-08 07:21:01', '2026-05-08 07:21:01'),
(337, '1778244760.jpg', '2026-05-08 07:22:40', '2026-05-08 07:22:40'),
(338, '1778244760.jpg', '2026-05-08 07:22:40', '2026-05-08 07:22:40'),
(339, '1778244761.jpg', '2026-05-08 07:22:41', '2026-05-08 07:22:41'),
(340, '1778244761.jpg', '2026-05-08 07:22:41', '2026-05-08 07:22:41'),
(341, '1778244853.jpg', '2026-05-08 07:24:13', '2026-05-08 07:24:13'),
(342, '1778244854.jpg', '2026-05-08 07:24:14', '2026-05-08 07:24:14'),
(343, '1778244854.jpg', '2026-05-08 07:24:14', '2026-05-08 07:24:14'),
(344, '1778244854.jpg', '2026-05-08 07:24:14', '2026-05-08 07:24:14'),
(345, '1778249573.jpg', '2026-05-08 08:42:53', '2026-05-08 08:42:53'),
(346, '1778249573.jpg', '2026-05-08 08:42:53', '2026-05-08 08:42:53'),
(347, '1778249574.jpg', '2026-05-08 08:42:54', '2026-05-08 08:42:54'),
(348, '1778249574.jpg', '2026-05-08 08:42:54', '2026-05-08 08:42:54'),
(349, '1778249597.jpg', '2026-05-08 08:43:17', '2026-05-08 08:43:17'),
(350, '1778249615.jpg', '2026-05-08 08:43:35', '2026-05-08 08:43:35'),
(351, '1778249618.jpg', '2026-05-08 08:43:38', '2026-05-08 08:43:38'),
(352, '1778249622.jpg', '2026-05-08 08:43:42', '2026-05-08 08:43:42'),
(353, '1778249626.jpg', '2026-05-08 08:43:46', '2026-05-08 08:43:46'),
(354, '1778249722.jpg', '2026-05-08 08:45:22', '2026-05-08 08:45:22'),
(355, '1778249724.jpg', '2026-05-08 08:45:24', '2026-05-08 08:45:24'),
(356, '1778249727.jpg', '2026-05-08 08:45:27', '2026-05-08 08:45:27'),
(357, '1778249729.jpg', '2026-05-08 08:45:29', '2026-05-08 08:45:29'),
(358, '1778306635.jpg', '2026-05-09 00:33:55', '2026-05-09 00:33:55'),
(359, '1778306687.jpg', '2026-05-09 00:34:47', '2026-05-09 00:34:47'),
(360, '1778306729.jpg', '2026-05-09 00:35:29', '2026-05-09 00:35:29'),
(361, '1778307306.jpg', '2026-05-09 00:45:06', '2026-05-09 00:45:06'),
(362, '1778335719.jpg', '2026-05-09 08:38:39', '2026-05-09 08:38:39'),
(363, '1778335838.jpg', '2026-05-09 08:40:38', '2026-05-09 08:40:38');

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` int(11) DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `restaurant_name` varchar(255) DEFAULT NULL,
  `restaurant_email` varchar(255) DEFAULT NULL,
  `restaurant_phone` varchar(255) DEFAULT NULL,
  `restaurant_logo` varchar(255) DEFAULT NULL,
  `restaurant_theme` varchar(255) DEFAULT NULL,
  `restaurant_address` varchar(255) DEFAULT NULL,
  `branch_name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `branch_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`branch_address`)),
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `status`, `email_verified_at`, `password`, `restaurant_name`, `restaurant_email`, `restaurant_phone`, `restaurant_logo`, `restaurant_theme`, `restaurant_address`, `branch_name`, `branch_address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', 2, 1, NULL, 'admin123', 'kanjipuram', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Priyanka', 'p.bhavsar2610@gmail.com', 2, 1, NULL, '$2y$10$0mtiGc/VLwgP6N/pAcy1geZ6WX4S/X1fojexw8cif.OTxOgux9Uma', 'Lemon Tree', 'mukeshbhavsar210@gmail.com', '09978835005', '3-.png', NULL, 'Keerthi Royal Palms,', NULL, NULL, NULL, '2023-11-25 00:32:42', '2025-01-10 23:55:26'),
(7, 'Mukesh Bhavsar', 'mukeshbhavsar210@gmail.com', 2, 1, NULL, '$2y$12$Iy5Wh1TVAkCYAvaefrR71OEKD4QDjhnnWBxknqjwnioSSM6sAJMnO', 'Lemon Tree', 'dhruvbhavsar210@gmail.com', '121', '7-Lemon Tree444.png', NULL, 'Navrangpura Praga', NULL, NULL, NULL, '2023-12-19 07:11:37', '2025-01-10 07:27:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `role_status` varchar(255) NOT NULL,
  `show_options` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variants`
--

INSERT INTO `variants` (`id`, `product_id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, 15, 'Oil', 190.00, NULL, NULL),
(2, 15, 'Butter', 390.00, NULL, NULL),
(3, 16, 'Oil', 190.00, NULL, NULL),
(4, 16, 'Butter', 290.00, NULL, NULL),
(9, 22, 'Oil', 40.00, '2026-05-08 08:45:31', '2026-05-08 08:45:31'),
(10, 22, 'Butter', 50.00, '2026-05-08 08:45:31', '2026-05-08 08:45:31');

-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE `views` (
  `id` int(11) NOT NULL,
  `views` enum('Table','Grid') NOT NULL DEFAULT 'Table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_menu`
--
ALTER TABLE `category_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_menu_menu_id_foreign` (`menu_id`),
  ADD KEY `category_menu_category_id_foreign` (`category_id`);

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_addresses_user_id_foreign` (`user_id`),
  ADD KEY `customer_addresses_country_id_foreign` (`country_id`);

--
-- Indexes for table `dining_carts`
--
ALTER TABLE `dining_carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_seat_id_foreign` (`seat_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_menu_id_foreign` (`menu_id`),
  ADD KEY `products_product_image_id_foreign` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_ratings_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_views`
--
ALTER TABLE `product_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tables_area_id_foreign` (`area_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `shipping_charges`
--
ALTER TABLE `shipping_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_images`
--
ALTER TABLE `temp_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_user_id_foreign` (`user_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `category_menu`
--
ALTER TABLE `category_menu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dining_carts`
--
ALTER TABLE `dining_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_views`
--
ALTER TABLE `product_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipping_charges`
--
ALTER TABLE `shipping_charges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temp_images`
--
ALTER TABLE `temp_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=364;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_menu`
--
ALTER TABLE `category_menu`
  ADD CONSTRAINT `category_menu_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_menu_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `customer_addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_product_image_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product_images` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `tables_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variants`
--
ALTER TABLE `variants`
  ADD CONSTRAINT `variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
