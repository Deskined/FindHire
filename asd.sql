-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for jobseeker
CREATE DATABASE IF NOT EXISTS `jobseeker` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `jobseeker`;

-- Dumping structure for table jobseeker.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jobseeker.admins: ~1 rows (approximately)
INSERT INTO `admins` (`admin_id`, `username`, `password`) VALUES
	(3, 'asd', '$2y$10$X0361xKqkUd/ZzgrBvBldev.Uh0iju9JTaBJO/cp8rkaF82K/q.0.');

-- Dumping structure for table jobseeker.job_applications
CREATE TABLE IF NOT EXISTS `job_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `cover_letter` text NOT NULL,
  `resume` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `job_title` varchar(255) NOT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jobseeker.job_applications: ~1 rows (approximately)
INSERT INTO `job_applications` (`id`, `firstname`, `lastname`, `email`, `phone`, `cover_letter`, `resume`, `created_at`, `job_title`, `status`, `username`) VALUES
	(32, 'Sky', 'Sales', 'skysales0321@gmail.com', '9561459834', 'asdasd', 'Netcom_Presentation (4).pdf', '2024-12-08 20:41:49', 'Full Stack Developer', 'Accepted', 'asd');

-- Dumping structure for table jobseeker.job_postings
CREATE TABLE IF NOT EXISTS `job_postings` (
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_title` varchar(255) NOT NULL,
  `job_description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `salary` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jobseeker.job_postings: ~4 rows (approximately)
INSERT INTO `job_postings` (`job_id`, `job_title`, `job_description`, `location`, `salary`, `image_path`, `last_updated`) VALUES
	(22, 'Creative Design', 'Video editing, 3D animation, and graphic design are top creative skills. These areas are benefiting from the growing need for engaging content across digital platforms​', 'Manila,Philippines', '$500', '/uploads/images.jpg', '2024-12-10 10:17:48'),
	(23, 'Tech and Development', 'Skills like full-stack development, mobile app development, UX/UI design, and scripting are highly sought after. AI and machine learning also stand out as fast-growing areas​', 'Dasmarinas, Philipphines', '$1100', '/uploads/images.jpg', '2024-12-10 10:18:02'),
	(24, 'Data and Analytics', 'The demand for data analytics and generative AI modeling is increasing as businesses focus on leveraging data for strategic decision-making. This also ties in with the rise of automation', 'Singapore', '$700', '/uploads/images.jpg', '2024-12-10 10:18:07'),
	(25, 'Tech and Marketing and Sales', 'Expertise in SEO, social media marketing, email marketing, and lead generation is in demand. The shift toward data-driven strategies also makes digital marketing analytics a key skills.', 'USA', '$1300', '/uploads/ss.png', '2024-12-10 10:19:26');

-- Dumping structure for table jobseeker.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jobseeker.users: ~2 rows (approximately)
INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `phone`, `created_at`, `profile_picture`) VALUES
	(7, 'Marc', '$2y$10$.llJXd5J6gkAmpIBgL./5.VzHttaZSCHAdrj/xvFiWkr2f.mfcIIa', 'mabs3271@gmail.com', '0956145976', '2024-10-22 19:16:01', NULL),
	(9, 'asd', '$2y$10$INcYhudStmXBvFsxQxyVwOZku8GkosrOKaHri3.hj0tk/19ExopXO', 'skysalesss0321@gmail.com', '09561459834', '2024-12-03 09:37:18', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
