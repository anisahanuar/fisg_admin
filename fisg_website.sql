-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 12, 2026 at 02:15 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fisg_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `state`, `password_hash`, `role`, `created_at`) VALUES
(1, 'alysya', 'alysya33@gmail.com', '01197654086', '', '$2y$10$ErJtaO7wT5Jybi9lK.1kbuZzHgE8ZugIEQ2Xbf7vaafQoF6AB2CCi', 'user', '2026-10-22 06:13:12'),
(3, 'akasyah', 'akasyah10@gmail.com', '0148867345', '', '$2y$10$AjbUxr0p5HWKVtACUfR7qOdPfhMaT5JyHJt8n3sSAP5RenHGidQca', 'user', '2026-10-22 06:26:36'),
(4, 'farah', 'farah55@gmail.com', '0115786735', '', '$2y$10$tNy9Hm91xc4lq/2q.iOtSurKqdj0mxO2wfWdJesbt.X9l0FfoDPq.', 'admin', '2026-10-28 13:59:30'),
(5, 'amelia', 'amelia01@gmail.com', '01494948702', '', '$2y$10$3hY2MU/85pQtb2WEQEvlmuw8Z8RSRb9VNR5drgLglQGE4CzWeryVu', 'admin', '2026-10-28 14:25:20'),
(6, 'aisyah', 'aisyah20@gmail.com', '01937867637', '', '$2y$10$InuKuX9pcqCSf4Vt9oirSOOrmmn9fmg.f5QHm/WMTN6/tBU3Y4mf.', 'pending_admin', '2026-10-29 03:19:35'),
(7, 'aliya', 'aliya71@gmail.com', '01872630987', '', '$2y$10$i0NWQfcyjqwSLBIfY2tR7O.B7pZ1LM5OiYjHFyASe5F7LyYF3taBu', 'pending_admin', '2026-10-29 03:43:35'),
(8, 'aina', 'aina80@gmail.com', '01678935876', '', '$2y$10$Ayq/LTSM3lJqLnHZR6AJh.SB96F3HkhKHgVKRMxziRAI0MR7rGaKm', 'admin', '2026-10-29 06:15:48'),
(9, 'malia', 'amanda59@gmail.com', '01178952456', 'Terengganu', '$2y$10$u88iMsO/V7yJczq6RSim3.Yi7JMDRrRql2Smw6U0ejqAQTyJENCZO', 'user', '2026-11-10 14:45:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
