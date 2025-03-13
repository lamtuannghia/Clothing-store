-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 11, 2025 lúc 09:52 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `rhodi_database`
--
CREATE DATABASE IF NOT EXISTS `rhodi_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rhodi_database`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `email`, `password`, `role`, `phone`) VALUES
(1, 'Lâm Tuấn Nghĩa', 'admin@gmail.com', 'admin', 'admin', '0984690512'),
(2, 'Nghia', 'staff@gmail.com', 'staff', 'staff', '0987654321');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bill`
--

DROP TABLE IF EXISTS `bill`;
CREATE TABLE IF NOT EXISTS `bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `payment` varchar(255) NOT NULL,
  `status` enum('pending','shipped','cancelled','') NOT NULL DEFAULT 'pending',
  `time_create` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bill`
--

INSERT INTO `bill` (`id`, `user_id`, `address`, `full_name`, `email`, `phone`, `note`, `payment`, `status`, `time_create`) VALUES
(1, 1, 'Thành phố Hà Nội, Việt Nam', 'Cơ hội của LAM TUAN NGHIA', 'lamnghia850@gmail.com', '0984690512', 'ship nhanh giup khach', 'COD', 'cancelled', '2025-02-18 15:05:15'),
(2, 1, 'Thành phố Hà Nội, Việt Nam', 'Lâm Tuấn Nghĩa', 'nghia@gmail.com', '0987654321', '', 'COD', 'shipped', '2025-02-25 07:20:43'),
(3, 1, 'hà nội', 'Lâm Tuấn Nghĩa', 'nghia@gmail.com', '0987654321', '', 'BANK TRANSFER', 'shipped', '2025-02-26 16:35:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `color` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `product_id`, `quantity`, `color`, `size`) VALUES
(47, 16, 3, 'Trắng', 'M'),
(48, 30, 1, 'Trắng', 'M'),
(49, 30, 1, 'Đen', 'M');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cate`
--

DROP TABLE IF EXISTS `cate`;
CREATE TABLE IF NOT EXISTS `cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cate`
--

INSERT INTO `cate` (`id`, `name`) VALUES
(1, 'Quần'),
(2, 'Áo'),
(3, 'Phụ Kiện');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`id`),
  KEY `cate_id` (`cate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `cate_id`, `name`) VALUES
(5, 1, 'Quần Âu'),
(6, 1, 'Quần Jeans'),
(7, 2, 'Áo Khoác'),
(8, 2, 'Áo Nỉ len'),
(9, 2, 'Áo Polo'),
(10, 2, 'Áo Phông'),
(11, 2, 'Áo Sơmi'),
(12, 3, 'Túi'),
(13, 3, 'Mũ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `color` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `bill_id`, `product_id`, `quantity`, `color`, `size`) VALUES
(7, 1, 10, 1, 'Trắng', 'M'),
(8, 2, 16, 1, 'Trắng', 'M'),
(9, 2, 22, 2, 'Xám', 'XL'),
(10, 2, 32, 1, 'Xanh than', 'S'),
(11, 3, 16, 1, 'Đen', 'M'),
(12, 3, 19, 1, 'Be', 'M'),
(13, 3, 32, 1, 'Xanh than', 'S'),
(14, 3, 32, 1, 'Xám', 'S'),
(15, 3, 22, 1, 'Trắng', 'M'),
(16, 3, 22, 1, 'Be', 'M');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL,
  `status` enum('In stock','Pre order','Sold out') NOT NULL DEFAULT 'In stock',
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id`, `name`, `quantity`, `price`, `status`, `category_id`) VALUES
(9, 'Quần Âu K286', 0, 260000, 'In stock', 5),
(10, 'Quần Âu K290', 73, 270000, 'In stock', 5),
(11, 'Quần Âu Dạ Cúc', 98, 320000, 'In stock', 5),
(12, 'Quần Âu Tăm Lì', 124, 280000, 'In stock', 5),
(13, 'Quần Âu Vải 568', 97, 220000, 'In stock', 5),
(14, 'Quần JEANS Dust', 108, 270000, 'In stock', 6),
(15, 'Quần JEANS Ống Suông', 31, 210000, 'In stock', 6),
(16, 'Áo Khoác lông', 88, 280000, 'In stock', 7),
(17, 'Áo Blazer', 74, 250000, 'In stock', 7),
(18, 'Áo Hoodie Số 3', 84, 180000, 'In stock', 8),
(19, 'Áo Sweater Couple', 66, 180000, 'In stock', 8),
(20, 'Áo Sweater Aimer', 81, 180000, 'In stock', 8),
(21, 'Áo Polo Kẻ', 101, 140000, 'In stock', 9),
(22, 'Áo Polo Tăm Cổ Viền', 242, 160000, 'In stock', 9),
(23, 'Áo 2 Cậu bé', 108, 160000, 'In stock', 10),
(24, 'Áo Cassette', 97, 170000, 'In stock', 10),
(25, 'Áo Croissant', 113, 160000, 'In stock', 10),
(26, 'Áo Viền Chữ', 107, 160000, 'In stock', 10),
(27, 'Sơmi Cổ Trụ', 128, 180000, 'In stock', 11),
(28, 'Sơmi Hoa', 57, 200000, 'In stock', 11),
(29, 'Sowmi Thô 2 túi', 151, 160000, 'In stock', 11),
(30, 'Sơmi Cộc Tay', 159, 160000, 'In stock', 11),
(31, 'Mũ Acefire', 67, 80000, 'In stock', 13),
(32, 'Mũ Caro Dorle', 48, 90000, 'In stock', 13),
(33, 'Mũ Len Tiêu', 112, 80000, 'In stock', 13);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_inventory`
--

DROP TABLE IF EXISTS `product_inventory`;
CREATE TABLE IF NOT EXISTS `product_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `color` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_inventory`
--

INSERT INTO `product_inventory` (`id`, `product_id`, `color`, `image`) VALUES
(21, 9, 'Đen', '1739462002_20250124_uQxYaHm6h0.jpeg'),
(22, 9, 'Be', '1739462016_20250124_6j5lob2AKR.jpeg'),
(23, 10, 'Trắng', '1739462042_20250124_uyHBP8WTTv.jpeg'),
(24, 10, 'Be', '1739462066_20250124_VSlq3koaF5.jpeg'),
(25, 11, 'Đen', '1739462114_20250125_qU3YVhEn53.jpeg'),
(26, 11, 'Xám nhạt', '1739462128_20250125_gnmvEcs0E0.jpeg'),
(27, 11, 'Xám', '1739462139_20250125_2KkAIsKwTC.jpeg'),
(28, 12, 'Đen', '1739462170_20250108_zRaTh0tzJo.jpeg'),
(29, 12, 'Xám', '1739462184_20250108_gH9xwpFFVj.jpeg'),
(30, 12, 'Đỏ', '1739462197_20250108_kBhafpzn8f.jpeg'),
(31, 13, 'Đen', '1739462220_20250109_7XsqdSJg8b.jpeg'),
(32, 13, 'Nâu', '1739462230_20250109_9kRNYL4Jxx.jpeg'),
(33, 13, 'Xám', '1739462239_20250109_2Qm1cFYipY.jpeg'),
(34, 14, 'Đen', '1739462297_20250125_eBLAURujDv.jpeg'),
(35, 14, 'Xanh than', '1739462325_20250125_fbQQ4IbIZJ.jpeg'),
(36, 14, 'Xanh nhạt', '1739462338_20250125_edXAMpYrNj.jpeg'),
(37, 15, 'Xám', '1739462504_20240917_vXJl47h5F2.jpeg'),
(38, 16, 'Trắng', '1739462572_20250108_4KMvqgstIJ.jpeg'),
(39, 16, 'Đen', '1739462583_20250108_GRHHAsAmtp.jpeg'),
(40, 17, 'Be', '1739462632_20241228_1iXDPPI1cK.jpeg'),
(41, 17, 'Đen', '1739462645_20241228_nZdgJiQXSH.jpeg'),
(42, 18, 'Nâu', '1739462710_20250125_gJMhOkkwvW.jpeg'),
(43, 18, 'Xám', '1739462721_20250125_IrULuErwDX.jpeg'),
(44, 19, 'Be', '1739462907_20250208_aSCImgtq4u.jpeg'),
(45, 19, 'Đen', '1739462918_20250208_xPGtS5Ga5j.jpeg'),
(46, 20, 'Trắng', '1739462973_20250208_Q52uHvXw6F.jpeg'),
(47, 20, 'Be', '1739462986_20250208_ZOoMevDsNw.jpeg'),
(48, 21, 'Đen', '1739463080_20250207_40n57hdKyU.jpeg'),
(49, 21, 'Xanh', '1739463113_20250207_D7c31WfTpu.jpeg'),
(50, 21, 'Nâu', '1739463122_20250207_MSIx1tlrsT.jpeg'),
(51, 22, 'Xanh rêu', '1739463143_20250207_6HGJy6dmnu.jpeg'),
(52, 22, 'Trắng', '1739463151_20250207_84w7zle18X.jpeg'),
(53, 22, 'Be', '1739463162_20250207_AXbaDFqCpy.jpeg'),
(54, 22, 'Xám', '1739463170_20250207_bYr3L5UkhR.jpeg'),
(55, 22, 'Đen', '1739463183_20250207_JyqsTK2FPb.jpeg'),
(56, 23, 'Trắng', '1739463240_20250207_CrPj2fRDXn.jpeg'),
(57, 23, 'Be', '1739463249_20250207_ctOOm7lYSr.jpeg'),
(58, 23, 'Đen', '1739463257_20250207_pMRU8l4BB6.jpeg'),
(59, 24, 'Đen', '1739463564_20240818_tThFp1URnz.jpeg'),
(60, 24, 'Trắng', '1739463573_20240818_GsW1Ux4Z6g.jpeg'),
(61, 25, 'Trắng', '1739463641_20240913_bGpiFX4mFB.jpeg'),
(62, 25, 'Đen', '1739463651_20240913_xLrkYv06gH.jpeg'),
(63, 26, 'Đen', '1739463685_20250207_Exus7ptSfk.jpeg'),
(64, 26, 'Nâu', '1739463692_20250207_QPMZDNrXvq.jpeg'),
(65, 26, 'Trắng', '1739463703_20250207_xfZysowM3l.jpeg'),
(66, 27, 'Trắng', '1739463754_20240924_QHAalDzWUt.jpeg'),
(67, 27, 'Đen', '1739463762_20240924_ZVWdnQKkFE.jpeg'),
(68, 28, 'Be', '1739463870_20240912_OXX8QUAevj.jpeg'),
(69, 29, 'Đen', '1739464005_20250207_xcjKSw7rD9.jpeg'),
(70, 29, 'Trắng', '1739464013_20250207_VKNF3KiK6m.jpeg'),
(71, 29, 'Be', '1739464023_20250207_TTWeUgCxPh.jpeg'),
(72, 30, 'Trắng', '1739464069_20240717_0zf8LVzP1d.jpeg'),
(73, 30, 'Đen', '1739464080_20240924_CxgKViALZT.jpeg'),
(74, 30, 'Be', '1739464096_20240924_Lb3gHljoCX.jpeg'),
(75, 31, 'Đen', '1739464156_20250116_b7ty06toIx.jpeg'),
(76, 31, 'Xanh rêu', '1739464165_20250116_rcpVu7AqZn.jpeg'),
(77, 31, 'Nâu', '1739464172_20250116_xF6nFygoRl.jpeg'),
(78, 32, 'Xám', '1739464230_20250125_N1Lp7xQCli.jpeg'),
(79, 32, 'Xanh than', '1739464238_20250125_nEETQMzLJJ.jpeg'),
(80, 32, 'Xanh rêu', '1739464244_20250125_ZRKJ2yPbyf.jpeg'),
(81, 33, 'Xanh rêu', '1739464279_20241201_1Q0lWb6y25.jpeg'),
(82, 33, 'Xám nhạt', '1739464286_20241201_3h1wFFbZyY.jpeg'),
(83, 33, 'Nâu', '1739464293_20241201_h22U0lXV9p.jpeg'),
(84, 33, 'Đỏ', '1739464300_20241201_M8CzbQyNXW.jpeg'),
(85, 33, 'Đen', '1739464309_20241201_qPWk9zPLE7.jpeg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `size`
--

DROP TABLE IF EXISTS `size`;
CREATE TABLE IF NOT EXISTS `size` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inven_id` int(11) NOT NULL,
  `size` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `color_id` (`inven_id`)
) ENGINE=InnoDB AUTO_INCREMENT=378 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `size`
--

INSERT INTO `size` (`id`, `inven_id`, `size`, `quantity`) VALUES
(23, 21, 'S', 0),
(24, 21, 'M', 0),
(25, 21, 'L', 0),
(26, 21, 'XL', 0),
(27, 21, 'XXL', 0),
(28, 22, 'S', 0),
(29, 22, 'M', 0),
(30, 22, 'L', 0),
(31, 22, 'XL', 0),
(32, 22, 'XXL', 0),
(33, 23, 'S', 11),
(34, 23, 'M', 15),
(35, 23, 'L', 4),
(36, 23, 'XL', 11),
(37, 23, 'XXL', 0),
(38, 24, 'S', 13),
(39, 24, 'M', 12),
(40, 24, 'L', 6),
(41, 24, 'XL', 1),
(42, 24, 'XXL', 0),
(43, 25, 'S', 9),
(44, 25, 'M', 11),
(45, 25, 'L', 5),
(46, 25, 'XL', 7),
(47, 25, 'XXL', 0),
(48, 26, 'S', 9),
(49, 26, 'M', 8),
(50, 26, 'L', 9),
(51, 26, 'XL', 7),
(52, 26, 'XXL', 0),
(53, 27, 'S', 10),
(54, 27, 'M', 9),
(55, 27, 'L', 4),
(56, 27, 'XL', 10),
(57, 27, 'XXL', 0),
(58, 28, 'S', 8),
(59, 28, 'M', 11),
(60, 28, 'L', 13),
(61, 28, 'XL', 5),
(62, 28, 'XXL', 0),
(63, 29, 'S', 11),
(64, 29, 'M', 12),
(65, 29, 'L', 8),
(66, 29, 'XL', 16),
(67, 29, 'XXL', 0),
(68, 30, 'S', 10),
(69, 30, 'M', 9),
(70, 30, 'L', 16),
(71, 30, 'XL', 5),
(72, 30, 'XXL', 0),
(73, 31, 'S', 10),
(74, 31, 'M', 11),
(75, 31, 'L', 9),
(76, 31, 'XL', 4),
(77, 31, 'XXL', 0),
(78, 32, 'S', 9),
(79, 32, 'M', 9),
(80, 32, 'L', 9),
(81, 32, 'XL', 4),
(82, 32, 'XXL', 0),
(83, 33, 'S', 9),
(84, 33, 'M', 9),
(85, 33, 'L', 12),
(86, 33, 'XL', 2),
(87, 33, 'XXL', 0),
(88, 34, 'S', 11),
(89, 34, 'M', 12),
(90, 34, 'L', 11),
(91, 34, 'XL', 9),
(92, 34, 'XXL', 0),
(93, 35, 'S', 8),
(94, 35, 'M', 11),
(95, 35, 'L', 8),
(96, 35, 'XL', 3),
(97, 35, 'XXL', 0),
(98, 36, 'S', 10),
(99, 36, 'M', 3),
(100, 36, 'L', 15),
(101, 36, 'XL', 7),
(102, 36, 'XXL', 0),
(103, 37, 'S', 10),
(104, 37, 'M', 9),
(105, 37, 'L', 5),
(106, 37, 'XL', 7),
(107, 37, 'XXL', 0),
(108, 38, 'S', 10),
(109, 38, 'M', 11),
(110, 38, 'L', 10),
(111, 38, 'XL', 12),
(112, 38, 'XXL', 0),
(113, 39, 'S', 10),
(114, 39, 'M', 12),
(115, 39, 'L', 15),
(116, 39, 'XL', 8),
(117, 39, 'XXL', 0),
(118, 40, 'S', 0),
(119, 40, 'M', 14),
(120, 40, 'L', 15),
(121, 40, 'XL', 9),
(122, 40, 'XXL', 0),
(123, 41, 'S', 0),
(124, 41, 'M', 14),
(125, 41, 'L', 13),
(126, 41, 'XL', 9),
(127, 41, 'XXL', 0),
(128, 42, 'S', 13),
(129, 42, 'M', 12),
(130, 42, 'L', 10),
(131, 42, 'XL', 8),
(132, 42, 'XXL', 0),
(133, 43, 'S', 7),
(134, 43, 'M', 12),
(135, 43, 'L', 14),
(136, 43, 'XL', 8),
(137, 43, 'XXL', 0),
(138, 44, 'S', 0),
(139, 44, 'M', 11),
(140, 44, 'L', 11),
(141, 44, 'XL', 12),
(142, 44, 'XXL', 0),
(143, 45, 'S', 0),
(144, 45, 'M', 10),
(145, 45, 'L', 9),
(146, 45, 'XL', 13),
(147, 45, 'XXL', 0),
(148, 46, 'S', 11),
(149, 46, 'M', 13),
(150, 46, 'L', 11),
(151, 46, 'XL', 7),
(152, 46, 'XXL', 0),
(153, 47, 'S', 11),
(154, 47, 'M', 10),
(155, 47, 'L', 8),
(156, 47, 'XL', 10),
(157, 47, 'XXL', 0),
(158, 48, 'S', 8),
(159, 48, 'M', 11),
(160, 48, 'L', 7),
(161, 48, 'XL', 9),
(162, 48, 'XXL', 0),
(163, 49, 'S', 10),
(164, 49, 'M', 11),
(165, 49, 'L', 11),
(166, 49, 'XL', 9),
(167, 49, 'XXL', 0),
(168, 50, 'S', 10),
(169, 50, 'M', 0),
(170, 50, 'L', 15),
(171, 50, 'XL', 0),
(172, 50, 'XXL', 0),
(173, 51, 'S', 10),
(174, 51, 'M', 8),
(175, 51, 'L', 13),
(176, 51, 'XL', 11),
(177, 51, 'XXL', 0),
(178, 52, 'S', 10),
(179, 52, 'M', 17),
(180, 52, 'L', 15),
(181, 52, 'XL', 13),
(182, 52, 'XXL', 0),
(183, 53, 'S', 10),
(184, 53, 'M', 15),
(185, 53, 'L', 17),
(186, 53, 'XL', 16),
(187, 53, 'XXL', 0),
(188, 54, 'S', 14),
(189, 54, 'M', 13),
(190, 54, 'L', 13),
(191, 54, 'XL', 9),
(192, 54, 'XXL', 0),
(193, 55, 'S', 4),
(194, 55, 'M', 10),
(195, 55, 'L', 10),
(196, 55, 'XL', 14),
(197, 55, 'XXL', 0),
(198, 56, 'S', 7),
(199, 56, 'M', 11),
(200, 56, 'L', 11),
(201, 56, 'XL', 10),
(202, 56, 'XXL', 0),
(203, 57, 'S', 5),
(204, 57, 'M', 9),
(205, 57, 'L', 10),
(206, 57, 'XL', 9),
(207, 57, 'XXL', 0),
(208, 58, 'S', 4),
(209, 58, 'M', 12),
(210, 58, 'L', 12),
(211, 58, 'XL', 8),
(212, 58, 'XXL', 0),
(213, 59, 'S', 12),
(214, 59, 'M', 10),
(215, 59, 'L', 13),
(216, 59, 'XL', 10),
(217, 59, 'XXL', 0),
(218, 60, 'S', 15),
(219, 60, 'M', 11),
(220, 60, 'L', 11),
(221, 60, 'XL', 15),
(222, 60, 'XXL', 0),
(223, 61, 'S', 15),
(224, 61, 'M', 16),
(225, 61, 'L', 11),
(226, 61, 'XL', 10),
(227, 61, 'XXL', 0),
(228, 62, 'S', 15),
(229, 62, 'M', 15),
(230, 62, 'L', 13),
(231, 62, 'XL', 18),
(232, 62, 'XXL', 0),
(233, 63, 'S', 3),
(234, 63, 'M', 13),
(235, 63, 'L', 11),
(236, 63, 'XL', 3),
(237, 63, 'XXL', 0),
(238, 64, 'S', 8),
(239, 64, 'M', 11),
(240, 64, 'L', 10),
(241, 64, 'XL', 6),
(242, 64, 'XXL', 0),
(243, 65, 'S', 13),
(244, 65, 'M', 10),
(245, 65, 'L', 11),
(246, 65, 'XL', 8),
(247, 65, 'XXL', 0),
(248, 66, 'S', 13),
(249, 66, 'M', 13),
(250, 66, 'L', 12),
(251, 66, 'XL', 14),
(252, 66, 'XXL', 10),
(253, 67, 'S', 14),
(254, 67, 'M', 12),
(255, 67, 'L', 13),
(256, 67, 'XL', 12),
(257, 67, 'XXL', 15),
(258, 68, 'S', 14),
(259, 68, 'M', 10),
(260, 68, 'L', 20),
(261, 68, 'XL', 7),
(262, 68, 'XXL', 6),
(263, 69, 'S', 14),
(264, 69, 'M', 11),
(265, 69, 'L', 10),
(266, 69, 'XL', 11),
(267, 69, 'XXL', 7),
(268, 70, 'S', 10),
(269, 70, 'M', 11),
(270, 70, 'L', 11),
(271, 70, 'XL', 12),
(272, 70, 'XXL', 4),
(273, 71, 'S', 13),
(274, 71, 'M', 10),
(275, 71, 'L', 10),
(276, 71, 'XL', 11),
(277, 71, 'XXL', 6),
(278, 72, 'S', 12),
(279, 72, 'M', 12),
(280, 72, 'L', 11),
(281, 72, 'XL', 10),
(282, 72, 'XXL', 7),
(283, 73, 'S', 13),
(284, 73, 'M', 11),
(285, 73, 'L', 10),
(286, 73, 'XL', 11),
(287, 73, 'XXL', 8),
(288, 74, 'S', 12),
(289, 74, 'M', 10),
(290, 74, 'L', 12),
(291, 74, 'XL', 11),
(292, 74, 'XXL', 9),
(293, 75, 'S', 20),
(294, 75, 'M', 0),
(295, 75, 'L', 0),
(296, 75, 'XL', 0),
(297, 75, 'XXL', 0),
(298, 76, 'S', 24),
(299, 76, 'M', 0),
(300, 76, 'L', 0),
(301, 76, 'XL', 0),
(302, 76, 'XXL', 0),
(303, 77, 'S', 23),
(304, 77, 'M', 0),
(305, 77, 'L', 0),
(306, 77, 'XL', 0),
(307, 77, 'XXL', 0),
(308, 78, 'S', 16),
(309, 78, 'M', 0),
(310, 78, 'L', 0),
(311, 78, 'XL', 0),
(312, 78, 'XXL', 0),
(313, 79, 'S', 15),
(314, 79, 'M', 0),
(315, 79, 'L', 0),
(316, 79, 'XL', 0),
(317, 79, 'XXL', 0),
(318, 80, 'S', 17),
(319, 80, 'M', 0),
(320, 80, 'L', 0),
(321, 80, 'XL', 0),
(322, 80, 'XXL', 0),
(323, 81, 'S', 17),
(324, 81, 'M', 0),
(325, 81, 'L', 0),
(326, 81, 'XL', 0),
(327, 81, 'XXL', 0),
(328, 82, 'S', 26),
(329, 82, 'M', 0),
(330, 82, 'L', 0),
(331, 82, 'XL', 0),
(332, 82, 'XXL', 0),
(333, 83, 'S', 25),
(334, 83, 'M', 0),
(335, 83, 'L', 0),
(336, 83, 'XL', 0),
(337, 83, 'XXL', 0),
(338, 84, 'S', 21),
(339, 84, 'M', 0),
(340, 84, 'L', 0),
(341, 84, 'XL', 0),
(342, 84, 'XXL', 0),
(343, 85, 'S', 23),
(344, 85, 'M', 0),
(345, 85, 'L', 0),
(346, 85, 'XL', 0),
(347, 85, 'XXL', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `full_name`, `email`, `password`, `phone`) VALUES
(1, 'Lâm Tuấn Nghĩa', 'nghia@gmail.com', '123', '0987654321'),
(13, 'Cơ hội của LAM TUAN NGHIA', 'lamnghia850@gmail.com', '123', '0984690512');

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`cate_id`) REFERENCES `cate` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD CONSTRAINT `product_inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `size`
--
ALTER TABLE `size`
  ADD CONSTRAINT `size_ibfk_1` FOREIGN KEY (`inven_id`) REFERENCES `product_inventory` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
