-- CoinNest Database Schema

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `win_rate` int(11) DEFAULT 50,
  `balance` decimal(18,2) DEFAULT 0.00,
  `role` enum('user','admin') DEFAULT 'user',
  `kyc_status` enum('unverified','pending','verified') DEFAULT 'unverified',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `trades`
--

CREATE TABLE `trades` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `asset` varchar(20) NOT NULL,
  `type` enum('buy','sell') NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `entry_price` decimal(18,8) NOT NULL,
  `status` enum('win','loss','pending') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kyc_requests`
--

CREATE TABLE `kyc_requests` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `id_type` varchar(50) NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `document_front` varchar(255) DEFAULT NULL,
  `document_back` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('deposit','withdrawal') NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `key_name` varchar(50) NOT NULL UNIQUE,
  `key_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Initial settings
INSERT INTO `settings` (`key_name`, `key_value`) VALUES 
('usdt_address', '0x8920...2a78'),
('qr_url', 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=0x8920...2a78');

-- Default Admin (Password: admin123)
-- Hash: $2y$10$pLw7.2p2C5m3m5m5m5m5m5m5m5m5m5m5m5m5m5m5m5m5m5m5m5m5 (Placeholder hash for admin123)
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `balance`) VALUES 
('Platform', 'Admin', 'admin@coinnest.com', '$2y$10$8YpL2yE8y0rYI7UeGvLwMe7B4V4lO6R/Cj7tS0L2K5V8pG1Z2a7Z2', 'admin', 1000.00);

COMMIT;
