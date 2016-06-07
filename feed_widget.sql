-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2016 at 11:23 PM
-- Server version: 10.0.23-MariaDB
-- PHP Version: 5.6.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `feed_widget`
--

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

CREATE TABLE `feeds` (
  `feed_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique id for the rss feed',
  `feed_url` text NOT NULL COMMENT 'Absolute url path to the rss feed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Used to store rss feeds for the CRON job';

--
-- Dumping data for table `feeds`
--

INSERT INTO `feeds` (`feed_id`, `feed_url`) VALUES
(7, 'http://www.homebrewtalk.com/feed/'),
(8, 'http://www.brewersfriend.com/blog/feed/');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`feed_id`),
  ADD UNIQUE KEY `feed_id` (`feed_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `feed_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique id for the rss feed', AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
