-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 14, 2026 at 06:57 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `market_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Biscuit Cake', 'biscuit-cake', '2026-02-25 13:47:46', '2026-02-25 13:47:46'),
(2, 'Drink', 'drink', '2026-02-25 13:50:37', '2026-02-25 13:50:37');

-- --------------------------------------------------------

--
-- Table structure for table `category_product`
--

CREATE TABLE `category_product` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_product`
--

INSERT INTO `category_product` (`id`, `category_id`, `product_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_02_05_083422_add_is_admin_to_users_table', 1),
(6, '2026_02_05_084319_create_products_table', 1),
(7, '2026_02_05_113624_create_categories_table', 1),
(8, '2026_02_05_113724_create_category_product_table', 1),
(9, '2026_02_24_000001_add_inventory_to_products_table', 1),
(10, '2026_02_24_000002_add_views_to_products_table', 1),
(11, '2026_02_24_000003_add_product_indexes', 1),
(12, '2026_02_24_000004_create_reviews_table', 1),
(13, '2026_02_24_000005_create_order_requests_table', 1),
(14, '2026_02_24_000006_add_is_approved_to_reviews_table', 1),
(15, '2026_02_24_000007_add_indexes_to_reviews_table', 1),
(16, '2026_02_24_000008_add_indexes_to_order_requests_table', 1),
(17, '2026_02_25_000009_add_admin_seen_at_to_notifications_tables', 1),
(18, '2026_02_25_000010_create_traffic_visits_table', 2),
(19, '2026_02_25_000011_create_search_keywords_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `order_requests`
--

CREATE TABLE `order_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `admin_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_requests`
--

INSERT INTO `order_requests` (`id`, `product_id`, `name`, `phone`, `quantity`, `note`, `status`, `admin_seen_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'hassan', '07509039191', 12, 'quickly pls', 'new', '2026-02-28 18:47:54', '2026-02-27 12:20:07', '2026-02-28 18:47:54'),
(2, 1, 'hassan', '07509039191', 12, 'quickly pls', 'new', '2026-02-28 18:47:54', '2026-02-27 12:20:12', '2026-02-28 18:47:54'),
(3, 2, 'ahmad', '07509039194', 16, 'i want so come', 'new', '2026-02-28 18:47:54', '2026-02-27 12:24:29', '2026-02-28 18:47:54'),
(4, 1, 'omer', '7504444444', 20, NULL, 'new', '2026-02-28 18:47:54', '2026-02-28 18:31:40', '2026-02-28 18:47:54'),
(5, 2, 'omer', '7504444444', 10, 'i will send money by fib pls', 'new', '2026-02-28 18:47:54', '2026-02-28 18:36:08', '2026-02-28 18:47:54');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
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
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int UNSIGNED NOT NULL DEFAULT '0',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `views` bigint UNSIGNED NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `is_available`, `views`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'biscream', '250.00', 200, 1, 8, 'Soft, luscious cream blended with golden biscuit layers for a dessert that’s both light and irresistibly satisfying.', 'products/c1ad3340-f5ff-4bb7-9a5c-9981e7ea5d32.webp', '2026-02-25 13:46:42', '2026-02-28 18:31:49'),
(2, 'vitamilk', '1500.00', 150, 1, 4, 'C Light is a refreshing, crisp drink with a smooth and balanced taste.\r\nLight and easy to enjoy, it’s perfect for cooling down anytime', 'products/514fba1c-6198-4f79-b13f-2f56b8b0fcb1.webp', '2026-02-25 13:50:17', '2026-03-06 12:02:33');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '1',
  `admin_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_keywords`
--

CREATE TABLE `search_keywords` (
  `id` bigint UNSIGNED NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` bigint UNSIGNED NOT NULL DEFAULT '0',
  `last_searched_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `search_keywords`
--

INSERT INTO `search_keywords` (`id`, `keyword`, `count`, `last_searched_at`, `created_at`, `updated_at`) VALUES
(1, 'biscream', 1, '2026-02-25 14:10:49', '2026-02-25 14:10:49', '2026-02-25 14:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `traffic_visits`
--

CREATE TABLE `traffic_visits` (
  `id` bigint UNSIGNED NOT NULL,
  `source` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referer_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `traffic_visits`
--

INSERT INTO `traffic_visits` (`id`, `source`, `referer_host`, `path`, `created_at`, `updated_at`) VALUES
(1, 'referral', '127.0.0.1', '/', '2026-02-25 14:02:56', '2026-02-25 14:02:56'),
(2, 'referral', '127.0.0.1', '/', '2026-02-25 14:03:13', '2026-02-25 14:03:13'),
(3, 'referral', '127.0.0.1', '/products', '2026-02-25 14:03:56', '2026-02-25 14:03:56'),
(4, 'referral', '127.0.0.1', '/login', '2026-02-25 14:04:06', '2026-02-25 14:04:06'),
(5, 'referral', '127.0.0.1', '/products', '2026-02-25 14:04:07', '2026-02-25 14:04:07'),
(6, 'referral', '127.0.0.1', '/login', '2026-02-25 14:04:11', '2026-02-25 14:04:11'),
(7, 'referral', '127.0.0.1', '/login', '2026-02-25 14:04:35', '2026-02-25 14:04:35'),
(8, 'referral', '127.0.0.1', '/login', '2026-02-25 14:04:55', '2026-02-25 14:04:55'),
(9, 'referral', '192.168.100.167', '/', '2026-02-25 14:05:01', '2026-02-25 14:05:01'),
(10, 'referral', '127.0.0.1', '/login', '2026-02-25 14:05:30', '2026-02-25 14:05:30'),
(11, 'referral', '127.0.0.1', '/login', '2026-02-25 14:06:09', '2026-02-25 14:06:09'),
(12, 'referral', '127.0.0.1', '/login', '2026-02-25 14:06:22', '2026-02-25 14:06:22'),
(13, 'referral', '127.0.0.1', '/products', '2026-02-25 14:06:41', '2026-02-25 14:06:41'),
(14, 'referral', '127.0.0.1', '/products', '2026-02-25 14:06:52', '2026-02-25 14:06:52'),
(15, 'referral', '127.0.0.1', '/login', '2026-02-25 14:06:56', '2026-02-25 14:06:56'),
(16, 'referral', '127.0.0.1', '/login', '2026-02-25 14:07:20', '2026-02-25 14:07:20'),
(17, 'referral', '127.0.0.1', '/login', '2026-02-25 14:07:33', '2026-02-25 14:07:33'),
(18, 'referral', '127.0.0.1', '/register', '2026-02-25 14:07:38', '2026-02-25 14:07:38'),
(19, 'referral', '127.0.0.1', '/register', '2026-02-25 14:08:12', '2026-02-25 14:08:12'),
(20, 'referral', '127.0.0.1', '/products', '2026-02-25 14:09:21', '2026-02-25 14:09:21'),
(21, 'referral', '192.168.100.167', '/', '2026-02-25 14:09:51', '2026-02-25 14:09:51'),
(22, 'referral', '127.0.0.1', '/', '2026-02-25 14:10:29', '2026-02-25 14:10:29'),
(23, 'referral', '127.0.0.1', '/products', '2026-02-25 14:10:34', '2026-02-25 14:10:34'),
(24, 'referral', '127.0.0.1', '/products', '2026-02-25 14:10:49', '2026-02-25 14:10:49'),
(25, 'referral', '127.0.0.1', '/', '2026-02-25 14:11:35', '2026-02-25 14:11:35'),
(26, 'referral', '127.0.0.1', '/products', '2026-02-25 14:14:06', '2026-02-25 14:14:06'),
(27, 'referral', '127.0.0.1', '/login', '2026-02-25 14:14:09', '2026-02-25 14:14:09'),
(28, 'direct', NULL, '/', '2026-02-26 21:12:41', '2026-02-26 21:12:41'),
(29, 'direct', NULL, '/login', '2026-02-26 21:14:21', '2026-02-26 21:14:21'),
(30, 'direct', NULL, '/', '2026-02-26 21:15:54', '2026-02-26 21:15:54'),
(31, 'direct', NULL, '/', '2026-02-26 21:17:30', '2026-02-26 21:17:30'),
(32, 'direct', NULL, '/', '2026-02-26 21:20:02', '2026-02-26 21:20:02'),
(33, 'direct', NULL, '/', '2026-02-26 21:21:24', '2026-02-26 21:21:24'),
(34, 'direct', NULL, '/login', '2026-02-26 21:26:04', '2026-02-26 21:26:04'),
(35, 'direct', NULL, '/login', '2026-02-26 21:27:50', '2026-02-26 21:27:50'),
(36, 'direct', NULL, '/login', '2026-02-26 21:28:05', '2026-02-26 21:28:05'),
(37, 'referral', '127.0.0.1', '/', '2026-02-26 21:29:26', '2026-02-26 21:29:26'),
(38, 'referral', '127.0.0.1', '/', '2026-02-26 21:36:57', '2026-02-26 21:36:57'),
(39, 'referral', '127.0.0.1', '/', '2026-02-26 21:37:27', '2026-02-26 21:37:27'),
(40, 'referral', '127.0.0.1', '/products', '2026-02-26 21:44:20', '2026-02-26 21:44:20'),
(41, 'referral', '127.0.0.1', '/', '2026-02-26 21:44:23', '2026-02-26 21:44:23'),
(42, 'referral', '127.0.0.1', '/', '2026-02-26 21:53:56', '2026-02-26 21:53:56'),
(43, 'direct', NULL, '/', '2026-02-27 11:10:22', '2026-02-27 11:10:22'),
(44, 'referral', '127.0.0.1', '/', '2026-02-27 11:10:57', '2026-02-27 11:10:57'),
(45, 'referral', '127.0.0.1', '/products', '2026-02-27 11:11:00', '2026-02-27 11:11:00'),
(46, 'referral', '127.0.0.1', '/about', '2026-02-27 11:11:04', '2026-02-27 11:11:04'),
(47, 'referral', '127.0.0.1', '/about', '2026-02-27 11:17:24', '2026-02-27 11:17:24'),
(48, 'referral', '127.0.0.1', '/about', '2026-02-27 11:19:35', '2026-02-27 11:19:35'),
(49, 'referral', '127.0.0.1', '/about', '2026-02-27 11:19:54', '2026-02-27 11:19:54'),
(50, 'referral', '127.0.0.1', '/about', '2026-02-27 11:36:43', '2026-02-27 11:36:43'),
(51, 'referral', '127.0.0.1', '/about', '2026-02-27 11:49:29', '2026-02-27 11:49:29'),
(52, 'referral', '127.0.0.1', '/about', '2026-02-27 12:00:10', '2026-02-27 12:00:10'),
(53, 'referral', '127.0.0.1', '/about', '2026-02-27 12:00:14', '2026-02-27 12:00:14'),
(54, 'referral', '127.0.0.1', '/about', '2026-02-27 12:19:26', '2026-02-27 12:19:26'),
(55, 'referral', '127.0.0.1', '/products', '2026-02-27 12:19:33', '2026-02-27 12:19:33'),
(56, 'referral', '127.0.0.1', '/products/1', '2026-02-27 12:19:37', '2026-02-27 12:19:37'),
(57, 'referral', '127.0.0.1', '/products/1', '2026-02-27 12:20:11', '2026-02-27 12:20:11'),
(58, 'referral', '127.0.0.1', '/products/1', '2026-02-27 12:20:15', '2026-02-27 12:20:15'),
(59, 'referral', '127.0.0.1', '/products/1', '2026-02-27 12:23:26', '2026-02-27 12:23:26'),
(60, 'referral', '127.0.0.1', '/products', '2026-02-27 12:23:52', '2026-02-27 12:23:52'),
(61, 'referral', '127.0.0.1', '/products/2', '2026-02-27 12:23:56', '2026-02-27 12:23:56'),
(62, 'referral', '127.0.0.1', '/products/2', '2026-02-27 12:24:35', '2026-02-27 12:24:35'),
(63, 'direct', NULL, '/', '2026-02-28 18:30:30', '2026-02-28 18:30:30'),
(64, 'referral', '127.0.0.1', '/products', '2026-02-28 18:30:47', '2026-02-28 18:30:47'),
(65, 'referral', '127.0.0.1', '/products/1', '2026-02-28 18:30:54', '2026-02-28 18:30:54'),
(66, 'referral', '127.0.0.1', '/products/1', '2026-02-28 18:31:49', '2026-02-28 18:31:49'),
(67, 'referral', '127.0.0.1', '/about', '2026-02-28 18:34:08', '2026-02-28 18:34:08'),
(68, 'referral', '127.0.0.1', '/', '2026-02-28 18:35:09', '2026-02-28 18:35:09'),
(69, 'referral', '127.0.0.1', '/products/2', '2026-02-28 18:35:37', '2026-02-28 18:35:37'),
(70, 'referral', '127.0.0.1', '/products/2', '2026-02-28 18:36:16', '2026-02-28 18:36:16'),
(71, 'referral', '127.0.0.1', '/login', '2026-02-28 18:40:59', '2026-02-28 18:40:59'),
(72, 'referral', '127.0.0.1', '/login', '2026-02-28 18:41:59', '2026-02-28 18:41:59'),
(73, 'referral', '127.0.0.1', '/products', '2026-02-28 18:42:21', '2026-02-28 18:42:21'),
(74, 'referral', '127.0.0.1', '/products', '2026-02-28 18:43:31', '2026-02-28 18:43:31'),
(75, 'referral', '127.0.0.1', '/login', '2026-02-28 18:43:35', '2026-02-28 18:43:35'),
(76, 'referral', '127.0.0.1', '/login', '2026-02-28 18:43:51', '2026-02-28 18:43:51'),
(77, 'referral', '127.0.0.1', '/products', '2026-02-28 18:44:02', '2026-02-28 18:44:02'),
(78, 'referral', '127.0.0.1', '/products', '2026-02-28 18:44:11', '2026-02-28 18:44:11'),
(79, 'referral', '127.0.0.1', '/login', '2026-02-28 18:44:14', '2026-02-28 18:44:14'),
(80, 'referral', '127.0.0.1', '/login', '2026-02-28 18:44:26', '2026-02-28 18:44:26'),
(81, 'referral', '127.0.0.1', '/login', '2026-02-28 18:44:34', '2026-02-28 18:44:34'),
(82, 'referral', '127.0.0.1', '/login', '2026-02-28 18:44:47', '2026-02-28 18:44:47'),
(83, 'referral', '127.0.0.1', '/login', '2026-02-28 18:45:02', '2026-02-28 18:45:02'),
(84, 'referral', '127.0.0.1', '/login', '2026-02-28 18:47:29', '2026-02-28 18:47:29'),
(85, 'referral', '127.0.0.1', '/', '2026-02-28 18:48:01', '2026-02-28 18:48:01'),
(86, 'referral', '127.0.0.1', '/', '2026-02-28 18:55:42', '2026-02-28 18:55:42'),
(87, 'referral', '127.0.0.1', '/', '2026-02-28 18:56:24', '2026-02-28 18:56:24'),
(88, 'referral', '127.0.0.1', '/', '2026-02-28 18:56:45', '2026-02-28 18:56:45'),
(89, 'referral', '127.0.0.1', '/about', '2026-02-28 18:57:38', '2026-02-28 18:57:38'),
(90, 'direct', NULL, '/', '2026-03-06 12:01:14', '2026-03-06 12:01:14'),
(91, 'referral', '127.0.0.1', '/login', '2026-03-06 12:01:34', '2026-03-06 12:01:34'),
(92, 'referral', '127.0.0.1', '/', '2026-03-06 12:02:40', '2026-03-06 12:02:40'),
(93, 'referral', '127.0.0.1', '/', '2026-03-06 12:06:35', '2026-03-06 12:06:35'),
(94, 'referral', '127.0.0.1', '/', '2026-03-06 12:06:51', '2026-03-06 12:06:51'),
(95, 'referral', '127.0.0.1', '/', '2026-03-06 12:07:11', '2026-03-06 12:07:11'),
(96, 'referral', '127.0.0.1', '/', '2026-03-06 12:08:45', '2026-03-06 12:08:45'),
(97, 'referral', '127.0.0.1', '/', '2026-03-06 12:08:50', '2026-03-06 12:08:50'),
(98, 'referral', '127.0.0.1', '/products', '2026-03-06 12:08:54', '2026-03-06 12:08:54'),
(99, 'referral', '127.0.0.1', '/', '2026-03-06 12:10:08', '2026-03-06 12:10:08'),
(100, 'referral', '127.0.0.1', '/', '2026-03-06 12:17:26', '2026-03-06 12:17:26'),
(101, 'referral', '127.0.0.1', '/lang/ku', '2026-03-06 12:18:00', '2026-03-06 12:18:00'),
(102, 'referral', '127.0.0.1', '/lang/en', '2026-03-06 12:18:08', '2026-03-06 12:18:08'),
(103, 'referral', '127.0.0.1', '/', '2026-03-06 12:18:12', '2026-03-06 12:18:12'),
(104, 'referral', '127.0.0.1', '/', '2026-03-06 12:20:17', '2026-03-06 12:20:17'),
(105, 'referral', '127.0.0.1', '/', '2026-03-06 12:20:23', '2026-03-06 12:20:23'),
(106, 'referral', '127.0.0.1', '/', '2026-03-06 12:20:40', '2026-03-06 12:20:40'),
(107, 'referral', '127.0.0.1', '/', '2026-03-06 12:21:25', '2026-03-06 12:21:25'),
(108, 'referral', '127.0.0.1', '/products', '2026-03-06 12:21:27', '2026-03-06 12:21:27'),
(109, 'referral', '127.0.0.1', '/about', '2026-03-06 12:21:32', '2026-03-06 12:21:32'),
(110, 'referral', '127.0.0.1', '/', '2026-03-06 12:22:12', '2026-03-06 12:22:12'),
(111, 'referral', '127.0.0.1', '/', '2026-03-06 12:22:30', '2026-03-06 12:22:30'),
(112, 'referral', '127.0.0.1', '/', '2026-03-06 12:23:04', '2026-03-06 12:23:04'),
(113, 'referral', '127.0.0.1', '/', '2026-03-06 12:23:18', '2026-03-06 12:23:18'),
(114, 'referral', '127.0.0.1', '/', '2026-03-06 12:23:37', '2026-03-06 12:23:37'),
(115, 'referral', '127.0.0.1', '/', '2026-03-06 12:23:55', '2026-03-06 12:23:55'),
(116, 'referral', '127.0.0.1', '/', '2026-03-06 12:24:02', '2026-03-06 12:24:02'),
(117, 'referral', '127.0.0.1', '/', '2026-03-06 12:24:33', '2026-03-06 12:24:33'),
(118, 'referral', '127.0.0.1', '/', '2026-03-06 12:24:41', '2026-03-06 12:24:41'),
(119, 'referral', '127.0.0.1', '/', '2026-03-06 12:25:26', '2026-03-06 12:25:26'),
(120, 'referral', '127.0.0.1', '/', '2026-03-06 12:25:36', '2026-03-06 12:25:36'),
(121, 'referral', '127.0.0.1', '/', '2026-03-06 12:25:54', '2026-03-06 12:25:54'),
(122, 'referral', '127.0.0.1', '/', '2026-03-06 12:26:03', '2026-03-06 12:26:03'),
(123, 'referral', '127.0.0.1', '/', '2026-03-06 12:26:14', '2026-03-06 12:26:14'),
(124, 'referral', '127.0.0.1', '/', '2026-03-06 12:26:25', '2026-03-06 12:26:25'),
(125, 'referral', '127.0.0.1', '/', '2026-03-06 12:26:35', '2026-03-06 12:26:35'),
(126, 'referral', '127.0.0.1', '/', '2026-03-06 12:26:41', '2026-03-06 12:26:41'),
(127, 'referral', '127.0.0.1', '/', '2026-03-06 12:27:02', '2026-03-06 12:27:02'),
(128, 'referral', '127.0.0.1', '/', '2026-03-06 12:27:17', '2026-03-06 12:27:17'),
(129, 'referral', '127.0.0.1', '/', '2026-03-06 12:27:24', '2026-03-06 12:27:24'),
(130, 'referral', '127.0.0.1', '/', '2026-03-06 12:27:37', '2026-03-06 12:27:37'),
(131, 'referral', '127.0.0.1', '/', '2026-03-06 12:27:42', '2026-03-06 12:27:42'),
(132, 'referral', '127.0.0.1', '/', '2026-03-06 12:30:57', '2026-03-06 12:30:57'),
(133, 'referral', '127.0.0.1', '/', '2026-03-06 12:32:18', '2026-03-06 12:32:18'),
(134, 'referral', '127.0.0.1', '/products', '2026-03-06 12:32:48', '2026-03-06 12:32:48'),
(135, 'referral', '127.0.0.1', '/', '2026-03-06 12:32:49', '2026-03-06 12:32:49'),
(136, 'referral', '127.0.0.1', '/products', '2026-03-06 12:32:54', '2026-03-06 12:32:54'),
(137, 'referral', '127.0.0.1', '/lang/ar', '2026-03-06 12:33:13', '2026-03-06 12:33:13'),
(138, 'referral', '127.0.0.1', '/products', '2026-03-06 12:33:14', '2026-03-06 12:33:14'),
(139, 'referral', '127.0.0.1', '/lang/en', '2026-03-06 12:33:24', '2026-03-06 12:33:24'),
(140, 'referral', '127.0.0.1', '/products', '2026-03-06 12:33:24', '2026-03-06 12:33:24'),
(141, 'referral', '127.0.0.1', '/about', '2026-03-06 12:33:28', '2026-03-06 12:33:28'),
(142, 'referral', '127.0.0.1', '/about', '2026-03-06 13:02:55', '2026-03-06 13:02:55'),
(143, 'direct', NULL, '/', '2026-03-09 14:07:10', '2026-03-09 14:07:10'),
(144, 'direct', NULL, '/', '2026-03-09 14:07:22', '2026-03-09 14:07:22'),
(145, 'referral', '127.0.0.1', '/login', '2026-03-09 14:20:43', '2026-03-09 14:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(21, 'hassan', 'hassansamirf2@gmail.com', NULL, '$2y$12$vBgcPKVXPq0etZih33S6PeJdqhvb4V/MRlBUCi/uzsHV4ffg4GXqC', 0, NULL, '2026-02-25 13:36:55', '2026-02-25 13:36:55'),
(22, 'Hassan Samir', 'hassansamirf@gmail.com', NULL, '$2y$12$hajfZ.wCfnsK2mqj5jDTQ.CdDT5ZsHSHhjepjCWOAUx.tLUQuuHIe', 1, NULL, '2026-02-25 13:41:37', '2026-02-25 13:41:37'),
(23, 'Hassan Samir', 'rasanmarket@gmail.com', NULL, '$2y$12$coZ26d0F3oUa0n2oqFLfy.z/d4djcdJEosuYXvi3s2SN6XUnzPTpy', 1, NULL, '2026-02-25 14:09:20', '2026-02-25 14:09:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_product_category_id_product_id_unique` (`category_id`,`product_id`),
  ADD KEY `category_product_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_requests`
--
ALTER TABLE `order_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_requests_product_id_index` (`product_id`),
  ADD KEY `order_requests_status_index` (`status`),
  ADD KEY `order_requests_created_at_index` (`created_at`),
  ADD KEY `order_requests_admin_seen_at_index` (`admin_seen_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
  ADD KEY `products_name_index` (`name`),
  ADD KEY `products_price_index` (`price`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_index` (`product_id`),
  ADD KEY `reviews_rating_index` (`rating`),
  ADD KEY `reviews_is_approved_index` (`is_approved`),
  ADD KEY `reviews_created_at_index` (`created_at`),
  ADD KEY `reviews_admin_seen_at_index` (`admin_seen_at`);

--
-- Indexes for table `search_keywords`
--
ALTER TABLE `search_keywords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `search_keywords_keyword_unique` (`keyword`),
  ADD KEY `search_keywords_count_index` (`count`),
  ADD KEY `search_keywords_last_searched_at_index` (`last_searched_at`);

--
-- Indexes for table `traffic_visits`
--
ALTER TABLE `traffic_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traffic_visits_source_index` (`source`),
  ADD KEY `traffic_visits_created_at_index` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `category_product`
--
ALTER TABLE `category_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_requests`
--
ALTER TABLE `order_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `search_keywords`
--
ALTER TABLE `search_keywords`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `traffic_visits`
--
ALTER TABLE `traffic_visits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_requests`
--
ALTER TABLE `order_requests`
  ADD CONSTRAINT `order_requests_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
