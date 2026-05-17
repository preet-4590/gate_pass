-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 10:47 AM
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
-- Database: `gate_pass`
--

-- --------------------------------------------------------

--
-- Table structure for table `clerks`
--

CREATE TABLE `clerks` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `institution` enum('GNDEC','GNDPC','GNDITI') NOT NULL,
  `role` enum('clerk','super_admin') NOT NULL DEFAULT 'clerk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clerks`
--

INSERT INTO `clerks` (`username`, `password`, `institution`, `role`) VALUES
('admin_gndec', 'pass123', 'GNDEC', 'clerk'),
('admin_gnditi', 'pass789', 'GNDITI', 'clerk'),
('admin_gndpc', 'pass456', 'GNDPC', 'clerk'),
('main_admin', 'admin123', 'GNDEC', 'super_admin');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `unique_id` varchar(20) NOT NULL,
  `roll_no` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `institution` varchar(10) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `phone_no` varchar(15) DEFAULT NULL,
  `gate_no` varchar(10) DEFAULT NULL,
  `admission_year` int(11) DEFAULT NULL,
  `passing_year` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `qr_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`unique_id`, `roll_no`, `name`, `institution`, `course`, `phone_no`, `gate_no`, `admission_year`, `passing_year`, `photo`, `signature`, `qr_path`) VALUES
('#E1', '2429017', 'Manpreet Kaur', 'GNDEC', 'Master of Computer Application ', '7973219070', '1', 2024, 2026, 'p2.jpeg', 'S2.png', 'uploads/qrcodes/E1.png');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `institution` varchar(100) DEFAULT NULL,
  `direction` enum('IN','OUT') NOT NULL,
  `gate_no` varchar(50) NOT NULL,
  `log_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clerks`
--
ALTER TABLE `clerks`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`unique_id`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `fk_student_logs` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD CONSTRAINT `fk_student_logs` FOREIGN KEY (`student_id`) REFERENCES `students` (`unique_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
