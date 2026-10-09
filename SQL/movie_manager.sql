-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 09, 2026 at 02:30 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `movie_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genreID` int(11) NOT NULL,
  `genreName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genreID`, `genreName`) VALUES
(2, 'Action'),
(1, 'Animation'),
(3, 'Comedy'),
(4, 'Drama');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movieID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `director` varchar(100) NOT NULL,
  `genreID` int(11) NOT NULL,
  `releaseYear` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `imageName` varchar(255) DEFAULT 'placeholder_100.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movieID`, `title`, `director`, `genreID`, `releaseYear`, `duration`, `imageName`) VALUES
(1, 'The Lion King', 'Roger Allers', 1, 1994, 88, 'movie_6ac833d23826f1.81852744.jpeg'),
(2, 'Finding Nemo', 'Andrew Stanton', 1, 2003, 100, 'movie_6ac833acf26651.40264961.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genreID`),
  ADD UNIQUE KEY `genreName` (`genreName`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movieID`),
  ADD KEY `genreID` (`genreID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movieID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `fk_movies_genres` FOREIGN KEY (`genreID`) REFERENCES `genres` (`genreID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
