-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 05, 2013 at 01:46 AM
-- Server version: 5.1.69
-- PHP Version: 5.3.6-13ubuntu3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tathva`
--

-- --------------------------------------------------------

--
-- Table structure for table `event_cats`
--

CREATE TABLE IF NOT EXISTS `event_cats` (
  `cat_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `par_cat` tinyint(4) NOT NULL DEFAULT '-1',
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `event_cats`
--

INSERT INTO `event_cats` (`cat_id`, `par_cat`, `name`) VALUES
(1, -1, 'Competitions'),
(2, -1, 'Workshops'),
(3, -1, 'Exhibition'),
(4, -1, 'Highlights'),
(5, 1, 'Envision'),
(6, -1, 'Online'),
(7, 1, 'General'),
(8, 1, 'Robotics'),
(9, 1, 'Blitzkrieg'),
(10, 1, 'Management'),
(11, 1, 'Mechanical'),
(12, 1, 'Electrical'),
(13, 1, 'Electronics'),
(14, 1, 'Computer Science'),
(15, 1, 'Civil'),
(16, 1, 'Chemical'),
(17, 2, 'pre-tathva'),
(18, 2, 'on-tathva'),
(19, -1, 'Lectures'),
(20, -1, 'Nites'),
(21, 1, 'Architecture'),
(22, -1, 'wheels'),
(23, -1, 'IDP');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
