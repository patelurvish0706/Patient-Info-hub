-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2025 at 02:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pims`
--
CREATE DATABASE IF NOT EXISTS `pims` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pims`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_Id` int(11) NOT NULL,
  `admin_Email` varchar(100) NOT NULL,
  `admin_Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `app_Id` int(11) NOT NULL,
  `user_Id` int(11) NOT NULL,
  `app_time` time NOT NULL,
  `app_date` date NOT NULL,
  `visit_for` varchar(255) DEFAULT NULL,
  `dept_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approval`
--

CREATE TABLE `approval` (
  `app_Id` int(11) NOT NULL,
  `approval_Status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `dept_Id` int(11) NOT NULL,
  `hospital_Id` int(11) NOT NULL,
  `admin_Id` int(11) NOT NULL,
  `dept_Name` varchar(100) NOT NULL,
  `dept_Email` varchar(100) NOT NULL,
  `dept_Phone` varchar(15) DEFAULT NULL,
  `dept_Password` varchar(255) NOT NULL,
  `Dept_Description` varchar(556) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doct_Id` int(11) NOT NULL,
  `dept_Id` int(11) NOT NULL,
  `hospital_Id` int(11) NOT NULL,
  `admin_Id` int(11) NOT NULL,
  `doct_Email` varchar(100) NOT NULL,
  `doct_Password` varchar(255) NOT NULL,
  `doct_Name` varchar(100) DEFAULT NULL,
  `doct_Phone` varchar(15) DEFAULT NULL,
  `doct_Speacialist` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `hospital_Id` int(11) NOT NULL,
  `admin_Id` int(11) NOT NULL,
  `hospital_Name` varchar(100) NOT NULL,
  `hospital_Email` varchar(100) NOT NULL,
  `hospital_Lat` decimal(10,6) DEFAULT NULL,
  `hospital_Long` decimal(10,6) DEFAULT NULL,
  `hospital_Phone` varchar(15) DEFAULT NULL,
  `hospital_Time_open` time DEFAULT NULL,
  `hospital_Time_close` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `app_Id` int(11) NOT NULL,
  `checkup_outcome` text DEFAULT NULL,
  `prescriptions` text DEFAULT NULL,
  `suggestion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_Id` int(11) NOT NULL,
  `user_Name` varchar(100) NOT NULL,
  `user_Email` varchar(100) NOT NULL,
  `user_Password` varchar(255) NOT NULL,
  `user_DOB` date DEFAULT NULL,
  `user_Phone` varchar(15) DEFAULT NULL,
  `user_Gender` enum('Male','Female','Other') DEFAULT NULL,
  `user_Address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
  `app_Id` int(11) NOT NULL,
  `Visit_Status` enum('Not Visited','Visited','Cancelled') DEFAULT 'Not Visited',
  `Visit_Time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_Id`),
  ADD UNIQUE KEY `admin_Email` (`admin_Email`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`app_Id`),
  ADD KEY `user_Id` (`user_Id`);

--
-- Indexes for table `approval`
--
ALTER TABLE `approval`
  ADD PRIMARY KEY (`app_Id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`dept_Id`),
  ADD UNIQUE KEY `dept_Email` (`dept_Email`),
  ADD KEY `hospital_Id` (`hospital_Id`),
  ADD KEY `admin_Id` (`admin_Id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doct_Id`),
  ADD UNIQUE KEY `doct_Email` (`doct_Email`),
  ADD KEY `dept_Id` (`dept_Id`),
  ADD KEY `hospital_Id` (`hospital_Id`),
  ADD KEY `admin_Id` (`admin_Id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`hospital_Id`),
  ADD UNIQUE KEY `hospital_Email` (`hospital_Email`),
  ADD KEY `admin_Id` (`admin_Id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`app_Id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_Id`),
  ADD UNIQUE KEY `user_Email` (`user_Email`);

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`app_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `app_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `dept_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doct_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `hospital_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_Id`) REFERENCES `user_details` (`user_Id`) ON DELETE CASCADE;

--
-- Constraints for table `approval`
--
ALTER TABLE `approval`
  ADD CONSTRAINT `approval_ibfk_1` FOREIGN KEY (`app_Id`) REFERENCES `appointments` (`app_Id`) ON DELETE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`hospital_Id`) REFERENCES `hospitals` (`hospital_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `departments_ibfk_2` FOREIGN KEY (`admin_Id`) REFERENCES `admin` (`admin_Id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`dept_Id`) REFERENCES `departments` (`dept_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`hospital_Id`) REFERENCES `hospitals` (`hospital_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctors_ibfk_3` FOREIGN KEY (`admin_Id`) REFERENCES `admin` (`admin_Id`) ON DELETE CASCADE;

--
-- Constraints for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD CONSTRAINT `hospitals_ibfk_1` FOREIGN KEY (`admin_Id`) REFERENCES `admin` (`admin_Id`) ON DELETE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`app_Id`) REFERENCES `appointments` (`app_Id`) ON DELETE CASCADE;

--
-- Constraints for table `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`app_Id`) REFERENCES `appointments` (`app_Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
