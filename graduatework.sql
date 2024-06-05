-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 05, 2024 at 04:06 PM
-- Server version: 8.0.24
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `graduatework`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_privileges`
--

CREATE TABLE `admin_privileges` (
  `id` bigint UNSIGNED NOT NULL,
  `userAdminId` bigint UNSIGNED NOT NULL,
  `privilege` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_privileges`
--

INSERT INTO `admin_privileges` (`id`, `userAdminId`, `privilege`, `created_at`, `updated_at`) VALUES
(1, 1, 'SuperAdmin', NULL, NULL),
(2, 2, 'Operator', NULL, NULL),
(3, 3, 'GroupManager', NULL, NULL),
(4, 4, 'ScheduleCoordinator', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auditoria`
--

CREATE TABLE `auditoria` (
  `id` bigint UNSIGNED NOT NULL,
  `number` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auditoria`
--

INSERT INTO `auditoria` (`id`, `number`) VALUES
(1, 2117),
(2, 3213);

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eventType` tinyint(1) NOT NULL,
  `groupId` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupId` bigint UNSIGNED NOT NULL,
  `userTeacherId` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_materials`
--

CREATE TABLE `classroom_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_material_files`
--

CREATE TABLE `classroom_material_files` (
  `id` bigint UNSIGNED NOT NULL,
  `classroomMaterialId` bigint UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_material_links`
--

CREATE TABLE `classroom_material_links` (
  `id` bigint UNSIGNED NOT NULL,
  `classroomMaterialId` bigint UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_tasks`
--

CREATE TABLE `classroom_tasks` (
  `id` bigint UNSIGNED NOT NULL,
  `classroomId` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodOfExecution` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_task_answers`
--

CREATE TABLE `classroom_task_answers` (
  `id` bigint UNSIGNED NOT NULL,
  `classroomTaskId` bigint UNSIGNED NOT NULL,
  `userStudentId` bigint UNSIGNED NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_task_answer_files`
--

CREATE TABLE `classroom_task_answer_files` (
  `id` bigint UNSIGNED NOT NULL,
  `classroomTaskAnswerId` bigint UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_task_answer_links`
--

CREATE TABLE `classroom_task_answer_links` (
  `id` bigint UNSIGNED NOT NULL,
  `classroomTaskAnswerId` bigint UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_task_comments`
--

CREATE TABLE `classroom_task_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `classroomTaskId` bigint UNSIGNED NOT NULL,
  `userStudentId` bigint UNSIGNED NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_task_files`
--

CREATE TABLE `classroom_task_files` (
  `id` bigint UNSIGNED NOT NULL,
  `classroomTaskId` bigint UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_task_links`
--

CREATE TABLE `classroom_task_links` (
  `id` bigint UNSIGNED NOT NULL,
  `classroomTaskId` bigint UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_assessments`
--

CREATE TABLE `class_assessments` (
  `id` bigint UNSIGNED NOT NULL,
  `groupScheduleClassId` bigint UNSIGNED NOT NULL,
  `assessment` smallint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_attendances`
--

CREATE TABLE `class_attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `groupScheduleClassId` bigint UNSIGNED NOT NULL,
  `attendanceStatusId` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_attendance_statuses`
--

CREATE TABLE `class_attendance_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `title`) VALUES
(1, 'Разработчик ПО'),
(2, 'Техник информационных систем'),
(3, 'Техник информационной безопасности');

-- --------------------------------------------------------

--
-- Table structure for table `event_selected_student_alls`
--

CREATE TABLE `event_selected_student_alls` (
  `id` bigint UNSIGNED NOT NULL,
  `calendarEventId` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_selected_student_children`
--

CREATE TABLE `event_selected_student_children` (
  `id` bigint UNSIGNED NOT NULL,
  `calendarEventId` bigint UNSIGNED NOT NULL,
  `userStudentId` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_selected_student_groups`
--

CREATE TABLE `event_selected_student_groups` (
  `id` bigint UNSIGNED NOT NULL,
  `calendarEventId` bigint UNSIGNED NOT NULL,
  `group` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departmentId` bigint UNSIGNED DEFAULT NULL,
  `userTeacherId` bigint UNSIGNED DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `title`, `departmentId`, `userTeacherId`, `color`, `created_at`, `updated_at`) VALUES
(1, 'П-21-57к', 1, 2, '008000', '2024-06-04 00:50:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group_schedule_classes`
--

CREATE TABLE `group_schedule_classes` (
  `id` bigint UNSIGNED NOT NULL,
  `groupId` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `subjectId` bigint UNSIGNED DEFAULT NULL,
  `subgroup` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_schedule_classes`
--

INSERT INTO `group_schedule_classes` (`id`, `groupId`, `date`, `subjectId`, `subgroup`, `number`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-12-31 19:00:00', 1, '', 2, '2024-06-04 00:50:23', NULL),
(2, 1, '2023-12-31 19:00:00', 1, '', 3, '2024-06-04 00:50:23', NULL),
(3, 1, '2023-12-31 19:00:00', 1, '', 4, '2024-06-04 00:50:23', NULL),
(4, 1, '2023-12-31 19:00:00', 1, NULL, 2, '2024-06-05 04:17:53', '2024-06-05 04:17:53'),
(12, 1, '2024-06-06 19:00:00', 1, NULL, 2, '2024-06-05 04:25:30', '2024-06-05 04:25:30');

-- --------------------------------------------------------

--
-- Table structure for table `group_schedule_class_replacements`
--

CREATE TABLE `group_schedule_class_replacements` (
  `id` bigint UNSIGNED NOT NULL,
  `userTeacherId` bigint UNSIGNED DEFAULT NULL,
  `groupScheduleClassId` bigint UNSIGNED NOT NULL,
  `subgroup` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_schedule_class_replacement_rq`
--

CREATE TABLE `group_schedule_class_replacement_rq` (
  `id` bigint UNSIGNED NOT NULL,
  `userTeacherId` bigint UNSIGNED DEFAULT NULL,
  `groupScheduleClassId` bigint UNSIGNED NOT NULL,
  `subgroup` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_subjects`
--

CREATE TABLE `group_subjects` (
  `id` bigint UNSIGNED NOT NULL,
  `groupId` bigint UNSIGNED DEFAULT NULL,
  `teacherSubjectId` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_subjects`
--

INSERT INTO `group_subjects` (`id`, `groupId`, `teacherSubjectId`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-06-04 00:50:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2024_04_30_000010_create_subjects_table', 1),
(4, '2024_04_30_000050_create_auditoria_table', 1),
(5, '2024_04_30_033210_create_user_admins_table', 1),
(6, '2024_04_30_033258_create_user_teachers_table', 1),
(7, '2024_04_30_033300_create_user_teacher_subjects_table', 1),
(8, '2024_04_30_095040_create_admin_privileges_table', 1),
(9, '2024_04_30_102903_create_departments_table', 1),
(10, '2024_04_30_102909_create_groups_table', 1),
(11, '2024_04_30_102999_create_group_subjects_table', 1),
(12, '2024_04_30_112000_create_user_students_table', 1),
(13, '2024_04_30_112738_create_group_schedule_classes_table', 1),
(14, '2024_04_30_112930_create_group_schedule_class_replacement_rq_table', 1),
(15, '2024_04_30_112938_create_group_schedule_class_replacements_table', 1),
(16, '2024_05_01_085429_create_class_assessments_table', 1),
(17, '2024_05_01_090100_create_class_attendance_statuses_table', 1),
(18, '2024_05_01_090109_create_class_attendances_table', 1),
(19, '2024_05_01_111825_create_calendar_events_table', 1),
(20, '2024_05_01_121002_create_event_selected_student_alls_table', 1),
(21, '2024_05_01_121008_create_event_selected_student_groups_table', 1),
(22, '2024_05_01_121014_create_event_selected_student_children_table', 1),
(23, '2024_05_02_080808_create_classrooms_table', 1),
(24, '2024_05_02_081322_create_classroom_tasks_table', 1),
(25, '2024_05_02_081746_create_classroom_task_files_table', 1),
(26, '2024_05_02_081750_create_classroom_task_links_table', 1),
(27, '2024_05_02_082503_create_classroom_materials_table', 1),
(28, '2024_05_02_082540_create_classroom_material_files_table', 1),
(29, '2024_05_02_082553_create_classroom_material_links_table', 1),
(30, '2024_05_02_083607_create_classroom_task_answers_table', 1),
(31, '2024_05_02_083945_create_classroom_task_answer_files_table', 1),
(32, '2024_05_02_084140_create_classroom_task_answer_links_table', 1),
(33, '2024_05_02_133449_create_classroom_task_comments_table', 1),
(34, '2024_05_07_044242_create_user_teacher_tokens_table', 1),
(35, '2024_05_07_044316_create_user_student_tokens_table', 1),
(36, '2024_05_07_044323_create_user_admin_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `title`) VALUES
(1, 'математика'),
(2, 'Основа права'),
(3, 'Физкультура'),
(4, 'Физика'),
(5, 'Основа предпринимательства'),
(6, 'Информатика'),
(7, 'Основы Frontend'),
(8, 'Разработка мобильных приложений');

-- --------------------------------------------------------

--
-- Table structure for table `user_admins`
--

CREATE TABLE `user_admins` (
  `id` bigint UNSIGNED NOT NULL,
  `login` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_admins`
--

INSERT INTO `user_admins` (`id`, `login`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$YSLsqiFBjo/hWPSqASv.se.e5TZx47d/OKQVghoeh8PozYrImH47O', NULL, '2024-06-04 00:50:23', NULL),
(2, 'operator', '$2y$10$RXir4kPTbj5pkau8vC0vG.4vh5ir7psew688JMmqgp3liKkKjJelC', NULL, '2024-06-04 00:50:23', NULL),
(3, 'group manager', '$2y$10$TlFsJp.kKiLtGCd0Y1GCfeBUxgTypYMAvBV8o/qT5vFSbDQ4m0r9e', NULL, '2024-06-04 00:50:23', NULL),
(4, 'schedule user', '$2y$10$1cyI3uCZa9spSLmBFd.qP.phpnVppqiYUFCT6siRrTRhijikl4WhW', NULL, '2024-06-04 00:50:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_admin_tokens`
--

CREATE TABLE `user_admin_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentId` bigint UNSIGNED NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_admin_tokens`
--

INSERT INTO `user_admin_tokens` (`id`, `token`, `parentId`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'ZVDjqE3osDNyG5GdVFMkYJu7vIgZRKmc', 1, NULL, '2024-06-04 00:50:31', '2024-06-04 00:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_students`
--

CREATE TABLE `user_students` (
  `id` bigint UNSIGNED NOT NULL,
  `login` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fio` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupId` bigint UNSIGNED DEFAULT NULL,
  `subgroup` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_students`
--

INSERT INTO `user_students` (`id`, `login`, `password`, `fio`, `groupId`, `subgroup`, `created_at`, `updated_at`) VALUES
(2, 'kishibaevNurzhanErkeshovich', '$2y$10$zEZPFQGlx8EzFw1WlJeIQOrk3po6BC3BDGmbT62pP4.3HjNVy2Z1u', 'Кишибаев Нуржан Еркешович', 1, 'A', '2024-06-04 00:50:23', '2024-06-05 05:10:31'),
(3, 'kimBogdanDanilovich', '$2y$10$mXx.NMgUl4MmQHJ03Irn8.JtomG8Gf4KJTR0jnYW3o65M3QYq6eKG', 'Ким Богдан Данилович', 1, 'A', '2024-06-04 00:50:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_student_tokens`
--

CREATE TABLE `user_student_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentId` bigint UNSIGNED NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_student_tokens`
--

INSERT INTO `user_student_tokens` (`id`, `token`, `parentId`, `expires_at`, `created_at`, `updated_at`) VALUES
(4, 'n1cF2XTUnzFvHUGrDwjaJlA3HxPQBRBV', 2, NULL, '2024-06-05 05:10:31', '2024-06-05 05:10:31'),
(6, '3lYWbzgfJXdGyr7E7xhk8X3sKhDqyLsB', 2, NULL, '2024-06-05 05:16:38', '2024-06-05 05:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_teachers`
--

CREATE TABLE `user_teachers` (
  `id` bigint UNSIGNED NOT NULL,
  `login` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fio` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditoriaId` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_teachers`
--

INSERT INTO `user_teachers` (`id`, `login`, `password`, `fio`, `auditoriaId`, `created_at`, `updated_at`) VALUES
(1, 'popovDenisValentinovich', '$2y$10$VPGTnP2Qq2VaMzItyNgayeJQHf.LkBj6yGsiaZxRSWwfoRZYuVQXu', 'Попов Денис Валентинович', 2, '2024-06-04 00:50:23', '2024-06-05 06:57:14'),
(2, 'gulnarNurkhamitovna', '$2y$10$R0A9puI/TzKpsZPcsyuHEeZRD7DEZ19Ku4j1As2BH0z.drrQzsvdO', 'Гульнар Нурхамитовна', 1, '2024-06-04 00:50:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_teacher_subjects`
--

CREATE TABLE `user_teacher_subjects` (
  `id` bigint UNSIGNED NOT NULL,
  `userTeacherId` bigint UNSIGNED NOT NULL,
  `subjectId` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_teacher_subjects`
--

INSERT INTO `user_teacher_subjects` (`id`, `userTeacherId`, `subjectId`, `created_at`, `updated_at`) VALUES
(1, 1, 7, '2024-06-04 00:50:23', NULL),
(2, 1, 8, '2024-06-04 00:50:23', NULL),
(3, 2, 4, '2024-06-04 00:50:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_teacher_tokens`
--

CREATE TABLE `user_teacher_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentId` bigint UNSIGNED NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_privileges`
--
ALTER TABLE `admin_privileges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_privileges_useradminid_foreign` (`userAdminId`);

--
-- Indexes for table `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendar_events_groupid_foreign` (`groupId`);

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classrooms_groupid_foreign` (`groupId`),
  ADD KEY `classrooms_userteacherid_foreign` (`userTeacherId`);

--
-- Indexes for table `classroom_materials`
--
ALTER TABLE `classroom_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classroom_material_files`
--
ALTER TABLE `classroom_material_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_material_files_classroommaterialid_foreign` (`classroomMaterialId`);

--
-- Indexes for table `classroom_material_links`
--
ALTER TABLE `classroom_material_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_material_links_classroommaterialid_foreign` (`classroomMaterialId`);

--
-- Indexes for table `classroom_tasks`
--
ALTER TABLE `classroom_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_tasks_classroomid_foreign` (`classroomId`);

--
-- Indexes for table `classroom_task_answers`
--
ALTER TABLE `classroom_task_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_task_answers_classroomtaskid_foreign` (`classroomTaskId`),
  ADD KEY `classroom_task_answers_userstudentid_foreign` (`userStudentId`);

--
-- Indexes for table `classroom_task_answer_files`
--
ALTER TABLE `classroom_task_answer_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_task_answer_files_classroomtaskanswerid_foreign` (`classroomTaskAnswerId`);

--
-- Indexes for table `classroom_task_answer_links`
--
ALTER TABLE `classroom_task_answer_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_task_answer_links_classroomtaskanswerid_foreign` (`classroomTaskAnswerId`);

--
-- Indexes for table `classroom_task_comments`
--
ALTER TABLE `classroom_task_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_task_comments_classroomtaskid_foreign` (`classroomTaskId`),
  ADD KEY `classroom_task_comments_userstudentid_foreign` (`userStudentId`);

--
-- Indexes for table `classroom_task_files`
--
ALTER TABLE `classroom_task_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_task_files_classroomtaskid_foreign` (`classroomTaskId`);

--
-- Indexes for table `classroom_task_links`
--
ALTER TABLE `classroom_task_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_task_links_classroomtaskid_foreign` (`classroomTaskId`);

--
-- Indexes for table `class_assessments`
--
ALTER TABLE `class_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_assessments_groupscheduleclassid_foreign` (`groupScheduleClassId`);

--
-- Indexes for table `class_attendances`
--
ALTER TABLE `class_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_attendances_groupscheduleclassid_foreign` (`groupScheduleClassId`),
  ADD KEY `class_attendances_attendancestatusid_foreign` (`attendanceStatusId`);

--
-- Indexes for table `class_attendance_statuses`
--
ALTER TABLE `class_attendance_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_selected_student_alls`
--
ALTER TABLE `event_selected_student_alls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_selected_student_alls_calendareventid_foreign` (`calendarEventId`);

--
-- Indexes for table `event_selected_student_children`
--
ALTER TABLE `event_selected_student_children`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_selected_student_children_calendareventid_foreign` (`calendarEventId`),
  ADD KEY `event_selected_student_children_userstudentid_foreign` (`userStudentId`);

--
-- Indexes for table `event_selected_student_groups`
--
ALTER TABLE `event_selected_student_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_selected_student_groups_calendareventid_foreign` (`calendarEventId`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groups_departmentid_foreign` (`departmentId`),
  ADD KEY `groups_userteacherid_foreign` (`userTeacherId`);

--
-- Indexes for table `group_schedule_classes`
--
ALTER TABLE `group_schedule_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_schedule_classes_groupid_foreign` (`groupId`),
  ADD KEY `group_schedule_classes_subjectid_foreign` (`subjectId`);

--
-- Indexes for table `group_schedule_class_replacements`
--
ALTER TABLE `group_schedule_class_replacements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_schedule_class_replacements_userteacherid_foreign` (`userTeacherId`),
  ADD KEY `group_schedule_class_replacements_groupscheduleclassid_foreign` (`groupScheduleClassId`);

--
-- Indexes for table `group_schedule_class_replacement_rq`
--
ALTER TABLE `group_schedule_class_replacement_rq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_schedule_class_replacement_rq_userteacherid_foreign` (`userTeacherId`),
  ADD KEY `group_schedule_class_replacement_rq_groupscheduleclassid_foreign` (`groupScheduleClassId`);

--
-- Indexes for table `group_subjects`
--
ALTER TABLE `group_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_subjects_groupid_foreign` (`groupId`),
  ADD KEY `group_subjects_teachersubjectid_foreign` (`teacherSubjectId`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_admins`
--
ALTER TABLE `user_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_admins_login_unique` (`login`);

--
-- Indexes for table `user_admin_tokens`
--
ALTER TABLE `user_admin_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_admin_tokens_parentid_foreign` (`parentId`);

--
-- Indexes for table `user_students`
--
ALTER TABLE `user_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_students_login_unique` (`login`),
  ADD UNIQUE KEY `user_students_fio_unique` (`fio`),
  ADD KEY `user_students_groupid_foreign` (`groupId`);

--
-- Indexes for table `user_student_tokens`
--
ALTER TABLE `user_student_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_student_tokens_parentid_foreign` (`parentId`);

--
-- Indexes for table `user_teachers`
--
ALTER TABLE `user_teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_teachers_login_unique` (`login`),
  ADD UNIQUE KEY `user_teachers_fio_unique` (`fio`),
  ADD KEY `user_teachers_auditoriaid_foreign` (`auditoriaId`);

--
-- Indexes for table `user_teacher_subjects`
--
ALTER TABLE `user_teacher_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_teacher_subjects_userteacherid_foreign` (`userTeacherId`),
  ADD KEY `user_teacher_subjects_subjectid_foreign` (`subjectId`);

--
-- Indexes for table `user_teacher_tokens`
--
ALTER TABLE `user_teacher_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_teacher_tokens_parentid_foreign` (`parentId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_privileges`
--
ALTER TABLE `admin_privileges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_materials`
--
ALTER TABLE `classroom_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_material_files`
--
ALTER TABLE `classroom_material_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_material_links`
--
ALTER TABLE `classroom_material_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_tasks`
--
ALTER TABLE `classroom_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_task_answers`
--
ALTER TABLE `classroom_task_answers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_task_answer_files`
--
ALTER TABLE `classroom_task_answer_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_task_answer_links`
--
ALTER TABLE `classroom_task_answer_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_task_comments`
--
ALTER TABLE `classroom_task_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_task_files`
--
ALTER TABLE `classroom_task_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classroom_task_links`
--
ALTER TABLE `classroom_task_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_assessments`
--
ALTER TABLE `class_assessments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_attendances`
--
ALTER TABLE `class_attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_attendance_statuses`
--
ALTER TABLE `class_attendance_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_selected_student_alls`
--
ALTER TABLE `event_selected_student_alls`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_selected_student_children`
--
ALTER TABLE `event_selected_student_children`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_selected_student_groups`
--
ALTER TABLE `event_selected_student_groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `group_schedule_classes`
--
ALTER TABLE `group_schedule_classes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `group_schedule_class_replacements`
--
ALTER TABLE `group_schedule_class_replacements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_schedule_class_replacement_rq`
--
ALTER TABLE `group_schedule_class_replacement_rq`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_subjects`
--
ALTER TABLE `group_subjects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_admins`
--
ALTER TABLE `user_admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_admin_tokens`
--
ALTER TABLE `user_admin_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_students`
--
ALTER TABLE `user_students`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_student_tokens`
--
ALTER TABLE `user_student_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_teachers`
--
ALTER TABLE `user_teachers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_teacher_subjects`
--
ALTER TABLE `user_teacher_subjects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_teacher_tokens`
--
ALTER TABLE `user_teacher_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_privileges`
--
ALTER TABLE `admin_privileges`
  ADD CONSTRAINT `admin_privileges_useradminid_foreign` FOREIGN KEY (`userAdminId`) REFERENCES `user_admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD CONSTRAINT `calendar_events_groupid_foreign` FOREIGN KEY (`groupId`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD CONSTRAINT `classrooms_groupid_foreign` FOREIGN KEY (`groupId`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classrooms_userteacherid_foreign` FOREIGN KEY (`userTeacherId`) REFERENCES `user_teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_material_files`
--
ALTER TABLE `classroom_material_files`
  ADD CONSTRAINT `classroom_material_files_classroommaterialid_foreign` FOREIGN KEY (`classroomMaterialId`) REFERENCES `classroom_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_material_links`
--
ALTER TABLE `classroom_material_links`
  ADD CONSTRAINT `classroom_material_links_classroommaterialid_foreign` FOREIGN KEY (`classroomMaterialId`) REFERENCES `classroom_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_tasks`
--
ALTER TABLE `classroom_tasks`
  ADD CONSTRAINT `classroom_tasks_classroomid_foreign` FOREIGN KEY (`classroomId`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_task_answers`
--
ALTER TABLE `classroom_task_answers`
  ADD CONSTRAINT `classroom_task_answers_classroomtaskid_foreign` FOREIGN KEY (`classroomTaskId`) REFERENCES `classroom_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classroom_task_answers_userstudentid_foreign` FOREIGN KEY (`userStudentId`) REFERENCES `user_students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_task_answer_files`
--
ALTER TABLE `classroom_task_answer_files`
  ADD CONSTRAINT `classroom_task_answer_files_classroomtaskanswerid_foreign` FOREIGN KEY (`classroomTaskAnswerId`) REFERENCES `classroom_task_answers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_task_answer_links`
--
ALTER TABLE `classroom_task_answer_links`
  ADD CONSTRAINT `classroom_task_answer_links_classroomtaskanswerid_foreign` FOREIGN KEY (`classroomTaskAnswerId`) REFERENCES `classroom_task_answers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_task_comments`
--
ALTER TABLE `classroom_task_comments`
  ADD CONSTRAINT `classroom_task_comments_classroomtaskid_foreign` FOREIGN KEY (`classroomTaskId`) REFERENCES `classroom_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classroom_task_comments_userstudentid_foreign` FOREIGN KEY (`userStudentId`) REFERENCES `user_students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_task_files`
--
ALTER TABLE `classroom_task_files`
  ADD CONSTRAINT `classroom_task_files_classroomtaskid_foreign` FOREIGN KEY (`classroomTaskId`) REFERENCES `classroom_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_task_links`
--
ALTER TABLE `classroom_task_links`
  ADD CONSTRAINT `classroom_task_links_classroomtaskid_foreign` FOREIGN KEY (`classroomTaskId`) REFERENCES `classroom_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class_assessments`
--
ALTER TABLE `class_assessments`
  ADD CONSTRAINT `class_assessments_groupscheduleclassid_foreign` FOREIGN KEY (`groupScheduleClassId`) REFERENCES `group_schedule_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class_attendances`
--
ALTER TABLE `class_attendances`
  ADD CONSTRAINT `class_attendances_attendancestatusid_foreign` FOREIGN KEY (`attendanceStatusId`) REFERENCES `class_attendance_statuses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `class_attendances_groupscheduleclassid_foreign` FOREIGN KEY (`groupScheduleClassId`) REFERENCES `group_schedule_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_selected_student_alls`
--
ALTER TABLE `event_selected_student_alls`
  ADD CONSTRAINT `event_selected_student_alls_calendareventid_foreign` FOREIGN KEY (`calendarEventId`) REFERENCES `calendar_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_selected_student_children`
--
ALTER TABLE `event_selected_student_children`
  ADD CONSTRAINT `event_selected_student_children_calendareventid_foreign` FOREIGN KEY (`calendarEventId`) REFERENCES `calendar_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_selected_student_children_userstudentid_foreign` FOREIGN KEY (`userStudentId`) REFERENCES `user_students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_selected_student_groups`
--
ALTER TABLE `event_selected_student_groups`
  ADD CONSTRAINT `event_selected_student_groups_calendareventid_foreign` FOREIGN KEY (`calendarEventId`) REFERENCES `calendar_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_departmentid_foreign` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_userteacherid_foreign` FOREIGN KEY (`userTeacherId`) REFERENCES `user_teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `group_schedule_classes`
--
ALTER TABLE `group_schedule_classes`
  ADD CONSTRAINT `group_schedule_classes_groupid_foreign` FOREIGN KEY (`groupId`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_schedule_classes_subjectid_foreign` FOREIGN KEY (`subjectId`) REFERENCES `group_subjects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `group_schedule_class_replacements`
--
ALTER TABLE `group_schedule_class_replacements`
  ADD CONSTRAINT `group_schedule_class_replacements_groupscheduleclassid_foreign` FOREIGN KEY (`groupScheduleClassId`) REFERENCES `group_schedule_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_schedule_class_replacements_userteacherid_foreign` FOREIGN KEY (`userTeacherId`) REFERENCES `user_teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `group_schedule_class_replacement_rq`
--
ALTER TABLE `group_schedule_class_replacement_rq`
  ADD CONSTRAINT `group_schedule_class_replacement_rq_groupscheduleclassid_foreign` FOREIGN KEY (`groupScheduleClassId`) REFERENCES `group_schedule_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_schedule_class_replacement_rq_userteacherid_foreign` FOREIGN KEY (`userTeacherId`) REFERENCES `user_teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `group_subjects`
--
ALTER TABLE `group_subjects`
  ADD CONSTRAINT `group_subjects_groupid_foreign` FOREIGN KEY (`groupId`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_subjects_teachersubjectid_foreign` FOREIGN KEY (`teacherSubjectId`) REFERENCES `user_teacher_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_admin_tokens`
--
ALTER TABLE `user_admin_tokens`
  ADD CONSTRAINT `user_admin_tokens_parentid_foreign` FOREIGN KEY (`parentId`) REFERENCES `user_admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_students`
--
ALTER TABLE `user_students`
  ADD CONSTRAINT `user_students_groupid_foreign` FOREIGN KEY (`groupId`) REFERENCES `groups` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_student_tokens`
--
ALTER TABLE `user_student_tokens`
  ADD CONSTRAINT `user_student_tokens_parentid_foreign` FOREIGN KEY (`parentId`) REFERENCES `user_students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_teachers`
--
ALTER TABLE `user_teachers`
  ADD CONSTRAINT `user_teachers_auditoriaid_foreign` FOREIGN KEY (`auditoriaId`) REFERENCES `auditoria` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_teacher_subjects`
--
ALTER TABLE `user_teacher_subjects`
  ADD CONSTRAINT `user_teacher_subjects_subjectid_foreign` FOREIGN KEY (`subjectId`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_teacher_subjects_userteacherid_foreign` FOREIGN KEY (`userTeacherId`) REFERENCES `user_teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_teacher_tokens`
--
ALTER TABLE `user_teacher_tokens`
  ADD CONSTRAINT `user_teacher_tokens_parentid_foreign` FOREIGN KEY (`parentId`) REFERENCES `user_teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
