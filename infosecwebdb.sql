-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-11-23 07:34:00
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `infosecwebdb`
--

-- --------------------------------------------------------

--
-- 資料表結構 `login_log`
--

CREATE TABLE `login_log` (
  `id` int(11) NOT NULL,
  `account` varchar(50) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `result` enum('success','fail') DEFAULT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `login_log`
--

INSERT INTO `login_log` (`id`, `account`, `ip_address`, `login_time`, `result`, `locked`) VALUES
(1, 'admin00', NULL, NULL, NULL, 0),
(2, 'admin00', '::1', '2025-11-23 12:33:03', '', 0),
(3, 'admin00', '::1', '2025-11-23 12:37:38', '', 0),
(4, 'admin00', '::1', '2025-11-23 12:39:24', 'success', 0),
(5, 'admin00', '::1', '2025-11-23 12:42:28', '', 0),
(6, 'admin00', '::1', '2025-11-23 12:42:36', '', 0),
(7, 'admin00', '::1', '2025-11-23 12:42:51', '', 1),
(8, 'admin00', '::1', '2025-11-23 12:43:00', '', 1),
(9, 'admin00', '::1', '2025-11-23 12:43:10', '', 1),
(10, 'admin00', '::1', '2025-11-23 13:03:08', '', 0),
(11, 'admin00', '::1', '2025-11-23 13:45:22', '', 0),
(12, 'admin00', '::1', '2025-11-23 13:45:32', 'success', 0),
(13, 'user2', '::1', '2025-11-23 13:54:02', 'success', 0),
(14, 'user3', '::1', '2025-11-23 14:06:52', 'success', 0),
(15, 'admin00', '::1', '2025-11-23 14:23:09', 'success', 0),
(16, 'admin00', '::1', '2025-11-23 14:24:02', '', 0),
(17, 'admin00', '::1', '2025-11-23 14:24:08', '', 0),
(18, 'admin00', '::1', '2025-11-23 14:24:16', '', 0),
(19, 'admin00', '::1', '2025-11-23 14:24:24', '', 0),
(20, 'admin00', '::1', '2025-11-23 14:25:10', '', 1),
(21, 'admin00', '::1', '2025-11-23 14:25:18', '', 1),
(22, 'admin00', '::1', '2025-11-23 14:26:34', '', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `account` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '443e5d36cbae730e136fefa5a040ffc0ce16003b27107c164c35c712ed2baeea',
  `realname` varchar(100) NOT NULL,
  `init` tinyint(1) DEFAULT 1,
  `password1` varchar(255) NOT NULL DEFAULT '587217c6d850eb6a47cb7fc4b8f37096cba5a91b172813025b6ef0e0cfae9b04',
  `password2` varchar(255) NOT NULL DEFAULT '7050036f21b2510a0b35b1643ce4e0a7137bb4a56c91c67f6c78632558a6a12d'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `account`, `password`, `realname`, `init`, `password1`, `password2`) VALUES
(13, 'user1', 'a292912c88c673b5668ffcfaba661414574856714d46d4d4bf93409678129e9a', 'Karen Lee', 1, '443e5d36cbae730e136fefa5a040ffc0ce16003b27107c164c35c712ed2baeea', ''),
(14, 'admin00', '40c33e947aaf7a8e1d0223a983f8e13f35c7e224d2d3970efc85a6809e367beb', 'admin', 0, 'a292912c88c673b5668ffcfaba661414574856714d46d4d4bf93409678129e9a', '7050036f21b2510a0b35b1643ce4e0a7137bb4a56c91c67f6c78632558a6a12d'),
(15, 'user2', '020141b0381b7818bba0092671dc3524fb1b47cbc5dadec0334e38723906e0e4', 'Kelly Lee', 1, '443e5d36cbae730e136fefa5a040ffc0ce16003b27107c164c35c712ed2baeea', '587217c6d850eb6a47cb7fc4b8f37096cba5a91b172813025b6ef0e0cfae9b04'),
(16, 'user3', '443e5d36cbae730e136fefa5a040ffc0ce16003b27107c164c35c712ed2baeea', 'Kai Lee', 1, '587217c6d850eb6a47cb7fc4b8f37096cba5a91b172813025b6ef0e0cfae9b04', '7050036f21b2510a0b35b1643ce4e0a7137bb4a56c91c67f6c78632558a6a12d');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
