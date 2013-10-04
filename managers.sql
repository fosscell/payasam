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
-- Table structure for table `managers`
--

CREATE TABLE IF NOT EXISTS `managers` (
  `eventcode` varchar(10) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(20) NOT NULL,
  `validate` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`eventcode`, `username`, `password`, `validate`) VALUES
('RBK', 'cbkjktj', 'tudimseude', 0),
('TQZ', 'ckjinscnclmuoat', 'vavhxa32', 0),
('ABX', 'cmcllivhkn', 'c10j48', 0),
('IDP', 'cncnfm', 'vAVHXA32', 0),
('ALQ', 'cncnvhw', 'Cncnfhwtvap', 0),
('TPR', 'cncnvhwrm', 'vrcnupqrvet', 0),
('AVR', 'cnkrwdj', 'cpru8', 0),
('PLD', 'crehk', 'crehk', 0),
('BKZ', 'csjmct', 'zlt8202rgcvoqr', 1),
('FFA', 'csjwkn', 'dlkt|', 1),
('GSM', 'csyivhc', 'leus6976', 0),
('ATG', 'cuvoircpj', 'yhgens', 0),
('ATQ', 'cuvosukz', 'yhgens', 0),
('BPT', 'dartksv', '423', 0),
('BLP', 'dlwerrknv', 'DlweRrknv14', 0),
('NFS', 'ehkrcg', 'dlkt|', 0),
('CGE', 'eokliupeo', 'EOTUUCCTG', 0),
('CCD', 'eopcgpv', 'eopcgpv', 0),
('CON', 'eopttaeertkop', 'dlchdlchdlch', 0),
('CTT', 'ergave', 'crehk', 0),
('ERC', 'grccgr', 'teeate', 0),
('ISR', 'gxja', 'gxj1', 0),
('LPS', 'gxjb', 'gxj2', 0),
('DRD', 'gxjc', 'gxj3', 0),
('NPC', 'gxjd', 'gxj4', 0),
('BLS', 'gxje', 'gxj5', 0),
('SUS', 'gxjf', 'gxj6', 0),
('IQB', 'gxjg', 'gxj7', 0),
('CAS', 'gxjh', 'gxj8', 0),
('FSS', 'housoeobgr', 'hous', 1),
('FLT', 'hunlvhtovtne', 'yhgens', 0),
('AQM', 'ieqrie', 'rausyotd', 0),
('HND', 'japduop', 'yhgens', 0),
('IBM', 'kbo', '{a|at', 1),
('ASA', 'kdra', 'kdra', 0),
('JIG', 'liiycsc', 'liiycsc', 0),
('INT', 'liuhpu', 'vavhxa', 0),
('-ml', 'ml_payasam', 'ml_payasam', 1),
('DSC', 'mrksjnc', 'mrksjnc', 0),
('RNS', 'neea', 'nee1', 0),
('ARS', 'neeb', 'nee2', 0),
('GMN', 'nevc', 'nevc', 0),
('ASP', 'nevd', 'nevd', 0),
('CSS', 'neve', 'neve', 0),
('GCS', 'nevf', 'nevf', 0),
('TCS', 'nevg', 'nevg', 0),
('BFG', 'nilip', 'foqmnotd', 1),
('LIQ', 'noiiseo', 'elwboavh', 0),
('CLC', 'oafhw', 'oafhw', 0),
('MCN', 'oepop', 'swgqyeswg', 0),
('CHE', 'oifhwnoojap', 'ehgmkccl', 0),
('KKP', 'os', 'mofet', 0),
('MEN', 'paxapegtj', 'qhotctuav', 0),
('-pr', 'payasam', 'payasam', 1),
('PTS', 'pifhgeuhm', 'rutevrkcu', 0),
('TTS', 'pitmcl', 'kdgave', 0),
('RAN', 'piva', 'piv1', 0),
('SUC', 'pivb', 'pivb', 1),
('ACR', 'pivc', 'pivc', 0),
('AGM', 'pivd', 'pivd', 1),
('BNB', 'pivhknmmctjeys', 'vavhxa', 0),
('TOQ', 'qnnipesukz', 'GQEESC325', 1),
('INF', 'rrcjktj', 'Rataxut', 0),
('KSC', 'rrcvcr', 'vavhxa32', 0),
('INC', 'topyoer', 'muvtqou0310', 0),
('LOM', 'tuvhxim', '32547', 0),
('DIR', 'uapdgerkcnfrggwlc', 'mapdteiuna', 0),
('KKT', 'uhcrctj', 'kmrottcnv163', 0),
('SGM', 'uiincloagsvrq', 'vavhxa32', 0),
('SOZ', 'uoeiqbkz|', 'vavhxauoeiq', 1),
('TLO', 'uoqrcj', 'rausyotd', 0),
('STD', 'utcaf', 'eixin', 0),
('INQ', 'utgvknyinsqn', 'ehtiuwkn39122', 0),
('TOW', 'uufex', 'huek{ow', 0),
('MMS', 'uuratnc', 'oowsg', 0),
('CHP', 'uwcrqor', 'Lak Ioxipdc', 0),
('-cl', 'veut', 'veut', 1),
('ERN', 'vhcrwn', 'taehglx', 0),
('TST', 'vovansvaviqn', 'eixin', 0),
('TWT', 'vwgev', 'veut325', 0),
('TYC', 'vyeoqn', 'vyeoqnvavhxa32', 0),
('ARC', 'wdcy', 'dlkt|', 0),
('BLT', 'wdcydhcrvic', 'dlkt|', 0),
('VIS', 'xiutc', 'yhgens', 0),
('DWA', 'yrma', 'lqj510', 0),
('RVT', 'yrmb', 'lqj510', 0),
('CCV', 'yrmc', 'lqj510', 0),
('AUT', 'yrmd', 'lqj510', 0),
('HAK', 'yrme', 'lqj510', 0),
('MSP', 'yrmf', 'lqj510', 0),
('YNG', '{owniepgknget', 'nonrqfn', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
