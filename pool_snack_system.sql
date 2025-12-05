-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table pool_snack_system.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pool_snack_system.cart: ~0 rows (approximately)

-- Dumping structure for table pool_snack_system.meja
CREATE TABLE IF NOT EXISTS `meja` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomor_meja` varchar(10) NOT NULL,
  `status` enum('tersedia','terpakai') DEFAULT 'tersedia',
  `customer_id` int DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_meja` (`nomor_meja`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pool_snack_system.meja: ~10 rows (approximately)
INSERT INTO `meja` (`id`, `nomor_meja`, `status`, `customer_id`, `session_id`, `created_at`) VALUES
	(1, 'A01', 'tersedia', NULL, NULL, '2025-11-30 12:41:57'),
	(2, 'A02', 'tersedia', NULL, NULL, '2025-11-30 12:41:57'),
	(3, 'A03', 'tersedia', NULL, NULL, '2025-11-30 12:41:57'),
	(4, 'A04', 'tersedia', NULL, NULL, '2025-11-30 12:41:57'),
	(5, 'A05', 'tersedia', NULL, NULL, '2025-11-30 12:41:57'),
	(6, 'B01', 'tersedia', NULL, NULL, '2025-11-30 12:41:57'),
	(7, 'B02', 'tersedia', NULL, NULL, '2025-11-30 12:41:57'),
	(8, 'B03', 'tersedia', NULL, NULL, '2025-11-30 12:41:57'),
	(9, 'B04', 'tersedia', NULL, NULL, '2025-11-30 12:41:57'),
	(10, 'B05', 'tersedia', NULL, NULL, '2025-11-30 12:41:57');

-- Dumping structure for table pool_snack_system.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text,
  `harga` decimal(10,2) NOT NULL,
  `kategori` enum('makanan','minuman') NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','habis') DEFAULT 'tersedia',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pool_snack_system.menu: ~14 rows (approximately)
INSERT INTO `menu` (`id`, `nama`, `deskripsi`, `harga`, `kategori`, `gambar`, `status`, `created_at`) VALUES
	(1, 'Burger Premium', 'Burger dengan daging sapi pilihan, sayuran segar dan saus spesial', 35000.00, 'makanan', 'burger.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(2, 'Pizza Italian', 'Pizza dengan topping daging sapi, jamur, dan keju mozzarella', 65000.00, 'makanan', 'pizza.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(3, 'French Fries', 'Kentang goreng renyah dengan bumbu rahasia', 20000.00, 'makanan', 'fries.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(4, 'Chicken Nugget', 'Nugget ayam crispy dengan saus BBQ', 25000.00, 'makanan', 'nugget.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(5, 'Club Sandwich', 'Sandwich isi ayam, sayuran segar dan mayonaise', 30000.00, 'makanan', 'sandwich.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(6, 'Classic Hotdog', 'Hotdog dengan sosis premium dan mustard', 28000.00, 'makanan', 'hotdog.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(7, 'Creamy Pasta', 'Pasta dengan saus krim dan jamur', 40000.00, 'makanan', 'pasta.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(8, 'Cola Chilled', 'Minuman cola dingin menyegarkan', 15000.00, 'minuman', 'cola.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(9, 'Orange Juice', 'Jus jeruk segar tanpa pengawet', 18000.00, 'minuman', 'juice.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(10, 'Latte Coffee', 'Kopi latte dengan susu steamed', 25000.00, 'minuman', 'coffee.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(11, 'Iced Tea', 'Es teh manis segar', 12000.00, 'minuman', 'tea.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(12, 'Chocolate Milkshake', 'Milkshake coklat dengan whipped cream', 22000.00, 'minuman', 'milkshake.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(13, 'Fresh Lemonade', 'Lemonade segar dengan es batu', 16000.00, 'minuman', 'lemonade.jpg', 'tersedia', '2025-11-30 12:41:57'),
	(14, 'Berry Smoothie', 'Smoothie mix berry dengan yogurt', 28000.00, 'minuman', 'smoothie.jpg', 'tersedia', '2025-11-30 12:41:57');

-- Dumping structure for table pool_snack_system.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_number` varchar(20) NOT NULL,
  `user_id` int NOT NULL,
  `meja_id` int NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `metode_bayar` enum('tunai','qris') DEFAULT 'tunai',
  `status` enum('menunggu_konfirmasi','diproses','selesai','dibatalkan') DEFAULT 'menunggu_konfirmasi',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `user_id` (`user_id`),
  KEY `meja_id` (`meja_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`meja_id`) REFERENCES `meja` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pool_snack_system.orders: ~10 rows (approximately)
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `meja_id`, `total_harga`, `metode_bayar`, `status`, `bukti_bayar`, `created_at`) VALUES
	(1, 'ORD20241130001', 3, 1, 75000.00, 'tunai', 'selesai', NULL, '2025-11-30 12:41:57'),
	(2, 'ORD20241130002', 3, 2, 45000.00, 'qris', 'selesai', NULL, '2025-11-30 12:41:57'),
	(3, 'ORD20251130135821683', 4, 1, 38500.00, 'qris', 'selesai', '1764511101_WIN_20250621_13_36_23_Pro.jpg', '2025-11-30 13:58:21'),
	(4, 'ORD20251130140123224', 4, 1, 38500.00, 'qris', 'selesai', '1764511283_WIN_20250621_13_36_23_Pro.jpg', '2025-11-30 14:01:23'),
	(5, 'ORD20251130153910733', 2, 5, 28000.00, 'tunai', 'selesai', NULL, '2025-11-30 15:39:10'),
	(6, 'ORD20251201044810396', 4, 10, 69300.00, 'qris', 'selesai', '1764564490_WIN_20251118_11_11_20_Pro.jpg', '2025-12-01 04:48:10'),
	(7, 'ORD20251201053214990', 4, 3, 71500.00, 'tunai', 'selesai', NULL, '2025-12-01 05:32:14'),
	(8, 'ORD20251201053318201', 2, 2, 25000.00, 'tunai', 'selesai', NULL, '2025-12-01 05:33:18'),
	(9, 'ORD20251201054131470', 4, 6, 27500.00, 'tunai', 'selesai', NULL, '2025-12-01 05:41:31'),
	(10, 'ORD20251201105702652', 4, 1, 58300.00, 'tunai', 'selesai', NULL, '2025-12-01 10:57:02');

-- Dumping structure for table pool_snack_system.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `quantity` int NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pool_snack_system.order_items: ~14 rows (approximately)
INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `quantity`, `harga_satuan`, `subtotal`) VALUES
	(1, 1, 1, 1, 35000.00, 35000.00),
	(2, 1, 8, 2, 15000.00, 30000.00),
	(3, 1, 3, 1, 20000.00, 20000.00),
	(4, 2, 2, 1, 65000.00, 65000.00),
	(5, 2, 9, 1, 18000.00, 18000.00),
	(6, 4, 1, 1, 35000.00, 35000.00),
	(7, 5, 6, 1, 28000.00, 28000.00),
	(8, 6, 6, 1, 28000.00, 28000.00),
	(9, 6, 1, 1, 35000.00, 35000.00),
	(10, 7, 2, 1, 65000.00, 65000.00),
	(11, 8, 4, 1, 25000.00, 25000.00),
	(12, 9, 4, 1, 25000.00, 25000.00),
	(13, 10, 4, 1, 25000.00, 25000.00),
	(14, 10, 6, 1, 28000.00, 28000.00);

-- Dumping structure for table pool_snack_system.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('tunai','qris') NOT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verified_by` int DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `verified_by` (`verified_by`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pool_snack_system.payments: ~0 rows (approximately)

-- Dumping structure for table pool_snack_system.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','kasir','admin') DEFAULT 'customer',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pool_snack_system.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `status`, `created_at`) VALUES
	(1, 'Administrator', 'admin@poolsnack.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', '2025-11-30 12:41:57'),
	(2, 'Kasir Utama', 'kasir@poolsnack.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kasir', 'active', '2025-11-30 12:41:57'),
	(3, 'Customer Demo', 'customer@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'active', '2025-11-30 12:41:57'),
	(4, 'Jihan Nabillah', 'jhnabillah@gmail.com', '$2y$10$a8C69qr1hNpdekdgk9UIG.bKL91n0SJx8QiC6YkHGt32papQobDNO', 'customer', 'active', '2025-11-30 12:43:57'),
	(5, 'sinta ulandari', 'sinta@gmail.com', '$2y$10$t5LWBnVQcAyEQ0CaIQ2RleRXgM.6J6NCrRcPJa9wqh1uOF5Q37vWy', 'customer', 'active', '2025-12-01 04:59:55');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
