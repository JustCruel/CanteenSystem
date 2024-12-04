-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2024 at 07:25 PM
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
-- Database: `canteenms`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Drinks'),
(2, 'Snacks'),
(3, 'Breakfast Meals'),
(4, 'Lunch Meals'),
(6, 'Crackers');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `barcode` varchar(250) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `market_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `barcode`, `quantity`, `image`, `expiry_date`, `market_price`, `selling_price`, `category`) VALUES
(54, 'sopas', '8801062518098', 11, 'sopas.jpg', '2024-10-18', 20.00, 25.00, 'Breakfast Meals'),
(55, 'Skinless with Rice', '4800016677809', 25, 'skinless.jpg', '2024-10-18', 30.00, 50.00, 'Breakfast Meals'),
(56, 'Dinuguan with Rice', '9556437013898', 20, 'dinuguan rice.jpg', '2024-10-18', 50.00, 65.00, 'Lunch Meals'),
(57, 'Zesto', NULL, 50, 'zesto.jpg', '2024-12-31', 8.00, 12.00, 'Drinks'),
(58, 'Pork Giniling with rice', NULL, 21, 'pork-giniling-rice.jpg', '2024-10-18', 60.00, 65.00, 'Lunch Meals'),
(59, 'Fried Chicken with Rice', NULL, 23, 'Pride siken.webp', '2024-10-18', 60.00, 65.00, 'Lunch Meals'),
(60, 'Lugaw', NULL, 28, 'lugaw.jpg', '2024-10-18', 10.00, 15.00, 'Breakfast Meals'),
(61, 'Water', NULL, 48, 'nature spring.png', '2024-10-18', 6.00, 10.00, 'Drinks'),
(62, 'Piatos', NULL, 39, 'piattos-cheese-40g_2.jpg', '2025-01-02', 16.00, 19.00, 'Snacks'),
(63, 'nova', NULL, 24, 'nova.jpg', '2024-11-29', 15.00, 18.00, 'Snacks'),
(64, 'Cream O', NULL, 38, 'cream o.jpg', '2024-11-21', 10.00, 12.00, 'Snacks'),
(65, 'Coke', NULL, 27, 'coke.webp', '2024-12-19', 15.00, 20.00, 'Drinks'),
(66, 'Royal', NULL, 39, 'royal.jpg', '2024-12-26', 25.00, 20.00, 'Drinks'),
(67, 'Mentos Candy', NULL, 48, 'mentos.jpg', '2024-12-28', 1.00, 2.00, 'Snacks'),
(68, 'Bravo', NULL, 24, 'bravo.jpg', '2024-11-17', 7.00, 10.00, 'Snacks'),
(69, 'Max Candy', NULL, 30, 'maxxx.jpg', '2024-12-25', 1.00, 2.00, 'Snacks'),
(70, 'FudgeeBarr', NULL, 49, 'piattos-cheese-40g_2.jpg', '2026-10-13', 8.00, 10.00, 'Snacks'),
(71, 'Rebisco Cracker', NULL, 62, 'rebisco-crackers.jpg', '2024-11-06', 18.00, 12.00, 'Crackers'),
(74, '7 up', '9578545203541', 26, '7up.jpg', '2024-12-28', 35.00, 45.00, 'Drinks'),
(75, 'Zec', '8801062518098', 20, '537a018e9b829a0993643f713326e81b.jpg', '2024-10-24', 20.00, 25.00, 'Snacks');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `sale_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `quantity_sold`, `total`, `sale_date`) VALUES
(77, 54, 1, 25.00, '2024-10-17 06:59:27'),
(78, 55, 1, 50.00, '2024-10-17 06:59:27'),
(79, 56, 1, 65.00, '2024-10-17 06:59:27'),
(80, 54, 1, 25.00, '2024-10-17 07:02:33'),
(81, 55, 1, 50.00, '2024-10-17 07:02:33'),
(82, 56, 1, 65.00, '2024-10-17 07:02:33'),
(83, 69, 1, 2.00, '2024-10-17 07:48:27'),
(84, 54, 1, 25.00, '2024-10-19 13:15:26'),
(85, 55, 1, 50.00, '2024-10-19 13:15:26'),
(86, 54, 1, 25.00, '2024-10-19 14:51:26'),
(87, 55, 1, 50.00, '2024-10-19 14:51:26'),
(88, 54, 1, 25.00, '2024-10-19 15:20:40'),
(89, 55, 2, 100.00, '2024-10-19 15:21:17'),
(90, 55, 2, 100.00, '2024-10-19 15:21:17'),
(91, 55, 2, 100.00, '2024-10-19 15:21:17'),
(92, 56, 1, 65.00, '2024-10-19 15:21:29'),
(93, 54, 1, 25.00, '2024-10-19 15:31:35'),
(94, 55, 1, 50.00, '2024-10-19 15:31:35'),
(95, 70, 1, 10.00, '2024-10-19 15:39:22'),
(96, 71, 3, 30.00, '2024-10-19 15:39:31'),
(97, 54, 5, 125.00, '2024-10-19 17:54:28'),
(98, 54, 2, 50.00, '2024-10-19 17:57:18'),
(99, 65, 1, 20.00, '2024-10-19 17:57:18'),
(100, 60, 1, 15.00, '2024-10-19 17:57:18'),
(101, 59, 1, 65.00, '2024-10-19 17:58:45'),
(102, 55, 1, 50.00, '2024-10-19 17:58:45'),
(103, 65, 1, 20.00, '2024-10-19 17:58:45'),
(104, 61, 1, 10.00, '2024-10-19 17:58:45'),
(105, 54, 1, 25.00, '2024-10-19 18:45:46'),
(106, 74, 1, 45.00, '2024-10-19 18:45:46'),
(107, 54, 1, 25.00, '2024-10-19 18:49:25'),
(108, 74, 1, 45.00, '2024-10-19 18:49:25'),
(109, 64, 1, 12.00, '2024-10-19 18:50:19'),
(110, 56, 1, 65.00, '2024-10-19 18:50:19');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(250) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rfid_code` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `user_type` enum('user','cstaff','cmanager','cashier','superadmin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `student_id`, `first_name`, `middle_name`, `last_name`, `email`, `rfid_code`, `password`, `balance`, `user_type`) VALUES
(5, '52552021', 'James Andrew', 'Adriano', 'Beley', 'jemusubeley@gmail.com', '0013389623', '$2y$10$S57d2QOZ5cRPphyzW7eheeubD4.we1bPOPkx7Ebj031Zd0wQ.O8Zm', 12258966.00, 'user'),
(6, '52552021', 'James Andrew', 'Adriano', 'Beley', 'beley@gmail.com', '123456789', '$2y$10$sARfuV6e6HzPG.wkR9U34.1EIXQXb9vTNq7R.Grn86mtLor8LjHya', 0.00, 'user'),
(7, '46182021', 'Grant Mikhail', 'Cayaba', 'Dela Cruz', 'grantmikhail@gmail.com', '0012245507', '$2y$10$.VxmdHAd2G0XI0Vvfu918.A//FxmndCHnlQzgYmYpj1aUdDBsYYSm', 350.00, 'user'),
(8, '5042017', 'Abraham', 'Ducha', 'Flores', 'abrahamflores@gmail.com', '0011793621', '$2y$10$U2udgWJ7wdvwyjPKmhQAF.jx4zvNInpAeU013M1Rtzl5dJkEuxZxW', 0.00, 'user'),
(9, '0000', 'Super', 'Admin', 'To', 'superadmin@gmail.com', 'sadmin', '$2y$10$grGkQDeAQUraCd2i.sl1ue4jhtUw1KS31pCa87ke01kBlQ4RaN0Qa', 0.00, 'superadmin'),
(10, '0001', 'Canteen', 'Staff', 'To', 'cstaff@gmail.com', 'cstaff', '$2y$10$EgXp3VEGYZtFT96d0IHwQOLSf52nTZXypqFoX46YnLLfcplkuk/qO', 0.00, 'cstaff'),
(11, '0002', 'Cashier', 'Po', 'To', 'cashierhcc@gmail.com', 'cashierhcc', '$2y$10$Tbr/vQ3s2gZK5vVgdTXHF.UxFwxdGga/FaVzvQZiKx1If1b73.oCy', 0.00, 'cashier'),
(12, '0003', 'Canteen', 'Manager', 'To', 'cmanager@gmail.com', 'cmanager', '$2y$10$2DeXlrLHbHG4lJQQPbndu.rTiAP23qF.oXjOXicSEPekEv6I.DQ92', 0.00, 'cmanager'),
(13, '52651', 'Test', 'Test', 'Test', 'test@gmail.com', '0012258965', '$2y$10$BltPcVhKIBkdrHMvqw3wXOXV0aYIyfG1UooT0c1q0WovWjYFy3JSm', 353.00, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','cashier','cstaff','cadmin','student') DEFAULT 'cstaff',
  `user_type` enum('users','cstaff','cmanager','cashier','superadmin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `user_type`) VALUES
(3, 'cstaff', '$2y$10$skYXyxo69sUe1Wa5xNQ1jeGlyBbnwOBIBR0rp/w8yfQqu.I5fqM.W', 'cstaff', 'users'),
(4, 'cadmin', '$2y$10$TZv4A3AdOGTzfJhkuT.AguKb8HfZFZcLQ18zEUoE.OyXMXYIt3J1W', 'cadmin', 'users');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `rfid_code` (`rfid_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
