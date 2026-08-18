-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2026 at 02:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `customer_name`, `email`, `address`, `city`, `phone`, `created_at`) VALUES
(1, 3, 1500.00, 'Janganolla Pramod Kumar', 'pramodkumargoud244@gmail.com', '8-138', 'Pargi', '07013634731', '2026-08-18 12:02:20'),
(2, 3, 1500.00, 'Janganolla Pramod Kumar', 'pramodkumargoud244@gmail.com', '8-138', 'Pargi', '07013634731', '2026-08-18 12:02:52'),
(3, 3, 1500.00, 'Janganolla Pramod Kumar', 'pramodkumargoud244@gmail.com', '8-138', 'Pargi', '07013634731', '2026-08-18 12:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(1, 1, 3, 1, 1500.00, '2026-08-18 12:02:20'),
(2, 2, 3, 1, 1500.00, '2026-08-18 12:02:52'),
(3, 3, 3, 1, 1500.00, '2026-08-18 12:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `created_at`) VALUES
(1, 'Premium Pen', 4.61, 'Key Features:\r\n\r\nSmooth and consistent ink flow\r\nComfortable, lightweight design\r\nSleek and professional appearance\r\nIdeal for school, office, and everyday use\r\nEasy to carry and use anywhere', 'product1.jpg', '2026-08-18 10:27:48'),
(2, 'Marvel Iron Man MK85, 1:10 Scale 7 Inch Collectible Action Figure for 14Y+, Metallic', 3809.00, 'The Mark LXXXV Iron Man Armor is Tony Stark\'s eighty-fifth and final Iron Man suit. An upgrade to the Mark L armor with enhanced power and combat capabilities. Used by Stark during the Time Heist. Played a crucial role in the Battle of Earth. Featured advanced weaponry and armor systems for maximum efficiency. Represents the culmination of Stark’s technological innovation and heroic legacy\r\n\r\nHighly detailed Iron Man MK85 1 0 V design with realistic armor textures\r\n7 inch articulated action figure for dynamic posing and display\r\nNon-luminous 10th Anniversary Remembrance Edition collectible\r\nPremium metallic finish reflecting authentic Marvel Studios movie appearance\r\nIncludes display stand for stable showcasing and enhanced collector experience\r\n1 10 scale accuracy for true-to-film proportions', 'product6.avif', '2026-08-18 10:31:17'),
(3, 'Canon EOS R100 24.1 MP Mirrorless Camera (Black) with RF-S18-45mm f/4.5-6.3 is STM Optical Zoom Lens | 4k Video', 1500.00, 'About this item\r\nCapture memories effortlessly with the Canon EOS R100 Mirrorless Camera. Perfect for beginners and casual snapshots, paired with the versatile RF-S18-45mm f/4.5-6.3 IS STM Lens.\r\nImage Sensor:APS-C CMOS\r\nImage processor: DIGIC 8\r\nShooting speed: Upto 6.5 Frames per socond\r\nVideo resolution: 4K 30p & Full HD 120p\r\nISO range: 100-12,800\r\nDisplay: TFT colour, LCD Screen, 3.0 type with approx 1040K dots\r\nConnectivity: Wifi + Bluetooth\r\nWarranty: 2 Years', 'product7.png', '2026-08-18 10:41:02'),
(4, ' Office Chair', 2000.00, 'Sitsmart with Tray Premium Ergonomic Full Mesh Office Chair for Work from Home, High Back Computer Chair with Adaptive Lumbar Support, 4D Headrest, 4D Armrest, Footrest & Recline (Grey)', 'product8.png', '2026-08-18 10:45:52'),
(5, 'Sunglasses', 25.00, 'Creature MC stan Rectangle Retro Vintage Narrow Unisex Sunglasses Small Narrow Square Sun Glasses', 'product2.png', '2026-08-18 10:50:49');

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
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(2, '', 'pramodkumargoud244@gmail.com', '$2y$10$5gVieBXXbhHj6p/ValjHxu0o9s5UjjJQQHN7tOkB2p5Aki9nrAgvm', '2026-08-18 10:24:06', 'admin'),
(3, '', 'pramodkumarjanganolla@gmail.com', '$2y$10$e6PVS968XqOMKPDtygfk/eoKLa2PXEWt2YX2UsHoRO47YKeus/vre', '2026-08-18 10:34:21', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
