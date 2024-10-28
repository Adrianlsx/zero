-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 07:41 PM
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
-- Database: `zero`
--

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `postID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `postID`, `userID`) VALUES
(1, 27, 2),
(2, 27, 2),
(3, 27, 2),
(4, 27, 2),
(5, 33, 1),
(6, 33, 1),
(7, 29, 1),
(8, 29, 1),
(9, 29, 1),
(10, 29, 1),
(11, 29, 1),
(12, 29, 1),
(13, 29, 1),
(14, 29, 1),
(15, 29, 1),
(16, 29, 1),
(17, 29, 1),
(18, 29, 1),
(19, 29, 1),
(20, 29, 1),
(21, 29, 1),
(22, 29, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `original_post_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `image_path`, `original_post_id`, `created_at`) VALUES
(1, 2, 'test', NULL, NULL, '2024-10-26 21:13:33'),
(2, 2, '', 'uploads/671d5b8362bfb.jpg', NULL, '2024-10-26 21:13:39'),
(3, 2, '', NULL, NULL, '2024-10-26 21:13:42'),
(4, 2, '', NULL, NULL, '2024-10-26 21:18:40'),
(5, 2, '', NULL, NULL, '2024-10-26 21:21:21'),
(6, 2, '', NULL, NULL, '2024-10-26 21:23:27'),
(7, 2, '', NULL, NULL, '2024-10-26 21:23:29'),
(8, 2, 'PUTANGINA', NULL, NULL, '2024-10-26 21:26:35'),
(9, 2, 'SINO KA NGA BA?', NULL, NULL, '2024-10-26 21:26:47'),
(10, 2, '', NULL, NULL, '2024-10-26 21:27:04'),
(11, 2, '', NULL, NULL, '2024-10-26 21:29:12'),
(12, 2, 'Reposted from lucario: SINO KA NGA BA?', NULL, NULL, '2024-10-26 21:33:42'),
(13, 2, 'Reposted from lucario: ', 'uploads/671d5b8362bfb.jpg', NULL, '2024-10-26 21:33:52'),
(14, 2, 'Reposted from lucario: Reposted from lucario: ', 'uploads/671d5b8362bfb.jpg', NULL, '2024-10-26 21:42:10'),
(15, 1, 'Reposted from lucario: Reposted from lucario: ', 'uploads/671d5b8362bfb.jpg', NULL, '2024-10-26 21:49:43'),
(16, 1, 'Reposted from lucario: ', 'uploads/671d5b8362bfb.jpg', NULL, '2024-10-26 21:49:51'),
(17, 2, 'Reposted from abdul: Reposted from lucario: ', 'uploads/671d5b8362bfb.jpg', NULL, '2024-10-27 01:07:46'),
(18, 2, '', 'uploads/671d957b170a6.jpg', NULL, '2024-10-27 01:20:59'),
(19, 2, '', 'uploads/671d983c0b191.jpg', NULL, '2024-10-27 01:32:44'),
(20, 2, 'Reposted from lucario: PUTANGINA', NULL, NULL, '2024-10-27 01:53:07'),
(21, 2, 'Reposted from lucario: ', 'uploads/671d983c0b191.jpg', NULL, '2024-10-27 02:34:34'),
(22, 2, '', NULL, NULL, '2024-10-27 02:37:37'),
(23, 2, '', NULL, NULL, '2024-10-27 02:37:38'),
(24, 2, '', NULL, NULL, '2024-10-27 02:37:38'),
(25, 2, '', NULL, NULL, '2024-10-27 02:37:39'),
(26, 2, 'Reposted from lucario: ', NULL, NULL, '2024-10-27 02:40:11'),
(27, 2, 'Reposted from lucario: Reposted from lucario: ', 'uploads/671d983c0b191.jpg', NULL, '2024-10-27 02:40:14'),
(28, 2, 'Reposted from lucario: ', NULL, NULL, '2024-10-27 02:42:56'),
(29, 2, 'Reposted from lucario: Reposted from lucario: Reposted from lucario: ', 'uploads/671d983c0b191.jpg', NULL, '2024-10-27 02:49:59'),
(30, 2, 'Reposted from lucario: ', 'uploads/671d983c0b191.jpg', NULL, '2024-10-27 07:15:45'),
(31, 2, '', 'uploads/671deca526eb0.png', NULL, '2024-10-27 07:32:53'),
(32, 2, 'Reposted from lucario: SINO KA NGA BA?', NULL, NULL, '2024-10-27 07:59:22'),
(33, 2, 'Reposted from lucario: SINO KA NGA BA?', NULL, NULL, '2024-10-27 07:59:26'),
(34, 3, 'Reposted from lucario: ', 'uploads/671d983c0b191.jpg', NULL, '2024-10-27 21:56:58'),
(35, 1, 'Reposted from lucario: SINO KA NGA BA?', NULL, NULL, '2024-10-28 03:33:22');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `bio`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 1, 'This is my bio!', 'uploads/profile1.jpg', '2024-10-27 08:33:01', '2024-10-27 08:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `zero_users`
--

CREATE TABLE `zero_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pin` varchar(6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zero_users`
--

INSERT INTO `zero_users` (`id`, `username`, `password`, `pin`, `created_at`, `updated_at`) VALUES
(1, 'abdul', '$2y$10$dnCrYHTP7Rxiq7DPE0MxLObRqIv5jNaTmIpp7lujMnX9TMG..FAEm', '9999', '2024-10-26 14:14:30', '2024-10-26 14:33:42'),
(2, 'lucario', '$2y$10$PHTlIrZhPZX7Nq/dv/6QgulMepU26dDHFBoLRqpT8jdAvVKoZvudG', '6788', '2024-10-26 21:01:32', '2024-10-26 21:01:32'),
(3, 'abduljakol', '$2y$10$K1hhzohBfVlfC7SZpMc9ze9l9vAmfxpHz4s4KGAZfKnXuKgjdXYcK', '0734', '2024-10-27 21:55:59', '2024-10-27 21:55:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postID` (`postID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `original_post_id` (`original_post_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profiles_ibfk_1` (`user_id`);

--
-- Indexes for table `zero_users`
--
ALTER TABLE `zero_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zero_users`
--
ALTER TABLE `zero_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `zero_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `zero_users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`original_post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `zero_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
