-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2024 at 01:18 AM
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
-- Database: `iskomyuter`
--

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `start` varchar(500) DEFAULT NULL,
  `destination` varchar(500) DEFAULT NULL,
  `transportmode` varchar(50) DEFAULT NULL,
  `distance` decimal(12,2) DEFAULT NULL,
  `fare` decimal(12,2) DEFAULT NULL,
  `duration` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `userid`, `start`, `destination`, `transportmode`, `distance`, `fare`, `duration`, `created_at`) VALUES
(13, 0, 'Manila', 'Pasay', 'driving', 9.96, 103.57, '29 mins', '2024-02-13 17:54:40'),
(15, 1, 'Manila', 'Pasay', 'driving', 9.96, 103.57, '29 mins', '2024-02-13 18:17:58'),
(16, 1, 'Manila', 'Pasay', 'all', 8.91, 92.64, '27 mins', '2024-02-13 23:51:24'),
(17, 1, 'Manila', 'Pasay', 'driving', 9.96, 103.57, '29 mins', '2024-02-13 23:55:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
