-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 06, 2019 at 05:48 AM
-- Server version: 5.6.43
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `camagru_db`
--
CREATE DATABASE IF NOT EXISTS `camagru_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `camagru_db`;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `photo_id`, `content`, `author`) VALUES
(137, 211, 'Oh, c\'est Charly dans le fond', 'gdrai'),
(138, 215, 'Tr&egrave;s dr&ocirc;le, ha ha ha', 'apeyret'),
(139, 211, 'Belle perruche', 'apeyret'),
(140, 204, 'C\'est l\'&eacute;t&eacute;, la temp&eacute;rature monte', 'apeyret'),
(141, 212, 'Tr&egrave;s vegan !', 'apeyret'),
(142, 208, 'Beurkkkkkkk', 'apeyret'),
(143, 215, '42humour, le 18 juin en Holodeck', 'arumpler'),
(144, 206, 'Hklein, le g&eacute;nie', 'arumpler'),
(145, 211, 'Magnifique', 'salibert'),
(146, 216, 'Lol', 'hklein'),
(147, 211, 'Best pic of the website', 'hklein'),
(148, 215, 'Oh non une pomme...', 'hklein');

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE `like` (
  `login` varchar(255) NOT NULL,
  `photo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`login`, `photo_id`) VALUES
('gdrai', 207),
('gdrai', 205),
('gdrai', 203),
('gdrai', 215),
('apeyret', 211),
('apeyret', 215),
('apeyret', 207),
('apeyret', 201),
('arumpler', 215),
('arumpler', 209),
('arumpler', 206),
('salibert', 208),
('salibert', 216),
('hklein', 216),
('hklein', 214),
('hklein', 211),
('hklein', 215),
('hklein', 202);

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `source` longtext NOT NULL,
  `upload_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`id`, `user_id`, `source`, `upload_date`) VALUES
(200, 10, 'public/images/camagru5cf8ff311aac5.png', '2019-06-06 13:55:29'),
(201, 10, 'public/images/camagru5cf8ff39b790c.png', '2019-06-06 13:55:38'),
(202, 10, 'public/images/camagru5cf8ff40b0a01.png', '2019-06-06 13:55:45'),
(203, 13, 'public/images/camagru5cf9058210ea7.png', '2019-06-06 14:22:26'),
(204, 13, 'public/images/camagru5cf9058aea9a9.png', '2019-06-06 14:22:35'),
(205, 13, 'public/images/camagru5cf905981befa.png', '2019-06-06 14:22:48'),
(206, 14, 'public/images/camagru5cf905e7cde1d.png', '2019-06-06 14:24:08'),
(207, 14, 'public/images/camagru5cf905f337764.png', '2019-06-06 14:24:19'),
(208, 14, 'public/images/camagru5cf9061341d35.png', '2019-06-06 14:24:51'),
(209, 14, 'public/images/camagru5cf9062f1395f.png', '2019-06-06 14:25:19'),
(211, 16, 'public/images/camagru5cf90786298a4.png', '2019-06-06 14:31:02'),
(212, 16, 'public/images/camagru5cf907a159b92.png', '2019-06-06 14:31:29'),
(213, 17, 'public/images/camagru5cf907dcbe9dd.png', '2019-06-06 14:32:29'),
(214, 17, 'public/images/camagru5cf907e63595d.png', '2019-06-06 14:32:38'),
(215, 17, 'public/images/camagru5cf907f3e5a6c.png', '2019-06-06 14:32:52'),
(216, 16, 'public/images/camagru5cf909b185c48.png', '2019-06-06 14:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `notification` tinyint(1) NOT NULL DEFAULT '1',
  `unique_key` varchar(255) NOT NULL,
  `confirmed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `lastName`, `firstName`, `email`, `notification`, `unique_key`, `confirmed`) VALUES
(10, 'arumpler', 'efa821c168eb857281c9106028f635f9d5b70eca0cb79d9b5cdcff0280e1ea67d25850371dff5a3720d40ecb70ef0203574986297cafbc70a656fe067a191072', 'Rumpler', 'Axel', 'fulome@2p-mail.com', 1, 'KEY5cf8ff13dba9f', 1),
(13, 'apeyret', '2e1edabc22a422496672c7b13c388cdfa9d4528f94508ef9111a9e4ba70abb8d5059ba8dd8e657b093496efeee3e5eda67721f1f478f0bdd1e87275b000180dc', 'Peyret', 'Alae', 'yofuwuho@rockmailgroup.com', 1, 'KEY5cf905670fa49', 1),
(14, 'hklein', '0b10b0f2e316e42ecd3ead2c8d8f96d69d801d1ac776e1d8550f813b7bfe8e8172926d7f8cb4fda7ab499b9864d3516f2631881cf276de67804661978bf97813', 'Klein', 'Hugo', 'hutece@cryptonet.top', 1, 'KEY5cf905c991696', 1),
(16, 'salibert', 'e50e995c3f06aae557c95c3d7694076c199c22b87e2b334c872b22cfbc1c90c746fd5875d8cd85288e33b5caa87ef3ac10812c26cbc92a162ec4f5e5251211ce', 'Alibert', 'Sebastien', 'tifonoz@crypto-net.club', 1, 'KEY5cf907631192d', 1),
(17, 'gdrai', '59c7639bc6bc11a2e871e0e978b2ca1f94323da3c919090c1c6a628daa80584f1bac7b8c3f521e11862a63923eb074f0f1737c5a81ff6c1e80c1fd20e2b0222c', 'Drai', 'Gabriel', 'yemozax@coin-link.com', 1, 'KEY5cf907c293115', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photo_id` (`photo_id`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `photo_id` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
