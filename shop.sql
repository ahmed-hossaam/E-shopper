-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2026 at 01:01 AM
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
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Dresses'),
(2, 'Shirts'),
(3, 'Jeans'),
(4, 'Blazers'),
(5, 'Jackets'),
(6, 'Shoes'),
(7, 'Accessories'),
(8, 'Bags');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `shipping` decimal(10,2) DEFAULT 10.00,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `order_status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `first_name`, `last_name`, `email`, `mobile`, `address`, `city`, `subtotal`, `discount`, `shipping`, `total_price`, `payment_method`, `order_status`, `created_at`) VALUES
(28, 34, 'ahmed', 'flkrb', 'ahmed.hossam.23098@gmail.com', '098765', 'lfbelk', 'foh', 1480.00, 148.00, 10.00, 1342.00, 'paypal', 'Delivered', '2026-03-27 18:07:26'),
(30, 38, 'ahmed', 'hossam', 'a.h13900139@gmail.com', '123456789', 'tanta', 'tanta', 1040.00, 0.00, 10.00, 1050.00, 'paypal', 'Pending', '2026-03-30 21:38:15'),
(31, 38, 'ahmed', 'hossam', 'a.h13900139@gmail.com', '0123456789', 'fkjr', 'tanta', 350.00, 0.00, 10.00, 360.00, 'paypal', 'Pending', '2026-03-30 21:41:54'),
(32, 38, 'ahmed', 'hossam', 'a.h13900139@gmail.com', '0123456789', 'tanta', 'tanta', 1750.00, 0.00, 10.00, 1760.00, 'paypal', 'Delivered', '2026-03-30 21:49:14');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(26, 28, 114, 2, 340.00),
(27, 28, 115, 1, 390.00),
(28, 28, 116, 1, 410.00),
(29, 30, 99, 4, 260.00),
(30, 31, 117, 1, 350.00),
(31, 32, 117, 5, 350.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `product_description` text NOT NULL,
  `additional_information` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`, `stock`, `product_description`, `additional_information`, `created_at`) VALUES
(3, 'Classic Blue Jeans', 'Regular fit classic jeans', 55.00, 'jeans-1.jpg', 3, 20, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Denim | Fit: Regular | Color: Blue | Care: Machine wash', '2026-03-17 21:15:38'),
(4, 'Slim Fit Jeans', 'Modern slim fit jeans', 60.00, 'jeans-2.jpg', 3, 18, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Stretch Denim | Fit: Slim | Color: Light Blue | Care: Machine wash', '2026-03-17 21:15:38'),
(5, 'Light Wash Jeans', 'Light blue casual jeans', 52.00, 'jeans-3.jpg', 3, 15, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Cotton Denim | Fit: Regular | Color: Light Blue', '2026-03-17 21:15:38'),
(6, 'Dark Blue Jeans', 'Elegant dark jeans', 65.00, 'jeans-4.jpg', 3, 12, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Premium Denim | Fit: Slim | Color: Dark Blue', '2026-03-17 21:15:38'),
(7, 'Grey Jeans', 'Stylish grey jeans', 58.00, 'jeans-5.jpg', 3, 14, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Cotton Blend | Fit: Slim | Color: Grey', '2026-03-17 21:15:38'),
(8, 'Straight Fit Jeans', 'Comfortable straight fit jeans', 54.00, 'jeans-6.jpg', 3, 16, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Denim | Fit: Straight | Color: Blue', '2026-03-17 21:15:38'),
(9, 'Ripped Jeans', 'Trendy ripped jeans', 62.00, 'jeans-7.jpg', 3, 10, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Denim | Fit: Slim | Style: Ripped', '2026-03-17 21:15:38'),
(10, 'Skinny Jeans', 'Tight fit skinny jeans', 59.00, 'jeans-8.jpg', 3, 13, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Stretch Denim | Fit: Skinny', '2026-03-17 21:15:38'),
(11, 'Casual Blue Jeans', 'Everyday casual jeans', 50.00, 'jeans-9.jpg', 3, 20, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Cotton Denim | Fit: Regular', '2026-03-17 21:15:38'),
(12, 'Vintage Jeans', 'Retro style jeans', 68.00, 'jeans-10.jpg', 3, 8, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Heavy Denim | Fit: Loose', '2026-03-17 21:15:38'),
(13, 'Loose Fit Jeans', 'Relaxed loose fit jeans', 57.00, 'jeans-11.jpg', 3, 11, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Denim | Fit: Loose', '2026-03-17 21:15:38'),
(14, 'Black Jeans', 'Classic black jeans', 63.00, 'jeans-12.jpg', 3, 17, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', 'Material: Premium Denim | Fit: Slim | Color: Black', '2026-03-17 21:15:38'),
(45, 'Classic Leather Shoes', 'Elegant formal leather shoes', 85.00, 'shoes-1.jpg', 6, 12, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(46, 'Brown Formal Shoes', 'Premium brown office shoes', 90.00, 'shoes-2.jpg', 6, 10, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(47, 'Luxury Dress Shoes', 'High-end elegant shoes', 110.00, 'shoes-3.jpg', 6, 8, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(48, 'Casual Sneakers', 'Comfortable daily sneakers', 65.00, 'shoes-4.jpg', 6, 20, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(49, 'Beige Sneakers', 'Stylish beige sneakers', 70.00, 'shoes-5.jpg', 6, 18, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(50, 'White Sport Shoes', 'Lightweight running shoes', 75.00, 'shoes-6.jpg', 6, 15, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(51, 'Leather Boots', 'Classic leather boots', 120.00, 'shoes-7.jpg', 6, 7, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(52, 'White Sneakers', 'Clean modern sneakers', 68.00, 'shoes-8.jpg', 6, 14, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(53, 'Black Sneakers', 'Casual black sneakers', 66.00, 'shoes-9.jpg', 6, 16, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(54, 'Minimal White Shoes', 'Simple stylish white shoes', 72.00, 'shoes-10.jpg', 6, 13, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-17 22:08:11'),
(67, 'Brown Classic Jacket', 'Classic brown everyday jacket', 320.00, 'jacket-1.jpg', 5, 15, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(68, 'Black Sport Jacket', 'Modern black sporty jacket', 280.00, 'jacket-2.jpg', 5, 12, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(69, 'Dark Minimal Jacket', 'Minimalist dark jacket', 300.00, 'jacket-3.jpg', 5, 10, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(70, 'Grey Urban Jacket', 'Urban grey stylish jacket', 310.00, 'jacket-4.jpg', 5, 11, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(71, 'Blue Denim Style Jacket', 'Denim-inspired blue jacket', 350.00, 'jacket-5.jpg', 5, 13, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(72, 'Dark Brown Coat Jacket', 'Elegant dark brown coat jacket', 420.00, 'jacket-6.jpg', 5, 8, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(73, 'Beige Casual Jacket', 'Light beige casual jacket', 295.00, 'jacket-7.jpg', 5, 14, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(74, 'Light Grey Modern Jacket', 'Modern light grey jacket', 305.00, 'jacket-8.jpg', 5, 9, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(75, 'Cream Bomber Jacket', 'Trendy cream bomber jacket', 330.00, 'jacket-9.jpg', 5, 10, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(76, 'Black Leather Jacket', 'Premium black leather jacket', 480.00, 'jacket-10.jpg', 5, 7, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(77, 'Olive Casual Jacket', 'Olive green everyday jacket', 290.00, 'jacket-11.jpg', 5, 12, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(78, 'Black Classic Zip Jacket', 'Classic black zip jacket', 310.00, 'jacket-12.jpg', 5, 11, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:04:59'),
(79, 'Pink Summer Dress', 'Light pink summer dress', 260.00, 'dress-1.jpg', 1, 15, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(80, 'Red Elegant Dress', 'Elegant red evening dress', 420.00, 'dress-2.jpg', 1, 10, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(81, 'Blue Classic Dress', 'Classic blue dress', 350.00, 'dress-3.jpg', 1, 12, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(82, 'Floral Casual Dress', 'Floral daily dress', 280.00, 'dress-4.jpg', 1, 14, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(83, 'Green Midi Dress', 'Stylish green midi dress', 330.00, 'dress-5.jpg', 1, 11, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(84, 'White Floral Dress', 'Fresh floral summer dress', 300.00, 'dress-6.jpg', 1, 13, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(85, 'Black Evening Dress', 'Elegant black dress', 450.00, 'dress-7.jpg', 1, 9, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(86, 'Cream Wrap Dress', 'Modern wrap dress', 340.00, 'dress-8.jpg', 1, 10, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(87, 'Vintage Pattern Dress', 'Retro style dress', 370.00, 'dress-9.jpg', 1, 8, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(88, 'Pink Floral Dress', 'Cute floral pink dress', 290.00, 'dress-10.jpg', 1, 12, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(89, 'Boho White Dress', 'Bohemian white dress', 410.00, 'dress-11.jpg', 1, 7, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(90, 'Green Floral Dress', 'Soft green floral dress', 310.00, 'dress-12.jpg', 1, 13, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:05:28'),
(91, 'Classic White Shirt', 'Elegant white formal shirt', 180.00, 'shirts-1.jpg', 2, 20, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(92, 'Green Casual Shirt', 'Casual green shirt', 160.00, 'shirts-2.jpg', 2, 18, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(93, 'Light Denim Shirt', 'Stylish denim shirt', 220.00, 'shirts-3.jpg', 2, 15, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(94, 'Navy Polo Shirt', 'Comfortable navy polo', 140.00, 'shirts-4.jpg', 2, 22, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(95, 'Slim Fit Blue Shirt', 'Modern slim fit shirt', 200.00, 'shirts-5.jpg', 2, 17, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(96, 'Checkered Shirt', 'Casual checkered shirt', 210.00, 'shirts-6.jpg', 2, 14, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(97, 'Dark Green Polo', 'Stylish green polo shirt', 150.00, 'shirts-7.jpg', 2, 19, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(98, 'Brown Long Sleeve Shirt', 'Warm brown shirt', 190.00, 'shirts-8.jpg', 2, 13, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(99, 'Denim Jacket Shirt', 'Trendy overshirt', 260.00, 'shirts-9.jpg', 2, 11, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(100, 'Striped Summer Shirt', 'Light striped shirt', 170.00, 'shirts-10.jpg', 2, 16, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(101, 'Light Blue Shirt', 'Classic light blue shirt', 180.00, 'shirts-11.jpg', 2, 18, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(102, 'Grey Casual Shirt', 'Simple grey shirt', 150.00, 'shirts-12.jpg', 2, 20, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(103, 'Beige Summer Shirt', 'Light beige shirt', 140.00, 'shirts-13.jpg', 2, 15, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(104, 'Plaid Shirt', 'Casual plaid shirt', 210.00, 'shirts-14.jpg', 2, 12, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(105, 'Dark Grey Shirt', 'Modern dark grey shirt', 200.00, 'shirts-15.jpg', 2, 14, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(106, 'Striped Formal Shirt', 'Formal striped shirt', 220.00, 'shirts-16.jpg', 2, 10, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(107, 'Casual Blue Blazer', 'Light blue blazer', 320.00, 'blazer-1.jpg', 4, 10, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(108, 'Grey Classic Blazer', 'Formal grey blazer', 350.00, 'blazer-2.jpg', 4, 8, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(109, 'Light Grey Blazer', 'Elegant blazer', 330.00, 'blazer-3.jpg', 4, 9, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(110, 'Dark Formal Blazer', 'Black blazer', 400.00, 'blazer-4.jpg', 4, 7, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(111, 'Modern Fit Blazer', 'Slim fit blazer', 380.00, 'blazer-5.jpg', 4, 6, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(112, 'Winter Blazer', 'Warm blazer', 420.00, 'blazer-6.jpg', 4, 5, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(113, 'Beige Smart Blazer', 'Elegant beige blazer', 360.00, 'blazer-7.jpg', 4, 8, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(114, 'Light Formal Blazer', 'Soft tone blazer', 340.00, 'blazer-8.jpg', 4, 9, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(115, 'Blue Business Blazer', 'Professional blazer', 390.00, 'blazer-9.jpg', 4, 6, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(116, 'Dark Navy Blazer', 'Premium blazer', 410.00, 'blazer-10.jpg', 4, 5, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42'),
(117, 'Cream Blazer', 'Soft cream blazer', 350.00, 'blazer-11.jpg', 4, 7, 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.\r\n\r\nDesigned with attention to detail and crafted from high-quality materials, this product offers both comfort and durability. Perfect for everyday wear, it combines modern style with a timeless look, making it a versatile addition to any wardrobe.', '', '2026-03-18 12:10:42');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_name`, `user_email`, `rating`, `review_text`, `created_at`, `status`) VALUES
(12, 3, 'ahmed', 'a.h2@gmail.cpm', 3, 'good', '2026-03-18 20:24:35', 0),
(13, 3, 'ahmed', 'a.h2@gmail.cpm', 3, 'good', '2026-03-18 20:25:08', 0),
(14, 3, 'ahmed', 'a.h2@gmail.cpm', 3, 'good', '2026-03-18 20:28:28', 1),
(15, 12, 'ahmed', 'a.h2@gmail.cpm', 3, 'good', '2026-03-19 15:16:54', 1),
(16, 12, 'ahmed', 'a.h2@gmail.cpm', 4, 'fantastic', '2026-03-19 15:17:23', 1),
(17, 12, 'ahmed', 'a.h2@gmail.cpm', 5, 'good', '2026-03-19 15:23:43', 1),
(18, 4, 'ahmed', 'a.h2@gmail.cpm', 5, 'fantastic', '2026-03-19 15:36:45', 1),
(19, 69, 'ahmed', 'a.h2@gmail.cpm', 2, 'good', '2026-03-21 14:12:07', 1),
(21, 117, 'hao', 'elkn.gmail@com', 5, 'bfkb', '2026-03-30 21:23:41', 1),
(22, 117, 'ahmed', 'a.h@gmail.com', 5, 'lorem', '2026-03-30 21:24:16', 1),
(23, 99, 'ahmed', 'a.h@gmail.com', 5, 'hjdbj', '2026-03-30 21:28:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `verification_code` varchar(6) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expire` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `verification_code`, `is_verified`, `reset_token`, `token_expire`, `created_at`, `updated_at`) VALUES
(34, 'ahmed hossam', 'ahmed.hossam.23098@gmail.com', '$2y$10$eC2/hnh/gGlre/G7XceBnOGMCcNWbEq6Dl2.X54JNztXFCvwv4/4.', NULL, NULL, 'admin', NULL, 1, NULL, NULL, '2026-03-27 17:26:36', '2026-03-27 22:13:15'),
(37, 'ahmed hossam', 'admin@gmail.com', '$2y$10$zLFYmQZzo73srY6Yf8AG7OhOJgr23ze28dTMecIjfbAD1ah9dYR3e', NULL, NULL, 'admin', NULL, 0, NULL, NULL, '2026-03-27 22:48:32', '2026-03-27 22:48:32'),
(38, 'ahmed', 'a.h13900139@gmail.com', '$2y$10$60wqb009z1m.LptPf/OOHemg.hcbtgevUUi4HFcaXqKww2g8e0wB2', NULL, NULL, 'user', NULL, 1, NULL, NULL, '2026-03-30 21:30:10', '2026-03-30 21:31:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
