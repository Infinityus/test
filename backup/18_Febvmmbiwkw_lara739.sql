-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 19, 2026 at 10:08 AM
-- Server version: 8.0.44-cll-lve
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vmmbiwkw_lara739`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_16_103112_create_personal_access_tokens_table', 2),
(5, '2026_02_17_075719_create_tbl_device_table', 3),
(6, '2026_02_17_081139_create_tbl_device_table', 4),
(7, '2026_02_17_125446_add_set_limit_to_tbl_otp_table', 5),
(8, '2026_02_17_125752_add_set_limit_to_tbl_otp_table', 6),
(9, '2026_02_17_132113_add_set_limit_to_tbl_otp_table', 7),
(10, '2026_02_18_121941_create_tbl_device_history_table', 8),
(11, '2026_02_18_150621_create_tbl_wallet_table', 9),
(12, '2026_02_18_151030_create_tbl_wallet_transactions_table', 9),
(13, '2026_02_18_151729_create_tbl_wallet_table', 10),
(14, '2026_02_18_151803_create_tbl_wallet_transactions_table', 10),
(15, '2026_02_18_191508_create_tbl_whatsapp_api_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 4, 'mobile-app-4', 'add5e264d8700ac3ed74499b6d623961ae2885f910a5d454b2825ab363271571', '[\"*\"]', NULL, '2026-03-19 07:27:55', '2026-02-17 01:57:55', '2026-02-17 01:57:55'),
(2, 'App\\Models\\User', 1, 'test-token', 'f88847d4724b15ae52a658e7839b1b3e27839e854201842f5b47c9584c9257cc', '[\"*\"]', NULL, NULL, '2026-02-17 03:02:17', '2026-02-17 03:02:17'),
(3, 'App\\Models\\User', 4, 'mobile-app-4', '28819766284450e0a4ee33e82388507ce09b4231614fa89d1bd0ca94a23edb22', '[\"*\"]', NULL, '2026-03-19 09:14:12', '2026-02-17 03:44:12', '2026-02-17 03:44:12'),
(4, 'App\\Models\\User', 4, 'mobile-app-4', '0366e24da2a8a04017ab423beb0a810fd39bf2a71f798e4b45b345eb283618a3', '[\"*\"]', NULL, '2026-03-19 09:18:26', '2026-02-17 03:48:26', '2026-02-17 03:48:26'),
(5, 'App\\Models\\User', 4, 'mobile-app-4', '466bf3b4a5f680319b1078be27e5fc30f97725145d2885c207733c65dca91ea3', '[\"*\"]', NULL, '2026-03-19 09:19:51', '2026-02-17 03:49:51', '2026-02-17 03:49:51'),
(6, 'App\\Models\\User', 4, 'mobile-app-4', '95bdf6f48aea9146563e3ef31a4accbf95fa5b04a439c03694820e05408d7e9e', '[\"*\"]', NULL, '2026-03-19 09:20:49', '2026-02-17 03:50:49', '2026-02-17 03:50:49'),
(7, 'App\\Models\\User', 4, 'mobile-app-4', '9d9d7d8df37b6b746c2538ffa7252376d8966494205c4db21bef183ae89e2da6', '[\"*\"]', NULL, '2026-03-19 09:46:28', '2026-02-17 04:16:28', '2026-02-17 04:16:28'),
(8, 'App\\Models\\User', 4, 'mobile-app-4', '327f1bfe0f9b8f53a0bcb69f7dc4b012cd46afccfc722b4eebbba525b9115aac', '[\"*\"]', NULL, '2026-03-19 09:46:59', '2026-02-17 04:16:59', '2026-02-17 04:16:59'),
(9, 'App\\Models\\User', 4, 'mobile-app-4', 'acc2760f5ebc0d2eaf10b98fc9c225a1aa4662883c2b26ad4b88a53fbb0c991f', '[\"*\"]', NULL, '2026-03-19 09:48:34', '2026-02-17 04:18:34', '2026-02-17 04:18:34'),
(10, 'App\\Models\\User', 4, 'mobile-app-4', '481420bc6d8bddd3a607344a76e2d99e3be318cae0f004a0a0be408988788b82', '[\"*\"]', NULL, '2026-03-19 09:48:36', '2026-02-17 04:18:36', '2026-02-17 04:18:36'),
(11, 'App\\Models\\User', 4, 'mobile-app-4', '39d8e31fa42827d634a92c13eb0930ef4251c77de57b8ff83b6246311f3d6439', '[\"*\"]', NULL, '2026-03-19 09:48:38', '2026-02-17 04:18:38', '2026-02-17 04:18:38'),
(12, 'App\\Models\\User', 4, 'mobile-app-4', 'e448ffda65d31ac9fff63e6fd7b85e2d38dfd7948ec47636f985ee6c01cd022a', '[\"*\"]', NULL, '2026-03-19 09:53:17', '2026-02-17 04:23:17', '2026-02-17 04:23:17'),
(13, 'App\\Models\\User', 4, 'mobile-app-4', '7f94180b12e7cacf91de6b20057a83c8e522a1e4c8e80a9db81158e00cdb9e23', '[\"*\"]', NULL, '2026-03-19 09:53:20', '2026-02-17 04:23:20', '2026-02-17 04:23:20'),
(14, 'App\\Models\\User', 4, 'mobile-app-4', '9e87e10100a742a6f55478190b8b480c7009ebfa90ac2a388bcc450257b6730c', '[\"*\"]', NULL, '2026-03-19 09:53:22', '2026-02-17 04:23:22', '2026-02-17 04:23:22'),
(15, 'App\\Models\\User', 4, 'mobile-app-4', '342eb5e6e91d6a916f2d64f9dab1429db582d71afa9c9b4982a76b961a6882ab', '[\"*\"]', NULL, '2026-03-19 09:53:24', '2026-02-17 04:23:24', '2026-02-17 04:23:24'),
(16, 'App\\Models\\User', 4, 'mobile-app-4', 'df98dac3b17353eea4f753e6ca4ecf3d16d773905dc10a8fc0d2a178b3cf4aa7', '[\"*\"]', NULL, '2026-03-19 09:53:25', '2026-02-17 04:23:25', '2026-02-17 04:23:25'),
(17, 'App\\Models\\User', 4, 'mobile-app-4', '00def16249559b07363faadb675b00c878694d5ac2c31cc1ff8b55809383d71e', '[\"*\"]', NULL, '2026-03-19 09:53:37', '2026-02-17 04:23:37', '2026-02-17 04:23:37'),
(18, 'App\\Models\\User', 4, 'mobile-app-4', '28a35d7206abde1ab572ce4b5629df6687e966d8741fe2e2d7fa84f8e8140749', '[\"*\"]', NULL, '2026-03-19 09:54:32', '2026-02-17 04:24:32', '2026-02-17 04:24:32'),
(19, 'App\\Models\\User', 4, 'mobile-app-4', '1822ba3556f91cee1e700bcaeab317ef28bcc94a3bf90f25aaf9a280907bd576', '[\"*\"]', NULL, '2026-03-19 09:58:01', '2026-02-17 04:28:01', '2026-02-17 04:28:01'),
(20, 'App\\Models\\User', 4, 'mobile-app-4', '75b181faeb098ee37847e86ed83f2fc76a2f2b6c6b988097a9b91317ddabb67b', '[\"*\"]', NULL, '2026-03-19 10:01:00', '2026-02-17 04:31:00', '2026-02-17 04:31:00'),
(21, 'App\\Models\\User', 4, 'mobile-app-4', 'e90cf1403a5f4b90fb4d0dcba6df23648579d137ffc285f63156e751fd61a080', '[\"*\"]', NULL, '2026-03-19 10:03:09', '2026-02-17 04:33:09', '2026-02-17 04:33:09'),
(22, 'App\\Models\\User', 4, 'mobile-app-4', '5f6bfeace00bed61e173500446a1eb81d38717b53b7bd1086ba76263b151d7fe', '[\"*\"]', NULL, '2026-03-19 10:04:26', '2026-02-17 04:34:26', '2026-02-17 04:34:26'),
(23, 'App\\Models\\User', 4, 'mobile-app-4', '3baf42f226612260083df98416b1038330f471979fd0f860114d7a8c9ee8e957', '[\"*\"]', NULL, '2026-03-19 10:06:10', '2026-02-17 04:36:10', '2026-02-17 04:36:10'),
(24, 'App\\Models\\User', 4, 'mobile-app-4', 'ade4ebd0ef88823fc38b76037207f445c1842ad9cdabb994f663d11107f1703b', '[\"*\"]', NULL, '2026-03-19 10:06:11', '2026-02-17 04:36:11', '2026-02-17 04:36:11'),
(25, 'App\\Models\\User', 4, 'mobile-app-4', '621a5a66de30abb2593b4258e564a8132193d89cbc8f4b416f025d0d937c5496', '[\"*\"]', NULL, '2026-03-19 10:18:40', '2026-02-17 04:48:40', '2026-02-17 04:48:40'),
(26, 'App\\Models\\User', 4, 'mobile-app-4', 'acf06017c760acb048df5cb11bf171d836f35b964bdf41e01a4fde43a0fafda4', '[\"*\"]', NULL, '2026-03-19 10:25:19', '2026-02-17 04:55:19', '2026-02-17 04:55:19'),
(27, 'App\\Models\\User', 4, 'mobile-app-4', 'adb3af81241e2a6ac054dfcd79c69dbc2cefb3219738f504503ef00c1fe47159', '[\"*\"]', NULL, '2026-03-19 10:26:19', '2026-02-17 04:56:19', '2026-02-17 04:56:19'),
(28, 'App\\Models\\User', 4, 'mobile-app-4', 'e14ef58af3716288c7a85a15c59c80492b819e076fcb489b461ed3eb88098906', '[\"*\"]', NULL, '2026-03-19 10:28:44', '2026-02-17 04:58:44', '2026-02-17 04:58:44'),
(29, 'App\\Models\\User', 4, 'mobile-app-4', '3d2d49ddd22df6c66366701677d7b0b776b455101ee4977ebf358b88d38d8cf7', '[\"*\"]', NULL, '2026-03-19 10:32:53', '2026-02-17 05:02:53', '2026-02-17 05:02:53'),
(30, 'App\\Models\\User', 4, 'mobile-app-4', 'ee71423088b3a5fbfda5493ca68f915fa83c0de5aa2ff847acf2114772d94794', '[\"*\"]', NULL, '2026-03-19 10:33:01', '2026-02-17 05:03:01', '2026-02-17 05:03:01'),
(31, 'App\\Models\\User', 4, 'mobile-app-4', '75fd447459cf081de254db5638138542b6eee84b130df96393b3eb83d13b48a1', '[\"*\"]', NULL, '2026-03-19 10:33:03', '2026-02-17 05:03:03', '2026-02-17 05:03:03'),
(32, 'App\\Models\\User', 4, 'mobile-app-4', '1680b6238b0ce23425701d1f84b3b3345685dad1f58c55ef14af5bbf1c1c75f0', '[\"*\"]', NULL, '2026-03-19 10:33:04', '2026-02-17 05:03:04', '2026-02-17 05:03:04'),
(33, 'App\\Models\\User', 4, 'mobile-app-4', '3d0e329f8c275c4c338bd5d84f0f8b9cc83f0e2181de0f12f1dfb38f82173400', '[\"*\"]', NULL, '2026-03-19 10:33:06', '2026-02-17 05:03:06', '2026-02-17 05:03:06'),
(34, 'App\\Models\\User', 4, 'mobile-app-4', 'ea9808f2943418257c27a2feb050167bed7830db68af1e86898be0e1759f28c8', '[\"*\"]', NULL, '2026-03-19 10:33:07', '2026-02-17 05:03:07', '2026-02-17 05:03:07'),
(35, 'App\\Models\\User', 4, 'mobile-app-4', '8f2bb6be7575205dfbd4b36382f2c04e0aad4d4632eb6432ed25adc1a55637e4', '[\"*\"]', NULL, '2026-03-19 10:45:22', '2026-02-17 05:15:22', '2026-02-17 05:15:22'),
(36, 'App\\Models\\User', 4, 'mobile-app-4', 'f8116938bef2782543c06b618bd482e7824c5a98cea11a4e86a79a17d07c2f52', '[\"*\"]', NULL, '2026-03-19 11:35:47', '2026-02-17 06:05:47', '2026-02-17 06:05:47'),
(37, 'App\\Models\\User', 4, 'mobile-app-4', '3398039636e3e68b86a588e879f1e4ec3c4cac80d8da67e06f1293e0e403f9e4', '[\"*\"]', NULL, '2026-03-19 11:36:11', '2026-02-17 06:06:11', '2026-02-17 06:06:11'),
(38, 'App\\Models\\User', 4, 'mobile-app-4', 'cc2cee245dc6a30c7870070b44e9a2f6f2546f8c2d54fc51025557e12ddde48a', '[\"*\"]', NULL, '2026-03-19 11:36:13', '2026-02-17 06:06:13', '2026-02-17 06:06:13'),
(39, 'App\\Models\\User', 4, 'mobile-app-4', '76edaf3f9916c9978c33138835bde127a47d1a6ccac59815e019e5d184a4d9df', '[\"*\"]', NULL, '2026-03-19 11:38:48', '2026-02-17 06:08:48', '2026-02-17 06:08:48'),
(40, 'App\\Models\\User', 4, 'mobile-app-4', '66a657b80f226ac59d000a3734b4fd3f86b955ddbb19f4cbd86e119e5082bd59', '[\"*\"]', NULL, '2026-03-19 11:38:57', '2026-02-17 06:08:57', '2026-02-17 06:08:57'),
(41, 'App\\Models\\User', 4, 'mobile-app-4', 'd2645968e76e83c8697f4f0a89d286e7c2b3b80f95e246b5ad6d88f1f9a14540', '[\"*\"]', NULL, '2026-03-19 11:39:12', '2026-02-17 06:09:12', '2026-02-17 06:09:12'),
(42, 'App\\Models\\User', 4, 'mobile-app-4', '00e544a9e88cc4b2fcec3e7ddc72517fcd8c62e438239f2042f05657ae5a5115', '[\"*\"]', NULL, '2026-03-19 11:40:27', '2026-02-17 06:10:27', '2026-02-17 06:10:27'),
(43, 'App\\Models\\User', 4, 'mobile-app-4', '1ebb0639716ce6f153535ece5ced4e8559c17f5ec6574d4f2356e2cca9bed7de', '[\"*\"]', NULL, '2026-03-19 11:43:19', '2026-02-17 06:13:19', '2026-02-17 06:13:19'),
(44, 'App\\Models\\User', 4, 'mobile-app-4', '2c99ec3a93b401a156d4a7519777366ebd8c51b5dd71fb0cfb8ee32783868d3b', '[\"*\"]', NULL, '2026-03-19 11:43:30', '2026-02-17 06:13:30', '2026-02-17 06:13:30'),
(45, 'App\\Models\\User', 4, 'mobile-app-4', '3617665e8e76e763617c7d6632a8720b2b9225c3021102d7b20400445a659819', '[\"*\"]', NULL, '2026-03-19 11:43:57', '2026-02-17 06:13:57', '2026-02-17 06:13:57'),
(46, 'App\\Models\\User', 4, 'mobile-app-4', 'e116de57896e35f4e85a94c29b77d8e85110f2b81a05b71027144624c8486705', '[\"*\"]', NULL, '2026-03-19 11:56:20', '2026-02-17 06:26:20', '2026-02-17 06:26:20'),
(47, 'App\\Models\\User', 4, 'mobile-app-4', '9fd1c9917c9eee582e72934a25151b605871d4b591d4d7f61971ee02c56e3cd3', '[\"*\"]', NULL, '2026-03-19 11:56:31', '2026-02-17 06:26:31', '2026-02-17 06:26:31'),
(48, 'App\\Models\\User', 4, 'mobile-app-4', '2ebe7bf72c94b06ee3d087f326286fd0f3e8e8c40235c0871a1627763fb5367f', '[\"*\"]', NULL, '2026-03-19 11:56:40', '2026-02-17 06:26:40', '2026-02-17 06:26:40'),
(49, 'App\\Models\\User', 4, 'mobile-app-4', 'eb4fa9c598b5fc7eafd40d7c5d0544cfe536bd00e82bac6571d50b22f4cfcb29', '[\"*\"]', NULL, '2026-03-19 11:57:14', '2026-02-17 06:27:14', '2026-02-17 06:27:14'),
(50, 'App\\Models\\User', 4, 'mobile-app-4', 'a10f9a2713caebf9509ba743f6f9a165367e75c43e1e1025255a961e810d9ad1', '[\"*\"]', NULL, '2026-03-19 11:58:08', '2026-02-17 06:28:08', '2026-02-17 06:28:08'),
(51, 'App\\Models\\User', 4, 'mobile-app-4', '35bdd33747355ff926debf20e0bc6101276211ff30504292f936dd0f2131d5a1', '[\"*\"]', NULL, '2026-03-19 11:58:12', '2026-02-17 06:28:12', '2026-02-17 06:28:12'),
(52, 'App\\Models\\User', 4, 'mobile-app-4', 'ea954e9cedfb032efd7805e8e3f85bf5815d06d7bc40d8f0273d58e8eaccf85f', '[\"*\"]', NULL, '2026-03-19 11:58:24', '2026-02-17 06:28:24', '2026-02-17 06:28:24'),
(53, 'App\\Models\\User', 4, 'mobile-app-4', 'a2904ca7b883934e1227838b79fd04de112633f325ed682d62843fae3dd453f0', '[\"*\"]', NULL, '2026-03-19 11:59:28', '2026-02-17 06:29:28', '2026-02-17 06:29:28'),
(54, 'App\\Models\\User', 4, 'mobile-app-4', '90d8099ac5c8e3e2aa2ee4ccc7620e7bd62c58cb9593da797c9c1a2f0a666ec0', '[\"*\"]', NULL, '2026-03-19 11:59:35', '2026-02-17 06:29:35', '2026-02-17 06:29:35'),
(55, 'App\\Models\\User', 4, 'mobile-app-4', '80b3df1902c930174ecc696b92c88802f3eea4b4f4eef6245c17f0c635d499f0', '[\"*\"]', NULL, '2026-03-19 12:00:55', '2026-02-17 06:30:55', '2026-02-17 06:30:55'),
(56, 'App\\Models\\User', 4, 'mobile-app-4', '387b9a328ce027482dba7680ee33627c75456a767998adb680225b4af3f9989c', '[\"*\"]', NULL, '2026-03-19 12:01:00', '2026-02-17 06:31:00', '2026-02-17 06:31:00'),
(57, 'App\\Models\\User', 4, 'mobile-app-4', '22d7d3050731ada107ee5edd91073ea86d0efd3db091dd8ca92f20c45f595185', '[\"*\"]', NULL, '2026-03-19 12:01:03', '2026-02-17 06:31:03', '2026-02-17 06:31:03'),
(58, 'App\\Models\\User', 4, 'mobile-app-4', '3763d270665b5e7022992ff03e0c15d5b30e77139b4043817f75edb7632ceee1', '[\"*\"]', NULL, '2026-03-19 12:01:20', '2026-02-17 06:31:20', '2026-02-17 06:31:20'),
(59, 'App\\Models\\User', 4, 'mobile-app-4', 'f5c1f77dff407321c4d246121324acffd7b8ebb32c2101ed3049dfc1452cbbc7', '[\"*\"]', NULL, '2026-03-19 12:02:53', '2026-02-17 06:32:53', '2026-02-17 06:32:53'),
(60, 'App\\Models\\User', 4, 'mobile-app-4', '5a26daa50aab4f30e1dc56ae7b88f939fb8a5a9f20767d2b7a4b1311893eff6f', '[\"*\"]', NULL, '2026-03-19 12:03:02', '2026-02-17 06:33:02', '2026-02-17 06:33:02'),
(61, 'App\\Models\\User', 4, 'mobile-app-4', '08b669c157eb34517cb5405c7ab311de8e2045f79ab0954c39e4fb04f511e54c', '[\"*\"]', NULL, '2026-03-19 12:04:03', '2026-02-17 06:34:03', '2026-02-17 06:34:03'),
(62, 'App\\Models\\User', 4, 'mobile-app-4', '0dd8c12f778df5bfe72cb0fb5632d845b322c77a36fd5daec79f5440490d6cf0', '[\"*\"]', NULL, '2026-03-19 12:04:05', '2026-02-17 06:34:05', '2026-02-17 06:34:05'),
(63, 'App\\Models\\User', 4, 'mobile-app-4', '21a7cc2d97ea0021b12527b4a5d6cceb4f437132190e09bf36022cd63fae1b23', '[\"*\"]', NULL, '2026-03-19 12:04:08', '2026-02-17 06:34:08', '2026-02-17 06:34:08'),
(64, 'App\\Models\\User', 4, 'mobile-app-4', 'e1eaa1f40f389fc316928f435cbbd250aebca6213d79474dcb02da63b0c28fe2', '[\"*\"]', NULL, '2026-03-19 12:07:43', '2026-02-17 06:37:43', '2026-02-17 06:37:43'),
(65, 'App\\Models\\User', 4, 'mobile-app-4', '0aba9f4c592095130dcc2e63c1ad9a65b763c2d243357c4efebd05498f16ea98', '[\"*\"]', NULL, '2026-03-19 12:07:45', '2026-02-17 06:37:45', '2026-02-17 06:37:45'),
(66, 'App\\Models\\User', 4, 'mobile-app-4', 'bc9e2a1c548995cdac217fbd7d56568fe7b11f338c152cee46ca58b737328653', '[\"*\"]', NULL, '2026-03-19 12:07:50', '2026-02-17 06:37:50', '2026-02-17 06:37:50'),
(67, 'App\\Models\\User', 4, 'mobile-app-4', '2e4e46fed2e0cda36c3a2940743988ed6bf9258ea1e82a0b662d0dc6d0aa2dd0', '[\"*\"]', NULL, '2026-03-19 12:09:36', '2026-02-17 06:39:36', '2026-02-17 06:39:36'),
(68, 'App\\Models\\User', 4, 'mobile-app-4', '3bd9e572e58a09052327173dd37f15139d87c76a7b1512a45aa3d0dff4564880', '[\"*\"]', NULL, '2026-03-19 12:09:37', '2026-02-17 06:39:37', '2026-02-17 06:39:37'),
(69, 'App\\Models\\User', 4, 'mobile-app-4', '3abcbe72ed1cc930c4974fa4876f38d02deeed6cab4e4e713ad650d5235f4f77', '[\"*\"]', NULL, '2026-03-19 12:09:40', '2026-02-17 06:39:40', '2026-02-17 06:39:40'),
(70, 'App\\Models\\User', 4, 'mobile-app-4', '246715ad94d48414d69b24f3bc4947bce97caa25197489a5fa72138ae5f5fcc9', '[\"*\"]', NULL, '2026-03-19 12:10:12', '2026-02-17 06:40:12', '2026-02-17 06:40:12'),
(71, 'App\\Models\\User', 4, 'mobile-app-4', '1f79d7070bd991b3be27038dbf112a97c5fcf22c3db58fc3d87e48ff00eeaf12', '[\"*\"]', NULL, '2026-03-19 12:10:19', '2026-02-17 06:40:19', '2026-02-17 06:40:19'),
(72, 'App\\Models\\User', 4, 'mobile-app-4', '81b8f5ad0a5c7effc45015888271f12f79e2a97c5577457f37f40eb8b621c3fb', '[\"*\"]', NULL, '2026-03-19 12:10:25', '2026-02-17 06:40:25', '2026-02-17 06:40:25'),
(73, 'App\\Models\\User', 4, 'mobile-app-4', '8a90153119285c4aa268ef056a95d455ea28ba6e9411fa9f02767ea77a44c313', '[\"*\"]', NULL, '2026-03-19 12:11:07', '2026-02-17 06:41:07', '2026-02-17 06:41:07'),
(74, 'App\\Models\\User', 4, 'mobile-app-4', '7d6da165c70e1f5f0fbadecdf45cd36ccebbfb08f94a2b73fb1e82f2d6224622', '[\"*\"]', NULL, '2026-03-19 12:11:11', '2026-02-17 06:41:11', '2026-02-17 06:41:11'),
(75, 'App\\Models\\User', 4, 'mobile-app-4', 'f56e2dab19548db669fd22b69189c02d7fee99f3a002f417a3eeb02ab8ed4181', '[\"*\"]', NULL, '2026-03-19 12:11:20', '2026-02-17 06:41:20', '2026-02-17 06:41:20'),
(76, 'App\\Models\\User', 6, 'mobile-app-6', '653b6ce4fd972c759fc05be1673571e40d4318ea0a8b0230e8603700ea134b08', '[\"*\"]', NULL, '2026-03-19 13:13:26', '2026-02-17 07:43:26', '2026-02-17 07:43:26'),
(77, 'App\\Models\\User', 6, 'mobile-app-6', '13166e818d03d26966f495a29ea1e9c40bb875fcb9fdce9247fa4955eb299947', '[\"*\"]', NULL, '2026-03-19 13:14:21', '2026-02-17 07:44:21', '2026-02-17 07:44:21'),
(78, 'App\\Models\\User', 6, 'mobile-app-6', '86f0f8709b6972bf729c5e2588d2f1253962d2f2b9ce5f5cac93fc3d5cd62e74', '[\"*\"]', NULL, '2026-03-19 13:15:03', '2026-02-17 07:45:03', '2026-02-17 07:45:03'),
(79, 'App\\Models\\User', 5, 'mobile-app-5', '2d265f91b066181e04320efc778ff930633d4fa78d3ee04b40925602f47fa9a8', '[\"*\"]', NULL, '2026-03-19 16:31:17', '2026-02-17 16:31:17', '2026-02-17 16:31:17'),
(80, 'App\\Models\\User', 5, 'mobile-app-5', '7ce946947997de007e5e83530e7a68faa3e179914c192ad57f5b8c4d8f058639', '[\"*\"]', NULL, '2026-03-19 16:34:39', '2026-02-17 16:34:39', '2026-02-17 16:34:39'),
(81, 'App\\Models\\User', 5, 'mobile-app-5', '950e3a0efe56e4366d952ab41029aa4c70b10cbf7ae0b73eb2114e81c19108b5', '[\"*\"]', NULL, '2026-03-19 16:38:49', '2026-02-17 16:38:49', '2026-02-17 16:38:49'),
(82, 'App\\Models\\User', 5, 'mobile-app-5', '12c71d641a5a6eedb75dca79e82588b3719f7d8c23c05a11293edf9b63bb5ac6', '[\"*\"]', NULL, '2026-03-19 16:43:34', '2026-02-17 16:43:34', '2026-02-17 16:43:34'),
(83, 'App\\Models\\User', 5, 'mobile-app-5', '83b579dbc4eec15b1af3e263ffbc67838d572a70c90507f1dbea9ca28b318869', '[\"*\"]', NULL, '2026-03-19 16:43:42', '2026-02-17 16:43:42', '2026-02-17 16:43:42'),
(84, 'App\\Models\\User', 5, 'mobile-app-5', 'fe2c920e5a1dee325cccf2836b5a25cfc3622287f436906ff6d296c19a96ff77', '[\"*\"]', NULL, '2026-03-19 16:44:01', '2026-02-17 16:44:01', '2026-02-17 16:44:01'),
(85, 'App\\Models\\User', 5, 'mobile-app-5', 'd185162d42bbda69e91e6a222ca15c3e23af60f8272d332de0ed8f97327d482a', '[\"*\"]', NULL, '2026-03-19 16:44:47', '2026-02-17 16:44:47', '2026-02-17 16:44:47'),
(86, 'App\\Models\\User', 5, 'mobile-app-5', 'd2750fc4feda955cbb595978214500c8ca4fd7deb4553b1c81c66fec5edf03f4', '[\"*\"]', NULL, '2026-03-19 16:56:21', '2026-02-17 16:56:21', '2026-02-17 16:56:21'),
(87, 'App\\Models\\User', 5, 'mobile-app-5', 'c86db54bfb8a9adebe03ae18d2d642335b4258f0cff1324f345297976125072f', '[\"*\"]', NULL, '2026-03-19 17:05:37', '2026-02-17 17:05:37', '2026-02-17 17:05:37'),
(88, 'App\\Models\\User', 5, 'mobile-app-5', '501cfd56ad5d0e1885e3c21f91109b8430f9677c1412bcc3e6afa63ba06057e6', '[\"*\"]', NULL, '2026-03-19 17:21:34', '2026-02-17 17:21:34', '2026-02-17 17:21:34'),
(89, 'App\\Models\\User', 3, 'mobile-app-3', '0b4b73cdee9bb631627359297c8fafee49a7056c147223e95c78fcfbc7f977d4', '[\"*\"]', NULL, '2026-03-19 17:24:20', '2026-02-17 17:24:20', '2026-02-17 17:24:20'),
(90, 'App\\Models\\User', 5, 'mobile-app-5', '9e9fa42648621857df2c0cd7456f8c98d6e08afaebdc88d66fd7829d663927f1', '[\"*\"]', NULL, '2026-03-19 17:24:27', '2026-02-17 17:24:27', '2026-02-17 17:24:27'),
(91, 'App\\Models\\User', 3, 'mobile-app-3', 'cbf5b26518265d4fff4d1ab4228e33d898f5fe19ff4bfca155f1edcdcfe2bc8a', '[\"*\"]', NULL, '2026-03-20 05:27:56', '2026-02-18 05:27:56', '2026-02-18 05:27:56'),
(92, 'App\\Models\\User', 3, 'mobile-app-3', 'f6dfad9ffa7c3d7d342607e24f5d1f2964ff38f0bd6b419de200149fc0ab0fed', '[\"*\"]', NULL, '2026-03-20 05:28:02', '2026-02-18 05:28:02', '2026-02-18 05:28:02'),
(93, 'App\\Models\\User', 3, 'mobile-app-3', '4e6d33c1f85b194e0f5b6b00d1c451db3980f151fd2471fe9f4072c8cd14118e', '[\"*\"]', NULL, '2026-03-20 05:28:07', '2026-02-18 05:28:07', '2026-02-18 05:28:07'),
(94, 'App\\Models\\User', 3, 'mobile-app-3', '162d0c824ee6e72f568e273a0fb113859258a5f627ee72b2cc17a55c96fad476', '[\"*\"]', NULL, '2026-03-20 05:28:12', '2026-02-18 05:28:12', '2026-02-18 05:28:12'),
(95, 'App\\Models\\User', 3, 'mobile-app-3', 'afbf5b6367a3164bd744cb74b8907033c4d7861bdc1308b659b773513e5e08e8', '[\"*\"]', NULL, '2026-03-20 05:28:16', '2026-02-18 05:28:16', '2026-02-18 05:28:16'),
(96, 'App\\Models\\User', 3, 'mobile-app-3', '16508fb0cf89817e797f5344acb0370ac4703e672294903ac2ea8fe2483b48e4', '[\"*\"]', NULL, '2026-03-20 05:28:21', '2026-02-18 05:28:21', '2026-02-18 05:28:21'),
(97, 'App\\Models\\User', 3, 'mobile-app-3', 'f08e1b874c9526e695ed388fd8ae0eddd00e704f9f354ceb9154a8b7cae481fe', '[\"*\"]', NULL, '2026-03-20 05:28:26', '2026-02-18 05:28:26', '2026-02-18 05:28:26'),
(98, 'App\\Models\\User', 3, 'mobile-app-3', '404b3ef79d7ae41208b58081e0f92665acb2965fd8d890a7a9ca114b5cae9d54', '[\"*\"]', NULL, '2026-03-20 05:32:00', '2026-02-18 05:32:00', '2026-02-18 05:32:00'),
(99, 'App\\Models\\User', 10, 'mobile-app-10', '490d458dc706ffed1f37b0596ff152bc561c0dfe51277693a2cd1169712c9f0b', '[\"*\"]', NULL, '2026-03-20 05:38:29', '2026-02-18 05:38:29', '2026-02-18 05:38:29'),
(100, 'App\\Models\\User', 11, 'mobile-app-11', '8cab5fabe8a21a55cb3142947413bd7f7344e3a4fa153e663d5fe149f4070d31', '[\"*\"]', NULL, '2026-03-20 06:01:44', '2026-02-18 06:01:44', '2026-02-18 06:01:44'),
(101, 'App\\Models\\User', 11, 'mobile-app-11', '6b252484cfce1aa012f7f6497281a53bce961fc9b906248ee18c79730533ee48', '[\"*\"]', NULL, '2026-03-20 06:03:57', '2026-02-18 06:03:57', '2026-02-18 06:03:57'),
(102, 'App\\Models\\User', 10, 'mobile-app-10', 'd9a4c59dd985cb8f39bae2d69b0272000088492cfeb1f263900831ed593dccac', '[\"*\"]', NULL, '2026-03-20 06:06:33', '2026-02-18 06:06:33', '2026-02-18 06:06:33'),
(103, 'App\\Models\\User', 12, 'mobile-app-12', '532e3f3823fb14b49845fbb1a89e61ef81e573f3fe0a454be2b7149b0fe2f7df', '[\"*\"]', NULL, '2026-03-20 06:11:12', '2026-02-18 06:11:12', '2026-02-18 06:11:12'),
(104, 'App\\Models\\User', 10, 'mobile-app-10', '5fd90f5e9266743ee7c7815162b30568126970cf97c3e6077e2ecb0ff7158897', '[\"*\"]', NULL, '2026-03-20 06:15:22', '2026-02-18 06:15:22', '2026-02-18 06:15:22'),
(105, 'App\\Models\\User', 12, 'mobile-app-12', '00ee0fb4857eb8cf841aa52f466e8ef793f22563e63d98a5fb1438f94c5e8346', '[\"*\"]', NULL, '2026-03-20 06:16:13', '2026-02-18 06:16:13', '2026-02-18 06:16:13'),
(106, 'App\\Models\\User', 12, 'mobile-app-12', '5d876f3471b18ca30101ac31d827e4900014b53d3d7a3540e28971beb43f6bd8', '[\"*\"]', NULL, '2026-03-20 06:54:08', '2026-02-18 06:54:08', '2026-02-18 06:54:08'),
(107, 'App\\Models\\User', 12, 'mobile-app-12', 'f82b550bd53fa33e179911a6f63401e3d5dbb2979dcd2196c4fd39e9a3cdc754', '[\"*\"]', NULL, '2026-03-20 06:55:41', '2026-02-18 06:55:41', '2026-02-18 06:55:41'),
(108, 'App\\Models\\User', 12, 'mobile-app-12', 'c92079f513e75b5a17aa741e5bdc196c3eeb152674d1cf745d917b480afddcef', '[\"*\"]', NULL, '2026-03-20 06:56:27', '2026-02-18 06:56:27', '2026-02-18 06:56:27'),
(109, 'App\\Models\\User', 12, 'mobile-app-12', '76c64dffe0d0a6d6099048183cad493bb3bd92a3a10a1d14ae9a77945c7cc901', '[\"*\"]', NULL, '2026-03-20 06:59:23', '2026-02-18 06:59:23', '2026-02-18 06:59:23'),
(110, 'App\\Models\\User', 14, 'mobile-app-14', 'fc347b787065cba3ed2acc30ecdbe36e2fbb9cb7fc93f9f2028b7db4103f2fcf', '[\"*\"]', NULL, '2026-03-20 07:13:28', '2026-02-18 07:13:28', '2026-02-18 07:13:28'),
(111, 'App\\Models\\User', 14, 'mobile-app-14', 'eb52ea867e7a22dd67fb6630f18f0761384bb25e2dfb2158a4538079a0eb11db', '[\"*\"]', NULL, '2026-03-20 07:14:08', '2026-02-18 07:14:08', '2026-02-18 07:14:08'),
(112, 'App\\Models\\User', 14, 'mobile-app-14', '23c057f96b5d0de3ebb080f974c69985dc7d2fc385b0847365527545c8dcbb6e', '[\"*\"]', NULL, '2026-03-20 07:14:43', '2026-02-18 07:14:43', '2026-02-18 07:14:43'),
(113, 'App\\Models\\User', 14, 'mobile-app-14', 'b0c509576ea7f4363de1cfda79a8dac63e63024d4ff3041d91a5d234fc115d44', '[\"*\"]', NULL, '2026-03-20 07:15:14', '2026-02-18 07:15:14', '2026-02-18 07:15:14'),
(114, 'App\\Models\\User', 15, 'mobile-app-15', '6326de935d5dc885acce9a02720a16818c63831294b82436b7db5d0f692d6af9', '[\"*\"]', NULL, '2026-03-20 07:17:41', '2026-02-18 07:17:41', '2026-02-18 07:17:41'),
(115, 'App\\Models\\User', 16, 'mobile-app-16', '940740df294dabf654a06c76ee83810f9cad7b701944e7703b9389b7295dfe21', '[\"*\"]', NULL, '2026-03-20 07:18:37', '2026-02-18 07:18:37', '2026-02-18 07:18:37'),
(116, 'App\\Models\\User', 15, 'mobile-app-15', 'ccc9a7de790f6f776aa4b3ed22781cae537b1f82d264c9ef7cbfa3845d3a134d', '[\"*\"]', NULL, '2026-03-20 07:19:13', '2026-02-18 07:19:13', '2026-02-18 07:19:13'),
(117, 'App\\Models\\User', 15, 'mobile-app-15', '021275232c732e43c47955e432be8874506a2b674b29f83b914ece95fac30376', '[\"*\"]', NULL, '2026-03-20 07:20:50', '2026-02-18 07:20:50', '2026-02-18 07:20:50'),
(118, 'App\\Models\\User', 17, 'mobile-app-17', '9cfd58da0f1622ef0052586b35a3ca84117b2b86f38439e4c0ad4ce3ed8de1c6', '[\"*\"]', NULL, '2026-03-20 07:28:19', '2026-02-18 07:28:19', '2026-02-18 07:28:19'),
(119, 'App\\Models\\User', 18, 'mobile-app-18', '3413b35f37e61232c2da9fa73de14747815cd3bbe84a1524f5e1499d4236a4d9', '[\"*\"]', NULL, '2026-03-20 07:29:11', '2026-02-18 07:29:11', '2026-02-18 07:29:11'),
(120, 'App\\Models\\User', 18, 'mobile-app-18', '0cb7fe425d60383530a79d6d5bc6c0c00e32a4ac54986c992fcb6a0a83cfc269', '[\"*\"]', NULL, '2026-03-20 07:29:37', '2026-02-18 07:29:37', '2026-02-18 07:29:37'),
(121, 'App\\Models\\User', 18, 'mobile-app-18', '2bf55383516dd969633adc8e2f68f8124ed54cd5ae22ed76ddefe14314b8d9af', '[\"*\"]', '2026-02-18 08:32:24', '2026-03-20 07:30:33', '2026-02-18 07:30:33', '2026-02-18 08:32:24'),
(122, 'App\\Models\\User', 20, 'mobile-app-20', 'edeb236b1d4d094dc2c0129a51bb5b01d1f14a2152e42bd49d0740278be5b1e6', '[\"*\"]', NULL, '2026-03-20 07:47:17', '2026-02-18 07:47:17', '2026-02-18 07:47:17'),
(123, 'App\\Models\\User', 20, 'mobile-app-20', 'f7818d0660e5d21fb7f93fae6a73adea1c211a7aedf78a3ab91d6ef913a5e4b5', '[\"*\"]', NULL, '2026-03-20 07:52:16', '2026-02-18 07:52:16', '2026-02-18 07:52:16'),
(124, 'App\\Models\\User', 20, 'mobile-app-20', '8d24c84cfc2214ed4646cb3c02c33212c69126523fe320ed8973f933917a6259', '[\"*\"]', NULL, '2026-03-20 07:54:20', '2026-02-18 07:54:20', '2026-02-18 07:54:20'),
(125, 'App\\Models\\User', 21, 'mobile-app-21', '21aafb04963761a60fe9a98f0d6e442d6aa063336a04fd970ad4de6008037ee4', '[\"*\"]', NULL, '2026-03-20 11:00:06', '2026-02-18 11:00:06', '2026-02-18 11:00:06'),
(126, 'App\\Models\\User', 21, 'mobile-app-21', '27c941cd65d51658bca2b22e33cc27ec790ab0b65b032d0544969fbbc64e85ea', '[\"*\"]', NULL, '2026-03-20 11:01:52', '2026-02-18 11:01:52', '2026-02-18 11:01:52'),
(127, 'App\\Models\\User', 21, 'mobile-app-21', '9fc08d00d253003c8890702f2d3621a68823629aa38e93c167518be72d19660f', '[\"*\"]', NULL, '2026-03-20 11:04:29', '2026-02-18 11:04:29', '2026-02-18 11:04:29'),
(128, 'App\\Models\\User', 21, 'mobile-app-21', '7c62f83f0b4fcf69e6096f4fd33369b241f32142a325289ace1730fbe3f2355b', '[\"*\"]', NULL, '2026-03-20 11:07:21', '2026-02-18 11:07:21', '2026-02-18 11:07:21'),
(129, 'App\\Models\\User', 21, 'mobile-app-21', '6221fd276bc2780f496e378d4801ac43ce205ab919e54fac0515a4cde6377622', '[\"*\"]', NULL, '2026-03-20 11:08:19', '2026-02-18 11:08:19', '2026-02-18 11:08:19'),
(130, 'App\\Models\\User', 22, 'mobile-app-22', 'f9a771a805e26351dd77ca264bcd93df017367cee1b73aaff78fc1e6c1c6b743', '[\"*\"]', NULL, '2026-03-20 11:09:34', '2026-02-18 11:09:34', '2026-02-18 11:09:34'),
(131, 'App\\Models\\User', 22, 'mobile-app-22', '64f157a0b315ecef6df0af9b35682a8a0722fcd8a3e2b13a1270deee459314a8', '[\"*\"]', NULL, '2026-03-20 11:10:43', '2026-02-18 11:10:43', '2026-02-18 11:10:43'),
(132, 'App\\Models\\User', 22, 'mobile-app-22', '967145ce55b6a81ee510eda241e37628e5c025a709bb540a0c84868b9e9b62cf', '[\"*\"]', NULL, '2026-03-20 11:16:15', '2026-02-18 11:16:15', '2026-02-18 11:16:15'),
(133, 'App\\Models\\User', 22, 'mobile-app-22', 'fb3b8311b63085026e680586d3f737e655e2c9c040a7c76bebe8cf60603b49dc', '[\"*\"]', NULL, '2026-03-20 11:25:58', '2026-02-18 11:25:58', '2026-02-18 11:25:58'),
(134, 'App\\Models\\User', 22, 'mobile-app-22', '3b502948a1b2d71fd696594e45db8b28867b0cdf012f422c67a0ad11d705a8e7', '[\"*\"]', NULL, '2026-03-20 11:31:44', '2026-02-18 11:31:44', '2026-02-18 11:31:44'),
(135, 'App\\Models\\User', 22, 'mobile-app-22', '58cd0a8a20fa273bc799b0bdc44f86686935f52aced846700c5c5a8ed9c86f25', '[\"*\"]', '2026-02-18 13:08:37', '2026-03-20 11:39:31', '2026-02-18 11:39:31', '2026-02-18 13:08:37'),
(136, 'App\\Models\\User', 22, 'mobile-app-22', '6e51b96d9ed2a3a5f96ee8d5186957e83bf382744327f0ffe9e3600a206bfa9a', '[\"*\"]', NULL, '2026-03-20 11:41:53', '2026-02-18 11:41:53', '2026-02-18 11:41:53'),
(137, 'App\\Models\\User', 22, 'mobile-app-22', 'c2027f98f67d3990b204ba86f3e8a6ca974c7de2ca6b26e07788470d80104a1d', '[\"*\"]', '2026-02-18 11:48:19', '2026-03-20 11:42:44', '2026-02-18 11:42:44', '2026-02-18 11:48:19'),
(138, 'App\\Models\\User', 23, 'mobile-app-23', 'ce536db28a2ebad2c80fefdf4d90054b960640374d38bca002adefbbffc3fb9c', '[\"*\"]', '2026-02-18 13:12:53', '2026-03-20 11:44:35', '2026-02-18 11:44:35', '2026-02-18 13:12:53'),
(139, 'App\\Models\\User', 22, 'mobile-app-22', '261e118fbe156eef0532773d2eb66e92b6437571657018f9bf9633351b8f06e2', '[\"*\"]', NULL, '2026-03-20 11:53:57', '2026-02-18 11:53:57', '2026-02-18 11:53:57'),
(140, 'App\\Models\\User', 22, 'mobile-app-22', '0f8b0ad678c22fbfdd366256e76f0f5b0885e4df167871b0376ec57ef9df45a0', '[\"*\"]', NULL, '2026-03-20 11:54:16', '2026-02-18 11:54:16', '2026-02-18 11:54:16'),
(141, 'App\\Models\\User', 22, 'mobile-app-22', '26e613719b693f421be3643da8518cad94b5e40350010d90b4e52454e8a1eeb7', '[\"*\"]', '2026-02-18 12:56:57', '2026-03-20 12:01:40', '2026-02-18 12:01:40', '2026-02-18 12:56:57'),
(142, 'App\\Models\\User', 22, 'mobile-app-22', '9ef34a1aee48cf05b306ce3eb0144c37241060cb693136ef66e7865c4f8d3ab4', '[\"*\"]', '2026-02-18 12:30:22', '2026-03-20 12:29:51', '2026-02-18 12:29:51', '2026-02-18 12:30:22'),
(143, 'App\\Models\\User', 22, 'mobile-app-22', 'e6ef9b51c9a02190c4c154f2182ce533fe1b72e95f3803048f2a752195312c97', '[\"*\"]', NULL, '2026-03-20 12:39:14', '2026-02-18 12:39:14', '2026-02-18 12:39:14'),
(144, 'App\\Models\\User', 22, 'mobile-app-22', 'acf5adbecfe0ade7d9c721a31cece6f043c30b7c70b0d50af221f1b568a18093', '[\"*\"]', '2026-02-18 13:00:17', '2026-03-20 13:00:15', '2026-02-18 13:00:15', '2026-02-18 13:00:17'),
(145, 'App\\Models\\User', 24, 'mobile-app-24', '4c6b55611e72efb97bfce11c13fecb7560502ff6bb3659057abaa042a19c3df3', '[\"*\"]', '2026-02-18 13:01:32', '2026-03-20 13:01:31', '2026-02-18 13:01:31', '2026-02-18 13:01:32'),
(146, 'App\\Models\\User', 22, 'mobile-app-22', '8f670efb10ed30bd53c91cf2614a434baeea6cce5425a26ef1e0586432efdb01', '[\"*\"]', NULL, '2026-03-20 13:11:05', '2026-02-18 13:11:05', '2026-02-18 13:11:05'),
(147, 'App\\Models\\User', 22, 'mobile-app-22', '1611b264923e59d43b37682b3474e8887350f095a4002b97c05ca2c16bc3250e', '[\"*\"]', '2026-02-18 13:13:12', '2026-03-20 13:12:04', '2026-02-18 13:12:04', '2026-02-18 13:13:12'),
(148, 'App\\Models\\User', 25, 'mobile-app-25', '11db8911053d284952972289460520fb71425ab8d5b8410885d4c7257fd767a1', '[\"*\"]', '2026-02-18 13:42:29', '2026-03-20 13:40:23', '2026-02-18 13:40:23', '2026-02-18 13:42:29'),
(149, 'App\\Models\\User', 26, 'mobile-app-26', 'a1427d02d055b4ae49a70ddc915d5027add937f8ce30bfb334a37888bba0fe13', '[\"*\"]', '2026-02-18 13:47:17', '2026-03-20 13:47:16', '2026-02-18 13:47:16', '2026-02-18 13:47:17'),
(150, 'App\\Models\\User', 25, 'mobile-app-25', '1926bd73c765d9f6cb493c1fa242035078441a00c270889cb753d30b95fd630f', '[\"*\"]', '2026-02-18 15:34:59', '2026-03-20 14:20:24', '2026-02-18 14:20:24', '2026-02-18 15:34:59'),
(151, 'App\\Models\\User', 27, 'mobile-app-27', '9183ea96581e68d0d0b0d54fbb417f98ca74ef1bffeb6c0b04b83d4e8d22b6d8', '[\"*\"]', '2026-02-19 01:58:24', '2026-03-20 15:34:08', '2026-02-18 15:34:08', '2026-02-19 01:58:24');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0Fba0X7VPxKTaSGYW9oDOEpm0nYKW88EuRUM3ii2', NULL, '142.147.178.242', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR1hqd0MxQ3g3YUc3d3hBQzFtV0JhNWprU1F3YlhSQWZZd3I2dmpsdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYmxvZy5hZmZpbGlhdGVtYWRpLmNvbS9wdWJsaWMvaW5kZXgucGhwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771240900),
('CgHUTmSH6x4YTePnDpEXSXsT3OqZYPEAQZlDKoJB', NULL, '60.243.250.95', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkRJQ1BZc0R3UjlHMG1iZ0NUc3k1MGZQbEhrZ3dNWWFwVjRUT3lIaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYmxvZy5hZmZpbGlhdGVtYWRpLmNvbS9wdWJsaWMvY2xlYXItY2FjaGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771241995),
('iYMbhPmRf0h6MJuQ68EIrkMxd4h4P83pOk5ClLS3', NULL, '23.27.145.40', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWVBmYlhhN2cwNTRzT2IwMzZmSG9ZUHVTaHFxNm93S242MXh6UldXRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYmxvZy5hZmZpbGlhdGVtYWRpLmNvbS9wdWJsaWMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771239082),
('KnDf03ykAtYhA9R7PxZWCqNmkJfduOD51BASznHg', NULL, '23.27.145.50', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRE5SamJ3Ym9aQ2czSTgyYm1wYXlucGZTZXptd2ZkVE1iTGc3d2djaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYmxvZy5hZmZpbGlhdGVtYWRpLmNvbS9wdWJsaWMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771239128),
('MRPyGhKHHMH0h84c5nzsAobY1EX4fgiQLhVxRayq', NULL, '60.243.250.95', 'PostmanRuntime/7.51.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWVpHT3BlcTJ0WkV4SHJXZGJ1UHJoUjJFZVVSa1NuMmhwS2ZYblRORSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYmxvZy5hZmZpbGlhdGVtYWRpLmNvbS9wdWJsaWMvY2xlYXItY2FjaGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771242213),
('NBbA55ZNB6KKvdkOjeJ5kbpMpXpnn2L0f1eQnviI', NULL, '69.109.182.193', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaG1IendldGU2T093cjExRFV2V0pwUkZBcVRwQmp4QnNXNFZPakNlaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vYmxvZy5hZmZpbGlhdGVtYWRpLmNvbS9wdWJsaWMvdGVzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771239934),
('NjaWBQ2e5vCxPwdIxuveVnmXmYQ7EYxRdNctZLJ9', NULL, '60.243.250.95', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0R0dGxhUlJBNndBclV0aUlvZ045cUp2RlV4QXVmOHo1cWE3NldVNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYmxvZy5hZmZpbGlhdGVtYWRpLmNvbS9wdWJsaWMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771237418),
('smcO7lluuU197Ale4Esct0O8VgN7o9dCsc6gk94L', NULL, '45.41.133.48', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFhDZTJOTTNJU3VnUkl1UVlUMzY1blg5RkxCN2haUWc3T25zblc1QiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYmxvZy5hZmZpbGlhdGVtYWRpLmNvbS9wdWJsaWMvaW5kZXgucGhwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771240200),
('W9uQ1Eu62N2xdBZKNUk23v5p6f9QYrOk5y1M40kh', NULL, '195.172.119.175', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWmp5Z2lFOU9YRklHMEh4SXAzM2JmcGt2RWNVeWdmYUpDRk9ORkJpdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vYmxvZy5hZmZpbGlhdGVtYWRpLmNvbS9wdWJsaWMvdGVzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771240429);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certificate`
--

CREATE TABLE `tbl_certificate` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `course_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `certificate` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_certificate`
--

INSERT INTO `tbl_certificate` (`id`, `user_id`, `course_name`, `certificate`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Full Stack Web Development', 1, 1, '2026-02-16 06:14:14', '2026-02-16 06:14:14'),
(2, 2, 'Python for Data Science', 0, 1, '2026-02-16 06:14:14', '2026-02-16 06:14:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_device`
--

CREATE TABLE `tbl_device` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `device_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_os` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `waiting_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_device`
--

INSERT INTO `tbl_device` (`id`, `user_id`, `device_name`, `mobile`, `token`, `ip_address`, `device_type`, `device_os`, `app_version`, `last_used_at`, `waiting_time`, `created_at`, `updated_at`) VALUES
(39, NULL, 'SM-M405F', '8660867506', '147|2VxqNcAJuoeuzttqpCDvObSukJEPN1pV9epDEblv2dc5c1f4', '152.57.106.141', 'mobile', 'android 11', '1.0.0+1', '2026-02-18 13:12:04', NULL, '2026-02-18 11:39:31', '2026-02-18 13:12:04'),
(40, 23, 'V2437', '6302173823', '138|fQNF2YPcIu2J6OiXNyx7E3tQQd6TgG81DC4jqAkpbd9212c0', '60.243.250.95', 'mobile', 'android 15', '1.0.0+1', '2026-02-18 11:44:35', NULL, '2026-02-18 11:44:35', '2026-02-18 11:44:35'),
(41, 24, 'chrome', '9515267091', '145|cNhiMePMILM1YaxQM58zNOvCJg0Y3QOxGFFjHJBe6ffe5342', '60.243.250.95', 'web', 'Win32', '1.0.0+1', '2026-02-18 13:01:31', NULL, '2026-02-18 13:01:31', '2026-02-18 13:01:31'),
(42, 25, 'SM-M405F', '8660867506', '150|8MzcWOawjqZgCrPVfyLXePxyv5Any84qCd87dLGZ6cc4a6b7', '152.57.101.159', 'mobile', 'android 11', '1.0.0+1', '2026-02-18 14:20:24', NULL, '2026-02-18 13:40:23', '2026-02-18 14:20:24'),
(43, 26, 'V2109', '8856842393', '149|xim35cMPk6L9zoner9ksxyb5Ak7q6N8IkseUobCdd663b5c4', '106.193.106.235', 'mobile', 'android 13', '1.0.0+1', '2026-02-18 13:47:16', NULL, '2026-02-18 13:47:16', '2026-02-18 13:47:16'),
(44, 27, 'CPH2487', '8762271811', '151|Az1jaH4cTNflAxZ3HcgIXEMp3rd3PMiF2WDZ8B4N719ee6fc', '110.225.36.141', 'mobile', 'android 15', '1.0.0+1', '2026-02-18 15:34:08', NULL, '2026-02-18 15:34:08', '2026-02-18 15:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_device_history`
--

CREATE TABLE `tbl_device_history` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `device_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_os` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_device_history`
--

INSERT INTO `tbl_device_history` (`id`, `user_id`, `device_name`, `mobile`, `ip_address`, `device_type`, `device_os`, `app_version`, `login_at`, `action`, `created_at`, `updated_at`) VALUES
(37, NULL, 'chrome', '8660867506', '60.243.250.95', 'web', 'Win32', '1.0.0+1', '2026-02-18 12:39:14', 'existing_device', '2026-02-18 12:39:14', '2026-02-18 12:39:14'),
(38, NULL, 'chrome', '8660867506', '60.243.250.95', 'web', 'Win32', '1.0.0+1', '2026-02-18 13:00:15', 'existing_device', '2026-02-18 13:00:15', '2026-02-18 13:00:15'),
(39, 24, 'chrome', '9515267091', '60.243.250.95', 'web', 'Win32', '1.0.0+1', '2026-02-18 13:01:31', 'first_device', '2026-02-18 13:01:31', '2026-02-18 13:01:31'),
(40, NULL, 'SM-M405F', '8660867506', '152.57.106.141', 'mobile', 'android 11', '1.0.0+1', '2026-02-18 13:11:05', 'device_change', '2026-02-18 13:11:05', '2026-02-18 13:11:05'),
(41, NULL, 'SM-M405F', '8660867506', '152.57.106.141', 'mobile', 'android 11', '1.0.0+1', '2026-02-18 13:12:04', 'existing_device', '2026-02-18 13:12:04', '2026-02-18 13:12:04'),
(42, 25, 'SM-M405F', '8660867506', '152.57.105.20', 'mobile', 'android 11', '1.0.0+1', '2026-02-18 13:40:23', 'first_device', '2026-02-18 13:40:23', '2026-02-18 13:40:23'),
(43, 26, 'V2109', '8856842393', '106.193.106.235', 'mobile', 'android 13', '1.0.0+1', '2026-02-18 13:47:16', 'first_device', '2026-02-18 13:47:16', '2026-02-18 13:47:16'),
(44, 25, 'SM-M405F', '8660867506', '152.57.101.159', 'mobile', 'android 11', '1.0.0+1', '2026-02-18 14:20:24', 'existing_device', '2026-02-18 14:20:24', '2026-02-18 14:20:24'),
(45, 27, 'CPH2487', '8762271811', '110.225.36.141', 'mobile', 'android 15', '1.0.0+1', '2026-02-18 15:34:08', 'first_device', '2026-02-18 15:34:08', '2026-02-18 15:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_otp`
--

CREATE TABLE `tbl_otp` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `set_limit` int NOT NULL DEFAULT '50',
  `rate_limit` int DEFAULT NULL,
  `rate_limit_old` tinyint NOT NULL DEFAULT '0' COMMENT 'number of attempts (1-5 typically)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_otp`
--

INSERT INTO `tbl_otp` (`id`, `user_id`, `mobile`, `otp`, `expiry_time`, `ip_address`, `set_limit`, `rate_limit`, `rate_limit_old`, `created_at`, `updated_at`) VALUES
(73, 24, '9515267091', '3632', '2026-02-18 14:37:17', '60.243.250.95', 50, 4, 0, '2026-02-18 13:27:14', '2026-02-18 14:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `name`, `mobile`, `device_name`, `token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Rahul Sharma', '919876543210', 'Samsung Galaxy S23', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.sampletoken123', 1, '2026-02-16 06:13:42', '2026-02-16 06:13:42'),
(2, 'Priya Patel', '919812345678', 'iPhone 14 Pro', 'abcxyz-token-456789-long-sample', 1, '2026-02-16 06:13:42', '2026-02-16 06:13:42'),
(23, 'User_173823', '6302173823', 'V2437', '138|fQNF2YPcIu2J6OiXNyx7E3tQQd6TgG81DC4jqAkpbd9212c0', 1, '2026-02-18 11:44:22', '2026-02-18 11:44:35'),
(24, 'User_267091', '9515267091', 'chrome', '145|cNhiMePMILM1YaxQM58zNOvCJg0Y3QOxGFFjHJBe6ffe5342', 1, '2026-02-18 13:01:16', '2026-02-18 13:01:31'),
(25, 'User_867506', '8660867506', 'SM-M405F', '150|8MzcWOawjqZgCrPVfyLXePxyv5Any84qCd87dLGZ6cc4a6b7', 1, '2026-02-18 13:40:08', '2026-02-18 14:20:24'),
(26, 'User_842393', '8856842393', 'V2109', '149|xim35cMPk6L9zoner9ksxyb5Ak7q6N8IkseUobCdd663b5c4', 1, '2026-02-18 13:47:05', '2026-02-18 13:47:16'),
(27, 'User_271811', '8762271811', 'CPH2487', '151|Az1jaH4cTNflAxZ3HcgIXEMp3rd3PMiF2WDZ8B4N719ee6fc', 1, '2026-02-18 15:33:55', '2026-02-18 15:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wallet`
--

CREATE TABLE `tbl_wallet` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `life_time_earing` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_earned` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_withdrawn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `pending_withdrawal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `available_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_wallet`
--

INSERT INTO `tbl_wallet` (`id`, `user_id`, `life_time_earing`, `total_earned`, `total_withdrawn`, `pending_withdrawal`, `available_balance`, `currency`, `created_at`, `updated_at`) VALUES
(36, 23, 0.00, 0.00, 0.00, 0.00, 0.00, 'INR', '2026-02-18 11:44:35', '2026-02-18 11:44:35'),
(37, 24, 0.00, 0.00, 0.00, 0.00, 0.00, 'INR', '2026-02-18 13:01:31', '2026-02-18 13:01:31'),
(38, 25, 5000.00, 5000.00, 1000.00, 4000.00, 4000.00, 'INR', '2026-02-18 13:40:23', '2026-02-18 13:40:23'),
(39, 26, 0.00, 0.00, 0.00, 0.00, 0.00, 'INR', '2026-02-18 13:47:16', '2026-02-18 13:47:16'),
(40, 27, 0.00, 0.00, 0.00, 0.00, 0.00, 'INR', '2026-02-18 15:34:08', '2026-02-18 15:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wallet_transactions`
--

CREATE TABLE `tbl_wallet_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `wallet_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance_before` decimal(15,2) NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_whatsapp_api`
--

CREATE TABLE `tbl_whatsapp_api` (
  `id` bigint UNSIGNED NOT NULL,
  `user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'wa',
  `stype` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'auth',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_whatsapp_api`
--

INSERT INTO `tbl_whatsapp_api` (`id`, `user`, `pass`, `sender`, `text`, `priority`, `stype`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
(1, 'MOUNTRABWA', '123456', 'BUZWAP', 'otp_verify', 'wa', 'auth', 1, NULL, '2026-02-18 13:53:34', '2026-02-18 13:53:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tbl_certificate`
--
ALTER TABLE `tbl_certificate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_certificate_user_id_foreign` (`user_id`);

--
-- Indexes for table `tbl_device`
--
ALTER TABLE `tbl_device`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_device_user_id_foreign` (`user_id`),
  ADD KEY `tbl_device_mobile_index` (`mobile`);

--
-- Indexes for table `tbl_device_history`
--
ALTER TABLE `tbl_device_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_device_history_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `tbl_device_history_mobile_index` (`mobile`);

--
-- Indexes for table `tbl_otp`
--
ALTER TABLE `tbl_otp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_otp_user_id_foreign` (`user_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_wallet`
--
ALTER TABLE `tbl_wallet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tbl_wallet_user_id_unique` (`user_id`),
  ADD KEY `tbl_wallet_user_id_index` (`user_id`);

--
-- Indexes for table `tbl_wallet_transactions`
--
ALTER TABLE `tbl_wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tbl_wallet_transactions_transaction_id_unique` (`transaction_id`),
  ADD KEY `tbl_wallet_transactions_wallet_id_created_at_index` (`wallet_id`,`created_at`),
  ADD KEY `tbl_wallet_transactions_user_id_type_index` (`user_id`,`type`),
  ADD KEY `tbl_wallet_transactions_transaction_id_index` (`transaction_id`);

--
-- Indexes for table `tbl_whatsapp_api`
--
ALTER TABLE `tbl_whatsapp_api`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_whatsapp_api_user_index` (`user`),
  ADD KEY `tbl_whatsapp_api_stype_index` (`stype`),
  ADD KEY `tbl_whatsapp_api_is_active_index` (`is_active`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `tbl_certificate`
--
ALTER TABLE `tbl_certificate`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_device`
--
ALTER TABLE `tbl_device`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_device_history`
--
ALTER TABLE `tbl_device_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tbl_otp`
--
ALTER TABLE `tbl_otp`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_wallet`
--
ALTER TABLE `tbl_wallet`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tbl_wallet_transactions`
--
ALTER TABLE `tbl_wallet_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_whatsapp_api`
--
ALTER TABLE `tbl_whatsapp_api`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_certificate`
--
ALTER TABLE `tbl_certificate`
  ADD CONSTRAINT `tbl_certificate_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_device`
--
ALTER TABLE `tbl_device`
  ADD CONSTRAINT `tbl_device_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_device_history`
--
ALTER TABLE `tbl_device_history`
  ADD CONSTRAINT `tbl_device_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_otp`
--
ALTER TABLE `tbl_otp`
  ADD CONSTRAINT `tbl_otp_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_wallet`
--
ALTER TABLE `tbl_wallet`
  ADD CONSTRAINT `tbl_wallet_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_wallet_transactions`
--
ALTER TABLE `tbl_wallet_transactions`
  ADD CONSTRAINT `tbl_wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `tbl_wallet` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
