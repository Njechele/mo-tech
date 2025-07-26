-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2025 at 01:50 PM
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
-- Database: `secondaryschool`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(255) NOT NULL,
  `class_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_name`) VALUES
(1, 'form one'),
(2, 'form two'),
(3, 'form three'),
(4, 'form four');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `comment` varchar(50) NOT NULL,
  `class_id` int(255) NOT NULL,
  `teacher_reply` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `comment`, `class_id`, `teacher_reply`) VALUES
(14, 68, 'kajitahidi sasa hivi 👌👌👏👏👏👏', 1, '🙌😎 usiajri mama asmah nijukumu letu'),
(15, 68, 'asante sana🤝🙏', 1, '🤜🤛');

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE `result` (
  `result_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `term_id` int(255) NOT NULL,
  `class_id` int(255) NOT NULL,
  `kiswahili` int(10) NOT NULL,
  `english` int(10) NOT NULL,
  `history` int(10) NOT NULL,
  `mathematics` int(10) NOT NULL,
  `geography` int(10) NOT NULL,
  `civics` int(10) NOT NULL,
  `biology` int(10) NOT NULL,
  `chemistry` int(10) NOT NULL,
  `physics` int(10) NOT NULL,
  `avarage` int(50) NOT NULL,
  `point` float NOT NULL,
  `division` varchar(50) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`result_id`, `user_id`, `term_id`, `class_id`, `kiswahili`, `english`, `history`, `mathematics`, `geography`, `civics`, `biology`, `chemistry`, `physics`, `avarage`, `point`, `division`, `remark`, `position`, `date_created`) VALUES
(31, 68, 3, 1, 90, 45, 34, 9, 89, 46, 34, 30, 78, 51, 26, 'Division IV', 'Pass', 'Position: 1/1', '2025-07-13 10:58:47'),
(34, 72, 6, 3, 90, 45, 34, 9, 34, 4, 34, 0, 0, 36, 26, 'Division IV', 'Pass', 'Position: 1/1', '2025-12-13 15:44:03'),
(37, 72, 3, 3, 90, 45, 34, 9, 34, 46, 34, 0, 0, 42, 24, 'Division III', 'Good', 'Position: 2/2', '2025-07-13 12:16:09'),
(38, 74, 3, 3, 90, 90, 90, 30, 9, 9, 95, 90, 10, 57, 24, 'Division III', 'Good', 'Position: 1/2', '2025-07-13 12:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_class_history`
--

CREATE TABLE `student_class_history` (
  `history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `date_assigned` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_class_history`
--

INSERT INTO `student_class_history` (`history_id`, `user_id`, `class_id`, `academic_year`, `date_assigned`) VALUES
(181, 68, 4, '2024/2025', '2025-07-12 21:00:00'),
(182, 69, 4, '2024/2025', '2025-07-12 21:00:00'),
(183, 72, 3, '2024/2025', '2025-07-12 21:00:00'),
(184, 74, 3, '2024/2025', '2025-07-12 21:00:00'),
(185, 76, 3, '2024/2025', '2025-07-12 21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `term`
--

CREATE TABLE `term` (
  `term_id` int(255) NOT NULL,
  `term_name` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `term`
--

INSERT INTO `term` (`term_id`, `term_name`, `start_date`, `end_date`) VALUES
(1, 'Midterm Term I', '2025-01-01', '2025-03-31'),
(2, 'End Term I', '2025-04-01', '2025-06-30'),
(3, 'Midterm Term II', '2025-07-01', '2025-09-30'),
(6, 'End Term II', '2026-10-01', '2025-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `class_id` int(255) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `parent_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','form1_teacher','form2_teacher','form3_teacher','form4_teacher') NOT NULL,
  `gender` varchar(8) NOT NULL,
  `stream` enum('science','art') DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `graduation_year` varchar(10) DEFAULT NULL,
  `parent_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `class_id`, `first_name`, `middle_name`, `last_name`, `parent_name`, `username`, `password`, `role`, `gender`, `stream`, `status`, `graduation_year`, `parent_email`) VALUES
(59, NULL, '', '', '', '', 'admin', '$2y$10$8PHBv/xwq/9Fs5u2uE6r9eGBhlEIa8IaCOexg7ORid6W7tD4zYfcK', 'admin', 'Male', NULL, '', NULL, NULL),
(60, 1, 'sakina', 'sharifu', '', '', 'TCH/FORM1/2025/001', '$2y$10$FtvV1FJSrNZOMsmV27Y5LeDuAgP6vsIw1rO/BZmVc2keeq/WSSzJi', 'form1_teacher', 'Female', NULL, '', NULL, NULL),
(61, NULL, '', '', '', '', 'mofinest', '$2y$10$kjQrSRDKtE27tOwWjUQMT.syRkOTKo.ZHrxhx7n.v2wfjkbi85LZy', 'admin', 'Male', NULL, '', NULL, NULL),
(65, 2, 'haji', 'juma', 'swalehe', '', 'TCH/FORM2/2025/001', '$2y$10$Ogd8JVgcnMuAaU3DA1vXaeQtIKOcuGDqVKSLZsT4FZvdIPkNj9GzG', 'form2_teacher', 'Male', NULL, '', NULL, NULL),
(66, 3, 'salima', 'jumanne', 'nurdin', '', 'TCH/FORM3/2025/001', '$2y$10$9SKX/zpZsiYgCVnscVAIR.PWMWPlwILsLS.Nm3/0/jmCrgv6itTXu', 'form3_teacher', 'Male', NULL, '', NULL, NULL),
(67, 4, 'Mohamed', 'issa', 'salumu', '', 'TCH/FORM4/2025/001', '$2y$10$BvuZXD0jrdkUTAedOrU4geQBq45t13ijfJFTlsjGHhsw7gBNLnTyW', 'form4_teacher', 'Male', NULL, '', NULL, NULL),
(68, 4, 'asmah', 'razaro', 'jonson', 'razaro', 'form1/2025/001', '$2y$10$o95CH4pOL5oigm/J6oePoOOaReiJgnO7xqbBzhT6COt5iWdAcSvrS', 'student', 'Female', 'science', 'graduated', '2024/2025', 'mohammedathumani100@gmail.com'),
(69, 4, 'mohamed', 'hemed', 'ramadhani', 'athumani jumanne', 'form2/2025/001', '$2y$10$5jsQTG4k/TN/hYK.OcJsIurJrcrN7qHQ0LmkCmSPoDq529xmwo7qu', 'student', 'Male', 'science', 'graduated', '2024/2025', 'emanuelmakundi2@gmail.com'),
(72, 4, 'arafa', 'salimu', 'richard', 'salimu', 'lumo/2023/001', '$2y$10$R0Vo0KUTQcT1a5Wk6vdddeFowY6y8d7P8bmgWtSJx/AbeiQoH7Omy', 'student', 'Female', 'art', '', NULL, ''),
(74, 4, 'sonara', 'samson', 'robert', 'samson', 'lumo/2023/003', '$2y$10$cRkav/K/9H/hCAGpzAwUN.aG8o672Rx7cZQSDS7/QPwpmiwI9L03u', 'student', 'Male', 'science', '', NULL, ''),
(76, 4, 'mohamed', 'ramadhani', 'ramadhani', 'ramadhani', 'lumo/2023/006', '$2y$10$Sgkx/P2s84cQl4o00sTr.urCD7E1MM2qsQm4sE/2xUvZY4ZC.wTQy', 'student', 'Male', 'art', '', NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `term_id` (`term_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `student_class_history`
--
ALTER TABLE `student_class_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `student_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `term`
--
ALTER TABLE `term`
  ADD PRIMARY KEY (`term_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `result`
--
ALTER TABLE `result`
  MODIFY `result_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `student_class_history`
--
ALTER TABLE `student_class_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `term`
--
ALTER TABLE `term`
  MODIFY `term_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `result`
--
ALTER TABLE `result`
  ADD CONSTRAINT `result_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `result_ibfk_2` FOREIGN KEY (`term_id`) REFERENCES `term` (`term_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `result_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `student_class_history`
--
ALTER TABLE `student_class_history`
  ADD CONSTRAINT `student_class_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `student_class_history_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
