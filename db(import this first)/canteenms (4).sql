-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2024 at 11:36 PM
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
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `transaction_number` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `e_receipts`
--

INSERT INTO `e_receipts` (`id`, `rfid_code`, `total_amount`, `sale_date`, `transaction_number`) VALUES
(57, '0013442974', 101.00, '0000-00-00 00:00:00', 'HCC-828545'),
(58, '0013442974', 252.00, '0000-00-00 00:00:00', 'HCC-472886'),
(59, '0013442974', 50.00, '0000-00-00 00:00:00', 'HCC-420834'),
(60, '0013442974', 32.00, '2024-10-22 10:24:37', 'HCC-266228'),
(61, '0013442974', 21.00, '2024-10-22 10:25:19', 'HCC-663295'),
(62, '0013442974', 21.00, '0000-00-00 00:00:00', '2024-10-22 18:26:07'),
(63, '0013442974', 21.00, '0000-00-00 00:00:00', '2024-10-22 18:26:55'),
(64, '', 36.00, '2024-10-22 10:28:46', 'HCC-705001'),
(65, '0013442974', 21.00, '2024-10-22 10:29:13', 'HCC-269347'),
(66, '0013442974', 10.00, '2024-10-22 16:34:49', 'HCC-759449'),
(67, '0013442974', 10.00, '2024-10-22 10:36:33', 'HCC-043561'),
(68, '0013442974', 10.00, '2024-10-22 10:37:03', 'HCC-348522'),
(69, '0013442974', 11.00, '2024-10-22 16:40:17', 'HCC-500290'),
(70, '0013442974', 46.00, '2024-10-22 16:41:37', 'HCC-047265'),
(71, '0013442974', 58.00, '2024-10-22 16:48:40', 'HCC-928445'),
(72, '0013442974', 31.00, '2024-10-22 20:54:07', 'HCC-892130'),
(73, '', 22.00, '2024-10-22 20:54:13', 'HCC-126232'),
(74, '0013442974', 36.00, '2024-10-22 20:58:45', 'HCC-448661'),
(75, '0012258965', 30.00, '2024-10-22 21:18:32', 'HCC-113001'),
(76, '0013442974', 41.00, '2024-10-22 21:19:05', 'HCC-796229'),
(77, '0013442974', 41.00, '2024-10-22 21:19:05', 'HCC-673935'),
(78, '0013442974', 135.00, '2024-10-22 21:19:20', 'HCC-291051'),
(79, '0013442974', 41.00, '2024-10-22 21:21:08', 'HCC-299056'),
(80, '', 142.00, '2024-10-22 21:22:09', 'HCC-335228');

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
  `total` decimal(10,2) NOT NULL,
  `transaction_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `e_receipt_details`
--

INSERT INTO `e_receipt_details` (`id`, `e_receipt_id`, `product_id`, `product_name`, `quantity_sold`, `total`, `transaction_number`) VALUES
(22, 57, 81, 'Lucky Me Beef Spicy Labuyo', 4, 48.00, 'HCC-828545'),
(23, 57, 65, 'Coke', 1, 20.00, 'HCC-828545'),
(24, 57, 64, 'Cream O', 1, 12.00, 'HCC-828545'),
(25, 57, 76, 'Cheese Cake', 1, 10.00, 'HCC-828545'),
(26, 57, 68, 'Bravo', 1, 11.00, 'HCC-828545'),
(27, 58, 78, 'Dewberry', 1, 10.00, 'HCC-472886'),
(28, 58, 64, 'Cream O', 1, 12.00, 'HCC-472886'),
(29, 58, 65, 'Coke', 1, 20.00, 'HCC-472886'),
(30, 58, 76, 'Cheese Cake', 1, 10.00, 'HCC-472886'),
(31, 58, 68, 'Bravo', 1, 11.00, 'HCC-472886'),
(32, 58, 74, '7 up', 1, 25.00, 'HCC-472886'),
(33, 58, 56, 'Dinuguan with Rice', 1, 65.00, 'HCC-472886'),
(34, 58, 59, 'Fried Chicken with Rice', 1, 60.00, 'HCC-472886'),
(35, 58, 70, 'FudgeeBarr', 1, 10.00, 'HCC-472886'),
(36, 58, 81, 'Lucky Me Beef Spicy Labuyo', 1, 12.00, 'HCC-472886'),
(37, 58, 60, 'Lugaw', 1, 15.00, 'HCC-472886'),
(38, 58, 69, 'Max Candy', 1, 2.00, 'HCC-472886'),
(39, 59, 76, 'Cheese Cake', 1, 10.00, 'HCC-420834'),
(40, 59, 68, 'Bravo', 1, 11.00, 'HCC-420834'),
(41, 59, 64, 'Cream O', 1, 12.00, 'HCC-420834'),
(42, 59, 60, 'Lugaw', 1, 15.00, 'HCC-420834'),
(43, 59, 69, 'Max Candy', 1, 2.00, 'HCC-420834'),
(44, 64, 68, 'Bravo', 1, 11.00, 'HCC-705001'),
(45, 64, 74, '7 up', 1, 25.00, 'HCC-705001'),
(46, 65, 76, 'Cheese Cake', 1, 10.00, 'HCC-269347'),
(47, 65, 68, 'Bravo', 1, 11.00, 'HCC-269347'),
(48, 66, 76, 'Cheese Cake', 1, 10.00, 'HCC-759449'),
(49, 67, 76, 'Cheese Cake', 1, 10.00, 'HCC-043561'),
(50, 68, 76, 'Cheese Cake', 1, 10.00, 'HCC-348522'),
(51, 69, 68, 'Bravo', 1, 11.00, 'HCC-500290'),
(52, 70, 74, '7 up', 1, 25.00, 'HCC-047265'),
(53, 70, 68, 'Bravo', 1, 11.00, 'HCC-047265'),
(54, 70, 76, 'Cheese Cake', 1, 10.00, 'HCC-047265'),
(55, 71, 74, '7 up', 1, 25.00, 'HCC-928445'),
(56, 71, 68, 'Bravo', 1, 11.00, 'HCC-928445'),
(57, 71, 76, 'Cheese Cake', 1, 10.00, 'HCC-928445'),
(58, 71, 64, 'Cream O', 1, 12.00, 'HCC-928445'),
(59, 72, 68, 'Bravo', 1, 11.00, 'HCC-892130'),
(60, 72, 65, 'Coke', 1, 20.00, 'HCC-892130'),
(61, 73, 78, 'Dewberry', 1, 10.00, 'HCC-126232'),
(62, 73, 64, 'Cream O', 1, 12.00, 'HCC-126232'),
(63, 74, 74, '7 up', 1, 25.00, 'HCC-448661'),
(64, 74, 68, 'Bravo', 1, 11.00, 'HCC-448661'),
(65, 75, 76, 'Cheese Cake', 1, 10.00, 'HCC-113001'),
(66, 75, 65, 'Coke', 1, 20.00, 'HCC-113001'),
(67, 76, 65, 'Coke', 1, 20.00, 'HCC-796229'),
(68, 77, 65, 'Coke', 1, 20.00, 'HCC-673935'),
(69, 76, 76, 'Cheese Cake', 1, 10.00, 'HCC-796229'),
(70, 77, 76, 'Cheese Cake', 1, 10.00, 'HCC-673935'),
(71, 76, 68, 'Bravo', 1, 11.00, 'HCC-796229'),
(72, 77, 68, 'Bravo', 1, 11.00, 'HCC-673935'),
(73, 78, 78, 'Dewberry', 1, 10.00, 'HCC-291051'),
(74, 78, 56, 'Dinuguan with Rice', 1, 65.00, 'HCC-291051'),
(75, 78, 59, 'Fried Chicken with Rice', 1, 60.00, 'HCC-291051'),
(76, 79, 68, 'Bravo', 1, 11.00, 'HCC-299056'),
(77, 79, 76, 'Cheese Cake', 1, 10.00, 'HCC-299056'),
(78, 79, 65, 'Coke', 1, 20.00, 'HCC-299056'),
(79, 80, 56, 'Dinuguan with Rice', 1, 65.00, 'HCC-335228'),
(80, 80, 59, 'Fried Chicken with Rice', 1, 60.00, 'HCC-335228'),
(81, 80, 60, 'Lugaw', 1, 15.00, 'HCC-335228'),
(82, 80, 69, 'Max Candy', 1, 2.00, 'HCC-335228');

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
(54, 'sopas', '8801062518098', 0, 'sopas.jpg', '2024-10-18', 20.00, 25.00, 'Breakfast Meals'),
(55, 'Skinless with Rice', '4800016677809', 22, 'skinless.jpg', '2024-10-18', 30.00, 50.00, 'Breakfast Meals'),
(56, 'Dinuguan with Rice', '9556437013898', 8, 'dinuguan rice.jpg', '2024-10-18', 50.00, 65.00, 'Lunch Meals'),
(57, 'Zesto', '0123456745650', 49, 'zesto.jpg', '2024-12-31', 8.00, 12.00, 'Drinks'),
(58, 'Pork Giniling with rice', NULL, 15, 'pork-giniling-rice.jpg', '2024-10-18', 60.00, 65.00, 'Lunch Meals'),
(59, 'Fried Chicken with Rice', NULL, 20, 'Pride siken.webp', '2024-10-18', 60.00, 60.00, 'Lunch Meals'),
(60, 'Lugaw', NULL, 23, 'lugaw.jpg', '2024-10-18', 10.00, 15.00, 'Breakfast Meals'),
(61, 'Water', '4800049720107', 44, 'nature spring.png', '2024-10-18', 6.00, 10.00, 'Drinks'),
(62, 'Piatos', NULL, 48, 'piattos-cheese-40g_2.jpg', '2025-01-02', 16.00, 19.00, 'Snacks'),
(63, 'nova', NULL, 22, 'nova.jpg', '2024-11-29', 15.00, 18.00, 'Snacks'),
(64, 'Cream O', NULL, 23, 'cream o.jpg', '2024-11-21', 10.00, 12.00, 'Snacks'),
(65, 'Coke', '4800016073960', 12, 'coke.webp', '2024-12-19', 15.00, 20.00, 'Drinks'),
(66, 'Royal', NULL, 50, 'royal.jpg', '2024-12-26', 25.00, 20.00, 'Drinks'),
(67, 'Mentos Candy', NULL, 41, 'mentos.jpg', '2024-12-28', 1.00, 2.00, 'Snacks'),
(68, 'Bravo', NULL, 4, 'bravo.jpg', '2024-11-17', 7.00, 11.00, 'Snacks'),
(69, 'Max Candy', NULL, 45, 'maxxx.jpg', '2024-12-25', 1.00, 2.00, 'Snacks'),
(70, 'FudgeeBarr', NULL, 47, 'piattos-cheese-40g_2.jpg', '2026-10-13', 8.00, 10.00, 'Snacks'),
(71, 'Rebisco Cracker', NULL, 61, 'rebisco-crackers.jpg', '2024-11-06', 18.00, 12.00, 'Crackers'),
(74, '7 up', '9578545203541', 60, '7up.jpg', '2024-12-28', 35.00, 25.00, 'Drinks'),
(75, 'Zec', '8801062518098', 40, '537a018e9b829a0993643f713326e81b.jpg', '2024-10-24', 20.00, 20.00, 'Snacks'),
(76, 'Cheese Cake', '1411000956805', 10, 'Cheesecake-Mock-Up-Inner-min-930x1024.png', '2024-12-28', 8.00, 10.00, 'Snacks'),
(77, 'Vitasoy', '4800016073960', 20, '7up.jpg', '2024-10-24', 10.00, 15.00, 'Drinks'),
(78, 'Dewberry', '4800016113055', 3, '537a018e9b829a0993643f713326e81b.jpg', '2024-10-22', 5.00, 10.00, 'Snacks'),
(79, 'Snacku', '8801062518098', 20, 'piattos-cheese-40g_2.jpg', '2024-10-22', 10.00, 15.00, 'Snacks'),
(81, 'Lucky Me Beef Spicy Labuyo', '4807770272325', 25, 'eb401bd605d39d24dc4353c4d59077bf.jpg', '2025-02-22', 8.00, 12.00, 'Breakfast Meals'),
(82, 'spicy', '4800016073960', 11, 'eb401bd605d39d24dc4353c4d59077bf.jpg', '2024-11-02', 1.00, 1.00, 'Breakfast Meals');

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
-- Table structure for table `rfid_history`
--

CREATE TABLE `rfid_history` (
  `id` int(11) NOT NULL,
  `rfid_code` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `action` enum('activated','disabled') NOT NULL,
  `action_time` datetime DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfid_history`
--

INSERT INTO `rfid_history` (`id`, `rfid_code`, `user_name`, `action`, `action_time`, `user_id`) VALUES
(5, 'sadmin', 'Super Admin To', 'activated', '2024-10-22 22:34:45', 9),
(6, '0012245507', 'Grant Mikhail Cayaba Dela Cruz', 'disabled', '2024-10-22 22:35:42', 7),
(7, '0012245507', 'Grant Mikhail Cayaba Dela Cruz', 'activated', '2024-10-22 22:39:15', 7),
(8, '0012245507', 'Grant Mikhail Cayaba Dela Cruz', '', '2024-10-22 22:39:35', 7),
(9, '0012245507', 'Grant Mikhail Cayaba Dela Cruz', 'activated', '2024-10-22 22:40:00', 7),
(10, '0011793621', 'Abraham Ducha Flores', '', '2024-10-22 22:40:08', 8),
(11, '0012245507', 'Grant Mikhail Cayaba Dela Cruz', '', '2024-10-22 22:41:12', 7),
(12, 'sadmin', 'Super Admin To', '', NULL, 9),
(13, 'cstaff', 'Canteen Staff To', '', '2024-10-22 22:45:41', 10),
(14, '0012245507', 'Grant Mikhail Cayaba Dela Cruz', 'activated', '2024-10-22 22:46:10', 7),
(15, '0011793621', 'Abraham Ducha Flores', 'activated', '2024-10-22 22:46:13', 8),
(16, 'sadmin', 'Super Admin To', 'activated', '2024-10-22 22:46:16', 9),
(17, 'cstaff', 'Canteen Staff To', 'activated', '2024-10-22 22:46:19', 10),
(18, '0012245507', 'Grant Mikhail Cayaba Dela Cruz', '', '2024-10-22 22:47:04', 7),
(19, '0011793621', 'Abraham Ducha Flores', 'disabled', '2024-10-22 22:47:46', 8),
(20, '0012245507', 'Grant Mikhail Cayaba Dela Cruz', 'activated', '2024-10-22 22:48:00', 7),
(21, '0011793621', 'Abraham Ducha Flores', 'activated', '2024-10-22 22:48:03', 8),
(22, '0012258965', 'Test Test Test', 'disabled', '2024-10-22 22:53:27', 13),
(23, '0012258965', 'Test Test Test', 'activated', '2024-10-22 23:17:08', 13),
(24, '0012258965', 'Test Test Test', 'disabled', '2024-10-22 23:20:00', 13),
(25, '0012258965', 'Test Test Test', 'activated', '2024-10-22 23:24:48', 13),
(26, '0013442974', 'Test Test Test', 'disabled', '2024-10-22 23:25:05', 16),
(27, '0012258965', 'Test Test Test', 'disabled', '2024-10-22 23:25:11', 13);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `sale_date` datetime DEFAULT current_timestamp(),
  `transaction_number` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `quantity_sold`, `total`, `sale_date`, `transaction_number`) VALUES
(77, 54, 1, 25.00, '2024-10-17 06:59:27', '0'),
(78, 55, 1, 50.00, '2024-10-17 06:59:27', '0'),
(79, 56, 1, 65.00, '2024-10-17 06:59:27', '0'),
(80, 54, 1, 25.00, '2024-10-17 07:02:33', '0'),
(81, 55, 1, 50.00, '2024-10-17 07:02:33', '0'),
(82, 56, 1, 65.00, '2024-10-17 07:02:33', '0'),
(83, 69, 1, 2.00, '2024-10-17 07:48:27', '0'),
(84, 54, 1, 25.00, '2024-10-19 13:15:26', '0'),
(85, 55, 1, 50.00, '2024-10-19 13:15:26', '0'),
(86, 54, 1, 25.00, '2024-10-19 14:51:26', '0'),
(87, 55, 1, 50.00, '2024-10-19 14:51:26', '0'),
(88, 54, 1, 25.00, '2024-10-19 15:20:40', '0'),
(89, 55, 2, 100.00, '2024-10-19 15:21:17', '0'),
(90, 55, 2, 100.00, '2024-10-19 15:21:17', '0'),
(91, 55, 2, 100.00, '2024-10-19 15:21:17', '0'),
(92, 56, 1, 65.00, '2024-10-19 15:21:29', '0'),
(93, 54, 1, 25.00, '2024-10-19 15:31:35', '0'),
(94, 55, 1, 50.00, '2024-10-19 15:31:35', '0'),
(95, 70, 1, 10.00, '2024-10-19 15:39:22', '0'),
(96, 71, 3, 30.00, '2024-10-19 15:39:31', '0'),
(97, 54, 5, 125.00, '2024-10-19 17:54:28', '0'),
(98, 54, 2, 50.00, '2024-10-19 17:57:18', '0'),
(99, 65, 1, 20.00, '2024-10-19 17:57:18', '0'),
(100, 60, 1, 15.00, '2024-10-19 17:57:18', '0'),
(101, 59, 1, 65.00, '2024-10-19 17:58:45', '0'),
(102, 55, 1, 50.00, '2024-10-19 17:58:45', '0'),
(103, 65, 1, 20.00, '2024-10-19 17:58:45', '0'),
(104, 61, 1, 10.00, '2024-10-19 17:58:45', '0'),
(105, 54, 1, 25.00, '2024-10-19 18:45:46', '0'),
(106, 74, 1, 45.00, '2024-10-19 18:45:46', '0'),
(107, 54, 1, 25.00, '2024-10-19 18:49:25', '0'),
(108, 74, 1, 45.00, '2024-10-19 18:49:25', '0'),
(109, 64, 1, 12.00, '2024-10-19 18:50:19', '0'),
(110, 56, 1, 65.00, '2024-10-19 18:50:19', '0'),
(111, 64, 1, 12.00, '2024-10-20 12:22:05', '0'),
(112, 65, 1, 20.00, '2024-10-20 12:22:05', '0'),
(113, 68, 1, 10.00, '2024-10-20 12:22:05', '0'),
(114, 74, 1, 45.00, '2024-10-20 12:22:05', '0'),
(115, 59, 1, 65.00, '2024-10-20 12:22:05', '0'),
(116, 62, 1, 19.00, '2024-10-20 12:22:05', '0'),
(117, 63, 1, 18.00, '2024-10-20 12:22:05', '0'),
(118, 67, 1, 2.00, '2024-10-20 12:22:05', '0'),
(119, 69, 1, 2.00, '2024-10-20 12:22:05', '0'),
(120, 60, 1, 15.00, '2024-10-20 12:22:05', '0'),
(121, 70, 1, 10.00, '2024-10-20 12:22:05', '0'),
(122, 54, 1, 25.00, '2024-10-20 12:22:05', '0'),
(123, 55, 1, 50.00, '2024-10-20 12:22:05', '0'),
(124, 58, 1, 65.00, '2024-10-20 12:22:05', '0'),
(125, 57, 1, 12.00, '2024-10-20 12:22:05', '0'),
(126, 75, 1, 25.00, '2024-10-20 12:22:05', '0'),
(127, 65, 1, 20.00, '2024-10-20 12:25:14', '0'),
(128, 68, 1, 10.00, '2024-10-20 12:25:14', '0'),
(129, 74, 1, 45.00, '2024-10-20 12:25:14', '0'),
(130, 56, 1, 65.00, '2024-10-20 12:25:14', '0'),
(131, 59, 1, 65.00, '2024-10-20 12:25:14', '0'),
(132, 62, 1, 19.00, '2024-10-20 12:25:14', '0'),
(133, 63, 1, 18.00, '2024-10-20 12:25:14', '0'),
(134, 67, 1, 2.00, '2024-10-20 12:25:14', '0'),
(135, 69, 1, 2.00, '2024-10-20 12:25:14', '0'),
(136, 60, 1, 15.00, '2024-10-20 12:25:14', '0'),
(137, 70, 1, 10.00, '2024-10-20 12:25:14', '0'),
(138, 54, 1, 25.00, '2024-10-20 12:25:14', '0'),
(139, 55, 1, 50.00, '2024-10-20 12:25:14', '0'),
(140, 71, 1, 12.00, '2024-10-20 12:25:14', '0'),
(141, 58, 1, 65.00, '2024-10-20 12:25:14', '0'),
(142, 61, 1, 10.00, '2024-10-20 12:25:14', '0'),
(143, 65, 1, 20.00, '2024-10-20 13:51:48', '0'),
(144, 64, 1, 12.00, '2024-10-20 13:51:48', '0'),
(145, 59, 1, 65.00, '2024-10-20 14:00:14', '0'),
(146, 74, 1, 45.00, '2024-10-20 14:06:10', '0'),
(147, 74, 1, 45.00, '2024-10-20 14:06:23', '0'),
(148, 74, 1, 45.00, '2024-10-20 14:09:00', '0'),
(149, 74, 1, 45.00, '2024-10-20 14:16:58', '0'),
(150, 74, 1, 45.00, '2024-10-20 14:17:08', '0'),
(151, 74, 1, 45.00, '2024-10-20 14:20:54', '0'),
(152, 74, 1, 45.00, '2024-10-20 14:21:04', '0'),
(153, 74, 1, 45.00, '2024-10-20 14:22:56', '0'),
(154, 74, 1, 45.00, '2024-10-20 14:23:36', '0'),
(155, 74, 1, 45.00, '2024-10-20 14:23:45', '0'),
(156, 74, 1, 45.00, '2024-10-20 14:24:11', '0'),
(157, 74, 1, 45.00, '2024-10-20 14:27:57', '0'),
(158, 74, 1, 45.00, '2024-10-20 14:29:38', '0'),
(159, 74, 1, 45.00, '2024-10-20 14:32:37', '0'),
(160, 68, 1, 10.00, '2024-10-20 14:32:37', '0'),
(161, 68, 1, 10.00, '2024-10-20 14:34:47', '0'),
(162, 74, 1, 45.00, '2024-10-20 14:34:47', '0'),
(163, 74, 1, 45.00, '2024-10-20 14:36:43', '0'),
(164, 74, 1, 45.00, '2024-10-20 14:38:38', '0'),
(165, 65, 1, 20.00, '2024-10-20 14:38:47', '0'),
(166, 65, 1, 20.00, '2024-10-20 14:38:56', '0'),
(167, 74, 1, 45.00, '2024-10-20 14:42:36', '0'),
(168, 64, 1, 12.00, '2024-10-20 14:43:39', '0'),
(169, 65, 1, 20.00, '2024-10-20 14:43:39', '0'),
(170, 68, 1, 10.00, '2024-10-20 14:43:39', '0'),
(171, 74, 1, 45.00, '2024-10-20 14:43:39', '0'),
(172, 64, 1, 12.00, '2024-10-20 14:43:39', '0'),
(173, 65, 1, 20.00, '2024-10-20 14:43:39', '0'),
(174, 68, 1, 10.00, '2024-10-20 14:43:39', '0'),
(175, 74, 1, 45.00, '2024-10-20 14:43:39', '0'),
(176, 74, 2, 90.00, '2024-10-20 14:47:52', '0'),
(177, 68, 1, 10.00, '2024-10-20 14:47:52', '0'),
(178, 65, 1, 20.00, '2024-10-20 14:47:52', '0'),
(179, 64, 1, 12.00, '2024-10-20 14:47:52', '0'),
(180, 60, 1, 15.00, '2024-10-20 14:47:52', '0'),
(181, 70, 1, 10.00, '2024-10-20 14:47:52', '0'),
(182, 64, 1, 12.00, '2024-10-20 14:49:25', '0'),
(183, 65, 1, 20.00, '2024-10-20 14:49:25', '0'),
(184, 68, 1, 10.00, '2024-10-20 14:49:25', '0'),
(185, 74, 1, 45.00, '2024-10-20 14:49:25', '0'),
(186, 56, 1, 65.00, '2024-10-20 14:49:25', '0'),
(187, 59, 1, 65.00, '2024-10-20 14:49:25', '0'),
(188, 70, 1, 10.00, '2024-10-20 14:49:25', '0'),
(189, 60, 1, 15.00, '2024-10-20 14:49:25', '0'),
(190, 68, 1, 10.00, '2024-10-20 14:53:04', '0'),
(191, 65, 1, 20.00, '2024-10-20 14:53:04', '0'),
(192, 64, 1, 12.00, '2024-10-20 14:53:04', '0'),
(193, 60, 1, 15.00, '2024-10-20 14:53:04', '0'),
(194, 70, 1, 10.00, '2024-10-20 14:53:04', '0'),
(195, 59, 1, 65.00, '2024-10-20 14:53:04', '0'),
(196, 63, 1, 18.00, '2024-10-20 14:56:11', '0'),
(197, 67, 1, 2.00, '2024-10-20 14:56:11', '0'),
(198, 69, 1, 2.00, '2024-10-20 14:56:11', '0'),
(199, 60, 1, 15.00, '2024-10-20 14:56:11', '0'),
(200, 68, 1, 10.00, '2024-10-20 15:03:35', '0'),
(201, 65, 1, 20.00, '2024-10-20 15:03:35', '0'),
(202, 64, 1, 12.00, '2024-10-20 15:03:35', '0'),
(203, 56, 1, 65.00, '2024-10-20 15:03:35', '0'),
(204, 59, 1, 65.00, '2024-10-20 15:03:35', '0'),
(205, 62, 1, 19.00, '2024-10-20 15:03:35', '0'),
(206, 63, 1, 18.00, '2024-10-20 15:03:35', '0'),
(207, 67, 1, 2.00, '2024-10-20 15:03:35', '0'),
(208, 69, 1, 2.00, '2024-10-20 15:03:35', '0'),
(209, 60, 1, 15.00, '2024-10-20 15:03:35', '0'),
(210, 70, 1, 10.00, '2024-10-20 15:03:35', '0'),
(211, 65, 1, 20.00, '2024-10-20 15:14:58', '0'),
(212, 65, 1, 20.00, '2024-10-20 15:14:58', '0'),
(213, 65, 1, 20.00, '2024-10-20 15:43:09', '0'),
(214, 68, 1, 10.00, '2024-10-20 15:43:09', '0'),
(215, 56, 1, 65.00, '2024-10-20 15:43:09', '0'),
(216, 74, 9, 405.00, '2024-10-20 17:31:58', '0'),
(217, 64, 1, 12.00, '2024-10-20 18:02:37', '0'),
(218, 64, 1, 12.00, '2024-10-20 18:02:45', '0'),
(219, 68, 1, 10.00, '2024-10-20 18:03:30', '0'),
(220, 68, 2, 20.00, '2024-10-20 18:04:15', '0'),
(221, 68, 2, 20.00, '2024-10-20 18:04:21', '0'),
(222, 68, 1, 10.00, '2024-10-20 18:07:57', '0'),
(223, 65, 1, 20.00, '2024-10-20 18:07:57', '0'),
(224, 64, 1, 12.00, '2024-10-20 18:08:21', '0'),
(225, 65, 1, 20.00, '2024-10-20 18:08:21', '0'),
(226, 64, 1, 12.00, '2024-10-20 18:08:34', '0'),
(227, 65, 1, 20.00, '2024-10-20 18:08:34', '0'),
(228, 64, 1, 12.00, '2024-10-20 18:14:39', '0'),
(229, 64, 1, 12.00, '2024-10-20 18:14:58', '0'),
(230, 64, 1, 12.00, '2024-10-20 18:15:25', '0'),
(231, 64, 2, 24.00, '2024-10-20 18:15:58', '0'),
(232, 65, 2, 40.00, '2024-10-20 18:15:58', '0'),
(233, 64, 1, 12.00, '2024-10-20 18:18:19', '0'),
(234, 70, 1, 10.00, '2024-10-20 18:18:31', '0'),
(235, 59, 1, 65.00, '2024-10-20 18:18:31', '0'),
(236, 56, 1, 65.00, '2024-10-20 18:18:31', '0'),
(237, 54, 1, 25.00, '2024-10-20 18:21:16', '0'),
(238, 60, 1, 15.00, '2024-10-20 18:22:59', '0'),
(239, 61, 1, 10.00, '2024-10-20 18:23:38', '0'),
(240, 75, 2, 50.00, '2024-10-20 18:23:38', '0'),
(241, 55, 1, 50.00, '2024-10-20 18:24:26', '0'),
(242, 68, 1, 10.00, '2024-10-20 18:54:49', '0'),
(243, 65, 1, 20.00, '2024-10-20 18:54:49', '0'),
(244, 64, 1, 12.00, '2024-10-20 18:55:08', '0'),
(245, 65, 1, 20.00, '2024-10-20 18:55:08', '0'),
(246, 64, 1, 12.00, '2024-10-20 18:57:12', '0'),
(247, 65, 1, 20.00, '2024-10-20 18:57:12', '0'),
(248, 65, 2, 40.00, '2024-10-20 18:58:22', '0'),
(249, 64, 1, 12.00, '2024-10-20 19:00:26', '0'),
(250, 65, 1, 20.00, '2024-10-20 19:00:26', '0'),
(251, 68, 1, 10.00, '2024-10-20 19:00:26', '0'),
(252, 74, 1, 25.00, '2024-10-20 19:00:26', '0'),
(253, 64, 1, 12.00, '2024-10-20 19:00:36', '0'),
(254, 65, 1, 20.00, '2024-10-20 19:00:36', '0'),
(255, 68, 1, 10.00, '2024-10-20 19:00:36', '0'),
(256, 63, 1, 18.00, '2024-10-20 19:00:36', '0'),
(257, 64, 2, 24.00, '2024-10-20 19:05:25', '0'),
(258, 60, 1, 15.00, '2024-10-20 19:05:25', '0'),
(259, 64, 1, 12.00, '2024-10-20 19:05:45', '0'),
(260, 65, 1, 20.00, '2024-10-20 19:05:45', '0'),
(261, 54, 1, 25.00, '2024-10-20 19:08:33', '0'),
(262, 64, 1, 12.00, '2024-10-20 19:18:27', '0'),
(263, 65, 1, 20.00, '2024-10-20 19:18:27', '0'),
(264, 64, 1, 12.00, '2024-10-20 19:18:27', '0'),
(265, 65, 1, 20.00, '2024-10-20 19:18:27', '0'),
(266, 64, 1, 12.00, '2024-10-20 19:19:26', '0'),
(267, 65, 1, 20.00, '2024-10-20 19:19:26', '0'),
(268, 64, 1, 12.00, '2024-10-20 19:19:26', '0'),
(269, 65, 1, 20.00, '2024-10-20 19:19:26', '0'),
(270, 65, 1, 20.00, '2024-10-20 19:19:42', '0'),
(271, 68, 1, 11.00, '2024-10-20 19:19:42', '0'),
(272, 68, 1, 11.00, '2024-10-21 05:26:27', '0'),
(273, 74, 1, 25.00, '2024-10-21 05:26:27', '0'),
(274, 68, 1, 11.00, '2024-10-21 05:31:05', '0'),
(275, 63, 1, 18.00, '2024-10-21 05:31:05', '0'),
(276, 67, 1, 2.00, '2024-10-21 05:31:05', '0'),
(277, 62, 1, 19.00, '2024-10-21 05:31:05', '0'),
(278, 60, 1, 15.00, '2024-10-21 05:34:02', '0'),
(279, 59, 1, 65.00, '2024-10-21 05:34:02', '0'),
(280, 56, 1, 65.00, '2024-10-21 05:34:02', '0'),
(281, 56, 2, 130.00, '2024-10-21 05:51:33', '0'),
(282, 59, 1, 60.00, '2024-10-21 05:51:33', '0'),
(283, 70, 1, 10.00, '2024-10-21 05:51:33', '0'),
(284, 64, 1, 12.00, '2024-10-21 05:51:44', '0'),
(285, 68, 1, 11.00, '2024-10-21 05:51:44', '0'),
(286, 60, 1, 15.00, '2024-10-21 05:52:24', '0'),
(287, 70, 1, 10.00, '2024-10-21 06:33:04', '0'),
(288, 69, 1, 2.00, '2024-10-21 06:33:04', '0'),
(289, 68, 1, 11.00, '2024-10-21 06:33:04', '0'),
(290, 67, 1, 2.00, '2024-10-21 06:33:04', '0'),
(291, 64, 2, 24.00, '2024-10-21 06:33:48', '0'),
(292, 65, 1, 20.00, '2024-10-21 06:33:48', '0'),
(293, 68, 1, 11.00, '2024-10-21 06:42:52', '0'),
(294, 76, 2, 20.00, '2024-10-21 06:42:52', '0'),
(295, 74, 1, 25.00, '2024-10-21 06:42:52', '0'),
(296, 74, 2, 50.00, '2024-10-21 07:18:23', '0'),
(297, 74, 1, 25.00, '2024-10-21 07:18:35', '0'),
(298, 61, 1, 10.00, '2024-10-21 07:24:56', '0'),
(299, 65, 1, 20.00, '2024-10-21 07:24:56', '0'),
(300, 76, 1, 10.00, '2024-10-21 07:24:56', '0'),
(301, 76, 1, 10.00, '2024-10-21 07:28:03', '0'),
(302, 65, 2, 40.00, '2024-10-21 07:50:15', '0'),
(303, 78, 1, 10.00, '2024-10-21 07:50:15', '0'),
(304, 65, 1, 20.00, '2024-10-21 07:51:18', '0'),
(305, 54, 7, 175.00, '2024-10-21 07:56:52', '0'),
(306, 76, 1, 10.00, '2024-10-22 17:11:34', '0'),
(307, 68, 1, 11.00, '2024-10-22 17:11:34', '0'),
(308, 74, 1, 25.00, '2024-10-22 17:11:34', '0'),
(309, 65, 1, 20.00, '2024-10-22 17:11:34', '0'),
(310, 64, 1, 12.00, '2024-10-22 17:11:34', '0'),
(311, 74, 1, 25.00, '2024-10-22 17:41:37', '0'),
(312, 68, 1, 11.00, '2024-10-22 17:41:37', '0'),
(313, 76, 1, 10.00, '2024-10-22 17:41:37', '0'),
(314, 65, 1, 20.00, '2024-10-22 17:41:37', '0'),
(315, 74, 1, 25.00, '2024-10-22 17:44:36', '0'),
(316, 76, 1, 10.00, '2024-10-22 17:44:36', '0'),
(317, 68, 1, 11.00, '2024-10-22 17:44:36', '0'),
(318, 76, 1, 10.00, '2024-10-22 17:45:23', '0'),
(319, 68, 1, 11.00, '2024-10-22 17:45:23', '0'),
(320, 74, 1, 25.00, '2024-10-22 17:45:23', '0'),
(321, 76, 1, 10.00, '2024-10-22 17:46:55', '0'),
(322, 68, 1, 11.00, '2024-10-22 17:46:55', '0'),
(323, 74, 1, 25.00, '2024-10-22 17:46:55', '0'),
(324, 76, 1, 10.00, '2024-10-22 17:51:42', '0'),
(325, 68, 1, 11.00, '2024-10-22 17:51:42', '0'),
(326, 74, 1, 25.00, '2024-10-22 17:51:42', '0'),
(327, 76, 1, 10.00, '2024-10-22 17:54:40', '0'),
(328, 68, 1, 11.00, '2024-10-22 17:54:40', '0'),
(329, 74, 1, 25.00, '2024-10-22 17:54:40', '0'),
(330, 64, 1, 12.00, '2024-10-22 17:55:35', '0'),
(331, 65, 1, 20.00, '2024-10-22 17:55:35', '0'),
(332, 78, 1, 10.00, '2024-10-22 17:55:35', '0'),
(333, 78, 1, 10.00, '2024-10-22 17:56:30', '0'),
(334, 64, 1, 12.00, '2024-10-22 17:56:30', '0'),
(335, 68, 1, 11.00, '2024-10-22 17:56:30', '0'),
(336, 65, 1, 20.00, '2024-10-22 17:57:08', 'TRX-572449'),
(337, 78, 1, 10.00, '2024-10-22 17:57:08', 'TRX-572449'),
(338, 74, 1, 25.00, '2024-10-22 17:57:08', 'TRX-572449'),
(339, 81, 4, 48.00, '2024-10-22 18:09:06', 'HCC-828545'),
(340, 65, 1, 20.00, '2024-10-22 18:09:06', 'HCC-828545'),
(341, 64, 1, 12.00, '2024-10-22 18:09:06', 'HCC-828545'),
(342, 76, 1, 10.00, '2024-10-22 18:09:06', 'HCC-828545'),
(343, 68, 1, 11.00, '2024-10-22 18:09:06', 'HCC-828545'),
(344, 78, 1, 10.00, '2024-10-22 18:12:17', 'HCC-472886'),
(345, 64, 1, 12.00, '2024-10-22 18:12:17', 'HCC-472886'),
(346, 65, 1, 20.00, '2024-10-22 18:12:17', 'HCC-472886'),
(347, 76, 1, 10.00, '2024-10-22 18:12:17', 'HCC-472886'),
(348, 68, 1, 11.00, '2024-10-22 18:12:17', 'HCC-472886'),
(349, 74, 1, 25.00, '2024-10-22 18:12:17', 'HCC-472886'),
(350, 56, 1, 65.00, '2024-10-22 18:12:17', 'HCC-472886'),
(351, 59, 1, 60.00, '2024-10-22 18:12:17', 'HCC-472886'),
(352, 70, 1, 10.00, '2024-10-22 18:12:17', 'HCC-472886'),
(353, 81, 1, 12.00, '2024-10-22 18:12:17', 'HCC-472886'),
(354, 60, 1, 15.00, '2024-10-22 18:12:17', 'HCC-472886'),
(355, 69, 1, 2.00, '2024-10-22 18:12:17', 'HCC-472886'),
(356, 76, 1, 10.00, '2024-10-22 18:16:27', 'HCC-420834'),
(357, 68, 1, 11.00, '2024-10-22 18:16:27', 'HCC-420834'),
(358, 64, 1, 12.00, '2024-10-22 18:16:27', 'HCC-420834'),
(359, 60, 1, 15.00, '2024-10-22 18:16:27', 'HCC-420834'),
(360, 69, 1, 2.00, '2024-10-22 18:16:27', 'HCC-420834'),
(361, 64, 1, 12.00, '2024-10-22 18:24:37', 'HCC-266228'),
(362, 65, 1, 20.00, '2024-10-22 18:24:37', 'HCC-266228'),
(363, 76, 1, 10.00, '2024-10-22 18:25:19', 'HCC-663295'),
(364, 68, 1, 11.00, '2024-10-22 18:25:19', 'HCC-663295'),
(365, 68, 1, 11.00, '2024-10-22 18:26:07', 'HCC-818572'),
(366, 76, 1, 10.00, '2024-10-22 18:26:07', 'HCC-818572'),
(367, 76, 1, 10.00, '2024-10-22 18:26:55', 'HCC-706499'),
(368, 68, 1, 11.00, '2024-10-22 18:26:55', 'HCC-706499'),
(369, 68, 1, 11.00, '2024-10-22 18:28:46', 'HCC-705001'),
(370, 74, 1, 25.00, '2024-10-22 18:28:46', 'HCC-705001'),
(371, 76, 1, 10.00, '2024-10-22 18:29:13', 'HCC-269347'),
(372, 68, 1, 11.00, '2024-10-22 18:29:13', 'HCC-269347'),
(373, 76, 1, 10.00, '2024-10-22 18:34:49', 'HCC-759449'),
(374, 76, 1, 10.00, '2024-10-22 18:36:33', 'HCC-043561'),
(375, 76, 1, 10.00, '2024-10-22 18:37:03', 'HCC-348522'),
(376, 68, 1, 11.00, '2024-10-23 00:40:17', 'HCC-500290'),
(377, 74, 1, 25.00, '2024-10-23 00:41:37', 'HCC-047265'),
(378, 68, 1, 11.00, '2024-10-23 00:41:37', 'HCC-047265'),
(379, 76, 1, 10.00, '2024-10-23 00:41:37', 'HCC-047265'),
(380, 74, 1, 25.00, '2024-10-23 00:48:40', 'HCC-928445'),
(381, 68, 1, 11.00, '2024-10-23 00:48:40', 'HCC-928445'),
(382, 76, 1, 10.00, '2024-10-23 00:48:40', 'HCC-928445'),
(383, 64, 1, 12.00, '2024-10-23 00:48:40', 'HCC-928445'),
(384, 68, 1, 11.00, '2024-10-23 04:54:07', 'HCC-892130'),
(385, 65, 1, 20.00, '2024-10-23 04:54:07', 'HCC-892130'),
(386, 78, 1, 10.00, '2024-10-23 04:54:13', 'HCC-126232'),
(387, 64, 1, 12.00, '2024-10-23 04:54:13', 'HCC-126232'),
(388, 74, 1, 25.00, '2024-10-23 04:58:45', 'HCC-448661'),
(389, 68, 1, 11.00, '2024-10-23 04:58:45', 'HCC-448661'),
(390, 76, 1, 10.00, '2024-10-23 05:18:32', 'HCC-113001'),
(391, 65, 1, 20.00, '2024-10-23 05:18:32', 'HCC-113001'),
(392, 65, 1, 20.00, '2024-10-23 05:19:05', 'HCC-796229'),
(393, 76, 1, 10.00, '2024-10-23 05:19:05', 'HCC-796229'),
(394, 68, 1, 11.00, '2024-10-23 05:19:05', 'HCC-796229'),
(395, 65, 1, 20.00, '2024-10-23 05:19:05', 'HCC-673935'),
(396, 76, 1, 10.00, '2024-10-23 05:19:05', 'HCC-673935'),
(397, 68, 1, 11.00, '2024-10-23 05:19:05', 'HCC-673935'),
(398, 78, 1, 10.00, '2024-10-23 05:19:20', 'HCC-291051'),
(399, 56, 1, 65.00, '2024-10-23 05:19:20', 'HCC-291051'),
(400, 59, 1, 60.00, '2024-10-23 05:19:20', 'HCC-291051'),
(401, 68, 1, 11.00, '2024-10-23 05:21:08', 'HCC-299056'),
(402, 76, 1, 10.00, '2024-10-23 05:21:08', 'HCC-299056'),
(403, 65, 1, 20.00, '2024-10-23 05:21:08', 'HCC-299056'),
(404, 56, 1, 65.00, '2024-10-23 05:22:09', 'HCC-335228'),
(405, 59, 1, 60.00, '2024-10-23 05:22:09', 'HCC-335228'),
(406, 60, 1, 15.00, '2024-10-23 05:22:09', 'HCC-335228'),
(407, 69, 1, 2.00, '2024-10-23 05:22:09', 'HCC-335228');

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
-- Table structure for table `transactionslnd`
--

CREATE TABLE `transactionslnd` (
  `id` int(11) NOT NULL,
  `rfid_code` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `transaction_type` enum('load','deduct') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactionslnd`
--

INSERT INTO `transactionslnd` (`id`, `rfid_code`, `user_name`, `transaction_type`, `amount`, `transaction_date`) VALUES
(1, '0013442974', 'Test Test', 'load', 50.00, '2024-10-22 19:57:37'),
(2, '0013442974', 'Test Test', 'deduct', 50.00, '2024-10-22 19:58:40'),
(3, '0013442974', 'Test Test', 'load', 50.00, '2024-10-22 21:22:52'),
(4, '0013442974', 'Test Test', 'deduct', 5000.00, '2024-10-22 21:23:05'),
(5, '0012258965', 'Test Test', 'load', 100.00, '2024-10-22 21:24:02'),
(6, '0012258965', 'Test Test', 'deduct', 1000.00, '2024-10-22 21:24:15');

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
  `user_type` enum('user','cstaff','cmanager','cashier','superadmin') NOT NULL,
  `is_activated` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `student_id`, `first_name`, `middle_name`, `last_name`, `email`, `rfid_code`, `password`, `balance`, `user_type`, `is_activated`) VALUES
(7, '46182021', 'Grant Mikhail', 'Cayaba', 'Dela Cruz', 'grantmikhail@gmail.com', '0012245507', '$2y$10$.VxmdHAd2G0XI0Vvfu918.A//FxmndCHnlQzgYmYpj1aUdDBsYYSm', 350.00, 'user', 1),
(8, '5042017', 'Abraham', 'Ducha', 'Flores', 'abrahamflores@gmail.com', '0011793621', '$2y$10$U2udgWJ7wdvwyjPKmhQAF.jx4zvNInpAeU013M1Rtzl5dJkEuxZxW', 500.00, 'user', 1),
(9, '0000', 'Super', 'Admin', 'To', 'superadmin@gmail.com', 'sadmin', '$2y$10$grGkQDeAQUraCd2i.sl1ue4jhtUw1KS31pCa87ke01kBlQ4RaN0Qa', 0.00, 'superadmin', 1),
(10, '0001', 'Canteen', 'Staff', 'To', 'cstaff@gmail.com', 'cstaff', '$2y$10$EgXp3VEGYZtFT96d0IHwQOLSf52nTZXypqFoX46YnLLfcplkuk/qO', 0.00, 'cstaff', 1),
(11, '0002', 'Cashier', 'Po', 'To', 'cashierhcc@gmail.com', 'cashierhcc', '$2y$10$Tbr/vQ3s2gZK5vVgdTXHF.UxFwxdGga/FaVzvQZiKx1If1b73.oCy', 0.00, 'cashier', 1),
(12, '0003', 'Canteen', 'Manager', 'To', 'cmanager@gmail.com', 'cmanager', '$2y$10$2DeXlrLHbHG4lJQQPbndu.rTiAP23qF.oXjOXicSEPekEv6I.DQ92', 0.00, 'cmanager', 1),
(13, '52651', 'Test', 'Test', 'Test', 'test@gmail.com', '0012258965', '$2y$10$BltPcVhKIBkdrHMvqw3wXOXV0aYIyfG1UooT0c1q0WovWjYFy3JSm', 673.00, 'user', 0),
(14, '52552021', 'James Andrew', 'Adriano', 'Beley', 'jemusubeley@gmail.com', '0013389623', '$2y$10$nGfOXORF82tzXRGXu9QtAeNho.WKLAXF/O1Ry2MLpKIf1JRSLWYsa', 825.00, 'user', 1),
(16, '52542021', 'Test', 'Test', 'Test', 'test123@gmail.com', '0013442974', '$2y$10$LfqEuyTr73LLXKoIKTrBduq3Yyq6AFews1kTakaTPBGs0zJZIA7Yq', 475.00, 'user', 0);

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
  ADD KEY `product_id` (`product_id`),
  ADD KEY `transaction_number` (`transaction_number`);

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
-- Indexes for table `rfid_history`
--
ALTER TABLE `rfid_history`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactionslnd`
--
ALTER TABLE `transactionslnd`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `e_receipt_details`
--
ALTER TABLE `e_receipt_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rfid_history`
--
ALTER TABLE `rfid_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=408;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactionslnd`
--
ALTER TABLE `transactionslnd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  ADD CONSTRAINT `e_receipt_details_ibfk_1` FOREIGN KEY (`e_receipt_id`) REFERENCES `e_receipts` (`id`),
  ADD CONSTRAINT `e_receipt_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `receipts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `rfid_history`
--
ALTER TABLE `rfid_history`
  ADD CONSTRAINT `rfid_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

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
