-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 23, 2024 at 09:50 AM
-- Server version: 10.11.8-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u235003615_ecourses_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(20) NOT NULL,
  `admin_type` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `admin_type`, `created`) VALUES
(1, 'mainAdmin@gmail.com', 'bWFpbkFkbWluQDEyMw==', 0, '2023-04-22 23:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) NOT NULL,
  `whatsapp_number` text NOT NULL DEFAULT '',
  `telegram_link` text NOT NULL DEFAULT '',
  `instagram_link` text NOT NULL DEFAULT '',
  `facebook_link` text NOT NULL DEFAULT '',
  `share_message` text NOT NULL DEFAULT '',
  `contact_email` text NOT NULL,
  `more_apps_url` text NOT NULL,
  `privacy_policy` text NOT NULL,
  `onesignal_app_id` text NOT NULL,
  `payment_method` int(11) NOT NULL DEFAULT 0,
  `phonepe_merchant_id` text NOT NULL DEFAULT '',
  `phonepe_salt_key` text NOT NULL DEFAULT '',
  `phonepe_salt_index` text NOT NULL DEFAULT '',
  `server_ip_address` text NOT NULL DEFAULT '',
  `phonepe_host_url` text NOT NULL DEFAULT '',
  `razorpay_api_key_id` text NOT NULL DEFAULT '',
  `upi_manual_pay` text NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `whatsapp_number`, `telegram_link`, `instagram_link`, `facebook_link`, `share_message`, `contact_email`, `more_apps_url`, `privacy_policy`, `onesignal_app_id`, `payment_method`, `phonepe_merchant_id`, `phonepe_salt_key`, `phonepe_salt_index`, `server_ip_address`, `phonepe_host_url`, `razorpay_api_key_id`, `upi_manual_pay`) VALUES
(1, '7017900496', 'https://t.me/sh_developer', 'https://www.instagram.com/shdevsolutions', 'https://www.instagram.com/shdevsolutions', 'Download This App Now', 'contactshivgupta@gmail.com', 'https://play.google.com/store/apps', 'https://knowledgeboostblogs.blogspot.com/', '3351655a-b14d-4df3-9cfc-b6757e9c2d27', 2, 'bvnv', 'fgn', 'fdbfg', '125.21.249.98', 'sdfddghgf', 'rzp_qCCxMVNhg7esvP', 'sdbfgn@upi');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `image` text NOT NULL,
  `url` text NOT NULL DEFAULT '',
  `type` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `image`, `url`, `type`, `created`) VALUES
(1, 'https://dmf76jm51vpov.cloudfront.net/www2/images/promotion/cbse/CBSE-Board-Course-Package-intro-mobile.jpg', '/', 0, '2023-07-07 13:25:05'),
(3, 'https://ecoursesapp.shdevsolutions.com/storage/no9UNVzYDC.jpg', '/', 0, '2024-05-18 06:04:44');

-- --------------------------------------------------------

--
-- Table structure for table `coupans`
--

CREATE TABLE `coupans` (
  `id` int(11) NOT NULL,
  `code` text NOT NULL,
  `discount_amount` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `coupans`
--

INSERT INTO `coupans` (`id`, `code`, `discount_amount`, `created`) VALUES
(1, '603071', 20, '2023-07-07 12:58:19');

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` int(11) NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`id`, `image`, `title`, `file`, `price`, `created`) VALUES
(1, 'https://evirtualguru.com/wp-content/uploads/2014/12/Capture15.png', 'Class 12th English Flamingo', 'https://drive.google.com/file/d/17EorOX98oZArDwb1K2uk-vlNeemjdH9D/view', 100, '2023-07-07 12:43:12');

-- --------------------------------------------------------

--
-- Table structure for table `manual_payment_requests`
--

CREATE TABLE `manual_payment_requests` (
  `id` int(11) NOT NULL,
  `user_uid` text NOT NULL,
  `transaction_id` text NOT NULL,
  `amount` int(11) NOT NULL,
  `data` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mock_tests`
--

CREATE TABLE `mock_tests` (
  `id` int(11) NOT NULL,
  `test_category_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `test_time` int(11) NOT NULL DEFAULT 30,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `mock_tests`
--

INSERT INTO `mock_tests` (`id`, `test_category_id`, `course_id`, `type`, `title`, `test_time`, `created`) VALUES
(1, 1, 5, 0, 'Class 12th Maths Quiz', 5, '2023-07-07 13:18:34'),
(2, 1, 1, 1, '12th Physics Test 1', 5, '2023-07-07 13:28:36'),
(3, 1, 5, 0, 'test', 5, '2024-07-05 14:11:01');

-- --------------------------------------------------------

--
-- Table structure for table `mock_test_category`
--

CREATE TABLE `mock_test_category` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `mock_test_category`
--

INSERT INTO `mock_test_category` (`id`, `title`, `price`, `created`) VALUES
(1, 'Maths', 99, '2023-07-07 13:15:56');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `click_url` text NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `image`, `click_url`, `created`) VALUES
(1, 'PNG to JPG Converter', 'Thank You For Downloading Our App', '', 'https://www.youtube.com/', '2024-05-18 05:40:53'),
(2, 'hello', 'this a testing message', '', 'https://wa.me/p/7948086585230964/919516047156', '2024-07-05 14:12:22'),
(3, 'check new', 'hello sir', 'https://ecoursesapp.shdevsolutions.com/storage/1720188838.jpg', 'https://wa.me/p/7948086585230964/919516047156', '2024-07-05 14:13:58'),
(4, 'PNG to JPG Converter', 'Thank You For Downloading Our App', '', 'https://www.youtube.com/', '2024-09-11 12:39:53'),
(5, 'PNG to JPG Converter', 'Thank You For Downloading Our App', '', 'https://www.youtube.com/', '2024-09-11 12:41:42'),
(6, 'PNG to JPG Converter', 'Thank You For Downloading Our App', '', 'https://www.youtube.com/', '2024-09-11 12:55:30');

-- --------------------------------------------------------

--
-- Table structure for table `paid_couses`
--

CREATE TABLE `paid_couses` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `is_active` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `paid_couses`
--

INSERT INTO `paid_couses` (`id`, `title`, `image`, `description`, `price`, `created`, `is_active`) VALUES
(1, 'Online Course for Class XII Board (Physics)', 'https://www.classcentral.com/report/wp-content/uploads/2022/04/Physics-Free-Online-Courses.png', '<p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Target</span></span>: Higher Percentage in Board Exams</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">Medium</span></span>: Mix (English &amp; Hindi). All definitions, derivation, etc will be in English however the explanation shall be in mixed language.</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">About Course</span></span>:</span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Self-Paced course providing you flexibility to plan your studies</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Suggested Day wise study plan designed by experts is also given for structure preparation.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Course is based on NCERT and cover all points essential to score good marks.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Course covers board syllabus in systematic manner</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Also useful for students who are preparing for competitive exam, and at the same time, want to prepare for the board exams.</span></span></li></ul><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">Course Validity</span></span>: Till end of academic session 2023</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Course Content</span></span>:&nbsp;</span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Recorded Lecture</span>: By experienced faculty members to ensure that the students understand all concepts.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Study Plan:</span></span></span>&nbsp;<span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Day Wise Study Plan made by expert faculy members for board examinations. Content Section -&gt; About Course -&gt; Study Plan</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Lecture Notes:</span>&nbsp;Class Notes &amp; all proofs and derivations (in pdf format after the class)</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Lecturewise daily Home Assignments</span>: After each lecture, a set of questions will be given as the Home Assignment with solutions.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Tests</span></span></span><ul style=\"padding-left: 2rem !important;\"><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Chapter Tests</span></span></li><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Module Tests (based on a group of topics)</span></span></li><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Full Tests</span></span></li></ul></li></ul><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Suggested Books:</span></span></span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">NCERT (Physics) - Pdfs of Book and solutions are also attached in concept secition.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">NCERT Exemplar (Physics) -&nbsp;Pdfs of Book and solutions are also attached in concept secition.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">All in one for class XII (Arihant Publications)</span></span></li></ul>', 199, '2023-07-07 12:34:09', 0),
(2, 'Online Course for Class XII Board (Chemistry)', 'https://blogassets.leverageedu.com/media/uploads/2022/07/26190501/Industrial-Chemistry.png', '<p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Target</span></span>: Higher Percentage in Board Exams</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">Medium</span></span>: Mix (English &amp; Hindi). All definitions, derivation, etc will be in English however the explanation shall be in mixed language.</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">About Course</span></span>:</span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Self-Paced course providing you flexibility to plan your studies</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Suggested Day wise study plan designed by experts is also given for structure preparation.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Course is based on NCERT and cover all points essential to score good marks.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Course covers board syllabus in systematic manner</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Also useful for students who are preparing for competitive exam, and at the same time, want to prepare for the board exams.</span></span></li></ul><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">Course Validity</span></span>: Till end of academic session 2023</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Course Content</span></span>:&nbsp;</span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Recorded Lecture</span>: By experienced faculty members to ensure that the students understand all concepts.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Study Plan:</span></span></span>&nbsp;<span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Day Wise Study Plan made by expert faculy members for board examinations. Content Section -&gt; About Course -&gt; Study Plan</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Lecture Notes:</span>&nbsp;Class Notes &amp; all proofs and derivations (in pdf format after the class)</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Lecturewise daily Home Assignments</span>: After each lecture, a set of questions will be given as the Home Assignment with solutions.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Tests</span></span></span><ul style=\"padding-left: 2rem !important;\"><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Chapter Tests</span></span></li><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Module Tests (based on a group of topics)</span></span></li><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Full Tests</span></span></li></ul></li></ul><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Suggested Books:</span></span></span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">NCERT (Chemistry) - Pdfs of Book and solutions are also attached in concept secition.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">NCERT Exemplar (Chemistry) -&nbsp;Pdfs of Book and solutions are also attached in concept secition.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">All in one for class XII (Arihant Publications)</span></span></li></ul>', 199, '2023-07-07 12:45:21', 0),
(3, 'Online Course for Class XII Board (Maths)', 'https://www.successtree.in/s/store/courses/64e9eda4e4b0286bc050bd53/cover.jpg?v=1', '<p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Target</span></span>: Higher Percentage in Board Exams</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">Medium</span></span>: Mix (English &amp; Hindi). All definitions, derivation, etc will be in English however the explanation shall be in mixed language.</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">About Course</span></span>:</span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Self-Paced course providing you flexibility to plan your studies</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Suggested Day wise study plan designed by experts is also given for structure preparation.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Course is based on NCERT and cover all points essential to score good marks.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Course covers board syllabus in systematic manner</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Also useful for students who are preparing for competitive exam, and at the same time, want to prepare for the board exams.</span></span></li></ul><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">Course Validity</span></span>: Till end of academic session 2023</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Course Content</span></span>:&nbsp;</span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Recorded Lecture</span>: By experienced faculty members to ensure that the students understand all concepts.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Study Plan:</span></span></span>&nbsp;<span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Day Wise Study Plan made by expert faculy members for board examinations. Content Section -&gt; About Course -&gt; Study Plan</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Lecture Notes:</span>&nbsp;Class Notes &amp; all proofs and derivations (in pdf format after the class)</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Lecturewise daily Home Assignments</span>: After each lecture, a set of questions will be given as the Home Assignment with solutions.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Tests</span></span></span><ul style=\"padding-left: 2rem !important;\"><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Chapter Tests</span></span></li><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Module Tests (based on a group of topics)</span></span></li><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Full Tests</span></span></li></ul></li></ul><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Suggested Books:</span></span></span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">NCERT (Maths) - Pdfs of Book and solutions are also attached in concept secition.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">NCERT Exemplar (Maths) -&nbsp;Pdfs of Book and solutions are also attached in concept secition.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">All in one for class XII (Arihant Publications)</span></span></li></ul>', 299, '2023-07-07 12:53:56', 0),
(4, 'Online Course for Class XII Board (Biology)', 'https://www.classcentral.com/report/wp-content/uploads/2023/10/bcg_cell_biology_banner.png', '<p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Target</span></span>: Higher Percentage in Board Exams</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">Medium</span></span>: Mix (English &amp; Hindi). All definitions, derivation, etc will be in English however the explanation shall be in mixed language.</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">About Course</span></span>:</span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Self-Paced course providing you flexibility to plan your studies</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Suggested Day wise study plan designed by experts is also given for structure preparation.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Course is based on NCERT and cover all points essential to score good marks.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Course covers board syllabus in systematic manner</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Also useful for students who are preparing for competitive exam, and at the same time, want to prepare for the board exams.</span></span></li></ul><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">Course Validity</span></span>: Till end of academic session 2023</span></span></p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Course Content</span></span>:&nbsp;</span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Recorded Lecture</span>: By experienced faculty members to ensure that the students understand all concepts.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Study Plan:</span></span></span>&nbsp;<span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Day Wise Study Plan made by expert faculy members for board examinations. Content&nbsp;Section -&gt; About Course -&gt; Study Plan</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Lecture Notes:</span>&nbsp;Class Notes &amp; all proofs and derivations (in pdf format after the class)</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Lecturewise daily Home Assignments</span>: After each lecture, a set of questions will be given as the Home Assignment with solutions.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Tests</span></span></span><ul style=\"padding-left: 2rem !important;\"><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Chapter Tests</span></span></li><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Module Tests (based on a group of topics)</span></span></li><li style=\"margin: 5px 0px !important; padding-left: 0px !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">Full Tests</span></span></li></ul></li></ul><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Suggested Books:</span></span></span></span></p><ul style=\"color: rgb(33, 37, 41); font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px; padding-left: 2rem !important;\"><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">NCERT (Biology) - Pdfs of Book and solutions are also attached in concept secition.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">NCERT Exemplar (Biology) -&nbsp;Pdfs of Book and solutions are also attached in concept secition.</span></span></li><li style=\"font-size: 16px !important; margin: 5px 0px !important; padding-left: 0px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-family: Tahoma, Geneva, sans-serif;\"><span style=\"font-size: 14px;\">All in one for class XII (Arihant Publications)</span></span></li></ul>', 249, '2023-07-07 12:54:22', 0),
(5, 'Physical Education for Class 12th Board', 'https://levelupcuet.com/admin/uploads/1705752918.jpg', '<p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\">In this course, Career Point giving you Chapter Wise CBSE Physical Education Class 12 Video Lectures with class notes and reference material which is&nbsp;designed by expert teachers from latest edition of NCERT books to get good marks in board exams.</p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\"><span style=\"color: rgb(192, 57, 43);\">Course Structure</span></span>:</p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\">1. Video lecture covers all chapters and all important concepts according to revised CBSE syllabus.</p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\">2. Class Notes in PDF form&nbsp;(given in Concept Section)</p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\">3. Important Question&nbsp;Home Assignments for Practice (given in Concept Section)</p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\">4. Latest Board Pattern Test for each Chapter</p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\">5.&nbsp;Sample Papers on Latest Board Pattern</p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\">6. Revision Notes and Important Questions in PDF form</p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\">7. Previous Year Question Papers</p><p style=\"font-family: system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, &quot;Liberation Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; text-align: justify; margin-bottom: 16px !important; font-size: 16px !important; color: rgb(0, 0, 0) !important;\"><span style=\"color: rgb(192, 57, 43);\"><span style=\"font-weight: 600; color: var(--bs-primary) !important; margin-right: 3px !important;\">Medium</span></span>: Mix (English &amp; Hindi). Recommended for those students who write exam in English &amp; comfortable in Hindi.</p>', 149, '2023-07-07 12:54:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pdf_notes`
--

CREATE TABLE `pdf_notes` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `file` text NOT NULL,
  `course_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pdf_notes`
--

INSERT INTO `pdf_notes` (`id`, `title`, `file`, `course_id`, `created`) VALUES
(1, 'ELECTROSTATIC POTENTIAL AND CAPACITANCE', 'https://drive.google.com/uc?export=download&id=1Y0gHkVJ3xRMlNReL4Iu2t0ieiMpCKpJ4', 1, '2023-07-07 12:38:07'),
(2, 'CURRENT ELECTRICITY', 'https://drive.google.com/uc?export=download&id=1Y0gHkVJ3xRMlNReL4Iu2t0ieiMpCKpJ4', 1, '2023-07-07 12:39:05');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(13, 'App\\Models\\Users', 1, 'my-app-token', 'c52633854047f686503ae6a301522fa8ada780782ef4bece0173c9639e7fb407', '[\"*\"]', '2023-07-07 15:09:30', '2023-07-07 15:01:25', '2023-07-07 15:09:30'),
(14, 'App\\Models\\Users', 2, 'my-app-token', '8956d2aa665652e25cb3348d63343dea01d344fb8b7bbd882ffa8d1d171545ab', '[\"*\"]', NULL, '2024-05-18 11:18:44', '2024-05-18 11:18:44'),
(35, 'App\\Models\\Users', 5, 'my-app-token', '053954827cd19a3ed296d3464ccdc090f6b49d3ac4a516df5d3a5cdcfe62bc7f', '[\"*\"]', '2024-05-23 10:28:39', '2024-05-23 10:28:39', '2024-05-23 10:28:39'),
(37, 'App\\Models\\Users', 4, 'my-app-token', '473d0fe2d5d3d87733d3bd5c87eb31f95e14fd22dbdade38c01306826ab38218', '[\"*\"]', '2024-05-23 17:39:10', '2024-05-23 17:39:08', '2024-05-23 17:39:10'),
(55, 'App\\Models\\Users', 7, 'my-app-token', '0183aeaf106d8cb9af7e87cb1b604414adf5ba15bbdf265893652b2544148820', '[\"*\"]', '2024-07-09 16:57:47', '2024-07-09 16:57:28', '2024-07-09 16:57:47'),
(57, 'App\\Models\\Users', 8, 'my-app-token', '4a011aee0cdabd373b0f9decbc48baa9a19e9c4de6e33d307238b642d9bb472c', '[\"*\"]', '2024-07-10 17:35:12', '2024-07-10 17:35:05', '2024-07-10 17:35:12'),
(61, 'App\\Models\\Users', 9, 'my-app-token', 'd3cf5bac3d60da5f0a6a1f5b4f1f02f53366519e1a7592f54b3587bb551921e3', '[\"*\"]', '2024-07-14 16:53:47', '2024-07-14 16:53:29', '2024-07-14 16:53:47'),
(76, 'App\\Models\\Users', 10, 'my-app-token', '829d15226a3838f666618242aca7e8c3108645f504199e019a9ce6b5bd51ab54', '[\"*\"]', '2024-07-17 07:42:53', '2024-07-17 07:42:41', '2024-07-17 07:42:53'),
(84, 'App\\Models\\Users', 11, 'my-app-token', 'bc3238dcfaff2cf5bbf287d6ed746b9860ab99c59fe57a9bae27179730cc0f63', '[\"*\"]', '2024-07-21 11:17:05', '2024-07-21 11:17:04', '2024-07-21 11:17:05'),
(112, 'App\\Models\\Users', 6, 'my-app-token', '0fbf6dc087213f2b1790ba005baf818faf8a6de0134a8abd2b50904b96d7ec54', '[\"*\"]', '2024-07-29 23:17:13', '2024-07-29 23:17:08', '2024-07-29 23:17:13'),
(164, 'App\\Models\\Users', 13, 'my-app-token', 'd4771ca2256b6def78c0111640bfe44b9ddb901f9ec05a3a04de8f1345b5b5db', '[\"*\"]', '2024-09-02 20:51:02', '2024-09-02 20:50:59', '2024-09-02 20:51:02'),
(165, 'App\\Models\\Users', 12, 'my-app-token', '6a8ab1a3b04680d734cc15570f895a3b591a1b624d8c91569d76bb0159e46a4b', '[\"*\"]', '2024-09-03 08:48:53', '2024-09-03 08:48:52', '2024-09-03 08:48:53'),
(215, 'App\\Models\\Users', 14, 'my-app-token', '78bde48844bb927f277bb8aded670981a4519fdca7f3d47c6b270e4213cabe31', '[\"*\"]', '2024-09-16 20:59:39', '2024-09-16 20:59:07', '2024-09-16 20:59:39'),
(232, 'App\\Models\\Users', 15, 'my-app-token', 'af0f45aabc1c389966f4ce44fa71aec3cd01eef4b6833af49b56b1e0e22320b5', '[\"*\"]', '2024-09-20 18:56:33', '2024-09-20 18:56:32', '2024-09-20 18:56:33'),
(234, 'App\\Models\\Users', 16, 'my-app-token', '6a4666f8682b77cd87aa6d858465484d57480a10aed581375af36959d6bb2635', '[\"*\"]', '2024-09-20 19:03:34', '2024-09-20 19:03:28', '2024-09-20 19:03:34'),
(235, 'App\\Models\\Users', 17, 'my-app-token', '7b298678eff33003c096652ce386d6d06d4e3efb6d5d41502490e715073285b2', '[\"*\"]', '2024-09-20 19:18:22', '2024-09-20 19:18:19', '2024-09-20 19:18:22'),
(238, 'App\\Models\\Users', 18, 'my-app-token', 'db076e33f88978c7c9a51eccafd6f433cb9c266ab77f3915817d4e484cd75f4b', '[\"*\"]', '2024-09-22 07:59:52', '2024-09-22 07:59:25', '2024-09-22 07:59:52'),
(253, 'App\\Models\\Users', 21, 'my-app-token', 'a912179bde4d1c0391188f2e90cff7963c3b867df982e35a3353d159833c0e66', '[\"*\"]', '2024-09-23 10:25:44', '2024-09-23 10:25:44', '2024-09-23 10:25:44'),
(258, 'App\\Models\\Users', 19, 'my-app-token', 'f804fd5cf5757e15b4a5f2879290589ed5aa25586d8a96c38bfec9bd27869f34', '[\"*\"]', '2024-09-23 12:17:24', '2024-09-23 12:17:23', '2024-09-23 12:17:24'),
(265, 'App\\Models\\Users', 20, 'my-app-token', '72f3a51b2dae5d6c8ad439280423f62a8658a9afc8dabd0b8d0bec9cd30e1b52', '[\"*\"]', '2024-09-23 15:06:47', '2024-09-23 15:06:35', '2024-09-23 15:06:47'),
(266, 'App\\Models\\Users', 3, 'my-app-token', '418b7e085353ff858513d0d26c34cd7bdff77ce441e8254c581031d563d21eaf', '[\"*\"]', '2024-09-23 15:20:14', '2024-09-23 15:16:34', '2024-09-23 15:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `syllabus`
--

CREATE TABLE `syllabus` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `file` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `syllabus`
--

INSERT INTO `syllabus` (`id`, `title`, `file`, `created`) VALUES
(1, 'Class 12th Physics', 'https://ecourses.omstar.co.in/storage/paZi1CcQ43.pdf', '2023-07-07 12:56:55'),
(2, 'Class 12th Chemistry', 'https://ecourses.omstar.co.in/storage/kfFiwTMF4g.pdf', '2023-07-07 12:57:29'),
(3, 'Class 12th Biology', 'https://ecourses.omstar.co.in/storage/a17oN3uk8U.pdf', '2023-07-07 12:57:58'),
(4, 'Class 12th Maths', 'https://ecourses.omstar.co.in/storage/ct8RZiM0Lj.pdf', '2023-07-07 12:58:09');

-- --------------------------------------------------------

--
-- Table structure for table `test_questions`
--

CREATE TABLE `test_questions` (
  `id` int(11) NOT NULL,
  `mock_test_id` int(11) NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `opt_1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `opt_2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `opt_3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `opt_4` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correct_option_no` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `test_questions`
--

INSERT INTO `test_questions` (`id`, `mock_test_id`, `question`, `opt_1`, `opt_2`, `opt_3`, `opt_4`, `correct_option_no`, `created`) VALUES
(1, 1, 'Find the derivative of f(x) = 3x^2 - 4x + 2', '6x - 4', '3x^2 - 4x + 2', '3x^3 - 2x + 4', '2x^2 - 4', 1, '2023-07-07 13:19:07'),
(2, 1, 'Solve the equation: 2x + 5 = 15', 'x = 5', 'x = 10', 'x = 7.5', 'x = 8', 1, '2023-07-07 13:19:41'),
(3, 1, 'Find the value of cos(π/3)', '1/2', '√3/2', '1/√2', '1', 2, '2023-07-07 13:20:15'),
(4, 1, 'Simplify the expression: (2x^2 + 3x - 1) + (4x^2 - 2x + 5)', '6x^2 + x + 4', '6x^2 + x - 6', '6x^2 - x + 6', '6x^2 + 5x + 4', 4, '2023-07-07 13:20:42'),
(5, 1, 'Find the value of log(base 2) 16', '2', '3', '4', '5', 3, '2023-07-07 13:21:03'),
(6, 2, 'What is the SI unit of electric current?', 'Ampere', 'Volt', 'Ohm', 'Watt', 1, '2023-07-07 13:25:00'),
(7, 2, 'What is the SI unit of force?', 'Newton', 'Joule', 'Watt', 'Pascal', 1, '2023-07-07 13:26:00'),
(8, 2, 'What is the SI unit of energy?', 'Joule', 'Watt', 'Newton', 'Pascal', 1, '2023-07-07 13:27:00'),
(9, 2, 'What is the SI unit of power?', 'Watt', 'Joule', 'Newton', 'Pascal', 1, '2023-07-07 13:28:00'),
(10, 2, 'Which law describes the conservation of energy?', 'First law of thermodynamics', 'Second law of thermodynamics', 'Newton\'s second law', 'Law of gravitation', 1, '2023-07-07 13:29:00'),
(11, 2, 'Which law states that the pressure of a gas is inversely proportional to its volume?', 'Boyle\'s law', 'Charles\'s law', 'Gay-Lussac\'s law', 'Avogadro\'s law', 1, '2023-07-07 13:30:00'),
(12, 2, 'Which law states that the volume of a gas is directly proportional to its temperature?', 'Charles\'s law', 'Boyle\'s law', 'Gay-Lussac\'s law', 'Avogadro\'s law', 1, '2023-07-07 13:31:00'),
(13, 2, 'Which law states that the pressure of a gas is directly proportional to its absolute temperature?', 'Gay-Lussac\'s law', 'Boyle\'s law', 'Charles\'s law', 'Avogadro\'s law', 1, '2023-07-07 13:32:00'),
(14, 2, 'Which law states that equal volumes of gases at the same temperature and pressure contain an equal number of molecules?', 'Avogadro\'s law', 'Boyle\'s law', 'Charles\'s law', 'Gay-Lussac\'s law', 1, '2023-07-07 13:33:00'),
(15, 2, 'What is the SI unit of electric charge?', 'Coulomb', 'Ampere', 'Ohm', 'Watt', 1, '2023-07-07 13:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uid` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `phone_number` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `state` varchar(20) NOT NULL DEFAULT '',
  `profile_pic` text NOT NULL DEFAULT '',
  `account_status` tinyint(4) NOT NULL DEFAULT 0,
  `last_active` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `country` varchar(20) NOT NULL DEFAULT '',
  `ip_address` text NOT NULL DEFAULT '',
  `device_id` text NOT NULL DEFAULT '',
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uid`, `username`, `email`, `phone_number`, `password`, `created`, `state`, `profile_pic`, `account_status`, `last_active`, `country`, `ip_address`, `device_id`, `token`) VALUES
(3, 'HMSXYHCMNMK7PZMUQYDB', 'SH Developer', 'sh.developer.shiv@gmail.com', '7017900496', 'YzJocGRtZDFjSFJo', '2024-05-18 11:21:45', 'Uttar Pradesh', '', 0, '2024-09-23 15:16:34', 'IN', '125.21.249.98', '6882fc6b02d4ec3d', 'D9F05187166AF554511DDA806C81066A'),
(18, 'TBHBRLI8UH1LTXSHY4PY', 'golden', '', '8072983973', 'WVdGaFlXRXhNVEU9', '2024-09-22 07:59:25', '', '', 0, '2024-09-22 07:59:25', 'IN', '2402:e280:2156:27:65fb:54e4:7c4f:d0b', '415ceca162aeb82f', '712E3C62D252C289680217893A6F864E'),
(19, 'LZMDPXLRUQGXVSN1OTE4', 'sudarshan', '', '9363515254', 'UVd4c2FYTjNaV3hzUURNMk9Uaz0=', '2024-09-23 08:24:04', '', '', 0, '2024-09-23 12:17:23', 'IN', '223.178.85.91', 'a5dcf4b167f578b2', 'F3728632851CDCA54CFAE0417F370EB3'),
(20, 'XZ40YBHY0MDAVB5QN5KJ', 'manohar', '', '8341169949', 'TVRJek5EVTJOemc1', '2024-09-23 09:13:04', '', '', 0, '2024-09-23 15:06:35', 'IN', '60.243.212.47', '840edfdd7566e55e', '62866ED1CAA0A8EBD1E7B0BABE4886FF'),
(21, 'CNMWUQ1I2RSIVZAZOYHL', 'fffff', '', '9188758677', 'WjJkblp6SXlkM2M9', '2024-09-23 10:22:15', '', '', 0, '2024-09-23 10:25:44', 'IN', '2409:40d4:f3:33d5:45c6:15fd:e175:41b7', '5ceb57786e287384', 'D063233628D2D77780E8ED49C15EE633');

-- --------------------------------------------------------

--
-- Table structure for table `user_orders`
--

CREATE TABLE `user_orders` (
  `id` int(11) NOT NULL,
  `user_uid` text NOT NULL,
  `details` text NOT NULL,
  `price` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `coupan_used` int(11) NOT NULL DEFAULT 0,
  `transaction_details` text NOT NULL,
  `payment_method` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_orders`
--

INSERT INTO `user_orders` (`id`, `user_uid`, `details`, `price`, `type`, `coupan_used`, `transaction_details`, `payment_method`, `created`) VALUES
(1, 'HMSXYHCMNMK7PZMUQYDB', '1', 199, 0, 0, '', 0, '2024-09-23 07:08:17'),
(2, 'HMSXYHCMNMK7PZMUQYDB', '1', 199, 1, 0, '', 0, '2024-09-23 07:08:17'),
(3, 'HMSXYHCMNMK7PZMUQYDB', '1', 199, 2, 0, '', 0, '2024-09-23 07:08:17');

-- --------------------------------------------------------

--
-- Table structure for table `videos_category`
--

CREATE TABLE `videos_category` (
  `id` int(11) NOT NULL,
  `category` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `videos_category`
--

INSERT INTO `videos_category` (`id`, `category`, `created`) VALUES
(1, 'Maths', '2023-07-07 12:58:30'),
(3, 'Biology', '2023-07-07 12:58:40'),
(4, 'Physics', '2023-07-07 12:58:46'),
(5, 'Chemistry', '2023-07-07 12:58:50');

-- --------------------------------------------------------

--
-- Table structure for table `youtube_videos`
--

CREATE TABLE `youtube_videos` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `video_type` int(1) NOT NULL DEFAULT 0,
  `paid` tinyint(1) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `course_id` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `video_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `youtube_videos`
--

INSERT INTO `youtube_videos` (`id`, `title`, `description`, `image`, `video_type`, `paid`, `category_id`, `course_id`, `created`, `video_link`) VALUES
(1, 'Electric Potential and Capacitance Class 12 Physics| Electric Potential | Potential Energy of Dipole', '<p><font face=\"Roboto, Arial, sans-serif\"><span style=\"font-size: 14px; white-space-collapse: preserve; background-color: rgba(255, 255, 255, 0.1);\">Subscribe Our Channel and don\'t forget to press the bell icon 🔔, so you don\'t miss out on future videos</span></font><br></p>', 'https://img.youtube.com/vi/SG2-yyj8nMo/mqdefault.jpg', 0, 1, 4, 0, '2023-07-07 13:01:05', 'https://www.youtube.com/watch?v=SG2-yyj8nMo'),
(2, 'CBSE Class 12 | Electric Charges and Fields | Electric Field Due to Sphere| Most Important Questions', '<p><font face=\"Roboto, Arial, sans-serif\"><span style=\"font-size: 14px; white-space-collapse: preserve; background-color: rgba(255, 255, 255, 0.1);\">Subscribe Our Channel and don\'t forget to press the bell icon 🔔, so you don\'t miss out on future videos</span></font><br></p>', 'https://img.youtube.com/vi/b2sb0TA1fK8/mqdefault.jpg', 0, 0, 4, 0, '2023-07-07 13:08:26', 'https://www.youtube.com/watch?v=b2sb0TA1fK8'),
(3, 'Class 12 Chemistry | Solutions | Concentration of Solutions | Chapter 2', '<p><font face=\"Roboto, Arial, sans-serif\"><span style=\"font-size: 14px; white-space-collapse: preserve; background-color: rgba(255, 255, 255, 0.1);\">Subscribe Our Channel and don\'t forget to press the bell icon 🔔, so you don\'t miss out on future videos</span></font><br></p>', 'https://img.youtube.com/vi/Mjjn2BUOTas/mqdefault.jpg', 0, 1, 5, 0, '2023-07-07 13:12:50', 'https://www.youtube.com/watch?v=Mjjn2BUOTas'),
(4, 'CBSE Class 12 | Chemistry | D & F Block Elements One Shot Revision', '<p><font face=\"Roboto, Arial, sans-serif\"><span style=\"font-size: 14px; white-space-collapse: preserve; background-color: rgba(255, 255, 255, 0.1);\">Subscribe Our Channel and don\'t forget to press the bell icon 🔔, so you don\'t miss out on future videos</span></font><br></p>', 'https://img.youtube.com/vi/QnGxm46Gg84/mqdefault.jpg', 0, 1, 5, 0, '2023-07-07 13:13:50', 'https://www.youtube.com/watch?v=QnGxm46Gg84'),
(5, 'Relation and Function Class 12 | One Shot Video | Full Chapter | 2023-24 | 2024 | Maths Chapter 1', '<p><font face=\"Roboto, Arial, sans-serif\"><span style=\"font-size: 14px; white-space-collapse: preserve; background-color: rgba(255, 255, 255, 0.1);\">Subscribe Our Channel and don\'t forget to press the bell icon 🔔, so you don\'t miss out on future videos</span></font><br></p>', 'https://img.youtube.com/vi/woTvb6v3IxI/mqdefault.jpg', 0, 1, 1, 0, '2023-07-07 13:14:42', 'https://www.youtube.com/watch?v=woTvb6v3IxI'),
(6, 'HUMAN REPRODUCTION | Complete Chapter in 1 Shot | Class 12th Board-NCERT', '<p><font face=\"Roboto, Arial, sans-serif\"><span style=\"font-size: 14px; white-space-collapse: preserve; background-color: rgba(255, 255, 255, 0.1);\">Subscribe Our Channel and don\'t forget to press the bell icon 🔔, so you don\'t miss out on future videos</span></font><br></p>', 'https://img.youtube.com/vi/Ohn8s4sGxzs/mqdefault.jpg', 0, 1, 3, 0, '2023-07-07 13:15:42', 'https://youtube.com/watch?v=Ohn8s4sGxzs'),
(7, 'Introduction to Charges | Electric Charges & Field | Chapter 1 | Class 12th physics', '<p><font face=\"Roboto, Arial, sans-serif\"><span style=\"font-size: 14px; white-space-collapse: preserve; background-color: rgba(255, 255, 255, 0.1);\">Subscribe Our Channel and don\'t forget to press the bell icon 🔔, so you don\'t miss out on future videos</span></font><br></p>', 'https://img.youtube.com/vi/zjlczIzB5rc/mqdefault.jpg', 0, 1, 4, 1, '2023-07-07 13:37:07', 'https://www.youtube.com/watch?v=zjlczIzB5rc');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupans`
--
ALTER TABLE `coupans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manual_payment_requests`
--
ALTER TABLE `manual_payment_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mock_tests`
--
ALTER TABLE `mock_tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mock_test_category`
--
ALTER TABLE `mock_test_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paid_couses`
--
ALTER TABLE `paid_couses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pdf_notes`
--
ALTER TABLE `pdf_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `syllabus`
--
ALTER TABLE `syllabus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_questions`
--
ALTER TABLE `test_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid_index` (`uid`);

--
-- Indexes for table `user_orders`
--
ALTER TABLE `user_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos_category`
--
ALTER TABLE `videos_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `youtube_videos`
--
ALTER TABLE `youtube_videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coupans`
--
ALTER TABLE `coupans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `manual_payment_requests`
--
ALTER TABLE `manual_payment_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mock_tests`
--
ALTER TABLE `mock_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mock_test_category`
--
ALTER TABLE `mock_test_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `paid_couses`
--
ALTER TABLE `paid_couses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pdf_notes`
--
ALTER TABLE `pdf_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- AUTO_INCREMENT for table `syllabus`
--
ALTER TABLE `syllabus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_orders`
--
ALTER TABLE `user_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `videos_category`
--
ALTER TABLE `videos_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `youtube_videos`
--
ALTER TABLE `youtube_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
