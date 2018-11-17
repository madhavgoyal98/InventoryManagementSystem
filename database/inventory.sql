-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2018 at 11:55 PM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `finished_order`
--

CREATE TABLE `finished_order` (
  `order_id` int(11) UNSIGNED NOT NULL,
  `fp_id` int(11) UNSIGNED NOT NULL,
  `quantity_made` int(11) NOT NULL COMMENT 'quantity of finished product made for order'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `finished_product`
--

CREATE TABLE `finished_product` (
  `fp_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `measuring_unit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `finished_product`
--

INSERT INTO `finished_product` (`fp_id`, `name`, `quantity`, `measuring_unit`) VALUES
(2, 'fp1', 2, 'm');

-- --------------------------------------------------------

--
-- Table structure for table `intermediate_finished`
--

CREATE TABLE `intermediate_finished` (
  `im_id` int(11) UNSIGNED NOT NULL,
  `fp_id` int(11) UNSIGNED NOT NULL,
  `quantity_used` int(11) NOT NULL COMMENT 'quantity of intermediate used per finished product'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `intermediate_finished`
--

INSERT INTO `intermediate_finished` (`im_id`, `fp_id`, `quantity_used`) VALUES
(4, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `intermediate_items`
--

CREATE TABLE `intermediate_items` (
  `im_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `measuring_unit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `intermediate_items`
--

INSERT INTO `intermediate_items` (`im_id`, `name`, `quantity`, `measuring_unit`) VALUES
(0, 'do not delete', 0, 'do not delete'),
(3, 'im1', 38, 'm'),
(4, 'im2', 10, 'm');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) UNSIGNED NOT NULL,
  `vendor` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `fp_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `raw_intermediate`
--

CREATE TABLE `raw_intermediate` (
  `rm_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `im_im_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `im_id` int(11) UNSIGNED NOT NULL,
  `rm_quantity_used` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'quantity of raw material used per intermediate',
  `im_quantity_used` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'quantity of intermedeiate used per intermediate'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `raw_intermediate`
--

INSERT INTO `raw_intermediate` (`rm_id`, `im_im_id`, `im_id`, `rm_quantity_used`, `im_quantity_used`) VALUES
(11, 0, 3, 1, 0),
(13, 0, 3, 1, 0),
(15, 0, 4, 1, 0),
(0, 3, 4, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `raw_material`
--

CREATE TABLE `raw_material` (
  `rm_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `measuring_unit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `raw_material`
--

INSERT INTO `raw_material` (`rm_id`, `name`, `quantity`, `measuring_unit`) VALUES
(0, 'do not delete', 0, 'do not delete'),
(11, 'rm1', 951, 'm'),
(12, 'rm2', 1000, 'm'),
(13, 'rm3', 950, 'm'),
(14, 'rm4', 1000, 'm'),
(15, 'rm5', 988, 'm');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(40) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `password`, `name`, `role`) VALUES
('a', '$2y$10$9fXEVF1B6Afl2gf6wElnZuEZjVz.HjhVamdKaJQuPN6tx2zTS9B.y', 'admin1', 'admin'),
('w', '$2y$10$XaDBh5/jnNAM7UPTAEqiE.Z2lJAi/yjFWaDyah70levcYQo1WGCTC', 'w', 'worker');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `finished_order`
--
ALTER TABLE `finished_order`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fp_id` (`fp_id`);

--
-- Indexes for table `finished_product`
--
ALTER TABLE `finished_product`
  ADD PRIMARY KEY (`fp_id`);

--
-- Indexes for table `intermediate_finished`
--
ALTER TABLE `intermediate_finished`
  ADD KEY `im_id` (`im_id`),
  ADD KEY `fp_id` (`fp_id`);

--
-- Indexes for table `intermediate_items`
--
ALTER TABLE `intermediate_items`
  ADD PRIMARY KEY (`im_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `raw_intermediate`
--
ALTER TABLE `raw_intermediate`
  ADD KEY `rm_id` (`rm_id`),
  ADD KEY `im_id` (`im_id`),
  ADD KEY `im_im_id` (`im_im_id`);

--
-- Indexes for table `raw_material`
--
ALTER TABLE `raw_material`
  ADD PRIMARY KEY (`rm_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `finished_product`
--
ALTER TABLE `finished_product`
  MODIFY `fp_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `intermediate_items`
--
ALTER TABLE `intermediate_items`
  MODIFY `im_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `raw_material`
--
ALTER TABLE `raw_material`
  MODIFY `rm_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `finished_order`
--
ALTER TABLE `finished_order`
  ADD CONSTRAINT `finished_order_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `finished_order_ibfk_2` FOREIGN KEY (`fp_id`) REFERENCES `finished_product` (`fp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `intermediate_finished`
--
ALTER TABLE `intermediate_finished`
  ADD CONSTRAINT `intermediate_finished_ibfk_1` FOREIGN KEY (`im_id`) REFERENCES `intermediate_items` (`im_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `intermediate_finished_ibfk_2` FOREIGN KEY (`fp_id`) REFERENCES `finished_product` (`fp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `raw_intermediate`
--
ALTER TABLE `raw_intermediate`
  ADD CONSTRAINT `raw_intermediate_ibfk_1` FOREIGN KEY (`rm_id`) REFERENCES `raw_material` (`rm_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `raw_intermediate_ibfk_2` FOREIGN KEY (`im_id`) REFERENCES `intermediate_items` (`im_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `raw_intermediate_ibfk_3` FOREIGN KEY (`im_im_id`) REFERENCES `intermediate_items` (`im_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
