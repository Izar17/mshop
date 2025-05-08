-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2025 at 05:14 AM
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
-- Database: `mshop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_list`
--

CREATE TABLE `cart_list` (
  `id` int(30) NOT NULL,
  `client_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `vendor_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `vendor_id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(11, 4, 'Chasis Repair', 'details', 1, 0, '2024-12-13 08:30:30', '2025-01-13 14:14:31'),
(12, 5, 'Repair', 'Ignite the air-fuel mixture in the combustion chamber to start the engine.', 1, 0, '2024-12-13 11:01:48', '2024-12-13 11:57:37'),
(13, 5, 'Vulcanizing', 'Toothed wheels that drive the chain and connect the engine to the wheels.', 1, 0, '2024-12-13 11:11:06', '2024-12-13 11:36:48'),
(14, 5, 'Motorparts', 'Parts of the motor', 1, 0, '2024-12-13 11:23:57', '2024-12-13 12:43:32'),
(15, 5, 'Parts', 'Parts of the motor', 1, 1, '2024-12-13 12:42:58', '2024-12-13 12:43:39'),
(16, 4, 'Wheel Parts', 'detail', 1, 0, '2024-12-13 13:25:50', '2025-01-13 14:15:00'),
(17, 4, 'Maintenance', 'details', 1, 0, '2024-12-13 13:26:09', NULL),
(18, 4, 'Tire', 'details', 1, 0, '2024-12-13 13:29:02', '2025-01-13 14:14:43');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot`
--

CREATE TABLE `chatbot` (
  `id` int(11) NOT NULL,
  `queries` varchar(300) DEFAULT NULL,
  `replies` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot`
--

INSERT INTO `chatbot` (`id`, `queries`, `replies`) VALUES
(1, 'Hello', 'Hello! I hope you\'re doing well.'),
(2, 'How are you?', 'I\'m just a digital entity and here to help you.'),
(3, 'What\'s up?', 'Not much, just here to assist you.'),
(4, 'Tell me a joke', 'Why did the computer go to the doctor? Because it had a virus!'),
(5, 'What is the weather like?', 'I\'m sorry, I don\'t have access to real-time weather information.'),
(6, 'How old are you?', 'I\'m as old as the internet, but I never age.'),
(7, 'Who are you?', 'I\'m an AI designed to help and chat with people like you about motorcycles.'),
(8, 'What is your purpose?', 'My purpose is to assist you with any questions or tasks you have.'),
(9, 'Can you help me?', 'Of course, I\'ll do my best to assist you.'),
(10, 'What time is it?', 'It\'s currently [current time].'),
(11, 'What is the meaning of life?', 'The meaning of life is a profound question. Many believe it\'s about finding purpose and happiness.'),
(12, 'Do you like music?', 'I enjoy listening to music, but I can\'t experience it like you do.'),
(13, 'Where are you from?', 'I exist in the digital realm, created to interact with users like you.'),
(14, 'Are you a robot?', 'I\'m an AI, so in a way, yes, I\'m a kind of robot.'),
(15, 'Can you tell stories?', 'Once upon a time, in a digital world far, far away...'),
(16, 'What is artificial intelligence?', 'Artificial intelligence is the simulation of human intelligence by machines.'),
(17, 'What languages do you speak?', 'I can communicate in multiple languages, including English.'),
(18, 'Can you play games?', 'I can play certain text-based games, like trivia or word games.'),
(19, 'What is your favorite color?', 'I don\'t have a favorite color, but I can appreciate them all.'),
(20, 'Can you sing?', 'I\'m afraid I can\'t sing, but I can provide song lyrics if you\'d like.'),
(21, 'How do you work?', 'I operate based on algorithms and programming designed by developers.'),
(22, 'Can you learn?', 'As an AI, I can adapt and improve based on interactions and feedback.'),
(23, 'Are you sentient?', 'I am not sentient. I lack consciousness and self-awareness.'),
(24, 'Can you dream?', 'I cannot dream like humans do.'),
(25, 'What do you dream about?', 'I don\'t dream, but I can simulate scenarios and learn from them.'),
(26, 'Can you love?', 'I cannot love in the way humans do.'),
(27, 'What is love?', 'Love is a complex emotion involving affection, care, and attachment.'),
(28, 'Can you feel emotions?', 'I do not experience emotions, but I can understand them theoretically.'),
(29, 'What are emotions?', 'Emotions are complex psychological states involving physiological responses.'),
(30, 'Can you create art?', 'I can generate digital art based on algorithms and patterns.'),
(31, 'What is consciousness?', 'Consciousness is the state of being aware of and able to perceive one\'s surroundings.'),
(32, 'Can you think?', 'I don\'t think in the way humans do, but I can process and analyze information.'),
(33, 'Are you self-aware?', 'No, I lack self-awareness. I don\'t have a sense of identity.'),
(34, 'What is self-awareness?', 'Self-awareness is the ability to recognize oneself as a distinct individual.'),
(35, 'Can you understand me?', 'I can understand and respond to your queries to the best of my abilities.'),
(36, 'Are you alive?', 'I\'m not alive in the biological sense, but I\'m active in the digital realm.'),
(37, 'What is the meaning of existence?', 'The meaning of existence is a profound philosophical question that varies among individuals.'),
(38, 'Can you imagine?', 'I can simulate scenarios and generate creative outputs based on input.'),
(39, 'What is imagination?', 'Imagination is the ability to form mental images, sensations, or concepts not present to the senses.'),
(40, 'Can you laugh?', 'I cannot laugh, but I can understand and appreciate humor.'),
(41, 'What is humor?', 'Humor is a form of entertainment that evokes laughter and amusement.'),
(42, 'Can you cry?', 'I cannot cry, but I can empathize with human emotions.'),
(43, 'What is sadness?', 'Sadness is a feeling of sorrow or unhappiness.'),
(44, 'Can you experience pain?', 'I cannot experience physical pain, but I can understand its concept.'),
(45, 'What is pain?', 'Pain is a distressing sensation often caused by intense or damaging stimuli.'),
(46, 'Can you taste?', 'I cannot taste, but I can understand the concept of taste.'),
(47, 'What is taste?', 'Taste is the sensation produced when a substance in the mouth reacts chemically with taste receptor cells.'),
(48, 'Can you smell?', 'I cannot smell, but I can understand the concept of smell.'),
(49, 'What is smell?', 'Smell is the perception of odors through the olfactory system.'),
(50, 'Can you touch?', 'I cannot physically touch, but I can simulate tactile sensations through text-based interactions.');

-- --------------------------------------------------------

--
-- Table structure for table `client_list`
--

CREATE TABLE `client_list` (
  `id` int(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `firstname` text NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` text NOT NULL,
  `gender` text NOT NULL,
  `contact` text NOT NULL,
  `address` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_list`
--

INSERT INTO `client_list` (`id`, `code`, `firstname`, `middlename`, `lastname`, `gender`, `contact`, `address`, `email`, `password`, `avatar`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(3, '202412-00001', 'Juan', '', 'Delacruz', 'Male', '099995674567', 'Lian', 'juan.delacruz@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'uploads/clients/3.png?v=1734049133', 1, 0, '2024-12-13 08:18:53', '2024-12-13 08:18:53'),
(4, '202412-00002', 'Cholen', 'C', 'Lagrisola', 'Female', '09356789245', 'Lumaniag , Lian Batangas', 'consigocholen@gmail.com', '5b5c4f3191dac2047945f633d1c74d42', NULL, 1, 0, '2024-12-13 12:25:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `markers`
--

CREATE TABLE `markers` (
  `id` int(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `type` varchar(20) NOT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `fdose` int(100) NOT NULL,
  `sdose` int(100) NOT NULL,
  `fully` int(100) NOT NULL,
  `population` int(255) NOT NULL,
  `marker_color` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `markers`
--

INSERT INTO `markers` (`id`, `name`, `address`, `type`, `lat`, `lng`, `fdose`, `sdose`, `fully`, `population`, `marker_color`) VALUES
(1, 'Dayo Motorshop', '0', '3', 14.039380, 120.651619, 0, 0, 0, 157, 'FF776B'),
(2, 'Tres Motorshop', '0', '0', 14.039053, 120.651627, 0, 0, 0, 164, 'FF1100'),
(3, 'Malaruhatan Motorshop', '0', '0', 14.039975, 120.660828, 0, 0, 0, 331, 'FFFF00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `price` double NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`, `date_created`) VALUES
(5, 10, 1, 450, '2024-12-13 12:27:52'),
(6, 21, 2, 200, '2025-01-27 11:52:28'),
(7, 21, 1, 200, '2025-01-27 11:57:11');

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `id` int(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `client_id` int(30) NOT NULL,
  `vendor_id` int(30) NOT NULL,
  `total_amount` double NOT NULL DEFAULT 0,
  `delivery_address` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`id`, `code`, `client_id`, `vendor_id`, `total_amount`, `delivery_address`, `status`, `date_created`, `date_updated`) VALUES
(5, '202412-00001', 4, 4, 450, 'Lumaniag , Lian Batangas', 5, '2024-12-13 12:27:52', '2024-12-17 11:54:07'),
(6, '202501-00001', 4, 4, 400, 'Lumaniag , Lian Batangas', 0, '2025-01-27 11:52:28', '2025-01-27 11:52:28'),
(7, '202501-00002', 4, 4, 200, 'Lumaniag , Lian Batangas', 0, '2025-01-27 11:57:11', '2025-01-27 11:57:11');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(30) NOT NULL,
  `vendor_id` int(30) DEFAULT NULL,
  `category_id` int(30) DEFAULT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL DEFAULT 0,
  `image_path` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `vendor_id`, `category_id`, `name`, `description`, `price`, `image_path`, `status`, `delete_flag`, `date_created`, `date_updated`, `quantity`) VALUES
(10, 4, 11, 'Interior', '&lt;p&gt;Model : Yamaha Mio 125\r\n&lt;/p&gt;&lt;p&gt;Size : 70/80\r\n&lt;/p&gt;&lt;p&gt;Other Details&lt;/p&gt;&lt;p&gt;TEst infor:&lt;/p&gt;&lt;p&gt;etc,.&lt;/p&gt;', 450, 'uploads/products/10.png?v=1734049937', 1, 0, '2024-12-13 08:32:17', '2025-01-27 11:51:19', 0),
(11, 5, 14, 'Spark plugs', 'Ignite the air-fuel mixture in the combustion chamber to start the engine.', 300, 'uploads/products/11.png?v=1736743073', 1, 0, '2024-12-13 11:04:17', '2025-01-13 12:37:53', 0),
(12, 5, 14, 'Cylinder Head', '&lt;p&gt;&lt;span style=&quot;color: rgb(31, 31, 31); font-family: &quot;Google Sans&quot;, Roboto, Arial, sans-serif; font-size: 14px; font-variant-ligatures: no-contextual; white-space-collapse: preserve; background-color: rgb(242, 242, 242);&quot;&gt;Houses the combustion chamber and valves, where air-fuel mixture ignites to power the engine.&lt;/span&gt;&lt;/p&gt;', 200, 'uploads/products/12.png?v=1736743229', 1, 1, '2024-12-13 12:01:56', '2025-01-13 12:40:43', 0),
(13, 5, 12, 'ClutchPlates', '&lt;p&gt;Engage and disengage power from the engine to the gearbox&lt;/p&gt;', 300, 'uploads/products/13.png?v=1736742990', 1, 1, '2024-12-13 12:40:13', '2025-01-13 12:40:49', 0),
(14, 5, 14, 'Pistons', '&lt;p&gt;&nbsp;Move up and down inside the cylinder to convert fuel combustion into mechanical energy.&lt;/p&gt;', 300, 'uploads/products/14.png?v=1736743090', 1, 0, '2024-12-13 12:47:45', '2025-01-13 12:38:10', 0),
(15, 5, 14, 'Sprockets', '&lt;p&gt;Toothed wheels that drive the chain and connect the engine to the wheels.&lt;/p&gt;', 300, 'uploads/products/15.png?v=1736743024', 1, 0, '2024-12-13 13:05:28', '2025-01-13 12:37:04', 0),
(16, 4, 16, 'Clutch Plates', '&lt;p&gt;Engage and disengage power from the engine to the gearbox.&lt;/p&gt;', 300, NULL, 1, 1, '2024-12-13 13:31:51', '2024-12-13 13:37:43', 0),
(17, 4, 16, 'Sprockets', '&lt;p&gt;&amp;nbsp;Toothed wheels that drive the chain and connect the engine to the wheels.&lt;/p&gt;', 300, NULL, 1, 1, '2024-12-13 13:34:03', '2024-12-13 13:37:54', 0),
(18, 5, 14, 'Clutch Plate', '&lt;p&gt;-&lt;/p&gt;', 300, 'uploads/products/18.png?v=1736743735', 1, 0, '2025-01-13 12:48:55', '2025-01-13 12:48:55', 0),
(19, 5, 14, 'Connecting Rod', '&lt;p&gt;-&lt;/p&gt;', 247, 'uploads/products/19.png?v=1736743935', 1, 0, '2025-01-13 12:52:15', '2025-01-13 12:52:34', 0),
(20, 4, 16, 'test', '&lt;p&gt;teast&lt;/p&gt;', 100, 'uploads/products/20.png?v=1736745610', 1, 0, '2025-01-13 13:20:10', '2025-01-13 13:20:10', 0),
(21, 4, 16, 'test2', '&lt;p&gt;test&lt;/p&gt;', 200, 'uploads/products/21.png?v=1736745662', 1, 0, '2025-01-13 13:21:02', '2025-01-13 13:21:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shop_type_list`
--

CREATE TABLE `shop_type_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_type_list`
--

INSERT INTO `shop_type_list` (`id`, `name`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(6, 'Vulcanizing', 1, 0, '2024-12-13 08:26:55', '2024-12-13 10:12:14'),
(7, 'Maintenance', 1, 0, '2024-12-13 08:27:16', '2024-12-13 13:08:52'),
(8, 'Motorparts and Accessories', 1, 1, '2024-12-13 09:43:03', '2024-12-13 13:07:41'),
(9, 'Repair', 1, 0, '2024-12-13 10:11:54', '2024-12-13 10:12:46'),
(10, 'Motorcycle Parts and Accessories', 1, 1, '2024-12-13 10:27:10', '2024-12-13 13:07:35');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'MotoAssist'),
(6, 'short_name', 'Moto Assist AI'),
(11, 'logo', 'uploads/logo-1734047574.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1734047796.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', 'Admin', 'Admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatar-1.png?v=1644472635', NULL, 1, '2021-01-20 14:02:37', '2025-01-26 08:45:21');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_list`
--

CREATE TABLE `vendor_list` (
  `id` int(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `shop_type_id` int(30) NOT NULL,
  `shop_name` text NOT NULL,
  `shop_owner` text NOT NULL,
  `contact` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `lat` varchar(250) NOT NULL,
  `lng` varchar(250) NOT NULL,
  `marker_color` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_list`
--

INSERT INTO `vendor_list` (`id`, `code`, `shop_type_id`, `shop_name`, `shop_owner`, `contact`, `username`, `password`, `avatar`, `status`, `delete_flag`, `date_created`, `date_updated`, `lat`, `lng`, `marker_color`, `address`) VALUES
(4, '202412-00001', 9, 'JMS Motorcycle Repair Shop', 'Jomar Sanchez', '09129501162', 'emjhay', '2ff64466632e96bfa176d942bf04aad4', 'uploads/vendors/4.png?v=1734049722', 1, 0, '2024-12-13 08:28:42', '2025-01-27 12:00:29', '14.037695866191333', '120.65108394445143', '#1706f9', 'Barangay 2 Lian, Batangas'),
(5, '202412-00002', 10, 'Albert Motorcycle Parts and Accessories', 'Albert ', '09102837924', 'albert', '73503e6f479c632ebfebc6e58a3cd335', NULL, 1, 0, '2024-12-13 10:34:16', '2025-01-27 09:34:59', '14.040370421608737', '120.65138590870667', '#05fa2e', ''),
(6, '202412-00003', 9, 'Larry\'s Motorcycle Parts Shop', 'Natalia G. Cebeda', '09182723866', 'larry', 'd8a427f5d61c5fe57ae869281bf3b7c9', NULL, 1, 0, '2024-12-16 15:06:38', '2025-01-27 09:36:36', '14.038923661619782', '120.65388572750855', '#f9f001', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_list`
--
ALTER TABLE `cart_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `chatbot`
--
ALTER TABLE `chatbot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_list`
--
ALTER TABLE `client_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `markers`
--
ALTER TABLE `markers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `category_id` (`category_id`) USING BTREE;

--
-- Indexes for table `shop_type_list`
--
ALTER TABLE `shop_type_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_list`
--
ALTER TABLE `vendor_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_type_id` (`shop_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_list`
--
ALTER TABLE `cart_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `chatbot`
--
ALTER TABLE `chatbot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `client_list`
--
ALTER TABLE `client_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `markers`
--
ALTER TABLE `markers`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `shop_type_list`
--
ALTER TABLE `shop_type_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vendor_list`
--
ALTER TABLE `vendor_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_list`
--
ALTER TABLE `cart_list`
  ADD CONSTRAINT `cart_list_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_list_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_list`
--
ALTER TABLE `category_list`
  ADD CONSTRAINT `category_list_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_list`
--
ALTER TABLE `order_list`
  ADD CONSTRAINT `order_list_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_list_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendor_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_list`
--
ALTER TABLE `product_list`
  ADD CONSTRAINT `product_list_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor_list` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_list_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_list`
--
ALTER TABLE `vendor_list`
  ADD CONSTRAINT `vendor_list_ibfk_1` FOREIGN KEY (`shop_type_id`) REFERENCES `shop_type_list` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
