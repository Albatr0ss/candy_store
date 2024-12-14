-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 05:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `candystore`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total`, `order_date`) VALUES
(0, 5, 3.99, '2024-12-13 20:20:05'),
(0, 5, 15.96, '2024-12-13 20:28:01'),
(0, 5, 15.96, '2024-12-13 20:28:10'),
(0, 5, 15.96, '2024-12-13 20:28:52'),
(0, 5, 3.99, '2024-12-13 20:30:21'),
(0, 5, 3.99, '2024-12-13 21:55:34'),
(0, 5, 7.98, '2024-12-13 22:01:57'),
(0, 5, 3.99, '2024-12-13 23:16:08'),
(0, 5, 19.97, '2024-12-14 00:50:40'),
(0, 5, 13.98, '2024-12-14 01:00:00'),
(0, 5, 5.99, '2024-12-14 01:07:31'),
(0, 9, 3.98, '2024-12-14 02:34:23'),
(0, 5, 17.97, '2024-12-14 02:45:35'),
(0, 10, 3.98, '2024-12-14 02:49:06'),
(0, 12, 3.98, '2024-12-14 03:07:37');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(0, 0, 3, 1, 3.99),
(0, 0, 3, 2, 3.99),
(0, 0, 3, 1, 3.99),
(0, 0, 5, 1, 7.99),
(0, 0, 6, 2, 5.99),
(0, 0, 6, 1, 5.99),
(0, 0, 5, 1, 7.99),
(0, 0, 6, 1, 5.99),
(0, 0, 4, 1, 3.98),
(0, 0, 6, 3, 5.99),
(0, 0, 4, 1, 3.98),
(0, 0, 4, 1, 3.98);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `image_url`, `created_at`) VALUES
(4, 'Chocolate Bar ', 'A delicious milk chocolate bar.', 3.98, 'uploads/675cc606588e27.51164427.jpeg', '2024-12-13 23:40:54'),
(5, 'Gummy Bears', 'A bag of fruity gummy bear candies.', 7.99, 'uploads/675cc635bc2bf5.94913460.jpeg', '2024-12-13 23:41:41'),
(6, 'Lollipop', 'Colorful lollipops with various fruit flavors.', 5.99, 'uploads/675cc664677e36.45407907.jpeg', '2024-12-13 23:42:28'),
(7, 'Caramel Chews', 'Soft and chewy caramel candies.', 3.49, 'uploads/675cc68bdf6868.17693006.jpeg', '2024-12-13 23:43:07'),
(8, 'Candy Cane', 'Peppermint candy cane, perfect for the holidays.', 1.29, 'uploads/675cc6aee3df33.91862455.jpeg', '2024-12-13 23:43:42'),
(9, 'Sour Patch', 'A bag of Sour, then sweet gummy candy in various shapes.', 8.99, 'uploads/675cc6e38427a5.52858047.jpeg', '2024-12-13 23:44:35'),
(10, 'Chocolate Truffles', 'Premium chocolate truffles with creamy filling.', 4.99, 'uploads/675cc7131a0536.30588218.jpeg', '2024-12-13 23:45:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(2, 'np', 'n@gmail.com', '$2y$10$2L0tzep3LQcbhtQX7lnCsu442sczZr7rOUkTRiLSsezEfJlUR.bZq', '2024-11-28 16:12:28', 'user'),
(3, 'dp', 'd@gmail.com', '$2y$10$EKDv.js66NcbxZCsIycroeq.UiL35niyrjFCCdQAre.L6fSmiWkyu', '2024-11-28 16:24:50', 'user'),
(5, 'admin', 'admin@admin.com', '$2y$10$JmoLfigV5Sjs6tzHS.OcgOxp2vP/muRTYWUion5MY4kq4/3FHL8yO', '2024-12-13 18:14:54', 'admin'),
(6, 'deep', 'deep@gmail.com', '$2y$10$dCi5G98VLDoCYEVu85KA6.aoC3RIxQ06FjWJaSo1Y.vGT0lnl/Rca', '2024-12-13 19:25:36', 'user'),
(8, 'Nidhi', 'test@gmail.com', '$2y$10$.ApBZqWnmWpD46nos4D.mecYfIST78k9rcRsuZBDo0q0Z6kdPtFTu', '2024-12-14 02:13:01', 'user'),
(9, 'Nidhi', 'test@test.com', '$2y$10$OJmmnjfM8pIPvRqUb9pg6u8l41.x.qB4D6DVGY4GJEr2Z4DuJ8fSy', '2024-12-14 02:33:39', 'user'),
(10, 'NP', 'p@gmail.com', '$2y$10$uJkq5726iVhgRpuGjPU8k.7gyIJQakJZ7c2coVEvX/muxAS.TsgK.', '2024-12-14 02:48:26', 'user'),
(12, 'NP', 'a@gmail.com', '$2y$10$/YHiEZNOqEjmYyGAHaaZ2ugKltX1HMvAkKrAdj/8E3tv4dKljsKmO', '2024-12-14 03:07:10', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
