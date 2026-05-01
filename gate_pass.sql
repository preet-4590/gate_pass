-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2026 at 09:41 AM
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
  `institution` enum('GNDEC','GNDPC','GNDITI') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clerks`
--

INSERT INTO `clerks` (`username`, `password`, `institution`) VALUES
('admin_gndec', 'pass123', 'GNDEC'),
('admin_gnditi', 'pass789', 'GNDITI'),
('admin_gndpc', 'pass456', 'GNDPC');

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
('#E1', '2429008', 'Harshdeep Singh ', 'GNDEC', 'Master of Computer Application ', '7696043931', '1', 2024, 2026, '1777620817_photo_WhatsApp Image 2026-05-01 at 11.16.59 AM.jpeg', '1777620817_sig_WhatsApp Image 2026-05-01 at 11.17.58 AM.jpeg', 'uploads/qrcodes/E1.png'),
('#E2', '2429011', 'Jagjot Singh ', 'GNDEC', 'Master of Computer Application ', '9417810241', '1', 2024, 2026, '1777620849_photo_Screenshot 2026-05-01 112543.png', '1777620849_sig_Screenshot 2026-05-01 112548.png', 'uploads/qrcodes/E2.png'),
('#E3', '2429014', 'Khushmeet Kaur ', 'GNDEC', 'Master of Computer Application ', '8146524143', '1', 2024, 2026, '1777620881_photo_Screenshot 2026-04-30 111254.png', '1777620881_sig_WhatsApp Image 2026-04-30 at 11.06.23 AM.jpeg', 'uploads/qrcodes/E3.png'),
('#E4', '2429017', 'Manpreet Kaur', 'GNDEC', 'Master of Computer Application ', '7973219070', '1', 2024, 2026, '1777620921_photo_WhatsApp Image 2026-04-28 at 10.39.01 PM.jpeg', '1777620921_sig_Screenshot 2026-04-28 224322.png', 'uploads/qrcodes/E4.png'),
('#E5', '2429030', 'Vansh  Mehra', 'GNDEC', 'Master of Computer Application ', '9779115985', '1', 2024, 2026, '1777620997_photo_Screenshot 2026-04-29 102929.png', '1777620997_sig_WhatsApp Image 2026-04-29 at 10.27.06 AM.jpeg', 'uploads/qrcodes/E5.png');

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
