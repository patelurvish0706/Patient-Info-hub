-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 08:56 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_Id` int(11) NOT NULL,
  `admin_Email` varchar(100) NOT NULL,
  `admin_Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_Id`, `admin_Email`, `admin_Password`) VALUES
(1, 'user@gmail.com', '$2y$10$gF4eW0G33xeKx4PL92QR0um.4RM/gAv80lD/txYTFGfFXCK.0LWhG'),
(3, 'user2@gmail.com', '$2y$10$xnj4k5f.s29q/wYFpBdgieO1Np0Onas5MdHzYX09oNQDoGJjoYPGK'),
(4, 'drushti@gmail.com', '$2y$10$4OJ9gQnjhbyq9Dwy/bzOcuFDtDDYFWzhwluO3AQV5FIcKOOaA7H32');

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

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`app_Id`, `user_Id`, `app_time`, `app_date`, `visit_for`, `dept_id`) VALUES
(1, 2, '23:54:00', '2025-06-14', 'Heart Checkup', '1'),
(2, 2, '23:54:00', '2025-06-15', 'Heart Checkup', '1'),
(3, 2, '23:08:00', '2025-06-21', 'heart testing', '1'),
(4, 2, '23:08:00', '2025-06-28', 'heart testing 2', '1'),
(5, 2, '23:08:00', '2025-06-14', 'heart testing 3', '1'),
(6, 2, '13:59:00', '2025-06-19', 'eye care', '5');

-- --------------------------------------------------------

--
-- Table structure for table `approval`
--

CREATE TABLE `approval` (
  `app_Id` int(11) NOT NULL,
  `approval_Status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approval`
--

INSERT INTO `approval` (`app_Id`, `approval_Status`) VALUES
(1, 'Approved'),
(2, 'Approved'),
(3, 'Rejected'),
(4, 'Approved'),
(5, 'Approved');

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

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`dept_Id`, `hospital_Id`, `admin_Id`, `dept_Name`, `dept_Email`, `dept_Phone`, `dept_Password`, `Dept_Description`) VALUES
(1, 1, 1, 'Cardiology Department', 'cardiology@gmail.com', '999999999', '111111', 'Fixes hearts but not broken one,'),
(2, 1, 1, 'Neurology Department', 'neuro@gmail.com', '999999999', '111111', 'Fixing Brain but not empty one.'),
(3, 1, 1, 'Orthology Department', 'ortho@gmail.com', '74136985', '111111', 'Broken Bones fixes here.'),
(4, 3, 3, 'Spine Care Section', 'spineCivil@mail.com', '9876543210', '111111', 'We provide world best spine care.'),
(5, 4, 4, 'Eye care', 'eye@gmail.com', '9876564332', '111111', 'we take care of your eyes');

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

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doct_Id`, `dept_Id`, `hospital_Id`, `admin_Id`, `doct_Email`, `doct_Password`, `doct_Name`, `doct_Phone`, `doct_Speacialist`) VALUES
(1, 4, 3, 3, 'sarah@gmail.com', '111111', 'Sarah Pathak', '1234567890', 'spine Care Mastered Pho'),
(2, 2, 1, 1, 'arvind@gmail.cm', '111111', 'Drushti gupta', '98747563210', 'Neurology Mster'),
(4, 5, 4, 4, 'dru@gmail.com', '111111', 'Drushti gupta', '2345589876', 'mbbs');

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

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`hospital_Id`, `admin_Id`, `hospital_Name`, `hospital_Email`, `hospital_Lat`, `hospital_Long`, `hospital_Phone`, `hospital_Time_open`, `hospital_Time_close`) VALUES
(1, 1, 'Appolo Hospital Bhat', 'appolo@gmail.com', 23.108149, 72.642289, '9876543210', '07:30:00', '22:30:00'),
(3, 3, 'Civil Hospital Ahmedabad', 'civil@gmail.com', 23.052274, 72.604372, '1234567890', '00:00:00', '12:00:00'),
(4, 4, 'drushti clinic', 'drushticlinic@gmail.com', 23.091059, 72.593665, '0987654321', '09:00:00', '22:00:00');

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

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_Id`, `user_Name`, `user_Email`, `user_Password`, `user_DOB`, `user_Phone`, `user_Gender`, `user_Address`) VALUES
(1, 'anandaa jhaa', 'user@gmail.com', '$2y$10$kltw6zUpgHw1wzLJ3p6.BeF0pHn2d5sNXG/MMu8CysYKiM1O5GuoW', '1999-01-01', '1236798765432', 'Male', '01, winewood, loss angles'),
(2, 'Urvish', 'urvish@gmail.com', '111111', '2005-06-07', '1234567892', 'Male', 'vijapur');

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
  MODIFY `admin_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `app_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `dept_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doct_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `hospital_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
