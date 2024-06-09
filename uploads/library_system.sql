-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2024 at 09:39 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `admin_id` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_id`, `password`) VALUES
(1, 'admin1', '$2y$10$A4hN2zuD4.urYoLAvxvBu.zTcJM4JC2yZUmr3PHP6YsTI/8BB.Eh.'),
(2, 'admin_id', '$2y$10$7SPmcL6/2ENPPJqH7yJXUuscFkMjWEL4nyy6JFoR7Ev4Vos4IlUd6');

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `author_name` varchar(150) DEFAULT NULL,
  `status` varchar(60) NOT NULL DEFAULT 'active',
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id`, `author_name`, `status`, `CreationDate`, `UpdationDate`) VALUES
(1, 'Natoy NA ito ohh', 'active', '2024-04-10 17:29:09', '2024-04-10 17:29:09'),
(2, 'Jefferson', 'active', '2024-04-10 17:31:02', '2024-04-10 17:31:02'),
(3, 'Vince Suinan', 'active', '2024-04-13 15:30:00', '2024-04-13 15:30:00'),
(4, 'Junior John', 'active', '2024-04-13 15:30:13', '2024-04-13 15:30:13'),
(5, 'Lawrence', 'active', '2024-04-13 19:26:38', '2024-04-13 19:26:38'),
(6, 'Vince Suinan', 'active', '2024-04-13 19:26:42', '2024-04-13 19:26:42');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `status` enum('Available','Unavailable') DEFAULT 'Available',
  `category` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` >= 0),
  `isbn` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archive` varchar(60) NOT NULL DEFAULT 'enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `cover_photo`, `status`, `category`, `quantity`, `isbn`, `created_at`, `archive`) VALUES
(1, 'UPDATED BOOK TITLE SAMPLE 3', 'Junior John', 'ab - Copy.png', 'Unavailable', 'smple', 3, NULL, '2024-04-10 17:54:11', 'enable'),
(2, 'Science and Health ssss', 'Vince Suinan', 'Screenshot 2023-11-05 201756.png', 'Available', 'Thesis', 34, NULL, '2024-04-10 18:01:28', 'enable'),
(3, 'sample book 34', 'AUthor 2', 'Screenshot 2023-11-19 154730.png', 'Available', 'Category 33ss', 3, NULL, '2024-04-10 18:35:11', 'enable'),
(4, 'GAHAHAHH Lingunin', 'Natoy NA ito ohh', 'Screenshot 2023-12-12 212610.png', 'Unavailable', 'Science Sheesh', 2, NULL, '2024-04-11 21:47:13', 'enable'),
(5, 'Library Management System', 'Vince Suinan', 'Screenshot 2024-03-19 103200.png', 'Available', 'Thesis', 1, NULL, '2024-04-13 15:30:52', 'enable'),
(6, 'TItle Book sample3', 'Vince Suinan', 'Group 13 (1).png', 'Unavailable', 'Category 33ss Shesh HHAHAHA', 0, NULL, '2024-04-13 19:32:57', 'enable');

-- --------------------------------------------------------

--
-- Table structure for table `book_category`
--

CREATE TABLE `book_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(150) DEFAULT NULL,
  `status` varchar(60) DEFAULT 'active',
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_category`
--

INSERT INTO `book_category` (`category_id`, `category_name`, `status`, `CreationDate`, `UpdationDate`) VALUES
(5, 'Category 33ss Shesh HHAHAHA', 'active', '2024-04-10 17:19:10', '2024-04-10 17:19:10'),
(8, 'Thesis', 'active', '2024-04-13 15:29:38', '2024-04-13 15:29:38'),
(9, 'Sheesh', 'active', '2024-04-13 19:23:15', '2024-04-13 19:23:15'),
(10, 'patawa', 'active', '2024-04-13 19:23:22', '2024-04-13 19:23:22'),
(11, 'smple', 'active', '2024-04-13 19:23:26', '2024-04-13 19:23:26'),
(12, 'hahahahahha', 'active', '2024-04-13 19:23:56', '2024-04-13 19:23:56');

-- --------------------------------------------------------

--
-- Table structure for table `book_requests`
--

CREATE TABLE `book_requests` (
  `id` int(11) NOT NULL,
  `book_id` int(60) NOT NULL,
  `student_id` varchar(60) NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `block` varchar(60) NOT NULL,
  `section` varchar(60) NOT NULL,
  `date_borrowed` timestamp NULL DEFAULT current_timestamp(),
  `date_return` timestamp NULL DEFAULT NULL,
  `status` varchar(60) DEFAULT 'pending',
  `borrowed_status` varchar(60) DEFAULT 'Not Return Yet',
  `return_date` timestamp NULL DEFAULT NULL,
  `archive` varchar(60) NOT NULL DEFAULT 'enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_requests`
--

INSERT INTO `book_requests` (`id`, `book_id`, `student_id`, `student_name`, `block`, `section`, `date_borrowed`, `date_return`, `status`, `borrowed_status`, `return_date`, `archive`) VALUES
(1, 1, '0645-8594', 'Pakundo', 'CCS', '3A', '2024-04-12 16:00:00', '2024-04-15 16:00:00', 'Approved', 'Returned Complete', '2024-04-13 18:18:34', 'enable'),
(2, 2, '061-5486', 'Vince Suinan', 'CCS - 3B ', '3B ', '2024-04-12 16:00:00', '2024-04-13 16:00:00', 'Approved', 'Returned Complete', '2024-04-14 05:39:22', 'enable'),
(3, 4, '061-5486', 'Vince Suinan', 'CCS - 3B ', '3B ', '2024-04-07 16:00:00', '2024-04-11 16:00:00', 'Approved', 'Returned Complete', '2024-04-14 04:47:25', 'enable'),
(4, 3, '061-5486', 'Vince Suinan', 'CCS - 3B ', '3B ', '2024-04-11 16:00:00', '2024-04-23 16:00:00', 'Approved', 'Returned Complete', '2024-04-14 05:39:43', 'enable'),
(5, 3, '061-5486', 'Vince Suinan', 'CCS - 3B ', '3B ', '2024-04-11 16:00:00', '2024-04-25 16:00:00', 'Approved', 'Returned Complete', '2024-04-14 05:39:43', 'enable'),
(6, 4, '061-5486', 'Vince Suinan', 'CCS - 3B ', '3B ', '2024-04-11 16:00:00', '2024-04-12 16:00:00', 'Approved', 'Returned Complete', '2024-04-14 04:47:25', 'enable'),
(7, 1, '061-5486', 'Vince Suinan', 'CCS - 3B ', '3B ', '2024-04-11 16:00:00', '2024-04-13 16:00:00', 'Approved', 'Returned Complete', '2024-04-13 18:18:34', 'enable'),
(8, 1, '061-5486', 'Vince Chua Suinans', 'CCS - 3A ', '3A ', '2024-04-12 16:00:00', '2024-04-13 16:00:00', 'Approved', 'Returned Complete', '2024-04-13 18:18:34', 'enable'),
(9, 5, '061-5486', 'Vince Chua Suinans', 'CCS - 3AB ', '3AB ', '2024-04-12 16:00:00', '2024-04-24 16:00:00', 'Declined', 'Returned Complete', '2024-04-14 05:38:51', 'enable'),
(10, 1, '096-8498', 'Vince Suinan', 'CAS - 1C ', '1C ', '2024-04-13 16:00:00', '2024-04-15 16:00:00', 'Approved', 'Returned Complete', '2024-04-13 18:18:34', 'enable'),
(11, 2, '096-8498', 'Vince Suinan', 'CAS - 1C ', '1C ', '2024-04-13 16:00:00', '2024-04-21 16:00:00', 'Approved', 'Returned Complete', '2024-04-14 05:39:22', 'enable'),
(12, 6, '096-8498', 'Vince Suinan', 'CAS - 1C ', '1C ', '2024-04-13 16:00:00', '2024-04-15 16:00:00', 'Approved', 'Returned Complete', '2024-04-14 04:47:51', 'enable'),
(13, 2, '213-3332', 'VLadimir Ginovachi', 'BSA - 3D ', '3D ', '2024-04-13 16:00:00', '2024-04-15 16:00:00', 'Approved', 'Returned Complete', '2024-04-14 05:39:22', 'enable'),
(14, 3, '213-3332', 'VLadimir Ginovachi', 'BSA - 3D ', '3D ', '2024-04-13 16:00:00', '2024-04-22 16:00:00', 'Approved', 'Returned Complete', '2024-04-14 05:39:43', 'enable'),
(15, 4, '213-3332', 'VLadimir Ginovachi', 'BSA - 3D ', '3D ', '2024-04-13 16:00:00', '2024-04-14 16:00:00', 'Approved', 'Not Return Yet', NULL, 'enable'),
(16, 6, '213-3332', 'VLadimir Ginovachi', 'BSA - 3D ', '3D ', '2024-04-13 16:00:00', '2024-04-20 16:00:00', 'Approved', 'Not Return Yet', NULL, 'enable'),
(17, 1, '213-3332', 'VLadimir Ginovachi', 'BSA - 3D ', '3D ', '2024-04-13 16:00:00', '2024-04-21 16:00:00', 'pending', 'Not Return Yet', NULL, 'enable');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `block` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `email`, `phone`, `student_id`, `block`, `section`, `password`, `status`) VALUES
(1, 'Vince', 'Suinan', 'suinanlawrence08@gmail.com', '091080294342', 'ssa', 'BSA', 'e32123', '$2y$10$6zGQ0C97J14LL/rsSxQ2duvngdsJWvjgcrz6ax67iDJv6Qos00dr.', 'Blocked'),
(2, 'Vince', 'Suinan', 'suinanlawrence08@gmail.com', '091080294342', '091080294342', 'BSA', 'SAA', '$2y$10$Lug2OmaAKNXSU7352GFpzOQNizOEZFiETX2m12CokTrVjZxnkt09i', 'Blocked'),
(3, 'Vince', 'Suinan', 'suinanlawrence08@gmail.com', '09108029482', '09108029482', 'BSA', '3B', '$2y$10$PX.Jrh8BlL2KWcFxlx72fez3fntkPVNZyeBYhk72iMzFXEYqKmWBS', 'Active'),
(4, 'Vince Chua', 'Suinan', 'suinanlawrence08@gmail.com', '091080294822', '061-5486', 'CCS', '3AB', '$2y$10$lPoADUAWEB49ztQIEibDJuqHfXSEh1DC0XcWSLqtp.F7mPNV.HUnK', 'Active'),
(5, 'Vince', 'Suinan', 'suinanlawrence082@gmail.com', '08788787878', '096-8498', 'CAS', '1C', '$2y$10$1fViSQ7YioRv.yK9.b4WLOw6PcFdkqCwTkbJygp/ORdWOrSIPOlte', 'Active'),
(6, 'Vlasd', 'sample', 'adminssda@gmail.com', NULL, '096-8498', 'CAS', '1C', '$2y$10$Ftwti6Fs0.Ye1tOfICdp8eKR.jdpsmJjx7o5OtmFAIk8dijeSaU4e', 'pending'),
(7, 'VLadimir', 'Ginovachi', 'Ginovachi@gmail.com', NULL, '213-3332', 'BSA', '3D', '$2y$10$cjm3gg6Condj1c1zYgXzQOJvKmr6Ew7gi2dzmxbs2.9tYLv2is6z2', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `book_category`
--
ALTER TABLE `book_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `book_requests`
--
ALTER TABLE `book_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `book_category`
--
ALTER TABLE `book_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `book_requests`
--
ALTER TABLE `book_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
