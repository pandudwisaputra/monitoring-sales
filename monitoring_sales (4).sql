-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Jul 2026 pada 18.34
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitoring_sales`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `periode` char(7) NOT NULL,
  `total_penjualan` decimal(15,2) NOT NULL,
  `persentase_komisi` decimal(5,4) NOT NULL,
  `total_pembayaran` decimal(15,2) NOT NULL,
  `status` enum('pending','paid','disbursed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `commissions`
--

INSERT INTO `commissions` (`id`, `user_id`, `periode`, `total_penjualan`, `persentase_komisi`, `total_pembayaran`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-06', 14610148.00, 0.0070, 102271.04, 'disbursed', '2026-06-17 12:21:37', '2026-06-19 13:03:40'),
(2, 3, '2026-06', 135500000.00, 1.0000, 1355000.00, 'disbursed', '2026-06-18 03:20:38', '2026-06-19 13:54:31'),
(3, 4, '2026-06', 61500000.00, 1.0000, 615000.00, 'paid', '2026-06-19 14:14:31', '2026-06-19 14:15:02'),
(4, 6, '2026-06', 55000000.00, 1.0000, 550000.00, 'disbursed', '2026-06-25 13:48:35', '2026-06-25 13:48:48'),
(5, 7, '2026-06', 11000000.00, 1.0000, 110000.00, 'paid', '2026-06-25 15:13:09', '2026-06-25 15:31:58'),
(6, 8, '2026-06', 5500000.00, 0.7000, 38500.00, 'paid', '2026-06-25 15:46:39', '2026-06-25 15:47:01'),
(7, 9, '2026-06', 11000000.00, 0.7000, 77000.00, 'disbursed', '2026-06-29 12:46:03', '2026-06-29 12:47:21'),
(8, 10, '2026-07', 18000000.00, 1.0000, 180000.00, 'paid', '2026-07-01 13:19:00', '2026-07-01 13:19:21'),
(9, 11, '2026-07', 15000000.00, 1.0000, 150000.00, 'disbursed', '2026-07-01 16:09:17', '2026-07-01 16:09:44'),
(10, 12, '2026-07', 22000000.00, 1.0000, 220000.00, 'paid', '2026-07-01 16:32:25', '2026-07-01 16:32:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `commission_payments`
--

CREATE TABLE `commission_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commission_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `flip_disbursement_id` varchar(30) DEFAULT NULL,
  `disbursement_status` varchar(20) DEFAULT NULL,
  `account_holder` varchar(100) DEFAULT NULL,
  `bank_code` varchar(30) DEFAULT NULL,
  `account_number` varchar(30) DEFAULT NULL,
  `recipient_name` varchar(100) DEFAULT NULL,
  `sender_bank` varchar(30) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `receipt` varchar(255) DEFAULT NULL,
  `time_served` timestamp NULL DEFAULT NULL,
  `fee` int(11) DEFAULT NULL,
  `beneficiary_email` varchar(150) DEFAULT NULL,
  `idempotency_key` varchar(100) DEFAULT NULL,
  `direction` varchar(30) DEFAULT NULL,
  `is_virtual_account` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `commission_payments`
--

INSERT INTO `commission_payments` (`id`, `commission_id`, `tanggal_bayar`, `jumlah`, `flip_disbursement_id`, `disbursement_status`, `account_holder`, `bank_code`, `account_number`, `recipient_name`, `sender_bank`, `remark`, `receipt`, `time_served`, `fee`, `beneficiary_email`, `idempotency_key`, `direction`, `is_virtual_account`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-06-19', 102271.04, '308309', 'pending', 'Yudi Pratama', 'bri', '9876543210', 'Yudi Pratama', NULL, 'Komisi 2026-06', NULL, NULL, 1998, 'yudi@gmail.com', 'commission-1-2026-06-102271.04-1781874218', NULL, 0, '2026-06-19 13:03:40', '2026-06-19 13:03:40'),
(2, 2, '2026-06-19', 1355000.00, '308311', 'pending', 'Huda Kurniawan', 'bri', '67812567642', 'Huda Kurniawan', NULL, 'Komisi 2026-06', NULL, NULL, 1998, 'huda@gmail.com', 'commission-2-2026-06-1355000.00-1781877270', NULL, 0, '2026-06-19 13:54:31', '2026-06-19 13:54:31'),
(3, 3, '2026-06-19', 615000.00, '308312', 'completed', 'Dummy Name', 'bri', '08762556123', 'Dummy Name', NULL, 'Komisi 2026-06', 'https://flip-receipt.oss-ap-southeast-5.aliyuncs.com/debit_receipt/', '2026-06-19 14:15:01', 1998, 'rizki@gmail.com', 'commission-3-2026-06-615000.00-1781878489', 'DOMESTIC_TRANSFER', 0, '2026-06-19 14:14:50', '2026-06-19 14:15:02'),
(4, 4, '2026-06-25', 550000.00, '309936', 'pending', 'Pandu', 'bsm', '09989128', 'Pandu', NULL, 'Komisi 2026-06', NULL, NULL, 1998, 'pandu@gmail.com', 'commission-4-2026-06-550000.00-1782395326', NULL, 0, '2026-06-25 13:48:48', '2026-06-25 13:48:48'),
(5, 5, '2026-06-25', 110000.00, '309939', 'completed', '', 'kalimantan_tengah', '81929791297', '', NULL, 'Komisi 2026-06', 'https://flip-receipt.oss-ap-southeast-5.aliyuncs.com/debit_receipt/', '2026-06-25 15:31:57', 1998, 'suroso@gmail.com', 'commission-5-2026-06-110000.00-1782400400', 'DOMESTIC_TRANSFER', 0, '2026-06-25 15:13:22', '2026-06-25 15:31:57'),
(6, 6, '2026-06-25', 38500.00, '309940', 'completed', '', 'permata', '65777756', '', NULL, 'Komisi 2026-06', 'https://flip-receipt.oss-ap-southeast-5.aliyuncs.com/debit_receipt/', '2026-06-25 15:47:01', 1998, 'loko@gmail.com', 'commission-6-2026-06-38500.00-1782402408', 'DOMESTIC_TRANSFER', 0, '2026-06-25 15:46:49', '2026-06-25 15:47:01'),
(7, 7, '2026-06-29', 77000.00, '310115', 'pending', 'ahmad', 'panin', '1029109209', 'ahmad', NULL, 'Komisi 2026-06', NULL, NULL, 1998, 'ahmad@gmail.com', 'commission-7-2026-06-77000.00-1782737238', NULL, 0, '2026-06-29 12:47:21', '2026-06-29 12:47:21'),
(8, 8, '2026-07-01', 180000.00, '310618', 'completed', 'Dummy Name', 'ocbc', '8918291728', NULL, NULL, 'Komisi 2026-07', 'https://flip-receipt.oss-ap-southeast-5.aliyuncs.com/debit_receipt/', '2026-07-01 13:19:21', 1998, NULL, 'commission-8-1782911950', NULL, 0, '2026-07-01 13:19:12', '2026-07-01 13:19:21'),
(9, 9, '2026-07-01', 150000.00, '310619', 'pending', NULL, 'anz', '1212132323', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'commission-9-1782922183', NULL, 0, '2026-07-01 16:09:44', '2026-07-01 16:09:44'),
(10, 10, '2026-07-01', 220000.00, '310620', 'completed', 'Dummy Name', 'tokyo', '91829792', NULL, NULL, 'Komisi 2026-07', 'https://flip-receipt.oss-ap-southeast-5.aliyuncs.com/debit_receipt/', '2026-07-01 16:32:42', 1998, NULL, 'commission-10-1782923552', NULL, 0, '2026-07-01 16:32:33', '2026-07-01 16:32:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `nama_customer`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', '081234567890', 'Madiun', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(2, 'Andi Saputra', '081298765432', 'Ponorogo', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(3, 'Siti Rahayu', '085712345678', 'Ngawi', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(4, 'Dwi Lestari', '089876543210', 'Magetan', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(7, 'Yonooo', '0856152786817297', 'Madiun', '2026-06-29 04:27:29', '2026-06-29 04:27:29'),
(8, 'Yanto anjay', '081324546276', 'Mejayan', '2026-06-29 12:44:37', '2026-06-29 12:44:37');

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
(1, '2026_05_24_160140_create_users_table', 1),
(2, '2026_05_24_160141_create_products_table', 1),
(3, '2026_05_24_160142_create_customers_table', 1),
(4, '2026_05_24_160143_create_targets_table', 1),
(5, '2026_05_24_160144_create_sales_transactions_table', 1),
(6, '2026_05_24_160145_create_sales_details_table', 1),
(7, '2026_05_24_160146_create_commissions_table', 1),
(8, '2026_05_24_160147_create_commission_payments_table', 1),
(9, '2026_05_24_160148_create_payment_logs_table', 1),
(10, '2026_05_24_161722_create_sessions_table', 1),
(11, '2026_05_24_161808_create_personal_access_tokens_table', 1),
(12, '2026_05_24_161837_create_view_total_penjualan', 1),
(13, '2026_06_18_000000_refactor_remove_redundant_columns', 2),
(14, '2026_06_18_000001_merge_commission_payments_with_flip_disbursement', 2),
(15, '2026_05_26_213900_add_missing_columns_to_commission_payments', 3),
(16, '2026_06_18_000000_add_total_harga_to_sales_transactions_table', 3),
(17, '2026_06_18_000001_backfill_total_harga_for_sales_transactions', 4),
(18, '2026_06_18_000002_add_harga_to_sales_details_table', 5),
(19, '2026_06_19_000001_sync_commission_payments_flip_columns', 6),
(20, '2026_06_19_000002_create_jobs_table', 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commission_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(30) NOT NULL,
  `transaction_status` varchar(20) NOT NULL,
  `payload` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payment_logs`
--

INSERT INTO `payment_logs` (`id`, `commission_id`, `order_id`, `transaction_status`, `payload`, `created_at`, `updated_at`) VALUES
(1, 3, '308312', 'completed', '{\"id\":308312,\"user_id\":100015584,\"amount\":615000,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-19 21:14:50\",\"bank_code\":\"bri\",\"account_number\":\"08762556123\",\"recipient_name\":\"Dummy Name\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-19 21:15:01\",\"bundle_id\":0,\"company_id\":95401,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"rizki@gmail.com\",\"idempotency_key\":\"commission-3-2026-06-615000.00-1781878489\",\"is_virtual_account\":false}', '2026-06-19 14:15:02', '2026-06-19 14:15:02'),
(2, 5, '309939', 'completed', '{\"id\":309939,\"user_id\":100015583,\"amount\":110000,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:13:23\",\"bank_code\":\"kalimantan_tengah\",\"account_number\":\"81929791297\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:31:57\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"suroso@gmail.com\",\"idempotency_key\":\"commission-5-2026-06-110000.00-1782400400\",\"is_virtual_account\":false}', '2026-06-25 15:31:58', '2026-06-25 15:31:58'),
(3, 5, '309939', 'completed', '{\"id\":309939,\"user_id\":100015583,\"amount\":110000,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:13:23\",\"bank_code\":\"kalimantan_tengah\",\"account_number\":\"81929791297\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:31:57\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"suroso@gmail.com\",\"idempotency_key\":\"commission-5-2026-06-110000.00-1782400400\",\"is_virtual_account\":false}', '2026-06-25 15:39:10', '2026-06-25 15:39:10'),
(4, 5, '309939', 'completed', '{\"id\":309939,\"user_id\":100015583,\"amount\":110000,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:13:23\",\"bank_code\":\"kalimantan_tengah\",\"account_number\":\"81929791297\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:31:57\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"suroso@gmail.com\",\"idempotency_key\":\"commission-5-2026-06-110000.00-1782400400\",\"is_virtual_account\":false}', '2026-06-25 15:40:16', '2026-06-25 15:40:16'),
(5, 5, '309939', 'completed', '{\"id\":309939,\"user_id\":100015583,\"amount\":110000,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:13:23\",\"bank_code\":\"kalimantan_tengah\",\"account_number\":\"81929791297\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:31:57\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"suroso@gmail.com\",\"idempotency_key\":\"commission-5-2026-06-110000.00-1782400400\",\"is_virtual_account\":false}', '2026-06-25 15:44:05', '2026-06-25 15:44:05'),
(6, 6, '309940', 'completed', '{\"id\":309940,\"user_id\":100015583,\"amount\":38500,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:46:50\",\"bank_code\":\"permata\",\"account_number\":\"65777756\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:47:01\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"loko@gmail.com\",\"idempotency_key\":\"commission-6-2026-06-38500.00-1782402408\",\"is_virtual_account\":false}', '2026-06-25 15:47:01', '2026-06-25 15:47:01'),
(7, 6, '309940', 'completed', '{\"id\":309940,\"user_id\":100015583,\"amount\":38500,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:46:50\",\"bank_code\":\"permata\",\"account_number\":\"65777756\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:47:01\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"loko@gmail.com\",\"idempotency_key\":\"commission-6-2026-06-38500.00-1782402408\",\"is_virtual_account\":false}', '2026-06-25 15:52:12', '2026-06-25 15:52:12'),
(8, 6, '309940', 'completed', '{\"id\":309940,\"user_id\":100015583,\"amount\":38500,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:46:50\",\"bank_code\":\"permata\",\"account_number\":\"65777756\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:47:01\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"loko@gmail.com\",\"idempotency_key\":\"commission-6-2026-06-38500.00-1782402408\",\"is_virtual_account\":false}', '2026-06-25 15:52:22', '2026-06-25 15:52:22'),
(9, 6, '309940', 'completed', '{\"id\":309940,\"user_id\":100015583,\"amount\":38500,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:46:50\",\"bank_code\":\"permata\",\"account_number\":\"65777756\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:47:01\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"loko@gmail.com\",\"idempotency_key\":\"commission-6-2026-06-38500.00-1782402408\",\"is_virtual_account\":false}', '2026-06-25 15:53:15', '2026-06-25 15:53:15'),
(10, 6, '309940', 'completed', '{\"id\":309940,\"user_id\":100015583,\"amount\":38500,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:46:50\",\"bank_code\":\"permata\",\"account_number\":\"65777756\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:47:01\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"loko@gmail.com\",\"idempotency_key\":\"commission-6-2026-06-38500.00-1782402408\",\"is_virtual_account\":false}', '2026-06-25 15:56:34', '2026-06-25 15:56:34'),
(11, 6, '309940', 'completed', '{\"id\":309940,\"user_id\":100015583,\"amount\":38500,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:46:50\",\"bank_code\":\"permata\",\"account_number\":\"65777756\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:47:01\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"loko@gmail.com\",\"idempotency_key\":\"commission-6-2026-06-38500.00-1782402408\",\"is_virtual_account\":false}', '2026-06-25 15:56:49', '2026-06-25 15:56:49'),
(12, 6, '309940', 'completed', '{\"id\":309940,\"user_id\":100015583,\"amount\":38500,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:46:50\",\"bank_code\":\"permata\",\"account_number\":\"65777756\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:47:01\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"loko@gmail.com\",\"idempotency_key\":\"commission-6-2026-06-38500.00-1782402408\",\"is_virtual_account\":false}', '2026-06-25 15:59:53', '2026-06-25 15:59:53'),
(13, 6, '309940', 'completed', '{\"id\":309940,\"user_id\":100015583,\"amount\":38500,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:46:50\",\"bank_code\":\"permata\",\"account_number\":\"65777756\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:47:01\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"loko@gmail.com\",\"idempotency_key\":\"commission-6-2026-06-38500.00-1782402408\",\"is_virtual_account\":false}', '2026-06-25 16:03:06', '2026-06-25 16:03:06'),
(14, 5, '309939', 'completed', '{\"id\":309939,\"user_id\":100015583,\"amount\":110000,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:13:23\",\"bank_code\":\"kalimantan_tengah\",\"account_number\":\"81929791297\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:31:57\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"suroso@gmail.com\",\"idempotency_key\":\"commission-5-2026-06-110000.00-1782400400\",\"is_virtual_account\":false}', '2026-06-25 16:03:14', '2026-06-25 16:03:14'),
(15, 5, '309939', 'completed', '{\"id\":309939,\"user_id\":100015583,\"amount\":110000,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:13:23\",\"bank_code\":\"kalimantan_tengah\",\"account_number\":\"81929791297\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:31:57\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"suroso@gmail.com\",\"idempotency_key\":\"commission-5-2026-06-110000.00-1782400400\",\"is_virtual_account\":false}', '2026-06-25 16:10:44', '2026-06-25 16:10:44'),
(16, 5, '309939', 'completed', '{\"id\":309939,\"user_id\":100015583,\"amount\":110000,\"status\":\"DONE\",\"reason\":\"\",\"timestamp\":\"2026-06-25 22:13:23\",\"bank_code\":\"kalimantan_tengah\",\"account_number\":\"81929791297\",\"recipient_name\":\"\",\"sender_bank\":null,\"remark\":\"Komisi 2026-06\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/\",\"time_served\":\"2026-06-25 22:31:57\",\"bundle_id\":0,\"company_id\":95400,\"recipient_city\":391,\"created_from\":\"API\",\"direction\":\"DOMESTIC_TRANSFER\",\"sender\":null,\"fee\":1998,\"beneficiary_email\":\"suroso@gmail.com\",\"idempotency_key\":\"commission-5-2026-06-110000.00-1782400400\",\"is_virtual_account\":false}', '2026-06-25 16:11:01', '2026-06-25 16:11:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(100) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, 'auth_token', '42bb0a3963007788eea883386fafb84850c1fc45e9926f43ef977463ea3a4cb0', '[\"*\"]', NULL, NULL, '2026-06-22 14:44:16', '2026-06-22 14:44:16'),
(3, 'App\\Models\\User', 2, 'auth_token', '33655278dbba12f53a1031fc51d3a7e8b38c9064d3f8a2c41c84fcd0e4ee5a16', '[\"*\"]', '2026-06-22 16:16:33', NULL, '2026-06-22 15:03:05', '2026-06-22 16:16:33'),
(5, 'App\\Models\\User', 1, 'auth_token', '5a453de14e96f87f32232dd61e3b6bb583088c3fb5d81430163bf0cdb48e14eb', '[\"*\"]', NULL, NULL, '2026-06-22 16:14:26', '2026-06-22 16:14:26'),
(7, 'App\\Models\\User', 7, 'auth_token', '6abdeb6312f7ea9f7d4a0d584d9816fe95cfcb43fc457b01e5c4a2beaa9c320c', '[\"*\"]', '2026-06-25 15:08:25', NULL, '2026-06-25 15:08:23', '2026-06-25 15:08:25'),
(8, 'App\\Models\\User', 2, 'auth_token', 'b8e28afc08f83c142ee4d3391767ef50e818790cc59ff9191c91f8cc96390313', '[\"*\"]', '2026-06-29 04:27:29', NULL, '2026-06-29 03:45:59', '2026-06-29 04:27:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `nama_produk`, `harga`, `created_at`, `updated_at`) VALUES
(1, 'Kulkas LG', 12000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(2, 'Mesin Cuci Panasonic', 18000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(3, 'TV Samsung', 7500000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(4, 'AC Sharp 1PK', 5500000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_details`
--

CREATE TABLE `sales_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `harga` decimal(15,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sales_details`
--

INSERT INTO `sales_details` (`id`, `transaction_id`, `product_id`, `jumlah`, `harga`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 12000000.00, 12000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(2, 2, 3, 1, 7500000.00, 7500000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(3, 3, 4, 1, 5500000.00, 5500000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(4, 4, 2, 1, 18000000.00, 18000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(5, 5, 3, 2, 7500000.00, 15000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(6, 6, 4, 1, 5500000.00, 5500000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(7, 7, 2, 1, 18000000.00, 18000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(8, 8, 1, 1, 12000000.00, 12000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(9, 9, 3, 1, 7500000.00, 7500000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(10, 10, 4, 2, 5500000.00, 11000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(11, 11, 3, 1, 7500000.00, 7500000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(12, 12, 1, 1, 12000000.00, 12000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(13, 13, 2, 1, 18000000.00, 18000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(14, 14, 4, 2, 5500000.00, 11000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(15, 15, 3, 1, 7500000.00, 7500000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(16, 15, 4, 1, 5500000.00, 5500000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(17, 16, 4, 5, 5500000.00, 27500000.00, '2026-06-18 03:16:27', '2026-06-18 03:16:27'),
(18, 16, 2, 3, 18000000.00, 54000000.00, '2026-06-18 03:16:27', '2026-06-18 03:16:27'),
(19, 17, 4, 10, 5500000.00, 55000000.00, '2026-06-25 13:48:17', '2026-06-25 13:48:17'),
(21, 19, 4, 2, 5500000.00, 11000000.00, '2026-06-25 15:12:58', '2026-06-25 15:12:58'),
(22, 20, 4, 1, 5500000.00, 5500000.00, '2026-06-25 15:46:31', '2026-06-25 15:46:31'),
(23, 21, 4, 2, 5500000.00, 11000000.00, '2026-06-29 12:45:01', '2026-06-29 12:45:01'),
(24, 22, 4, 1, 5500000.00, 5500000.00, '2026-06-29 13:12:39', '2026-06-29 13:12:39'),
(25, 22, 1, 1, 12000000.00, 12000000.00, '2026-06-29 13:12:39', '2026-06-29 13:12:39'),
(26, 22, 2, 1, 18000000.00, 18000000.00, '2026-06-29 13:12:39', '2026-06-29 13:12:39'),
(27, 23, 2, 1, 18000000.00, 18000000.00, '2026-07-01 13:18:35', '2026-07-01 13:18:35'),
(28, 24, 3, 2, 7500000.00, 15000000.00, '2026-07-01 16:08:58', '2026-07-01 16:08:58'),
(29, 25, 4, 4, 5500000.00, 22000000.00, '2026-07-01 16:32:11', '2026-07-01 16:32:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_transactions`
--

CREATE TABLE `sales_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `total_harga` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tanggal_transaksi` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sales_transactions`
--

INSERT INTO `sales_transactions` (`id`, `user_id`, `customer_id`, `total_harga`, `tanggal_transaksi`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 12000000.00, '2026-06-16', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(2, 2, 3, 7500000.00, '2026-06-06', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(3, 2, 2, 5500000.00, '2026-06-01', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(4, 2, 4, 18000000.00, '2026-06-09', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(5, 2, 2, 15000000.00, '2026-06-01', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(6, 3, 3, 5500000.00, '2026-06-01', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(7, 3, 1, 18000000.00, '2026-06-13', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(8, 3, 1, 12000000.00, '2026-06-11', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(9, 3, 2, 7500000.00, '2026-06-15', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(10, 3, 1, 11000000.00, '2026-06-14', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(11, 4, 2, 7500000.00, '2026-06-04', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(12, 4, 4, 12000000.00, '2026-06-08', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(13, 4, 2, 18000000.00, '2026-06-14', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(14, 4, 1, 11000000.00, '2026-06-18', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(15, 4, 3, 13000000.00, '2026-06-05', '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(16, 3, 3, 81500000.00, '2026-06-18', '2026-06-18 03:16:27', '2026-06-18 03:16:27'),
(17, 6, 2, 55000000.00, '2026-06-25', '2026-06-25 13:48:17', '2026-06-25 13:48:17'),
(19, 7, 1, 11000000.00, '2026-06-25', '2026-06-25 15:12:58', '2026-06-25 15:12:58'),
(20, 8, 2, 5500000.00, '2026-06-25', '2026-06-25 15:46:31', '2026-06-25 15:46:31'),
(21, 9, 8, 11000000.00, '2026-06-29', '2026-06-29 12:45:01', '2026-06-29 12:45:01'),
(22, 9, 2, 35500000.00, '2026-06-29', '2026-06-29 13:12:39', '2026-06-29 13:12:39'),
(23, 10, 7, 18000000.00, '2026-07-01', '2026-07-01 13:18:35', '2026-07-01 13:18:35'),
(24, 11, 2, 15000000.00, '2026-07-01', '2026-07-01 16:08:58', '2026-07-01 16:08:58'),
(25, 12, 1, 22000000.00, '2026-07-01', '2026-07-01 16:32:11', '2026-07-01 16:32:11');

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `targets`
--

CREATE TABLE `targets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `periode` char(7) NOT NULL,
  `target_nominal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `targets`
--

INSERT INTO `targets` (`id`, `user_id`, `periode`, `target_nominal`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-06', 50000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(2, 3, '2026-06', 50000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(3, 4, '2026-06', 50000000.00, '2026-06-17 11:57:34', '2026-06-17 11:57:34'),
(4, 6, '2026-06', 20000000.00, '2026-06-18 03:22:31', '2026-06-18 03:22:31'),
(5, 7, '2026-06', 10000000.00, '2026-06-25 15:12:43', '2026-06-25 15:12:43'),
(6, 8, '2026-06', 7000000.00, '2026-06-25 15:45:33', '2026-06-25 15:45:33'),
(7, 9, '2026-06', 20000000.00, '2026-06-29 12:44:11', '2026-06-29 12:44:11'),
(8, 10, '2026-07', 15000000.00, '2026-07-01 13:18:01', '2026-07-01 13:18:01'),
(9, 11, '2026-07', 12000000.00, '2026-07-01 16:08:22', '2026-07-01 16:08:22'),
(10, 12, '2026-07', 20000000.00, '2026-07-01 16:31:42', '2026-07-01 16:31:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','sales') NOT NULL DEFAULT 'sales',
  `nama_rekening` varchar(100) DEFAULT NULL,
  `nomor_rekening` varchar(30) DEFAULT NULL,
  `bank` varchar(50) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `nama_rekening`, `nomor_rekening`, `bank`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$12$bk2RL37zsXT0EKPzqaVJVOLSlolkA4RPkiIk53ae5YmgwHVfs4Vju', 'admin', NULL, NULL, NULL, 'Xwc0yiENtUDaDlurRIiNvDPIUvqifZhcMe6gVwqD0rgdYQyj9RXUiSnEY3ko', '2026-06-17 11:57:33', '2026-06-17 11:57:33'),
(2, 'Yudi', 'yudi@gmail.com', '$2y$12$iZwb3Ol9uwVcHhc49EAX7egKVaZO08dLzE1WGo6Pl88g77YkB3OUu', 'sales', 'Yudi Pratama', '9876543210', 'BRI', NULL, '2026-06-17 11:57:33', '2026-06-17 12:07:32'),
(3, 'Huda', 'huda@gmail.com', '$2y$12$ghfiGaZNUCkA6q/MgBZEye1gAvBIdHP7QZJ6wVf0IYhFk8iwFo8WC', 'sales', 'Huda Kurniawan', '67812567642', 'BRI', NULL, '2026-06-17 11:57:33', '2026-06-17 12:07:45'),
(4, 'Rizki', 'rizki@gmail.com', '$2y$12$sktJH9S7cpUkNGzu4tvcROuUuJHjTX.UUn6TSIrdsWKlg5vtp2KHK', 'sales', 'Rizki Aditya', '08762556123', 'BRI', NULL, '2026-06-17 11:57:34', '2026-06-17 12:08:07'),
(6, 'Pandu', 'pandu@gmail.com', '$2y$12$9hJ0Q4iPHdZphQVPzqpjZOGXE7mLRJyFvlpikb7XR66BJz1E9H/dK', 'sales', 'Pandu', '09989128', 'BSI (Bank Syariah Indonesia)', NULL, '2026-06-18 03:22:17', '2026-06-18 03:22:17'),
(7, 'Suroso', 'suroso@gmail.com', '$2y$12$FRLWDVDW4gxjPQanPu2/megFOPJ9kio5yzbVfp5WplkW0NZVsxNdW', 'sales', 'Suroso', '81929791297', 'Bank Kalteng', NULL, '2026-06-25 15:01:25', '2026-06-25 15:01:25'),
(8, 'loko', 'loko@gmail.com', '$2y$12$gDDnU9WarZqSWdHvM0OUuue1Tkdw1m1eR23sy3PutW/uizyX2tGOm', 'sales', 'loko', '65777756', 'Bank Permata', NULL, '2026-06-25 15:45:14', '2026-06-25 15:45:14'),
(9, 'Ahmad', 'ahmad@gmail.com', '$2y$12$SQD5AbRVt.9OQABcGMEym.8JcF0Mo.rcPJ7EAHbYd2iGwV62Micx6', 'sales', 'ahmad', '1029109209', 'Panin Bank', 'AQuuWu338c2goWn0HexEJ3uEOVXqsyBZEO5P0Youq37enNiEBj7GxPi5tYBM', '2026-06-29 12:43:15', '2026-06-29 12:43:15'),
(10, 'roni', 'roni@gmail.com', '$2y$12$hy4cwPUqiP0yBYnaz3tMeOITUmABalJY.qZfnT1hwjdV8kFviM0U.', 'sales', 'roni', '8918291728', 'OCBC NISP', NULL, '2026-07-01 13:17:34', '2026-07-01 13:17:34'),
(11, 'yare', 'yare@gmail.com', '$2y$12$8kS3IRiDGlzdQ0w17DntFe6dnjqEzf2s4URL.IlrcVyg/UQlGbHLi', 'sales', 'yare', '1212132323', 'ANZ Indonesia', NULL, '2026-07-01 16:07:50', '2026-07-01 16:07:50'),
(12, 'yuli', 'yuli@gmail.com', '$2y$12$ZHX3SLfNd6s3f3S1sD4eoOLtUuuiAaz0uJk2mytWO3ID67v8Obhcq', 'sales', 'yuli', '91829792', 'Bank of Tokyo Mitsubishi UFJ', NULL, '2026-07-01 16:25:33', '2026-07-01 16:25:33');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_total_penjualan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_total_penjualan` (
`user_id` bigint(20) unsigned
,`periode` varchar(7)
,`total_penjualan` decimal(37,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_total_per_transaksi`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_total_per_transaksi` (
`transaction_id` bigint(20) unsigned
,`user_id` bigint(20) unsigned
,`customer_id` bigint(20) unsigned
,`tanggal_transaksi` date
,`total_harga` decimal(37,2)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_total_penjualan`
--
DROP TABLE IF EXISTS `v_total_penjualan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_total_penjualan`  AS SELECT `st`.`user_id` AS `user_id`, date_format(`st`.`tanggal_transaksi`,'%Y-%m') AS `periode`, sum(`sd`.`subtotal`) AS `total_penjualan` FROM (`sales_transactions` `st` join `sales_details` `sd` on(`sd`.`transaction_id` = `st`.`id`)) GROUP BY `st`.`user_id`, date_format(`st`.`tanggal_transaksi`,'%Y-%m') ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_total_per_transaksi`
--
DROP TABLE IF EXISTS `v_total_per_transaksi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_total_per_transaksi`  AS SELECT `st`.`id` AS `transaction_id`, `st`.`user_id` AS `user_id`, `st`.`customer_id` AS `customer_id`, `st`.`tanggal_transaksi` AS `tanggal_transaksi`, sum(`sd`.`subtotal`) AS `total_harga` FROM (`sales_transactions` `st` join `sales_details` `sd` on(`sd`.`transaction_id` = `st`.`id`)) GROUP BY `st`.`id`, `st`.`user_id`, `st`.`customer_id`, `st`.`tanggal_transaksi` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commissions_user_id_foreign` (`user_id`),
  ADD KEY `idx_commissions_periode` (`periode`);

--
-- Indeks untuk tabel `commission_payments`
--
ALTER TABLE `commission_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commission_payments_idempotency_key_unique` (`idempotency_key`),
  ADD KEY `commission_payments_commission_id_foreign` (`commission_id`),
  ADD KEY `idx_disbursement_status` (`disbursement_status`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_logs_commission_id_foreign` (`commission_id`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sales_details`
--
ALTER TABLE `sales_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `sales_details_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `sales_transactions`
--
ALTER TABLE `sales_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_transactions_user_id_foreign` (`user_id`),
  ADD KEY `sales_transactions_customer_id_foreign` (`customer_id`),
  ADD KEY `idx_tanggal_transaksi` (`tanggal_transaksi`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `targets`
--
ALTER TABLE `targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `targets_user_id_foreign` (`user_id`),
  ADD KEY `idx_targets_periode` (`periode`);

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
-- AUTO_INCREMENT untuk tabel `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `commission_payments`
--
ALTER TABLE `commission_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `sales_details`
--
ALTER TABLE `sales_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `sales_transactions`
--
ALTER TABLE `sales_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `targets`
--
ALTER TABLE `targets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `commission_payments`
--
ALTER TABLE `commission_payments`
  ADD CONSTRAINT `commission_payments_commission_id_foreign` FOREIGN KEY (`commission_id`) REFERENCES `commissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD CONSTRAINT `payment_logs_commission_id_foreign` FOREIGN KEY (`commission_id`) REFERENCES `commissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sales_details`
--
ALTER TABLE `sales_details`
  ADD CONSTRAINT `sales_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `sales_transactions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sales_transactions`
--
ALTER TABLE `sales_transactions`
  ADD CONSTRAINT `sales_transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `targets`
--
ALTER TABLE `targets`
  ADD CONSTRAINT `targets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
