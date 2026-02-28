-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2026 at 06:44 PM
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
  `First_name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Phone` varchar(12) NOT NULL,
  `Department_Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisor_info`
--

INSERT INTO `advisor_info` (`Advisor_ID`, `First_name`, `Last_Name`, `Phone`, `Department_Name`) VALUES
(30000, 'Andreas', 'Andreou', '11111111', 'ΗΜΜΗΥ');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_history`
--

CREATE TABLE `appointment_history` (
  `Appointment_ID` int(11) NOT NULL,
  `Student_ID` int(11) NOT NULL,
  `Advisor_ID` int(11) NOT NULL,
  `Reason` text NOT NULL,
  `Appointment_Date` datetime NOT NULL,
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
  `Year` int(11) NOT NULL,
  `Advisor_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`Student_ID`, `First_name`, `Last_Name`, `Year`, `Advisor_ID`) VALUES
(27407, 'Paraskevas', 'Vafeiadis', 3, 30000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `Uni_Email` varchar(150) NOT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `Role` enum('Student','Advisor','Admin','SuperUser') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `Uni_Email`, `Password`, `Role`) VALUES
(1, 'admin@cut.ac.cy', '$2y$10$VT59...', 'Admin'),
(2, 'superuser@cut.ac.cy', '$2y$10$VT59...', 'SuperUser'),
(27407, 'pt.vafeiadis@edu.cut.ac.cy', '$2y$10$VT59...', 'Student'),
(30000, 'a.andreou@cut.ac.cy', '$2y$10$VT59...', 'Advisor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisor_info`
--
ALTER TABLE `advisor_info`
  ADD PRIMARY KEY (`Advisor_ID`);

--
-- Indexes for table `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD PRIMARY KEY (`Appointment_ID`),
  ADD KEY `fk_app_student` (`Student_ID`),
  ADD KEY `fk_app_advisor` (`Advisor_ID`);

--
-- Indexes for table `communication_history`
--
ALTER TABLE `communication_history`
  ADD PRIMARY KEY (`Comm_ID`),
  ADD KEY `fk_comm_student` (`Student_ID`),
  ADD KEY `fk_comm_advisor` (`Advisor_ID`);

--
-- Indexes for table `student_info`
--
ALTER TABLE `student_info`
  ADD PRIMARY KEY (`Student_ID`),
  ADD KEY `fk_student_advisor` (`Advisor_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `Uni_Email` (`Uni_Email`);

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30001;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advisor_info`
--
ALTER TABLE `advisor_info`
  ADD CONSTRAINT `fk_advisor_user` FOREIGN KEY (`Advisor_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD CONSTRAINT `fk_app_advisor` FOREIGN KEY (`Advisor_ID`) REFERENCES `advisor_info` (`Advisor_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_app_student` FOREIGN KEY (`Student_ID`) REFERENCES `student_info` (`Student_ID`) ON DELETE CASCADE;

--
-- Constraints for table `communication_history`
--
ALTER TABLE `communication_history`
  ADD CONSTRAINT `fk_comm_advisor` FOREIGN KEY (`Advisor_ID`) REFERENCES `advisor_info` (`Advisor_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comm_student` FOREIGN KEY (`Student_ID`) REFERENCES `student_info` (`Student_ID`) ON DELETE CASCADE;

--
-- Constraints for table `student_info`
--
ALTER TABLE `student_info`
  ADD CONSTRAINT `fk_student_advisor` FOREIGN KEY (`Advisor_ID`) REFERENCES `advisor_info` (`Advisor_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_user` FOREIGN KEY (`Student_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
