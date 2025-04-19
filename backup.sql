-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: VetManagement
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Appointments`
--

DROP TABLE IF EXISTS `Appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Appointments` (
  `Appt_ID` int NOT NULL AUTO_INCREMENT,
  `Pet_ID` int DEFAULT NULL,
  `Vet_ID` int DEFAULT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Reason` text,
  `Status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  PRIMARY KEY (`Appt_ID`),
  KEY `Pet_ID` (`Pet_ID`),
  KEY `Vet_ID` (`Vet_ID`),
  CONSTRAINT `Appointments_ibfk_1` FOREIGN KEY (`Pet_ID`) REFERENCES `Pets` (`Pet_ID`) ON DELETE CASCADE,
  CONSTRAINT `Appointments_ibfk_2` FOREIGN KEY (`Vet_ID`) REFERENCES `Vets` (`Vet_ID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Appointments`
--

LOCK TABLES `Appointments` WRITE;
/*!40000 ALTER TABLE `Appointments` DISABLE KEYS */;
INSERT INTO `Appointments` VALUES (1,2,3,'2025-04-20','15:00:00','Castraction','Scheduled'),(2,9,19,'2025-04-25','13:00:00','Eye surgery','Scheduled'),(3,45,20,'2025-04-06','16:30:00','Skin problems(Rash)','Scheduled');
/*!40000 ALTER TABLE `Appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Inventory`
--

DROP TABLE IF EXISTS `Inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Inventory` (
  `Product_ID` int NOT NULL,
  `Supplier` varchar(100) DEFAULT NULL,
  `Stock_Level` int NOT NULL,
  PRIMARY KEY (`Product_ID`),
  CONSTRAINT `Inventory_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `Products` (`Product_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Inventory`
--

LOCK TABLES `Inventory` WRITE;
/*!40000 ALTER TABLE `Inventory` DISABLE KEYS */;
INSERT INTO `Inventory` VALUES (1,'Pet Supplies Ltd.',50),(2,'Best Pet Food Co.',40),(3,'Animal Care Products',30);
/*!40000 ALTER TABLE `Inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Medical_Records`
--

DROP TABLE IF EXISTS `Medical_Records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Medical_Records` (
  `Record_ID` int NOT NULL AUTO_INCREMENT,
  `Pet_ID` int DEFAULT NULL,
  `Treatment` text NOT NULL,
  `Prescription` text,
  `Notes` text,
  PRIMARY KEY (`Record_ID`),
  KEY `Pet_ID` (`Pet_ID`),
  CONSTRAINT `Medical_Records_ibfk_1` FOREIGN KEY (`Pet_ID`) REFERENCES `Pets` (`Pet_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Medical_Records`
--

LOCK TABLES `Medical_Records` WRITE;
/*!40000 ALTER TABLE `Medical_Records` DISABLE KEYS */;
INSERT INTO `Medical_Records` VALUES (2,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(4,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(6,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(8,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(10,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(12,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(14,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(16,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(18,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(20,2,'Skin treatment','Ointment','Check progress in 2 weeks'),(21,2,'Skin treatment','Ointment','Check progress in 2 weeks');
/*!40000 ALTER TABLE `Medical_Records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Order_Details`
--

DROP TABLE IF EXISTS `Order_Details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Order_Details` (
  `Order_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `Quantity` int NOT NULL,
  PRIMARY KEY (`Order_ID`,`Product_ID`),
  KEY `Product_ID` (`Product_ID`),
  CONSTRAINT `Order_Details_ibfk_1` FOREIGN KEY (`Order_ID`) REFERENCES `Orders` (`Order_ID`) ON DELETE CASCADE,
  CONSTRAINT `Order_Details_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `Products` (`Product_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Order_Details`
--

LOCK TABLES `Order_Details` WRITE;
/*!40000 ALTER TABLE `Order_Details` DISABLE KEYS */;
INSERT INTO `Order_Details` VALUES (1,1,2),(2,3,1);
/*!40000 ALTER TABLE `Order_Details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Orders`
--

DROP TABLE IF EXISTS `Orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Orders` (
  `Order_ID` int NOT NULL AUTO_INCREMENT,
  `Customer_ID` int DEFAULT NULL,
  `Date` date NOT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`Order_ID`),
  KEY `Customer_ID` (`Customer_ID`),
  CONSTRAINT `Orders_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `Owners` (`Owner_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Orders`
--

LOCK TABLES `Orders` WRITE;
/*!40000 ALTER TABLE `Orders` DISABLE KEYS */;
INSERT INTO `Orders` VALUES (1,1,'2025-04-05',30.00),(2,2,'2025-04-06',20.00),(3,1,'2025-04-05',30.00),(4,2,'2025-04-06',20.00),(5,1,'2025-04-05',30.00),(6,2,'2025-04-06',20.00),(7,1,'2025-04-05',30.00),(8,2,'2025-04-06',20.00),(9,1,'2025-04-05',30.00),(10,2,'2025-04-06',20.00),(11,1,'2025-04-05',30.00),(12,2,'2025-04-06',20.00),(13,1,'2025-04-05',30.00),(14,2,'2025-04-06',20.00),(15,1,'2025-04-05',30.00),(16,2,'2025-04-06',20.00),(17,1,'2025-04-05',30.00),(18,2,'2025-04-06',20.00),(19,1,'2025-04-05',30.00),(20,2,'2025-04-06',20.00),(21,1,'2025-04-05',30.00),(22,2,'2025-04-06',20.00);
/*!40000 ALTER TABLE `Orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Owners`
--

DROP TABLE IF EXISTS `Owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Owners` (
  `Owner_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Contact` varchar(20) NOT NULL,
  `Email` varchar(100) NOT NULL,
  PRIMARY KEY (`Owner_ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Owners`
--

LOCK TABLES `Owners` WRITE;
/*!40000 ALTER TABLE `Owners` DISABLE KEYS */;
INSERT INTO `Owners` VALUES (1,'Kabelo Thesele','+266 6271-1525','kabelothesele81@mail.com'),(2,'Boitumelo Jase','+266 6850-9719','boityjase@mail.com'),(3,'James Mafohla','+266 6357-6902','jamesmafohla@mail.com'),(34,'Napo J Qheku','+266 6851-7747','naponefsqheku@gmail.com'),(35,'Hlompho Ntoko','+266 6340-6411','blackball@gmail.com'),(36,'John Doe','123-456-7890','johndoe@mail.com'),(37,'Jane Smith','987-654-3210','janesmith@mail.com'),(38,'Michael Brown','555-123-4567','michaelbrown@mail.com');
/*!40000 ALTER TABLE `Owners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PasswordResets`
--

DROP TABLE IF EXISTS `PasswordResets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PasswordResets` (
  `reset_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`reset_id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `PasswordResets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PasswordResets`
--

LOCK TABLES `PasswordResets` WRITE;
/*!40000 ALTER TABLE `PasswordResets` DISABLE KEYS */;
/*!40000 ALTER TABLE `PasswordResets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Pets`
--

DROP TABLE IF EXISTS `Pets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Pets` (
  `Pet_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Species` varchar(50) NOT NULL,
  `Breed` varchar(50) DEFAULT NULL,
  `Age` int DEFAULT NULL,
  `Owner_ID` int DEFAULT NULL,
  PRIMARY KEY (`Pet_ID`),
  KEY `Owner_ID` (`Owner_ID`),
  CONSTRAINT `Pets_ibfk_1` FOREIGN KEY (`Owner_ID`) REFERENCES `Owners` (`Owner_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Pets`
--

LOCK TABLES `Pets` WRITE;
/*!40000 ALTER TABLE `Pets` DISABLE KEYS */;
INSERT INTO `Pets` VALUES (2,'Bella','Cat','Siamese',2,2),(3,'Rocky','Dog','Labrador',5,1),(4,'Luna','Rabbit','Angora',1,3),(5,'Max','Dog','Beagle',4,1),(6,'Bella','Cat','Siamese',2,2),(7,'Rocky','Dog','Labrador',5,1),(8,'Luna','Rabbit','Angora',1,3),(9,'Max','Dog','Beagle',4,1),(10,'Bella','Cat','Siamese',2,2),(11,'Rocky','Dog','Labrador',5,1),(12,'Luna','Rabbit','Angora',1,3),(13,'Max','Dog','Beagle',4,1),(14,'Bella','Cat','Siamese',2,2),(15,'Rocky','Dog','Labrador',5,1),(16,'Luna','Rabbit','Angora',1,3),(17,'Max','Dog','Beagle',4,1),(18,'Bella','Cat','Siamese',2,2),(19,'Rocky','Dog','Labrador',5,1),(20,'Luna','Rabbit','Angora',1,3),(21,'Max','Dog','Beagle',4,1),(22,'Bella','Cat','Siamese',2,2),(23,'Rocky','Dog','Labrador',5,1),(24,'Luna','Rabbit','Angora',1,3),(25,'Max','Dog','Beagle',4,1),(26,'Bella','Cat','Siamese',2,2),(27,'Rocky','Dog','Labrador',5,1),(28,'Luna','Rabbit','Angora',1,3),(29,'Max','Dog','Beagle',4,1),(30,'Bella','Cat','Siamese',2,2),(31,'Rocky','Dog','Labrador',5,1),(33,'Max','Dog','Beagle',4,1),(34,'Bella','Cat','Siamese',2,2),(35,'Rocky','Dog','Labrador',5,1),(37,'Max','Dog','Beagle',4,1),(38,'Bella','Cat','Siamese',2,2),(39,'Rocky','Dog','Labrador',5,1),(41,'Thesele','Dog','Lekesi',6,1),(43,'Bella','Cat','Siamese',2,2),(44,'Rocky','Dog','Labrador',5,1),(45,'Luna','Rabbit','Angora',1,3);
/*!40000 ALTER TABLE `Pets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Products`
--

DROP TABLE IF EXISTS `Products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Products` (
  `Product_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Stock` int NOT NULL,
  PRIMARY KEY (`Product_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Products`
--

LOCK TABLES `Products` WRITE;
/*!40000 ALTER TABLE `Products` DISABLE KEYS */;
INSERT INTO `Products` VALUES (1,'Dog Food','Food',15.99,50),(2,'Cat Food','Food',12.99,40),(3,'Pet Shampoo','Hygiene',8.99,30),(4,'Dog Food','Food',15.99,50),(5,'Cat Food','Food',12.99,40),(6,'Pet Shampoo','Hygiene',8.99,30),(7,'Dog Food','Food',15.99,50),(8,'Cat Food','Food',12.99,40),(9,'Pet Shampoo','Hygiene',8.99,30),(10,'Dog Food','Food',15.99,50),(11,'Cat Food','Food',12.99,40),(12,'Pet Shampoo','Hygiene',8.99,30),(13,'Dog Food','Food',15.99,50),(14,'Cat Food','Food',12.99,40),(15,'Pet Shampoo','Hygiene',8.99,30),(16,'Dog Food','Food',15.99,50),(17,'Cat Food','Food',12.99,40),(18,'Pet Shampoo','Hygiene',8.99,30),(19,'Dog Food','Food',15.99,50),(20,'Cat Food','Food',12.99,40),(21,'Pet Shampoo','Hygiene',8.99,30),(22,'Dog Food','Food',15.99,50),(23,'Cat Food','Food',12.99,40),(24,'Pet Shampoo','Hygiene',8.99,30),(25,'Dog Food','Food',15.99,50),(26,'Cat Food','Food',12.99,40),(27,'Pet Shampoo','Hygiene',8.99,30),(28,'Dog Food','Food',15.99,50),(29,'Cat Food','Food',12.99,40),(30,'Pet Shampoo','Hygiene',8.99,30),(31,'Dog Food','Food',15.99,50),(32,'Cat Food','Food',12.99,40),(33,'Pet Shampoo','Hygiene',8.99,30);
/*!40000 ALTER TABLE `Products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Sessions`
--

DROP TABLE IF EXISTS `Sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Sessions` (
  `session_id` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Sessions`
--

LOCK TABLES `Sessions` WRITE;
/*!40000 ALTER TABLE `Sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `Sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','vet','staff','client') NOT NULL,
  `owner_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `owner_id` (`owner_id`),
  CONSTRAINT `Users_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `Owners` (`Owner_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'Kabelo Thesele','$2y$10$y6FijFKZy2t8XgQQ3Fyy5uQk7HmFqSI/2dEVcNAEiJ.zZtc4Qa.Ze','kabelothesele81@mail.com','client',1,'2025-04-19 17:30:15'),(2,'Napo Qheku','68517747','naponefsqheku@gmail.com','admin',NULL,'2025-04-19 18:05:29');
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Vets`
--

DROP TABLE IF EXISTS `Vets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Vets` (
  `Vet_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Specialization` varchar(100) DEFAULT NULL,
  `Contact` varchar(20) NOT NULL,
  PRIMARY KEY (`Vet_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Vets`
--

LOCK TABLES `Vets` WRITE;
/*!40000 ALTER TABLE `Vets` DISABLE KEYS */;
INSERT INTO `Vets` VALUES (1,'Dr. Emily Carter','Surgery','111-222-3333'),(2,'Dr. James Wilson','Dermatology','444-555-6666'),(3,'Dr. Emily Carter','Surgery','111-222-3333'),(4,'Dr. James Wilson','Dermatology','444-555-6666'),(5,'Dr. Emily Carter','Surgery','111-222-3333'),(6,'Dr. James Wilson','Dermatology','444-555-6666'),(7,'Dr. Emily Carter','Surgery','111-222-3333'),(8,'Dr. James Wilson','Dermatology','444-555-6666'),(9,'Dr. Emily Carter','Surgery','111-222-3333'),(10,'Dr. James Wilson','Dermatology','444-555-6666'),(11,'Dr. Emily Carter','Surgery','111-222-3333'),(12,'Dr. James Wilson','Dermatology','444-555-6666'),(13,'Dr. Emily Carter','Surgery','111-222-3333'),(14,'Dr. James Wilson','Dermatology','444-555-6666'),(15,'Dr. Emily Carter','Surgery','111-222-3333'),(16,'Dr. James Wilson','Dermatology','444-555-6666'),(17,'Dr. Emily Carter','Surgery','111-222-3333'),(18,'Dr. James Wilson','Dermatology','444-555-6666'),(19,'Dr. Emily Carter','Surgery','111-222-3333'),(20,'Dr. James Wilson','Dermatology','444-555-6666'),(21,'Dr. Emily Carter','Surgery','111-222-3333'),(22,'Dr. James Wilson','Dermatology','444-555-6666');
/*!40000 ALTER TABLE `Vets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-19 20:29:58
