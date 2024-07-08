-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 15, 2024 at 07:26 PM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Dummy category'),
(2, 'Laptop'),
(3, 'Desk'),
(4, 'Furniture'),
(5, 'Toy'),
(6, 'Games'),
(7, 'Fashion'),
(8, 'Beauty'),
(9, 'Apparel'),
(10, 'Sport'),
(11, 'Fitness'),
(12, 'Kitchen'),
(13, 'Today\'s deals'),
(14, 'Kids');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `seller_id` int(10) UNSIGNED NOT NULL,
  `buyer_id` int(10) UNSIGNED NOT NULL,
  `quantity` tinyint(3) UNSIGNED NOT NULL,
  `paid_to_seller` tinyint(1) NOT NULL,
  `paid_by_buyer` tinyint(1) NOT NULL,
  `delivery_method` text NOT NULL,
  `status` varchar(300) DEFAULT NULL,
  `payment_details` text,
  `address` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_id`, `seller_id`, `buyer_id`, `quantity`, `paid_to_seller`, `paid_by_buyer`, `delivery_method`, `status`, `payment_details`, `address`) VALUES
(10, 10, 5, 4, 2, 0, 0, 'fedex', 'Placed', 'First Last|1111-2222-3333-4444|1223|123', '123 pushkina|Washington DC|Virginia|32101');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(120) DEFAULT NULL,
  `description` text NOT NULL,
  `picture_url` varchar(600) NOT NULL,
  `rating` tinyint(5) UNSIGNED NOT NULL,
  `price` float UNSIGNED NOT NULL,
  `delivery_methods` text NOT NULL,
  `categories` text NOT NULL,
  `total_number_ordered` int(11) NOT NULL DEFAULT '0',
  `seller_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `picture_url`, `rating`, `price`, `delivery_methods`, `categories`, `total_number_ordered`, `seller_id`) VALUES
(1, 'Test product1', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text.', 'https://i.etsystatic.com/17280762/r/il/81146f/2583746160/il_fullxfull.2583746160_gxid.jpg', 0, 10, 'ups|pickup', '4|2', 0, 5),
(2, 'Test product2', 'Another simple description', 'https://s3.envato.com/files/351108440/DSC_6242.jpg', 0, 99, 'ups|pickup', '2|6|8', 0, 5),
(3, 'Desk', 'This is a brief description of a product', 'https://i.etsystatic.com/17280762/r/il/81146f/2583746160/il_fullxfull.2583746160_gxid.jpg', 0, 1, 'ups', '1|4', 0, 5),
(4, 'Laptop', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries', 'https://cdn.thewirecutter.com/wp-content/media/2023/11/laptops-2048px-8826.jpg?auto=webp&quality=75&crop=1.91:1&width=1200', 0, 1000, 'ups|pickup|fedex', '2|10', 0, 5),
(5, 'Some arbitrary name', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text.', 'https://s3.envato.com/files/351108440/DSC_6242.jpg', 0, 10, 'ups|pickup', '3|5|6|7', 0, 5),
(6, 'Dummy product', 'Lorem ipsum etc long something example', 'https://i.ebayimg.com/images/g/Wc4AAOSwtXZgaEBE/s-l1200.jpg', 0, 99, 'ups|pickup', '3|11', 0, 5),
(7, 'Desk', 'This is a brief description of a product', 'https://i.etsystatic.com/17280762/r/il/81146f/2583746160/il_fullxfull.2583746160_gxid.jpg', 0, 1.25, 'ups', '4|9|7', 0, 5),
(8, 'Smart Laptop', 'This is a brief description of a product', 'https://cdn.thewirecutter.com/wp-content/media/2023/11/laptops-2048px-8826.jpg?auto=webp&quality=75&crop=1.91:1&width=1200', 0, 1000, 'ups|pickup|fedex', '2|11|12', 0, 5),
(9, 'Another Test product', 'Lorem ipsum etc long something example', 'https://i.ebayimg.com/images/g/Wc4AAOSwtXZgaEBE/s-l1200.jpg', 0, 99, 'ups|pickup', '1|13|14', 0, 5),
(10, 'Very beautiful desk', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.\n\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 'https://i.etsystatic.com/17280762/r/il/81146f/2583746160/il_fullxfull.2583746160_gxid.jpg', 0, 10, 'ups|pickup', '4|12', 0, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `user_type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `products_rated` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `user_type`, `username`, `password`, `email`, `products_rated`) VALUES
(1, 'Ruslan', 'Test', 0, 'usertest1', '$2y$10$C0DzS9fNXKAIuSq3vROpe.zbuQfzS4zhYzdO7mkqwKz2ylegB7gza', 'test@gmail.com', NULL),
(2, 'Ruslan', 'Final', 1, 'finaltest', '$2y$10$y5SlHagj2a9gncK5dVulI.2nMX6h.0Y75dmoElVjQSy9dmJ5JHOTq', 'final@gmail.com', NULL),
(3, 'Test', 'Lname', 1, 'usertestOne', '$2y$10$38Hf4WrebaJVikGFjMzVGOvtRaPgNd1VCSLqvPM5xdU2.mgWsBSUq', 'test@gmail.com', NULL),
(4, 'Test', 'newTest', 0, 'userOne', '$2y$10$iW8usdsqtTeyrVOosBML3eUH6hwLevmh4AJGmcBWARkPNvnYSltYu', 'test@gmail.com', NULL),
(5, 'test', 'ani', 1, 'anitest', '$2y$10$SHPfjtX/yWzdX36s/EvjDe4RIl2a7bAvDaVb06XitXlZDZphnqwUa', 'test@gmail.com', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
