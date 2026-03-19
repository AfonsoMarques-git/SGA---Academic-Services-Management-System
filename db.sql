-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2026 at 11:29 PM
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
-- Database: `sagdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `id` int(11) NOT NULL,
  `label` varchar(20) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_years`
--

INSERT INTO `academic_years` (`id`, `label`, `is_active`, `created_at`, `comment`) VALUES
(1, '2024/2025', 1, '2026-03-13 17:36:16', NULL),
(2, '2025/2026', 1, '2026-03-13 17:36:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `entity_type` varchar(100) NOT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `entity_type`, `entity_id`, `action`, `description`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 4, 'student_record', 2, 'submitted', 'Ficha submetida por Maria Santos', NULL, NULL, NULL, NULL, '2026-03-13 17:36:26'),
(2, 3, 'enrollment_request', 1, 'created', 'Pedido de matrícula criado', NULL, NULL, NULL, NULL, '2026-03-13 17:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `code`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'CSI', 'Computação e Sistemas de Informação', 'Licenciatura em Computação e Sistemas de Informação', 1, '2026-03-13 17:36:26', '2026-03-13 17:36:26'),
(2, 'GGE', 'Gestão', 'Licenciatura em Gestão', 1, '2026-03-13 17:36:26', '2026-03-13 17:36:26'),
(3, 'LET', 'Letras', 'Licenciatura em Letras', 1, '2026-03-13 17:36:26', '2026-03-13 17:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `course_units`
--

CREATE TABLE `course_units` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ects` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_units`
--

INSERT INTO `course_units` (`id`, `code`, `name`, `ects`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ALG', 'Álgebra Linear', 6, 'Matrizes, espaços vetoriais', 1, '2026-03-13 17:36:26', '2026-03-13 17:36:26'),
(2, 'PROG', 'Programação I', 6, 'Programação em Python', 1, '2026-03-13 17:36:26', '2026-03-13 17:36:26'),
(3, 'BD', 'Bases de Dados', 6, 'SQL e Modelação de Dados', 1, '2026-03-13 17:36:26', '2026-03-13 17:36:26'),
(4, 'WEB', 'Desenvolvimento Web', 6, 'HTML, CSS, JavaScript, PHP', 1, '2026-03-13 17:36:26', '2026-03-13 17:36:26'),
(5, 'ECON', 'Economia', 6, 'Microeconomia e Macroeconomia', 1, '2026-03-13 17:36:26', '2026-03-13 17:36:26'),
(6, 'CONT', 'Contabilidade', 6, 'Contabilidade Financeira', 1, '2026-03-13 17:36:26', '2026-03-13 17:36:26'),
(7, 'PSI', 'Programação de Sistemas Informáticos', 6, 'Disciplina focada em programação de sistemas informáticos de complexidade alta e de elevada segurança.', 1, '2026-03-19 20:41:08', '2026-03-19 20:41:08');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_requests`
--

CREATE TABLE `enrollment_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `student_record_id` int(11) DEFAULT NULL,
  `request_type` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pendente',
  `notes_by_student` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment_requests`
--

INSERT INTO `enrollment_requests` (`id`, `user_id`, `course_id`, `student_record_id`, `request_type`, `status`, `notes_by_student`, `reviewed_by`, `reviewed_at`, `review_notes`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 2, 'inscricao', 'aprovado', 'Desejo inscrever-me em CSI', 3, '2026-03-19 21:30:19', NULL, '2026-03-13 17:36:26', '2026-03-19 21:30:19');

-- --------------------------------------------------------

--
-- Table structure for table `grade_sheets`
--

CREATE TABLE `grade_sheets` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `season` varchar(30) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) DEFAULT 'em_preparacao',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade_sheets`
--

INSERT INTO `grade_sheets` (`id`, `unit_id`, `academic_year_id`, `season`, `created_by`, `created_at`, `updated_at`, `status`, `notes`) VALUES
(1, 1, 1, 'normal', 3, '2026-03-13 17:36:26', '2026-03-13 17:36:26', 'em_preparacao', NULL),
(2, 2, 1, 'normal', 3, '2026-03-13 17:36:26', '2026-03-13 17:36:26', 'em_preparacao', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grade_sheet_students`
--

CREATE TABLE `grade_sheet_students` (
  `id` int(11) NOT NULL,
  `grade_sheet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `final_grade` decimal(5,2) DEFAULT NULL,
  `grade_status` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade_sheet_students`
--

INSERT INTO `grade_sheet_students` (`id`, `grade_sheet_id`, `user_id`, `final_grade`, `grade_status`, `notes`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 16.50, 'aprovado', 'Bom desempenho', NULL, '2026-03-13 17:36:26', '2026-03-13 17:36:26'),
(2, 2, 2, 14.00, 'aprovado', 'Progressão satisfatória', NULL, '2026-03-13 17:36:26', '2026-03-13 17:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nome_grupo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grupos`
--

INSERT INTO `grupos` (`id`, `nome_grupo`) VALUES
(1, 'admin'),
(2, 'gestor'),
(3, 'aluno'),
(4, 'funcionario');

-- --------------------------------------------------------

--
-- Table structure for table `request_messages`
--

CREATE TABLE `request_messages` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_messages`
--

INSERT INTO `request_messages` (`id`, `thread_id`, `sender_id`, `message_text`, `created_at`) VALUES
(1, 1, 2, 'Olá João!', '2026-03-19 21:37:56'),
(4, 2, 3, 'Teste funcionario para gestor', '2026-03-19 21:43:45'),
(5, 2, 4, 'Teste gestor para funcionario', '2026-03-19 21:43:45'),
(7, 3, 1, 'Olá Carlos!', '2026-03-19 21:50:10'),
(9, 1, 1, 'Olá Maria!\r\nComo estás ?', '2026-03-19 21:52:45'),
(10, 1, 2, 'Estou bem e tu lindão ?', '2026-03-19 21:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `request_threads`
--

CREATE TABLE `request_threads` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_threads`
--

INSERT INTO `request_threads` (`id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-03-19 21:37:56', '2026-03-19 21:53:18'),
(2, 3, '2026-03-19 21:43:45', '2026-03-19 21:43:45'),
(3, 1, '2026-03-19 21:50:10', '2026-03-19 21:50:10');

-- --------------------------------------------------------

--
-- Table structure for table `request_thread_participants`
--

CREATE TABLE `request_thread_participants` (
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_thread_participants`
--

INSERT INTO `request_thread_participants` (`thread_id`, `user_id`, `joined_at`, `last_read_at`) VALUES
(1, 1, '2026-03-19 21:37:56', NULL),
(1, 2, '2026-03-19 21:37:56', NULL),
(2, 3, '2026-03-19 21:43:45', NULL),
(2, 4, '2026-03-19 21:43:45', NULL),
(3, 1, '2026-03-19 21:50:10', NULL),
(3, 3, '2026-03-19 21:50:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'aluno', 'Estudante', '2026-03-13 17:36:16'),
(2, 'funcionario', 'Funcionário dos Serviços Académicos', '2026-03-13 17:36:16'),
(3, 'gestor', 'Gestor Pedagógico', '2026-03-13 17:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `student_records`
--

CREATE TABLE `student_records` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `national_id` varchar(20) DEFAULT NULL,
  `tax_number` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_contact` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `photo_path` varchar(500) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'rascunho',
  `submitted_at` datetime DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_records`
--

INSERT INTO `student_records` (`id`, `user_id`, `course_id`, `full_name`, `birth_date`, `national_id`, `tax_number`, `phone`, `email_contact`, `address`, `city`, `postal_code`, `photo_path`, `status`, `submitted_at`, `reviewed_by`, `reviewed_at`, `review_notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'João Silva', '2003-05-15', '12345678A', '123456789', '961234567', 'joao.silva@email.com', 'Rua da Liberdade, 42', 'Lisboa', '1250-100', NULL, 'rejeitada', NULL, 3, '2026-03-19 21:19:57', NULL, '2026-03-13 17:36:26', '2026-03-19 21:23:04'),
(2, 2, 1, 'Maria João Santos', '2002-08-22', '87654321B', '987654321', '919876543', 'maria.santos@email.com', 'Avenida Central, 123', 'Porto', '4000-100', NULL, 'aprovada', '2026-03-13 17:36:26', 4, '2026-03-19 21:25:24', NULL, '2026-03-13 17:36:26', '2026-03-19 21:25:24');

-- --------------------------------------------------------

--
-- Table structure for table `study_plans`
--

CREATE TABLE `study_plans` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `academic_year_number` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `study_plans`
--

INSERT INTO `study_plans` (`id`, `course_id`, `unit_id`, `academic_year_number`, `semester`, `is_active`, `created_at`) VALUES
(1, 1, 1, 1, 1, 1, '2026-03-13 17:36:26'),
(2, 1, 2, 1, 1, 1, '2026-03-13 17:36:26'),
(3, 1, 3, 1, 1, 1, '2026-03-13 17:36:26'),
(4, 1, 4, 1, 2, 1, '2026-03-13 17:36:26'),
(5, 2, 5, 1, 1, 1, '2026-03-13 17:36:26'),
(6, 2, 6, 1, 2, 1, '2026-03-13 17:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `full_name`, `email`, `username`, `password_hash`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'João Silva', 'joao.silva@email.com', 'joao_silva', '$2y$10$9fVwusDDEiMTjH0AilfwiOHxffopUkTYqqvlRUhmCZy83xR6EvKau', 1, '2026-03-19 22:27:17', '2026-03-13 17:36:26', '2026-03-19 22:27:17'),
(2, 1, 'Maria Santos', 'maria.santos@email.com', 'maria_santos', '$2y$10$9fVwusDDEiMTjH0AilfwiOHxffopUkTYqqvlRUhmCZy83xR6EvKau', 1, '2026-03-19 21:52:58', '2026-03-13 17:36:26', '2026-03-19 21:52:58'),
(3, 2, 'Carlos Oliveira', 'carlos.oliveira@univ.edu', 'carlos_funcionario', '$2y$10$9fVwusDDEiMTjH0AilfwiOHxffopUkTYqqvlRUhmCZy83xR6EvKau', 1, '2026-03-19 21:30:12', '2026-03-13 17:36:26', '2026-03-19 21:30:12'),
(4, 3, 'Ana Costa', 'ana.costa@univ.edu', 'ana_gestor', '$2y$10$9fVwusDDEiMTjH0AilfwiOHxffopUkTYqqvlRUhmCZy83xR6EvKau', 1, '2026-03-19 21:47:53', '2026-03-13 17:36:26', '2026-03-19 21:47:53');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pending_enrollments`
-- (See below for the actual view)
--
CREATE TABLE `view_pending_enrollments` (
`id` int(11)
,`user_id` int(11)
,`full_name` varchar(255)
,`email` varchar(255)
,`course_name` varchar(255)
,`record_id` int(11)
,`record_status` varchar(20)
,`status` varchar(20)
,`created_at` timestamp
,`notes_by_student` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_students_records`
-- (See below for the actual view)
--
CREATE TABLE `view_students_records` (
`id` int(11)
,`full_name` varchar(255)
,`email` varchar(255)
,`username` varchar(100)
,`record_id` int(11)
,`course_id` int(11)
,`course_name` varchar(255)
,`status` varchar(20)
,`submitted_at` datetime
,`reviewed_at` datetime
,`photo_path` varchar(500)
);

-- --------------------------------------------------------

--
-- Structure for view `view_pending_enrollments`
--
DROP TABLE IF EXISTS `view_pending_enrollments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pending_enrollments`  AS SELECT `er`.`id` AS `id`, `er`.`user_id` AS `user_id`, `u`.`full_name` AS `full_name`, `u`.`email` AS `email`, `c`.`name` AS `course_name`, `sr`.`id` AS `record_id`, `sr`.`status` AS `record_status`, `er`.`status` AS `status`, `er`.`created_at` AS `created_at`, `er`.`notes_by_student` AS `notes_by_student` FROM (((`enrollment_requests` `er` join `users` `u` on(`er`.`user_id` = `u`.`id`)) join `courses` `c` on(`er`.`course_id` = `c`.`id`)) left join `student_records` `sr` on(`er`.`student_record_id` = `sr`.`id`)) WHERE `er`.`status` = 'pendente' ORDER BY `er`.`created_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_students_records`
--
DROP TABLE IF EXISTS `view_students_records`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_students_records`  AS SELECT `u`.`id` AS `id`, `u`.`full_name` AS `full_name`, `u`.`email` AS `email`, `u`.`username` AS `username`, `sr`.`id` AS `record_id`, `sr`.`course_id` AS `course_id`, `c`.`name` AS `course_name`, `sr`.`status` AS `status`, `sr`.`submitted_at` AS `submitted_at`, `sr`.`reviewed_at` AS `reviewed_at`, `sr`.`photo_path` AS `photo_path` FROM ((`users` `u` left join `student_records` `sr` on(`u`.`id` = `sr`.`user_id`)) left join `courses` `c` on(`sr`.`course_id` = `c`.`id`)) WHERE `u`.`role_id` = 1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `label` (`label`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `course_units`
--
ALTER TABLE `course_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `enrollment_requests`
--
ALTER TABLE `enrollment_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_record_id` (`student_record_id`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_course` (`course_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `grade_sheets`
--
ALTER TABLE `grade_sheets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_grade_sheet` (`unit_id`,`academic_year_id`,`season`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_unit` (`unit_id`),
  ADD KEY `idx_academic_year` (`academic_year_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `grade_sheet_students`
--
ALTER TABLE `grade_sheet_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_grade` (`grade_sheet_id`,`user_id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_grade_sheet` (`grade_sheet_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_messages`
--
ALTER TABLE `request_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `idx_thread` (`thread_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `request_threads`
--
ALTER TABLE `request_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `request_thread_participants`
--
ALTER TABLE `request_thread_participants`
  ADD PRIMARY KEY (`thread_id`,`user_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `student_records`
--
ALTER TABLE `student_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_active_record` (`user_id`,`status`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_course` (`course_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `study_plans`
--
ALTER TABLE `study_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_study_plan` (`course_id`,`unit_id`,`academic_year_number`,`semester`),
  ADD KEY `idx_course` (`course_id`),
  ADD KEY `idx_unit` (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_role` (`role_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `course_units`
--
ALTER TABLE `course_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `enrollment_requests`
--
ALTER TABLE `enrollment_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `grade_sheets`
--
ALTER TABLE `grade_sheets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `grade_sheet_students`
--
ALTER TABLE `grade_sheet_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request_messages`
--
ALTER TABLE `request_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `request_threads`
--
ALTER TABLE `request_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_records`
--
ALTER TABLE `student_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `study_plans`
--
ALTER TABLE `study_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `enrollment_requests`
--
ALTER TABLE `enrollment_requests`
  ADD CONSTRAINT `enrollment_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `enrollment_requests_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `enrollment_requests_ibfk_3` FOREIGN KEY (`student_record_id`) REFERENCES `student_records` (`id`),
  ADD CONSTRAINT `enrollment_requests_ibfk_4` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `grade_sheets`
--
ALTER TABLE `grade_sheets`
  ADD CONSTRAINT `grade_sheets_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `course_units` (`id`),
  ADD CONSTRAINT `grade_sheets_ibfk_2` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`),
  ADD CONSTRAINT `grade_sheets_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `grade_sheet_students`
--
ALTER TABLE `grade_sheet_students`
  ADD CONSTRAINT `grade_sheet_students_ibfk_1` FOREIGN KEY (`grade_sheet_id`) REFERENCES `grade_sheets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grade_sheet_students_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `grade_sheet_students_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `request_messages`
--
ALTER TABLE `request_messages`
  ADD CONSTRAINT `request_messages_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `request_threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `request_threads`
--
ALTER TABLE `request_threads`
  ADD CONSTRAINT `request_threads_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `request_thread_participants`
--
ALTER TABLE `request_thread_participants`
  ADD CONSTRAINT `request_thread_participants_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `request_threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_thread_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `student_records`
--
ALTER TABLE `student_records`
  ADD CONSTRAINT `student_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_records_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `student_records_ibfk_3` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `study_plans`
--
ALTER TABLE `study_plans`
  ADD CONSTRAINT `study_plans_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `study_plans_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `course_units` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
