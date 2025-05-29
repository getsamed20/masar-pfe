-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 19 mai 2025 à 20:08
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `masar_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role` enum('super_admin','moderator') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`admin_id`, `user_id`, `role`, `created_at`) VALUES
(2, 16, 'super_admin', '2025-04-28 10:18:49');

-- --------------------------------------------------------

--
-- Structure de la table `challenges`
--

CREATE TABLE `challenges` (
  `challenge_id` int(11) NOT NULL,
  `institution_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('targeted','open-ended') DEFAULT 'open-ended',
  `status` enum('open','closed') DEFAULT 'open',
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deadline` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `file_attachment` varchar(255) DEFAULT NULL,
  `category` enum('Operations','Design & Planning','Land Use & Urban Planning','Vehicles','Automated Enforcement','ITS & Data Utilization','Police Enforcement','Legislation & Regulations','Training, Awareness & Education','Other') NOT NULL DEFAULT 'Other'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `challenges`
--

INSERT INTO `challenges` (`challenge_id`, `institution_id`, `title`, `description`, `type`, `status`, `posted_at`, `deadline`, `created_at`, `file_attachment`, `category`) VALUES
(1, 1, 'very hard challenge', 'i can\'t find my glasses TT\r\nwho find it shall be rewarded by a resto ticket!', 'open-ended', 'closed', '2025-04-15 22:30:52', '2025-05-17', '2025-04-16 00:30:52', NULL, 'Vehicles'),
(2, 1, 'kkllll', 'hjkhjkl', 'open-ended', 'closed', '2025-04-20 08:59:22', '2025-04-30', '2025-04-20 10:59:22', NULL, 'Other'),
(3, 1, 'challenge1', 'kk', 'open-ended', 'closed', '2025-05-08 16:21:24', '2025-05-09', '2025-05-08 18:21:24', NULL, 'Other'),
(4, 1, '', '', 'open-ended', 'closed', '2025-05-10 19:24:41', '0000-00-00', '2025-05-10 21:24:41', NULL, 'Other'),
(5, 1, 'challenge 5atir', 'uhfiuf', 'open-ended', 'closed', '2025-05-10 19:38:03', '2025-05-17', '2025-05-10 21:38:03', NULL, 'Other'),
(6, 1, 'ed', 'dddd', 'open-ended', 'closed', '2025-05-10 19:38:39', '2025-05-17', '2025-05-10 21:38:39', NULL, 'Other'),
(7, 1, 'idv', 'csdksv', 'open-ended', 'closed', '2025-05-11 19:29:56', '2025-05-05', '2025-05-11 21:29:56', NULL, 'Other'),
(8, 1, 'k', 'jjp\r\nk', 'open-ended', 'open', '2025-05-15 11:46:05', '2026-02-20', '2025-05-15 12:46:05', NULL, 'Design & Planning'),
(9, 1, 'c', 's', 'open-ended', 'open', '2025-05-16 16:52:50', '2026-12-12', '2025-05-16 17:52:50', NULL, 'Land Use & Urban Planning'),
(10, 1, 'challenge1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eget lectus ac velit laoreet bibendum in vitae lacus. In nec congue urna. Fusce porta, augue in interdum posuere, arcu ante euismod lorem, at scelerisque eros elit vitae velit. Vivamus et mauris metus. Duis in ante facilisis, elementum leo eget, venenatis nisl. Vestibulum quis lectus leo. Donec pharetra vitae mi iaculis auctor. Nunc ut quam sed lacus faucibus pulvinar. Donec faucibus mauris sed elementum posuere. Quisque ultrices nibh in magna blandit, a porttitor urna vestibulum. Aenean vel quam vel massa dapibus auctor. Maecenas lectus velit, eleifend eget gravida et, tincidunt quis magna. Proin sodales orci vitae massa blandit, in pharetra ante congue. Phasellus et semper elit, a venenatis justo.\r\n\r\nDonec hendrerit magna consequat lacus commodo, non elementum nibh fermentum. Aliquam erat volutpat. Vivamus sit amet pellentesque risus. Nullam hendrerit ultrices sem vitae pharetra. Curabitur tincidunt rutrum ex, sit amet auctor ante porta ac. Proin sit amet nisl ac magna bibendum pellentesque nec non velit. Vestibulum fringilla tristique mi, aliquet dictum metus porta sed. Nulla a urna ac velit sollicitudin dictum. Vestibulum a est venenatis, egestas diam vel, auctor turpis.\r\n\r\nPhasellus porta nulla tellus, eget ornare orci elementum ut. Cras facilisis dapibus nunc eu porttitor. Donec mauris mauris, fringilla a ullamcorper nec, fringilla quis enim. Sed eleifend cursus libero eu pellentesque. Nunc vitae mi metus. In at ipsum tellus. Donec ac bibendum turpis. Nullam bibendum eros justo, pretium dapibus nibh cursus feugiat. Nullam sagittis massa in dui varius, vitae vestibulum ante pharetra. Aliquam id aliquam sem. Proin et accumsan odio, sit amet interdum nulla. Nunc dictum, lacus at tincidunt cursus, est neque condimentum diam, ac malesuada nisi dolor ac odio. Sed eu dolor eu odio tempor tristique ut vel libero. Aenean nec dapibus mauris. Etiam ultrices malesuada quam, ac bibendum sapien. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.\r\n\r\nNam non arcu tellus. Curabitur lobortis tristique nunc, vel commodo diam sodales sit amet. Fusce ac orci tempus, hendrerit nunc ac, sodales urna. Sed id orci et urna dictum dictum posuere et lorem. Suspendisse fringilla nunc vel dui pellentesque suscipit. Mauris quis dapibus risus. Duis aliquam, eros non condimentum convallis, urna urna consectetur diam, quis sagittis leo tellus id lorem. Donec viverra quam ac leo viverra finibus eget id enim. Curabitur viverra neque faucibus efficitur pellentesque. Nunc massa ligula, pulvinar sed risus at, scelerisque egestas ipsum. Nulla aliquam, libero a ultricies semper, ex nunc imperdiet arcu, et pharetra justo nulla aliquet leo. Praesent et ligula nec justo malesuada tincidunt. Phasellus id cursus ipsum. Sed commodo malesuada ultrices. Maecenas nec enim felis. Vivamus augue libero, elementum a turpis ac, luctus ornare purus.\r\n\r\nNunc blandit dui id nibh feugiat tempor. Maecenas libero ipsum, consectetur at dolor nec, vehicula ullamcorper nunc. Aliquam scelerisque dui nec faucibus tristique. Vivamus faucibus orci in laoreet pulvinar. Fusce placerat convallis blandit. Proin non libero ut diam tempor efficitur. Integer euismod nunc eu ante feugiat, sit amet faucibus lacus interdum. Nunc efficitur est vel fringilla maximus. Nunc sit amet sagittis odio. Quisque eget suscipit risus. Vivamus auctor, nibh congue tempor condimentum, ex risus consequat nisi, quis sodales nisi massa eu velit. Suspendisse aliquam interdum leo, consectetur lacinia augue rutrum in.', 'open-ended', 'closed', '2025-05-17 13:37:30', '2005-12-12', '2025-05-17 14:37:30', NULL, 'Automated Enforcement');

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`document_id`, `admin_id`, `title`, `description`, `file_path`, `uploaded_at`) VALUES
(6, 2, 'doc 1', '', 'docs_uploads/68188c255490f.jpg', '2025-05-05 12:00:05'),
(8, 2, 'doc', '', 'docs_uploads/681fc01bae276.pdf', '2025-05-10 23:07:39');

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `institution_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `event_type` enum('online','offline') DEFAULT 'offline',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`event_id`, `institution_id`, `title`, `description`, `location`, `date`, `time`, `cover_image`, `event_type`, `created_at`) VALUES
(2, 1, 'community', 'hmdlhh', 'bardo', '2028-12-12', '12:12:00', '../../uploads/event_covers1746806155_image003.png', 'offline', '2025-04-20 12:05:19'),
(4, 1, 'ji', 'njknlk', 'jukhkj', '2025-05-16', '19:59:00', '../uploads/event_covers/1746865347_Diagramme de cas d’utilisation UML (5).jpg', 'offline', '2025-05-09 18:00:02'),
(7, 1, 'edee', 'eee', 'kl,', '2025-05-09', '15:08:00', 'uploads/event_covers/1746865035_image003.png', 'offline', '2025-05-10 10:17:15'),
(8, 1, 'team event', 'jisjc\r\n', 'll', '2025-04-04', '14:20:00', '../uploads/event_covers/1746865643_juego-team-building.jpg', 'offline', '2025-05-10 10:25:12'),
(9, 1, 'ljcl', 's,kl,s', 'kk', '2025-05-25', '10:28:00', 'uploads/event_covers/1746865619_User Registration.jpg', 'offline', '2025-05-10 10:26:59'),
(10, 1, 'hj', 'jj', 'j', '2025-05-23', '16:00:00', '../uploads/event_covers/1746868038_colorful-logo-design-03.jpg', 'offline', '2025-05-10 11:00:52'),
(11, 1, 'not cry', 'not cry', 'lll', '2025-05-16', '23:55:00', '../uploads/event_covers/', 'offline', '2025-05-10 22:09:32'),
(12, 1, 'd', 'd', 'dd', '0004-04-04', '05:05:00', 'uploads/event_covers/', 'offline', '2025-05-10 22:10:13'),
(13, 1, 'x', 'xx\r\n', '66', '0525-02-15', '05:05:00', '../uploads/event_covers/', 'offline', '2025-05-10 22:11:55'),
(14, 1, 'event', 'event', '5656', '2025-05-13', '22:42:00', '../../uploads/event_covers/1746909598_juego-team-building.jpg', 'offline', '2025-05-10 22:39:58'),
(18, 4, 'awel event', 'ggf', 'ezhnkjfel', '2028-12-12', '23:06:00', 'uploads/event_covers/1746988170_Diagramme de cas d’utilisation UML (4).jpg', 'online', '2025-05-11 20:29:30'),
(20, 1, 'ekfjezkf', 'ekkjr', '55', '2025-05-22', '05:05:00', 'uploads/event_covers/1747082281_Diagramme de cas d’utilisation UML (4).jpg', 'offline', '2025-05-12 22:38:01'),
(21, 1, 'cleaning', 'rr', 'd', '2025-11-10', '12:00:00', '', 'offline', '2025-05-13 17:52:11');

-- --------------------------------------------------------

--
-- Structure de la table `ideas`
--

CREATE TABLE `ideas` (
  `idea_id` int(11) NOT NULL,
  `startup_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ideas`
--

INSERT INTO `ideas` (`idea_id`, `startup_id`, `title`, `description`, `file_attachment`, `created_at`, `status`) VALUES
(7, 7, 'crying cure', 'a potion that makes code works lol', NULL, '2025-04-14 20:49:47', 'pending'),
(14, 7, 'thank god for everything', '', NULL, '2025-04-18 22:25:13', 'pending'),
(15, 7, 'idea', '', NULL, '2025-05-08 18:08:12', 'pending'),
(16, 7, 'idea1', '', NULL, '2025-05-08 18:52:35', 'pending'),
(17, 7, 'f', '', NULL, '2025-05-08 18:53:07', 'pending'),
(18, 7, 'g', 'g', NULL, '2025-05-09 21:16:42', 'pending'),
(19, 7, 'f', 'hhf', NULL, '2025-05-09 21:28:27', 'pending'),
(20, 7, 'dc', 'dd', NULL, '2025-05-12 19:27:14', 'pending'),
(21, 7, 'hi', 'hello', NULL, '2025-05-12 19:41:25', 'pending'),
(22, 7, '45', '8', NULL, '2025-05-12 19:52:20', 'pending'),
(23, 7, 'idea 1', '111', NULL, '2025-05-14 17:39:51', 'pending'),
(24, 7, 'k', 'j', NULL, '2025-05-14 18:58:23', 'pending');

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `media_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `idea_id` int(11) DEFAULT NULL,
  `media_type` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_institution_id` int(11) DEFAULT NULL,
  `challenge_id` int(11) DEFAULT NULL,
  `story_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`media_id`, `post_id`, `idea_id`, `media_type`, `file_path`, `created_at`, `post_institution_id`, `challenge_id`, `story_id`) VALUES
(13, NULL, 7, 'image', '67fd74eb90625.jpg', '2025-04-14 20:49:47', NULL, NULL, NULL),
(14, NULL, 7, 'image', '67fd74eb90caa.jpg', '2025-04-14 20:49:47', NULL, NULL, NULL),
(15, 1, NULL, 'video', '67fd752c237df.mp4', '2025-04-14 20:50:52', NULL, NULL, NULL),
(24, NULL, NULL, 'image', '6808da068ca00_test.png', '2025-04-23 12:16:06', 5, NULL, NULL),
(25, NULL, NULL, 'image', '6808db444aa92_OCCT-25315-065654.png', '2025-04-23 12:21:24', 6, NULL, NULL),
(26, NULL, NULL, 'image', '6808dbe24c165_test.png', '2025-04-23 12:24:02', 7, NULL, NULL),
(27, NULL, NULL, 'image', '6808e100e1d3e_WhatsApp Image 2025-03-21 at 10.01.50 PM.jpeg', '2025-04-23 12:45:52', 8, NULL, NULL),
(28, NULL, NULL, 'image', '681cd74ae019e_Diagramme de cas d’utilisation UML (4).jpg', '2025-05-08 16:09:46', 9, NULL, NULL),
(29, NULL, NULL, 'image', '681cd74ae20ea_Diagramme de cas d’utilisation UML (3).jpg', '2025-05-08 16:09:46', 9, NULL, NULL),
(30, NULL, NULL, 'application/pdf', '../uploads/challenges/challenge_681cda04cfd16.pdf', '2025-05-08 16:21:24', NULL, 3, NULL),
(31, NULL, 15, 'video', '681cf30c870df_gh.mp4', '2025-05-08 18:08:12', NULL, NULL, NULL),
(32, NULL, 16, 'application', '681cfd7323bb7_FoodTounsiRapportFinal.pdf', '2025-05-08 18:52:35', NULL, NULL, NULL),
(33, NULL, NULL, 'application', '681e203d9fc3f_Rapport PFE scrum.pdf', '2025-05-09 15:33:17', 11, NULL, NULL),
(34, NULL, NULL, 'application', '681e203da046b_User Registration (2) (1).pdf', '2025-05-09 15:33:17', 11, NULL, NULL),
(35, NULL, NULL, 'image', '681e206c09c23_User Registration (1).jpg', '2025-05-09 15:34:04', 12, NULL, NULL),
(36, NULL, NULL, 'video', '681e207dbcc93_gh.mp4', '2025-05-09 15:34:21', 13, NULL, NULL),
(37, 18, NULL, 'video', '681e561317de9_gh.mp4', '2025-05-09 19:22:59', NULL, NULL, NULL),
(38, 19, NULL, 'application', '681e561fa7991_Chapitre2 ICOO.pdf', '2025-05-09 19:23:11', NULL, NULL, NULL),
(42, NULL, 18, 'image', '681e70ba8099f_User Registration (2).jpg', '2025-05-09 21:16:42', NULL, NULL, NULL),
(43, NULL, 18, '', '681e70ba8199f_User Registration (2).jpg', '2025-05-09 21:16:42', NULL, NULL, NULL),
(44, NULL, 19, 'image', '681e737b17b1c_User Registration (1).jpg', '2025-05-09 21:28:27', NULL, NULL, NULL),
(45, NULL, NULL, 'application/pdf', '../uploads/challenges/challenge_681fab3f4843d.pdf', '2025-05-10 19:38:39', NULL, 6, NULL),
(46, NULL, NULL, 'application/pdf', '../uploads/challenges/challenge_681fab3f4a03e.pdf', '2025-05-10 19:38:39', NULL, 6, NULL),
(47, 21, NULL, 'image', '681fc8211b3dd_User Registration (3).jpg', '2025-05-10 21:41:53', NULL, NULL, NULL),
(51, NULL, NULL, 'image', 'uploads/success_stories/681fdba6b6960_Diagramme de cas d’utilisation UML (4).jpg', '2025-05-10 23:05:10', NULL, NULL, 3),
(54, 22, NULL, 'image', '681fde325a1d9_Diagramme de cas d’utilisation UML (5).jpg', '2025-05-10 23:16:02', NULL, NULL, NULL),
(55, 23, NULL, 'image', '681fdeab9d4d5_User Registration.jpg', '2025-05-10 23:18:03', NULL, NULL, NULL),
(56, 24, NULL, 'image', '681fdedb660e2_User Registration.jpg', '2025-05-10 23:18:51', NULL, NULL, NULL),
(57, 25, NULL, 'image', '681fdf3dbabe6_User Registration (1).jpg', '2025-05-10 23:20:29', NULL, NULL, NULL),
(58, 26, NULL, 'image', '681fdfff0e925_User Registration.jpg', '2025-05-10 23:23:43', NULL, NULL, NULL),
(59, NULL, NULL, '', '../../uploads/challenges/challenge_6820fab47a62f.pdf', '2025-05-11 19:29:56', NULL, 7, NULL),
(60, 27, NULL, 'image', '6821065f6d5de_Diagramme de cas d’utilisation UML (5).jpg', '2025-05-11 20:19:43', NULL, NULL, NULL),
(61, 28, NULL, 'image', '6821090e9d190_Diagramme de cas d’utilisation UML (5).jpg', '2025-05-11 20:31:10', NULL, NULL, NULL),
(62, 29, NULL, 'image', '682109198e012_Diagramme de cas d’utilisation UML (5).jpg', '2025-05-11 20:31:21', NULL, NULL, NULL),
(63, 30, NULL, 'image', '682109239024c_Diagramme de cas d’utilisation UML (5).jpg', '2025-05-11 20:31:31', NULL, NULL, NULL),
(64, 31, NULL, 'application', '6821f73bb9fab_Chapitre 1 ICOO.pdf', '2025-05-12 13:27:23', NULL, NULL, NULL),
(65, 32, NULL, 'image', '6821f752637c3_User Registration (1).jpg', '2025-05-12 13:27:46', NULL, NULL, NULL),
(66, 33, NULL, 'video', '68223b94d60e1_gh.mp4', '2025-05-12 18:19:00', NULL, NULL, NULL),
(67, 34, NULL, 'image', '68223ba76cc6b_User Registration (2).jpg', '2025-05-12 18:19:19', NULL, NULL, NULL),
(68, 34, NULL, 'image', '68223ba76d46e_User Registration (1).jpg', '2025-05-12 18:19:19', NULL, NULL, NULL),
(69, 34, NULL, 'image', '68223ba76dc19_User Registration.jpg', '2025-05-12 18:19:19', NULL, NULL, NULL),
(70, NULL, 20, 'image', '68224b925ccdd_Diagramme de cas d’utilisation UML (5).jpg', '2025-05-12 19:27:14', NULL, NULL, NULL),
(71, 35, NULL, 'application', '68224e951570e_Chapitre2 ICOO.pdf', '2025-05-12 19:40:05', NULL, NULL, NULL),
(72, 36, NULL, 'image', '68224eae6d55f_User Registration (1).jpg', '2025-05-12 19:40:30', NULL, NULL, NULL),
(73, NULL, 21, 'image', '68224ee59e106_User Registration (3).jpg', '2025-05-12 19:41:25', NULL, NULL, NULL),
(74, NULL, 22, 'image', '68225174dac7a_User Registration (1).jpg', '2025-05-12 19:52:20', NULL, NULL, NULL),
(75, NULL, NULL, 'image', '6822ff4daff4f_User Registration (2).jpg', '2025-05-13 08:14:05', 14, NULL, NULL),
(76, 38, NULL, 'image', '6824d556e30a3_User Registration (2).jpg', '2025-05-14 17:39:34', NULL, NULL, NULL),
(77, NULL, 23, 'image', '6824d5671c75b_Diagramme de cas d’utilisation UML (5).jpg', '2025-05-14 17:39:51', NULL, NULL, NULL),
(78, 39, NULL, 'image', '6824e7ba0677f_juego-team-building.jpg', '2025-05-14 18:58:02', NULL, NULL, NULL),
(79, NULL, 24, 'image', '6824e7cf5890f_User Registration (3).jpg', '2025-05-14 18:58:23', NULL, NULL, NULL),
(80, 40, NULL, 'video', '6824f7dc37a69_gh.mp4', '2025-05-14 20:06:52', NULL, NULL, NULL),
(81, NULL, NULL, '', '../../uploads/challenges/challenge_6825d3fd3cd37.jpg', '2025-05-15 11:46:05', NULL, 8, NULL),
(82, NULL, NULL, '', '../../uploads/challenges/challenge_68276d6293752.pdf', '2025-05-16 16:52:50', NULL, 9, NULL),
(83, NULL, NULL, '', '../../uploads/challenges/challenge_6828911a486e0.pdf', '2025-05-17 13:37:30', NULL, 10, NULL),
(84, NULL, NULL, 'image', 'uploads/success_stories/682898edf3112_ok.png', '2025-05-17 14:10:54', NULL, NULL, 5),
(85, NULL, NULL, 'image', 'uploads/success_stories/682898ee057b3_Placeholder.png', '2025-05-17 14:10:54', NULL, NULL, 5),
(86, NULL, NULL, 'image', 'uploads/success_stories/68289f352f4c8_logo.jpg', '2025-05-17 14:37:41', NULL, NULL, 6);

-- --------------------------------------------------------

--
-- Structure de la table `media_chat`
--

CREATE TABLE `media_chat` (
  `media_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `media_type` enum('image','video','audio','document','other') NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `media_chat`
--

INSERT INTO `media_chat` (`media_id`, `message_id`, `file_path`, `media_type`, `uploaded_at`) VALUES
(1, 68, 'chat_681644507f368.jpg', '', '2025-05-03 16:29:04'),
(2, 69, 'chat_6816445779b38.jpg', '', '2025-05-03 16:29:11'),
(3, 83, '1746374730_Diagramme de cas d’utilisation UML (5).jpg', 'image', '2025-05-04 16:05:30'),
(4, 84, '1746374861_Diagramme de cas d’utilisation UML (4).jpg', 'image', '2025-05-04 16:07:41'),
(5, 85, '681791e9b87c6_Diagramme de cas d’utilisation UML (4).jpg', 'image', '2025-05-04 16:12:25'),
(6, 88, '6817926ee60b2_Diagramme de cas d’utilisation UML (4).jpg', 'image', '2025-05-04 16:14:38'),
(7, 89, '6817946ec06a6_Diagramme de cas d’utilisation UML (4).jpg', 'image', '2025-05-04 16:23:10'),
(8, 90, '68179648729a0_l-060-23_Ghozzi-Moula-Mizy-2.pdf', 'document', '2025-05-04 16:31:04'),
(9, 91, '6817979a7f87f_تسجيل 2025-04-10 174059.mp4', 'video', '2025-05-04 16:36:42'),
(10, 92, '681799a24ddf1_Diagramme de cas d’utilisation UML (3).jpg', 'image', '2025-05-04 16:45:22'),
(11, 93, '681799bbaaff1_communication-indoor-games-for-team-building-1024x536.webp', 'other', '2025-05-04 16:45:47'),
(12, 94, '681799c925d30_juego-team-building.jpg', 'image', '2025-05-04 16:46:01'),
(13, 97, '6817a16e5066a_Diagramme de cas d’utilisation UML (2).jpg', 'image', '2025-05-04 17:18:38'),
(14, 98, '6817a196b1204_Diagramme de cas d’utilisation UML (3).jpg', 'image', '2025-05-04 17:19:18'),
(15, 99, '6817a1ca945fd_تسجيل 2025-04-10 174059.mp4', 'video', '2025-05-04 17:20:10'),
(16, 105, '6817b8e1ce02e_Diagramme de cas d’utilisation UML (2).jpg', 'image', '2025-05-04 18:58:41'),
(17, 137, '68236ec113441_Diagramme de cas d’utilisation UML (5).jpg', 'image', '2025-05-13 16:09:37'),
(18, 138, '68236ecc9deb1_Diagramme de cas d’utilisation UML (4).jpg', 'image', '2025-05-13 16:09:48'),
(19, 139, '68236f1315b5a_User Registration.jpg', 'image', '2025-05-13 16:10:59'),
(20, 143, '6828a5d71859d_Placeholder.png', 'image', '2025-05-17 15:05:59'),
(21, 144, '6828a600bb475_masar-logo.png', 'image', '2025-05-17 15:06:40'),
(22, 145, '6828a60fec757_download.jpg', 'image', '2025-05-17 15:06:55');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `file_attachment`, `sent_at`, `seen`) VALUES
(1, 11, 2, 'hi oneshot', NULL, '2025-04-22 20:40:18', 0),
(2, 11, 8, 'hi unkown', NULL, '2025-04-22 20:43:54', 1),
(3, 11, 8, 'sup', NULL, '2025-04-22 20:44:00', 1),
(4, 3, 2, 'hi oneshottt', NULL, '2025-04-22 20:59:44', 0),
(5, 8, 11, 'hola', NULL, '2025-04-22 21:00:46', 1),
(6, 8, 11, 'sad sad', NULL, '2025-04-22 21:06:20', 1),
(7, 3, 11, 'hhijnioj', NULL, '2025-04-22 21:42:16', 1),
(8, 8, 4, 'sub', NULL, '2025-04-24 20:59:16', 0),
(9, 11, 8, 'hi', NULL, '2025-04-25 13:13:09', 1),
(10, 8, 11, 'hello heloo', NULL, '2025-04-25 13:14:41', 1),
(11, 11, 11, 'hi', NULL, '2025-04-25 13:21:28', 1),
(12, 11, 11, 'hello idk who', NULL, '2025-04-25 13:21:39', 1),
(13, 11, 3, 'hi one shot its peace', NULL, '2025-04-25 13:29:53', 1),
(14, 11, 2, 'hi salt', NULL, '2025-04-25 13:30:07', 0),
(15, 3, 2, 'hi saly\r\n', NULL, '2025-04-25 13:37:24', 0),
(16, 11, 13, 'hi ', NULL, '2025-04-25 13:46:41', 0),
(17, 11, 13, 'yoss', NULL, '2025-04-25 13:46:45', 0),
(18, 11, 13, 'like yoo', NULL, '2025-04-25 13:46:51', 0),
(19, 8, 8, 'hi ', NULL, '2025-04-25 13:48:57', 1),
(20, 8, 8, 'sup', NULL, '2025-04-25 13:49:02', 1),
(21, 8, 8, 'ccccccccccc', NULL, '2025-04-25 16:00:26', 1),
(22, 8, 8, 'why i can never see you in contacts page :(', NULL, '2025-04-25 16:08:41', 1),
(23, 8, 11, 'k', NULL, '2025-04-25 16:40:47', 1),
(24, 8, 8, 'hi', NULL, '2025-04-25 16:41:15', 1),
(25, 8, 4, 'hurdjlrgkmdrl', NULL, '2025-04-25 17:02:41', 0),
(26, 11, 2, 'pepper', NULL, '2025-04-25 17:03:02', 0),
(27, 11, 13, 'kkkkkkk', NULL, '2025-04-25 17:03:11', 0),
(28, 11, 13, ',,', NULL, '2025-04-25 17:03:20', 0),
(29, 11, 11, ',,', NULL, '2025-04-25 17:03:25', 1),
(30, 11, 11, 'kkk', NULL, '2025-04-25 17:03:37', 1),
(31, 11, 11, ';', NULL, '2025-04-25 17:05:39', 1),
(32, 11, 8, ',', NULL, '2025-04-25 17:05:44', 1),
(33, 11, 11, ',,,', NULL, '2025-04-25 17:05:50', 1),
(34, 11, 8, ',', NULL, '2025-04-25 17:24:00', 1),
(35, 11, 13, 'jfclks', NULL, '2025-04-25 17:24:07', 0),
(36, 11, 11, 'jkdsfk<', NULL, '2025-04-25 17:24:11', 1),
(37, 11, 3, 'jnjkn', NULL, '2025-04-25 17:24:23', 1),
(38, 11, 11, 'مرحبا', NULL, '2025-04-25 18:31:14', 1),
(39, 11, 11, 'ûjuiji', NULL, '2025-04-26 17:25:57', 1),
(40, 11, 11, 'jijijio', NULL, '2025-04-26 17:26:10', 1),
(41, 11, 8, 'oijoi', NULL, '2025-04-26 17:26:19', 1),
(42, 8, 11, 'jkoikoi', NULL, '2025-04-26 17:28:00', 1),
(43, 8, 11, 'nkj', NULL, '2025-04-26 17:28:20', 1),
(44, 8, 4, 'ikçikà', NULL, '2025-04-26 17:28:26', 0),
(45, 8, 3, 'hi one', NULL, '2025-04-26 19:10:31', 1),
(46, 3, 11, 'hniuni', NULL, '2025-04-26 19:17:00', 1),
(47, 3, 11, 'uhni', NULL, '2025-04-26 19:18:06', 1),
(48, 3, 11, 'hii', NULL, '2025-04-26 19:19:43', 1),
(49, 3, 11, 'hii', NULL, '2025-04-26 19:21:15', 1),
(50, 3, 11, 'hello', NULL, '2025-04-26 19:27:54', 1),
(51, 3, 8, 'workk?', NULL, '2025-04-26 19:28:04', 1),
(52, 3, 8, ',kl,sml', NULL, '2025-04-26 19:28:13', 1),
(53, 8, 11, 'i can\'t believe!!!', NULL, '2025-04-26 19:28:37', 1),
(54, 11, 8, 'hiiii', NULL, '2025-04-26 20:15:03', 1),
(55, 8, 11, 'hehehe', NULL, '2025-04-26 20:15:28', 1),
(56, 8, 3, 'hi', NULL, '2025-04-27 18:20:16', 1),
(57, 11, 14, 'hi me', NULL, '2025-04-27 19:56:13', 0),
(58, 11, 8, 'hnhnj', NULL, '2025-04-28 10:51:57', 1),
(59, 8, 8, '', 'chat_68162a7f2f208.jpg', '2025-05-03 14:38:55', 1),
(60, 8, 11, '', 'chat_68162c05459e6.jpg', '2025-05-03 14:45:25', 1),
(61, 11, 8, '', 'chat_68162c57c3daf.pdf', '2025-05-03 14:46:47', 1),
(62, 11, 8, '', 'chat_68162d79584e6.mp4', '2025-05-03 14:51:37', 1),
(63, 11, 14, 'hi', '', '2025-05-03 15:55:41', 0),
(64, 11, 14, '', 'chat_68163e16d9f90.jpg', '2025-05-03 16:02:30', 0),
(65, 11, 14, '', '', '2025-05-03 16:18:28', 0),
(66, 11, 14, '', '', '2025-05-03 16:19:20', 0),
(67, 11, 14, '', '', '2025-05-03 16:19:35', 0),
(68, 11, 14, 'you s', NULL, '2025-05-03 16:29:04', 0),
(69, 11, 14, '', NULL, '2025-05-03 16:29:11', 0),
(70, 11, 14, 'h,hg;v', '', '2025-05-03 16:41:44', 0),
(71, 11, 2, 'htfdj', NULL, '2025-05-03 18:18:23', 0),
(72, 11, 8, 'l', NULL, '2025-05-03 18:18:26', 1),
(73, 13, 11, 'stalker', NULL, '2025-05-04 13:51:52', 1),
(74, 11, 3, 'c', NULL, '2025-05-04 13:52:18', 1),
(75, 11, 8, 'h', NULL, '2025-05-04 15:38:09', 1),
(76, 11, 8, 'w', NULL, '2025-05-04 15:38:19', 1),
(77, 11, 8, 'w', NULL, '2025-05-04 15:38:21', 1),
(78, 11, 8, 'w', NULL, '2025-05-04 15:38:23', 1),
(79, 11, 8, 'w', NULL, '2025-05-04 15:38:26', 1),
(80, 11, 8, 'w', NULL, '2025-05-04 15:38:39', 1),
(81, 11, 8, 'w', NULL, '2025-05-04 15:38:41', 1),
(82, 11, 8, 'w', NULL, '2025-05-04 15:38:44', 1),
(83, 11, 13, '', NULL, '2025-05-04 16:05:30', 0),
(84, 11, 13, '', NULL, '2025-05-04 16:07:41', 0),
(85, 11, 13, 'rfs', NULL, '2025-05-04 16:12:25', 0),
(86, 11, 13, 'cdsfq', NULL, '2025-05-04 16:12:53', 0),
(87, 11, 13, '', NULL, '2025-05-04 16:14:14', 0),
(88, 11, 13, '', NULL, '2025-05-04 16:14:38', 0),
(89, 11, 13, '', NULL, '2025-05-04 16:23:10', 0),
(90, 11, 13, '', NULL, '2025-05-04 16:31:04', 0),
(91, 11, 8, '', NULL, '2025-05-04 16:36:42', 1),
(92, 11, 8, '', NULL, '2025-05-04 16:45:22', 1),
(93, 11, 8, 'perfect team!', NULL, '2025-05-04 16:45:47', 1),
(94, 11, 8, 'team!', NULL, '2025-05-04 16:46:01', 1),
(95, 11, 8, 'yjklhkjnjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjmgjkshj,rtm,nflm,nfld,klyjfykdtk', NULL, '2025-05-04 16:46:26', 1),
(96, 11, 8, 'g', NULL, '2025-05-04 16:46:35', 1),
(97, 11, 8, '', NULL, '2025-05-04 17:18:38', 1),
(98, 11, 8, '', NULL, '2025-05-04 17:19:18', 1),
(99, 11, 8, '', NULL, '2025-05-04 17:20:10', 1),
(100, 11, 8, 'hjknk', NULL, '2025-05-04 17:21:26', 1),
(101, 11, 13, 'j', NULL, '2025-05-04 18:26:03', 0),
(102, 11, 8, 'jnk', NULL, '2025-05-04 18:26:10', 1),
(103, 8, 15, 'sam', NULL, '2025-05-04 18:48:39', 0),
(104, 8, 11, 'xx', NULL, '2025-05-04 18:48:49', 1),
(105, 8, 8, '', NULL, '2025-05-04 18:58:41', 1),
(106, 11, 2, 'gfgfchcf', NULL, '2025-05-05 15:25:50', 0),
(107, 11, 2, 'dfxbhdh', NULL, '2025-05-05 15:25:57', 0),
(108, 11, 2, 'drxgxh', NULL, '2025-05-05 15:26:04', 0),
(109, 11, 8, 'n,', NULL, '2025-05-08 12:34:53', 1),
(110, 8, 11, 'hi peace', NULL, '2025-05-09 09:15:07', 1),
(111, 8, 8, 'hi me', NULL, '2025-05-09 09:15:17', 1),
(112, 8, 3, 'suspanded?', NULL, '2025-05-09 09:15:43', 1),
(113, 8, 15, 'hi', NULL, '2025-05-09 09:15:56', 0),
(114, 11, 8, 'hi', NULL, '2025-05-09 09:38:55', 1),
(115, 8, 15, 'hi', NULL, '2025-05-09 09:39:30', 0),
(116, 11, 8, 'i want to work with you', NULL, '2025-05-09 09:40:00', 1),
(117, 11, 8, 'any idea?', NULL, '2025-05-09 09:40:15', 1),
(118, 8, 11, 'yes of', NULL, '2025-05-09 10:06:08', 1),
(119, 8, 11, 'c', NULL, '2025-05-09 10:06:15', 1),
(120, 8, 15, 'kl;ml', NULL, '2025-05-09 10:06:21', 0),
(121, 11, 2, 'ccc', NULL, '2025-05-09 10:07:02', 0),
(122, 11, 8, 'sam', NULL, '2025-05-09 10:07:24', 1),
(123, 8, 8, 'ggg', NULL, '2025-05-09 10:08:02', 1),
(124, 8, 11, 'ggggg', NULL, '2025-05-09 10:08:09', 1),
(125, 11, 8, 'bfjngkghkkh', NULL, '2025-05-09 10:09:27', 1),
(126, 8, 8, 'y', NULL, '2025-05-09 22:25:10', 1),
(127, 8, 8, 'ccc', NULL, '2025-05-09 22:25:14', 1),
(128, 8, 8, 'nnnn', NULL, '2025-05-09 22:26:53', 1),
(129, 8, 11, 'it\'s me', NULL, '2025-05-09 22:27:19', 1),
(130, 8, 11, 'seen the message?', NULL, '2025-05-09 22:27:31', 1),
(131, 8, 11, 'hi', NULL, '2025-05-11 19:51:21', 1),
(132, 11, 8, 'jgrho', NULL, '2025-05-13 14:59:44', 1),
(133, 11, 8, 'grg', NULL, '2025-05-13 14:59:47', 1),
(134, 8, 15, 'm', NULL, '2025-05-13 15:01:48', 0),
(135, 8, 8, '', NULL, '2025-05-13 15:29:45', 1),
(136, 8, 8, '', NULL, '2025-05-13 15:48:16', 1),
(137, 8, 8, '', NULL, '2025-05-13 16:09:37', 1),
(138, 8, 8, '', NULL, '2025-05-13 16:09:48', 1),
(139, 8, 11, '', NULL, '2025-05-13 16:10:59', 1),
(140, 8, 11, 'hi', NULL, '2025-05-13 16:11:08', 1),
(141, 16, 23, 'gnb', NULL, '2025-05-17 15:05:22', 0),
(142, 16, 11, 'hi', NULL, '2025-05-17 15:05:48', 0),
(143, 16, 11, '', NULL, '2025-05-17 15:05:59', 0),
(144, 16, 11, '', NULL, '2025-05-17 15:06:40', 0),
(145, 16, 11, '', NULL, '2025-05-17 15:06:55', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pending_accounts`
--

CREATE TABLE `pending_accounts` (
  `id` int(11) NOT NULL,
  `role` enum('startup','institution') NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `unique_identifier` varchar(255) DEFAULT NULL,
  `commercial_register` varchar(255) DEFAULT NULL,
  `validated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pending_accounts`
--

INSERT INTO `pending_accounts` (`id`, `role`, `name`, `email`, `password`, `unique_identifier`, `commercial_register`, `validated`, `created_at`, `logo`) VALUES
(21, 'startup', 'me the great i got this i can do it no matter what it takes!!!', 'gbhebhgjoj@gmail.com', '$2y$10$P8OG3kLw24PQEenni/QFQ.9T8ikAg9sR0Y5SWZ6eQsUAi7TpdSt5i', '148852369', 'User Registration (2) (1).pdf', 0, '2025-05-11 17:37:11', NULL),
(23, 'startup', 'startup12', 'hbbgf@gmail.com', '$2y$10$6CYxKQTiWJAOOPhb3qdPneeVsAbjWF1UbfIuO1N1uqwQgd8yu7e8m', '123456789', 'User Registration (1) (1).pdf', 0, '2025-05-12 08:28:01', NULL),
(26, 'startup', 'testyos', 'testyos@test.com', '$2y$10$Qnm.LTWD/ivPlXhCGZM6ae5d9JTwAhWXwJmADfi4CTMSyU390mnpO', '85266', 'Chapitre2 ICOO.pdf', 0, '2025-05-13 11:41:47', NULL),
(27, 'startup', 'startup15', 'startup15@gmail.com', '$2y$10$yLTHWFVv7pNbWxiA62tzFerBh3Ykc6jT7L5MZwcKHt.Lv/dHLJPgO', '147852369', 'User Registration (1) (1).pdf', 0, '2025-05-17 11:06:55', NULL),
(29, 'startup', 'test', 'test22@gmail.com', '$2y$10$GCrS4Hu3qE1bc4CyYhnqdupEMjdwhVpdTumuNh5noT1W7ayOCotPu', '147852369', 'Rapport PFE scrum.pdf', 0, '2025-05-17 11:21:41', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `startup_id` int(11) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reported` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`post_id`, `startup_id`, `title`, `content`, `image`, `created_at`, `reported`) VALUES
(1, 7, 'can you believe it!!', 'a so wow platform yey', NULL, '2025-04-14 20:50:52', 1),
(14, 7, NULL, 'fqf', NULL, '2025-05-07 09:05:28', 0),
(16, 7, NULL, 'ffffffffffffffffffffff', NULL, '2025-05-07 09:08:47', 0),
(18, 7, NULL, 'm', NULL, '2025-05-09 19:22:59', 0),
(19, 7, NULL, 'm', NULL, '2025-05-09 19:23:11', 0),
(20, 7, NULL, 'k', NULL, '2025-05-09 19:23:30', 0),
(21, 7, NULL, 'kk', NULL, '2025-05-10 21:41:53', 0),
(22, 7, NULL, 'l', NULL, '2025-05-10 23:16:02', 0),
(23, 7, NULL, 'll', NULL, '2025-05-10 23:18:03', 0),
(24, 7, NULL, '^l^l', NULL, '2025-05-10 23:18:51', 0),
(25, 7, NULL, 'p', NULL, '2025-05-10 23:20:29', 0),
(26, 7, NULL, 'kkml', NULL, '2025-05-10 23:23:43', 0),
(27, 7, NULL, ')f^psd', NULL, '2025-05-11 20:19:43', 0),
(28, 7, 'jlko', 'kolk', NULL, '2025-05-11 20:31:10', 0),
(29, 7, 'jlkokllk', 'kolk', NULL, '2025-05-11 20:31:21', 0),
(30, 7, 'jlkokllknk,l', 'kolkkl,k', NULL, '2025-05-11 20:31:31', 0),
(31, 7, 'tired', 'tired', NULL, '2025-05-12 13:27:23', 0),
(32, 7, 'tired', 'd', NULL, '2025-05-12 13:27:46', 0),
(33, 7, NULL, 'k', NULL, '2025-05-12 18:19:00', 0),
(34, 7, NULL, 'klkl', NULL, '2025-05-12 18:19:19', 0),
(35, 7, NULL, 'ب', NULL, '2025-05-12 19:40:05', 0),
(36, 7, NULL, 'س', NULL, '2025-05-12 19:40:30', 0),
(37, 7, NULL, 'new post ya allah ya karim', NULL, '2025-05-14 17:39:15', 0),
(38, 7, NULL, '1', NULL, '2025-05-14 17:39:34', 0),
(39, 7, NULL, 'k', NULL, '2025-05-14 18:58:02', 0),
(40, 7, NULL, 'sx', NULL, '2025-05-14 20:06:52', 0);

-- --------------------------------------------------------

--
-- Structure de la table `posts_institution`
--

CREATE TABLE `posts_institution` (
  `post_id` int(11) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `reported` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts_institution`
--

INSERT INTO `posts_institution` (`post_id`, `institution_id`, `title`, `content`, `image`, `created_at`, `reported`) VALUES
(1, 1, 'k', 'kl', NULL, '2025-04-23 12:25:51', 0),
(2, 1, 'k', 'lll', NULL, '2025-04-23 12:26:10', 0),
(3, 1, 'fekraaa heyla', 'f', NULL, '2025-04-23 12:34:09', 0),
(4, 1, 'fgf', 'dgfg', NULL, '2025-04-23 12:34:36', 0),
(5, 1, 'thank god for everything', 'sas', NULL, '2025-04-23 14:16:06', 0),
(6, 1, 'cc', 'cv', NULL, '2025-04-23 14:21:24', 0),
(7, 1, 'd', 'd', NULL, '2025-04-23 14:24:02', 0),
(8, 1, 'kg', 'g', NULL, '2025-04-23 14:45:52', 0),
(9, 1, '', 'lklkl', NULL, '2025-05-08 18:09:46', 0),
(10, 1, '', 'ost', NULL, '2025-05-09 17:33:06', 0),
(11, 1, '', 'post', NULL, '2025-05-09 17:33:17', 0),
(12, 1, '', 'kk', NULL, '2025-05-09 17:34:04', 0),
(13, 1, '', 'good', NULL, '2025-05-09 17:34:21', 0),
(14, 1, '', 'ugl', NULL, '2025-05-13 10:14:05', 0);

-- --------------------------------------------------------

--
-- Structure de la table `public_institutions`
--

CREATE TABLE `public_institutions` (
  `institution_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `institution_name` varchar(150) NOT NULL,
  `unique_identifier` varchar(50) NOT NULL,
  `commercial_register` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `about_section` text DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `x_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `public_institutions`
--

INSERT INTO `public_institutions` (`institution_id`, `user_id`, `institution_name`, `unique_identifier`, `commercial_register`, `description`, `contact_email`, `logo`, `type`, `about_section`, `phone_number`, `address`, `website_url`, `facebook_link`, `linkedin_link`, `x_link`, `instagram_link`) VALUES
(1, 8, 'Municipality of Manouba', 'a1256398', 'agenda.pdf', NULL, 'contact@nat.tn', '6802ccf61c709_pngtree-sea-logo-vector-image_77937.jpg', NULL, 'informations', '70897413', 'manouba', 'https://translate.google.com/', '', '', '', ''),
(2, 13, 'yossa', '852369741', 'agenda.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 17, 'dd', '123456789', 'User Registration.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 23, 'inst', '741258963', 'User Registration.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 24, 'instlmmmmm', '14785', 'User Registration.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 29, 'yahyaoui big hospital', '565465465465', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 30, 'yahyaoui big hospital', '6665465465', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 33, 'yossaujny', '147852369', 'masar.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 37, 'test23', '147852369', 'FoodTounsiRapportFinal.pdf', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_institution_id` int(11) DEFAULT NULL,
  `reporter_name` varchar(255) DEFAULT NULL,
  `reporter_email` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `reported_at` datetime DEFAULT current_timestamp(),
  `post_owner` enum('startup','institution') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reports`
--

INSERT INTO `reports` (`report_id`, `post_id`, `post_institution_id`, `reporter_name`, `reporter_email`, `reason`, `reported_at`, `post_owner`) VALUES
(4, 16, NULL, '', 'municipalityofmanouba@nat.tn', 'Hate', '2025-05-10 23:10:34', 'startup'),
(5, NULL, 9, '', 'peace1@gmail.com', 'Off-topic', '2025-05-10 23:11:41', 'startup'),
(6, NULL, 2, '', 'peace1@gmail.com', 'Spam', '2025-05-11 21:52:50', 'startup'),
(7, NULL, 2, '', 'peace1@gmail.com', 'Spam', '2025-05-11 21:53:11', 'startup');

-- --------------------------------------------------------

--
-- Structure de la table `solutions`
--

CREATE TABLE `solutions` (
  `solution_id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `startup_id` int(11) NOT NULL,
  `proposal_title` varchar(255) NOT NULL,
  `proposal_description` text NOT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `status` enum('pending','rejected','selected','under review') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `solutions`
--

INSERT INTO `solutions` (`solution_id`, `challenge_id`, `startup_id`, `proposal_title`, `proposal_description`, `file_attachment`, `submitted_at`, `status`) VALUES
(1, 1, 7, 'thank god for everything', 'joijooooooooooojkhkkkkkkkkkk^$^$l', '../uploads/solutions/1745688301_Diagramme de cas d’utilisation UML (3).jpg', '2025-04-22 21:15:34', 'pending'),
(2, 1, 7, 'thank god for everything', 'joij', 'uploads/solutions/6807eadbacc45_image003.png', '2025-04-22 21:15:39', 'pending'),
(3, 1, 7, 'bjkkjjilk', 'hhhhhhhh', 'uploads/solutions/6807eae756058_juego-team-building.jpg', '2025-04-22 21:15:51', 'pending'),
(4, 1, 2, 'fekraaa heyla w jamila', 'hehehe', 'uploads/solutions/68080e0edea46_pfe masar.pdf', '2025-04-22 23:45:50', 'pending'),
(5, 2, 7, 'yos', 'iljlkjk', '', '2025-04-23 11:10:30', 'selected'),
(6, 1, 2, 'gughku', 'kjhk', 'uploads/solutions/680d35c9e5569_User Registration (3).jpg', '2025-04-26 21:36:41', 'under review');

-- --------------------------------------------------------

--
-- Structure de la table `startups`
--

CREATE TABLE `startups` (
  `startup_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `startup_name` varchar(150) NOT NULL,
  `unique_identifier` varchar(50) NOT NULL,
  `commercial_register` varchar(255) NOT NULL,
  `about_section` text DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `x_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `startups`
--

INSERT INTO `startups` (`startup_id`, `user_id`, `startup_name`, `unique_identifier`, `commercial_register`, `about_section`, `contact_email`, `phone_number`, `address`, `logo`, `website_url`, `facebook_link`, `linkedin_link`, `x_link`, `instagram_link`) VALUES
(1, 2, 'salt', '123456', 'MethodesdesignUX2018TDM.pdf', '', '', '', '', '6825ca0649eee_download.jpg', '', '', '', '', ''),
(2, 3, 'oneshot', ',kl,l7', 'agenda.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 4, 'the com', '123456987', 'agenda.pdf', 'i', 'contactthecom@gmail.com', '70852369', 'somewhere', '6825cad7ce9f8_download.jpg', 'https://translate.google.com/', '', '', '', ''),
(4, 5, 'y&sam', '789654123', 'masar.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 11, 'peace1', '668767878866', 'Plan-du-rapport-PFE1.pdf', 'a startup wow \r\n', 'startup@gmail.com', '708552365', 'island', '6803ebfd3e54f_colorful-logo-design-03.jpg', '', '', '', '', ''),
(9, 14, 'me', '123456789', 'pfe masar.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 15, 'yosss', '123456789', 'User Registration.pdf', '', '', '', '', '680d3dd8eceba_image003.png', '', '', '', '', ''),
(11, 19, 'startup12', '123456789', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 25, 'Youssef\'s big startup', '646546135', '', '', '', '', '', '6825c88e86696_logo.jpg', '', '', '', '', ''),
(15, 28, 'ysj', '95956', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 31, 'perfecto', '147852369', 'User Registration (1) (1).pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 32, 'besto', '14524546', 'User Registration (2).pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 34, 'o', '26945896514', 'تصنيف العقود الإلكترونية حسب طبيعة الطلب.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 35, 'masar', '465465465', 'User Registration (2).pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 36, 'test', '123456', 'User Registration.pdf', NULL, NULL, NULL, NULL, '68287dd86bafd.jpg', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `success_stories`
--

CREATE TABLE `success_stories` (
  `story_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `success_stories`
--

INSERT INTO `success_stories` (`story_id`, `title`, `content`, `created_at`) VALUES
(3, 'k', 'k', '2025-05-10 23:05:10'),
(5, 'story', 'great success story', '2025-05-17 14:10:53'),
(6, 'long story', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eget lectus ac velit laoreet bibendum in vitae lacus. In nec congue urna. Fusce porta, augue in interdum posuere, arcu ante euismod lorem, at scelerisque eros elit vitae velit. Vivamus et mauris metus. Duis in ante facilisis, elementum leo eget, venenatis nisl. Vestibulum quis lectus leo. Donec pharetra vitae mi iaculis auctor. Nunc ut quam sed lacus faucibus pulvinar. Donec faucibus mauris sed elementum posuere. Quisque ultrices nibh in magna blandit, a porttitor urna vestibulum. Aenean vel quam vel massa dapibus auctor. Maecenas lectus velit, eleifend eget gravida et, tincidunt quis magna. Proin sodales orci vitae massa blandit, in pharetra ante congue. Phasellus et semper elit, a venenatis justo.\r\n\r\nDonec hendrerit magna consequat lacus commodo, non elementum nibh fermentum. Aliquam erat volutpat. Vivamus sit amet pellentesque risus. Nullam hendrerit ultrices sem vitae pharetra. Curabitur tincidunt rutrum ex, sit amet auctor ante porta ac. Proin sit amet nisl ac magna bibendum pellentesque nec non velit. Vestibulum fringilla tristique mi, aliquet dictum metus porta sed. Nulla a urna ac velit sollicitudin dictum. Vestibulum a est venenatis, egestas diam vel, auctor turpis.\r\n\r\nPhasellus porta nulla tellus, eget ornare orci elementum ut. Cras facilisis dapibus nunc eu porttitor. Donec mauris mauris, fringilla a ullamcorper nec, fringilla quis enim. Sed eleifend cursus libero eu pellentesque. Nunc vitae mi metus. In at ipsum tellus. Donec ac bibendum turpis. Nullam bibendum eros justo, pretium dapibus nibh cursus feugiat. Nullam sagittis massa in dui varius, vitae vestibulum ante pharetra. Aliquam id aliquam sem. Proin et accumsan odio, sit amet interdum nulla. Nunc dictum, lacus at tincidunt cursus, est neque condimentum diam, ac malesuada nisi dolor ac odio. Sed eu dolor eu odio tempor tristique ut vel libero. Aenean nec dapibus mauris. Etiam ultrices malesuada quam, ac bibendum sapien. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.\r\n\r\nNam non arcu tellus. Curabitur lobortis tristique nunc, vel commodo diam sodales sit amet. Fusce ac orci tempus, hendrerit nunc ac, sodales urna. Sed id orci et urna dictum dictum posuere et lorem. Suspendisse fringilla nunc vel dui pellentesque suscipit. Mauris quis dapibus risus. Duis aliquam, eros non condimentum convallis, urna urna consectetur diam, quis sagittis leo tellus id lorem. Donec viverra quam ac leo viverra finibus eget id enim. Curabitur viverra neque faucibus efficitur pellentesque. Nunc massa ligula, pulvinar sed risus at, scelerisque egestas ipsum. Nulla aliquam, libero a ultricies semper, ex nunc imperdiet arcu, et pharetra justo nulla aliquet leo. Praesent et ligula nec justo malesuada tincidunt. Phasellus id cursus ipsum. Sed commodo malesuada ultrices. Maecenas nec enim felis. Vivamus augue libero, elementum a turpis ac, luctus ornare purus.\r\n\r\nNunc blandit dui id nibh feugiat tempor. Maecenas libero ipsum, consectetur at dolor nec, vehicula ullamcorper nunc. Aliquam scelerisque dui nec faucibus tristique. Vivamus faucibus orci in laoreet pulvinar. Fusce placerat convallis blandit. Proin non libero ut diam tempor efficitur. Integer euismod nunc eu ante feugiat, sit amet faucibus lacus interdum. Nunc efficitur est vel fringilla maximus. Nunc sit amet sagittis odio. Quisque eget suscipit risus. Vivamus auctor, nibh congue tempor condimentum, ex risus consequat nisi, quis sodales nisi massa eu velit. Suspendisse aliquam interdum leo, consectetur lacinia augue rutrum in.', '2025-05-17 14:37:41');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('startup','institution','admin') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(2, 'a9@gmail.com ', '$2y$10$9Gpi7XNtoOuj7JxwMaoDXuhMNqv1ar3ACjEHCevEnrffNMgcXsRE6', 'startup', 'active', '2025-04-13 12:10:22'),
(3, 'oneshot@gmail.com', '$2y$10$KWs3LQ3BL9piAobEIkcywOyy/xKCQbbettSt4KO.I1dsKcFmD/5qq', 'startup', 'active', '2025-04-13 12:19:45'),
(4, 'thecom@gmail.com', '$2y$10$c/uSyqSBBIV1z8IsZRhzAOvllpWRAKmMUtnS4C7zjehpPUXpiPCHy', 'startup', 'active', '2025-04-13 12:20:22'),
(5, 'yossam@gmail.com', '123456', 'startup', 'active', '2025-04-13 12:35:01'),
(8, 'municipalityofmanouba@nat.tn', '$2y$10$6d19bV4C5hbxhFrplKfA6eb.m/VNtLDf6ZwQsR6gZYsXEPQmBO7Sq', 'institution', 'active', '2025-04-13 13:19:11'),
(11, 'peace1@gmail.com', '$2y$10$fiOA6s5uAuzHciT5HucMauiae.8pBcr7vFdnIDVuJXHdx.71NAy8.', 'startup', 'active', '2025-04-13 14:37:27'),
(12, 'great@gmail.com', '123456', 'startup', 'active', '2025-04-14 12:42:46'),
(13, 'youssra.yah@gmail.com', '123456', 'institution', 'active', '2025-04-16 15:32:27'),
(14, 'me@gmail.com', '123456', 'startup', 'inactive', '2025-04-22 20:46:14'),
(15, 'hediuf@gmail.com', '123456', 'startup', 'active', '2025-04-26 20:10:26'),
(16, 'admin@gmail.com', '123456', 'admin', 'active', '2025-04-27 08:23:32'),
(17, 'mouhammed@gmail.com', '123456', 'institution', 'inactive', '2025-04-28 12:51:12'),
(19, 'startup12@gmail.com', '123456', 'startup', 'active', '2025-04-30 13:03:24'),
(20, '12346789@gmail.com', '123456', 'institution', 'active', '2025-04-30 13:05:45'),
(21, 'help@gmailcom', '123456', 'institution', 'active', '2025-04-30 13:16:45'),
(22, '14@gmail.com', '$2y$10$1IUW/nH/8KjB4YljgIFP7e/AZjI0KGR/d7i8r1Lyw/VCsP2zfzEfi', 'institution', 'active', '2025-04-30 14:27:13'),
(23, 'ist@gmail.com', '$2y$10$lmg5dNo10Lz.tK7plKep2OVv2sRcO.A6IzMEJ.894In0zr09CSOcy', 'institution', 'active', '2025-04-30 15:16:17'),
(24, 'i464st@gmail.com', '$2y$10$puXG12cj4oyttusMk9J6a.i2Ep38aOxrRJNeq4MBwiKFO3IwHprnW', 'institution', 'active', '2025-04-30 15:34:07'),
(25, 'Youseffellani@gmail.com', '$2y$10$1kvpLgUNV6.POCAsj1ouz.TmOo2yhs1gJN58OhxGsSNu1PVz8TNaW', 'startup', 'active', '2025-04-30 23:25:02'),
(28, 'yahyaoui@gmail.com ', '$2y$10$azwyVpX4yw9c/5o0gOo99uj6hyzwb3lOG1hHpODKWH0IpJSYPwt.i', 'startup', 'active', '2025-05-01 18:33:35'),
(29, 'yahyaouiyoussra9@gmail.com ', '$2y$10$c1XcStpxLkNOZelW2b5aMefjhDaVXz7lUHyKyntc6VlAR4vCPqnFy', 'institution', 'active', '2025-05-02 14:41:20'),
(30, 'samira.mommed@gmail.com', '$2y$10$jgJRdLEf6N5HynW/BosBrOb.xgtHZmfX.VmvHasKAeis2nO0VzoFi', 'institution', 'active', '2025-05-02 14:43:26'),
(31, 'a.mouhammed@gmail.com', '$2y$10$oLucmYFH/U7NVJ6WGTE60usCuP0CZegjVmzKoZLzzREuROtLDCnw.', 'startup', 'active', '2025-05-10 23:55:24'),
(32, 'meda@gmail.com', '$2y$10$arTquTkmBOEyfXA1zRAx2u6zn6BGu7UR7UUe0oq7rS0ws9fl6A5YK', 'startup', 'active', '2025-05-11 12:39:35'),
(33, 'samira.mouhammed@@gmail.com', '$2y$10$7IS3v1gN62DUo4jF2hs0Uu9pqQJifU.8IxV/Jtj8YDan/ROxbOhEi', 'institution', 'active', '2025-05-11 12:44:00'),
(34, 'samira.mouhammed@gmail.com', '$2y$10$7QpkSEWwy.gOnxsinpkoLOon6lKEjPP5K1PGK/OXMl.U1Ir0RfGWy', 'startup', 'active', '2025-05-11 12:46:30'),
(35, 'masar.platform.tn@gmail.com', '$2y$10$99VtDDqtcVsNxp/38.YjR.E2VM7pJiX/uem2uw0olQf5bZXok0CF.', 'startup', 'active', '2025-05-11 17:39:55'),
(36, 'test28@gmail.com', '$2y$10$NZTCaWqqHqOb/u2HsSHZuusTnPv3aSttIYnGZ5Khcc9d0W/JKmzsG', 'startup', 'active', '2025-05-17 12:27:46'),
(37, 'test23@gmail.com', '$2y$10$ehW.v8llQl7zwGYJRo4OJOsH3J/2Yl46swCql6JuokOkvHXA9YOzq', 'institution', 'active', '2025-05-17 13:34:09');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `fk_admins_user` (`user_id`) USING BTREE;

--
-- Index pour la table `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`challenge_id`),
  ADD KEY `fk_challenges_institution` (`institution_id`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `fk_events_institution` (`institution_id`);

--
-- Index pour la table `ideas`
--
ALTER TABLE `ideas`
  ADD PRIMARY KEY (`idea_id`),
  ADD KEY `fk_ideas_startup` (`startup_id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `idea_id` (`idea_id`),
  ADD KEY `fk_post_institution` (`post_institution_id`),
  ADD KEY `fk_media_challenge` (`challenge_id`),
  ADD KEY `fk_media_story` (`story_id`);

--
-- Index pour la table `media_chat`
--
ALTER TABLE `media_chat`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `message_id` (`message_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `fk_messages_sender` (`sender_id`),
  ADD KEY `fk_messages_receiver` (`receiver_id`);

--
-- Index pour la table `pending_accounts`
--
ALTER TABLE `pending_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `fk_posts_startup` (`startup_id`);

--
-- Index pour la table `posts_institution`
--
ALTER TABLE `posts_institution`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `fk_posts_institution_institution_id` (`institution_id`);

--
-- Index pour la table `public_institutions`
--
ALTER TABLE `public_institutions`
  ADD PRIMARY KEY (`institution_id`),
  ADD KEY `fk_institutions_user` (`user_id`);

--
-- Index pour la table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `institution_post_id` (`post_institution_id`);

--
-- Index pour la table `solutions`
--
ALTER TABLE `solutions`
  ADD PRIMARY KEY (`solution_id`),
  ADD KEY `fk_solution_challenge` (`challenge_id`),
  ADD KEY `fk_solution_startup` (`startup_id`);

--
-- Index pour la table `startups`
--
ALTER TABLE `startups`
  ADD PRIMARY KEY (`startup_id`),
  ADD KEY `fk_startups_user` (`user_id`);

--
-- Index pour la table `success_stories`
--
ALTER TABLE `success_stories`
  ADD PRIMARY KEY (`story_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `challenges`
--
ALTER TABLE `challenges`
  MODIFY `challenge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `ideas`
--
ALTER TABLE `ideas`
  MODIFY `idea_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT pour la table `media_chat`
--
ALTER TABLE `media_chat`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT pour la table `pending_accounts`
--
ALTER TABLE `pending_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `posts_institution`
--
ALTER TABLE `posts_institution`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `public_institutions`
--
ALTER TABLE `public_institutions`
  MODIFY `institution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `solutions`
--
ALTER TABLE `solutions`
  MODIFY `solution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `startups`
--
ALTER TABLE `startups`
  MODIFY `startup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `success_stories`
--
ALTER TABLE `success_stories`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_admins_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `challenges`
--
ALTER TABLE `challenges`
  ADD CONSTRAINT `fk_challenges_institution` FOREIGN KEY (`institution_id`) REFERENCES `public_institutions` (`institution_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_institution` FOREIGN KEY (`institution_id`) REFERENCES `public_institutions` (`institution_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ideas`
--
ALTER TABLE `ideas`
  ADD CONSTRAINT `fk_ideas_startup` FOREIGN KEY (`startup_id`) REFERENCES `startups` (`startup_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_challenge` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`challenge_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_media_story` FOREIGN KEY (`story_id`) REFERENCES `success_stories` (`story_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_institution` FOREIGN KEY (`post_institution_id`) REFERENCES `posts_institution` (`post_id`),
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `media_ibfk_2` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`idea_id`);

--
-- Contraintes pour la table `media_chat`
--
ALTER TABLE `media_chat`
  ADD CONSTRAINT `media_chat_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`message_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_startup` FOREIGN KEY (`startup_id`) REFERENCES `startups` (`startup_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `posts_institution`
--
ALTER TABLE `posts_institution`
  ADD CONSTRAINT `fk_posts_institution_institution_id` FOREIGN KEY (`institution_id`) REFERENCES `public_institutions` (`institution_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `public_institutions`
--
ALTER TABLE `public_institutions`
  ADD CONSTRAINT `fk_institutions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`post_institution_id`) REFERENCES `posts_institution` (`post_id`);

--
-- Contraintes pour la table `solutions`
--
ALTER TABLE `solutions`
  ADD CONSTRAINT `fk_solution_challenge` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`challenge_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_solution_startup` FOREIGN KEY (`startup_id`) REFERENCES `startups` (`startup_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `startups`
--
ALTER TABLE `startups`
  ADD CONSTRAINT `fk_startups_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
