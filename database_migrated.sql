-- Data migration from old database to new schema
-- Generated: 2026-01-24
-- Changes: Removed special_orders tables, adjusted spk/delivery_notes/fpb columns

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table: categories
-- --------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `slug`, `type`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bahan Baku', 'bahan-baku', 'bahan_baku', 'Bahan baku untuk produksi cat', 1, '2025-12-27 14:57:00', '2025-12-27 14:57:00'),
(2, 'Material/Packaging', 'packaging', 'packaging', 'Material dan packaging produksi', 1, '2025-12-27 14:57:00', '2025-12-27 14:57:00'),
(3, 'Barang Jadi', 'barang-jadi', 'barang_jadi', 'Produk jadi siap jual', 1, '2025-12-27 14:57:00', '2025-12-27 14:57:00');

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `signature_path`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Mulyadi', 'mulyadi@bdb.site', NULL, NULL, '$2y$12$iiqaMRALr4A8X8CiojCDIuFzES8TUcFXRKCBdrQl4m37Qe31oI71G', 'owner', 1, 'sOaBh6kUZCQ5iLiWQAGlvHYZ4Cmja2zb9axFNPaBMrhLMHQUA0mcFIqSHCNv', '2025-12-27 14:56:59', '2025-12-27 14:56:59'),
(2, 'Yuyun', 'yuyun.naelufar@bdb.site', NULL, NULL, '$2y$12$LDobBx9zMI5aJy/zKQTw5ev0oihSp5Bi8j9xeff7EFGkzKhe2kiqe', 'finance', 1, NULL, '2025-12-27 14:56:59', '2025-12-27 14:56:59'),
(4, 'Fiqri Haiqal', 'fiqri.haiqal@bdb.site', NULL, NULL, '$2y$12$WRIyXRNRytbyUvC7POUp7.01VDJF9tdurFonL7YGX0TfBBgG5TOyi', 'operasional', 1, NULL, '2025-12-27 14:57:00', '2025-12-27 14:57:00'),
(5, 'Admin', 'admin@bdb.site', NULL, NULL, '$2y$12$o8lAsczl4I1ZB.iBrPe06uiv.azb9HC5kuv3izYlNirmGmSBqKL0K', 'operasional', 1, 'TGWEjHo03sKc1jZXF5GHPqlce81WDTHlHPAwxUhU5odthNR3OnuNwgF9ixcf', '2025-12-27 14:57:00', '2025-12-27 14:57:00');

-- --------------------------------------------------------
-- Table: customers
-- --------------------------------------------------------
INSERT INTO `customers` (`id`, `code`, `uuid`, `name`, `email`, `phone`, `address`, `company`, `is_active`, `created_at`, `updated_at`) VALUES
(11, 'RSPD', 'c462211b-9242-4167-b6aa-b7ff9985ebe1', 'PAK RUSPANDI', NULL, NULL, 'Cikarang Pusat Jawa Barat', 'PT. NAF TEKNIK UTAMA', 1, '2025-12-27 16:53:59', '2025-12-29 18:40:19'),
(12, 'AGS', '6ae084a2-e00b-4eca-a2a6-7cca04d30fc4', 'PAK ANDRI AGASI', NULL, NULL, 'Bogor Barat', NULL, 1, '2025-12-29 18:41:21', '2025-12-29 18:41:21'),
(13, 'AII', 'd5649685-6a66-4408-8db0-71b8dd768f9f', 'PT ARTIMA INDUSTRI INDONESIA', NULL, NULL, 'Kawasan Industri Jatake', 'PT ARTIMA INDUSTRI INDONESIA', 1, '2025-12-29 18:42:49', '2025-12-29 18:42:49'),
(14, 'EKO', '4c4d626c-041c-4d6d-8006-34c74c927b16', 'PAK EKO', NULL, '087884859326', 'RLI POLIMER TEKNOLOGI\r\nBABAKAN SENTRAL RT008, RW05,SUKAPURA,KIARACONDONG,KOTA BANDUNG,JAWA BARAT', NULL, 1, '2025-12-30 09:58:16', '2025-12-30 09:58:16'),
(15, 'YYK', 'b88a2ba5-fd51-4200-91bc-d3ab80a491cb', 'PAK YOYOK', NULL, NULL, NULL, 'APLIKATOR', 1, '2025-12-31 15:24:53', '2025-12-31 15:24:53'),
(16, 'STR', '68d9b1bb-a853-4ff5-bac5-694534833030', 'SUTRISNO', NULL, NULL, NULL, NULL, 1, '2025-12-31 15:36:03', '2025-12-31 15:36:03'),
(17, 'PFR', '03ff730f-864a-428b-b6b3-e8fc9d877868', 'PAK FAJAR RAMADHAN', NULL, '02155775291', 'RUKO AZOROES BLOK B17A NO29 CIPONDOH TANGERANG', NULL, 1, '2026-01-05 11:02:28', '2026-01-05 11:02:28');

-- --------------------------------------------------------
-- Table: suppliers
-- --------------------------------------------------------
INSERT INTO `suppliers` (`id`, `code`, `name`, `phone`, `email`, `address`, `contact_person`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SUP-0001', 'PT. JUSTUS KIMIA RAYA', '651 5188', 'sales_jkr@justus.co.id', 'Wisma Justus,Jl.Danau Sunter Utara No.27-28,Blok 03 Jakarta - Indonesia', 'KANTOR', 1, '2026-01-05 10:43:02', '2026-01-05 10:43:02'),
(2, 'SUP-0002', 'PT. MULTI FLASOL INDONESIA', NULL, NULL, 'MUARA KARANG BLOK D4 U/23 NORTH JAKARTA', 'BPK HARDI', 1, '2026-01-05 10:47:45', '2026-01-05 10:47:45'),
(3, 'SUP-0003', 'PT. BERUANG MAS', '082158304064', 'beruang@cbn.net.id', 'citta graha complex block 1-k jl panjang no 26 kedoya selatan jakarta barat', 'KANTOR', 1, '2026-01-05 10:50:41', '2026-01-05 10:50:41'),
(4, 'SUP-0004', 'PT. ASCC', '082139700658', NULL, NULL, 'KANTOR', 1, '2026-01-05 10:53:49', '2026-01-05 10:53:49'),
(5, 'SUP-0005', 'PT. JING WEI', '081199911131', 'contact@jingwei-nti.com', 'jl puri indah raya no 3 rt3 rw2 kembangan selatan jakarta', 'KANTOR', 1, '2026-01-05 10:55:55', '2026-01-05 10:55:55'),
(6, 'SUP-0006', 'PT. TAWAZON CHEMICAL', '082129867783', 'info@tawazon.ae', 'vivo busines park blok I no 6jl pembangunan Lii kel karangsari kec.neglasarikota tangerang', 'KANTOR', 1, '2026-01-05 10:59:27', '2026-01-05 10:59:27');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- Products data from old database
-- Part 2 of migration

SET FOREIGN_KEY_CHECKS=0;

INSERT INTO `products` (`id`, `uuid`, `code`, `name`, `category_id`, `spec_type`, `current_stock`, `min_stock`, `unit`, `unit_packing`, `purchase_price`, `selling_price`, `supplier_type`, `description`, `is_active`, `approval_status`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(247, '47ccfa48-36d9-4c5f-8594-e806d3c00ecf', 'R-128', 'Resin 128', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 0, 'deleted', 1, 1, '2025-12-30 07:46:38', '2025-12-27 15:05:53', '2025-12-30 07:46:38'),
(248, 'e851efef-935a-46b3-a33e-13c901aa2224', 'R-Pls', 'Resin Polyester', 1, 'high_spec', 180.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-27 15:06:31', '2025-12-27 15:06:31', '2025-12-27 15:06:31'),
(249, '560e794e-bf2d-4ad5-95d9-4276f9e307a9', 'R-Pu', 'Resin Pu', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-27 15:08:13', '2025-12-27 15:08:13', '2025-12-27 15:08:13'),
(250, 'fd683f88-4c6d-4643-b596-f590c2dcf4a5', 'T- Pm', 'Thinner Pm', 1, 'high_spec', 183.50, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-27 15:08:43', '2025-12-27 15:08:43', '2026-01-15 08:54:18'),
(260, '41fb4984-eccb-42eb-b04b-f296be11d739', 'B-C', 'Base Clear SL', 3, NULL, 400.00, 100.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-27 16:04:01', '2025-12-27 16:04:01', '2025-12-27 16:04:01'),
(261, '46777463-af77-4a01-b329-0df6cb89969b', 'B-CT', 'Base Clear TC', 3, NULL, 400.00, 100.00, 'kg', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-27 16:04:44', '2025-12-27 16:04:44', '2025-12-27 16:04:44'),
(266, '11c71a37-c238-4671-b041-14d074c71577', 'R-X75', 'RESIN X75', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 4, 1, '2025-12-29 00:09:58', '2025-12-29 00:04:57', '2025-12-29 00:09:58'),
(267, '6b5eec1a-437c-45f0-90fb-f7302cbf3b9f', 'AP-1041', 'HARDENER AP-1041', 1, 'high_spec', 200.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-29 00:12:04', '2025-12-29 00:12:04', '2025-12-29 00:12:04'),
(268, 'cf56fb5c-70d8-42c5-82a8-5d373f91c0ed', 'P-Slvr', 'PAIL SILVER', 2, NULL, 114.00, 50.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:23:51', '2025-12-29 14:23:51', '2025-12-31 16:55:15'),
(269, '33bed1d9-2eff-49c3-bb68-43969ca82e7e', 'P-BRU', 'PAIL BIRU', 2, NULL, 51.00, 20.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:24:32', '2025-12-29 14:24:32', '2025-12-29 14:24:32'),
(270, '468e8346-87b1-4676-be62-dd8a098062a1', 'P-OTO', 'PAIL OTOPAINT', 2, NULL, 246.00, 50.00, 'pcs', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-29 14:25:15', '2025-12-29 14:25:15', '2025-12-29 14:25:15'),
(302, '2812b751-6463-404c-92a8-f2d65b4bd912', 'DSPRLN', 'DISPARLON', 1, 'medium_spec', 179.40, 50.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-30 07:52:32', '2025-12-30 07:52:32', '2025-12-30 09:30:27'),
(304, '9f3ba660-6f41-4523-8383-3f3c4598683c', 'T-BC', 'THINNER-BC', 1, 'high_spec', 355.00, 100.00, 'kg', NULL, 0.00, 0.00, 'agen', NULL, 1, 'approved', 1, 1, '2025-12-30 09:05:34', '2025-12-30 09:05:34', '2026-01-15 08:54:18'),
(306, 'cf607908-37e1-41ba-a51f-dcd5c61c32c3', 'BYK-077', 'DISSPERS BYK-077', 1, 'high_spec', 1.27, 20.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-30 09:13:43', '2025-12-30 09:13:43', '2025-12-31 16:22:10'),
(307, '7d901b9c-fd15-4ad2-87f3-bb9eb4245631', 'T-AGE', 'THINNER-AGE', 1, 'high_spec', 88.50, 50.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2025-12-30 09:19:47', '2025-12-30 09:19:47', '2025-12-31 16:22:10'),
(312, '46a853fa-bfc7-427d-8008-7be2e105b0ef', 'R.128', 'RESIN 128', 3, NULL, 330.00, 100.00, 'KG', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 15:35:16', '2025-12-31 15:35:16', '2025-12-31 15:35:16'),
(314, '83125f97-4147-4c6d-bfc3-9672e503c0f1', 'E.PG', 'EPOXY PRIMER GREY', 3, NULL, 2.00, 5.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 15:59:07', '2025-12-31 15:59:07', '2025-12-31 15:59:07'),
(315, '32aec653-3c9a-4ec9-b3c7-0a0c328eba14', 'ESL', 'EPOXY SL GREEN PASTEL', 3, NULL, 2.00, 5.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:01:47', '2025-12-31 16:01:47', '2025-12-31 16:01:47'),
(327, 'd9a36c64-f834-48f6-9b17-c3654bac34ab', 'E-YG', 'EPOXY SL YELLOW GREEN 4:1', 3, NULL, 87.00, 20.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:42:57', '2025-12-31 16:42:57', '2025-12-31 16:44:43'),
(328, '253afa1e-4281-4eb1-96ba-3e2b5bea5d27', 'E-LG', 'EPOXY SL LIGHT GREY', 3, NULL, 20.00, 50.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:46:34', '2025-12-31 16:46:34', '2025-12-31 16:46:34'),
(329, 'df4e3b29-1d91-4a0a-839c-8fe0b4bdaaca', 'E-P.C', 'EPOXY PRIMER CLEAR', 3, NULL, 80.00, 20.00, 'PAIL', NULL, 0.00, 0.00, NULL, NULL, 1, 'approved', 1, 1, '2025-12-31 16:47:18', '2025-12-31 16:47:18', '2025-12-31 16:47:18'),
(333, '6a76c88d-dbe6-429a-b7fc-5ba11244f6c3', 'R-E128', 'RESIN EPOXY 128', 1, 'high_spec', 460.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2026-01-07 08:09:25', '2026-01-07 08:09:25', '2026-01-15 08:54:18'),
(334, 'b95cdf83-c6b1-4b81-975e-9d6cb270a146', 'KH816', 'HARDENER EPOXY 816', 1, 'high_spec', 400.00, 100.00, 'kg', NULL, 0.00, 0.00, 'supplier_resmi', NULL, 1, 'approved', 1, 1, '2026-01-07 08:12:09', '2026-01-07 08:12:09', '2026-01-07 08:12:09');

SET FOREIGN_KEY_CHECKS=1;
-- SPK data adapted to new schema (WITHOUT special_order_id column)
-- Part 3 of migration

SET FOREIGN_KEY_CHECKS=0;

-- SPK table (REMOVED special_order_id column)
INSERT INTO `spk` (`id`, `uuid`, `spk_number`, `spk_type`, `created_by`, `approved_by`, `status`, `production_date`, `deadline`, `notes`, `approved_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, '94523079-eb87-497b-a020-f16d5ebda1d3', 'SPK/001/BDB/XII/25', 'base', 1, 1, 'approved', '2025-12-27', '2025-12-27', 'untuk stok Januari', '2025-12-27 16:08:40', NULL, '2025-12-27 16:08:40', '2025-12-27 16:08:40'),
(2, 'aadab851-e1af-460a-9fba-71f835361b36', 'SPK/002/BDB/XII/25', 'base', 4, 1, 'approved', '2025-12-27', '2025-12-27', 'untuk stok Januari', '2025-12-27 16:10:26', NULL, '2025-12-27 16:10:19', '2025-12-27 16:10:26'),
(3, '87272807-c8e5-4d82-935a-dc8ea2826142', 'SPK/003/BDB/XII/25', 'finishgood', 1, 1, 'completed', '2025-12-27', NULL, 'dfasdasdasd', '2025-12-27 16:56:14', '2025-12-27 18:45:02', '2025-12-27 16:56:14', '2025-12-27 18:45:02'),
(4, '1ffbdd56-b2e2-435c-8821-ef07a2fddca9', 'SPK/004/BDB/XII/25', 'base', 4, 1, 'in_progress', '2025-12-30', '2025-12-30', NULL, '2025-12-30 08:36:23', NULL, '2025-12-30 08:33:12', '2025-12-30 09:38:20'),
(5, 'd41be1ac-87d0-4529-9bc0-b25a37efe72f', 'SPK/005/BDB/XII/25', 'finishgood', 1, 1, 'approved', '2025-12-30', '2025-12-30', 'SPK Produksi', '2025-12-31 15:44:45', NULL, '2025-12-31 15:44:45', '2025-12-31 15:44:45'),
(6, 'b7e1f5c5-db59-482e-8da1-28903ab365e5', 'SPK/006/BDB/XII/25', 'finishgood', 1, 1, 'approved', '2025-12-31', '2025-12-31', 'SPK Produksi', '2025-12-31 15:48:48', NULL, '2025-12-31 15:48:48', '2025-12-31 15:48:48'),
(7, 'af778015-3d86-4537-8b45-568a813372fc', 'SPK/007/BDB/XII/25', 'finishgood', 1, 1, 'approved', '2025-12-30', '2025-12-31', 'SPK Produksi', '2025-12-31 16:03:15', NULL, '2025-12-31 16:03:15', '2025-12-31 16:03:15'),
(8, 'b330f07c-41f1-4b7f-a43a-ac4d54da84f9', 'SPK/008/BDB/XII/25', 'finishgood', 1, 1, 'approved', '2025-12-30', '2025-12-31', 'SPK Produksi', '2025-12-31 16:48:53', NULL, '2025-12-31 16:48:53', '2025-12-31 16:48:53'),
(9, 'd7d67d16-fc48-421e-b82e-1ae7871de07f', 'SPK/001/BDB/I/26', 'base', 5, 1, 'approved', '2026-01-15', '2026-01-15', NULL, '2026-01-15 08:50:46', NULL, '2026-01-15 08:41:14', '2026-01-15 08:50:46'),
(10, '62cdd0b5-2961-47d1-82aa-a4e6bc70f477', 'SPK/002/BDB/I/26', 'base', 5, 1, 'approved', '2026-01-15', '2026-01-15', NULL, '2026-01-15 08:50:44', NULL, '2026-01-15 08:50:39', '2026-01-15 08:50:44');

-- SPK Items
INSERT INTO `spk_items` (`id`, `spk_id`, `product_id`, `item_type`, `quantity_planned`, `quantity_used`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 260, 'output', 100.00, 0.00, 'kg', NULL, '2025-12-27 16:08:40', '2025-12-27 16:08:40'),
(2, 2, 260, 'output', 100.00, 0.00, 'kg', NULL, '2025-12-27 16:10:19', '2025-12-27 16:10:19'),
(4, 4, 260, 'output', 206.00, 0.00, 'kg', NULL, '2025-12-30 08:33:12', '2025-12-30 08:33:12'),
(5, 5, 312, 'output', 100.00, 0.00, 'KG', 'Produksi', '2025-12-31 15:44:45', '2025-12-31 15:44:45'),
(6, 6, 312, 'output', 50.00, 0.00, 'KG', 'Produksi', '2025-12-31 15:48:48', '2025-12-31 15:48:48'),
(7, 7, 314, 'output', 2.00, 0.00, 'PAIL', 'Produksi', '2025-12-31 16:03:15', '2025-12-31 16:03:15'),
(8, 7, 315, 'output', 2.00, 0.00, 'PAIL', 'Produksi', '2025-12-31 16:03:15', '2025-12-31 16:03:15'),
(9, 8, 327, 'output', 5.00, 0.00, 'PAIL', 'Produksi', '2025-12-31 16:48:53', '2025-12-31 16:48:53'),
(10, 8, 329, 'output', 5.00, 0.00, 'PAIL', 'Produksi', '2025-12-31 16:48:53', '2025-12-31 16:48:53'),
(11, 8, 328, 'output', 1.00, 0.00, 'PAIL', 'Produksi', '2025-12-31 16:48:53', '2025-12-31 16:48:53'),
(12, 9, 260, 'output', 200.00, 0.00, 'kg', NULL, '2026-01-15 08:41:14', '2026-01-15 08:41:14'),
(13, 10, 260, 'output', 100.00, 0.00, 'kg', NULL, '2026-01-15 08:50:39', '2026-01-15 08:50:39');

SET FOREIGN_KEY_CHECKS=1;
-- Delivery Notes adapted to new schema (WITHOUT spk_id and special_order_id columns)
-- FPB adapted (WITHOUT special_order_id)
-- Part 4 of migration

SET FOREIGN_KEY_CHECKS=0;

-- Delivery Notes (REMOVED spk_id and special_order_id columns)
INSERT INTO `delivery_notes` (`id`, `uuid`, `sj_number`, `customer_id`, `invoice_id`, `created_by`, `approved_by`, `delivery_date`, `driver_name`, `vehicle_number`, `recipient_name`, `delivery_address`, `notes`, `status`, `delivered_at`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, '927fcd57-eed9-4c5f-8cd5-630592fc762f', 'SJ/001/BDB/XII/25', 11, NULL, 4, 1, '2025-12-27', NULL, NULL, NULL, 'asfasf', NULL, 'approved', NULL, '2025-12-27 18:45:40', '2025-12-27 18:45:21', '2025-12-27 18:45:40'),
(2, '74e4b812-44b3-4019-acd4-7e48379f44ce', 'SJ/002/BDB/XII/25', 15, 1, 1, 1, '2025-12-31', 'PAK AHMAD', NULL, 'PAK YOYOK', 'PANDEGLANG', NULL, 'delivered', '2025-12-31 16:27:47', '2025-12-31 16:26:55', '2025-12-31 16:26:45', '2025-12-31 16:28:35'),
(3, 'a7a0578a-60f5-4a38-9b6c-e77375e7ddd2', 'SJ/003/BDB/XII/25', 16, 2, 1, 1, '2025-12-30', NULL, NULL, 'PAK SUTRISNO', 'BEKASI', NULL, 'delivered', '2025-12-31 16:32:18', '2025-12-31 16:32:13', '2025-12-31 16:32:06', '2025-12-31 16:32:51'),
(4, '289a70af-0771-4d7a-ad43-73a31486f665', 'SJ/004/BDB/XII/25', 16, 3, 1, 1, '2025-12-31', NULL, NULL, 'PAK SUTRISNO', 'BEKASI', NULL, 'delivered', '2025-12-31 16:33:32', '2025-12-31 16:33:25', '2025-12-31 16:33:21', '2025-12-31 16:34:01'),
(5, 'a51c1d98-c17e-47a2-aba5-bb26dc18d24c', 'SJ/005/BDB/XII/25', 12, 4, 1, 1, '2025-12-31', 'SAPTA', NULL, 'BAPAK ANDRI AGASI', 'BOGOR', NULL, 'delivered', '2025-12-31 16:56:38', '2025-12-31 16:56:33', '2025-12-31 16:56:26', '2025-12-31 16:57:30');

-- Delivery Note Items
INSERT INTO `delivery_note_items` (`id`, `delivery_note_id`, `product_id`, `quantity`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(2, 2, 314, 2.00, 'PAIL', NULL, '2025-12-31 16:26:45', '2025-12-31 16:26:45'),
(3, 2, 315, 2.00, 'PAIL', NULL, '2025-12-31 16:26:45', '2025-12-31 16:26:45'),
(4, 3, 312, 100.00, 'KG', NULL, '2025-12-31 16:32:06', '2025-12-31 16:32:06'),
(5, 4, 312, 50.00, 'KG', NULL, '2025-12-31 16:33:21', '2025-12-31 16:33:21'),
(6, 5, 327, 5.00, 'PAIL', NULL, '2025-12-31 16:56:26', '2025-12-31 16:56:26'),
(7, 5, 329, 5.00, 'PAIL', NULL, '2025-12-31 16:56:26', '2025-12-31 16:56:26'),
(8, 5, 328, 1.00, 'PAIL', NULL, '2025-12-31 16:56:26', '2025-12-31 16:56:26');

-- FPB (REMOVED special_order_id column, set to NULL)
INSERT INTO `fpb` (`id`, `fpb_number`, `spk_id`, `special_order_id`, `created_by`, `approved_by`, `status`, `request_date`, `notes`, `approved_at`, `created_at`, `updated_at`) VALUES
('019b5f33-cf73-72ee-8d8d-b0b569691f00', 'FPB/001/BDB/XII/25', 2, NULL, 4, 1, 'approved', '2025-12-27', NULL, '2025-12-27 16:46:43', '2025-12-27 16:46:29', '2025-12-27 16:46:43'),
('019b5f93-940d-715d-b06e-938eb084d65d', 'FPB/002/BDB/XII/25', 3, NULL, 1, 1, 'approved', '2025-12-27', NULL, '2025-12-27 18:31:13', '2025-12-27 18:31:05', '2025-12-27 18:31:13'),
('019b6d0f-ea52-70f5-a9bc-d6c2c5433f38', 'FPB/003/BDB/XII/25', 4, NULL, 4, 1, 'rejected', '2025-12-30', NULL, '2025-12-30 09:27:18', '2025-12-30 09:21:58', '2025-12-30 09:27:18'),
('019b6d16-d89c-71c5-8094-de6f86ec8561', 'FPB/004/BDB/XII/25', 1, NULL, 4, 1, 'approved', '2025-12-30', NULL, '2025-12-30 09:30:27', '2025-12-30 09:29:32', '2025-12-30 09:30:27'),
('019b7395-58e9-723b-b587-9152bac75f64', 'FPB/005/BDB/XII/25', 5, NULL, 1, 1, 'approved', '2025-12-30', NULL, '2025-12-31 15:45:40', '2025-12-31 15:45:26', '2025-12-31 15:45:40'),
('019b7398-de47-71d5-acb1-e2db8abe21f0', 'FPB/006/BDB/XII/25', 6, NULL, 1, 1, 'approved', '2025-12-31', NULL, '2025-12-31 15:49:26', '2025-12-31 15:49:16', '2025-12-31 15:49:26'),
('019b73b6-862b-73b0-b3fd-5458f1d0a7be', 'FPB/007/BDB/XII/25', 7, NULL, 1, 1, 'approved', '2025-12-30', 'NOT FORMULASI EPOXY SL GREEN PASTEL 3:1', '2025-12-31 16:22:10', '2025-12-31 16:21:40', '2025-12-31 16:22:10'),
('019b73d5-1c9a-73f1-895e-599a07b9926e', 'FPB/008/BDB/XII/25', 8, NULL, 1, 1, 'approved', '2025-12-31', 'FORMULASI EPOXY SL YELLOW GREEN 4:1', '2025-12-31 16:55:15', '2025-12-31 16:55:04', '2025-12-31 16:55:15'),
('019bbf5b-194b-7340-bd65-1cf45bf90b27', 'FPB/001/BDB/I/26', 10, NULL, 5, 1, 'approved', '2026-01-15', NULL, '2026-01-15 08:54:18', '2026-01-15 08:52:57', '2026-01-15 08:54:18');

-- FPB Items
INSERT INTO `fpb_items` (`id`, `fpb_id`, `product_id`, `quantity_requested`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(34, '019bbf5b-194b-7340-bd65-1cf45bf90b27', 333, 20.00, 'kg', NULL, '2026-01-15 08:52:57', '2026-01-15 08:52:57'),
(35, '019bbf5b-194b-7340-bd65-1cf45bf90b27', 250, 10.00, 'kg', NULL, '2026-01-15 08:52:57', '2026-01-15 08:52:57'),
(36, '019bbf5b-194b-7340-bd65-1cf45bf90b27', 304, 10.00, 'kg', NULL, '2026-01-15 08:52:57', '2026-01-15 08:52:57');

-- Invoices
INSERT INTO `invoices` (`id`, `uuid`, `invoice_number`, `customer_id`, `delivery_note_id`, `created_by`, `invoice_date`, `due_date`, `subtotal`, `tax_percent`, `tax_amount`, `discount`, `total`, `paid_amount`, `status`, `approved_by`, `approved_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, '84637a3c-5f76-40e0-87cd-9ee273432728', 'YYK/001/BDB/XII/2025', 15, 2, 1, '2025-12-30', '2025-12-31', 6200000.00, 0.00, 0.00, 0.00, 6200000.00, 6200000.00, 'paid', NULL, NULL, NULL, '2025-12-31 16:28:35', '2025-12-31 16:29:40'),
(2, '176eb7f5-a137-4863-8678-67faec4b7c86', 'STR/002/BDB/XII/2025', 16, 3, 1, '2025-12-30', '2026-01-30', 5500000.00, 0.00, 0.00, 0.00, 5500000.00, 5500000.00, 'paid', NULL, NULL, NULL, '2025-12-31 16:32:51', '2025-12-31 16:32:54'),
(3, '63add749-0367-48f0-a480-58f4eb5bf234', 'STR/003/BDB/XII/2025', 16, 4, 1, '2025-12-31', '2026-01-31', 2750000.00, 0.00, 0.00, 0.00, 2750000.00, 2750000.00, 'paid', NULL, NULL, NULL, '2025-12-31 16:34:01', '2025-12-31 16:34:06'),
(4, '019b7b05-7b67-4790-ae73-a47f1bdbf867', 'AGS/004/BDB/XII/2025', 12, 5, 1, '2025-12-30', '2025-12-31', 14500000.00, 0.00, 0.00, 0.00, 14500000.00, 14500000.00, 'paid', NULL, NULL, NULL, '2025-12-31 16:57:30', '2025-12-31 16:57:37');

-- Invoice Items
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 314, 2.00, 1350000.00, 2700000.00, NULL, '2025-12-31 16:28:35', '2025-12-31 16:29:34'),
(2, 1, 315, 2.00, 1750000.00, 3500000.00, NULL, '2025-12-31 16:29:34', '2025-12-31 16:29:34'),
(3, 2, 312, 100.00, 55000.00, 5500000.00, NULL, '2025-12-31 16:32:51', '2025-12-31 16:32:51'),
(4, 3, 312, 50.00, 55000.00, 2750000.00, NULL, '2025-12-31 16:34:01', '2025-12-31 16:34:01'),
(5, 4, 327, 5.00, 1500000.00, 7500000.00, NULL, '2025-12-31 16:57:30', '2025-12-31 16:57:30'),
(6, 4, 329, 5.00, 1100000.00, 5500000.00, NULL, '2025-12-31 16:57:30', '2025-12-31 16:57:30'),
(7, 4, 328, 1.00, 1500000.00, 1500000.00, NULL, '2025-12-31 16:57:30', '2025-12-31 16:57:30');

SET FOREIGN_KEY_CHECKS=1;
