-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2022 at 10:55 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_shopping`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `decrip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `decrip`, `image`) VALUES
(10, 'Bobson', 'Bobson product', 'BOBSON-brand-62b10fae42a90.webp'),
(11, 'Levi\'s', 'Levi\'s product', 'Levis-brand-62b10fbb639b7.png'),
(12, 'EDWIN', 'EDWIN product', 'edwin-brand-62b10fc7c8929.webp'),
(13, 'MLB', 'MLB product', 'MLB-brand-62b10fd8a171d.png'),
(14, 'Champion', 'Champion Product', 'Champion-brand-62b10fe5e3fca.png'),
(15, 'Gildan', 'Gildan product', 'GILDAN-brand-62b10ffc3cfd3.png'),
(16, 'Delong', 'Delong product', 'DeLONG-brand-62b1100dbe24a.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `telephone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fullname`, `sex`, `telephone`, `address`, `birthday`, `user_id`) VALUES
(6, 'ndquang', 1, '0327281160', 'Can Tho city', '2022-06-02', 24),
(7, 'Nguyen Duy Quang', 1, '0916843367', 'Hung Loi, Ninh Kieu, Can Tho city', '2002-08-05', 1),
(8, 'Nguyen Thai Duong', 1, '0375741165', 'An Binh, Vinh Long', '2002-03-15', 26),
(9, 'Nguyen Tuan Anh', 1, '0704725944', 'Phung Hiep, Hau Giang', '2002-11-19', 27),
(14, 'Nguyen Que Tran', 0, '0987654321', 'Tan Hanh, Vinh Long', '2003-03-09', 29);

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220615134331', '2022-06-15 15:43:37', 45),
('DoctrineMigrations\\Version20220615134423', '2022-06-15 15:44:28', 43),
('DoctrineMigrations\\Version20220615134705', '2022-06-15 15:47:12', 200),
('DoctrineMigrations\\Version20220615135745', '2022-06-15 15:57:52', 51),
('DoctrineMigrations\\Version20220615140019', '2022-06-15 16:00:27', 195),
('DoctrineMigrations\\Version20220615141332', '2022-06-15 16:13:40', 256),
('DoctrineMigrations\\Version20220617103930', '2022-06-17 12:39:38', 95),
('DoctrineMigrations\\Version20220617104414', '2022-06-17 12:44:17', 51),
('DoctrineMigrations\\Version20220617114723', '2022-06-17 13:47:26', 135);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `send_date` datetime NOT NULL,
  `allow` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `customer_id`, `product_id`, `content`, `send_date`, `allow`) VALUES
(2, 7, 23, 'This is a nice jacket that I have seen', '2022-06-24 11:57:18', 1),
(3, 7, 15, 'Spacious and comfortable', '2022-06-24 11:57:48', 1),
(4, 7, 17, 'Excellent fabric quality, fits perfectly', '2022-06-24 11:58:12', 1),
(5, 7, 11, 'The quality of the shirt is good, but there is still one flaw in the shirt', '2022-06-24 11:59:49', 1),
(6, 7, 8, 'The pants are quite wide', '2022-06-24 12:00:30', 0),
(7, 7, 16, 'Awesome', '2022-06-25 02:39:50', 0),
(10, 7, 8, 'Even though I agree with the comment above about being unnecessary, I\'m going to upvote this because it demonstrates that custom methods can be used if so desired', '2022-06-25 15:08:14', 0),
(11, 7, 17, 'Đẹp tuyệt trần', '2022-06-28 14:36:05', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `username_id` int(11) DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `delivery_date` datetime NOT NULL,
  `checked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `username_id`, `order_date`, `delivery_date`, `checked`) VALUES
(9, 7, '2022-06-23 03:46:20', '2022-06-23 03:46:20', 1),
(10, 7, '2022-06-24 03:14:43', '2022-06-24 03:14:43', 1),
(11, 7, '2022-06-24 03:18:11', '2022-06-24 03:18:11', 1),
(12, 7, '2022-06-24 03:19:48', '2022-06-24 03:19:48', 1),
(13, 8, '2022-06-24 03:21:33', '2022-06-24 03:21:33', 1),
(14, 8, '2022-06-24 03:22:15', '2022-06-24 03:22:15', 1),
(15, 8, '2022-06-24 03:25:18', '2022-06-24 03:25:18', 1),
(16, 8, '2022-06-24 03:26:02', '2022-06-24 03:26:02', 1),
(17, 9, '2022-06-24 03:29:22', '2022-06-24 03:29:22', 1),
(20, 14, '2022-06-24 15:37:46', '2022-06-25 07:46:19', 0),
(21, 7, '2022-06-25 02:37:49', '2022-06-25 08:11:05', 0),
(22, 7, '2022-06-28 14:41:17', '2022-06-28 14:41:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `orders_id`, `product_id`, `quantity`, `total_price`) VALUES
(3, 9, 23, 1, 35),
(4, 10, 20, 1, 22),
(5, 11, 23, 1, 35),
(6, 11, 20, 1, 22),
(7, 12, 13, 2, 46),
(8, 12, 11, 1, 15),
(9, 13, 14, 1, 9),
(10, 14, 10, 2, 40),
(11, 14, 5, 1, 9),
(12, 14, 6, 1, 20),
(13, 15, 17, 1, 8),
(14, 16, 15, 1, 11),
(15, 17, 18, 1, 30),
(16, 17, 19, 1, 25),
(17, 17, 12, 1, 20),
(22, 20, 16, 1, 28),
(23, 20, 20, 1, 22),
(25, 21, 16, 1, 28),
(26, 22, 17, 1, 8),
(27, 22, 9, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `small_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detail_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `brand_id`, `supplier_id`, `name`, `price`, `small_desc`, `detail_desc`, `created_date`, `quantity`, `image`) VALUES
(5, 10, 4, 'Black T-shirt', 9, 'Small Desc Black T-shirt', 'Detail Desc Black T-shirt', '2022-06-21 02:39:17', 1, 'pro1-62b113355390d.jpg'),
(6, 11, 4, 'Black jeans', 20, 'Small Desc Black jeans', 'Detail Desc Black jeans', '2022-06-21 02:40:25', 2, 'pro2-62b11379d2fbe.jpg'),
(7, 13, 4, 'Light blue jeans', 10, 'Small Desc Light blue jeans', 'Detail Desc Light blue jeans', '2022-06-21 02:41:04', 2, 'pro3-62b113a0482df.jpg'),
(8, 14, 4, 'Loose jeans', 25, 'Small Desc Loose jeans', 'Detail Desc Loose jeans', '2022-06-21 02:42:10', 2, 'pro4-62b113e28709e.jpg'),
(9, 16, 5, 'Green T-shirt', 10, 'Small Desc Green T-shirt', 'Detail Desc Green T-shirt', '2022-06-21 02:42:51', 0, 'download-62b1140b9713a.jpg'),
(10, 15, 5, 'White T-shirt', 20, 'Small Desc White T-shirt', 'Detail Desc White T-shirt', '2022-06-21 02:44:07', 0, 'z3398765216804-bbd37e95412ae276b0740ed82e342127-62b11457561d8.jpg'),
(11, 13, 5, 'Checkered shirt', 15, 'Small Desc Checkered shirt', 'Detail Desc Checkered shirt', '2022-06-21 02:45:42', 2, 'z3398766422532-ede724b6a5829547fd899e7c185a16c8-62b114b6ab823.jpg'),
(12, 16, 6, 'Shorts', 20, 'Small Desc Shorts', 'Detail Desc Shorts', '2022-06-21 02:46:22', 1, 'z3398766617127-271089c387fd75d4a1bcc129e6b7e14e-62b114de4e57b.jpg'),
(13, 13, 4, 'Moss green shirt', 23, 'Small Desc Moss green shirt', 'Detail Desc Moss green shirt', '2022-06-21 02:47:50', 4, 'download-1-62b11536a566a.jpg'),
(14, 10, 6, 'Navy swim short', 9, 'Small Desc Navy swim short', 'Detail Desc Navy swim short', '2022-06-21 02:48:35', 1, 'navyswimshorts-62b1156326539.jpg'),
(15, 16, 4, 'Shorts stock', 11, 'Small Desc Shorts stock', 'Detail Desc Shorts stock', '2022-06-21 02:49:45', 4, 'ShortsStock-62b115a9f407e.jpg'),
(16, 11, 5, 'Plaid shirt', 28, 'Small Desc Plaid shirt', 'Detail Desc Plaid shirt', '2022-06-21 02:50:57', 1, 'images-62b115f1bde19.jpg'),
(17, 13, 5, 'Blue t-shirt', 8, 'Small Desc Blue t-shirt', 'Detail Desc Blue t-shirt', '2022-06-21 02:51:40', 2, '9088-62b1161c57045.png'),
(18, 15, 4, 'Leather jacket', 30, 'Small Desc Leather jacket', 'Detail Desc Leather jacket', '2022-06-21 02:52:22', 0, 'LeatherJacket-62b1164657953.jpg'),
(19, 14, 6, 'Bomber jacket', 25, 'Small Desc Bomber jacket', 'Detail Desc Bomber jacket', '2022-06-21 02:53:10', 1, 'BomberJacket-62b11676d7309.jpg'),
(20, 10, 5, 'Plain Hoodie', 22, 'Small Desc Plain Hoodie', 'Detail Desc Plain Hoodie', '2022-06-21 02:54:11', 0, 'PlainHoodie-62b116b352aa6.jpg'),
(21, 12, 5, 'Denim jacket', 30, 'Small Desc Denim jacket', 'Detail Desc Denim jacket', '2022-06-25 15:01:39', 3, 'DenimJacket-62b116dedaac6.jpg'),
(23, 12, 6, 'Denim jacket', 35, 'Small Desc Denim jeans', 'Detail Desc Denim jeans', '2022-06-25 15:17:12', 2, 'denim-jeans-large-black-62b11db08450e.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `telephone`, `email`) VALUES
(4, 'Giaysecondhand', '0884564666', 'Giaysecondhand@gmail.com'),
(5, 'Chodosi', '0846446513', 'Chodosi@gmail.vn'),
(6, 'Sida', '0895484564', 'Sida@gmail.vn');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'quangndgcc200030@fpt.edu.vn', '[\"ROLE_ADMIN\"]', '$2y$13$GAc/wlaH9j5wc6BKBoXcwetB4csF.9vu6zr8Hon4fSI2GurF./C/S'),
(24, 'usercustomer@gmail.com', '[\"ROLE_USER\"]', '$2y$13$8ZvUy05NChEwWU.4rK7JlefJcZFrDvAML9nXZ86/Y/vdmj7YhIgp2'),
(26, 'duongntgcc200026@fpt.edu.vn', '[\"ROLE_USER\"]', '$2y$13$aP2AmWHFj8tOaA051s6IfudcqMRvYQx7l.xyGgTnRRFiB4ZThoeD2'),
(27, 'anhntgcc200302@fpt.edu.vn', '[\"ROLE_USER\"]', '$2y$13$B3FrPiF2b7x6tomYIpihbeL3hWG0.XzGFKua6eGD2QANUpoDdIp5K'),
(29, 'trannqgcc210042@fpt.edu.vn', '[\"ROLE_USER\"]', '$2y$13$3JnGqDmhptUX3IAyzKFSG.4YS6C3dkFfy1ASOpksGmEXJ3rrWai/a');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_62534E21A76ED395` (`user_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D22944589395C3F3` (`customer_id`),
  ADD KEY `IDX_D22944584584665A` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E52FFDEEED766068` (`username_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_845CA2C1CFFE9AD6` (`orders_id`),
  ADD KEY `IDX_845CA2C14584665A` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B3BA5A5A44F5D008` (`brand_id`),
  ADD KEY `IDX_B3BA5A5A2ADD6D8C` (`supplier_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `FK_62534E21A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `FK_D22944584584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `FK_D22944589395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEEED766068` FOREIGN KEY (`username_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `FK_845CA2C14584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `FK_845CA2C1CFFE9AD6` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_B3BA5A5A2ADD6D8C` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `FK_B3BA5A5A44F5D008` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
