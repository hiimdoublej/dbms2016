-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 30, 2016 at 03:14 PM
-- Server version: 5.7.12
-- PHP Version: 7.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbtest`
--
CREATE DATABASE IF NOT EXISTS `dbtest` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dbtest`;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(5) NOT NULL,
  `uid` int(5) NOT NULL,
  `msg` text,
  `msg_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `uid`, `msg`, `msg_time`) VALUES
(6, 2, '131283012830128932019830921i3129i3012jadjk;lajsdl;ajdf;ladjkfl;asdf', '2007-05-08 12:35:29'),
(7, 1, '20160528 1339', '2016-05-28 05:39:42'),
(8, 1, '20160528 1341', '2016-05-28 13:41:52'),
(9, 1, '012345678901234567890123456789', '2016-05-28 14:23:20'),
(10, 1, '00000000001111111111222222222233333333334444444444', '2016-05-28 14:24:30'),
(11, 1, 'å—¨ç¾…', '2016-05-28 14:25:42'),
(12, 1, 'ä¸­æ–‡æ¸¬è©¦', '2016-05-28 14:28:10'),
(23, 3, 'sadasdasd', '2016-05-29 08:48:29'),
(26, 6, 'asdasd', '2016-05-29 20:47:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(5) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(35) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`) VALUES
(1, 'hiimdoublej', '410221009@ems.ndhu.edu.tw', 'ea8903edbc3694d279d16016bfb06eab'),
(2, 'dbj', 'dhec10701p@gmail.com', '7f9035930d839616005a0b59356c66ac'),
(3, 'dhec', 'dhec10701p@hotmail.com', '689bbbaadccc137e8a6659ffad98669e'),
(4, 'aaa', 'aaa@bbb', '08f8e0260c64418510cefb2b06eee5cd'),
(6, 'demo', 'demo@demo.com', '1a1dc91c907325c69271ddf0c944bc72');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
