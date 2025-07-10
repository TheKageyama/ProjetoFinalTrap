-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: synawrld_news
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `anuncios`
--

DROP TABLE IF EXISTS `anuncios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anuncios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `texto` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `destaque` tinyint(1) DEFAULT 0,
  `popup` tinyint(1) DEFAULT 0,
  `valor` decimal(10,2) DEFAULT 0.00,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ativo` (`ativo`),
  KEY `idx_destaque` (`destaque`),
  KEY `idx_popup` (`popup`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anuncios`
--

LOCK TABLES `anuncios` WRITE;
/*!40000 ALTER TABLE `anuncios` DISABLE KEYS */;
INSERT INTO `anuncios` VALUES (9,'333','syn_686ef94f521b6.jpg','https://open.spotify.com/playlist/0b2iTZyLr1sNOFyoKlVRfR','333 matue',1,1,1,1000.00,'2025-07-09 20:20:47'),(10,'333','','https://open.spotify.com/playlist/0b2iTZyLr1sNOFyoKlVRfR','333 matue',1,1,1,1000.00,'2025-07-09 20:21:14'),(11,'I UIHIUH UIHUHUI IUIL',NULL,'https://open.spotify.com/playlist/0b2iTZyLr1sNOFyoKlVRfR','MFSIAPKFPASOPFKASOKFOPASKOPFKASOPFOPASFOKASOFKASOKFP',1,0,1,22.00,'2025-07-09 21:19:29');
/*!40000 ALTER TABLE `anuncios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-09 22:01:24
