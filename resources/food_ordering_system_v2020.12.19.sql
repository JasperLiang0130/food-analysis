-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2020 at 11:05 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

DROP DATABASE food;
CREATE DATABASE food;
USE food;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_ordering_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`) VALUES
(1, 'Burgers'),
(2, 'Sides'),
(3, 'Drinks'),
(4, 'Desserts');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `ID` int(11) NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `TotalOrders` int(11) NOT NULL,
  `TotalValue` decimal(11,2) NOT NULL,
  `FirstOrderDateTime` datetime NULL,
  `MostRecentOrderDateTime` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer_accounts`
--

CREATE TABLE `customer_accounts` (
  `ID` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `CustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `BasePrice` decimal(11,2) NOT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ID`, `Name`, `Description`, `BasePrice`, `CategoryID`) VALUES
(1, 'Cheeseburger', 'Beef patty, cheese, tomato, lettuce, tomato sauce served in a white bread bun', '9.00', 1),
(2, 'Fries', 'Seasoned fries.', '3.00', 2),
(3, 'Chicken Tenders', 'Crispy Chicken Tenders', '8.00', 2),
(4, 'Double Cheeseburger', 'Two beef patties, cheese, tomato, lettuce, tomato sauce served in a white bread bun', '12.00', 1),
(5, 'BBQ Bacon Burger', 'Beef patty, bacon, onion, BBQ sauce served in a white bread bun', '11.00', 1),
(6, 'Salad', 'Lettuce, tomato, cucumber, with dressing.', '5.00', 2),
(7, 'Latte', '', '4.50', 3),
(8, 'Flat White', '', '4.50', 3),
(9, 'Cappuccino', '', '4.50', 3),
(10, 'Coke', '', '3.00', 3),
(11, 'Cheesecake', '', '5.00', 4),
(12, 'Sundae', '', '5.00', 4),
(13, 'Apple Pie', '', '6.50', 4);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Price` decimal(11,2) NOT NULL,
  `OptionSetID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`ID`, `Name`, `Price`, `OptionSetID`) VALUES
(1, 'No Meal', '0.00', 1),
(2, 'Medium', '4.50', 1),
(3, 'Large', '7.00', 1),
(4, 'Coke', '3.00', 2),
(5, 'Sprite', '3.00', 2),
(6, 'Fanta', '3.00', 2),
(7, 'Tomato', '1.00', 3),
(8, 'BBQ', '1.00', 3),
(9, 'Aioli', '1.50', 3);

-- --------------------------------------------------------

--
-- Table structure for table `option_sets`
--

CREATE TABLE `option_sets` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `MultipleOptions` tinyint(1) NOT NULL DEFAULT 0,
  `ItemID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `option_sets`
--

INSERT INTO `option_sets` (`ID`, `Name`, `MultipleOptions`, `ItemID`) VALUES
(1, 'Meal Size', 0, 1),
(2, 'Drink', 1, 1),
(3, 'Sauce', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `TotalValue` decimal(11,2) NOT NULL,
  `TotalItems` int(11) NOT NULL,
  `DistinctItems` int(11) NOT NULL,
  `JSON` mediumblob NOT NULL,
  `DateTime` datetime NOT NULL,
  `CustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `SingleValue` decimal(11,2) NOT NULL,
  `TotalValue` decimal(11,2) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_options`
--

CREATE TABLE `order_options` (
  `ID` int(11) NOT NULL,
  `Value` decimal(11,2) NOT NULL,
  `OrderItemID` int(11) NOT NULL,
  `OptionID` int(11) NOT NULL,
  `OptionSetID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `option_sets`
--
ALTER TABLE `option_sets`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `order_options`
--
ALTER TABLE `order_options`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `option_sets`
--
ALTER TABLE `option_sets`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_options`
--
ALTER TABLE `order_options`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
