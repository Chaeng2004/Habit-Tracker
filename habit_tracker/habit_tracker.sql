-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 09:15 PM
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
-- Database: `habit_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `habits`
--

CREATE TABLE `habits` (
  `habit_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `frequency` enum('daily','weekly') NOT NULL,
  `start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habits`
--

INSERT INTO `habits` (`habit_id`, `name`, `frequency`, `start_date`) VALUES
(1, 'Exercise', 'daily', '2025-04-01'),
(2, 'Read Book', 'weekly', '2025-04-01'),
(3, 'Scrolling', 'daily', '2025-04-16');

-- --------------------------------------------------------

--
-- Table structure for table `habit_completions`
--

CREATE TABLE `habit_completions` (
  `habit_completion_id` int(11) NOT NULL,
  `habit_id` int(11) DEFAULT NULL,
  `completion_date` date NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habit_completions`
--

INSERT INTO `habit_completions` (`habit_completion_id`, `habit_id`, `completion_date`, `completed`) VALUES
(1, 1, '2025-04-27', 1),
(2, 2, '2025-04-21', 1),
(3, 3, '2025-04-27', 0),
(4, 3, '2025-04-28', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `habits`
--
ALTER TABLE `habits`
  ADD PRIMARY KEY (`habit_id`);

--
-- Indexes for table `habit_completions`
--
ALTER TABLE `habit_completions`
  ADD PRIMARY KEY (`habit_completion_id`),
  ADD KEY `habit_id` (`habit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `habits`
--
ALTER TABLE `habits`
  MODIFY `habit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `habit_completions`
--
ALTER TABLE `habit_completions`
  MODIFY `habit_completion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `habit_completions`
--
ALTER TABLE `habit_completions`
  ADD CONSTRAINT `habit_completions_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habits` (`habit_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
