-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2025 at 08:24 AM
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
-- Database: `pg_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aid` int(10) NOT NULL,
  `aname` varchar(20) NOT NULL,
  `aemail` varchar(30) NOT NULL,
  `apass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `aname`, `aemail`, `apass`) VALUES
(101, 'mayur', 'mayur09@gmail.com', '$2y$10$K02rWmZ/NloBBSCld3NGjuADYLBfNh7T7730KvSJgiGuaXgidENzm'),
(102, 'atul', 'atul998@gmail.com', '$2y$10$k66DlgC1cEAQZMqvbVHQI.c/sNm5vQAKGQU4p9j1QKwqMHy2vMyhm');

-- --------------------------------------------------------

--
-- Table structure for table `inquire`
--

CREATE TABLE `inquire` (
  `iid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `a_respone` text NOT NULL,
  `status` enum('Pending','Cancelled','Completed') NOT NULL,
  `resolved_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

CREATE TABLE `listing` (
  `lid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `price` decimal(20,0) NOT NULL,
  `ladd` varchar(50) NOT NULL,
  `stype` varchar(20) NOT NULL,
  `wifi` varchar(40) NOT NULL,
  `ac` varchar(20) NOT NULL,
  `food` varchar(20) NOT NULL,
  `parking` varchar(20) NOT NULL,
  `gym` varchar(20) NOT NULL,
  `securitry` varchar(20) NOT NULL,
  `limage` varchar(255) NOT NULL,
  `ldesc` varchar(255) NOT NULL,
  `aid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` (`lid`, `title`, `price`, `ladd`, `stype`, `wifi`, `ac`, `food`, `parking`, `gym`, `securitry`, `limage`, `ldesc`, `aid`) VALUES
(6, 'Widwlare Pgs', 8700, 'Surat Gujarat', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', '', '', '../uploads/generated-image.png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 101),
(7, 'Guvaliar Pgs', 12000, 'Ahemdabad , Gujarat', 'Double', 'WiFi', 'AC', 'Food', 'Parking', '', 'Security', '../uploads/generated-image (2).png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 101),
(8, 'Patel Pgs', 9000, 'Vadorda , Gujarat', 'Single', 'WiFi', 'AC', 'Food', 'Parking', 'Gym', 'Security', '../uploads/generated-image (10).png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 101),
(9, 'Adani Pgs', 18000, 'Rajkot , Gujarat', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', '', '', '../uploads/generated-image (9).png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 101),
(10, 'Gulati Pgs', 25000, 'Gadhinagar , Gujarat', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', 'Gym', 'Security', '../uploads/generated-image (6).png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 101),
(11, 'Spicesnce Pgs', 7000, 'Nasik , Maharashta', 'Single', 'WiFi', '', 'Food', 'Parking', '', '', '../uploads/generated-image (7).png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 102),
(12, 'Sharmas Pgs', 12000, 'Pune , Maharashta', 'Double', 'WiFi', 'AC', 'Food', 'Parking', '', '', '../uploads/generated-image (8).png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 102),
(13, 'Welbiers Pgs', 19000, 'Mumbai , Maharastha', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', 'Gym', 'Security', '../uploads/generated-image (5).png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 102),
(14, 'Guatms Pgs', 8000, 'Pune , Maharashta', 'Double', 'WiFi', '', 'Food', 'Parking', '', 'Security', '../uploads/generated-image (4).png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 102),
(15, 'The Maharaja Pgs', 30000, 'Mumbai , Maharastha', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', 'Gym', 'Security', '../uploads/generated-image (3).png', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', 102);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `sno` int(11) NOT NULL,
  `uname` varchar(40) NOT NULL,
  `uemail` varchar(40) NOT NULL,
  `upass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`sno`, `uname`, `uemail`, `upass`) VALUES
(7, 'mayur', 'mayurprjpati03@gmail.com', '$2y$10$ZoCG9H1IPf3YhDgG7lpqJ.hu1h7qZcEj8Gz8Q.iCYeC8Kk2LkzNq.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`),
  ADD UNIQUE KEY `aname` (`aname`),
  ADD KEY `aid` (`aid`);

--
-- Indexes for table `inquire`
--
ALTER TABLE `inquire`
  ADD PRIMARY KEY (`iid`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `listing_id` (`listing_id`),
  ADD KEY `listing_id_2` (`listing_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `listing`
--
ALTER TABLE `listing`
  ADD PRIMARY KEY (`lid`),
  ADD KEY `aid` (`aid`),
  ADD KEY `lid` (`lid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`sno`),
  ADD UNIQUE KEY `uname` (`uname`),
  ADD KEY `sno` (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inquire`
--
ALTER TABLE `inquire`
  MODIFY `iid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listing`
--
ALTER TABLE `listing`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inquire`
--
ALTER TABLE `inquire`
  ADD CONSTRAINT `admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`aid`) ON DELETE CASCADE,
  ADD CONSTRAINT `listing_id` FOREIGN KEY (`listing_id`) REFERENCES `listing` (`lid`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`sno`) ON DELETE CASCADE;

--
-- Constraints for table `listing`
--
ALTER TABLE `listing`
  ADD CONSTRAINT `aid` FOREIGN KEY (`aid`) REFERENCES `admin` (`aid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
