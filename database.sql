-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 18, 2021 at 08:00 AM
-- Server version: 8.0.25
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `amount` float DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `count` int DEFAULT NULL,
  `log_date` date DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`id`, `name`, `email`, `address`, `description`, `amount`, `balance`, `count`, `log_date`, `log_time`, `status`) VALUES
(1, 'Test 1', 'test1@test.com', 'Test 1 Address', 'Test 1 Description', 100, '1200.00', 100, '2021-06-14', '2021-06-14 04:22:58', 1),
(2, 'Test 2', 'test2@test.com', 'Test 2 Address', 'Test 2 Description', 100, '1200.00', 100, '2021-06-14', '2021-06-14 04:22:58', 1),
(3, 'Test 3', 'test3@test.com', 'Test 3 Address', 'Test 3 Description', 100, '1200.00', 100, '2021-06-14', '2021-06-14 04:22:58', 1),
(4, 'Test 4', 'test4@test.com', 'Test 4 Address', 'Test 4 Description', 100, '1200.00', 100, '2021-06-14', '2021-06-14 04:22:58', 1),
(5, 'Test 5', 'test5@test.com', 'Test 5 Address', 'Test 5 Description', 100, '1200.00', 100, '2021-06-14', '2021-06-14 04:22:58', 1),
(6, 'Test 6', 'test6@test.com', 'Test 6 Address', 'Test 6 Description', 100, '1200.00', 100, '2021-06-14', '2021-06-14 04:22:58', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
