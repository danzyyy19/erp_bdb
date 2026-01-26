-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 24, 2026 at 09:55 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u172281706_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('bahan_baku','packaging','barang_jadi') NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `type`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bahan Baku', 'bahan-baku', 'bahan_baku', 'Bahan baku untuk produksi cat', 1, '2025-12-27 14:57:00', '2025-12-27 14:57:00'),
(2, 'Material/Packaging', 'packaging', 'packaging', 'Material dan packaging produksi', 1, '2025-12-27 14:57:00', '2025-12-27 14:57:00'),
(3, 'Barang Jadi', 'barang-jadi', 'barang_jadi', 'Produk jadi siap jual', 1, '2025-12-27 14:57:00', '2025-12-27 14:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `code`, `uuid`, `name`, `email`, `phone`, `address`, `company`, `is_active`, `created_at`, `updated_at`) VALUES
(11, 'RSPD', 'c462211b-9242-4167-b6aa-b7ff9985ebe1', 'PAK RUSPANDI', NULL, NULL, 'Cikarang Pusat Jawa Barat', 'PT. NAF TEKNIK UTAMA', 1, '2025-12-27 16:53:59', '2025-12-29 18:40:19'),
(12, 'AGS', '6ae084a2-e00b-4eca-a2a6-7cca04d30fc4', 'PAK ANDRI AGASI', NULL, NULL, 'Bogor Barat', NULL, 1, '2025-12-29 18:41:21', '2025-12-29 18:41:21'),
(13, 'AII', 'd5649685-6a66-4408-8db0-71b8dd768f9f', 'PT ARTIMA INDUSTRI INDONESIA', NULL, NULL, 'Kawasan Industri Jatake', 'PT ARTIMA INDUSTRI INDONESIA', 1, '2025-12-29 18:42:49', '2025-12-29 18:42:49'),
(14, 'EKO', '4c4d626c-041c-4d6d-8006-34c74c927b16', 'PAK EKO', NULL, '087884859326', 'RLI POLIMER TEKNOLOGI\r\nBABAKAN SENTRAL RT008, RW05,SUKAPURA,KIARACONDONG,KOTA BANDUNG,JAWA BARAT', NULL, 1, '2025-12-30 09:58:16', '2025-12-30 09:58:16'),
(15, 'YYK', 'b88a2ba5-fd51-4200-91bc-d3ab80a491cb', 'PAK YOYOK', NULL, NULL, NULL, 'APLIKATOR', 1, '2025-12-31 15:24:53', '2025-12-31 15:24:53'),
(16, 'STR', '68d9b1bb-a853-4ff5-bac5-694534833030', 'SUTRISNO', NULL, NULL, NULL, NULL, 1, '2025-12-31 15:36:03', '2025-12-31 15:36:03'),
(17, 'PFR', '03ff730f-864a-428b-b6b3-e8fc9d877868', 'PAK FAJAR RAMADHAN', NULL, '02155775291', 'RUKO AZOROES BLOK B17A NO29 CIPONDOH TANGERANG', NULL, 1, '2026-01-05 11:02:28', '2026-01-05 11:02:28');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_notes`
--

CREATE TABLE `delivery_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `sj_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `spk_id` bigint(20) UNSIGNED DEFAULT NULL,
  `special_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_date` date NOT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `vehicle_number` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `delivery_address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','in_transit','delivered','returned') DEFAULT 'pending',
  `delivered_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_notes`
--

INSERT INTO `delivery_notes` (`id`, `uuid`, `sj_number`, `customer_id`, `spk_id`, `special_order_id`, `invoice_id`, `created_by`, `approved_by`, `delivery_date`, `driver_name`, `vehicle_number`, `recipient_name`, `delivery_address`, `notes`, `status`, `delivered_at`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, '927fcd57-eed9-4c5f-8cd5-630592fc762f', 'SJ/001/BDB/XII/25', 11, 3, 1, NULL, 4, 1, '2025-12-27', NULL, NULL, NULL, 'asfasf', NULL, 'approved', NULL, '2025-12-27 18:45:40', '2025-12-27 18:45:21', '2025-12-27 18:45:40'),
(2, '74e4b812-44b3-4019-acd4-7e48379f44ce', 'SJ/002/BDB/XII/25', 15, 7, 5, 1, 1, 1, '2025-12-31', 'PAK AHMAD', NULL, 'PAK YOYOK', 'PANDEGLANG', NULL, 'delivered', '2025-12-31 16:27:47', '2025-12-31 16:26:55', '2025-12-31 16:26:45', '2025-12-31 16:28:35'),
(3, 'a7a0578a-60f5-4a38-9b6c-e77375e7ddd2', 'SJ/003/BDB/XII/25', 16, 5, 2, 2, 1, 1, '2025-12-30', NULL, NULL, 'PAK SUTRISNO', 'BEKASI', NULL, 'delivered', '2025-12-31 16:32:18', '2025-12-31 16:32:13', '2025-12-31 16:32:06', '2025-12-31 16:32:51'),
(4, '289a70af-0771-4d7a-ad43-73a31486f665', 'SJ/004/BDB/XII/25', 16, 6, 3, 3, 1, 1, '2025-12-31', NULL, NULL, 'PAK SUTRISNO', 'BEKASI', NULL, 'delivered', '2025-12-31 16:33:32', '2025-12-31 16:33:25', '2025-12-31 16:33:21', '2025-12-31 16:34:01'),
(5, 'a51c1d98-c17e-47a2-aba5-bb26dc18d24c', 'SJ/005/BDB/XII/25', 12, 8, 6, 4, 1, 1, '2025-12-31', 'SAPTA', NULL, 'BAPAK ANDRI AGASI', 'BOGOR', NULL, 'delivered', '2025-12-31 16:56:38', '2025-12-31 16:56:33', '2025-12-31 16:56:26', '2025-12-31 16:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_note_items`
--

CREATE TABLE `delivery_note_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_note_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_note_items`
--

INSERT INTO `delivery_note_items` (`id`, `delivery_note_id`, `product_id`, `quantity`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 262, 25.00, 'kg', NULL, '2025-12-27 18:45:21', '2025-12-27 18:45:21'),
(2, 2, 314, 2.00, 'PAIL', NULL, '2025-12-31 16:26:45', '2025-12-31 16:26:45'),
(3, 2, 315, 2.00, 'PAIL', NULL, '2025-12-31 16:26:45', '2025-12-31 16:26:45'),
(4, 3, 312, 100.00, 'KG', NULL, '2025-12-31 16:32:06', '2025-12-31 16:32:06'),
(5, 4, 312, 50.00, 'KG', NULL, '2025-12-31 16:33:21', '2025-12-31 16:33:21'),
(6, 5, 327, 5.00, 'PAIL', NULL, '2025-12-31 16:56:26', '2025-12-31 16:56:26'),
(7, 5, 329, 5.00, 'PAIL', NULL, '2025-12-31 16:56:26', '2025-12-31 16:56:26'),
(8, 5, 328, 1.00, 'PAIL', NULL, '2025-12-31 16:56:26', '2025-12-31 16:56:26');

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
-- Table structure for table `fpb`
--

CREATE TABLE `fpb` (
  `id` char(36) NOT NULL,
  `fpb_number` varchar(255) NOT NULL,
  `spk_id` bigint(20) UNSIGNED DEFAULT NULL,
  `special_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `request_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fpb`
--

INSERT INTO `fpb` (`id`, `fpb_number`, `spk_id`, `special_order_id`, `created_by`, `approved_by`, `status`, `request_date`, `notes`, `approved_at`, `created_at`, `updated_at`) VALUES
('019b5f33-cf73-72ee-8d8d-b0b569691f00', 'FPB/001/BDB/XII/25', 2, NULL, 4, 1, 'approved', '2025-12-27', NULL, '2025-12-27 16:46:43', '2025-12-27 16:46:29', '2025-12-27 16:46:43'),
('019b5f93-940d-715d-b06e-938eb084d65d', 'FPB/002/BDB/XII/25', 3, 1, 1, 1, 'approved', '2025-12-27', NULL, '2025-12-27 18:31:13', '2025-12-27 18:31:05', '2025-12-27 18:31:13'),
('019b6d0f-ea52-70f5-a9bc-d6c2c5433f38', 'FPB/003/BDB/XII/25', 4, NULL, 4, 1, 'rejected', '2025-12-30', NULL, '2025-12-30 09:27:18', '2025-12-30 09:21:58', '2025-12-30 09:27:18'),
('019b6d16-d89c-71c5-8094-de6f86ec8561', 'FPB/004/BDB/XII/25', 1, NULL, 4, 1, 'approved', '2025-12-30', NULL, '2025-12-30 09:30:27', '2025-12-30 09:29:32', '2025-12-30 09:30:27'),
('019b7395-58e9-723b-b587-9152bac75f64', 'FPB/005/BDB/XII/25', 5, 2, 1, 1, 'approved', '2025-12-30', NULL, '2025-12-31 15:45:40', '2025-12-31 15:45:26', '2025-12-31 15:45:40'),
('019b7398-de47-71d5-acb1-e2db8abe21f0', 'FPB/006/BDB/XII/25', 6, 3, 1, 1, 'approved', '2025-12-31', NULL, '2025-12-31 15:49:26', '2025-12-31 15:49:16', '2025-12-31 15:49:26'),
('019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'FPB/007/BDB/XII/25', 7, 5, 1, 1, 'approved', '2025-12-30', 'NOT FORMULASI EPOXY SL GREEN PASTEL 3:1', '2025-12-31 16:22:10', '2025-12-31 16:21:40', '2025-12-31 16:22:10'),
('019b73d5-1c9a-73f1-895e-599a07b9926e', 'FPB/008/BDB/XII/25', 8, 6, 1, 1, 'approved', '2025-12-31', 'FORMULASI EPOXY SL YELLOW GREEN 4:1', '2025-12-31 16:55:15', '2025-12-31 16:55:04', '2025-12-31 16:55:15'),
('019bbf5b-194b-7340-bd65-1cf45bf90b27', 'FPB/001/BDB/I/26', 10, NULL, 5, 1, 'approved', '2026-01-15', NULL, '2026-01-15 08:54:18', '2026-01-15 08:52:57', '2026-01-15 08:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `fpb_items`
--

CREATE TABLE `fpb_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fpb_id` char(36) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_requested` decimal(15,2) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'pcs',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fpb_items`
--

INSERT INTO `fpb_items` (`id`, `fpb_id`, `product_id`, `quantity_requested`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(1, '019b5f33-cf73-72ee-8d8d-b0b569691f00', 258, 100.00, 'pcs', NULL, '2025-12-27 16:46:29', '2025-12-27 16:46:29'),
(2, '019b5f93-940d-715d-b06e-938eb084d65d', 258, 20.00, 'pcs', NULL, '2025-12-27 18:31:05', '2025-12-27 18:31:05'),
(3, '019b6d0f-ea52-70f5-a9bc-d6c2c5433f38', 301, 110.00, 'kg', NULL, '2025-12-30 09:21:58', '2025-12-30 09:21:58'),
(4, '019b6d0f-ea52-70f5-a9bc-d6c2c5433f38', 302, 0.60, 'kg', NULL, '2025-12-30 09:21:58', '2025-12-30 09:21:58'),
(5, '019b6d0f-ea52-70f5-a9bc-d6c2c5433f38', 303, 75.00, 'kg', NULL, '2025-12-30 09:21:58', '2025-12-30 09:21:58'),
(6, '019b6d0f-ea52-70f5-a9bc-d6c2c5433f38', 307, 10.00, 'kg', NULL, '2025-12-30 09:21:58', '2025-12-30 09:21:58'),
(7, '019b6d0f-ea52-70f5-a9bc-d6c2c5433f38', 250, 5.00, 'kg', NULL, '2025-12-30 09:21:58', '2025-12-30 09:21:58'),
(8, '019b6d0f-ea52-70f5-a9bc-d6c2c5433f38', 304, 5.00, 'kg', NULL, '2025-12-30 09:21:58', '2025-12-30 09:21:58'),
(9, '019b6d0f-ea52-70f5-a9bc-d6c2c5433f38', 306, 0.60, 'kg', NULL, '2025-12-30 09:21:58', '2025-12-30 09:21:58'),
(10, '019b6d16-d89c-71c5-8094-de6f86ec8561', 301, 110.00, 'kg', NULL, '2025-12-30 09:29:32', '2025-12-30 09:29:32'),
(11, '019b6d16-d89c-71c5-8094-de6f86ec8561', 302, 0.60, 'kg', NULL, '2025-12-30 09:29:32', '2025-12-30 09:29:32'),
(12, '019b6d16-d89c-71c5-8094-de6f86ec8561', 303, 50.00, 'kg', NULL, '2025-12-30 09:29:32', '2025-12-30 09:29:32'),
(13, '019b6d16-d89c-71c5-8094-de6f86ec8561', 307, 10.00, 'kg', NULL, '2025-12-30 09:29:32', '2025-12-30 09:29:32'),
(14, '019b6d16-d89c-71c5-8094-de6f86ec8561', 250, 5.00, 'kg', NULL, '2025-12-30 09:29:32', '2025-12-30 09:29:32'),
(15, '019b6d16-d89c-71c5-8094-de6f86ec8561', 304, 5.00, 'kg', NULL, '2025-12-30 09:29:32', '2025-12-30 09:29:32'),
(16, '019b6d16-d89c-71c5-8094-de6f86ec8561', 306, 0.60, 'kg', NULL, '2025-12-30 09:29:32', '2025-12-30 09:29:32'),
(17, '019b7395-58e9-723b-b587-9152bac75f64', 301, 100.00, 'kg', NULL, '2025-12-31 15:45:26', '2025-12-31 15:45:26'),
(18, '019b7398-de47-71d5-acb1-e2db8abe21f0', 301, 50.00, 'kg', NULL, '2025-12-31 15:49:16', '2025-12-31 15:49:16'),
(19, '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 301, 19.50, 'kg', NULL, '2025-12-31 16:21:40', '2025-12-31 16:21:40'),
(20, '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 321, 2.65, 'kg', NULL, '2025-12-31 16:21:40', '2025-12-31 16:21:40'),
(21, '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 319, 3.00, 'kg', NULL, '2025-12-31 16:21:40', '2025-12-31 16:21:40'),
(22, '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 318, 1.95, 'kg', NULL, '2025-12-31 16:21:40', '2025-12-31 16:21:40'),
(23, '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 307, 1.50, 'kg', NULL, '2025-12-31 16:21:40', '2025-12-31 16:21:40'),
(24, '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 250, 1.50, 'kg', NULL, '2025-12-31 16:21:40', '2025-12-31 16:21:40'),
(25, '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 306, 0.13, 'kg', NULL, '2025-12-31 16:21:40', '2025-12-31 16:21:40'),
(26, '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 268, 4.00, 'pcs', NULL, '2025-12-31 16:21:40', '2025-12-31 16:21:40'),
(27, '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 316, 4.00, 'pcs', NULL, '2025-12-31 16:21:40', '2025-12-31 16:21:40'),
(28, '019b73d5-1c9a-73f1-895e-599a07b9926e', 331, 60.00, 'kg', NULL, '2025-12-31 16:55:04', '2025-12-31 16:55:04'),
(29, '019b73d5-1c9a-73f1-895e-599a07b9926e', 332, 19.00, 'kg', NULL, '2025-12-31 16:55:04', '2025-12-31 16:55:04'),
(30, '019b73d5-1c9a-73f1-895e-599a07b9926e', 318, 2.70, 'kg', NULL, '2025-12-31 16:55:04', '2025-12-31 16:55:04'),
(31, '019b73d5-1c9a-73f1-895e-599a07b9926e', 319, 3.50, 'kg', NULL, '2025-12-31 16:55:04', '2025-12-31 16:55:04'),
(32, '019b73d5-1c9a-73f1-895e-599a07b9926e', 268, 11.00, 'pcs', NULL, '2025-12-31 16:55:04', '2025-12-31 16:55:04'),
(33, '019b73d5-1c9a-73f1-895e-599a07b9926e', 316, 11.00, 'pcs', NULL, '2025-12-31 16:55:04', '2025-12-31 16:55:04'),
(34, '019bbf5b-194b-7340-bd65-1cf45bf90b27', 333, 20.00, 'kg', NULL, '2026-01-15 08:52:57', '2026-01-15 08:52:57'),
(35, '019bbf5b-194b-7340-bd65-1cf45bf90b27', 250, 10.00, 'kg', NULL, '2026-01-15 08:52:57', '2026-01-15 08:52:57'),
(36, '019bbf5b-194b-7340-bd65-1cf45bf90b27', 304, 10.00, 'kg', NULL, '2026-01-15 08:52:57', '2026-01-15 08:52:57');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_note_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','pending_approval','sent','paid','partial','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `uuid`, `invoice_number`, `customer_id`, `delivery_note_id`, `created_by`, `invoice_date`, `due_date`, `subtotal`, `tax_percent`, `tax_amount`, `discount`, `total`, `paid_amount`, `status`, `approved_by`, `approved_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, '84637a3c-5f76-40e0-87cd-9ee273432728', 'YYK/001/BDB/XII/2025', 15, 2, 1, '2025-12-30', '2025-12-31', 6200000.00, 0.00, 0.00, 0.00, 6200000.00, 6200000.00, 'paid', NULL, NULL, NULL, '2025-12-31 16:28:35', '2025-12-31 16:29:40'),
(2, '176eb7f5-a137-4863-8678-67faec4b7c86', 'STR/002/BDB/XII/2025', 16, 3, 1, '2025-12-30', '2026-01-30', 5500000.00, 0.00, 0.00, 0.00, 5500000.00, 5500000.00, 'paid', NULL, NULL, NULL, '2025-12-31 16:32:51', '2025-12-31 16:32:54'),
(3, '63add749-0367-48f0-a480-58f4eb5bf234', 'STR/003/BDB/XII/2025', 16, 4, 1, '2025-12-31', '2026-01-31', 2750000.00, 0.00, 0.00, 0.00, 2750000.00, 2750000.00, 'paid', NULL, NULL, NULL, '2025-12-31 16:34:01', '2025-12-31 16:34:06'),
(4, '019b7b05-7b67-4790-ae73-a47f1bdbf867', 'AGS/004/BDB/XII/2025', 12, 5, 1, '2025-12-30', '2025-12-31', 14500000.00, 0.00, 0.00, 0.00, 14500000.00, 14500000.00, 'paid', NULL, NULL, NULL, '2025-12-31 16:57:30', '2025-12-31 16:57:37');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 314, 2.00, 1350000.00, 2700000.00, NULL, '2025-12-31 16:28:35', '2025-12-31 16:29:34'),
(2, 1, 315, 2.00, 1750000.00, 3500000.00, NULL, '2025-12-31 16:29:34', '2025-12-31 16:29:34'),
(3, 2, 312, 100.00, 55000.00, 5500000.00, NULL, '2025-12-31 16:32:51', '2025-12-31 16:32:51'),
(4, 3, 312, 50.00, 55000.00, 2750000.00, NULL, '2025-12-31 16:34:01', '2025-12-31 16:34:01'),
(5, 4, 327, 5.00, 1500000.00, 7500000.00, NULL, '2025-12-31 16:57:30', '2025-12-31 16:57:30'),
(6, 4, 329, 5.00, 1100000.00, 5500000.00, NULL, '2025-12-31 16:57:30', '2025-12-31 16:57:30'),
(7, 4, 328, 1.00, 1500000.00, 1500000.00, NULL, '2025-12-31 16:57:30', '2025-12-31 16:57:30');

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
-- Table structure for table `job_costs`
--

CREATE TABLE `job_costs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `job_cost_number` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` enum('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_cost_items`
--

CREATE TABLE `job_cost_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_cost_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000003_create_categories_table', 1),
(5, '2024_01_01_000004_create_products_table', 1),
(6, '2024_01_01_000005_create_customers_table', 1),
(7, '2024_01_01_000006_create_spk_tables', 1),
(8, '2024_01_01_000007_create_stock_movements_table', 1),
(9, '2024_01_01_000008_create_production_logs_table', 1),
(10, '2024_01_01_000009_create_invoices_tables', 1),
(11, '2024_01_01_000010_create_notifications_table', 1),
(12, '2025_12_09_161142_create_special_orders_table', 1),
(13, '2025_12_09_161149_create_delivery_notes_table', 1),
(14, '2025_12_09_161746_alter_spks_table_add_type_and_so', 1),
(15, '2025_12_10_114202_create_spk_production_logs_table', 1),
(16, '2025_12_10_145836_simplify_spk_production_logs_table', 1),
(17, '2025_12_10_150211_create_fpb_table', 1),
(18, '2025_12_10_150214_create_fpb_items_table', 1),
(19, '2025_12_10_201620_change_reference_id_to_string_in_stock_movements', 1),
(20, '2025_12_10_210015_add_special_order_id_to_delivery_notes', 1),
(21, '2025_12_10_214402_add_approval_fields_to_delivery_notes', 1),
(22, '2025_12_10_221716_add_approved_status_to_delivery_notes', 1),
(23, '2025_12_10_222631_add_delivery_note_id_to_invoices', 1),
(24, '2025_12_11_000001_create_suppliers_table', 1),
(25, '2025_12_11_000002_create_purchases_table', 1),
(26, '2025_12_11_000003_add_signature_to_users_table', 1),
(27, '2025_12_11_000004_create_job_costs_table', 1),
(28, '2025_12_11_000005_add_code_to_customers_table', 1),
(29, '2025_12_11_000006_add_unit_packing_to_products_table', 1),
(30, '2025_12_27_000001_update_products_approval_status_enum', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES
(1, 1, 'new_spk', 'SPK Baru Menunggu Approval', 'SPK SPK/002/BDB/XII/25 dibuat oleh Fiqri Haiqal dan menunggu approval Anda.', 0, '{\"spk_id\":2}', '2025-12-27 16:10:19', '2025-12-27 16:10:19'),
(2, 4, 'spk_approved', 'SPK Disetujui', 'SPK SPK/002/BDB/XII/25 telah disetujui oleh Mulyadi.', 1, '{\"spk_id\":2}', '2025-12-27 16:10:26', '2025-12-30 09:15:05'),
(3, 1, 'new_spk', 'SPK Baru Menunggu Approval', 'SPK SPK/004/BDB/XII/25 dibuat oleh Fiqri Haiqal dan menunggu approval Anda.', 1, '{\"spk_id\":4}', '2025-12-30 08:33:12', '2025-12-30 08:34:01'),
(4, 4, 'spk_approved', 'SPK Disetujui', 'SPK SPK/004/BDB/XII/25 telah disetujui oleh Mulyadi.', 1, '{\"spk_id\":4}', '2025-12-30 08:36:23', '2025-12-30 09:14:54'),
(5, 1, 'new_spk', 'SPK Baru Menunggu Approval', 'SPK SPK/001/BDB/I/26 dibuat oleh Admin dan menunggu approval Anda.', 0, '{\"spk_id\":9}', '2026-01-15 08:41:14', '2026-01-15 08:41:14'),
(6, 1, 'new_spk', 'SPK Baru Menunggu Approval', 'SPK SPK/002/BDB/I/26 dibuat oleh Admin dan menunggu approval Anda.', 0, '{\"spk_id\":10}', '2026-01-15 08:50:39', '2026-01-15 08:50:39'),
(7, 5, 'spk_approved', 'SPK Disetujui', 'SPK SPK/002/BDB/I/26 telah disetujui oleh Mulyadi.', 0, '{\"spk_id\":10}', '2026-01-15 08:50:44', '2026-01-15 08:50:44'),
(8, 5, 'spk_approved', 'SPK Disetujui', 'SPK SPK/001/BDB/I/26 telah disetujui oleh Mulyadi.', 0, '{\"spk_id\":9}', '2026-01-15 08:50:46', '2026-01-15 08:50:46');

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
-- Table structure for table `production_logs`
--

CREATE TABLE `production_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spk_id` bigint(20) UNSIGNED NOT NULL,
  `spk_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` enum('started','paused','resumed','completed','cancelled','production_entry') NOT NULL,
  `quantity_produced` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `consumed_materials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`consumed_materials`)),
  `produced_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`produced_items`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `spec_type` enum('high_spec','medium_spec') DEFAULT NULL,
  `current_stock` decimal(15,2) NOT NULL DEFAULT 0.00,
  `min_stock` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `unit_packing` varchar(255) DEFAULT NULL,
  `purchase_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `supplier_type` enum('supplier_resmi','agen','internal') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `approval_status` enum('approved','pending','rejected','deleted','pending_deletion') DEFAULT 'approved',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `uuid`, `code`, `name`, `category_id`, `spec_type`, `current_stock`, `min_stock`, `unit`, `unit_packing`, `purchase_price`, `selling_price`, `supplier_type`, `description`, `is_active`, `approval_status`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(247, '47ccfa48-36d9-4c5f-8594-e806d3c00ecf', 'R-128', 'Resin 128', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 0, 'deleted', 1, 1, '2025-12-30 07:46:38', '2025-12-27 15:05:53', '2025-12-30 07:46:38'),
(248, 'e851efef-935a-46b3-a33e-13c901aa2224', 'R-Pls', 'Resin Polyester', 1, 'high_spec', 180.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-27 15:06:31', '2025-12-27 15:06:31', '2025-12-27 15:06:31'),
(249, '560e794e-bf2d-4ad5-95d9-4276f9e307a9', 'R-Pu', 'Resin Pu', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-27 15:08:13', '2025-12-27 15:08:13', '2025-12-27 15:08:13'),
(250, 'fd683f88-4c6d-4643-b596-f590c2dcf4a5', 'T- Pm', 'Thinner Pm', 1, 'high_spec', 183.50, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-27 15:08:43', '2025-12-27 15:08:43', '2026-01-15 08:54:18'),
(251, 'af6e64dc-b12f-4332-a2de-a2856f0143f9', 'T- Xyline', 'Thinner Xyline', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 0, 'deleted', 1, 1, '2026-01-07 08:14:02', '2025-12-27 15:09:19', '2026-01-07 08:14:02'),
(252, 'b62503a6-550b-488e-a946-0701fa53c03e', 'M-C', 'Microncuad', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 0, 'deleted', 1, 1, '2025-12-27 15:25:53', '2025-12-27 15:09:38', '2025-12-27 15:25:53'),
(253, '7a15e188-758c-49ce-92fb-eee981781a0c', 'M-C1', 'Microncuad', 1, 'high_spec', 0.00, 0.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 0, 'deleted', 1, 1, '2025-12-27 15:27:52', '2025-12-27 15:27:36', '2025-12-27 15:27:52'),
(254, '3206ee8d-a866-4b6d-bd52-95080487f8e5', 'M-C2', 'Micronquart', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 0, 'deleted', 1, 1, '2025-12-30 08:52:23', '2025-12-27 15:28:40', '2025-12-30 08:52:23'),
(255, '13507518-831f-440e-894d-b5463200656d', 'P-Svr', 'Pail Silver', 2, NULL, 300.00, 100.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 0, 'deleted', 1, 1, '2025-12-29 14:23:08', '2025-12-27 16:00:54', '2025-12-29 14:23:08'),
(256, 'c5556c37-5ad6-4b5e-89f3-b8f8894313d2', 'P-Pth', 'Pail Putih', 2, NULL, 300.00, 100.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 0, 'deleted', 1, 1, '2025-12-29 14:23:11', '2025-12-27 16:01:16', '2025-12-29 14:23:11'),
(257, 'c87ff4f6-c3d9-4f1b-bd5d-7942f71a47bc', 'E-Pth', 'Ember Putih', 2, NULL, 300.00, 100.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'pending_deletion', 5, 1, '2025-12-27 16:02:37', '2025-12-27 16:02:37', '2025-12-29 14:20:51'),
(258, '6c874196-6bd0-4752-8c20-6173ca144073', 'B-1L', 'Botol 1 Liter', 2, NULL, 180.00, 100.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'pending_deletion', 5, 1, '2025-12-27 16:02:58', '2025-12-27 16:02:58', '2025-12-29 14:20:48'),
(259, 'f9ebf51c-6d41-4b84-bd62-ca1ca1883aec', 'E-5Kg', 'Ember 5Kg', 2, NULL, 300.00, 100.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'pending_deletion', 5, 1, '2025-12-27 16:03:14', '2025-12-27 16:03:14', '2025-12-29 14:20:36'),
(260, '41fb4984-eccb-42eb-b04b-f296be11d739', 'B-C', 'Base Clear SL', 3, NULL, 400.00, 100.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-27 16:04:01', '2025-12-27 16:04:01', '2025-12-27 16:04:01'),
(261, '46777463-af77-4a01-b329-0df6cb89969b', 'B-CT', 'Base Clear TC', 3, NULL, 400.00, 100.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-27 16:04:44', '2025-12-27 16:04:44', '2025-12-27 16:04:44'),
(262, 'de66a00a-922e-4a44-b516-94c1e2fe0c93', 'E-SL', 'Epoxy SL', 3, NULL, 300.00, 100.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'pending_deletion', 4, 1, '2025-12-27 16:05:58', '2025-12-27 16:05:58', '2025-12-29 16:56:17'),
(263, 'ffea3cf8-6cc5-40d4-a2bd-2a7adec54ae3', 'E-TC', 'Epoxy TC', 3, NULL, 300.00, 100.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'pending_deletion', 4, 1, '2025-12-27 16:06:18', '2025-12-27 16:06:18', '2025-12-29 16:56:12'),
(264, '23ceafd5-2572-440b-b71b-e933dc83b91e', 'PU', 'Polyurethane', 3, NULL, 300.00, 100.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'pending_deletion', 4, 1, '2025-12-27 16:07:14', '2025-12-27 16:07:14', '2025-12-29 16:56:04'),
(265, '8f6fc101-8538-48ca-bdeb-17d38fd90aea', 'KH 816', 'HARDENER EPOXY SL', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 0, 'deleted', 4, 1, '2026-01-07 08:10:43', '2025-12-29 00:02:38', '2026-01-07 08:10:43'),
(266, '11c71a37-c238-4671-b041-14d074c71577', 'R-X75', 'RESIN X75', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 4, 1, '2025-12-29 00:09:58', '2025-12-29 00:04:57', '2025-12-29 00:09:58'),
(267, '6b5eec1a-437c-45f0-90fb-f7302cbf3b9f', 'AP-1041', 'HARDENER AP-1041', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 00:12:04', '2025-12-29 00:12:04', '2025-12-29 00:12:04'),
(268, 'cf56fb5c-70d8-42c5-82a8-5d373f91c0ed', 'P-Slvr', 'PAIL SILVER', 2, NULL, 114.00, 50.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:23:51', '2025-12-29 14:23:51', '2025-12-31 16:55:15'),
(269, '33bed1d9-2eff-49c3-bb68-43969ca82e7e', 'P-BRU', 'PAIL BIRU', 2, NULL, 51.00, 20.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:24:32', '2025-12-29 14:24:32', '2025-12-29 14:24:32'),
(270, '468e8346-87b1-4676-be62-dd8a098062a1', 'P-OTO', 'PAIL OTOPAINT', 2, NULL, 246.00, 50.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:25:15', '2025-12-29 14:25:15', '2025-12-29 14:25:15'),
(271, '0f188326-8807-4346-be67-8f9864910b46', 'P-SLVR P', 'PAIL SILVER PANJANG', 2, NULL, 157.00, 50.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:27:33', '2025-12-29 14:27:33', '2025-12-29 14:27:33'),
(272, '3bfb88ad-0bc0-4ff4-8b4a-6e9ba36bb7de', 'P-PH', 'PAIL PUTIH', 2, NULL, 8.00, 5.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:31:00', '2025-12-29 14:31:00', '2025-12-29 14:31:00'),
(273, '113c74f3-f69a-4a86-b965-bbf7d07bd9da', 'K-PTH', 'KALENG PUTIH', 2, NULL, 55.00, 15.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:32:25', '2025-12-29 14:32:25', '2025-12-29 14:32:25'),
(274, '4f48cb57-f5b4-4be7-b6a6-9245ab7917f6', 'SLKPN', 'SILIKOPHEN', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 14:36:28', '2025-12-29 14:36:28', '2025-12-29 14:36:28'),
(275, '636243ee-fdeb-45f5-8eb0-5c1ea2dd237b', 'T-RTL', 'TITANIUM RUTILE', 1, 'high_spec', 750.00, 100.00, 'kg', NULL, 0.00, 0.00, 'agen', 'Stok 30Zak', 1, 'approved', 1, 1, '2025-12-29 14:39:14', '2025-12-29 14:39:14', '2025-12-29 14:39:14'),
(276, 'b5fb8e0d-68ef-4ede-aaa8-bc1509ba498c', 'T-SR2377', 'TITANIUM SR2377', 1, 'high_spec', 800.00, 100.00, 'kg', NULL, 0.00, 0.00, 'agen', 'Stok32 Zak', 1, 'approved', 1, 1, '2025-12-29 14:43:34', '2025-12-29 14:43:34', '2025-12-29 14:43:34'),
(277, 'aa365d3c-c8e7-480a-bd48-13fb2aa49810', 'TYR-681', 'TITANIUM TYR-681', 1, 'medium_spec', 75.00, 25.00, 'kg', NULL, 0.00, 0.00, 'agen', 'Stok 3zak', 1, 'approved', 1, 1, '2025-12-29 14:45:35', '2025-12-29 14:45:35', '2025-12-29 14:45:35'),
(278, 'bdb5cd42-c75f-48f7-8e54-a85bc3f01d30', 'P-Y 2GS', 'PIGMENT YELLOW 2GS', 1, 'high_spec', 100.00, 25.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', '4zak', 1, 'approved', 1, 1, '2025-12-29 14:48:31', '2025-12-29 14:48:31', '2025-12-29 14:48:31'),
(279, '43a1f6e4-6e3c-4c48-b671-388e2f45d9bc', 'P-BLCK', 'PIGMENT BLACK', 1, 'medium_spec', 75.00, 25.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 14:50:13', '2025-12-29 14:50:13', '2025-12-29 14:50:13'),
(280, '010709a8-cd3a-4295-831a-7c87bfaa063a', 'E-KNNG', 'EMBER KUNING', 2, NULL, 75.00, 20.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:51:19', '2025-12-29 14:51:19', '2025-12-29 14:51:19'),
(281, 'f1667a7e-1c93-4f2d-9886-3611a3ad6937', 'E-PTIH', 'EMBER PUTIH', 2, NULL, 13.00, 5.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:51:51', '2025-12-29 14:51:51', '2025-12-29 14:51:51'),
(282, 'dd9c1479-095c-440b-af51-50cb69f1f4c5', 'CPP', 'CHLORINATED POLYPROPYLENE', 1, 'high_spec', 1100.00, 300.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', '55 Dus', 1, 'approved', 1, 1, '2025-12-29 14:59:09', '2025-12-29 14:59:09', '2025-12-29 14:59:09'),
(283, 'c9321a53-bcc9-427d-b27c-202a64958745', 'R-E759-1', 'RESIN (BLACK)', 1, 'medium_spec', 540.00, 100.00, 'kg', NULL, 0.00, 0.00, 'agen', '36Dus', 1, 'approved', 1, 1, '2025-12-29 15:03:38', '2025-12-29 15:03:38', '2025-12-29 15:03:38'),
(284, 'e6e9c37c-5edd-43eb-8be6-00a122140fe8', 'P-G7', 'PIGMENT GREEN7', 1, 'high_spec', 25.00, 10.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 15:08:00', '2025-12-29 15:08:00', '2025-12-29 15:08:00'),
(285, 'e843f044-d7c0-41ad-9e89-240f522e84a6', 'PB15, 4', 'PIGMENT BLUE', 1, 'high_spec', 50.00, 25.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 15:10:33', '2025-12-29 15:10:33', '2025-12-29 15:10:33'),
(286, 'ef96af29-feb2-419c-a492-814a644b875b', 'M3D1-AQ', 'PIGMENT BLUE', 1, 'high_spec', 75.00, 25.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 15:11:37', '2025-12-29 15:11:37', '2025-12-29 15:11:37'),
(287, 'a9b0f64f-6e24-4715-bb39-f630f1feeff6', 'P-OXY', 'PIGMENT OXERA YELLOW', 1, 'high_spec', 250.00, 25.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 15:13:02', '2025-12-29 15:13:02', '2025-12-29 15:13:02'),
(288, '5312140a-a9e7-4d76-9b82-b71ffc7b3eee', 'P-OY', 'PIGMENT OXIDE YELLOW', 1, 'high_spec', 100.00, 25.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 15:14:31', '2025-12-29 15:14:31', '2025-12-29 15:14:31'),
(289, '3bbb7060-5dea-47fd-a27c-c68e96ae7e10', 'P-LLR', 'PIGMENT LCY LAKE RED', 1, 'high_spec', 100.00, 25.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 0, 'deleted', 1, 1, '2026-01-07 08:26:01', '2025-12-29 15:16:22', '2026-01-07 08:26:01'),
(290, '7fe40760-c8de-41d0-9579-6fbe3f012abe', 'P-R. GL', 'PIGMENT RED GL', 1, 'high_spec', 25.00, 25.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 15:18:39', '2025-12-29 15:18:39', '2025-12-29 15:18:39'),
(291, 'b9aebc35-e1e3-420f-9848-4b1421b7fd87', 'P-R57. 1', 'PIGMENT RED 571', 1, 'high_spec', 100.00, 25.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 0, 'deleted', 1, 1, '2026-01-07 08:27:39', '2025-12-29 15:20:48', '2026-01-07 08:27:39'),
(292, '33e1db81-6bb5-49ae-81f5-579a08e9ef33', 'P-V. MNC SG', 'PIGMENT VIOLET MNC-SG', 1, 'high_spec', 675.00, 25.00, 'kg', NULL, 0.00, 0.00, 'agen', NULL, 1, 'approved', 1, 1, '2025-12-29 15:24:24', '2025-12-29 15:24:24', '2025-12-29 15:24:24'),
(293, '61ab7ca6-19a2-4789-b8a9-62e01bb32019', 'DSPRN', 'DISPARLON', 1, 'medium_spec', 180.00, 60.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', '12zak', 0, 'deleted', 1, 1, '2025-12-30 07:51:23', '2025-12-29 15:26:56', '2025-12-30 07:51:23'),
(294, 'eea5d37f-5e0f-4cd3-a297-7b56243660d5', 'H-KTK', 'HIKOTACK', 1, 'medium_spec', 175.00, 50.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', '7zak', 1, 'approved', 1, 1, '2025-12-29 15:29:16', '2025-12-29 15:29:16', '2025-12-29 15:29:16'),
(295, '8dc26d6e-d690-4fcd-b20e-a994fdd3acfe', 'T-TIPURE', 'TITANIUM-TIPURE', 1, 'high_spec', 1350.00, 500.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', '54zak', 1, 'approved', 1, 1, '2025-12-29 15:30:49', '2025-12-29 15:30:49', '2025-12-29 15:30:49'),
(296, '8f70c598-2dd1-4692-af3c-0b889c53ac8d', 'Z-DUST', 'ZINK DUST', 1, 'high_spec', 500.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 15:31:51', '2025-12-29 15:31:51', '2025-12-29 15:31:51'),
(297, '36aa98a8-dd69-4c40-a1ca-e20377b74190', 'HMPDR-35560', 'HEMPADURE-35560', 1, 'medium_spec', 1080.00, 300.00, 'kg', NULL, 0.00, 0.00, 'agen', NULL, 1, 'approved', 1, 1, '2025-12-29 15:34:06', '2025-12-29 15:34:06', '2025-12-29 15:34:06'),
(298, '6bf07a78-b4d4-4655-9e3d-7d3119ff65b5', 'CMP-45', 'COPOLYMER OF VINYL CHLORIDE', 1, 'high_spec', 1840.00, 500.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', '92zak', 1, 'approved', 1, 1, '2025-12-29 15:38:48', '2025-12-29 15:38:48', '2025-12-29 15:38:48'),
(299, 'b82abd65-988c-4531-8ee3-7ade17098fcf', 'B-S', 'Botol Sample', 2, NULL, 94.00, 20.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 16:43:51', '2025-12-29 16:43:51', '2025-12-29 16:43:51'),
(300, 'aae25b3b-56a8-4eb0-9748-deaed49413a7', 'B1L', 'Botol 1 Liter', 2, NULL, 25.00, 20.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 16:46:59', '2025-12-29 16:46:59', '2025-12-29 16:46:59'),
(301, '9b984669-edba-4e77-a8bf-5eb206948e63', 'R128', 'RESIN 128', 1, 'high_spec', 160.50, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 0, 'deleted', 1, 1, '2026-01-07 08:07:47', '2025-12-30 07:49:22', '2026-01-07 08:07:47'),
(302, '2812b751-6463-404c-92a8-f2d65b4bd912', 'DSPRLN', 'DISPARLON', 1, 'medium_spec', 179.40, 50.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-30 07:52:32', '2025-12-30 07:52:32', '2025-12-30 09:30:27'),
(303, '36c2384e-b567-4ff2-b59e-b3a2c793e4a1', 'M-CD', 'Microncuad', 1, 'high_spec', 0.00, 10.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-30 08:53:21', '2025-12-30 08:53:21', '2025-12-30 09:30:27'),
(304, '9f3ba660-6f41-4523-8383-3f3c4598683c', 'T-BC', 'THINNER-BC', 1, 'high_spec', 355.00, 100.00, 'kg', NULL, 0.00, 0.00, 'agen', NULL, 1, 'approved', 1, 1, '2025-12-30 09:05:34', '2025-12-30 09:05:34', '2026-01-15 08:54:18'),
(305, '1490b6aa-8515-406a-bc2a-a30d6e1778f2', 'B-RS', 'BAHRIUM-SULFAT', 1, 'medium_spec', 950.00, 100.00, 'kg', NULL, 0.00, 0.00, 'agen', NULL, 0, 'deleted', 1, 1, '2026-01-07 08:20:43', '2025-12-30 09:12:44', '2026-01-07 08:20:43'),
(306, 'cf607908-37e1-41ba-a51f-dcd5c61c32c3', 'BYK-077', 'DISSPERS BYK-077', 1, 'high_spec', 1.27, 20.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-30 09:13:43', '2025-12-30 09:13:43', '2025-12-31 16:22:10'),
(307, '7d901b9c-fd15-4ad2-87f3-bb9eb4245631', 'T-AGE', 'THINNER-AGE', 1, 'high_spec', 88.50, 50.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-30 09:19:47', '2025-12-30 09:19:47', '2025-12-31 16:22:10'),
(308, 'b38205ac-a11d-481a-aede-28eb0ee6d6c9', 'H-AP1041', 'HARDENER AP-1041', 3, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-30 10:06:32', '2025-12-30 10:06:32', '2025-12-30 10:15:22'),
(309, '95de46b9-71e0-49ec-bde8-724c3e3d5d1e', 'T.AGE', 'THINNER.AGE', 3, 'high_spec', 90.00, 30.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-30 10:07:49', '2025-12-30 10:07:49', '2025-12-30 10:14:58'),
(310, 'd3eb1668-103b-41b7-8878-245bf761374c', 'E.SL', 'EPOXY SL GREEN PASTEL 3:1', 3, NULL, 30.00, 30.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 0, 'deleted', 4, 1, '2025-12-31 16:00:27', '2025-12-31 09:21:03', '2025-12-31 16:00:27'),
(311, 'd5f662bf-4907-4336-a25c-92b655ac1ded', 'E-PC', 'EPOXY PRIMER CLEAR', 3, NULL, 103.00, 20.00, 'KG', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 15:29:44', '2025-12-31 15:29:44', '2025-12-31 15:29:44'),
(312, '46a853fa-bfc7-427d-8008-7be2e105b0ef', 'R.128', 'RESIN 128', 3, NULL, 330.00, 100.00, 'KG', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 15:35:16', '2025-12-31 15:35:16', '2025-12-31 15:35:16'),
(313, '54f764e1-a516-49a2-a09f-9f5fedc26bf4', 'E-PG', 'EPOXY PRIMER GREY', 3, NULL, 32.00, 10.00, 'KG', NULL, 0.00, 0.00, NULL, NULL, 0, 'deleted', 1, 1, '2025-12-31 15:58:23', '2025-12-31 15:54:52', '2025-12-31 15:58:23'),
(314, '83125f97-4147-4c6d-bfc3-9672e503c0f1', 'E.PG', 'EPOXY PRIMER GREY', 3, NULL, 2.00, 5.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 15:59:07', '2025-12-31 15:59:07', '2025-12-31 15:59:07'),
(315, '32aec653-3c9a-4ec9-b3c7-0a0c328eba14', 'ESL', 'EPOXY SL GREEN PASTEL', 3, NULL, 2.00, 5.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:01:47', '2025-12-31 16:01:47', '2025-12-31 16:01:47'),
(316, '449908cf-42ae-40c1-97b7-aef6b636a924', 'J5L', 'JERIGEN 5LITER', 2, NULL, 36.00, 20.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:04:42', '2025-12-31 16:04:42', '2025-12-31 16:55:15'),
(317, '96d77128-2b40-4a0b-b813-b23cdd530ef6', 'J25L', 'JERIGEN 25 LITER', 2, NULL, 60.00, 20.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:05:15', '2025-12-31 16:05:15', '2025-12-31 16:05:15'),
(318, 'e5e34d63-6157-4137-adf5-4b241373a894', 'P-H', 'PASTA HIJAU', 1, 'high_spec', 195.35, 50.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 1, 1, '2025-12-31 16:10:31', '2025-12-31 16:10:31', '2025-12-31 16:55:15'),
(319, '677c1b71-69ab-4aa3-9158-83d9825e7480', 'P-P', 'PASTA PUTIH', 1, 'high_spec', 93.50, 20.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 1, 1, '2025-12-31 16:11:11', '2025-12-31 16:11:11', '2025-12-31 16:55:15'),
(320, 'f8447a72-e11d-47a6-81e2-8b9a7ee0390e', 'P-B', 'PASTA BIRU', 1, 'high_spec', 150.00, 20.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 1, 1, '2025-12-31 16:11:56', '2025-12-31 16:11:56', '2025-12-31 16:11:56'),
(321, '269c21c1-94ef-40cb-aa79-866e7c3f4dcf', 'P-Y', 'PASTA YELLOW', 1, 'high_spec', 147.35, 20.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:12:43', '2025-12-31 16:12:43', '2025-12-31 16:22:10'),
(322, 'ab72829f-03c5-4d50-89a1-c9143b536464', 'P-I', 'PASTA ITEM', 1, 'high_spec', 150.00, 20.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 1, 1, '2025-12-31 16:13:23', '2025-12-31 16:13:23', '2025-12-31 16:13:23'),
(323, '8f699e0b-79bc-4791-b7f2-3d86aace3656', 'P-YR', 'PASTA YELLOW RED', 1, 'high_spec', 100.00, 20.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 1, 1, '2025-12-31 16:14:00', '2025-12-31 16:14:00', '2025-12-31 16:14:00'),
(324, '04e1acce-6227-4053-b4e0-2246b7ee545d', 'P.OXY', 'PASTA OXIDE YELLOW', 1, 'high_spec', 100.00, 20.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 1, 1, '2025-12-31 16:15:37', '2025-12-31 16:15:37', '2025-12-31 16:15:37'),
(325, 'bfd8929b-d514-4676-839a-b48d327ec495', 'P-OR', 'PASTA OXIDE RED', 1, 'high_spec', 100.00, 20.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 1, 1, '2025-12-31 16:16:04', '2025-12-31 16:16:04', '2025-12-31 16:16:04'),
(326, 'e0e6d082-fb3e-43ae-99af-e76555a87cd9', 'P-M', 'PASTA RED', 1, NULL, 100.00, 20.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:16:47', '2025-12-31 16:16:47', '2025-12-31 16:16:47'),
(327, 'd9a36c64-f834-48f6-9b17-c3654bac34ab', 'E-YG', 'EPOXY SL YELLOW GREEN 4:1', 3, NULL, 87.00, 20.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:42:57', '2025-12-31 16:42:57', '2025-12-31 16:44:43'),
(328, '253afa1e-4281-4eb1-96ba-3e2b5bea5d27', 'E-LG', 'EPOXY SL LIGHT GREY', 3, NULL, 20.00, 50.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:46:34', '2025-12-31 16:46:34', '2025-12-31 16:46:34'),
(329, 'df4e3b29-1d91-4a0a-839c-8fe0b4bdaaca', 'E-P.C', 'EPOXY PRIMER CLEAR', 3, NULL, 80.00, 20.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:47:18', '2025-12-31 16:47:18', '2025-12-31 16:47:18'),
(330, 'c7d2448d-0e1e-4bf0-b03b-5baf2c2f26ef', 'BC', 'BASE CLEAR SL', 3, NULL, 80.00, 50.00, 'KG', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:50:13', '2025-12-31 16:50:13', '2025-12-31 16:50:13'),
(331, '92469a4c-5d65-4221-8344-5928af44b76e', 'B-CLEAR', 'BASE CLEAR SL', 1, 'high_spec', 20.00, 50.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 1, 1, '2025-12-31 16:51:51', '2025-12-31 16:51:51', '2025-12-31 16:55:15'),
(332, '36c7da0f-b0aa-45c7-aa17-7e6c120061c7', 'P-LY', 'PASTA LEMON YELLOW', 1, 'high_spec', 81.00, 50.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 1, 1, '2025-12-31 16:52:59', '2025-12-31 16:52:59', '2025-12-31 16:55:15'),
(333, '6a76c88d-dbe6-429a-b7fc-5ba11244f6c3', 'R-E128', 'RESIN EPOXY 128', 1, 'high_spec', 460.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2026-01-07 08:09:25', '2026-01-07 08:09:25', '2026-01-15 08:54:18'),
(334, 'b95cdf83-c6b1-4b81-975e-9d6cb270a146', 'KH816', 'HARDENER EPOXY 816', 1, 'high_spec', 400.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2026-01-07 08:12:09', '2026-01-07 08:12:09', '2026-01-07 08:12:09'),
(335, 'fa9ce50c-a9a7-43fe-bcb0-6c7be189b31b', 'T.Tronox', 'Titanium Tronox', 1, 'high_spec', 475.00, 200.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 5, 1, '2026-01-07 08:15:29', '2026-01-07 08:14:30', '2026-01-07 08:15:29'),
(336, '28776cd5-89d7-4c95-8652-c3dd4e4f8b51', 'T.XYLINE', 'TINNER XYLINE', 1, 'medium_spec', 716.00, 50.00, 'kg', NULL, 0.00, 0.00, 'agen', NULL, 1, 'approved', 1, 1, '2026-01-07 08:15:00', '2026-01-07 08:15:00', '2026-01-07 08:15:00'),
(337, '29f46502-33a5-46b8-833b-37c16397c239', 'G. S', 'Gelsil', 1, 'medium_spec', 280.00, 100.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 5, 1, '2026-01-07 08:18:32', '2026-01-07 08:17:43', '2026-01-07 08:18:32'),
(338, 'cf0514a1-391f-4f23-977e-c217472628af', 'A. Crayvallac', 'Arkema crayvallac', 1, 'high_spec', 120.00, 60.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 5, 1, '2026-01-07 08:21:20', '2026-01-07 08:19:39', '2026-01-07 08:21:20'),
(339, 'd316d2c4-02cc-448f-848b-2349679cf887', 'B. Sulfat', 'Barium sulfat', 1, 'high_spec', 1000.00, 200.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 5, 1, '2026-01-07 08:21:21', '2026-01-07 08:21:10', '2026-01-07 08:21:21'),
(340, '366717da-2f7b-4768-ad3c-01d263c039ea', 'T. D. Tioxhua', 'Titanium Dioxide Tioxhua', 1, 'medium_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 5, 1, '2026-01-07 08:23:18', '2026-01-07 08:22:44', '2026-01-07 08:23:18'),
(341, '127d694b-ff63-40b0-8058-10f0e5f49fa9', 'T. D. Xuelian', 'Titanium Dioxide Xuelian', 1, 'high_spec', 160.00, 100.00, 'kg', NULL, 0.00, 0.00, 'internal', NULL, 1, 'approved', 5, 1, '2026-01-07 08:25:01', '2026-01-07 08:24:53', '2026-01-07 08:25:01'),
(342, 'ac425104-8ab0-41e9-abdd-48f26668ebc8', 'P-LCY', 'PIGMENT LCY LAKE RED C', 1, NULL, 200.00, 50.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2026-01-07 08:26:57', '2026-01-07 08:26:57', '2026-01-07 08:26:57'),
(343, '154f1c17-9391-4f6b-9383-a1aba85e9f38', 'P-R571', 'PIGMENT RED- 571', 1, 'high_spec', 150.00, 50.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2026-01-07 08:28:25', '2026-01-07 08:28:25', '2026-01-07 08:28:25');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `purchase_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','pending','approved','received','cancelled') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `uuid`, `purchase_number`, `supplier_id`, `purchase_date`, `subtotal`, `tax`, `discount`, `total_amount`, `status`, `created_by`, `approved_by`, `approved_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'a68cec6e-712b-4cdc-8b14-5a15c6303c7c', 'PO-2026010001', 6, '2026-01-15', 16232400.00, 0.00, 0.00, 16232400.00, 'draft', 1, NULL, NULL, NULL, '2026-01-15 09:21:25', '2026-01-15 09:21:25');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `quantity`, `unit_price`, `total`, `notes`, `created_at`, `updated_at`) VALUES
(3, 1, 267, 400.00, 40581.00, 16232400.00, NULL, '2026-01-15 09:22:22', '2026-01-15 09:22:22');

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
-- Table structure for table `special_orders`
--

CREATE TABLE `special_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `so_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('draft','pending_approval','approved','in_production','completed','cancelled') NOT NULL DEFAULT 'draft',
  `order_date` date NOT NULL,
  `delivery_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `special_orders`
--

INSERT INTO `special_orders` (`id`, `uuid`, `so_number`, `customer_id`, `created_by`, `approved_by`, `status`, `order_date`, `delivery_date`, `subtotal`, `tax_percent`, `tax_amount`, `discount`, `total`, `notes`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, '224ad353-d877-4d2c-8853-96c277ff43f8', 'SO/001/BDB/XII/25', 11, 1, 1, 'completed', '2025-12-27', NULL, 5000000.00, 0.00, 0.00, 0.00, 5000000.00, 'asfasfasfas', '2025-12-27 16:55:50', '2025-12-27 16:55:41', '2025-12-27 18:45:02'),
(2, '87cb95d3-e54e-41ee-898c-f9e46306219d', 'SO/002/BDB/XII/25', 16, 1, 1, 'in_production', '2025-12-30', '2025-12-31', 5500000.00, 0.00, 0.00, 0.00, 5500000.00, NULL, '2025-12-31 15:44:22', '2025-12-31 15:44:17', '2025-12-31 15:44:45'),
(3, 'ed463acb-3b29-4468-8182-e3f488e642b7', 'SO/003/BDB/XII/25', 16, 1, 1, 'in_production', '2025-12-30', '2025-12-31', 2750000.00, 0.00, 0.00, 0.00, 2750000.00, NULL, '2025-12-31 15:48:34', '2025-12-31 15:48:32', '2025-12-31 15:48:48'),
(4, '13c42a1b-fe54-4ccb-a662-362aa083a571', 'SO/004/BDB/XII/25', 15, 1, NULL, 'pending_approval', '2025-12-30', '2025-12-31', 3500000.00, 0.00, 0.00, 0.00, 3500000.00, NULL, NULL, '2025-12-31 15:53:27', '2025-12-31 15:53:27'),
(5, 'b9d890c3-8b61-416f-a3fe-2821757f5248', 'SO/005/BDB/XII/25', 15, 1, 1, 'in_production', '2025-12-30', '2025-12-31', 6200000.00, 0.00, 0.00, 0.00, 6200000.00, NULL, '2025-12-31 16:03:00', '2025-12-31 16:02:59', '2025-12-31 16:03:15'),
(6, '20921816-2779-4809-96c9-8e8ead437148', 'SO/006/BDB/XII/25', 12, 1, 1, 'in_production', '2025-12-30', '2025-12-31', 14500000.00, 0.00, 0.00, 0.00, 14500000.00, NULL, '2025-12-31 16:48:35', '2025-12-31 16:48:30', '2025-12-31 16:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `special_order_items`
--

CREATE TABLE `special_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `special_order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `special_order_items`
--

INSERT INTO `special_order_items` (`id`, `special_order_id`, `product_id`, `quantity`, `unit`, `unit_price`, `subtotal`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 262, 25.00, 'kg', 200000.00, 5000000.00, NULL, '2025-12-27 16:55:41', '2025-12-27 16:55:41'),
(2, 2, 312, 100.00, 'KG', 55000.00, 5500000.00, NULL, '2025-12-31 15:44:17', '2025-12-31 15:44:17'),
(3, 3, 312, 50.00, 'KG', 55000.00, 2750000.00, NULL, '2025-12-31 15:48:32', '2025-12-31 15:48:32'),
(4, 4, 310, 2.00, 'kg', 1750000.00, 3500000.00, NULL, '2025-12-31 15:53:27', '2025-12-31 15:53:27'),
(5, 5, 314, 2.00, 'PAIL', 1350000.00, 2700000.00, NULL, '2025-12-31 16:02:59', '2025-12-31 16:02:59'),
(6, 5, 315, 2.00, 'PAIL', 1750000.00, 3500000.00, NULL, '2025-12-31 16:02:59', '2025-12-31 16:02:59'),
(7, 6, 327, 5.00, 'PAIL', 1500000.00, 7500000.00, NULL, '2025-12-31 16:48:30', '2025-12-31 16:48:30'),
(8, 6, 329, 5.00, 'PAIL', 1100000.00, 5500000.00, NULL, '2025-12-31 16:48:30', '2025-12-31 16:48:30'),
(9, 6, 328, 1.00, 'PAIL', 1500000.00, 1500000.00, NULL, '2025-12-31 16:48:30', '2025-12-31 16:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `spk`
--

CREATE TABLE `spk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `special_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `spk_number` varchar(255) NOT NULL,
  `spk_type` enum('base','finishgood') NOT NULL DEFAULT 'finishgood',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `production_date` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spk`
--

INSERT INTO `spk` (`id`, `uuid`, `special_order_id`, `spk_number`, `spk_type`, `created_by`, `approved_by`, `status`, `production_date`, `deadline`, `notes`, `approved_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, '94523079-eb87-497b-a020-f16d5ebda1d3', NULL, 'SPK/001/BDB/XII/25', 'base', 1, 1, 'approved', '2025-12-27', '2025-12-27', 'untuk stok Januari', '2025-12-27 16:08:40', NULL, '2025-12-27 16:08:40', '2025-12-27 16:08:40'),
(2, 'aadab851-e1af-460a-9fba-71f835361b36', NULL, 'SPK/002/BDB/XII/25', 'base', 4, 1, 'approved', '2025-12-27', '2025-12-27', 'untuk stok Januari', '2025-12-27 16:10:26', NULL, '2025-12-27 16:10:19', '2025-12-27 16:10:26'),
(3, '87272807-c8e5-4d82-935a-dc8ea2826142', 1, 'SPK/003/BDB/XII/25', 'finishgood', 1, 1, 'completed', '2025-12-27', NULL, 'dfasdasdasd', '2025-12-27 16:56:14', '2025-12-27 18:45:02', '2025-12-27 16:56:14', '2025-12-27 18:45:02'),
(4, '1ffbdd56-b2e2-435c-8821-ef07a2fddca9', NULL, 'SPK/004/BDB/XII/25', 'base', 4, 1, 'in_progress', '2025-12-30', '2025-12-30', NULL, '2025-12-30 08:36:23', NULL, '2025-12-30 08:33:12', '2025-12-30 09:38:20'),
(5, 'd41be1ac-87d0-4529-9bc0-b25a37efe72f', 2, 'SPK/005/BDB/XII/25', 'finishgood', 1, 1, 'approved', '2025-12-30', '2025-12-30', 'SPK dari Special Order: SO/002/BDB/XII/25', '2025-12-31 15:44:45', NULL, '2025-12-31 15:44:45', '2025-12-31 15:44:45'),
(6, 'b7e1f5c5-db59-482e-8da1-28903ab365e5', 3, 'SPK/006/BDB/XII/25', 'finishgood', 1, 1, 'approved', '2025-12-31', '2025-12-31', 'SPK dari Special Order: SO/003/BDB/XII/25', '2025-12-31 15:48:48', NULL, '2025-12-31 15:48:48', '2025-12-31 15:48:48'),
(7, 'af778015-3d86-4537-8b45-568a813372fc', 5, 'SPK/007/BDB/XII/25', 'finishgood', 1, 1, 'approved', '2025-12-30', '2025-12-31', 'SPK dari Special Order: SO/005/BDB/XII/25', '2025-12-31 16:03:15', NULL, '2025-12-31 16:03:15', '2025-12-31 16:03:15'),
(8, 'b330f07c-41f1-4b7f-a43a-ac4d54da84f9', 6, 'SPK/008/BDB/XII/25', 'finishgood', 1, 1, 'approved', '2025-12-30', '2025-12-31', 'SPK dari Special Order: SO/006/BDB/XII/25', '2025-12-31 16:48:53', NULL, '2025-12-31 16:48:53', '2025-12-31 16:48:53'),
(9, 'd7d67d16-fc48-421e-b82e-1ae7871de07f', NULL, 'SPK/001/BDB/I/26', 'base', 5, 1, 'approved', '2026-01-15', '2026-01-15', NULL, '2026-01-15 08:50:46', NULL, '2026-01-15 08:41:14', '2026-01-15 08:50:46'),
(10, '62cdd0b5-2961-47d1-82aa-a4e6bc70f477', NULL, 'SPK/002/BDB/I/26', 'base', 5, 1, 'approved', '2026-01-15', '2026-01-15', NULL, '2026-01-15 08:50:44', NULL, '2026-01-15 08:50:39', '2026-01-15 08:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `spk_items`
--

CREATE TABLE `spk_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spk_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` enum('bahan_baku','packaging','output') NOT NULL,
  `quantity_planned` decimal(15,2) NOT NULL DEFAULT 0.00,
  `quantity_used` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spk_items`
--

INSERT INTO `spk_items` (`id`, `spk_id`, `product_id`, `item_type`, `quantity_planned`, `quantity_used`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 260, 'output', 100.00, 0.00, 'kg', NULL, '2025-12-27 16:08:40', '2025-12-27 16:08:40'),
(2, 2, 260, 'output', 100.00, 0.00, 'kg', NULL, '2025-12-27 16:10:19', '2025-12-27 16:10:19'),
(3, 3, 262, 'output', 25.00, 25.00, 'kg', 'Target dari SO: SO/001/BDB/XII/25', '2025-12-27 16:56:14', '2025-12-27 18:45:02'),
(4, 4, 260, 'output', 206.00, 0.00, 'kg', NULL, '2025-12-30 08:33:12', '2025-12-30 08:33:12'),
(5, 5, 312, 'output', 100.00, 0.00, 'KG', 'Target dari SO: SO/002/BDB/XII/25', '2025-12-31 15:44:45', '2025-12-31 15:44:45'),
(6, 6, 312, 'output', 50.00, 0.00, 'KG', 'Target dari SO: SO/003/BDB/XII/25', '2025-12-31 15:48:48', '2025-12-31 15:48:48'),
(7, 7, 314, 'output', 2.00, 0.00, 'PAIL', 'Target dari SO: SO/005/BDB/XII/25', '2025-12-31 16:03:15', '2025-12-31 16:03:15'),
(8, 7, 315, 'output', 2.00, 0.00, 'PAIL', 'Target dari SO: SO/005/BDB/XII/25', '2025-12-31 16:03:15', '2025-12-31 16:03:15'),
(9, 8, 327, 'output', 5.00, 0.00, 'PAIL', 'Target dari SO: SO/006/BDB/XII/25', '2025-12-31 16:48:53', '2025-12-31 16:48:53'),
(10, 8, 329, 'output', 5.00, 0.00, 'PAIL', 'Target dari SO: SO/006/BDB/XII/25', '2025-12-31 16:48:53', '2025-12-31 16:48:53'),
(11, 8, 328, 'output', 1.00, 0.00, 'PAIL', 'Target dari SO: SO/006/BDB/XII/25', '2025-12-31 16:48:53', '2025-12-31 16:48:53'),
(12, 9, 260, 'output', 200.00, 0.00, 'kg', NULL, '2026-01-15 08:41:14', '2026-01-15 08:41:14'),
(13, 10, 260, 'output', 100.00, 0.00, 'kg', NULL, '2026-01-15 08:50:39', '2026-01-15 08:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `spk_production_logs`
--

CREATE TABLE `spk_production_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spk_id` bigint(20) UNSIGNED NOT NULL,
  `spk_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `work_date` date NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spk_production_logs`
--

INSERT INTO `spk_production_logs` (`id`, `spk_id`, `spk_item_id`, `quantity`, `work_date`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 3, 3, 25.00, '2025-12-27', 4, '2025-12-27 18:44:35', '2025-12-27 18:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment','production_in','production_out') NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `stock_before` decimal(15,2) NOT NULL,
  `stock_after` decimal(15,2) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `user_id`, `type`, `quantity`, `stock_before`, `stock_after`, `reference_type`, `reference_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 247, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-27 15:05:53', '2025-12-27 15:05:53'),
(2, 248, 1, 'in', 180.00, 0.00, 180.00, 'manual', NULL, 'Stok awal', '2025-12-27 15:06:31', '2025-12-27 15:06:31'),
(3, 249, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-27 15:08:13', '2025-12-27 15:08:13'),
(4, 250, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-27 15:08:43', '2025-12-27 15:08:43'),
(5, 251, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-27 15:09:19', '2025-12-27 15:09:19'),
(6, 252, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-27 15:09:38', '2025-12-27 15:09:38'),
(7, 254, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-27 15:28:40', '2025-12-27 15:28:40'),
(8, 255, 1, 'in', 300.00, 0.00, 300.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:00:54', '2025-12-27 16:00:54'),
(9, 256, 1, 'in', 300.00, 0.00, 300.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:01:16', '2025-12-27 16:01:16'),
(10, 257, 1, 'in', 300.00, 0.00, 300.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:02:37', '2025-12-27 16:02:37'),
(11, 258, 1, 'in', 300.00, 0.00, 300.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:02:58', '2025-12-27 16:02:58'),
(12, 259, 1, 'in', 300.00, 0.00, 300.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:03:14', '2025-12-27 16:03:14'),
(13, 260, 1, 'in', 400.00, 0.00, 400.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:04:01', '2025-12-27 16:04:01'),
(14, 261, 1, 'in', 400.00, 0.00, 400.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:04:44', '2025-12-27 16:04:44'),
(15, 262, 1, 'in', 300.00, 0.00, 300.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:05:58', '2025-12-27 16:05:58'),
(16, 263, 1, 'in', 300.00, 0.00, 300.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:06:18', '2025-12-27 16:06:18'),
(17, 264, 1, 'in', 300.00, 0.00, 300.00, 'manual', NULL, 'Stok awal', '2025-12-27 16:07:14', '2025-12-27 16:07:14'),
(18, 258, 1, 'out', 100.00, 300.00, 200.00, 'fpb', '019b5f33-cf73-72ee-8d8d-b0b569691f00', 'Permintaan FPB-FPB/001/BDB/XII/25 untuk SPK-SPK/002/BDB/XII/25', '2025-12-27 16:46:43', '2025-12-27 16:46:43'),
(19, 258, 1, 'out', 20.00, 200.00, 180.00, 'fpb', '019b5f93-940d-715d-b06e-938eb084d65d', 'Permintaan FPB-FPB/002/BDB/XII/25 untuk SPK-SPK/003/BDB/XII/25', '2025-12-27 18:31:13', '2025-12-27 18:31:13'),
(20, 266, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal (setelah approval)', '2025-12-29 00:09:58', '2025-12-29 00:09:58'),
(21, 265, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal (setelah approval)', '2025-12-29 00:09:59', '2025-12-29 00:09:59'),
(22, 267, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-29 00:12:04', '2025-12-29 00:12:04'),
(23, 268, 1, 'in', 129.00, 0.00, 129.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:23:51', '2025-12-29 14:23:51'),
(24, 269, 1, 'in', 51.00, 0.00, 51.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:24:32', '2025-12-29 14:24:32'),
(25, 270, 1, 'in', 246.00, 0.00, 246.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:25:15', '2025-12-29 14:25:15'),
(26, 271, 1, 'in', 157.00, 0.00, 157.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:27:33', '2025-12-29 14:27:33'),
(27, 272, 1, 'in', 8.00, 0.00, 8.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:31:00', '2025-12-29 14:31:00'),
(28, 273, 1, 'in', 55.00, 0.00, 55.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:32:25', '2025-12-29 14:32:25'),
(29, 274, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:36:28', '2025-12-29 14:36:28'),
(30, 275, 1, 'in', 750.00, 0.00, 750.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:39:14', '2025-12-29 14:39:14'),
(31, 276, 1, 'in', 800.00, 0.00, 800.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:43:34', '2025-12-29 14:43:34'),
(32, 277, 1, 'in', 75.00, 0.00, 75.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:45:35', '2025-12-29 14:45:35'),
(33, 278, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:48:31', '2025-12-29 14:48:31'),
(34, 279, 1, 'in', 75.00, 0.00, 75.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:50:13', '2025-12-29 14:50:13'),
(35, 280, 1, 'in', 75.00, 0.00, 75.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:51:19', '2025-12-29 14:51:19'),
(36, 281, 1, 'in', 13.00, 0.00, 13.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:51:51', '2025-12-29 14:51:51'),
(37, 282, 1, 'in', 1100.00, 0.00, 1100.00, 'manual', NULL, 'Stok awal', '2025-12-29 14:59:09', '2025-12-29 14:59:09'),
(38, 283, 1, 'in', 540.00, 0.00, 540.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:03:38', '2025-12-29 15:03:38'),
(39, 284, 1, 'in', 25.00, 0.00, 25.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:08:00', '2025-12-29 15:08:00'),
(40, 285, 1, 'in', 50.00, 0.00, 50.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:10:33', '2025-12-29 15:10:33'),
(41, 286, 1, 'in', 75.00, 0.00, 75.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:11:37', '2025-12-29 15:11:37'),
(42, 287, 1, 'in', 250.00, 0.00, 250.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:13:02', '2025-12-29 15:13:02'),
(43, 288, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:14:31', '2025-12-29 15:14:31'),
(44, 289, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:16:22', '2025-12-29 15:16:22'),
(45, 290, 1, 'in', 25.00, 0.00, 25.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:18:39', '2025-12-29 15:18:39'),
(46, 291, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:20:48', '2025-12-29 15:20:48'),
(47, 292, 1, 'in', 675.00, 0.00, 675.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:24:24', '2025-12-29 15:24:24'),
(48, 293, 1, 'in', 180.00, 0.00, 180.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:26:56', '2025-12-29 15:26:56'),
(49, 294, 1, 'in', 175.00, 0.00, 175.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:29:16', '2025-12-29 15:29:16'),
(50, 295, 1, 'in', 1350.00, 0.00, 1350.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:30:49', '2025-12-29 15:30:49'),
(51, 296, 1, 'in', 500.00, 0.00, 500.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:31:51', '2025-12-29 15:31:51'),
(52, 297, 1, 'in', 1080.00, 0.00, 1080.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:34:06', '2025-12-29 15:34:06'),
(53, 298, 1, 'in', 1840.00, 0.00, 1840.00, 'manual', NULL, 'Stok awal', '2025-12-29 15:38:48', '2025-12-29 15:38:48'),
(54, 299, 1, 'in', 94.00, 0.00, 94.00, 'manual', NULL, 'Stok awal', '2025-12-29 16:43:51', '2025-12-29 16:43:51'),
(55, 300, 1, 'in', 25.00, 0.00, 25.00, 'manual', NULL, 'Stok awal', '2025-12-29 16:46:59', '2025-12-29 16:46:59'),
(56, 301, 1, 'in', 440.00, 0.00, 440.00, 'manual', NULL, 'Stok awal', '2025-12-30 07:49:22', '2025-12-30 07:49:22'),
(57, 302, 1, 'in', 180.00, 0.00, 180.00, 'manual', NULL, 'Stok awal', '2025-12-30 07:52:32', '2025-12-30 07:52:32'),
(58, 303, 1, 'in', 50.00, 0.00, 50.00, 'manual', NULL, 'Stok awal', '2025-12-30 08:53:21', '2025-12-30 08:53:21'),
(59, 304, 1, 'in', 370.00, 0.00, 370.00, 'manual', NULL, 'Stok awal', '2025-12-30 09:05:34', '2025-12-30 09:05:34'),
(60, 305, 1, 'in', 950.00, 0.00, 950.00, 'manual', NULL, 'Stok awal', '2025-12-30 09:12:44', '2025-12-30 09:12:44'),
(61, 306, 1, 'in', 2.00, 0.00, 2.00, 'manual', NULL, 'Stok awal', '2025-12-30 09:13:43', '2025-12-30 09:13:43'),
(62, 307, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-30 09:19:47', '2025-12-30 09:19:47'),
(63, 301, 1, 'out', 110.00, 440.00, 330.00, 'fpb', '019b6d16-d89c-71c5-8094-de6f86ec8561', 'Permintaan FPB-FPB/004/BDB/XII/25 untuk SPK-SPK/001/BDB/XII/25', '2025-12-30 09:30:27', '2025-12-30 09:30:27'),
(64, 302, 1, 'out', 0.60, 180.00, 179.40, 'fpb', '019b6d16-d89c-71c5-8094-de6f86ec8561', 'Permintaan FPB-FPB/004/BDB/XII/25 untuk SPK-SPK/001/BDB/XII/25', '2025-12-30 09:30:27', '2025-12-30 09:30:27'),
(65, 303, 1, 'out', 50.00, 50.00, 0.00, 'fpb', '019b6d16-d89c-71c5-8094-de6f86ec8561', 'Permintaan FPB-FPB/004/BDB/XII/25 untuk SPK-SPK/001/BDB/XII/25', '2025-12-30 09:30:27', '2025-12-30 09:30:27'),
(66, 307, 1, 'out', 10.00, 100.00, 90.00, 'fpb', '019b6d16-d89c-71c5-8094-de6f86ec8561', 'Permintaan FPB-FPB/004/BDB/XII/25 untuk SPK-SPK/001/BDB/XII/25', '2025-12-30 09:30:27', '2025-12-30 09:30:27'),
(67, 250, 1, 'out', 5.00, 200.00, 195.00, 'fpb', '019b6d16-d89c-71c5-8094-de6f86ec8561', 'Permintaan FPB-FPB/004/BDB/XII/25 untuk SPK-SPK/001/BDB/XII/25', '2025-12-30 09:30:27', '2025-12-30 09:30:27'),
(68, 304, 1, 'out', 5.00, 370.00, 365.00, 'fpb', '019b6d16-d89c-71c5-8094-de6f86ec8561', 'Permintaan FPB-FPB/004/BDB/XII/25 untuk SPK-SPK/001/BDB/XII/25', '2025-12-30 09:30:27', '2025-12-30 09:30:27'),
(69, 306, 1, 'out', 0.60, 2.00, 1.40, 'fpb', '019b6d16-d89c-71c5-8094-de6f86ec8561', 'Permintaan FPB-FPB/004/BDB/XII/25 untuk SPK-SPK/001/BDB/XII/25', '2025-12-30 09:30:27', '2025-12-30 09:30:27'),
(70, 308, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-30 10:06:32', '2025-12-30 10:06:32'),
(71, 309, 1, 'in', 90.00, 0.00, 90.00, 'manual', NULL, 'Stok awal', '2025-12-30 10:07:49', '2025-12-30 10:07:49'),
(72, 310, 1, 'in', 30.00, 0.00, 30.00, 'manual', NULL, 'Stok awal (setelah approval)', '2025-12-31 09:22:16', '2025-12-31 09:22:16'),
(73, 311, 1, 'in', 103.00, 0.00, 103.00, 'manual', NULL, 'Stok awal', '2025-12-31 15:29:44', '2025-12-31 15:29:44'),
(74, 312, 1, 'in', 330.00, 0.00, 330.00, 'manual', NULL, 'Stok awal', '2025-12-31 15:35:16', '2025-12-31 15:35:16'),
(75, 301, 1, 'out', 100.00, 330.00, 230.00, 'fpb', '019b7395-58e9-723b-b587-9152bac75f64', 'Permintaan FPB-FPB/005/BDB/XII/25 untuk SPK-SPK/005/BDB/XII/25', '2025-12-31 15:45:40', '2025-12-31 15:45:40'),
(76, 301, 1, 'out', 50.00, 230.00, 180.00, 'fpb', '019b7398-de47-71d5-acb1-e2db8abe21f0', 'Permintaan FPB-FPB/006/BDB/XII/25 untuk SPK-SPK/006/BDB/XII/25', '2025-12-31 15:49:26', '2025-12-31 15:49:26'),
(77, 313, 1, 'in', 32.00, 0.00, 32.00, 'manual', NULL, 'Stok awal', '2025-12-31 15:54:52', '2025-12-31 15:54:52'),
(78, 314, 1, 'in', 2.00, 0.00, 2.00, 'manual', NULL, 'Stok awal', '2025-12-31 15:59:07', '2025-12-31 15:59:07'),
(79, 315, 1, 'in', 2.00, 0.00, 2.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:01:47', '2025-12-31 16:01:47'),
(80, 316, 1, 'in', 51.00, 0.00, 51.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:04:42', '2025-12-31 16:04:42'),
(81, 317, 1, 'in', 60.00, 0.00, 60.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:05:15', '2025-12-31 16:05:15'),
(82, 318, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:10:31', '2025-12-31 16:10:31'),
(83, 319, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:11:11', '2025-12-31 16:11:11'),
(84, 320, 1, 'in', 150.00, 0.00, 150.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:11:56', '2025-12-31 16:11:56'),
(85, 321, 1, 'in', 150.00, 0.00, 150.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:12:43', '2025-12-31 16:12:43'),
(86, 322, 1, 'in', 150.00, 0.00, 150.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:13:23', '2025-12-31 16:13:23'),
(87, 323, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:14:00', '2025-12-31 16:14:00'),
(88, 324, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:15:37', '2025-12-31 16:15:37'),
(89, 325, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:16:04', '2025-12-31 16:16:04'),
(90, 326, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:16:47', '2025-12-31 16:16:47'),
(91, 301, 1, 'out', 19.50, 180.00, 160.50, 'fpb', '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'Permintaan FPB-FPB/007/BDB/XII/25 untuk SPK-SPK/007/BDB/XII/25', '2025-12-31 16:22:10', '2025-12-31 16:22:10'),
(92, 321, 1, 'out', 2.65, 150.00, 147.35, 'fpb', '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'Permintaan FPB-FPB/007/BDB/XII/25 untuk SPK-SPK/007/BDB/XII/25', '2025-12-31 16:22:10', '2025-12-31 16:22:10'),
(93, 319, 1, 'out', 3.00, 100.00, 97.00, 'fpb', '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'Permintaan FPB-FPB/007/BDB/XII/25 untuk SPK-SPK/007/BDB/XII/25', '2025-12-31 16:22:10', '2025-12-31 16:22:10'),
(94, 318, 1, 'out', 1.95, 200.00, 198.05, 'fpb', '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'Permintaan FPB-FPB/007/BDB/XII/25 untuk SPK-SPK/007/BDB/XII/25', '2025-12-31 16:22:10', '2025-12-31 16:22:10'),
(95, 307, 1, 'out', 1.50, 90.00, 88.50, 'fpb', '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'Permintaan FPB-FPB/007/BDB/XII/25 untuk SPK-SPK/007/BDB/XII/25', '2025-12-31 16:22:10', '2025-12-31 16:22:10'),
(96, 250, 1, 'out', 1.50, 195.00, 193.50, 'fpb', '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'Permintaan FPB-FPB/007/BDB/XII/25 untuk SPK-SPK/007/BDB/XII/25', '2025-12-31 16:22:10', '2025-12-31 16:22:10'),
(97, 306, 1, 'out', 0.13, 1.40, 1.27, 'fpb', '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'Permintaan FPB-FPB/007/BDB/XII/25 untuk SPK-SPK/007/BDB/XII/25', '2025-12-31 16:22:10', '2025-12-31 16:22:10'),
(98, 268, 1, 'out', 4.00, 129.00, 125.00, 'fpb', '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'Permintaan FPB-FPB/007/BDB/XII/25 untuk SPK-SPK/007/BDB/XII/25', '2025-12-31 16:22:10', '2025-12-31 16:22:10'),
(99, 316, 1, 'out', 4.00, 51.00, 47.00, 'fpb', '019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'Permintaan FPB-FPB/007/BDB/XII/25 untuk SPK-SPK/007/BDB/XII/25', '2025-12-31 16:22:10', '2025-12-31 16:22:10'),
(100, 327, 1, 'in', 87.00, 0.00, 87.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:42:57', '2025-12-31 16:42:57'),
(101, 328, 1, 'in', 20.00, 0.00, 20.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:46:34', '2025-12-31 16:46:34'),
(102, 329, 1, 'in', 80.00, 0.00, 80.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:47:18', '2025-12-31 16:47:18'),
(103, 330, 1, 'in', 80.00, 0.00, 80.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:50:13', '2025-12-31 16:50:13'),
(104, 331, 1, 'in', 80.00, 0.00, 80.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:51:51', '2025-12-31 16:51:51'),
(105, 332, 1, 'in', 100.00, 0.00, 100.00, 'manual', NULL, 'Stok awal', '2025-12-31 16:52:59', '2025-12-31 16:52:59'),
(106, 331, 1, 'out', 60.00, 80.00, 20.00, 'fpb', '019b73d5-1c9a-73f1-895e-599a07b9926e', 'Permintaan FPB-FPB/008/BDB/XII/25 untuk SPK-SPK/008/BDB/XII/25', '2025-12-31 16:55:15', '2025-12-31 16:55:15'),
(107, 332, 1, 'out', 19.00, 100.00, 81.00, 'fpb', '019b73d5-1c9a-73f1-895e-599a07b9926e', 'Permintaan FPB-FPB/008/BDB/XII/25 untuk SPK-SPK/008/BDB/XII/25', '2025-12-31 16:55:15', '2025-12-31 16:55:15'),
(108, 318, 1, 'out', 2.70, 198.05, 195.35, 'fpb', '019b73d5-1c9a-73f1-895e-599a07b9926e', 'Permintaan FPB-FPB/008/BDB/XII/25 untuk SPK-SPK/008/BDB/XII/25', '2025-12-31 16:55:15', '2025-12-31 16:55:15'),
(109, 319, 1, 'out', 3.50, 97.00, 93.50, 'fpb', '019b73d5-1c9a-73f1-895e-599a07b9926e', 'Permintaan FPB-FPB/008/BDB/XII/25 untuk SPK-SPK/008/BDB/XII/25', '2025-12-31 16:55:15', '2025-12-31 16:55:15'),
(110, 268, 1, 'out', 11.00, 125.00, 114.00, 'fpb', '019b73d5-1c9a-73f1-895e-599a07b9926e', 'Permintaan FPB-FPB/008/BDB/XII/25 untuk SPK-SPK/008/BDB/XII/25', '2025-12-31 16:55:15', '2025-12-31 16:55:15'),
(111, 316, 1, 'out', 11.00, 47.00, 36.00, 'fpb', '019b73d5-1c9a-73f1-895e-599a07b9926e', 'Permintaan FPB-FPB/008/BDB/XII/25 untuk SPK-SPK/008/BDB/XII/25', '2025-12-31 16:55:15', '2025-12-31 16:55:15'),
(112, 333, 1, 'in', 480.00, 0.00, 480.00, 'manual', NULL, 'Stok awal', '2026-01-07 08:09:25', '2026-01-07 08:09:25'),
(113, 334, 1, 'in', 400.00, 0.00, 400.00, 'manual', NULL, 'Stok awal', '2026-01-07 08:12:09', '2026-01-07 08:12:09'),
(114, 336, 1, 'in', 716.00, 0.00, 716.00, 'manual', NULL, 'Stok awal', '2026-01-07 08:15:00', '2026-01-07 08:15:00'),
(115, 335, 1, 'in', 475.00, 0.00, 475.00, 'manual', NULL, 'Stok awal (setelah approval)', '2026-01-07 08:15:29', '2026-01-07 08:15:29'),
(116, 337, 1, 'in', 280.00, 0.00, 280.00, 'manual', NULL, 'Stok awal (setelah approval)', '2026-01-07 08:18:32', '2026-01-07 08:18:32'),
(117, 338, 1, 'in', 120.00, 0.00, 120.00, 'manual', NULL, 'Stok awal (setelah approval)', '2026-01-07 08:21:20', '2026-01-07 08:21:20'),
(118, 339, 1, 'in', 1000.00, 0.00, 1000.00, 'manual', NULL, 'Stok awal (setelah approval)', '2026-01-07 08:21:21', '2026-01-07 08:21:21'),
(119, 340, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal (setelah approval)', '2026-01-07 08:23:18', '2026-01-07 08:23:18'),
(120, 341, 1, 'in', 160.00, 0.00, 160.00, 'manual', NULL, 'Stok awal (setelah approval)', '2026-01-07 08:25:01', '2026-01-07 08:25:01'),
(121, 342, 1, 'in', 200.00, 0.00, 200.00, 'manual', NULL, 'Stok awal', '2026-01-07 08:26:57', '2026-01-07 08:26:57'),
(122, 343, 1, 'in', 150.00, 0.00, 150.00, 'manual', NULL, 'Stok awal', '2026-01-07 08:28:25', '2026-01-07 08:28:25'),
(123, 333, 1, 'out', 20.00, 480.00, 460.00, 'fpb', '019bbf5b-194b-7340-bd65-1cf45bf90b27', 'Permintaan FPB-FPB/001/BDB/I/26 untuk SPK-SPK/002/BDB/I/26', '2026-01-15 08:54:18', '2026-01-15 08:54:18'),
(124, 250, 1, 'out', 10.00, 193.50, 183.50, 'fpb', '019bbf5b-194b-7340-bd65-1cf45bf90b27', 'Permintaan FPB-FPB/001/BDB/I/26 untuk SPK-SPK/002/BDB/I/26', '2026-01-15 08:54:18', '2026-01-15 08:54:18'),
(125, 304, 1, 'out', 10.00, 365.00, 355.00, 'fpb', '019bbf5b-194b-7340-bd65-1cf45bf90b27', 'Permintaan FPB-FPB/001/BDB/I/26 untuk SPK-SPK/002/BDB/I/26', '2026-01-15 08:54:18', '2026-01-15 08:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `code`, `name`, `phone`, `email`, `address`, `contact_person`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SUP-0001', 'PT. JUSTUS KIMIA RAYA', '651 5188', 'sales_jkr@justus.co.id', 'Wisma Justus,Jl.Danau Sunter Utara No.27-28,Blok 03 Jakarta - Indonesia', 'KANTOR', 1, '2026-01-05 10:43:02', '2026-01-05 10:43:02'),
(2, 'SUP-0002', 'PT. MULTI FLASOL INDONESIA', NULL, NULL, 'MUARA KARANG BLOK D4 U/23 NORTH JAKARTA', 'BPK HARDI', 1, '2026-01-05 10:47:45', '2026-01-05 10:47:45'),
(3, 'SUP-0003', 'PT. BERUANG MAS', '082158304064', 'beruang@cbn.net.id', 'citta graha complex block 1-k jl panjang no 26 kedoya selatan jakarta barat', 'KANTOR', 1, '2026-01-05 10:50:41', '2026-01-05 10:50:41'),
(4, 'SUP-0004', 'PT. ASCC', '082139700658', NULL, NULL, 'KANTOR', 1, '2026-01-05 10:53:49', '2026-01-05 10:53:49'),
(5, 'SUP-0005', 'PT. JING WEI', '081199911131', 'contact@jingwei-nti.com', 'jl puri indah raya no 3 rt3 rw2 kembangan selatan jakarta', 'KANTOR', 1, '2026-01-05 10:55:55', '2026-01-05 10:55:55'),
(6, 'SUP-0006', 'PT. TAWAZON CHEMICAL', '082129867783', 'info@tawazon.ae', 'vivo busines park blok I no 6jl pembangunan Lii kel karangsari kec.neglasarikota tangerang', 'KANTOR', 1, '2026-01-05 10:59:27', '2026-01-05 10:59:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','operasional','finance') NOT NULL DEFAULT 'operasional',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `signature_path`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Mulyadi', 'mulyadi@bdb.site', NULL, NULL, '$2y$12$iiqaMRALr4A8X8CiojCDIuFzES8TUcFXRKCBdrQl4m37Qe31oI71G', 'owner', 1, 'sOaBh6kUZCQ5iLiWQAGlvHYZ4Cmja2zb9axFNPaBMrhLMHQUA0mcFIqSHCNv', '2025-12-27 14:56:59', '2025-12-27 14:56:59'),
(2, 'Yuyun', 'yuyun.naelufar@bdb.site', NULL, NULL, '$2y$12$LDobBx9zMI5aJy/zKQTw5ev0oihSp5Bi8j9xeff7EFGkzKhe2kiqe', 'finance', 1, NULL, '2025-12-27 14:56:59', '2025-12-27 14:56:59'),
(4, 'Fiqri Haiqal', 'fiqri.haiqal@bdb.site', NULL, NULL, '$2y$12$WRIyXRNRytbyUvC7POUp7.01VDJF9tdurFonL7YGX0TfBBgG5TOyi', 'operasional', 1, NULL, '2025-12-27 14:57:00', '2025-12-27 14:57:00'),
(5, 'Admin', 'admin@bdb.site', NULL, NULL, '$2y$12$o8lAsczl4I1ZB.iBrPe06uiv.azb9HC5kuv3izYlNirmGmSBqKL0K', 'operasional', 1, 'TGWEjHo03sKc1jZXF5GHPqlce81WDTHlHPAwxUhU5odthNR3OnuNwgF9ixcf', '2025-12-27 14:57:00', '2025-12-27 14:57:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `customers_code_unique` (`code`);

--
-- Indexes for table `delivery_notes`
--
ALTER TABLE `delivery_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_notes_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `delivery_notes_sj_number_unique` (`sj_number`),
  ADD KEY `delivery_notes_customer_id_foreign` (`customer_id`),
  ADD KEY `delivery_notes_created_by_foreign` (`created_by`),
  ADD KEY `delivery_notes_special_order_id_foreign` (`special_order_id`),
  ADD KEY `delivery_notes_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `delivery_note_items`
--
ALTER TABLE `delivery_note_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_note_items_delivery_note_id_foreign` (`delivery_note_id`),
  ADD KEY `delivery_note_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fpb`
--
ALTER TABLE `fpb`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fpb_fpb_number_unique` (`fpb_number`),
  ADD KEY `fpb_spk_id_foreign` (`spk_id`),
  ADD KEY `fpb_special_order_id_foreign` (`special_order_id`),
  ADD KEY `fpb_created_by_foreign` (`created_by`),
  ADD KEY `fpb_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `fpb_items`
--
ALTER TABLE `fpb_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fpb_items_fpb_id_foreign` (`fpb_id`),
  ADD KEY `fpb_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD UNIQUE KEY `invoices_uuid_unique` (`uuid`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`),
  ADD KEY `invoices_created_by_foreign` (`created_by`),
  ADD KEY `invoices_approved_by_foreign` (`approved_by`),
  ADD KEY `invoices_delivery_note_id_foreign` (`delivery_note_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_items_product_id_foreign` (`product_id`);

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
-- Indexes for table `job_costs`
--
ALTER TABLE `job_costs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_costs_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `job_costs_job_cost_number_unique` (`job_cost_number`),
  ADD KEY `job_costs_created_by_foreign` (`created_by`),
  ADD KEY `job_costs_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `job_cost_items`
--
ALTER TABLE `job_cost_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_cost_items_job_cost_id_foreign` (`job_cost_id`),
  ADD KEY `job_cost_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_is_read_created_at_index` (`user_id`,`is_read`,`created_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `production_logs`
--
ALTER TABLE `production_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_logs_spk_id_foreign` (`spk_id`),
  ADD KEY `production_logs_spk_item_id_foreign` (`spk_item_id`),
  ADD KEY `production_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_code_unique` (`code`),
  ADD UNIQUE KEY `products_uuid_unique` (`uuid`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_created_by_foreign` (`created_by`),
  ADD KEY `products_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchases_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `purchases_purchase_number_unique` (`purchase_number`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchases_created_by_foreign` (`created_by`),
  ADD KEY `purchases_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `special_orders`
--
ALTER TABLE `special_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `special_orders_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `special_orders_so_number_unique` (`so_number`),
  ADD KEY `special_orders_customer_id_foreign` (`customer_id`),
  ADD KEY `special_orders_created_by_foreign` (`created_by`),
  ADD KEY `special_orders_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `special_order_items`
--
ALTER TABLE `special_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `special_order_items_special_order_id_foreign` (`special_order_id`),
  ADD KEY `special_order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `spk`
--
ALTER TABLE `spk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `spk_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `spk_spk_number_unique` (`spk_number`),
  ADD KEY `spk_created_by_foreign` (`created_by`),
  ADD KEY `spk_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `spk_items`
--
ALTER TABLE `spk_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spk_items_spk_id_foreign` (`spk_id`),
  ADD KEY `spk_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `spk_production_logs`
--
ALTER TABLE `spk_production_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spk_production_logs_spk_item_id_foreign` (`spk_item_id`),
  ADD KEY `spk_production_logs_created_by_foreign` (`created_by`),
  ADD KEY `spk_production_logs_spk_id_work_date_index` (`spk_id`,`work_date`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`),
  ADD KEY `stock_movements_product_id_created_at_index` (`product_id`,`created_at`),
  ADD KEY `stock_movements_reference_type_reference_id_index` (`reference_type`,`reference_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_code_unique` (`code`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `delivery_notes`
--
ALTER TABLE `delivery_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delivery_note_items`
--
ALTER TABLE `delivery_note_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fpb_items`
--
ALTER TABLE `fpb_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_costs`
--
ALTER TABLE `job_costs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_cost_items`
--
ALTER TABLE `job_cost_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `production_logs`
--
ALTER TABLE `production_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `special_orders`
--
ALTER TABLE `special_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `special_order_items`
--
ALTER TABLE `special_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `spk`
--
ALTER TABLE `spk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `spk_items`
--
ALTER TABLE `spk_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `spk_production_logs`
--
ALTER TABLE `spk_production_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery_notes`
--
ALTER TABLE `delivery_notes`
  ADD CONSTRAINT `delivery_notes_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delivery_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `delivery_notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `delivery_notes_special_order_id_foreign` FOREIGN KEY (`special_order_id`) REFERENCES `special_orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `delivery_note_items`
--
ALTER TABLE `delivery_note_items`
  ADD CONSTRAINT `delivery_note_items_delivery_note_id_foreign` FOREIGN KEY (`delivery_note_id`) REFERENCES `delivery_notes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_note_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `fpb`
--
ALTER TABLE `fpb`
  ADD CONSTRAINT `fpb_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fpb_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fpb_special_order_id_foreign` FOREIGN KEY (`special_order_id`) REFERENCES `special_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fpb_spk_id_foreign` FOREIGN KEY (`spk_id`) REFERENCES `spk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fpb_items`
--
ALTER TABLE `fpb_items`
  ADD CONSTRAINT `fpb_items_fpb_id_foreign` FOREIGN KEY (`fpb_id`) REFERENCES `fpb` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fpb_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_delivery_note_id_foreign` FOREIGN KEY (`delivery_note_id`) REFERENCES `delivery_notes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `job_costs`
--
ALTER TABLE `job_costs`
  ADD CONSTRAINT `job_costs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `job_costs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `job_cost_items`
--
ALTER TABLE `job_cost_items`
  ADD CONSTRAINT `job_cost_items_job_cost_id_foreign` FOREIGN KEY (`job_cost_id`) REFERENCES `job_costs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_cost_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_logs`
--
ALTER TABLE `production_logs`
  ADD CONSTRAINT `production_logs_spk_id_foreign` FOREIGN KEY (`spk_id`) REFERENCES `spk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_logs_spk_item_id_foreign` FOREIGN KEY (`spk_item_id`) REFERENCES `spk_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `special_orders`
--
ALTER TABLE `special_orders`
  ADD CONSTRAINT `special_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `special_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `special_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `special_order_items`
--
ALTER TABLE `special_order_items`
  ADD CONSTRAINT `special_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `special_order_items_special_order_id_foreign` FOREIGN KEY (`special_order_id`) REFERENCES `special_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spk`
--
ALTER TABLE `spk`
  ADD CONSTRAINT `spk_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `spk_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `spk_items`
--
ALTER TABLE `spk_items`
  ADD CONSTRAINT `spk_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `spk_items_spk_id_foreign` FOREIGN KEY (`spk_id`) REFERENCES `spk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spk_production_logs`
--
ALTER TABLE `spk_production_logs`
  ADD CONSTRAINT `spk_production_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `spk_production_logs_spk_id_foreign` FOREIGN KEY (`spk_id`) REFERENCES `spk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spk_production_logs_spk_item_id_foreign` FOREIGN KEY (`spk_item_id`) REFERENCES `spk_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- 
-- ================================================================
-- PATCH TABLES TO NEW STRUCTURE (ADDED BY ANTIGRAVITY)
-- ================================================================
-- 

-- 1. Users: Signature Path
SET @dbname = DATABASE();
SET @tablename = "users";
SET @columnname = "signature_path";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE users ADD COLUMN signature_path varchar(255) NULL AFTER email"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 2. Customers: Code
SET @tablename = "customers";
SET @columnname = "code";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE customers ADD COLUMN code varchar(10) NULL AFTER id"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 3. Suppliers Table
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Purchases Table
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `purchase_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','pending','approved','received','cancelled') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_uuid_unique` (`uuid`),
  UNIQUE KEY `purchases_purchase_number_unique` (`purchase_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Purchase Items Table
CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Notifications Table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Invoices: Delivery Note ID column
SET @tablename = "invoices";
SET @columnname = "delivery_note_id";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE invoices ADD COLUMN delivery_note_id bigint(20) UNSIGNED NULL AFTER customer_id"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

