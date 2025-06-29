-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2025 at 12:06 PM
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
-- Database: `new_labreschedule`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`) VALUES
(3, 'ADMIN@1'),
(1, 'admin@gmail.com'),
(2, 's@1');

-- --------------------------------------------------------

--
-- Table structure for table `coordinator`
--

CREATE TABLE `coordinator` (
  `Coordinator_id` int(11) NOT NULL,
  `Coordinator_name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Sub_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coordinator`
--

INSERT INTO `coordinator` (`Coordinator_id`, `Coordinator_name`, `Email`, `Sub_id`) VALUES
(2, 'M.S.M.DE', 'co@1', 'CE404'),
(3, 'n', 'n@2', 'EE202'),
(4, 'cc', '1@2', 'ME303'),
(5, 'cc', '1@4', 'CS101'),
(6, 'cc', '1@5', 'EE202'),
(7, 's', 's@a', 'EE202'),
(8, 'M.S.M.DE', '1@10', 'EE202'),
(9, 'COORDINATOR', 'COORDINATOR@1', 'EE202');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `Dept_id` int(11) NOT NULL,
  `Dept_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`Dept_id`, `Dept_name`) VALUES
(101, 'Computer Engineering Department'),
(102, 'Electrical And Electronic Engineering Department'),
(103, 'Civil Engineering Department'),
(104, 'Mechanical Engineering Department');

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE `lab` (
  `Lab_id` int(11) NOT NULL,
  `Sub_id` varchar(10) DEFAULT NULL,
  `Lab_name` varchar(100) DEFAULT NULL,
  `Lab_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab`
--

INSERT INTO `lab` (`Lab_id`, `Sub_id`, `Lab_name`, `Lab_date`) VALUES
(1, 'CS101', 'Microprocessor Lab', '2025-07-10'),
(2, 'EE202', 'Circuits Lab', '2025-07-12'),
(3, 'ME303', 'Thermal Lab', '2025-07-14'),
(4, 'CE404', 'Concrete Testing Lab', '2025-07-16');

-- --------------------------------------------------------

--
-- Table structure for table `lab_instructor`
--

CREATE TABLE `lab_instructor` (
  `id` int(11) NOT NULL,
  `Instructor_name` varchar(100) DEFAULT NULL,
  `Sub_id` varchar(10) DEFAULT NULL,
  `Lab_id` int(11) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_instructor`
--

INSERT INTO `lab_instructor` (`id`, `Instructor_name`, `Sub_id`, `Lab_id`, `Email`) VALUES
(2, 'e', 'CE404', 1, 'd@1');

-- --------------------------------------------------------

--
-- Table structure for table `reschedule_requests`
--

CREATE TABLE `reschedule_requests` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sub_id` varchar(10) DEFAULT NULL,
  `lab_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `medical_image` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `coordinator_status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `forwarded_to_coordinator` tinyint(1) DEFAULT 0,
  `forwarded_to_instructor` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reschedule_requests`
--

INSERT INTO `reschedule_requests` (`id`, `email`, `sub_id`, `lab_date`, `reason`, `medical_image`, `status`, `submitted_at`, `coordinator_status`, `forwarded_to_coordinator`, `forwarded_to_instructor`) VALUES
(29, 'saumyamadhubashana31@gmail.com', 'EE202', '2025-06-27', 'SICK', '1751190712_medical-form-medical-emegency-form-AlgOsR.jpg', 'Rejected', '2025-06-29 09:51:52', 'Pending', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `Fname` varchar(50) DEFAULT NULL,
  `Lname` varchar(50) DEFAULT NULL,
  `Dept_id` int(11) DEFAULT NULL,
  `Semester_id` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `Group` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `Fname`, `Lname`, `Dept_id`, `Semester_id`, `email`, `Group`) VALUES
(1, 'M.S.M.DE', 'COSTA', 101, 1, 'saumyamadhubashana31@gmail.com', 'cg1'),
(2, 'deco', 'costa', 101, 1, '1@23', 'cg1'),
(3, '1', '1', 102, 1, '1@1', 'ca1'),
(4, 'M.S.M.DE', 'COSTA', 101, 1, 'costa@1', 'ca1');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `Sub_id` varchar(10) NOT NULL,
  `Sub_name` varchar(100) DEFAULT NULL,
  `Dept_id` int(11) DEFAULT NULL,
  `Semester` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`Sub_id`, `Sub_name`, `Dept_id`, `Semester`) VALUES
('CE404', 'Structural Analysis', 103, 4),
('CS101', 'Computer Fundamentals', 101, 1),
('EE202', 'Circuits and Systems', 102, 2),
('ME303', 'Thermodynamics', 104, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Fname` varchar(50) DEFAULT NULL,
  `Lname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('student','admin','coordinator','lab_instructor') DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Fname`, `Lname`, `email`, `role`, `password`) VALUES
(1, 's', 's', 's@s', 'student', '$2y$10$Ffy4rP8JdZoW/lrxYaP7Guzem63QGUZHsKI/D/gNfbTWmE6xkSt/S'),
(2, 'admin', 'a', 'admin@a', 'admin', '$2y$10$LaWS7dkRGPDG6HCqsg7nheGxt1rRuehxIX/NsRK9aNoadeYCs9J8G'),
(3, 'coordinator', 'c', 'c@c', 'coordinator', '$2y$10$emFRhIzoB.dkdbT306aFQOkgaSOvVliX21EH1nT4A7qKbBcIOe/v.'),
(4, 'x', 'x', 'i@i', 'lab_instructor', '$2y$10$SesM37S8lEX9pHUtfRRdGOMwWVzgCe3YSsWMZYpDiE46KmrrbGvIC'),
(5, 'M.S.M.DE', 'COSTA', 'saumyamadhubashana31@gmail.com', 'student', '$2y$10$Y0WDMointkKayd3LINWHwOTTGc4Cj.VaCboIgLd8ozuWtIMbdgiE.'),
(6, 'admin', 'ad', 'admin@gmail.com', 'admin', '$2y$10$NPXMzotO/TnLuoD.kP9cd.UZ7Cra4Akl8VmNaJfjcIacFXBWN24H6'),
(7, 'a', 'a', 'a@a', 'coordinator', '$2y$10$heneqEYxPGARzKoY2d5hSOPNp6Z9DONqPJn4LTY2.qaOXUKx5bGti'),
(8, 'M.S.M.DE', 'COSTA', 'co@1', 'coordinator', '$2y$10$suFyvOOmfpvyHBalZIJL8uXA03M6L4C7Xqe.HkxwiTaS3Ww5wYoh.'),
(9, 'v', 'v', '8@8', 'lab_instructor', '$2y$10$IDSMgtmhjB8jYjI7dj0sue4ZPAzm6P5F/oUnvdHOd42muX/Y5KVkG'),
(10, 'e', 'd', 'd@1', 'lab_instructor', '$2y$10$RrA59GcTPv7awzTU7Tnn2OJn3/j3dSXRLmXuDuL0UhI0qcsRzkbZe'),
(11, 'n', 'n', 'n@2', 'coordinator', '$2y$10$6j.JsCVsxDK76nSaGMLcDO.QSVVgSX6xUiNmRcMsdBisrB5Hk9ejC'),
(12, 'cc', 'cc', '1@2', 'coordinator', '$2y$10$/.xR6lITO1SXXZ2V1QN7refaY5vjmuHbnyz3zD2K0MJ60PSguLWlO'),
(13, 'cc', 'cc', '1@4', 'coordinator', '$2y$10$vof3HsNti78fNjNa0z2tF.XkT36V3ZkLSHfFj3tMYfZ.5HowUWwEe'),
(14, 'cc', 'cc', '1@5', 'coordinator', '$2y$10$AeY3sNRwqiiBNY2qmeNgvewcjTYBk634Uyo/qH/A61PRCyAR4uUgG'),
(15, 's', 's', 's@a', 'coordinator', '$2y$10$wP80UgnC5pz/cjjYZOYjhOG1oMiL6jdaOjEZpb7sD5qKdVhCR1EOG'),
(16, 'deco', 'costa', 'sa@a', 'student', '$2y$10$jUe5L84urWbAcaotrGcSyeRrlt4op/XXU.Pye6UZic14ijgu9tgA2'),
(17, 'deco', 'costa', 's1@1', 'student', '$2y$10$Mc/Ra5e5hSEICYrP5LQEsOH4Iu3.FohPRevyaq4fFHpQ4Fw9gw7RW'),
(18, 'deco', 'costa', '1@23', 'student', '$2y$10$esgb2jJ0Q6YgRd2dQXucy.JA4n.Wao3CU77iW/0vcMZjXf2uKUPdK'),
(19, 's', 's', 's@1', 'admin', '$2y$10$hDBziCjc721HpMN.umTD4..f2UV61Sim6aNF4ISLHtePDkpjohZTe'),
(20, 'M.S.M.DE', 'COSTA', '1@10', 'coordinator', '$2y$10$4CXYNVhmrkxHFleaJyhMVuoMpkxzlK17WSEFVB/Q5ymy9yNzziey.'),
(21, '1', '1', '1@1', 'student', '$2y$10$oVMbefljZXlxrrJgRk1hDOma8wIfIMdtEPVu.JUVp1AQa3L.j.JkS'),
(22, 'M.S.M.DE', 'COSTA', 'costa@1', 'student', '$2y$10$AfHqqaYsCmlNxKzzIHgK/Of4p1CLCCfeQscJPybl6IjlogHKua0NO'),
(23, 'ADMIN', 'ADMIN', 'ADMIN@1', 'admin', '$2y$10$eaU7GaGRd582yWX8BvziketpC1Vm.Tc1ApyOwBBWPKhMRAqF9pX7a'),
(24, 'COORDINATOR', '1', 'COORDINATOR@1', 'coordinator', '$2y$10$dpLBjYuh0nM8TZI9Up8pDuY9EakwsG6NCgBjl1azW40gopLOiLh16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `coordinator`
--
ALTER TABLE `coordinator`
  ADD PRIMARY KEY (`Coordinator_id`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Sub_id` (`Sub_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`Dept_id`);

--
-- Indexes for table `lab`
--
ALTER TABLE `lab`
  ADD PRIMARY KEY (`Lab_id`),
  ADD KEY `Sub_id` (`Sub_id`);

--
-- Indexes for table `lab_instructor`
--
ALTER TABLE `lab_instructor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Sub_id` (`Sub_id`),
  ADD KEY `Lab_id` (`Lab_id`);

--
-- Indexes for table `reschedule_requests`
--
ALTER TABLE `reschedule_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `sub_id` (`sub_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `Dept_id` (`Dept_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`Sub_id`),
  ADD KEY `Dept_id` (`Dept_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coordinator`
--
ALTER TABLE `coordinator`
  MODIFY `Coordinator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lab`
--
ALTER TABLE `lab`
  MODIFY `Lab_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lab_instructor`
--
ALTER TABLE `lab_instructor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reschedule_requests`
--
ALTER TABLE `reschedule_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`);

--
-- Constraints for table `coordinator`
--
ALTER TABLE `coordinator`
  ADD CONSTRAINT `coordinator_ibfk_1` FOREIGN KEY (`Email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `coordinator_ibfk_2` FOREIGN KEY (`Sub_id`) REFERENCES `subject` (`Sub_id`);

--
-- Constraints for table `lab`
--
ALTER TABLE `lab`
  ADD CONSTRAINT `lab_ibfk_1` FOREIGN KEY (`Sub_id`) REFERENCES `subject` (`Sub_id`);

--
-- Constraints for table `lab_instructor`
--
ALTER TABLE `lab_instructor`
  ADD CONSTRAINT `lab_instructor_ibfk_1` FOREIGN KEY (`Sub_id`) REFERENCES `subject` (`Sub_id`),
  ADD CONSTRAINT `lab_instructor_ibfk_2` FOREIGN KEY (`Lab_id`) REFERENCES `lab` (`Lab_id`),
  ADD CONSTRAINT `lab_instructor_ibfk_3` FOREIGN KEY (`Email`) REFERENCES `users` (`email`);

--
-- Constraints for table `reschedule_requests`
--
ALTER TABLE `reschedule_requests`
  ADD CONSTRAINT `reschedule_requests_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `reschedule_requests_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject` (`Sub_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`Dept_id`) REFERENCES `department` (`Dept_id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`Dept_id`) REFERENCES `department` (`Dept_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
