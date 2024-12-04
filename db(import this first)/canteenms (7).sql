-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2024 at 04:51 PM
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
-- Table structure for table `e_receipts`
--

CREATE TABLE `e_receipts` (
  `id` int(11) NOT NULL,
  `rfid_code` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `sale_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `e_receipts`
--

INSERT INTO `e_receipts` (`id`, `rfid_code`, `total_amount`, `sale_date`) VALUES
(6, '0013389623', 132.00, '2024-10-20 14:53:04'),
(7, '0013389623', 37.00, '2024-10-20 14:56:11'),
(8, '0013389623', 238.00, '2024-10-20 15:03:35'),
(9, '0013389623', 20.00, '2024-10-20 15:14:58'),
(10, '0013389623', 20.00, '2024-10-20 15:14:58'),
(11, '0013442974', 95.00, '2024-10-20 15:43:09');

-- --------------------------------------------------------

--
-- Table structure for table `e_receipt_details`
--

CREATE TABLE `e_receipt_details` (
  `id` int(11) NOT NULL,
  `e_receipt_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `e_receipt_details`
--

INSERT INTO `e_receipt_details` (`id`, `e_receipt_id`, `product_id`, `product_name`, `quantity_sold`, `total`) VALUES
(1, 6, 68, 'Bravo', 1, 10.00),
(2, 6, 65, 'Coke', 1, 20.00),
(3, 6, 64, 'Cream O', 1, 12.00),
(4, 6, 60, 'Lugaw', 1, 15.00),
(5, 6, 70, 'FudgeeBarr', 1, 10.00),
(6, 6, 59, 'Fried Chicken with Rice', 1, 65.00),
(7, 7, 63, 'nova', 1, 18.00),
(8, 7, 67, 'Mentos Candy', 1, 2.00),
(9, 7, 69, 'Max Candy', 1, 2.00),
(10, 7, 60, 'Lugaw', 1, 15.00),
(11, 8, 68, 'Bravo', 1, 10.00),
(12, 8, 65, 'Coke', 1, 20.00),
(13, 8, 64, 'Cream O', 1, 12.00),
(14, 8, 56, 'Dinuguan with Rice', 1, 65.00),
(15, 8, 59, 'Fried Chicken with Rice', 1, 65.00),
(16, 8, 62, 'Piatos', 1, 19.00),
(17, 8, 63, 'nova', 1, 18.00),
(18, 8, 67, 'Mentos Candy', 1, 2.00),
(19, 8, 69, 'Max Candy', 1, 2.00),
(20, 8, 60, 'Lugaw', 1, 15.00),
(21, 8, 70, 'FudgeeBarr', 1, 10.00),
(22, 10, 65, 'Coke', 1, 20.00),
(23, 9, 65, 'Coke', 1, 20.00),
(24, 11, 65, 'Coke', 1, 20.00),
(25, 11, 68, 'Bravo', 1, 10.00),
(26, 11, 56, 'Dinuguan with Rice', 1, 65.00);

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
(54, 'sopas', '8801062518098', 9, 'sopas.jpg', '2024-10-18', 20.00, 25.00, 'Breakfast Meals'),
(55, 'Skinless with Rice', '4800016677809', 23, 'skinless.jpg', '2024-10-18', 30.00, 50.00, 'Breakfast Meals'),
(56, 'Dinuguan with Rice', '9556437013898', 16, 'dinuguan rice.jpg', '2024-10-18', 50.00, 65.00, 'Lunch Meals'),
(57, 'Zesto', '0123456745650', 49, 'zesto.jpg', '2024-12-31', 8.00, 12.00, 'Drinks'),
(58, 'Pork Giniling with rice', NULL, 19, 'pork-giniling-rice.jpg', '2024-10-18', 60.00, 65.00, 'Lunch Meals'),
(59, 'Fried Chicken with Rice', NULL, 17, 'Pride siken.webp', '2024-10-18', 60.00, 65.00, 'Lunch Meals'),
(60, 'Lugaw', NULL, 21, 'lugaw.jpg', '2024-10-18', 10.00, 15.00, 'Breakfast Meals'),
(61, 'Water', NULL, 47, 'nature spring.png', '2024-10-18', 6.00, 10.00, 'Drinks'),
(62, 'Piatos', NULL, 36, 'piattos-cheese-40g_2.jpg', '2025-01-02', 16.00, 19.00, 'Snacks'),
(63, 'nova', NULL, 20, 'nova.jpg', '2024-11-29', 15.00, 18.00, 'Snacks'),
(64, 'Cream O', NULL, 28, 'cream o.jpg', '2024-11-21', 10.00, 12.00, 'Snacks'),
(65, 'Coke', NULL, 10, 'coke.webp', '2024-12-19', 15.00, 20.00, 'Drinks'),
(66, 'Royal', NULL, 39, 'royal.jpg', '2024-12-26', 25.00, 20.00, 'Drinks'),
(67, 'Mentos Candy', NULL, 44, 'mentos.jpg', '2024-12-28', 1.00, 2.00, 'Snacks'),
(68, 'Bravo', NULL, 12, 'bravo.jpg', '2024-11-17', 7.00, 10.00, 'Snacks'),
(69, 'Max Candy', NULL, 26, 'maxxx.jpg', '2024-12-25', 1.00, 2.00, 'Snacks'),
(70, 'FudgeeBarr', NULL, 43, 'piattos-cheese-40g_2.jpg', '2026-10-13', 8.00, 10.00, 'Snacks'),
(71, 'Rebisco Cracker', NULL, 61, 'rebisco-crackers.jpg', '2024-11-06', 18.00, 12.00, 'Crackers'),
(74, '7 up', '9578545203541', 0, '7up.jpg', '2024-12-28', 35.00, 45.00, 'Drinks'),
(75, 'Zec', '8801062518098', 19, '537a018e9b829a0993643f713326e81b.jpg', '2024-10-24', 20.00, 25.00, 'Snacks');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','rfid') NOT NULL,
  `items` text NOT NULL,
  `issue_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(110, 56, 1, 65.00, '2024-10-19 18:50:19'),
(111, 64, 1, 12.00, '2024-10-20 12:22:05'),
(112, 65, 1, 20.00, '2024-10-20 12:22:05'),
(113, 68, 1, 10.00, '2024-10-20 12:22:05'),
(114, 74, 1, 45.00, '2024-10-20 12:22:05'),
(115, 59, 1, 65.00, '2024-10-20 12:22:05'),
(116, 62, 1, 19.00, '2024-10-20 12:22:05'),
(117, 63, 1, 18.00, '2024-10-20 12:22:05'),
(118, 67, 1, 2.00, '2024-10-20 12:22:05'),
(119, 69, 1, 2.00, '2024-10-20 12:22:05'),
(120, 60, 1, 15.00, '2024-10-20 12:22:05'),
(121, 70, 1, 10.00, '2024-10-20 12:22:05'),
(122, 54, 1, 25.00, '2024-10-20 12:22:05'),
(123, 55, 1, 50.00, '2024-10-20 12:22:05'),
(124, 58, 1, 65.00, '2024-10-20 12:22:05'),
(125, 57, 1, 12.00, '2024-10-20 12:22:05'),
(126, 75, 1, 25.00, '2024-10-20 12:22:05'),
(127, 65, 1, 20.00, '2024-10-20 12:25:14'),
(128, 68, 1, 10.00, '2024-10-20 12:25:14'),
(129, 74, 1, 45.00, '2024-10-20 12:25:14'),
(130, 56, 1, 65.00, '2024-10-20 12:25:14'),
(131, 59, 1, 65.00, '2024-10-20 12:25:14'),
(132, 62, 1, 19.00, '2024-10-20 12:25:14'),
(133, 63, 1, 18.00, '2024-10-20 12:25:14'),
(134, 67, 1, 2.00, '2024-10-20 12:25:14'),
(135, 69, 1, 2.00, '2024-10-20 12:25:14'),
(136, 60, 1, 15.00, '2024-10-20 12:25:14'),
(137, 70, 1, 10.00, '2024-10-20 12:25:14'),
(138, 54, 1, 25.00, '2024-10-20 12:25:14'),
(139, 55, 1, 50.00, '2024-10-20 12:25:14'),
(140, 71, 1, 12.00, '2024-10-20 12:25:14'),
(141, 58, 1, 65.00, '2024-10-20 12:25:14'),
(142, 61, 1, 10.00, '2024-10-20 12:25:14'),
(143, 65, 1, 20.00, '2024-10-20 13:51:48'),
(144, 64, 1, 12.00, '2024-10-20 13:51:48'),
(145, 59, 1, 65.00, '2024-10-20 14:00:14'),
(146, 74, 1, 45.00, '2024-10-20 14:06:10'),
(147, 74, 1, 45.00, '2024-10-20 14:06:23'),
(148, 74, 1, 45.00, '2024-10-20 14:09:00'),
(149, 74, 1, 45.00, '2024-10-20 14:16:58'),
(150, 74, 1, 45.00, '2024-10-20 14:17:08'),
(151, 74, 1, 45.00, '2024-10-20 14:20:54'),
(152, 74, 1, 45.00, '2024-10-20 14:21:04'),
(153, 74, 1, 45.00, '2024-10-20 14:22:56'),
(154, 74, 1, 45.00, '2024-10-20 14:23:36'),
(155, 74, 1, 45.00, '2024-10-20 14:23:45'),
(156, 74, 1, 45.00, '2024-10-20 14:24:11'),
(157, 74, 1, 45.00, '2024-10-20 14:27:57'),
(158, 74, 1, 45.00, '2024-10-20 14:29:38'),
(159, 74, 1, 45.00, '2024-10-20 14:32:37'),
(160, 68, 1, 10.00, '2024-10-20 14:32:37'),
(161, 68, 1, 10.00, '2024-10-20 14:34:47'),
(162, 74, 1, 45.00, '2024-10-20 14:34:47'),
(163, 74, 1, 45.00, '2024-10-20 14:36:43'),
(164, 74, 1, 45.00, '2024-10-20 14:38:38'),
(165, 65, 1, 20.00, '2024-10-20 14:38:47'),
(166, 65, 1, 20.00, '2024-10-20 14:38:56'),
(167, 74, 1, 45.00, '2024-10-20 14:42:36'),
(168, 64, 1, 12.00, '2024-10-20 14:43:39'),
(169, 65, 1, 20.00, '2024-10-20 14:43:39'),
(170, 68, 1, 10.00, '2024-10-20 14:43:39'),
(171, 74, 1, 45.00, '2024-10-20 14:43:39'),
(172, 64, 1, 12.00, '2024-10-20 14:43:39'),
(173, 65, 1, 20.00, '2024-10-20 14:43:39'),
(174, 68, 1, 10.00, '2024-10-20 14:43:39'),
(175, 74, 1, 45.00, '2024-10-20 14:43:39'),
(176, 74, 2, 90.00, '2024-10-20 14:47:52'),
(177, 68, 1, 10.00, '2024-10-20 14:47:52'),
(178, 65, 1, 20.00, '2024-10-20 14:47:52'),
(179, 64, 1, 12.00, '2024-10-20 14:47:52'),
(180, 60, 1, 15.00, '2024-10-20 14:47:52'),
(181, 70, 1, 10.00, '2024-10-20 14:47:52'),
(182, 64, 1, 12.00, '2024-10-20 14:49:25'),
(183, 65, 1, 20.00, '2024-10-20 14:49:25'),
(184, 68, 1, 10.00, '2024-10-20 14:49:25'),
(185, 74, 1, 45.00, '2024-10-20 14:49:25'),
(186, 56, 1, 65.00, '2024-10-20 14:49:25'),
(187, 59, 1, 65.00, '2024-10-20 14:49:25'),
(188, 70, 1, 10.00, '2024-10-20 14:49:25'),
(189, 60, 1, 15.00, '2024-10-20 14:49:25'),
(190, 68, 1, 10.00, '2024-10-20 14:53:04'),
(191, 65, 1, 20.00, '2024-10-20 14:53:04'),
(192, 64, 1, 12.00, '2024-10-20 14:53:04'),
(193, 60, 1, 15.00, '2024-10-20 14:53:04'),
(194, 70, 1, 10.00, '2024-10-20 14:53:04'),
(195, 59, 1, 65.00, '2024-10-20 14:53:04'),
(196, 63, 1, 18.00, '2024-10-20 14:56:11'),
(197, 67, 1, 2.00, '2024-10-20 14:56:11'),
(198, 69, 1, 2.00, '2024-10-20 14:56:11'),
(199, 60, 1, 15.00, '2024-10-20 14:56:11'),
(200, 68, 1, 10.00, '2024-10-20 15:03:35'),
(201, 65, 1, 20.00, '2024-10-20 15:03:35'),
(202, 64, 1, 12.00, '2024-10-20 15:03:35'),
(203, 56, 1, 65.00, '2024-10-20 15:03:35'),
(204, 59, 1, 65.00, '2024-10-20 15:03:35'),
(205, 62, 1, 19.00, '2024-10-20 15:03:35'),
(206, 63, 1, 18.00, '2024-10-20 15:03:35'),
(207, 67, 1, 2.00, '2024-10-20 15:03:35'),
(208, 69, 1, 2.00, '2024-10-20 15:03:35'),
(209, 60, 1, 15.00, '2024-10-20 15:03:35'),
(210, 70, 1, 10.00, '2024-10-20 15:03:35'),
(211, 65, 1, 20.00, '2024-10-20 15:14:58'),
(212, 65, 1, 20.00, '2024-10-20 15:14:58'),
(213, 65, 1, 20.00, '2024-10-20 15:43:09'),
(214, 68, 1, 10.00, '2024-10-20 15:43:09'),
(215, 56, 1, 65.00, '2024-10-20 15:43:09');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` enum('cash','rfid') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `items` text NOT NULL,
  `sale_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(7, '46182021', 'Grant Mikhail', 'Cayaba', 'Dela Cruz', 'grantmikhail@gmail.com', '0012245507', '$2y$10$.VxmdHAd2G0XI0Vvfu918.A//FxmndCHnlQzgYmYpj1aUdDBsYYSm', 350.00, 'user'),
(8, '5042017', 'Abraham', 'Ducha', 'Flores', 'abrahamflores@gmail.com', '0011793621', '$2y$10$U2udgWJ7wdvwyjPKmhQAF.jx4zvNInpAeU013M1Rtzl5dJkEuxZxW', 0.00, 'user'),
(9, '0000', 'Super', 'Admin', 'To', 'superadmin@gmail.com', 'sadmin', '$2y$10$grGkQDeAQUraCd2i.sl1ue4jhtUw1KS31pCa87ke01kBlQ4RaN0Qa', 0.00, 'superadmin'),
(10, '0001', 'Canteen', 'Staff', 'To', 'cstaff@gmail.com', 'cstaff', '$2y$10$EgXp3VEGYZtFT96d0IHwQOLSf52nTZXypqFoX46YnLLfcplkuk/qO', 0.00, 'cstaff'),
(11, '0002', 'Cashier', 'Po', 'To', 'cashierhcc@gmail.com', 'cashierhcc', '$2y$10$Tbr/vQ3s2gZK5vVgdTXHF.UxFwxdGga/FaVzvQZiKx1If1b73.oCy', 0.00, 'cashier'),
(12, '0003', 'Canteen', 'Manager', 'To', 'cmanager@gmail.com', 'cmanager', '$2y$10$2DeXlrLHbHG4lJQQPbndu.rTiAP23qF.oXjOXicSEPekEv6I.DQ92', 0.00, 'cmanager'),
(13, '52651', 'Test', 'Test', 'Test', 'test@gmail.com', '0012258965', '$2y$10$BltPcVhKIBkdrHMvqw3wXOXV0aYIyfG1UooT0c1q0WovWjYFy3JSm', 353.00, 'user'),
(14, '52552021', 'James Andrew', 'Adriano', 'Beley', 'jemusubeley@gmail.com', '0013389623', '$2y$10$nGfOXORF82tzXRGXu9QtAeNho.WKLAXF/O1Ry2MLpKIf1JRSLWYsa', 0.00, 'user'),
(15, '52552022', 'Abraham', 'Ducha', 'Flores', 'abraham@gmail.com', '0013442974', '$2y$10$v3p.e5EUcyoAA8Cil74ajuocJwK9sWYwseUZ1ULdq1e691yHCJJB6', 405.00, 'user');

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
-- Indexes for table `e_receipts`
--
ALTER TABLE `e_receipts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `e_receipt_details`
--
ALTER TABLE `e_receipt_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `e_receipt_id` (`e_receipt_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `e_receipts`
--
ALTER TABLE `e_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `e_receipt_details`
--
ALTER TABLE `e_receipt_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `e_receipt_details`
--
ALTER TABLE `e_receipt_details`
  ADD CONSTRAINT `e_receipt_details_ibfk_1` FOREIGN KEY (`e_receipt_id`) REFERENCES `e_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `e_receipt_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `receipts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
