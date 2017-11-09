-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 09, 2017 at 11:10 AM
-- Server version: 5.6.35
-- PHP Version: 7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `playlogix`
--

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `url`, `name`) VALUES
(1, './dist/images/hotrod-2918174_1280.jpg', 'hotrod'),
(2, './dist/images/iphone-2854327_1280.png', 'phone'),
(3, './dist/images/airbus-2132610_1280.jpg', 'airbus'),
(4, './dist/images/meat-709346_1280.jpg', 'cooking pan'),
(5, './dist/images/avenue-2215317_1280.jpg', 'trees');

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'nature'),
(2, 'technology'),
(3, 'appliances'),
(4, 'travel');

--
-- Dumping data for table `tag_relationships`
--

INSERT INTO `tag_relationships` (`tag_id`, `image_id`) VALUES
(2, 2),
(4, 1),
(4, 3),
(3, 4),
(1, 5),
(2, 3);
