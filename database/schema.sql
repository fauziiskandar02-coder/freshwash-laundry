-- ========================================================
-- Database Schema: Web UMKM - Siklus Pendapatan (Revenue Cycle)
-- Target RDBMS: MySQL 8.0+ / MariaDB 10.4+
-- Charset: utf8mb4 / utf8mb4_unicode_ci
-- ========================================================

CREATE DATABASE IF NOT EXISTS `web_umkm` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `web_umkm`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Tabel Users (Authentication & Multi-Role: Admin & Kasir)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Settings (Pengaturan Global: Status PKP, Tarif PPN, Profil Usaha)
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT NULL,
    `description` VARCHAR(255) NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel Chart of Accounts (COA - Bagan Akun Standar AIS)
DROP TABLE IF EXISTS `chart_of_accounts`;
CREATE TABLE `chart_of_accounts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `account_code` VARCHAR(20) NOT NULL UNIQUE,
    `account_name` VARCHAR(150) NOT NULL,
    `account_type` ENUM('asset', 'liability', 'equity', 'revenue', 'expense') NOT NULL,
    `normal_balance` ENUM('debit', 'credit') NOT NULL DEFAULT 'debit',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabel Customers (Master Data Pelanggan UMKM)
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(30) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(30) NULL,
    `email` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabel Items (Master Jasa / Layanan UMKM)
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(30) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `unit` VARCHAR(30) NOT NULL DEFAULT 'transaksi',
    `sell_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabel Invoices (Faktur Penjualan - Core Transaksi Pendapatan)
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
    `customer_id` INT UNSIGNED NOT NULL,
    `created_by` INT UNSIGNED NULL,
    `invoice_date` DATE NOT NULL,
    `due_date` DATE NULL,
    `payment_type` ENUM('tunai', 'kredit') NOT NULL DEFAULT 'tunai',
    `is_pkp` TINYINT(1) NOT NULL DEFAULT 0,
    `tax_rate` DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
    `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `discount_total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `tax_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `grand_total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `paid_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM('unpaid', 'partial', 'paid') NOT NULL DEFAULT 'unpaid',
    `notes` TEXT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_invoices_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON UPDATE CASCADE,
    CONSTRAINT `fk_invoices_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabel Invoice Lines (Detail Item Jasa per Faktur Penjualan)
DROP TABLE IF EXISTS `invoice_lines`;
CREATE TABLE `invoice_lines` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `invoice_id` INT UNSIGNED NOT NULL,
    `item_id` INT UNSIGNED NULL,
    `item_name` VARCHAR(150) NOT NULL,
    `quantity` DECIMAL(10, 2) NOT NULL DEFAULT 1.00,
    `unit_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `discount_type` ENUM('none', 'percentage', 'amount') NOT NULL DEFAULT 'none',
    `discount_value` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `discount_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `line_total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    CONSTRAINT `fk_invoice_lines_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_invoice_lines_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tabel Receipts (Penerimaan Kas / Pelunasan Piutang)
DROP TABLE IF EXISTS `receipts`;
CREATE TABLE `receipts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `receipt_number` VARCHAR(50) NOT NULL UNIQUE,
    `invoice_id` INT UNSIGNED NOT NULL,
    `created_by` INT UNSIGNED NULL,
    `payment_date` DATE NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `payment_method` ENUM('cash', 'transfer', 'other') NOT NULL DEFAULT 'cash',
    `account_code` VARCHAR(20) NOT NULL DEFAULT '1100',
    `notes` TEXT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_receipts_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON UPDATE CASCADE,
    CONSTRAINT `fk_receipts_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Tabel Returns (Retur Penjualan / Pengurangan Jasa)
DROP TABLE IF EXISTS `returns`;
CREATE TABLE `returns` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `return_number` VARCHAR(50) NOT NULL UNIQUE,
    `invoice_id` INT UNSIGNED NOT NULL,
    `created_by` INT UNSIGNED NULL,
    `return_date` DATE NOT NULL,
    `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `tax_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `grand_total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `refund_method` ENUM('potong_piutang', 'cash') NOT NULL DEFAULT 'potong_piutang',
    `notes` TEXT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_returns_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON UPDATE CASCADE,
    CONSTRAINT `fk_returns_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Tabel Return Lines (Detail Item Jasa yang Diretur)
DROP TABLE IF EXISTS `return_lines`;
CREATE TABLE `return_lines` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `return_id` INT UNSIGNED NOT NULL,
    `item_id` INT UNSIGNED NULL,
    `item_name` VARCHAR(150) NOT NULL,
    `quantity` DECIMAL(10, 2) NOT NULL DEFAULT 1.00,
    `unit_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `line_total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    CONSTRAINT `fk_return_lines_return` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_return_lines_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Tabel Journals (Header Jurnal Akuntansi Otomatis)
DROP TABLE IF EXISTS `journals`;
CREATE TABLE `journals` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `journal_number` VARCHAR(50) NOT NULL UNIQUE,
    `journal_date` DATE NOT NULL,
    `ref_type` ENUM('invoice', 'receipt', 'return', 'manual') NOT NULL,
    `ref_id` INT UNSIGNED NULL,
    `description` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Tabel Journal Details (Baris Debit & Kredit Jurnal Akuntansi)
DROP TABLE IF EXISTS `journal_details`;
CREATE TABLE `journal_details` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `journal_id` INT UNSIGNED NOT NULL,
    `account_code` VARCHAR(20) NOT NULL,
    `account_name` VARCHAR(150) NOT NULL,
    `debit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `credit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    CONSTRAINT `fk_journal_details_journal` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ========================================================
-- SEED DATA AWAL (DEFAULT CONFIG, COA, USER, SAMPLE DATA)
-- ========================================================

-- Settings Default
INSERT INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES
('company_name', 'Solusi Kreatif UMKM Indonesia', 'Nama Perusahaan / Bisnis Jasa'),
('company_phone', '0812-3456-7890', 'Nomor Telepon'),
('company_email', 'admin@solusikreatif.id', 'Email Perusahaan'),
('company_address', 'Jl. Sudirman No. 45, Jakarta Selatan', 'Alamat Lengkap Perusahaan'),
('is_pkp', '1', 'Status PKP Global (1 = PKP, 0 = Non-PKP)'),
('default_tax_rate', '12.00', 'Tarif PPN Standar (%)'),
('currency_symbol', 'Rp', 'Simbol Mata Uang');

-- Users Default (Password hash: 'admin123' & 'kasir123')
-- Bcrypt hash untuk admin123: $2y$10$wWkY0jVb5uYV50ZsmKj4g.WwQZzWkWbNfN2bX9rO7J4C3F0Xb1.62
-- Bcrypt hash untuk kasir123: $2y$10$7eB84cWcSmKj4g.WwQZzWkWbNfN2bX9rO7J4C3F0Xb1.62wWkY0jV
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`) VALUES
('Administrator UMKM', 'admin@umkm.id', '$2y$10$vI8A7C20uWn10Jv8z3mH2eqiHlQZ776WcZ36C70N9H8E6Z41Z2sO6', 'admin', 1),
('Kasir Penjualan', 'kasir@umkm.id', '$2y$10$vI8A7C20uWn10Jv8z3mH2eqiHlQZ776WcZ36C70N9H8E6Z41Z2sO6', 'kasir', 1);

-- Chart of Accounts (COA) Standar Siklus Pendapatan
INSERT INTO `chart_of_accounts` (`account_code`, `account_name`, `account_type`, `normal_balance`, `is_active`) VALUES
('1100', 'Kas Kasir / Tunai', 'asset', 'debit', 1),
('1110', 'Bank Operasional', 'asset', 'debit', 1),
('1200', 'Piutang Usaha', 'asset', 'debit', 1),
('2100', 'PPN Keluaran', 'liability', 'credit', 1),
('4100', 'Pendapatan Jasa Penjualan', 'revenue', 'credit', 1),
('4110', 'Diskon Penjualan', 'revenue', 'debit', 1),
('4200', 'Retur Penjualan Jasa', 'revenue', 'debit', 1);

-- Sample Customers
INSERT INTO `customers` (`code`, `name`, `phone`, `email`, `address`, `is_active`) VALUES
('CUST-001', 'PT Maju Bersama Digital', '0811-2233-4455', 'finance@majubersama.com', 'Gedung Menara Prima Lt. 8, Jakarta', 1),
('CUST-002', 'CV Sumber Rejeki Abadi', '0813-8899-0011', 'contact@sumberrejeki.co.id', 'Jl. Pemuda No. 12, Bandung', 1),
('CUST-003', 'Ibu Rina Wulandari (Personal)', '0857-4433-2211', 'rina.wulandari@gmail.com', 'Komplek Permata Hijau Blok C-4, Surabaya', 1);

-- Sample Services / Items
INSERT INTO `items` (`code`, `name`, `description`, `unit`, `sell_price`, `is_active`) VALUES
('SRV-001', 'Jasa Konsultasi Keuangan & Akuntansi', 'Pendampingan laporan keuangan bulanan dan pembukuan', 'bulan', 2500000.00, 1),
('SRV-002', 'Jasa Pembuatan Website & Branding', 'Desain landing page modern responsif dan company profile', 'paket', 5000000.00, 1),
('SRV-003', 'Jasa Maintenance Sistem & IT Support', 'Dukungan teknis berkala sistem dan cloud hosting', 'bulan', 1200000.00, 1),
('SRV-004', 'Jasa Pelatihan & Workshop Staff', 'Pelatihan operasional kasir & pencatatan digital 1 hari', 'sesi', 1800000.00, 1);
