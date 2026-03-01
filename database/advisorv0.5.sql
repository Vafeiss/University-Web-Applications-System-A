-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2026 at 07:53 PM
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
-- Database: `advicut`
--

-- --------------------------------------------------------

--
-- Table structure for table `advisor_info`
--

CREATE TABLE `advisor_info` (
  `Advisor_ID` int(11) NOT NULL,
  `External_ID` int(11) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Phone` varchar(12) NOT NULL,
  `Department_Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisor_info`
--

INSERT INTO `advisor_info` (`Advisor_ID`, `External_ID`, `First_name`, `Last_Name`, `Phone`, `Department_Name`) VALUES
(30001, 1123, 'test', 'test', '12131415', 'HMMHY');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisor_info`
--
ALTER TABLE `advisor_info`
  ADD PRIMARY KEY (`Advisor_ID`),
  ADD UNIQUE KEY `External_ID` (`External_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advisor_info`
--
ALTER TABLE `advisor_info`
  ADD CONSTRAINT `fk_advisor_user` FOREIGN KEY (`Advisor_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
