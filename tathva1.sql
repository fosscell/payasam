-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 22, 2012 at 01:17 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

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
-- Table structure for table `managers`
--

CREATE TABLE IF NOT EXISTS `managers` (
  `ID` int(200) NOT NULL,
  `CATEGORY` varchar(25) NOT NULL,
  `EVENT` varchar(30) NOT NULL,
  `EVENTCODE` varchar(10) NOT NULL,
  `NAME` varchar(20) NOT NULL,
  `USERNAME` varchar(25) NOT NULL,
  `PASSWORD` varchar(20) NOT NULL,
  `VALIDATE` int(2) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`ID`, `CATEGORY`, `EVENT`, `EVENTCODE`, `NAME`, `USERNAME`, `PASSWORD`, `VALIDATE`) VALUES
(1, 'Privilege', '', '', 'john p joseph', 'john', 'pass', 1),
(2, 'Robotics', 'test', 'TST', 'test', 'test', 'test', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
