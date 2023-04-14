-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2023 at 01:25 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `440`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `user` varchar(50) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `firstName`, `lastName`, `email`) VALUES
('Duos111', '3697119f1fca51c93a20018d7059a315c1e3d7bfb2cbb418749006866315cddbc25e3ab7d87bc13e5b882e028feebba0d38379b693831fee9263f33cbdff3abd', 'Dua', 'Lipa', 'plzmarryme@gmail.com'),
('John124', '86928c796312ae2d121659b987c093d5150b54bb0a85cfad42c3ebe8a8ec7fe7da5392b0661fb33447c9ec7bfcb2082a96e274b3eced13079a8b6d6988a25a1d', 'Jon', 'Moxley', 'jonmoxley27617@gmail.com'),
('phpistrash', '892b5a17254c0c3dbf118d13141aeb113cb668e6ddb2faf994d44680e9d842c3a18df378a62ced925297a031d5cc361d5b407fd98b8ac788ecc4abc3a9018f32', 'me', 'irl', 'nodebetter@gmail.com'),
('Tethys69', 'd180adac1fdd9f664fd3cfc018c79244d20f586e5be350bd8efbbc744c74e069448f203bb6808f1b590e4215700af3d594ed0277b500fa19d4fdafd6fe558187', 'Unil', 'Kuykendal', 'reviverpplz@gmail.com'),
('thegoat88', '632c06cbf5006e2393b9c2dda5b7a1bc41f95c843cf2c97b54a64396e435c29416f3bb7692f7f6d2b589c95e50aa3d79c47186b7b8979b61a4b7551be0dd9738', 'Lionel', 'Messi', 'goat@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
