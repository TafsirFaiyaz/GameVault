-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2024 at 02:51 PM
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
-- Database: `gamevault`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `game_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `characters`
--

INSERT INTO `characters` (`id`, `name`, `description`, `image_path`, `game_title`) VALUES
(1, 'Arthur Morgan', 'Arthur Morgan is a character and the main playable protagonist of the video game Red Dead Redemption 2. A high-ranking member of the Van der Linde gang, Arthur must deal with the decline of the Wild West while attempting to survive against government forces and other adversaries in a fictionalized representation of the American frontier. He is portrayed by Roger Clark through performance capture.', 'Assets\\Charecters\\arthur.webp', 'Red Dead Redemption 2'),
(2, 'Geralt of Rivia', 'Geralt of Rivia was a legendary witcher of the School of the Wolf active throughout the 13th century. He loved the sorceress Yennefer, considered the love of his life despite their tumultuous relationship, and became Ciri\'s adoptive father.\r\n\r\nDuring the Trial of the Grasses, Geralt exhibited unusual tolerance for the mutagens that grant witchers their abilities. Accordingly, Geralt was subjected to further experimental mutagens which rendered his hair white and may have given him greater speed, strength, and stamina than his fellow witchers.', 'Assets\\Charecters\\geralt.webp', 'The Witcher 3'),
(3, 'Dutch Van Der Linde', 'Dutch van der Linde is the secondary antagonist of the 2010 western action-adventure video game Red Dead Redemption and one of the two deuteragonists (alongside John Marston) of its 2018 prequel Red Dead Redemption II.', 'Assets/Charecters/dutch-van-der-linde.webp', 'Red Dead Redemption'),
(4, 'Dante', 'Dante, also known under the alias of Tony Redgrave, is a fictional character and the protagonist of Devil May Cry, an action-adventure hack and slash video game series by Japanese developer and publisher Capcom.', 'Assets/Charecters/devil-may-cry-5-2453635.webp', 'Devil May Cry');

-- --------------------------------------------------------

--
-- Table structure for table `characters_request`
--

CREATE TABLE `characters_request` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `game_title` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `character_ratings`
--

CREATE TABLE `character_ratings` (
  `user_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `character_ratings`
--

INSERT INTO `character_ratings` (`user_id`, `character_id`, `rating`) VALUES
(1, 1, 4),
(1, 2, 3),
(2, 2, 1),
(2, 1, 3),
(1, 4, 4),
(1, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `review_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favourite_games`
--

CREATE TABLE `favourite_games` (
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourite_games`
--

INSERT INTO `favourite_games` (`user_id`, `game_id`) VALUES
(1, 2),
(3, 1),
(3, 3),
(3, 2),
(3, 7),
(3, 8),
(1, 7),
(1, 14),
(1, 16);

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`user_id`, `friend_id`) VALUES
(3, 1),
(1, 3),
(2, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `friends_requests`
--

CREATE TABLE `friends_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends_requests`
--

INSERT INTO `friends_requests` (`id`, `sender_id`, `receiver_id`, `status`) VALUES
(1, 1, 3, 1),
(2, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `release_date` date DEFAULT NULL,
  `platform` varchar(100) DEFAULT NULL,
  `developer` varchar(100) DEFAULT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `action` int(11) NOT NULL,
  `rpg` int(11) NOT NULL,
  `strategy` int(11) NOT NULL,
  `sports` int(11) NOT NULL,
  `adventure` int(11) NOT NULL,
  `mystery` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `title`, `description`, `release_date`, `platform`, `developer`, `publisher`, `image_path`, `action`, `rpg`, `strategy`, `sports`, `adventure`, `mystery`) VALUES
(1, 'Red Dead Redemption 2', 'Red Dead Redemption 2 is a 2018 action-adventure game developed and published by Rockstar Games. The game is the third entry in the Red Dead series and a prequel to the 2010 game Red Dead Redemption', '2018-10-26', 'PlayStation 4, Xbox One, Google Stadia, Microsoft Windows', 'Rockstar', 'Rockstar', 'Assets/rdr2.jpg', 1, 1, 0, 0, 1, 1),
(2, 'The Witcher 3', 'The monster slayer Geralt of Rivia must find his adoptive daughter who is being pursued by the Wild Hunt, and prevent the White Frost from bringing about the end of the world.', '2015-05-18', 'PlayStation 5, PlayStation 4, Xbox One, Xbox Series X and Series S, Microsoft Windows', 'CD Projekt', 'CD Projekt', 'Assets\\witcher3.jpg', 1, 1, 1, 0, 1, 1),
(3, 'Sleeping Dogs', 'Asian-American cop Wei Shen goes undercover in Hong Kong to infiltrate a Triad organization. However, he is soon torn between his duty as a police officer and the organization he helped rise through the ranks.', '2012-08-13', 'PlayStation 4, PlayStation 3, Xbox 360, Microsoft Windows, Xbox One, macOS, Mac operating systems', 'United Front Games, Feral Interactive', 'United Front Games, Feral Interactive', 'Assets\\sleepingdogs.jpg', 1, 0, 0, 0, 1, 1),
(7, 'Grand Theft Auto V', 'Grand Theft Auto V is a 2013 action-adventure game developed by Rockstar North and published by Rockstar Games. It is the seventh main entry in the Grand Theft Auto series, following 2008\'s Grand Theft Auto IV, and the fifteenth instalment overall', '2013-09-13', 'PlayStation 5, PlayStation 4, Xbox One, Xbox Series X and Series S, Microsoft Windows', 'Rockstar', 'Rockstar', 'Assets\\gta5.png', 1, 1, 1, 0, 1, 1),
(8, 'Dragon Age: The Veilguard', 'Dragon Age: The Veilguard is an upcoming action role-playing video game developed by BioWare and published by Electronic Arts. The fourth major game in the Dragon Age franchise, The Veilguard will be the sequel to Dragon Age: Inquisition.', '2024-10-31', 'PlayStation 5, Xbox Series X and Series S, Microsoft Windows', 'BioWare', 'BioWare', 'Assets/game_images/dragon-age-veilguard-eacom-coming-soon-16x9.jpg.adapt.crop191x100.1200w.jpg', 1, 1, 1, 0, 1, 1),
(13, 'God of War', 'God of War is a 2018 action-adventure game developed by Santa Monica Studio and published by Sony Interactive Entertainment. The game was released for the PlayStation 4 in April 2018, with a Windows port released in January 2022.', '2018-04-20', 'PlayStation 5, PlayStation 4, Microsoft Windows', 'Santa Monica Studio, Javaground, Ready at Dawn, Bluepoint Games, Daybreak Game Company, Jetpack Inte', ' Sony Interactive Entertainment, PlayStation Studios', 'Assets/game_images/God_of_War_4_cover.jpg', 1, 0, 0, 0, 1, 1),
(14, 'EA Sports FC 24', 'EA Sports FC 24 is an association football-themed simulation video game developed by EA Vancouver and EA Romania and published by EA Sports. It is the inaugural installment in the EA Sports FC series, succeeding the FIFA video game series after Electronic Arts\'s partnership with FIFA concluded with FIFA 23', '2024-09-29', 'PlayStation 5, Xbox Series X and Series S, Microsoft Windows', 'EA Vancouver, EA Romania, EA Canada', ' Electronic Arts', 'Assets/game_images/capsule_616x353.jpg', 0, 0, 0, 1, 0, 0),
(17, 'Elden Ring', 'Elden Ring is a 2022 action role-playing game developed by FromSoftware. It was directed by Hidetaka Miyazaki with worldbuilding provided by American fantasy writer George R. R. Martin.', '2022-02-25', 'PlayStation 5, PlayStation 4, Xbox Series X and Series S, Xbox One, Microsoft Windows', 'FromSoftware Inc.', 'FromSoftware Inc.', 'Assets/game_images/Elden_Ring_Box_art.jpg', 1, 1, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `games_request`
--

CREATE TABLE `games_request` (
  `id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `release_date` date DEFAULT NULL,
  `platform` varchar(100) DEFAULT NULL,
  `developer` varchar(100) DEFAULT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `action` int(11) NOT NULL,
  `rpg` int(11) NOT NULL,
  `strategy` int(11) NOT NULL,
  `sports` int(11) NOT NULL,
  `adventure` int(11) NOT NULL,
  `mystery` int(11) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `game_id`, `rating`) VALUES
(1, 1, 2, 4),
(2, 2, 2, 4),
(3, 1, 1, 3),
(4, 1, 7, 3),
(5, 1, 3, 4),
(6, 3, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `review` text NOT NULL,
  `is_critic_review` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `favourite_games` varchar(2000) DEFAULT NULL,
  `favourite_charecters` varchar(2000) DEFAULT NULL,
  `profile_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `favourite_games`, `favourite_charecters`, `profile_image`) VALUES
(1, 'tafsir', '$2y$10$iqQfiDeTgLgOKpZaHXYTTOzeWOQEGQX9yA/P/djYEOuKzmPqsHn7m', 'tafsirfaiyaz@gmail.com', NULL, NULL, 'Screenshot (2302).png'),
(2, 'tanjiro', '$2y$10$LFIGU014.ymnQ6mlsQjjQ.Ciy5SoWQ/H2iYqBzaUW1iRewqzcAafa', 'tanjiro@gmail.com', NULL, NULL, 'geralt.webp'),
(3, 'sadat', '$2y$10$KUcFdDIixKlpb2ttBUcMRuFDoiKIY5Wl1I4pecyDB/fn3aXF/c9o6', 'messi@gmail.com', NULL, NULL, 'vlcsnap-2024-08-18-15h45m02s074.png'),
(6, 'sadat10', '$2y$10$BYCuzc/th56ZHJom3X7jCePNtU0gJTEGxYZO20LgzYe.zkOGOYVJi', 'lala@gmail.com', NULL, NULL, 'vlcsnap-2024-09-08-22h30m42s801.png'),
(7, 'Sazzad', '$2y$10$KAZoFezNUQI4E.FRiuUcAuWbVu.jxAm9W/g8FCgPKLMJ/Y/GsuINC', 'sazzad@gmail.com', NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_planned_list`
--

CREATE TABLE `user_planned_list` (
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_planned_list`
--

INSERT INTO `user_planned_list` (`user_id`, `game_id`) VALUES
(1, 2),
(2, 2),
(1, 1),
(1, 3),
(2, 3),
(2, 7),
(1, 17),
(1, 16),
(3, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_title`);

--
-- Indexes for table `characters_request`
--
ALTER TABLE `characters_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_title`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `review_id` (`review_id`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Indexes for table `friends_requests`
--
ALTER TABLE `friends_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `characters_request`
--
ALTER TABLE `characters_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friends_requests`
--
ALTER TABLE `friends_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `communities`
--
ALTER TABLE `communities`
  ADD CONSTRAINT `communities_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
