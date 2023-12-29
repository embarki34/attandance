-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2023 at 06:31 PM
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
-- Database: `university`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin1', 'adminpassword1'),
(2, 'admin2', 'adminpassword2');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `event_id`, `student_id`) VALUES
(92, 1, 2),
(93, 1, 1),
(94, 2, 2),
(95, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `location`, `date`) VALUES
(1, 'codecraft', 'sall 12', '2023-12-30'),
(2, 'desine labe', 'hall18', '2023-12-15'),
(8, 'Conference A', 'Conference Center', '2023-03-15'),
(9, 'Seminar B', 'Auditorium', '2023-04-20'),
(10, 'Workshop C', 'Room 101', '2023-05-25'),
(11, 'Meeting D', 'Boardroom', '2023-06-10'),
(12, 'Symposium E', 'Lecture Hall', '2023-07-15'),
(13, 'Exhibition F', 'Gallery', '2023-08-20'),
(14, 'Training G', 'Training Room', '2023-09-25'),
(15, 'Lecture H', 'Classroom 102', '2023-10-10'),
(16, 'Panel Discussion I', 'Meeting Room A', '2023-11-15'),
(17, 'Networking Event J', 'Cafeteria', '2023-12-20');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `family_name` varchar(255) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `family_name`, `student_id`, `department`, `phone_number`, `email`, `points`) VALUES
(1, 'Jane', 'Smith', 'JS456', 'Electrical Engineering', '1212121', 'jane.smith@example.com', 5),
(2, 'omar', 'embarki', 'trt', 'mi', '07969', 'embarki24@gmail.com', 8),
(11, 'Alice', 'Johnson', 'AJ789', 'Physics', '555-1234', 'alice.johnson@example.com', 0),
(12, 'Bob', 'Williams', 'BW567', 'Chemistry', '555-5678', 'bob.williams@example.com', 0),
(13, 'Charlie', 'Brown', 'CB123', 'Biology', '555-4321', 'charlie.brown@example.com', 0),
(14, 'David', 'Smith', 'DS456', 'Computer Science', '555-8765', 'david.smith@example.com', 0),
(15, 'Eva', 'Miller', 'EM789', 'Mathematics', '555-2345', 'eva.miller@example.com', 0),
(16, 'Frank', 'Davis', 'FD123', 'Electrical Engineering', '555-7890', 'frank.davis@example.com', 0),
(17, 'Grace', 'Jones', 'GJ567', 'Chemical Engineering', '555-3456', 'grace.jones@example.com', 0),
(18, 'Henry', 'Taylor', 'HT123', 'Mechanical Engineering', '555-8901', 'henry.taylor@example.com', 0),
(19, 'Ivy', 'White', 'IW456', 'Psychology', '555-4567', 'ivy.white@example.com', 0),
(20, 'Jack', 'Martin', 'JM789', 'Economics', '555-0123', 'jack.martin@example.com', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `members` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
