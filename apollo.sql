-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2016 at 04:40 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `apollo`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `target_group_comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_hidden` tinyint(1) NOT NULL,
  `target_group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B5F1AFE59E6B1585` (`organisation_id`),
  KEY `IDX_B5F1AFE524FF092E` (`target_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `organisation_id`, `name`, `start_date`, `end_date`, `target_group_comment`, `is_hidden`, `target_group_id`) VALUES
(1, 1, 'Default Activity', '2016-03-10 00:00:00', '2016-03-17 00:00:00', 'Some description', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `_int` int(11) NOT NULL,
  `_varchar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `_date_time` datetime NOT NULL,
  `_long_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `updated_on` datetime NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ADF3F3634DFD750C` (`record_id`),
  KEY `IDX_ADF3F363443707B0` (`field_id`),
  KEY `IDX_ADF3F363896DBBDE` (`updated_by_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=202 ;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `record_id`, `field_id`, `updated_by_id`, `_int`, `_varchar`, `_date_time`, `_long_text`, `updated_on`, `is_default`) VALUES
(1, 8, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(2, 15, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(3, 8, 5, 1, 0, '123 456 678', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(4, 8, 4, 1, 0, 'test@test.test', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(5, 24, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(6, 24, 5, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(7, 24, 4, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(8, 16, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(9, 16, 5, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(10, 16, 4, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(11, 7, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(12, 10, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(13, 7, 5, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(14, 7, 4, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(15, 29, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(16, 29, 5, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(17, 29, 4, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(18, 6, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(19, 6, 5, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(20, 6, 4, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(21, 25, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(22, 25, 5, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(23, 25, 4, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(24, 19, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(25, 19, 5, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(26, 19, 4, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(27, 9, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(28, 9, 5, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(29, 9, 4, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(30, 14, 2, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(31, 14, 5, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(32, 14, 4, 1, 0, '', '2016-04-27 21:39:11', '', '2016-04-27 21:39:11', 0),
(33, 14, 1, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 0),
(34, 14, 3, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 0),
(35, 14, 6, 1, 0, '', '2016-04-27 21:39:15', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:15', 0),
(36, 14, 7, 1, 0, '', '2016-04-27 21:39:15', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:15', 0),
(37, 14, 8, 1, 0, '', '2016-04-27 21:39:15', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:15', 0),
(38, 14, 9, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(39, 14, 30, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(40, 14, 35, 1, 0, '', '2016-04-27 21:39:15', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:15', 1),
(41, 14, 36, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 0),
(42, 14, 37, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 0),
(43, 14, 39, 1, 0, '', '2016-04-27 21:39:15', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:15', 1),
(44, 14, 40, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(45, 14, 41, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 0),
(46, 14, 42, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(47, 14, 43, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(48, 14, 44, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(49, 14, 46, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(50, 14, 47, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 0),
(51, 14, 48, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 0),
(52, 14, 49, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(53, 14, 50, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(54, 14, 51, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 0),
(55, 14, 52, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(56, 14, 53, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(57, 14, 54, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(58, 14, 55, 1, 0, '', '2016-04-27 21:39:15', '', '2016-04-27 21:39:15', 1),
(59, 21, 2, 1, 0, '', '2016-04-27 21:39:19', '', '2016-04-27 21:39:19', 0),
(60, 22, 2, 1, 0, '', '2016-04-27 21:39:19', '', '2016-04-27 21:39:19', 0),
(61, 21, 5, 1, 0, '', '2016-04-27 21:39:19', '', '2016-04-27 21:39:19', 0),
(62, 21, 4, 1, 0, '', '2016-04-27 21:39:19', '', '2016-04-27 21:39:19', 0),
(63, 8, 1, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 0),
(64, 8, 3, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 0),
(65, 8, 6, 1, 0, '', '2016-04-27 21:39:20', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:20', 0),
(66, 8, 7, 1, 0, '', '2016-04-27 21:39:20', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:20', 0),
(67, 8, 8, 1, 0, '', '2016-04-27 21:39:20', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:20', 0),
(68, 8, 9, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(69, 8, 30, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(70, 8, 35, 1, 0, '', '2016-04-27 21:39:20', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:20', 1),
(71, 8, 36, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 0),
(72, 8, 37, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 0),
(73, 8, 39, 1, 0, '', '2016-04-27 21:39:20', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:20', 1),
(74, 8, 40, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(75, 8, 41, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 0),
(76, 8, 42, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(77, 8, 43, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(78, 8, 44, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(79, 8, 46, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(80, 8, 47, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 0),
(81, 8, 48, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 0),
(82, 8, 49, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(83, 8, 50, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(84, 8, 51, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 0),
(85, 8, 52, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(86, 8, 53, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(87, 8, 54, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(88, 8, 55, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 1),
(89, 15, 1, 1, 0, '', '2016-04-27 21:39:20', '', '2016-04-27 21:39:20', 0),
(90, 26, 2, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(91, 26, 5, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(92, 26, 4, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(93, 28, 2, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(94, 28, 5, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(95, 28, 4, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(96, 18, 2, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(97, 18, 5, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(98, 18, 4, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(99, 17, 2, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(100, 17, 5, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(101, 17, 4, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(102, 1, 2, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(103, 2, 2, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(104, 3, 2, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(105, 187, 2, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(106, 1, 5, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(107, 1, 4, 1, 0, '', '2016-04-27 21:39:36', '', '2016-04-27 21:39:36', 0),
(108, 1, 1, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(109, 1, 3, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(110, 1, 6, 1, 0, '', '2016-04-27 21:39:39', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:39', 0),
(111, 1, 7, 1, 0, '', '2016-04-27 21:39:39', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:39', 0),
(112, 1, 8, 1, 0, '', '2016-04-27 21:39:39', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:39', 0),
(113, 1, 9, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(114, 1, 30, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(115, 1, 35, 1, 0, '', '2016-04-27 21:39:39', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:39', 1),
(116, 1, 36, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(117, 1, 37, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(118, 1, 39, 1, 0, '', '2016-04-27 21:39:39', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:39', 1),
(119, 1, 40, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(120, 1, 41, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(121, 1, 42, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(122, 1, 43, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(123, 1, 44, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(124, 1, 46, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(125, 1, 47, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(126, 1, 48, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(127, 1, 49, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(128, 1, 50, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(129, 1, 51, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(130, 1, 52, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(131, 1, 53, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(132, 1, 54, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(133, 1, 55, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 1),
(134, 2, 1, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(135, 3, 1, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(136, 187, 1, 1, 0, '', '2016-04-27 21:39:39', '', '2016-04-27 21:39:39', 0),
(137, 24, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(138, 16, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(139, 7, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(140, 29, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(141, 6, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(142, 25, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(143, 19, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(144, 9, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(145, 21, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(146, 26, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(147, 28, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(148, 18, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(149, 17, 55, 1, 0, '', '2016-04-27 21:39:44', '', '2016-04-27 21:39:44', 1),
(150, 7, 1, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 0),
(151, 7, 3, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 0),
(152, 7, 6, 1, 0, '', '2016-04-27 21:39:46', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:46', 0),
(153, 7, 7, 1, 0, '', '2016-04-27 21:39:46', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:46', 0),
(154, 7, 8, 1, 0, '', '2016-04-27 21:39:46', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:46', 0),
(155, 7, 9, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(156, 7, 30, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(157, 7, 35, 1, 0, '', '2016-04-27 21:39:46', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:46', 1),
(158, 7, 36, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 0),
(159, 7, 37, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 0),
(160, 7, 39, 1, 0, '', '2016-04-27 21:39:46', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:46', 1),
(161, 7, 40, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(162, 7, 41, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 0),
(163, 7, 42, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(164, 7, 43, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(165, 7, 44, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(166, 7, 46, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(167, 7, 47, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 0),
(168, 7, 48, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 0),
(169, 7, 49, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(170, 7, 50, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(171, 7, 51, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 0),
(172, 7, 52, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(173, 7, 53, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(174, 7, 54, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 1),
(175, 10, 1, 1, 0, '', '2016-04-27 21:39:46', '', '2016-04-27 21:39:46', 0),
(176, 21, 1, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 0),
(177, 21, 3, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 0),
(178, 21, 6, 1, 0, '', '2016-04-27 21:39:49', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:49', 0),
(179, 21, 7, 1, 0, '', '2016-04-27 21:39:49', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:49', 0),
(180, 21, 8, 1, 0, '', '2016-04-27 21:39:49', 'a:1:{i:0;s:0:"";}', '2016-04-27 21:39:49', 0),
(181, 21, 9, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(182, 21, 30, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(183, 21, 35, 1, 0, '', '2016-04-27 21:39:49', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:49', 1),
(184, 21, 36, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 0),
(185, 21, 37, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 0),
(186, 21, 39, 1, 0, '', '2016-04-27 21:39:49', 'a:1:{i:0;i:0;}', '2016-04-27 21:39:49', 1),
(187, 21, 40, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(188, 21, 41, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 0),
(189, 21, 42, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(190, 21, 43, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(191, 21, 44, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(192, 21, 46, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(193, 21, 47, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 0),
(194, 21, 48, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 0),
(195, 21, 49, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(196, 21, 50, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(197, 21, 51, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 0),
(198, 21, 52, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(199, 21, 53, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(200, 21, 54, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 1),
(201, 22, 1, 1, 0, '', '2016-04-27 21:39:49', '', '2016-04-27 21:39:49', 0);

-- --------------------------------------------------------

--
-- Table structure for table `defaults`
--

CREATE TABLE IF NOT EXISTS `defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) DEFAULT NULL,
  `_order` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D815E4E443707B0` (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=235 ;

--
-- Dumping data for table `defaults`
--

INSERT INTO `defaults` (`id`, `field_id`, `_order`, `value`) VALUES
(1, 14, 0, 'Dropdowns first'),
(2, 14, 1, 'Dropdowns second'),
(3, 15, 0, 'Others first'),
(4, 15, 1, 'Others second'),
(5, 16, 0, 'First (1)'),
(6, 16, 1, 'Second (2)'),
(7, 9, 0, 'Unknown'),
(8, 9, 1, 'Some other job'),
(9, 16, 2, 'Third (3)'),
(10, 16, 3, 'Fourth (4)'),
(11, 19, 0, 'Test'),
(12, 19, 1, ''),
(13, 20, 0, 'Default value'),
(14, 9, 2, 'Third job'),
(15, 9, 3, 'Fourth job'),
(16, 22, 0, 'Test 1'),
(17, 22, 1, 'Test 2'),
(18, 22, 2, 'Test 3'),
(20, 26, 0, 'Red'),
(21, 26, 1, 'Green'),
(22, 26, 2, 'Blue'),
(23, 26, 3, 'Gold'),
(25, 27, 0, 'Default value'),
(26, 27, 1, ''),
(27, 27, 2, ''),
(28, 28, 0, 'Default value'),
(29, 28, 1, 'Another default value'),
(30, 28, 2, 'Third default value'),
(31, 29, 0, 'Default value'),
(32, 30, 0, 'Unknown'),
(33, 30, 1, 'Medic'),
(34, 30, 2, 'Dentist'),
(35, 30, 3, 'Nurse'),
(36, 30, 4, 'Midwife'),
(37, 30, 5, 'Other AHP'),
(38, 31, 0, 'Please contact'),
(39, 31, 1, 'Do not contact'),
(40, 32, 0, 'First'),
(41, 32, 1, 'Second 1'),
(42, 32, 2, '3rd'),
(45, 30, 6, 'Not medically qualified'),
(46, 33, 0, 'Unknown'),
(61, 33, 1, 'Cancer Institute'),
(69, 33, 2, 'Division of Biosciences'),
(70, 33, 3, 'Division of Infection and Immunity'),
(71, 33, 4, 'Division of Medicine'),
(72, 33, 5, 'Division of Psychiatry'),
(73, 33, 6, 'Division of Psychology and Language Sciences'),
(74, 33, 7, 'Division of Surgery and Interventional Science'),
(75, 33, 8, 'Ear Institute'),
(76, 33, 9, 'Eastman Dental Institute'),
(77, 33, 10, 'Gatsby Computational Neuroscience Unit'),
(78, 33, 11, 'Institute for Womenâ€™s Health'),
(79, 33, 12, 'Institute of Cardiovascular Science'),
(80, 33, 13, 'Institute of Child Health'),
(81, 33, 14, 'Institute of Clinical Trials and Methodology'),
(82, 33, 15, 'Institute of Cognitive Neuroscience '),
(83, 33, 16, 'Institute of Epidemiology and Health Care'),
(84, 33, 17, 'Institute of Global Health'),
(85, 33, 18, 'Institute of Health Informatics'),
(86, 33, 19, 'Institute of Healthcare Engineering'),
(87, 33, 20, 'Institute of Neurology'),
(88, 33, 21, 'Institute of Ophthalmology '),
(89, 33, 22, 'Laboratory for Molecular Cell Biology'),
(90, 33, 23, 'Medical School'),
(91, 33, 24, 'School of Pharmacy'),
(92, 33, 25, 'Wolfson Institute of Biomedical Research'),
(93, 35, 0, 'Unknown'),
(94, 35, 1, 'Cancer Institute'),
(95, 35, 2, 'Division of Biosciences'),
(96, 35, 3, 'Division of Infection and Immunity'),
(97, 35, 4, 'Division of Medicine'),
(98, 35, 5, 'Division of Psychiatry'),
(99, 35, 6, 'Division of Psychology and Language Sciences'),
(100, 35, 7, 'Division of Surgery and Interventional Science'),
(101, 35, 8, 'Ear Institute'),
(102, 35, 9, 'Eastman Dental Institute'),
(103, 35, 10, 'Gatsby Computational Neuroscience Unit'),
(104, 35, 11, 'Institute of Cardiovascular Science'),
(105, 35, 12, 'Institute of Child Health'),
(106, 35, 13, 'Institute of Clinical Trials and Methodology'),
(107, 35, 14, 'Institute of Cognitive Neuroscience '),
(108, 35, 15, 'Institute of Epidemiology and Health Care'),
(109, 35, 16, 'Institute of Global Health'),
(110, 35, 17, 'Institute of Health Informatics'),
(111, 35, 18, 'Institute of Healthcare Engineering'),
(112, 35, 19, 'Institute of Neurology'),
(113, 35, 20, 'Institute of Ophthalmology '),
(114, 35, 21, 'Laboratory for Molecular Cell Biology'),
(115, 35, 22, 'Medical School'),
(116, 35, 23, 'School of Pharmacy'),
(117, 35, 24, 'Wolfson Institute of Biomedical Research'),
(118, 39, 0, 'Unknown'),
(119, 39, 1, 'Wellcome Trust'),
(120, 39, 2, 'MRC'),
(121, 39, 3, 'EPSRC'),
(122, 39, 4, 'CRUK'),
(123, 39, 5, 'Wolfson'),
(124, 40, 0, 'Unknown'),
(125, 39, 6, 'NIHR'),
(126, 42, 0, 'Unknown'),
(127, 43, 0, 'Unknown'),
(128, 44, 0, 'Unknown'),
(129, 46, 0, 'Unknown'),
(130, 49, 0, 'Not applicable'),
(131, 49, 1, 'UCLH'),
(132, 49, 2, 'GOSH'),
(133, 49, 3, 'Moorfields'),
(135, 50, 0, 'Not applicable'),
(136, 50, 1, 'UCLH'),
(137, 50, 2, 'GOSH'),
(138, 50, 3, 'Moorfields'),
(139, 52, 0, 'Unknown'),
(140, 52, 1, 'PhD'),
(141, 52, 2, 'MDRes'),
(142, 52, 3, 'MSc'),
(143, 52, 4, 'BSc'),
(144, 52, 5, 'No research degree'),
(145, 53, 0, 'Unknown'),
(146, 53, 1, 'Academic Clinical Fellow'),
(147, 53, 2, 'MSc'),
(148, 53, 3, 'PhD/CRTF'),
(149, 53, 4, 'Clinical Lecturer'),
(150, 53, 5, 'Clinician Scientist Fellow/ Intermediate Clinical Fellow'),
(151, 54, 0, 'Unknown'),
(152, 53, 6, 'Faculty position'),
(153, 54, 1, 'Foundation doctor'),
(154, 54, 2, 'Specialty or core trainee'),
(155, 54, 3, 'Academic Clinical Fellow'),
(156, 54, 4, 'MSc'),
(157, 54, 5, 'PhD/CRTF'),
(158, 54, 6, 'Clinical Lecturer'),
(160, 53, 7, 'Left academic research'),
(161, 55, 0, 'Please contact'),
(162, 55, 1, 'Do not contact'),
(163, 43, 1, 'Blood'),
(164, 43, 2, 'Cancer'),
(165, 43, 3, 'Cardiovascular'),
(166, 43, 4, 'Congenital Disorders'),
(167, 43, 5, 'Ear'),
(168, 43, 6, 'Eye'),
(169, 43, 7, 'Infection'),
(170, 43, 8, 'Inflammatory and Immune System'),
(171, 43, 9, 'Injuries and Accidents'),
(172, 43, 10, 'Mental Health'),
(173, 43, 11, 'Metabolic and Endocrine'),
(174, 43, 12, 'Musculoskeletal'),
(175, 43, 13, 'Neurological'),
(176, 43, 14, 'Oral and Gastrointestinal'),
(177, 43, 15, 'Renal and Urogenital'),
(178, 43, 16, 'Reproductive Health and Childbirth'),
(179, 43, 17, 'Respiratory'),
(180, 43, 18, 'Skin'),
(181, 43, 19, 'Stroke'),
(182, 43, 20, 'Generic Health Relevance'),
(183, 43, 21, 'Other'),
(184, 43, 22, 'Not applicable'),
(185, 46, 1, '1.1 Normal biological development and functioning '),
(186, 46, 2, '1.2 Psychological and socioeconomic processes'),
(187, 46, 3, '1.3 Chemical and physical sciences'),
(188, 46, 4, '1.4 Methodologies and measurements '),
(189, 46, 5, '1.5 Resources and infrastructure (underpinning)'),
(190, 46, 6, '2.1 Biological and endogenous factors'),
(191, 46, 7, '2.2 Factors relating to physical environment'),
(192, 46, 8, '2.3 Psychological, social and economic factors'),
(193, 46, 9, '2.4 Surveillance and distribution'),
(194, 46, 10, '2.5 Research design and methodologies (aetiology)'),
(195, 46, 11, '2.6 Resources and infrastructure (aetiology)'),
(196, 46, 12, '3.1 Primary prevention interventions to modify behaviours or promote well-being'),
(197, 46, 13, '3.2 Interventions to alter physical and biological environmental risks  '),
(198, 46, 14, '3.3 Nutrition and chemoprevention'),
(199, 46, 15, '3.4 Vaccines'),
(200, 46, 16, '3.5 Resources and infrastructure (prevention)'),
(201, 46, 17, '4.1 Discovery and preclinical testing of markers and technologies '),
(202, 46, 18, '4.2 Evaluation of markers and technologies '),
(203, 46, 19, '4.3 Influences and impact '),
(204, 46, 20, '4.4 Population screening'),
(205, 46, 21, '4.5 Resources and infrastructure (detection)'),
(206, 46, 22, '5.1 Pharmaceuticals'),
(207, 46, 23, '5.2 Cellular and gene therapies'),
(208, 46, 24, '5.3 Medical devices'),
(209, 46, 25, '5.4 Surgery'),
(210, 46, 26, '5.5 Radiotherapy'),
(211, 46, 27, '5.6 Psychological and behavioural'),
(212, 46, 28, '5.7 Physical'),
(213, 46, 29, '5.8 Complementary '),
(214, 46, 30, '5.9 Resources and infrastructure (development of treatments)'),
(215, 46, 31, '6.1 Pharmaceuticals'),
(216, 46, 32, '6.2 Cellular and gene therapies'),
(217, 46, 33, '6.3 Medical devices'),
(218, 46, 34, '6.4 Surgery'),
(219, 46, 35, '6.5 Radiotherapy'),
(220, 46, 36, '6.6 Psychological and behavioural'),
(221, 46, 37, '6.7 Physical '),
(222, 46, 38, '6.8 Complementary '),
(223, 46, 39, '6.9 Resources and infrastructure (evaluation of treatments)'),
(224, 46, 40, '7.1 Individual care needs '),
(225, 46, 41, '7.2 End of life care'),
(226, 46, 42, '7.3 Management and decision making'),
(227, 46, 43, '7.4 Resources and infrastructure (disease management)'),
(228, 46, 44, '8.1 Organisation and delivery of services'),
(229, 46, 45, '8.2 Health and welfare economics'),
(230, 46, 46, '8.3 Policy, ethics and research governance '),
(231, 46, 47, '8.4 Research design and methodologies '),
(232, 46, 48, '8.5 Resources and infrastructure (health services)'),
(233, 46, 49, 'Not applicable'),
(234, 54, 7, 'Clinician Scientist Fellow/ Intermediate Clinical Fellow');

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE IF NOT EXISTS `fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `has_default` tinyint(1) NOT NULL,
  `allow_other` tinyint(1) NOT NULL,
  `is_multiple` tinyint(1) NOT NULL,
  `is_hidden` tinyint(1) NOT NULL,
  `is_essential` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7EE5E3889E6B1585` (`organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=56 ;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `organisation_id`, `name`, `type`, `has_default`, `allow_other`, `is_multiple`, `is_hidden`, `is_essential`) VALUES
(1, 1, 'Record name', 2, 0, 0, 0, 0, 1),
(2, 1, 'Start date', 3, 0, 0, 0, 0, 1),
(3, 1, 'End date', 3, 0, 0, 0, 0, 1),
(4, 1, 'Email', 2, 0, 0, 0, 0, 1),
(5, 1, 'Phone', 2, 0, 0, 0, 0, 1),
(6, 1, 'Address', 2, 0, 0, 1, 0, 1),
(7, 1, 'Awards', 2, 0, 0, 1, 0, 1),
(8, 1, 'Publications', 2, 0, 0, 1, 0, 1),
(9, 1, 'Job category', 2, 1, 1, 0, 0, 0),
(10, 1, 'A simple integer', 1, 0, 0, 0, 1, 0),
(11, 1, 'A simple string', 2, 0, 0, 0, 1, 0),
(12, 1, 'A simple date', 3, 0, 0, 0, 1, 0),
(13, 1, 'A simple long text', 4, 0, 0, 0, 1, 0),
(14, 1, 'A simple dropdown', 2, 1, 0, 0, 1, 0),
(15, 1, 'Dropdown with other', 2, 1, 1, 0, 1, 0),
(16, 1, 'Multiple dropdown', 2, 1, 0, 1, 1, 0),
(17, 1, 'Multiple text', 2, 0, 0, 1, 1, 0),
(18, 1, 'Field to be deleted', 2, 0, 0, 0, 1, 0),
(19, 1, 'Testing field', 2, 1, 1, 0, 1, 0),
(20, 1, 'Testing field #2', 2, 1, 0, 1, 1, 0),
(21, 1, 'NTN', 2, 0, 0, 0, 1, 0),
(22, 1, 'Test Field', 2, 1, 1, 0, 1, 0),
(23, 1, 'Integer #1', 1, 0, 0, 0, 1, 0),
(24, 1, 'Integer #2', 1, 0, 0, 0, 1, 0),
(25, 1, 'Testing Multiple Choice', 2, 0, 0, 1, 1, 0),
(26, 1, 'Testing Multiple Choice', 2, 1, 0, 1, 1, 0),
(27, 1, 'Test Field 2', 2, 1, 0, 0, 1, 0),
(28, 1, 'Multiple inputs field', 2, 1, 0, 1, 1, 0),
(29, 1, 'A', 2, 1, 0, 1, 1, 0),
(30, 1, 'Clinical qualification', 2, 1, 0, 0, 0, 0),
(31, 1, 'Do not contact', 2, 1, 0, 0, 1, 0),
(32, 1, 'Test Dropdown', 2, 1, 0, 0, 1, 0),
(33, 1, 'Institute', 2, 1, 0, 1, 1, 0),
(34, 1, 'aaa', 1, 0, 0, 0, 1, 0),
(35, 1, 'Institute', 2, 1, 0, 1, 0, 0),
(36, 1, 'Department', 2, 0, 0, 0, 0, 0),
(37, 1, 'Job title', 2, 0, 0, 0, 0, 0),
(38, 1, 'Job category', 1, 0, 0, 0, 1, 0),
(39, 1, 'Funding source', 2, 1, 0, 1, 0, 0),
(40, 1, 'Funding category', 2, 1, 0, 0, 0, 0),
(41, 1, 'Pay band', 2, 0, 0, 0, 0, 0),
(42, 1, 'Clinical specialty', 2, 1, 0, 0, 0, 0),
(43, 1, 'HRCS Health Category', 2, 1, 0, 0, 0, 0),
(44, 1, 'Research discipline', 2, 1, 0, 0, 0, 0),
(45, 1, 'Research discipline', 2, 0, 0, 0, 1, 0),
(46, 1, 'HRCS Research Category', 2, 1, 0, 0, 0, 0),
(47, 1, 'Primary supervisor', 2, 0, 0, 0, 0, 0),
(48, 1, 'Secondary supervisor', 2, 0, 0, 0, 0, 0),
(49, 1, 'NHS Trust', 2, 1, 0, 0, 0, 0),
(50, 1, 'BRC affiliated', 2, 1, 0, 0, 0, 0),
(51, 1, 'Research project title', 4, 0, 0, 0, 0, 0),
(52, 1, 'Highest research degree attained', 2, 1, 0, 0, 0, 0),
(53, 1, 'Next destination', 2, 1, 1, 0, 0, 0),
(54, 1, 'Previous role', 2, 1, 1, 0, 0, 0),
(55, 1, 'Contact request', 2, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `organisations`
--

CREATE TABLE IF NOT EXISTS `organisations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `organisations`
--

INSERT INTO `organisations` (`id`, `name`, `timezone`) VALUES
(1, 'UCL School of Life and Medical Sciences', 'Europe/London');

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) DEFAULT NULL,
  `given_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_hidden` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_28166A269E6B1585` (`organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=172 ;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `organisation_id`, `given_name`, `middle_name`, `last_name`, `is_hidden`) VALUES
(1, 1, 'Timur', 'Almazuly', 'Kuzhagaliyev', 0),
(2, 1, 'Christoph', 'Secret', 'Ulshoefer', 0),
(3, 1, 'Hello', 'Can', 'You', 0),
(4, 1, 'Desislava', 'Dragomirova', 'Koleva', 0),
(5, 1, 'Mary', 'Jane', 'Smith', 0),
(6, 1, 'Test', 'qwe', 'Test', 1),
(7, 1, 'Asuka', 'Sohryuu', 'Langley', 1),
(8, 1, 'More', 'code injection', '<iframe src="//giphy.com/embed/HoffxyN8ghVuw" width="480" height="275" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>', 1),
(9, 1, 'Colby', 'Ellen', 'Benari', 0),
(10, 1, 'Test', '', 'Test', 0),
(11, 1, 'Samantha', '', 'Green', 0),
(12, 1, 'Mary', '', 'Lawrence', 0),
(13, 1, 'Peter', '', 'Last', 0),
(14, 1, 'qw', '', 'qweqw', 1),
(15, 1, 'Clemmie', 'Shea', 'Schoen', 0),
(16, 1, 'Lurline', 'Abbey', 'Stanton', 0),
(17, 1, 'Philip', 'Emmanuel', 'Satterfield', 0),
(18, 1, 'Abby', 'Jerrold', 'Kunze', 1),
(19, 1, 'Russell', 'Emelie', 'Nitzsche', 0),
(20, 1, 'Dianna', 'Jennifer', 'Huel', 0),
(21, 1, 'Colleen', 'Gino', 'DuBuque', 0),
(22, 1, 'Jaqueline', 'Carlotta', 'Moen', 0),
(23, 1, 'Esther', 'Eleanora', 'Homenick', 0),
(24, 1, 'Vernie', 'Elza', 'Medhurst', 0),
(25, 1, 'Rosalia', 'Isom', 'Parisian', 0),
(26, 1, 'Gage', 'Lloyd', 'Wilderman', 0),
(27, 1, 'Claud', 'Kattie', 'Schultz', 0),
(28, 1, 'Jaqueline', 'America', 'Reinger', 0),
(29, 1, 'Marilou', 'Christop', 'Romaguera', 0),
(30, 1, 'Edwin', 'Brooklyn', 'Denesik', 0),
(31, 1, 'Effie', 'Annie', 'Larson', 0),
(32, 1, 'Karine', 'Johnson', 'Mohr', 0),
(33, 1, 'Lola', 'Aleen', 'Welch', 0),
(34, 1, 'Una', 'Amely', 'Koch', 0),
(35, 1, 'Lukas', 'Cleveland', 'Pacocha', 0),
(36, 1, 'Darwin', 'Candace', 'Block', 0),
(37, 1, 'Kyle', 'Florida', 'Schneider', 0),
(38, 1, 'Ila', 'Micaela', 'Barrows', 0),
(39, 1, 'Magdalena', 'Kaya', 'Mills', 0),
(40, 1, 'Violet', 'Ed', 'McLaughlin', 0),
(41, 1, 'Ken', 'Vidal', 'Bartoletti', 0),
(42, 1, 'Mortimer', 'Geovanny', 'Langworth', 0),
(43, 1, 'Jacquelyn', 'Shaina', 'Labadie', 0),
(44, 1, 'Ebony', 'Ubaldo', 'Sanford', 0),
(45, 1, 'Liana', 'Jamel', 'Leffler', 0),
(46, 1, 'Bridget', 'Edythe', 'Bauch', 0),
(47, 1, 'Hank', 'Sigrid', 'Kovacek', 0),
(48, 1, 'Erna', 'Cydney', 'Morissette', 0),
(49, 1, 'Clare', 'Michale', 'Zboncak', 0),
(50, 1, 'Linnie', 'Kip', 'Bergnaum', 0);

-- --------------------------------------------------------

--
-- Table structure for table `personentity_activityentity`
--

CREATE TABLE IF NOT EXISTS `personentity_activityentity` (
  `personentity_id` int(11) NOT NULL,
  `activityentity_id` int(11) NOT NULL,
  PRIMARY KEY (`personentity_id`,`activityentity_id`),
  KEY `IDX_CA025AA13BA39D0A` (`personentity_id`),
  KEY `IDX_CA025AA1FEF8833` (`activityentity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `personentity_activityentity`
--

INSERT INTO `personentity_activityentity` (`personentity_id`, `activityentity_id`) VALUES
(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE IF NOT EXISTS `records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `is_hidden` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9C9D5846217BBB47` (`person_id`),
  KEY `IDX_9C9D5846B03A8386` (`created_by_id`),
  KEY `IDX_9C9D5846896DBBDE` (`updated_by_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=188 ;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `person_id`, `created_by_id`, `updated_by_id`, `created_on`, `updated_on`, `is_hidden`) VALUES
(1, 1, 2, 2, '2016-03-27 17:42:25', '2016-03-27 17:42:25', 0),
(2, 1, 2, 2, '2016-03-27 20:42:38', '2016-03-27 20:42:38', 0),
(3, 1, 2, 2, '2016-03-27 20:45:06', '2016-03-27 20:45:06', 0),
(4, 1, 2, 2, '2016-03-27 20:50:20', '2016-03-27 20:50:20', 1),
(5, 2, 2, 2, '2016-04-01 19:21:12', '2016-04-01 19:21:12', 1),
(6, 3, 2, 2, '2016-04-04 18:59:46', '2016-04-04 18:59:46', 0),
(7, 4, 2, 2, '2016-04-05 13:53:30', '2016-04-05 13:53:30', 0),
(8, 2, 2, 2, '2016-04-05 14:54:14', '2016-04-05 14:54:14', 0),
(9, 5, 2, 2, '2016-04-05 15:31:21', '2016-04-05 15:31:21', 0),
(10, 4, 2, 2, '2016-04-05 17:54:23', '2016-04-05 17:54:23', 0),
(11, 6, 2, 2, '2016-04-11 02:49:28', '2016-04-11 02:49:28', 0),
(12, 7, 2, 2, '2016-04-11 11:30:50', '2016-04-11 11:30:50', 1),
(13, 7, 2, 2, '2016-04-11 11:38:56', '2016-04-11 11:38:56', 1),
(14, 8, 2, 2, '2016-04-11 11:44:30', '2016-04-11 11:44:30', 1),
(15, 2, 2, 2, '2016-04-12 15:27:17', '2016-04-12 15:27:17', 0),
(16, 9, 2, 2, '2016-04-13 11:53:18', '2016-04-13 11:53:18', 0),
(17, 10, 2, 2, '2016-04-14 12:12:11', '2016-04-14 12:12:11', 0),
(18, 11, 2, 2, '2016-04-14 15:08:16', '2016-04-14 15:08:16', 0),
(19, 12, 2, 2, '2016-04-17 21:17:26', '2016-04-17 21:17:26', 0),
(20, 12, 2, 2, '2016-04-17 21:20:01', '2016-04-17 21:20:01', 1),
(21, 13, 2, 2, '2016-04-18 08:38:11', '2016-04-18 08:38:11', 0),
(22, 13, 2, 2, '2016-04-18 08:38:59', '2016-04-18 08:38:59', 0),
(23, 14, 2, 2, '2016-04-18 09:32:39', '2016-04-18 09:32:39', 1),
(24, 15, 1, 1, '2016-04-19 16:59:53', '2016-04-19 16:59:53', 0),
(25, 16, 1, 1, '2016-04-19 17:00:13', '2016-04-19 17:00:13', 0),
(26, 17, 1, 1, '2016-04-19 17:02:16', '2016-04-19 17:02:16', 0),
(27, 18, 1, 1, '2016-04-19 17:05:54', '2016-04-19 17:05:54', 1),
(28, 19, 1, 1, '2016-04-19 17:05:55', '2016-04-19 17:05:55', 0),
(29, 20, 1, 1, '2016-04-19 17:05:55', '2016-04-19 17:05:55', 0),
(187, 1, 2, 2, '2016-04-27 17:14:37', '2016-04-27 17:14:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `targetgroups`
--

CREATE TABLE IF NOT EXISTS `targetgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_hidden` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C6FE4EE09E6B1585` (`organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `targetgroups`
--

INSERT INTO `targetgroups` (`id`, `organisation_id`, `name`, `is_hidden`) VALUES
(1, 1, 'Masters', 0),
(4, 1, 'Bachelors', 0),
(5, 1, 'PhD', 0),
(6, 1, 'Clinical Lecturers', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `registered_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1483A5E99E6B1585` (`organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `organisation_id`, `name`, `email`, `password`, `is_admin`, `registered_on`) VALUES
(1, 1, 'Console', '.', '.', 1, '2016-03-09 15:42:00'),
(2, 1, 'Steve Jobs', 'test@test.com', '$2y$10$2vduVVRAu1QLwrbJY/W36.8cdMs9tnG2vXdOLm3Q69om1exuj81NG', 1, '2016-03-09 15:42:00');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `FK_B5F1AFE524FF092E` FOREIGN KEY (`target_group_id`) REFERENCES `targetgroups` (`id`),
  ADD CONSTRAINT `FK_B5F1AFE59E6B1585` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`);

--
-- Constraints for table `data`
--
ALTER TABLE `data`
  ADD CONSTRAINT `FK_ADF3F363443707B0` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`),
  ADD CONSTRAINT `FK_ADF3F3634DFD750C` FOREIGN KEY (`record_id`) REFERENCES `records` (`id`),
  ADD CONSTRAINT `FK_ADF3F363896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `defaults`
--
ALTER TABLE `defaults`
  ADD CONSTRAINT `FK_D815E4E443707B0` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`);

--
-- Constraints for table `fields`
--
ALTER TABLE `fields`
  ADD CONSTRAINT `FK_7EE5E3889E6B1585` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`);

--
-- Constraints for table `people`
--
ALTER TABLE `people`
  ADD CONSTRAINT `FK_28166A269E6B1585` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`);

--
-- Constraints for table `personentity_activityentity`
--
ALTER TABLE `personentity_activityentity`
  ADD CONSTRAINT `FK_CA025AA13BA39D0A` FOREIGN KEY (`personentity_id`) REFERENCES `people` (`id`),
  ADD CONSTRAINT `FK_CA025AA1FEF8833` FOREIGN KEY (`activityentity_id`) REFERENCES `activities` (`id`);

--
-- Constraints for table `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `FK_9C9D5846217BBB47` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`),
  ADD CONSTRAINT `FK_9C9D5846896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_9C9D5846B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `targetgroups`
--
ALTER TABLE `targetgroups`
  ADD CONSTRAINT `FK_C6FE4EE09E6B1585` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_1483A5E99E6B1585` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
