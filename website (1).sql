-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2024 at 06:22 PM
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
-- Database: `website`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_auth`
--

CREATE TABLE `user_auth` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `eye_col` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `insta` varchar(255) DEFAULT NULL,
  `insta_followers` int(11) DEFAULT NULL,
  `snap` varchar(255) DEFAULT NULL,
  `fb` varchar(255) DEFAULT NULL,
  `yt` varchar(255) DEFAULT NULL,
  `linkd` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `photo_album` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_auth`
--

INSERT INTO `user_auth` (`id`, `username`, `email`, `password`, `height`, `weight`, `eye_col`, `note`, `insta`, `insta_followers`, `snap`, `fb`, `yt`, `linkd`, `state`, `photo_album`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'demousername', 'demoemail@gmail.com', '$2y$10$JkLaXdDjOA82cgsOkqUNIu3/fxpdQWRGWkyLbP3QRKUJ4U5GkyDjm', 6.00, 91.00, 'Brown', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Delectus pariatur omnis ad ab ea ipsum eligendi nihil reprehenderit, explicabo mollitia necessitatibus aut? Provident quo natus hic illo debitis illum excepturi vitae earum libero mollitia! Voluptatum obcaecati optio provident tempora deleniti quos culpa soluta repellendus accusamus mollitia hic ipsam sint voluptates accusantium fugiat quaerat dolore labore ea fuga unde tenetur, nisi ducimus. Soluta necessitatibus quod earum, labore nesciunt neque ad culpa numquam non, et eaque officia.\r\n', 'https://www.instagram.com/nirrav_akshat_2005/', 12345, '', '', '', '', 'Maharashtra. India', 'uploads/665ef205887d60.59293874.png,uploads/665ef20588eef2.07858772.png,uploads/665ef205892335.99848960.png,uploads/665ef2058960c0.26256891.png,uploads/665ef205898f25.53007251.png,uploads/665ef23adec121.19334364.png,uploads/665ef23adeee22.95641308.png,uploads/665ef23adf55f8.11990009.png,uploads/665ef23adf8b35.43918505.png,uploads/665ef23adfbbf4.56022334.png', 'dp_pics/p1.png', '2024-06-04 10:45:18', '2024-06-04 10:53:46'),
(2, 'nirravsawla', 'nirravsawlaadobe@gmail.com', '$2y$10$q897RV1MrmwaNFfF3oNT9OpcSwYsnJIi7Y81vtfmlFo.fg2Oj/pPi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dp_pics/Nirrav Sawla.jpg', '2024-07-24 15:39:03', '2024-07-24 15:39:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_auth`
--
ALTER TABLE `user_auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_auth`
--
ALTER TABLE `user_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
