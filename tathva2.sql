-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 22, 2012 at 01:21 PM
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
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `ID` int(200) NOT NULL AUTO_INCREMENT,
  `AUTHOR` varchar(50) NOT NULL,
  `CATEGORY` varchar(50) NOT NULL,
  `EVENTCODE` varchar(10) NOT NULL,
  `EVENT` varchar(25) NOT NULL,
  `SHORT_DESC` longtext NOT NULL,
  `DATA` longtext NOT NULL,
  `TAGS` longtext NOT NULL,
  `CONTACTS` longtext NOT NULL,
  `PRIZE` varchar(50) NOT NULL,
  `VALIDATE` int(2) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`ID`, `AUTHOR`, `CATEGORY`, `EVENTCODE`, `EVENT`, `SHORT_DESC`, `DATA`, `TAGS`, `CONTACTS`, `PRIZE`, `VALIDATE`) VALUES
(1, 'test', 'Robotics', 'TST', 'test', 'Carrying huge weights was always a big problem for mankind. From the days of the pyramids of Egypt man has been using different techniques to transport huge weights. The latest addition to this is the use of robots. Weight carrying robots or transporters as they are popularly known, are robots which can lift weights and transport them from one area to another.\r\n', 'Carrying huge weights was always a big problem for mankind. From the days of the pyramids of Egypt man has been using different techniques to transport huge weights. The latest addition to this is the use of robots. Weight carrying robots or transporters as they are popularly known, are robots which can lift weights and transport them from one area to another.\r\n\r\n|||break|||\r\nBlock Specification\r\n||===||\r\nThere will be twelve cylindrical blocks, each having a base diameter of 4 cm and height 8 m, and about 150 gm weight (within 10% error) and will be distributed randomly in the Zone A.\r\nBlocks are made using wood. All twelve blocks will have a metallic semi-circular ring (of non-magnetic material) on the top. The placement of the blocks will be the same for every team. Blocks will be distributed in rows with 15 cm spacing in between(from center to center).\r\n|||break|||\r\nRobot Fabrication\r\n||===||\r\nThere will be twelve cylindrical blocks, each having a base diameter of 4 cm and height 8 m, and about 150 gm weight (within 10% error) and will be distributed randomly in the Zone A.\r\nBlocks are made using wood. All twelve blocks will have a metallic semi-circular ring (of non-magnetic material) on the top. The placement of the blocks will be the same for every team. Blocks will be distributed in rows with 15 cm spacing in between(from center to center).', 'transporter', '||N||test||C||123||E||test||0||||N||random||C||123||E||random||0||', '||1||1000||2||500||3||250||0||', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
