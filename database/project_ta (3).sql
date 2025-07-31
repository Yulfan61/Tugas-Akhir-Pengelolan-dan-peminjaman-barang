-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jul 2025 pada 09.09
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_ta`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `announcements`
--

INSERT INTO `announcements` (`id`, `message`, `created_at`, `updated_at`) VALUES
(1, 'Kelola data barang, peminjaman, dan laporan kerusakan dengan lebih efisien.', '2025-07-02 13:01:26', '2025-07-02 13:05:35'),
(2, 'Kelola data barang, peminjaman, dan laporan kerusakan dengan lebih efisien.', '2025-07-02 13:03:13', '2025-07-02 13:03:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrowings`
--

CREATE TABLE `borrowings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Pending','Approved','Waiting Approval','Rejected','Returned') NOT NULL,
  `returned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date NOT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `penalty` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `penalty_status` enum('Unpaid','Paid') NOT NULL DEFAULT 'Unpaid',
  `penalty_proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `borrowings`
--

INSERT INTO `borrowings` (`id`, `user_id`, `status`, `returned_by`, `borrow_date`, `return_date`, `returned_at`, `approved_by`, `approved_at`, `penalty`, `created_at`, `updated_at`, `penalty_status`, `penalty_proof`) VALUES
(74, 13, 'Returned', NULL, '2025-07-01', '2025-07-08', NULL, 1, '2025-07-01 12:30:28', NULL, '2025-07-01 12:30:07', '2025-07-01 12:32:55', 'Unpaid', NULL),
(76, 12, 'Rejected', NULL, '2025-07-01', '2025-07-10', NULL, NULL, NULL, NULL, '2025-07-01 12:38:26', '2025-07-01 12:39:54', 'Unpaid', NULL),
(78, 14, 'Returned', NULL, '2025-07-02', '2025-07-03', NULL, 1, '2025-07-02 14:20:20', NULL, '2025-07-02 11:10:12', '2025-07-02 14:26:55', 'Unpaid', NULL),
(79, 12, 'Returned', NULL, '2025-07-07', '2025-07-08', NULL, 1, '2025-07-07 14:51:21', 18000.00, '2025-07-07 14:50:49', '2025-07-07 14:54:34', 'Paid', 'penalty_proofs/v2uIHESw6egTlfYMvb9dIQyz6fX9UkLYDqcOeEIV.png'),
(80, 1, 'Returned', NULL, '2025-07-08', '2025-07-09', NULL, 1, '2025-07-08 06:49:15', 18000.00, '2025-07-08 06:48:58', '2025-07-08 06:56:00', 'Paid', 'penalty_proofs/v1Mq4enBqec9frBcQKWoLIK0maoU1lTEejzpVk91.jpg'),
(81, 1, 'Waiting Approval', NULL, '2025-07-08', '2025-07-10', NULL, 1, '2025-07-08 07:04:54', 20000.00, '2025-07-08 06:57:13', '2025-07-08 07:05:02', 'Unpaid', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrowing_items`
--

CREATE TABLE `borrowing_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `borrowing_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `return_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `borrowing_items`
--

INSERT INTO `borrowing_items` (`id`, `borrowing_id`, `item_id`, `quantity`, `return_photo`, `created_at`, `updated_at`) VALUES
(79, 74, 7, 100, NULL, NULL, NULL),
(81, 76, 3, 50, NULL, NULL, NULL),
(83, 78, 5, 10, NULL, NULL, NULL),
(84, 79, 3, 100, NULL, NULL, NULL),
(85, 80, 5, 10, 'return_photos/RL6a5EmmOgTShz7vsiuKiXQMeu19YodcbPVjvhZU.png', NULL, NULL),
(86, 81, 4, 100, NULL, NULL, NULL),
(87, 81, 5, 10, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(3, 'Pecah Belah', '2025-06-04 00:17:33', '2025-06-04 00:17:33'),
(4, 'Elektronik', '2025-06-04 01:19:51', '2025-06-04 01:19:51'),
(5, 'Tikar', '2025-06-09 21:35:27', '2025-06-09 21:35:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `damage_reports`
--

CREATE TABLE `damage_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text NOT NULL,
  `report_date` date NOT NULL DEFAULT curdate(),
  `status` enum('Reported','In Progress','Resolved') NOT NULL DEFAULT 'Reported',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `damage_reports`
--

INSERT INTO `damage_reports` (`id`, `item_id`, `user_id`, `description`, `report_date`, `status`, `created_at`, `updated_at`) VALUES
(5, 5, 1, 'Pecah 1', '2025-06-13', 'Resolved', '2025-06-13 01:44:35', '2025-06-13 01:44:46'),
(6, 4, 1, 'Pecah 1', '2025-06-19', 'Reported', '2025-06-19 06:59:33', '2025-06-19 06:59:33'),
(7, 3, 1, 'Pecah 1', '2025-07-07', 'Reported', '2025-07-07 14:52:32', '2025-07-07 14:52:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `year_of_purchase` year(4) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `condition` enum('Baik','Rusak Ringan','Rusak Berat','Dalam Perbaikan') NOT NULL DEFAULT 'Baik',
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `items`
--

INSERT INTO `items` (`id`, `code`, `name`, `stock`, `category_id`, `brand`, `model`, `year_of_purchase`, `price`, `specifications`, `condition`, `location_id`, `created_at`, `updated_at`) VALUES
(3, 'P3S-1', 'Gelas', 100, 3, 'gelas', 'gelas kecil', '2019', 5000.00, NULL, 'Rusak Ringan', 4, '2025-06-04 00:18:08', '2025-07-07 14:51:37'),
(4, 'SK-02', 'Piring', 100, 3, 'piring', 'piring standar', '2019', 5.00, NULL, 'Baik', 5, '2025-06-04 00:19:20', '2025-07-08 07:05:02'),
(5, 'SK-05', 'Lampu', 10, 4, 'Philips', 'Lampu Besar', '2022', 20.00, NULL, 'Baik', 5, '2025-06-04 01:21:01', '2025-07-08 07:05:02'),
(7, 'P3S-4', 'Sendok', 100, 3, 'sendok', 'besi', '2019', 100.00, NULL, 'Baik', 4, '2025-06-10 00:17:49', '2025-07-02 10:53:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `item_histories`
--

CREATE TABLE `item_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `activity` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `locations`
--

INSERT INTO `locations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(4, 'Gudang Pemuda', '2025-06-04 00:17:20', '2025-06-04 00:17:20'),
(5, 'Gudang Senin Kliwon', '2025-06-04 00:18:31', '2025-06-04 00:18:31'),
(6, 'Gudang Mijen', '2025-06-22 04:33:47', '2025-06-22 04:33:59'),
(11, 'Gudang Masjid', '2025-07-02 11:15:52', '2025-07-02 11:15:52'),
(12, 'Gudang Gedung 2', '2025-07-02 11:16:06', '2025-07-02 11:16:06'),
(13, 'Gudang 2', '2025-07-02 11:23:06', '2025-07-02 11:23:06'),
(14, 'Gudang 3', '2025-07-02 11:23:27', '2025-07-02 11:23:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_01_170945_add_role_to_users_table', 2),
(5, '2025_06_02_023642_create_categories_table', 2),
(6, '2025_06_02_023748_create_locations_table', 2),
(7, '2025_06_02_023821_create_items_table', 2),
(8, '2025_06_02_023902_create_borrowings_table', 2),
(9, '2025_06_02_023946_create_borrowing_items_table', 2),
(10, '2025_06_02_024016_create_item_histories_table', 2),
(11, '2025_06_02_024053_create_damage_reports_table', 2),
(14, '2025_06_02_063246_create_permission_tables', 3),
(15, '2025_06_02_064127_create_personal_access_tokens_table', 3),
(16, '2025_06_04_084102_add_return_photo_to_borrowing_items_table', 4),
(17, '2025_06_10_035233_remove_role_from_users_table', 5),
(18, '2025_06_10_055615_add_stock_to_items_table', 6),
(19, '2025_06_10_121957_add_user_id_to_damage_reports_table', 7),
(20, '2025_06_11_072738_add_phone_number_to_users_table', 8),
(21, '2025_06_11_115041_add_last_seen_at_to_users_table', 9),
(22, '2025_06_15_022202_add_profile_photo_to_users_table', 10),
(24, '2025_06_19_062145_add_returned_by_to_borrowings_table', 11),
(25, '2025_06_19_070330_add_returned_at_to_borrowings_table', 12),
(26, '2025_06_30_061537_create_notifications_table', 13),
(27, '2025_07_02_194409_create_announcements_table', 14),
(28, '2025_07_07_213737_add_penalty_payment_to_borrowings_table', 15);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 13),
(3, 'App\\Models\\User', 12),
(3, 'App\\Models\\User', 14),
(4, 'App\\Models\\User', 15);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0d60b283-128e-4a18-9f21-f00a833740ba', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 13, '{\"message\":\"Permintaan peminjaman baru dari reza\",\"link\":\"https:\\/\\/121e-115-178-237-96.ngrok-free.app\\/borrowings\\/78\"}', NULL, '2025-07-02 11:10:13', '2025-07-02 11:10:13'),
('15fdc71d-9c32-4c20-93ca-58092226f50b', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 13, '{\"message\":\"Permintaan peminjaman baru dari yulfan\",\"link\":\"https:\\/\\/546a-114-79-32-145.ngrok-free.app\\/borrowings\\/75\"}', NULL, '2025-07-01 12:37:53', '2025-07-01 12:37:53'),
('18740c0e-d333-42ae-8281-69a1971160c9', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 12, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Pending\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/79\"}', NULL, '2025-07-07 14:53:24', '2025-07-07 14:53:24'),
('1a0eeefc-bdf7-4393-ac6f-e23bec19c6dc', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari Maheswara\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/81\"}', NULL, '2025-07-08 06:57:21', '2025-07-08 06:57:21'),
('20e756ed-230d-41ff-966d-adf047d4317b', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari reza\",\"link\":\"https:\\/\\/121e-115-178-237-96.ngrok-free.app\\/borrowings\\/78\"}', NULL, '2025-07-02 11:10:13', '2025-07-02 11:10:13'),
('2ae30db5-5fdf-4fd2-b4d0-f76ae788e7ac', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 13, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Approved\",\"link\":\"https:\\/\\/546a-114-79-32-145.ngrok-free.app\\/borrowings\\/74\"}', NULL, '2025-07-01 12:30:29', '2025-07-01 12:30:29'),
('2b13e842-556a-4dab-953a-1ec2c8ce2438', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 13, '{\"message\":\"Permintaan peminjaman baru dari Maheswara\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/80\"}', NULL, '2025-07-08 06:49:05', '2025-07-08 06:49:05'),
('2cce3621-f3e7-4495-b260-5a79217600a4', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 13, '{\"message\":\"Permintaan peminjaman baru dari Maheswara\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/81\"}', NULL, '2025-07-08 06:57:22', '2025-07-08 06:57:22'),
('3437f716-9163-4fcd-9e1f-daeb40d22a24', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari Maheswara\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/80\"}', NULL, '2025-07-08 06:49:05', '2025-07-08 06:49:05'),
('3695caeb-d490-419f-b9e2-ab1d5ece4f68', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 14, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Returned\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/78\"}', NULL, '2025-07-02 14:26:55', '2025-07-02 14:26:55'),
('3967be96-00c7-4feb-9c37-024721a4e77f', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 12, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Approved\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/79\"}', NULL, '2025-07-07 14:51:22', '2025-07-07 14:51:22'),
('45a6437d-d003-4a0c-a45d-ff3a801495c5', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 13, '{\"message\":\"Permintaan peminjaman baru dari reza\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/77\"}', NULL, '2025-07-02 04:29:46', '2025-07-02 04:29:46'),
('49edfab3-97a1-45d5-8b83-6deb33ef4f88', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 12, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Pending\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/79\"}', NULL, '2025-07-07 14:53:08', '2025-07-07 14:53:08'),
('4e177527-85ab-4125-ab21-c35816896c72', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 11, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Approved\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/70\"}', NULL, '2025-06-30 02:13:30', '2025-06-30 02:13:30'),
('4ec42ca5-77a4-459b-850c-1e0a7db7346c', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 11, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Returned\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/70\"}', NULL, '2025-06-30 04:52:51', '2025-06-30 04:52:51'),
('4f8a1e51-9463-4e23-a63d-ef4cac36f92b', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 14, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Approved\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/78\"}', NULL, '2025-07-02 11:10:31', '2025-07-02 11:10:31'),
('5679c346-4f48-4955-8b11-ab67a4469567', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 13, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Returned\",\"link\":\"https:\\/\\/546a-114-79-32-145.ngrok-free.app\\/borrowings\\/74\"}', NULL, '2025-07-01 12:32:56', '2025-07-01 12:32:56'),
('6580625a-c26d-4a26-b14a-0f28ee439dd9', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 11, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Returned\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/71\"}', NULL, '2025-06-30 04:55:19', '2025-06-30 04:55:19'),
('67b09879-7dc8-412d-83ef-cb78117a0ce0', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 14, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Pending\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/78\"}', NULL, '2025-07-02 11:10:56', '2025-07-02 11:10:56'),
('6f5049a7-0173-42f6-b431-a385a6b3e22b', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 11, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Returned\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/72\"}', NULL, '2025-06-30 12:01:50', '2025-06-30 12:01:50'),
('776f8c14-8ce5-4e1b-b090-2bb276ddbfbb', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 11, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Approved\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/71\"}', NULL, '2025-06-30 04:53:08', '2025-06-30 04:53:08'),
('7fa904aa-72a4-4598-b7d9-91a720fb55db', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari yulfan24\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/71\"}', NULL, '2025-06-30 04:52:12', '2025-06-30 04:52:12'),
('90891b11-4ebc-49b0-801e-45efebc8df9f', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari Muhammad Reza\",\"link\":\"https:\\/\\/546a-114-79-32-145.ngrok-free.app\\/borrowings\\/74\"}', NULL, '2025-07-01 12:30:10', '2025-07-01 12:30:10'),
('92d17efa-25a5-4619-baf2-d7688d6d6044', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari reza\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/77\"}', NULL, '2025-07-02 04:29:46', '2025-07-02 04:29:46'),
('9362558c-6005-447d-ac43-d1790c6dbbb8', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 14, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Approved\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/78\"}', NULL, '2025-07-02 14:20:25', '2025-07-02 14:20:25'),
('95183a99-1d48-41aa-a494-263fdaea6410', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari yulfan\",\"link\":\"https:\\/\\/546a-114-79-32-145.ngrok-free.app\\/borrowings\\/76\"}', NULL, '2025-07-01 12:38:27', '2025-07-01 12:38:27'),
('9b88a118-5f2b-4ef2-a764-042dddf2e09f', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 13, '{\"message\":\"Permintaan peminjaman baru dari yulfan\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/79\"}', NULL, '2025-07-07 14:50:53', '2025-07-07 14:50:53'),
('a5080a45-9cc4-4cfe-a2c7-33caeae9c499', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 14, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Pending\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/77\"}', NULL, '2025-07-02 10:48:07', '2025-07-02 10:48:07'),
('ba57dfce-0f7c-4880-8073-1d57da6e2688', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 14, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Approved\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/77\"}', NULL, '2025-07-02 10:47:23', '2025-07-02 10:47:23'),
('bd1bd3fb-6640-4efa-bf1e-34843dcbf4cc', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 12, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Returned\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/79\"}', NULL, '2025-07-07 14:54:35', '2025-07-07 14:54:35'),
('c623888a-dcf3-4185-ab85-6d480481cac4', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari yulfan24\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/73\"}', NULL, '2025-06-30 12:03:27', '2025-06-30 12:03:27'),
('c89099a8-75b6-4974-947b-2a29a9b368a9', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 11, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Rejected\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/73\"}', NULL, '2025-06-30 12:03:39', '2025-06-30 12:03:39'),
('cadd88e6-04f6-4097-a424-983f71edf7c8', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 12, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Approved\",\"link\":\"https:\\/\\/546a-114-79-32-145.ngrok-free.app\\/borrowings\\/75\"}', NULL, '2025-07-01 12:39:01', '2025-07-01 12:39:01'),
('cd1c0282-3208-4bca-baf6-0568e194448d', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 13, '{\"message\":\"Permintaan peminjaman baru dari yulfan\",\"link\":\"https:\\/\\/546a-114-79-32-145.ngrok-free.app\\/borrowings\\/76\"}', NULL, '2025-07-01 12:38:27', '2025-07-01 12:38:27'),
('d38bc722-61ea-4c73-bb2e-48269b0617a6', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 11, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Approved\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/72\"}', NULL, '2025-06-30 12:01:14', '2025-06-30 12:01:14'),
('db69ea35-0f0e-4384-97a0-777c5a0d9c01', 'App\\Notifications\\BorrowingStatusUpdated', 'App\\Models\\User', 12, '{\"message\":\"Status peminjaman Anda diperbarui menjadi Pending\",\"link\":\"https:\\/\\/546a-114-79-32-145.ngrok-free.app\\/borrowings\\/75\"}', NULL, '2025-07-01 12:39:40', '2025-07-01 12:39:40'),
('e1aa101f-18ec-423a-920c-2e1e482ddeea', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari yulfan24\",\"link\":\"https:\\/\\/5d79-36-72-214-221.ngrok-free.app\\/borrowings\\/72\"}', NULL, '2025-06-30 12:00:48', '2025-06-30 12:00:48'),
('ed8159dc-5917-4605-878a-6eb69c0089e8', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari yulfan\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/borrowings\\/79\"}', NULL, '2025-07-07 14:50:53', '2025-07-07 14:50:53'),
('f50e940a-5551-4a74-8dca-f3e6f432417a', 'App\\Notifications\\NewBorrowingNotification', 'App\\Models\\User', 1, '{\"message\":\"Permintaan peminjaman baru dari yulfan\",\"link\":\"https:\\/\\/546a-114-79-32-145.ngrok-free.app\\/borrowings\\/75\"}', NULL, '2025-07-01 12:37:53', '2025-07-01 12:37:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('mahesvaranirwataka@gmail.com', '$2y$12$LFlS2kFI/mvZuMJDT5QR6OMHychCyff98hvbixQ61TK/sp0TRcwOe', '2025-06-29 20:59:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
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
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(2, 'Admin', 'web', '2025-06-08 20:45:08', '2025-06-08 20:45:08'),
(3, 'Peminjam', 'web', '2025-06-08 20:45:18', '2025-06-08 20:45:18'),
(4, 'Staff', 'web', '2025-06-08 20:45:49', '2025-06-08 20:45:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('wOwx6IvOPQRDNAJJmkwIcOnuo8F3Fd5fqN5acoSs', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGY3ZURhSU1zbDgyVjRienFNaEFDTXRjVTVLM2ZIbndzOUlRR2NTNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib3Jyb3dpbmdzLzgxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1751958380);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `email_verified_at`, `last_seen_at`, `password`, `remember_token`, `created_at`, `updated_at`, `profile_photo`) VALUES
(1, 'Maheswara', 'mahesvaranirwataka@gmail.com', '0123456789', NULL, '2025-07-08 07:06:19', '$2y$12$PYSWcYgw50NCal8dVd9OmOqQ4IgK30l6X6gsxHUXZHgURalynX5Ba', NULL, '2025-06-02 22:34:31', '2025-07-08 07:06:19', 'profile-photos/mf87NWYueBB4934PVJcEthNv5TCBRxRx84l1alUi.jpg'),
(12, 'yulfan', 'yulfan61@gmail.com', '08822234444', NULL, '2025-07-07 14:54:56', '$2y$12$gtXSP57a7Q3cvglAn6Bbu.mNebymFvAD5eCq0Tik/zQhIduEmkMAm', NULL, '2025-07-01 12:17:47', '2025-07-07 14:54:56', NULL),
(13, 'Muhammad Reza', 'syahf6288@gmail.com', '085801581001', NULL, '2025-07-01 12:34:44', '$2y$12$0V6eQgKEGfvO10JfISKU2ONlEyfcA6Jyu1weXLLV3j07c1YnM7c.q', NULL, '2025-07-01 12:28:32', '2025-07-01 12:34:44', NULL),
(14, 'reza', 'reza@gmail.com', '0000000', NULL, '2025-07-02 14:43:09', '$2y$12$s/z5486HT3324tOdqgz1SOtkOQHdihIxCFtfl8tc15MGiLBaM.0He', NULL, '2025-07-02 04:28:10', '2025-07-02 14:43:09', NULL),
(15, 'alza maulana puta', 'alzaganteng211@gmail.com', '081298222950', NULL, '2025-07-02 05:23:32', '$2y$12$F3a3OH9ND/cfV/cb328EyuCgsqw3.Df2YMiD2wPhfNpd.VagIkMSa', NULL, '2025-07-02 05:21:08', '2025-07-02 05:23:32', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrowings_user_id_foreign` (`user_id`),
  ADD KEY `borrowings_approved_by_foreign` (`approved_by`),
  ADD KEY `borrowings_returned_by_foreign` (`returned_by`);

--
-- Indeks untuk tabel `borrowing_items`
--
ALTER TABLE `borrowing_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrowing_items_borrowing_id_foreign` (`borrowing_id`),
  ADD KEY `borrowing_items_item_id_foreign` (`item_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `damage_reports`
--
ALTER TABLE `damage_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `damage_reports_item_id_foreign` (`item_id`),
  ADD KEY `damage_reports_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_code_unique` (`code`),
  ADD KEY `items_category_id_foreign` (`category_id`),
  ADD KEY `items_location_id_foreign` (`location_id`);

--
-- Indeks untuk tabel `item_histories`
--
ALTER TABLE `item_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_histories_item_id_foreign` (`item_id`),
  ADD KEY `item_histories_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT untuk tabel `borrowing_items`
--
ALTER TABLE `borrowing_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `damage_reports`
--
ALTER TABLE `damage_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `item_histories`
--
ALTER TABLE `item_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT untuk tabel `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `borrowings_returned_by_foreign` FOREIGN KEY (`returned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `borrowings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `borrowing_items`
--
ALTER TABLE `borrowing_items`
  ADD CONSTRAINT `borrowing_items_borrowing_id_foreign` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowing_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `damage_reports`
--
ALTER TABLE `damage_reports`
  ADD CONSTRAINT `damage_reports_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `damage_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `item_histories`
--
ALTER TABLE `item_histories`
  ADD CONSTRAINT `item_histories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
