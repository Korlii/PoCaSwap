-- PocaSwap Unified Database
-- Generated for deployment
-- Compatible with InfinityFree / MariaDB

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

-- =========================
-- USER TABLES
-- =========================

CREATE TABLE `info` (
  `User_ID` int(36) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `PhoneNumber` varchar(11) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `PhoneNumber` (`PhoneNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `info` VALUES
(1,'ricartes','Cedric','Dungca','09813320360','$2y$10$JITH8iQeAPC.z0/T.PNFDefqsLQo.xd/YC2SGVejEe9GXlT.S/5EG','admin','2025-03-28 00:22:56'),
(2,'chewei','Meg','Esguerra','09369537233','$2y$10$T0xfb9eTT/GzXtJ7X37LXef4JUpkoR3JOgJ1eesHGWIWBv56QWX62','admin','2025-03-28 00:22:56'),
(3,'Korlishells','Adrian','Curley','09605644843','$2y$10$KRINDvCQ/hQY.6Jy2AFwLOZ2s0zyWjt/2q8n1OyuCxgOtOawS9nTe','admin','2025-03-28 00:24:49'),
(4,'seowfie','Sofia','Sarmiento','09229495255','$2y$10$TpBLrrvAGG2/RngHk/m1/uSQUoIo99FTCcusrPCH5yg.3Nc1UMUqu','admin','2025-03-28 00:24:49'),
(5,'coc0o.09','Coco','Dungca','09218627366','$2y$10$TuNZxkI6tx2cJlC08oM5oun2poHNtYdnXhBmjntggrQ6MafRS.IGK','user','2025-03-28 00:29:24');

CREATE TABLE `cart` (
  `User_ID` int(11) NOT NULL,
  `Product_ID` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  UNIQUE KEY (`User_ID`,`Product_ID`),
  FOREIGN KEY (`User_ID`) REFERENCES `info` (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- PRODUCT TABLES
-- =========================

CREATE TABLE `products` (
  `Product_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Product_Image` varchar(255) NOT NULL,
  `Photocard_Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Tradable` tinyint(1) NOT NULL,
  PRIMARY KEY (`Product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` VALUES
(1,'Yunah.jpg','Yunah SUPER REAL ME POB M2 Debut Show','ILLIT Yunah SUPER REAL ME M2 Debut Show',400,5,1),
(2,'Moka.jpg','Moka SUPER REAL ME POB M2 Debut Show','ILLIT Moka SUPER REAL ME M2 Debut Show',1600,2,0),
(3,'Minju.jpg','Minju SUPER REAL ME POB M2 Debut Show','ILLIT Minju SUPER REAL ME M2 Debut Show',480,3,1),
(4,'Iroha.jpg','Iroha SUPER REAL ME POB M2 Debut Show','ILLIT Iroha SUPER REAL ME M2 Debut Show',640,4,0),
(5,'Wonhee.jpg','Wonhee SUPER REAL ME POB M2 Debut Show','ILLIT Wonhee SUPER REAL ME M2 Debut Show',640,2,1),
(6,'chaewonhoodie.jpg','Kim Chaewon Hoodie','IZ*ONE Oneiric Diary 3D Version',2000,1,0),
(7,'youngeunheart.jpg','Seo Youngeun Fingerheart','KEP1ER First Impact Connect 1 Ver',100,7,1),
(8,'chaehyunpisngi.jpg','Kim Chaehyun Pisngi','KEP1ER First Impact Connect - Ver',180,5,1),
(9,'hanschool.jpg','Han Jisung School Unif','STRAY KIDS GO Live White Back Member Ver',300,2,1),
(10,'flowerwon.jpg','Yang Jungwon Flowerwon','ENHYPEN Dimension Answer Yet Ver',400,2,1),
(11,'leeseomandu.jpg','Lee Hyunseo Leeseo Mandu','IVE Eleven WithDrama PoB',850,1,0),
(12,'isaprincess.jpg','Lee Chaeyoung Isa Princess','STAYC Stereotype Type B',350,2,1),
(13,'AhyeonDRIP.png','Ahyeon DRIP FOREVER ALBUM','BABYMONSTER Ahyeon DRIP FOREVER',450,4,1),
(14,'GiselleMYWORLD.png','Giselle MY WORLD ALBUM','aespa Giselle My World',400,2,0),
(15,'KarinaMYWORLD.png','Karina MY WORLD ALBUM','aespa Karina My World',600,4,0),
(16,'Jeonghanhawak.png','Jeonghan SVT RIGHT HERE','SVT Jeonghan SVT RIGHT HERE DEAR VER.A',300,2,1),
(17,'HanniHowSweet.png','Hanni HOW SWEET WEVERSE POB','NJZ HANNI HOW SWEET WEVERSE POB',250,6,1),
(18,'MingyuTadashi.png','Mingyu Tadashi IN THE SOOP','SVT Mingyu IN THE SOOP',500,3,1),
(19,'NingningArmageddon.png','Ningning ARMAGEDDON','aespa Ningning ARMAGEDDON SM TOWN',850,3,0),
(20,'WonyALIVE.jpg','Wonyoung Alive Japan 2nd EP','IVE Wonyoung Alive Japan 2nd EP',1200,2,1);

-- =========================
-- ORDER & TRADE TABLES
-- =========================

CREATE TABLE `orders` (
  `Order_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) NOT NULL,
  `Product_ID` int(10) UNSIGNED NOT NULL,
  `num_ordered` int(9) NOT NULL,
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Order_ID`),
  FOREIGN KEY (`User_ID`) REFERENCES `info` (`User_ID`),
  FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `order_details` (
  `OrderDetails_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Order_ID` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `pickup_location` enum(
    'Holy Angel University - Main Gate',
    'SM City Clark - Main Entrance',
    'SM Telebastagan - Food Court',
    'Marquee Mall - J.CO Entrance'
  ) NOT NULL,
  `mode_of_payment` enum('Cash on Meetup','GCASH') NOT NULL,
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp(),
  `Status` enum('Pending','Ongoing','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`OrderDetails_ID`),
  FOREIGN KEY (`Order_ID`) REFERENCES `orders` (`Order_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `trade` (
  `Trade_ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `Product_ID` int(10) UNSIGNED NOT NULL,
  `Trade_Name` varchar(255) NOT NULL,
  `Trade_Description` text NOT NULL,
  `Trade_Offer` varchar(255) NOT NULL,
  `Trade_Status` enum('Pending','Ongoing','Completed','Declined') NOT NULL,
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Trade_ID`),
  FOREIGN KEY (`username`) REFERENCES `info` (`username`),
  FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
