-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: web_umkm
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `chart_of_accounts`
--

DROP TABLE IF EXISTS `chart_of_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chart_of_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_code` varchar(20) NOT NULL,
  `account_name` varchar(150) NOT NULL,
  `account_type` enum('asset','liability','equity','revenue','expense') NOT NULL,
  `normal_balance` enum('debit','credit') NOT NULL DEFAULT 'debit',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_code` (`account_code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chart_of_accounts`
--

LOCK TABLES `chart_of_accounts` WRITE;
/*!40000 ALTER TABLE `chart_of_accounts` DISABLE KEYS */;
INSERT INTO `chart_of_accounts` VALUES (1,'1100','Kas Kasir / Tunai','asset','debit',1,'2026-10-03 23:23:49','2026-10-03 23:23:49'),(2,'1110','Bank Operasional','asset','debit',1,'2026-10-03 23:23:49','2026-10-03 23:23:49'),(3,'1200','Piutang Usaha','asset','debit',1,'2026-10-03 23:23:49','2026-10-03 23:23:49'),(4,'2100','PPN Keluaran','liability','credit',1,'2026-10-03 23:23:49','2026-10-03 23:23:49'),(5,'4100','Pendapatan Jasa Penjualan','revenue','credit',1,'2026-10-03 23:23:49','2026-10-03 23:23:49'),(6,'4110','Diskon Penjualan','revenue','debit',1,'2026-10-03 23:23:49','2026-10-03 23:23:49'),(7,'4200','Retur Penjualan Jasa','revenue','debit',1,'2026-10-03 23:23:49','2026-10-03 23:23:49');
/*!40000 ALTER TABLE `chart_of_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (4,'MBR-001','Ananda Putri (Mahasiswi SV IPB)','0812-1122-3344','ananda.putri@apps.ipb.ac.id','Kost Aster Putri No. 12, Babakan Tengah, Bogor',1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(5,'MBR-002','Bpk. Rahmat Hidayat (Warga Lodaya)','0813-2233-4455','rahmat.hidayat@gmail.com','Jl. Lodaya I No. 8, Babakan, Bogor Tengah',1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(6,'MBR-003','Dimas Arya Pratama (Asrama IPB)','0857-3344-5566','dimas.arya@apps.ipb.ac.id','Asrama Sylvaplat A-102, Kampus IPB Dramaga',1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(7,'MBR-004','Ibu Siti Rahmawati (Kost Pajajaran)','0821-4455-6677','siti.rahmawati@yahoo.com','Perumahan Pajajaran Indah Blok B-3, Bogor',1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(8,'MBR-005','dr. Hendra Kusuma (Klinik Medika)','0811-5566-7788','dr.hendra@medikabogor.com','Jl. Malabar Ujung No. 19, Bogor',1,'2026-11-22 00:09:07','2026-11-21 19:35:58');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_lines`
--

DROP TABLE IF EXISTS `invoice_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_lines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `item_name` varchar(150) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('none','percentage','amount') NOT NULL DEFAULT 'none',
  `discount_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_lines_invoice` (`invoice_id`),
  KEY `fk_invoice_lines_item` (`item_id`),
  CONSTRAINT `fk_invoice_lines_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_invoice_lines_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_lines`
--

LOCK TABLES `invoice_lines` WRITE;
/*!40000 ALTER TABLE `invoice_lines` DISABLE KEYS */;
INSERT INTO `invoice_lines` VALUES (9,7,5,'Cuci Kiloan Reguler (2 Hari)',6.00,8000.00,'none',0.00,0.00,48000.00),(10,8,8,'Cuci Bed Cover King Size',2.00,35000.00,'none',0.00,0.00,70000.00),(11,8,5,'Cuci Kiloan Reguler (2 Hari)',15.00,8000.00,'none',0.00,0.00,120000.00),(12,9,10,'Dry Cleaning Jas & Blazer Premium',2.00,45000.00,'none',0.00,0.00,90000.00),(13,10,6,'Cuci Kilat Express (1 Hari)',5.00,13000.00,'none',0.00,0.00,65000.00),(14,10,11,'Cuci Sepatu & Sneakers Canvas',1.00,30000.00,'none',0.00,0.00,30000.00),(15,11,7,'Cuci Kilat Super Express (4 Jam)',4.00,18000.00,'none',0.00,0.00,72000.00),(16,12,9,'Cuci Setrika Kemeja / Seragam',8.00,6000.00,'none',0.00,0.00,48000.00),(17,13,5,'Cuci Kiloan Reguler (2 Hari)',10.00,8000.00,'none',0.00,0.00,80000.00);
/*!40000 ALTER TABLE `invoice_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `payment_type` enum('tunai','kredit') NOT NULL DEFAULT 'tunai',
  `is_pkp` tinyint(1) NOT NULL DEFAULT 0,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
  `process_status` varchar(30) DEFAULT 'antrean',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `fk_invoices_customer` (`customer_id`),
  KEY `fk_invoices_user` (`created_by`),
  CONSTRAINT `fk_invoices_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_invoices_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (7,'INV-202611-001',4,1,'2026-11-18',NULL,'tunai',0,0.00,48000.00,0.00,0.00,48000.00,48000.00,'paid','diambil','Parfum: Sweet Lavender. Sudah diambil pelanggan.','2026-11-18 09:30:00','2026-11-21 19:35:58'),(8,'INV-202611-002',7,1,'2026-11-19',NULL,'tunai',0,0.00,190000.00,0.00,0.00,190000.00,190000.00,'paid','siap_ambil','Bed cover king size + cuci kiloan rutin kost. Sudah selesai dipacking wangi.','2026-11-19 09:30:00','2026-11-21 19:35:58'),(9,'INV-202611-003',8,1,'2026-11-20','2026-11-30','kredit',0,0.00,90000.00,0.00,0.00,90000.00,0.00,'unpaid','setrika','Jas dokter & blazer seminar. Sedang tahap steam uap & hanger.','2026-11-20 09:30:00','2026-11-20 09:30:00'),(10,'INV-202611-004',5,1,'2026-11-20','2026-11-25','kredit',0,0.00,95000.00,0.00,0.00,95000.00,50000.00,'partial','cuci','Cuci kilat + deep clean sepatu Converse. DP Rp 50.000 tunai.','2026-11-20 09:30:00','2026-11-21 19:35:58'),(11,'INV-202611-005',6,1,'2026-11-21',NULL,'tunai',0,0.00,72000.00,0.00,0.00,72000.00,72000.00,'paid','siap_ambil','Baju seragam praktikum IPB mendesak. Selesai dan siap diambil di kasir.','2026-11-21 09:30:00','2026-11-21 19:35:58'),(12,'INV-202611-006',4,1,'2026-11-21',NULL,'tunai',0,0.00,48000.00,0.00,0.00,48000.00,48000.00,'paid','antrean','8 pcs kemeja putih & batik kuliah. Sedang antre masuk washer 02.','2026-11-21 09:30:00','2026-11-21 19:35:58'),(13,'INV-202611-007',7,1,'2026-11-21','2026-11-28','kredit',0,0.00,80000.00,0.00,0.00,80000.00,0.00,'unpaid','cuci','Tagihan mingguan kost. Sedang diproses pencucian.','2026-11-21 09:30:00','2026-11-21 09:30:00');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(30) NOT NULL DEFAULT 'transaksi',
  `sell_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (5,'LND-001','Cuci Kiloan Reguler (2 Hari)','Cuci, pengeringan, setrika uap, dan packing plastik wangi semerbak','kg',8000.00,1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(6,'LND-002','Cuci Kilat Express (1 Hari)','Cuci kilat selesai dalam 24 jam dengan parfum premium antibakteri','kg',13000.00,1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(7,'LND-003','Cuci Kilat Super Express (4 Jam)','Prioritas antrean kilat 4 jam siap jemput/antar khusus area kampus IPB','kg',18000.00,1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(8,'LND-004','Cuci Bed Cover King Size','Pembersihan mendalam serat kain, anti-tungau dan pelembut serat mikro','pcs',35000.00,1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(9,'LND-005','Cuci Setrika Kemeja / Seragam','Pencucian satuan kerah rapi, kancing aman, disetrika dengan steamer uap','pcs',6000.00,1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(10,'LND-006','Dry Cleaning Jas & Blazer Premium','Perawatan khusus tanpa air dengan pelindung serat wol & jas formil','pcs',45000.00,1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(11,'LND-007','Cuci Sepatu & Sneakers Canvas','Deep clean sol luar dalam, unyellowing midsole dan pengharum anti-bau','pasang',30000.00,1,'2026-11-22 00:09:07','2026-11-21 19:35:58'),(12,'LND-008','Cuci Boneka & Helm Bogo','Disinfeksi busa helm atau boneka jumbo, wangi tahan lama','pcs',25000.00,1,'2026-11-22 00:09:07','2026-11-21 19:35:58');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_details`
--

DROP TABLE IF EXISTS `journal_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `journal_id` int(10) unsigned NOT NULL,
  `account_code` varchar(20) NOT NULL,
  `account_name` varchar(150) NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `fk_journal_details_journal` (`journal_id`),
  CONSTRAINT `fk_journal_details_journal` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_details`
--

LOCK TABLES `journal_details` WRITE;
/*!40000 ALTER TABLE `journal_details` DISABLE KEYS */;
INSERT INTO `journal_details` VALUES (21,11,'1200','Piutang Usaha',48000.00,0.00),(22,11,'4100','Pendapatan Jasa Penjualan',0.00,48000.00),(23,12,'1100','Kas Kasir / Tunai',48000.00,0.00),(24,12,'1200','Piutang Usaha',0.00,48000.00),(25,13,'1200','Piutang Usaha',190000.00,0.00),(26,13,'4100','Pendapatan Jasa Penjualan',0.00,190000.00),(27,14,'1100','Kas Kasir / Tunai',190000.00,0.00),(28,14,'1200','Piutang Usaha',0.00,190000.00),(29,15,'1200','Piutang Usaha',90000.00,0.00),(30,15,'4100','Pendapatan Jasa Penjualan',0.00,90000.00),(31,16,'1200','Piutang Usaha',95000.00,0.00),(32,16,'4100','Pendapatan Jasa Penjualan',0.00,95000.00),(33,17,'1100','Kas Kasir / Tunai',50000.00,0.00),(34,17,'1200','Piutang Usaha',0.00,50000.00),(35,18,'1200','Piutang Usaha',72000.00,0.00),(36,18,'4100','Pendapatan Jasa Penjualan',0.00,72000.00),(37,19,'1100','Kas Kasir / Tunai',72000.00,0.00),(38,19,'1200','Piutang Usaha',0.00,72000.00),(39,20,'1200','Piutang Usaha',48000.00,0.00),(40,20,'4100','Pendapatan Jasa Penjualan',0.00,48000.00),(41,21,'1100','Kas Kasir / Tunai',48000.00,0.00),(42,21,'1200','Piutang Usaha',0.00,48000.00),(43,22,'1200','Piutang Usaha',80000.00,0.00),(44,22,'4100','Pendapatan Jasa Penjualan',0.00,80000.00),(45,23,'4200','Retur Penjualan Jasa',35000.00,0.00),(46,23,'1100','Kas Kasir / Tunai',0.00,35000.00);
/*!40000 ALTER TABLE `journal_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journals`
--

DROP TABLE IF EXISTS `journals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `journal_number` varchar(50) NOT NULL,
  `journal_date` date NOT NULL,
  `ref_type` enum('invoice','receipt','return','manual') NOT NULL,
  `ref_id` int(10) unsigned DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_number` (`journal_number`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journals`
--

LOCK TABLES `journals` WRITE;
/*!40000 ALTER TABLE `journals` DISABLE KEYS */;
INSERT INTO `journals` VALUES (11,'JRN-202611-0001','2026-11-18','invoice',7,'Penjualan Faktur INV-202611-001 - Ananda Putri (Mahasiswi SV IPB)','2026-11-21 19:35:58'),(12,'JRN-202611-0002','2026-11-18','receipt',6,'Penerimaan Kas Tunai Faktur INV-202611-001 - Ananda Putri (Mahasiswi SV IPB)','2026-11-21 19:35:58'),(13,'JRN-202611-0003','2026-11-19','invoice',8,'Penjualan Faktur INV-202611-002 - Ibu Siti Rahmawati (Kost Pajajaran)','2026-11-21 19:35:58'),(14,'JRN-202611-0004','2026-11-19','receipt',7,'Penerimaan Kas Tunai Faktur INV-202611-002 - Ibu Siti Rahmawati (Kost Pajajaran)','2026-11-21 19:35:58'),(15,'JRN-202611-0005','2026-11-20','invoice',9,'Penjualan Faktur INV-202611-003 - dr. Hendra Kusuma (Klinik Medika)','2026-11-21 19:35:58'),(16,'JRN-202611-0006','2026-11-20','invoice',10,'Penjualan Faktur INV-202611-004 - Bpk. Rahmat Hidayat (Warga Lodaya)','2026-11-21 19:35:58'),(17,'JRN-202611-0007','2026-11-20','receipt',8,'Pelunasan Piutang INV-202611-004 via Kas Kasir / Tunai - Bpk. Rahmat Hidayat (Warga Lodaya)','2026-11-21 19:35:58'),(18,'JRN-202611-0008','2026-11-21','invoice',11,'Penjualan Faktur INV-202611-005 - Dimas Arya Pratama (Asrama IPB)','2026-11-21 19:35:58'),(19,'JRN-202611-0009','2026-11-21','receipt',9,'Penerimaan Kas Tunai Faktur INV-202611-005 - Dimas Arya Pratama (Asrama IPB)','2026-11-21 19:35:58'),(20,'JRN-202611-0010','2026-11-21','invoice',12,'Penjualan Faktur INV-202611-006 - Ananda Putri (Mahasiswi SV IPB)','2026-11-21 19:35:58'),(21,'JRN-202611-0011','2026-11-21','receipt',10,'Penerimaan Kas Tunai Faktur INV-202611-006 - Ananda Putri (Mahasiswi SV IPB)','2026-11-21 19:35:58'),(22,'JRN-202611-0012','2026-11-21','invoice',13,'Penjualan Faktur INV-202611-007 - Ibu Siti Rahmawati (Kost Pajajaran)','2026-11-21 19:35:58'),(23,'JRN-202611-0013','2026-11-20','return',1,'Retur Penjualan Faktur INV-202611-002 - Ibu Siti Rahmawati (Kost Pajajaran)','2026-11-21 19:35:58');
/*!40000 ALTER TABLE `journals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026-10-03-000001','App\\Database\\Migrations\\CreateAllTables','default','App',1795289726,1),(2,'2026-11-22-000001','App\\Database\\Migrations\\AddProcessStatusToInvoices','default','App',1795289726,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receipts`
--

DROP TABLE IF EXISTS `receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receipts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `receipt_number` varchar(50) NOT NULL,
  `invoice_id` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','transfer','other') NOT NULL DEFAULT 'cash',
  `account_code` varchar(20) NOT NULL DEFAULT '1100',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt_number` (`receipt_number`),
  KEY `fk_receipts_invoice` (`invoice_id`),
  KEY `fk_receipts_user` (`created_by`),
  CONSTRAINT `fk_receipts_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_receipts_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receipts`
--

LOCK TABLES `receipts` WRITE;
/*!40000 ALTER TABLE `receipts` DISABLE KEYS */;
INSERT INTO `receipts` VALUES (6,'RCP-202611-0001',7,1,'2026-11-18',48000.00,'cash','1100','Pelunasan otomatis faktur tunai saat penerbitan faktur','2026-11-21 19:35:58','2026-11-21 19:35:58'),(7,'RCP-202611-0002',8,1,'2026-11-19',190000.00,'cash','1100','Pelunasan otomatis faktur tunai saat penerbitan faktur','2026-11-21 19:35:58','2026-11-21 19:35:58'),(8,'RCP-202611-0003',10,1,'2026-11-20',50000.00,'cash','1100','Uang Muka (DP) cucian kilat express saat penyerahan pakaian','2026-11-20 10:00:00','2026-11-20 10:00:00'),(9,'RCP-202611-0004',11,1,'2026-11-21',72000.00,'cash','1100','Pelunasan otomatis faktur tunai saat penerbitan faktur','2026-11-21 19:35:58','2026-11-21 19:35:58'),(10,'RCP-202611-0005',12,1,'2026-11-21',48000.00,'cash','1100','Pelunasan otomatis faktur tunai saat penerbitan faktur','2026-11-21 19:35:58','2026-11-21 19:35:58');
/*!40000 ALTER TABLE `receipts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_lines`
--

DROP TABLE IF EXISTS `return_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_lines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `item_name` varchar(150) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `fk_return_lines_return` (`return_id`),
  KEY `fk_return_lines_item` (`item_id`),
  CONSTRAINT `fk_return_lines_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_return_lines_return` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_lines`
--

LOCK TABLES `return_lines` WRITE;
/*!40000 ALTER TABLE `return_lines` DISABLE KEYS */;
INSERT INTO `return_lines` VALUES (1,1,8,'Cuci Bed Cover King Size (Garansi Cuci Ulang)',1.00,35000.00,35000.00);
/*!40000 ALTER TABLE `return_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `returns`
--

DROP TABLE IF EXISTS `returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `returns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_number` varchar(50) NOT NULL,
  `invoice_id` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `return_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `refund_method` enum('potong_piutang','cash') NOT NULL DEFAULT 'potong_piutang',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_number` (`return_number`),
  KEY `fk_returns_invoice` (`invoice_id`),
  KEY `fk_returns_user` (`created_by`),
  CONSTRAINT `fk_returns_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_returns_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `returns`
--

LOCK TABLES `returns` WRITE;
/*!40000 ALTER TABLE `returns` DISABLE KEYS */;
INSERT INTO `returns` VALUES (1,'RET-202611-0001',8,1,'2026-11-20',35000.00,0.00,35000.00,'cash','Garansi cuci ulang 1 Bed Cover King karena ada sisa noda minyak kopi.','2026-11-20 14:00:00','2026-11-20 14:00:00');
/*!40000 ALTER TABLE `returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'company_name','FreshWash Laundry & Dry Cleaning','Nama Perusahaan / Bisnis Jasa','2026-10-03 23:23:49','2026-11-21 19:35:58'),(2,'company_phone','0812-9876-5432','Nomor Telepon','2026-10-03 23:23:49','2026-11-21 19:35:58'),(3,'company_email','halo@freshwash.id','Email Perusahaan','2026-10-03 23:23:49','2026-11-21 19:35:58'),(4,'company_address','Jl. Kumbang No. 14, Babakan, Bogor Tengah (Kampus SV IPB)','Alamat Lengkap Perusahaan','2026-10-03 23:23:49','2026-11-21 19:35:58'),(5,'is_pkp','0','Status PKP Global (1 = PKP, 0 = Non-PKP)','2026-10-03 23:23:49','2026-11-21 19:35:58'),(6,'default_tax_rate','0.00','Tarif PPN Standar (%)','2026-10-03 23:23:49','2026-11-21 19:35:58'),(7,'currency_symbol','Rp','Simbol Mata Uang','2026-10-03 23:23:49','2026-11-21 19:35:58');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir') NOT NULL DEFAULT 'kasir',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator UMKM','admin@umkm.id','$2y$10$JcutStFWeKehlN8MTNsPOeirMWo4in2GIUiPqHHPegmTQ0.sbd6Bu','admin',1,'2026-10-03 23:23:49','2026-10-03 23:29:33'),(2,'Kasir Penjualan','kasir@umkm.id','$2y$10$yGUkKuR/x8HCj7cHbHk/v.XIx9JXJZZFG..NJuOdmAyWfHwoqIWB.','kasir',1,'2026-10-03 23:23:49','2026-10-03 23:29:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-24 22:36:39
