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
-- Table structure for table `noticias`
--

DROP TABLE IF EXISTS `noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `noticia` text NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `autor` int(11) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `visualizacoes` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `autor` (`autor`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_data` (`data`),
  FULLTEXT KEY `idx_fulltext` (`titulo`,`noticia`),
  CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `noticias`
--

LOCK TABLES `noticias` WRITE;
/*!40000 ALTER TABLE `noticias` DISABLE KEYS */;
INSERT INTO `noticias` VALUES (1,'Novo álbum do Central Ceee chega com colaborações internacionais','O artista underground Central Ceee acaba de lançar seu aguardado álbum \"Ruas do Mundo\", com participações de nomes internacionais da cena trap. O trabalho traz 12 faixas inéditas e já está disponível nas principais plataformas de streaming.','Música','2025-07-03 20:31:06',1,'central-ceee-album.jpg',13),(2,'Exposição de grafite transforma centro da cidade em galeria a céu aberto','A mostra \"Paredes que Falam\" reúne trabalhos de 30 artistas urbanos em diversos prédios do centro. A exposição ficará disponível por 3 meses e conta com um aplicativo de realidade aumentada.','Arte','2025-07-03 20:31:06',1,'exposicao-grafite.jpg',6),(3,'Festival Synawrld anuncia line-up com grandes nomes do hip-hop nacional','O festival que acontece no próximo mês divulgou sua programação completa, com shows de Emicida, Racionais MC\'s e novos talentos da cena. O evento terá também workshops e batalhas de rap.','Eventos','2025-07-03 20:31:06',1,'festival-synawrld.jpg',1),(4,'Documentário sobre cultura de rua estreia no streaming','O filme \"Vozes do Asfalto\", que retrata a evolução da cultura urbana nas últimas décadas, já está disponível nas principais plataformas. A produção contou com entrevistas exclusivas e imagens raras.','Cinema','2025-07-03 20:31:06',1,'documentario-rua.jpg',1),(5,'Artista local transforma muros em obras de arte interativas','Usando tecnologia e arte tradicional, o coletivo \"Pixels Urbanos\" está criando murais que reagem ao movimento dos espectadores. A iniciativa já chamou atenção de curadores internacionais.','Street Art','2025-07-03 20:31:06',1,'arte-interativa.jpg',1),(8,'gremio','aadaadaadaad vaadaad \r\n  aadaadaadaad vaadaad  aadaadaadaad vaadaad  aadaadaadaad vaadaad  aadaadaadaad vaadaad  aadaadaadaad vaadaad  aadaadaadaad vaadaad  aadaadaadaad vaadaad   aadaadaadaad vaadaad','Arte','2025-07-07 22:15:12',3,'syn_686c7120bdc2b.jpg',5),(9,'gremio','sdadaaddada','Arte','2025-07-09 19:49:03',3,'syn_686ef1df0c0e4.jpg',1),(10,'Matue lança Vampiro','Matue lança a música tão aguardada vampiro','Música','2025-07-09 20:23:56',3,'syn_686efa0c6eec4.jpg',8);
/*!40000 ALTER TABLE `noticias` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-09 22:01:25
