-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: web_konference
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  `authors` varchar(254) NOT NULL,
  `abstract` text NOT NULL,
  `file` varchar(254) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `published` tinyint(1) NOT NULL,
  `rejected` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article`
--

LOCK TABLES `article` WRITE;
/*!40000 ALTER TABLE `article` DISABLE KEYS */;
INSERT INTO `article` VALUES (7,'Lorem','Jon Doe','<p>&nbsp;<strong>Lorem ipsum dolor sit amet</strong>, consectetur adipiscing elit. Nullam eros \r\nest, ultricies ut ante sit amet, finibus malesuada ex. Curabitur ex \r\norci, pretium ut enim tempus, eleifend commodo neque. Fusce lorem ex, \r\nmalesuada sit amet dictum at, bibendum in ex. Aliquam sit amet nisl ut \r\nvelit sollicitudin pellentesque. Nulla a felis a est ultricies viverra \r\nut a mi. Nam vitae purus vitae lorem varius bibendum vel ac sem. \r\nVestibulum et sem tortor. Nullam sit amet tempus sapien. Nullam sed \r\ntempor justo, sit amet porta metus.&nbsp;</p>','5a7178f5a41a53.58509654.pdf',14,1,0),(8,'Curabitur aliquet','Jon Doe, Johny Quin','<p>&nbsp;Curabitur aliquet erat neque, laoreet dictum nunc volutpat sed. Quisque \r\nsed purus commodo, tempus libero id, laoreet elit. Sed convallis dui nec\r\n nulla laoreet semper. Nulla tincidunt, dui vitae ornare eleifend, nunc \r\ndolor porttitor tellus, in bibendum odio felis eu lectus. Cras elementum\r\n enim erat, ac vestibulum sapien facilisis non. Nulla porta diam lectus,\r\n non elementum purus malesuada nec. Sed convallis erat non magna semper,\r\n aliquet imperdiet risus egestas. Nulla consectetur, lorem in varius \r\npharetra, diam nisi rhoncus urna, a luctus ipsum ligula sit amet nibh. \r\nUt venenatis magna vitae urna volutpat blandit sed ut enim. Sed commodo \r\nest nec metus rutrum elementum. Aenean commodo pellentesque nulla vel \r\nornare. Aenean dui turpis, finibus eget turpis ac, tincidunt iaculis \r\nest. Pellentesque at bibendum mi. Ut nulla leo, vestibulum non tortor \r\nac, commodo imperdiet ex. Fusce placerat accumsan nunc, ultrices maximus\r\n diam finibus non. Suspendisse ut nulla et elit efficitur posuere.&nbsp;</p>','5a717927c8d9a0.91015267.pdf',14,0,1),(9,'Article','Tom Hanks','<p>&nbsp;Donec rhoncus fringilla velit, vitae hendrerit erat facilisis eget. \r\nVestibulum fringilla purus eros. Suspendisse semper vestibulum tortor, \r\nin dictum nisi sagittis a. Proin vitae ipsum pulvinar, laoreet velit at,\r\n pharetra quam. Nunc commodo tincidunt purus in semper. Vivamus ut leo \r\nvitae nulla feugiat imperdiet. Donec vitae aliquet justo. Aliquam erat \r\nvolutpat. Phasellus euismod eros tellus, id mollis mauris ultrices at. \r\nNulla eleifend id felis id dignissim. Vivamus malesuada pellentesque \r\norci, non ornare purus ornare eget. Quisque vulputate mollis nibh, ut \r\nvulputate eros dignissim eget. Sed justo arcu, congue sed tellus in, \r\nscelerisque facilisis velit. Integer a nisi ornare, volutpat lorem \r\nmattis, auctor magna.&nbsp;</p>','5a7179508a5762.31820638.pdf',15,0,0);
/*!40000 ALTER TABLE `article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `article_id` int(11) unsigned NOT NULL,
  `language` tinyint(3) unsigned DEFAULT NULL,
  `technical_quality` tinyint(3) unsigned DEFAULT NULL,
  `utility` tinyint(3) unsigned DEFAULT NULL,
  `audience_diversity` tinyint(3) unsigned DEFAULT NULL,
  `originality` tinyint(3) unsigned DEFAULT NULL,
  `sum` int(10) unsigned DEFAULT NULL,
  `suggestion` tinyint(3) unsigned DEFAULT NULL,
  `text` text,
  `done` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `article_rel` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `user_rel` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES (1,16,7,2,1,3,1,1,8,1,'Dobre',1),(2,17,7,1,3,1,2,3,10,2,'Slo by',1),(3,18,7,1,1,1,1,1,5,1,'Vyborne',1),(4,16,8,1,5,1,2,1,10,5,'Hrozne',1),(5,17,8,0,0,0,0,0,0,0,'',0),(6,18,8,0,0,0,0,0,0,0,'',0),(7,16,9,0,0,0,0,0,0,0,'',0),(8,17,9,0,0,0,0,0,0,0,'',0),(9,18,9,0,0,0,0,0,0,0,'',0);
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(127) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `rights` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','mbrunas.p@gmail.com','Martin','Bruna','66d30c93619758b4fd14eb88a7c7b9ff1d4c4b6e',7,0),(14,'author1','author@gmail.com','Jon','Doe','66d30c93619758b4fd14eb88a7c7b9ff1d4c4b6e',1,0),(15,'author2','a@gmail.com','Tom','Hanks','66d30c93619758b4fd14eb88a7c7b9ff1d4c4b6e',1,0),(16,'reviewer1','r@gmail.com','Duncan','Hettinger','66d30c93619758b4fd14eb88a7c7b9ff1d4c4b6e',3,0),(17,'reviewer2','r2@gmail.com','Opal','Weissnat','66d30c93619758b4fd14eb88a7c7b9ff1d4c4b6e',3,0),(18,'reviewer3','e@gmail.com','Shawn','Bradtke','66d30c93619758b4fd14eb88a7c7b9ff1d4c4b6e',3,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-31  9:11:30
