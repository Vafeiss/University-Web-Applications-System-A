-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 04:48 PM
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
  `Advisor_ID` int(5) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Uni_Email` varchar(200) NOT NULL,
  `Phone` varchar(12) NOT NULL,
  `Department_Name` varchar(100) NOT NULL,
  `OneTime_Password` varchar(20) NOT NULL,
  `NewPassword` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisor_info`
--

INSERT INTO `advisor_info` (`Advisor_ID`, `First_name`, `Last_Name`, `Uni_Email`, `Phone`, `Department_Name`, `OneTime_Password`, `NewPassword`) VALUES
(30000, 'Andreas', 'Andreou', 'a.andreou@cut.ac.cy', '11111111', 'ΗΜΜΗΥ', '12345', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_history`
--

CREATE TABLE `appointment_history` (
  `Appointment_ID` int(11) NOT NULL,
  `Student_ID` int(11) NOT NULL,
  `Advisor_ID` int(11) NOT NULL,
  `Reason` text NOT NULL,
  `Appointment_Date` int(11) NOT NULL,
  `Status` tinyint(1) NOT NULL,
  `Attendance` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communication_history`
--

CREATE TABLE `communication_history` (
  `Comm_ID` int(11) NOT NULL,
  `Student_ID` int(11) NOT NULL,
  `Advisor_ID` int(11) NOT NULL,
  `Message_Content` text NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

CREATE TABLE `student_info` (
  `Student_ID` int(11) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Uni_Email` varchar(200) NOT NULL,
  `Year` int(1) NOT NULL,
  `Advisor_ID` int(5) NOT NULL,
  `OneTime_Password` varchar(20) NOT NULL,
  `NewPassword` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`Student_ID`, `First_name`, `Last_Name`, `Uni_Email`, `Year`, `Advisor_ID`, `OneTime_Password`, `NewPassword`) VALUES
(27407, 'Paraskevas', 'Vafeiadis', 'pt.vafeiadis@edu.cut.ac.cy', 3, 30000, '1234', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisor_info`
--
ALTER TABLE `advisor_info`
  ADD PRIMARY KEY (`Advisor_ID`),
  ADD UNIQUE KEY `Uni_Email` (`Uni_Email`);

--
-- Indexes for table `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD PRIMARY KEY (`Appointment_ID`),
  ADD KEY `Advisor_ID` (`Advisor_ID`),
  ADD KEY `Student_ID` (`Student_ID`);

--
-- Indexes for table `communication_history`
--
ALTER TABLE `communication_history`
  ADD PRIMARY KEY (`Comm_ID`),
  ADD KEY `Advisor_ID` (`Advisor_ID`),
  ADD KEY `Student_ID` (`Student_ID`);

--
-- Indexes for table `student_info`
--
ALTER TABLE `student_info`
  ADD PRIMARY KEY (`Student_ID`),
  ADD UNIQUE KEY `Uni_Email` (`Uni_Email`),
  ADD KEY `Advisor_ID` (`Advisor_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment_history`
--
ALTER TABLE `appointment_history`
  MODIFY `Appointment_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communication_history`
--
ALTER TABLE `communication_history`
  MODIFY `Comm_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_info`
--
ALTER TABLE `student_info`
  MODIFY `Student_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27408;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD CONSTRAINT `appointment_history_ibfk_1` FOREIGN KEY (`Advisor_ID`) REFERENCES `advisor_info` (`Advisor_ID`),
  ADD CONSTRAINT `appointment_history_ibfk_2` FOREIGN KEY (`Advisor_ID`) REFERENCES `advisor_info` (`Advisor_ID`),
  ADD CONSTRAINT `appointment_history_ibfk_3` FOREIGN KEY (`Student_ID`) REFERENCES `advisor_info` (`Advisor_ID`);

--
-- Constraints for table `communication_history`
--
ALTER TABLE `communication_history`
  ADD CONSTRAINT `communication_history_ibfk_1` FOREIGN KEY (`Advisor_ID`) REFERENCES `advisor_info` (`Advisor_ID`),
  ADD CONSTRAINT `communication_history_ibfk_2` FOREIGN KEY (`Student_ID`) REFERENCES `advisor_info` (`Advisor_ID`);

--
-- Constraints for table `student_info`
--
ALTER TABLE `student_info`
  ADD CONSTRAINT `student_info_ibfk_1` FOREIGN KEY (`Advisor_ID`) REFERENCES `advisor_info` (`Advisor_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
