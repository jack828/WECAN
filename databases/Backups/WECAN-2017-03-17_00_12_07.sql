-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 17, 2017 at 12:16 AM
-- Server version: 5.7.17-0ubuntu0.16.04.1
-- PHP Version: 5.6.30-1+deb.sury.org~xenial+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `WECAN`
--
CREATE DATABASE IF NOT EXISTS `WECAN` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `WECAN`;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `organisation` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

REPLACE INTO `accounts` (`ID`, `username`, `passwd`, `salt`, `organisation`, `token`) VALUES
(1, 'admin', 'a7c73f575b4be8d67127756e3216809d8c19461355040f410fee44e9a26ece6b', '6fb993f183e28d5cacf101f85c851210ab066640d25a2629c52de10d8ceb8de2', 'caio', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `competitorID` int(11) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `cardStateID` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `competitorID` (`competitorID`),
  KEY `cardStateID` (`cardStateID`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `card`
--

REPLACE INTO `card` (`ID`, `competitorID`, `startDate`, `endDate`, `cardStateID`) VALUES
(1, 1, '2017-07-16', '2017-08-06', 1),
(2, 2, '2017-07-16', '2017-08-06', 1),
(3, 3, '2017-07-16', '2017-08-06', 1),
(4, 4, '2017-07-16', '2017-07-24', 1),
(5, 4, '2017-07-24', '2017-08-01', 3),
(6, 4, '2017-08-01', '2017-08-06', 3),
(7, 5, '2017-07-16', '2017-07-30', 3),
(8, 5, '2017-07-30', '2017-08-06', 1),
(9, 6, '2017-07-16', '2017-08-06', 1),
(10, 7, '2017-07-16', '2017-08-06', 1),
(11, 8, '2017-07-16', '2017-08-02', 2),
(12, 9, '2017-07-16', '2017-07-17', 3),
(13, 9, '2017-07-17', '2017-08-06', 1),
(14, 10, '2017-07-16', '2017-07-25', 2),
(20, 14, '2017-02-21', '2017-02-21', 3),
(21, 14, '2017-02-21', '2017-08-06', 1),
(22, 14, '2017-02-21', '2017-02-21', 3),
(23, 14, '2017-02-21', '2017-02-21', 3),
(24, 14, '2017-02-21', '2017-08-06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cardState`
--

DROP TABLE IF EXISTS `cardState`;
CREATE TABLE IF NOT EXISTS `cardState` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cardState`
--

REPLACE INTO `cardState` (`ID`, `state`) VALUES
(1, 'valid'),
(2, 'expired'),
(3, 'cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `competitor`
--

DROP TABLE IF EXISTS `competitor`;
CREATE TABLE IF NOT EXISTS `competitor` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `titleID` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `teamID` int(11) NOT NULL,
  `authorised` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `titleID` (`titleID`),
  KEY `teamID` (`teamID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `competitor`
--

REPLACE INTO `competitor` (`ID`, `titleID`, `fullName`, `role`, `teamID`, `authorised`) VALUES
(1, 2, 'Toni Duggan', 'Forward', 1, 1),
(2, 2, 'Carly Telford', 'Goalkeeper', 1, 1),
(3, 2, 'Fara Williams', 'Midfielder', 1, 1),
(4, 3, 'Anna Glowacka', 'Physiotherapist', 1, 1),
(5, 1, 'Mark Sampson', 'Manager', 1, 1),
(6, 2, 'Anna Siguel', 'Head Coach', 2, 1),
(7, 2, 'Gemma Fay', 'Captain', 2, 1),
(8, 2, 'Ifeoma Dieke', 'Defender', 2, 1),
(9, 2, 'Zoe Ness', 'Forward', 2, 1),
(10, 3, 'Vito Gelato', 'Doctor', 2, 1),
(11, 1, 'Jorge Vilda', 'Head Coach', 3, 1),
(12, 2, 'Olga Garcia', 'Forward', 3, 1),
(13, 2, 'Sara Serrat', 'Goalkeeper', 3, 1),
(14, 2, 'Andrea Falcón', 'Midfielder', 3, 1),
(15, 3, 'Mary Rose', 'Doctor', 4, 1),
(16, 2, 'Clãudia Neto', 'Captain', 4, 1),
(17, 1, 'Francisco Neto', 'Head Coach', 4, 1),
(18, 2, 'Barbara Bonansea', 'Captain', 5, 1),
(19, 1, 'Fred Bloggs', 'Physiotherapist', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `competitorTitle`
--

DROP TABLE IF EXISTS `competitorTitle`;
CREATE TABLE IF NOT EXISTS `competitorTitle` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `competitorTitle`
--

REPLACE INTO `competitorTitle` (`ID`, `title`) VALUES
(1, 'Mr'),
(2, 'Ms'),
(3, 'Dr');

-- --------------------------------------------------------

--
-- Table structure for table `matchAccess`
--

DROP TABLE IF EXISTS `matchAccess`;
CREATE TABLE IF NOT EXISTS `matchAccess` (
  `ID` int(11) NOT NULL,
  `matchDate` date DEFAULT NULL,
  `venueID` int(11) NOT NULL,
  `team1ID` int(11) NOT NULL,
  `team2ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `venueID` (`venueID`),
  KEY `team1ID` (`team1ID`),
  KEY `team2ID` (`team2ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `matchAccess`
--

REPLACE INTO `matchAccess` (`ID`, `matchDate`, `venueID`, `team1ID`, `team2ID`) VALUES
(7, '2017-07-19', 1, 3, 4),
(8, '2017-07-19', 2, 1, 2),
(15, '2017-07-23', 3, 2, 4),
(16, '2017-07-23', 4, 1, 3),
(23, '2017-07-27', 5, 4, 1),
(24, '2017-07-27', 6, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `teamName` varchar(128) NOT NULL,
  `nfa` varchar(255) NOT NULL,
  `acronym` varchar(8) DEFAULT NULL,
  `nickname` varchar(64) DEFAULT NULL,
  `eliminated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team`
--

REPLACE INTO `team` (`ID`, `teamName`, `nfa`, `acronym`, `nickname`, `eliminated`) VALUES
(1, 'England', 'Football Association', 'FA', 'Lionesses', 0),
(2, 'Scotland', 'Scottish Football Association', 'SFA', ' ', 0),
(3, 'Spain', 'Real Federación Española de Fútbol', 'RFEF', 'Las Soñadoras', 0),
(4, 'Portugal', 'Federaçáo Portuguesa de Futebol', 'FPF', 'A Selecção das Quinas', 0),
(5, 'Italy', 'Federazione Italiana Giuoco Calcio', 'FIGG', 'Azzurre', 0);

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

DROP TABLE IF EXISTS `venue`;
CREATE TABLE IF NOT EXISTS `venue` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `venueName` varchar(255) NOT NULL,
  `stadium` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venue`
--

REPLACE INTO `venue` (`ID`, `venueName`, `stadium`) VALUES
(1, 'Doetinchem', 'De Vijverberg'),
(2, 'Utrecht', 'Galgenwaard'),
(3, 'Rotterdam', 'Sparta Stadion'),
(4, 'Breda', 'Rat Verlegh'),
(5, 'Tilburg', 'Konig Willem II'),
(6, 'Deventer', 'De Adelaarshorst');

-- --------------------------------------------------------

--
-- Table structure for table `venueUsage`
--

DROP TABLE IF EXISTS `venueUsage`;
CREATE TABLE IF NOT EXISTS `venueUsage` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `cardID` int(11) NOT NULL,
  `venueID` int(11) NOT NULL,
  `dateAccessed` date DEFAULT NULL,
  `accessGranted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `cardID` (`cardID`),
  KEY `venueID` (`venueID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venueUsage`
--

REPLACE INTO `venueUsage` (`ID`, `cardID`, `venueID`, `dateAccessed`, `accessGranted`) VALUES
(1, 11, 1, '2017-07-22', 0),
(2, 9, 3, '2017-07-28', 0),
(3, 4, 4, '2017-07-22', 1),
(4, 4, 1, '2017-07-21', 0),
(5, 1, 5, '2017-07-23', 1),
(6, 12, 5, '2017-07-27', 1),
(7, 10, 2, '2017-07-22', 1),
(8, 1, 3, '2017-07-21', 1),
(9, 4, 6, '2017-07-17', 0),
(10, 10, 1, '2017-07-25', 1),
(11, 7, 1, '2017-08-02', 0),
(12, 12, 4, '2017-07-18', 1),
(13, 1, 3, '2017-07-26', 0),
(14, 1, 1, '2017-08-03', 1),
(15, 4, 5, '2017-08-05', 0),
(16, 6, 4, '2017-07-28', 0),
(17, 11, 6, '2017-07-26', 1),
(18, 4, 6, '2017-08-01', 1),
(19, 8, 4, '2017-07-30', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `card_ibfk_1` FOREIGN KEY (`competitorID`) REFERENCES `competitor` (`ID`),
  ADD CONSTRAINT `card_ibfk_2` FOREIGN KEY (`cardStateID`) REFERENCES `cardState` (`ID`);

--
-- Constraints for table `competitor`
--
ALTER TABLE `competitor`
  ADD CONSTRAINT `competitor_ibfk_1` FOREIGN KEY (`titleID`) REFERENCES `competitorTitle` (`ID`),
  ADD CONSTRAINT `competitor_ibfk_2` FOREIGN KEY (`teamID`) REFERENCES `team` (`ID`);

--
-- Constraints for table `matchAccess`
--
ALTER TABLE `matchAccess`
  ADD CONSTRAINT `matchAccess_ibfk_1` FOREIGN KEY (`venueID`) REFERENCES `venue` (`ID`),
  ADD CONSTRAINT `matchAccess_ibfk_2` FOREIGN KEY (`team1ID`) REFERENCES `team` (`ID`),
  ADD CONSTRAINT `matchAccess_ibfk_3` FOREIGN KEY (`team2ID`) REFERENCES `team` (`ID`);

--
-- Constraints for table `venueUsage`
--
ALTER TABLE `venueUsage`
  ADD CONSTRAINT `venueUsage_ibfk_1` FOREIGN KEY (`cardID`) REFERENCES `card` (`ID`),
  ADD CONSTRAINT `venueUsage_ibfk_2` FOREIGN KEY (`venueID`) REFERENCES `venue` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
