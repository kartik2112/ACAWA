-- MySQL dump 10.13  Distrib 5.5.45, for Win64 (x86)
--
-- Host: localhost    Database: dbACAWA
-- ------------------------------------------------------
-- Server version	5.5.45

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
-- Table structure for table `ac1`
--

DROP TABLE IF EXISTS `ac1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ac1` (
  `Roll_number` int(11) NOT NULL DEFAULT '0',
  `submittedOn` datetime DEFAULT NULL,
  `pref_1` int(11) DEFAULT NULL,
  `pref_2` int(11) DEFAULT NULL,
  `pref_3` int(11) DEFAULT NULL,
  `pref_4` int(11) DEFAULT NULL,
  `pref_5` int(11) DEFAULT NULL,
  `allottedChoice` int(11) DEFAULT NULL,
  PRIMARY KEY (`Roll_number`),
  KEY `pref_1` (`pref_1`),
  KEY `pref_2` (`pref_2`),
  KEY `pref_3` (`pref_3`),
  KEY `pref_4` (`pref_4`),
  KEY `pref_5` (`pref_5`),
  CONSTRAINT `ac1_ibfk_1` FOREIGN KEY (`Roll_number`) REFERENCES `user` (`Roll_number`),
  CONSTRAINT `ac1_ibfk_2` FOREIGN KEY (`pref_1`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac1_ibfk_3` FOREIGN KEY (`pref_2`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac1_ibfk_4` FOREIGN KEY (`pref_3`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac1_ibfk_5` FOREIGN KEY (`pref_4`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac1_ibfk_6` FOREIGN KEY (`pref_5`) REFERENCES `subject` (`Subj_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ac1`
--

LOCK TABLES `ac1` WRITE;
/*!40000 ALTER TABLE `ac1` DISABLE KEYS */;
/*!40000 ALTER TABLE `ac1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ac2`
--

DROP TABLE IF EXISTS `ac2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ac2` (
  `Roll_number` int(11) NOT NULL DEFAULT '0',
  `submittedOn` datetime DEFAULT NULL,
  `pref_1` int(11) DEFAULT NULL,
  `pref_2` int(11) DEFAULT NULL,
  `pref_3` int(11) DEFAULT NULL,
  `pref_4` int(11) DEFAULT NULL,
  `pref_5` int(11) DEFAULT NULL,
  `allottedChoice` int(11) DEFAULT NULL,
  PRIMARY KEY (`Roll_number`),
  KEY `pref_1` (`pref_1`),
  KEY `pref_2` (`pref_2`),
  KEY `pref_3` (`pref_3`),
  KEY `pref_4` (`pref_4`),
  KEY `pref_5` (`pref_5`),
  CONSTRAINT `ac2_ibfk_1` FOREIGN KEY (`Roll_number`) REFERENCES `user` (`Roll_number`),
  CONSTRAINT `ac2_ibfk_2` FOREIGN KEY (`pref_1`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac2_ibfk_3` FOREIGN KEY (`pref_2`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac2_ibfk_4` FOREIGN KEY (`pref_3`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac2_ibfk_5` FOREIGN KEY (`pref_4`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac2_ibfk_6` FOREIGN KEY (`pref_5`) REFERENCES `subject` (`Subj_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ac2`
--

LOCK TABLES `ac2` WRITE;
/*!40000 ALTER TABLE `ac2` DISABLE KEYS */;
/*!40000 ALTER TABLE `ac2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ac3`
--

DROP TABLE IF EXISTS `ac3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ac3` (
  `Roll_number` int(11) NOT NULL DEFAULT '0',
  `submittedOn` datetime DEFAULT NULL,
  `pref_1` int(11) DEFAULT NULL,
  `pref_2` int(11) DEFAULT NULL,
  `pref_3` int(11) DEFAULT NULL,
  `pref_4` int(11) DEFAULT NULL,
  `pref_5` int(11) DEFAULT NULL,
  `allottedChoice` int(11) DEFAULT NULL,
  PRIMARY KEY (`Roll_number`),
  KEY `pref_1` (`pref_1`),
  KEY `pref_2` (`pref_2`),
  KEY `pref_3` (`pref_3`),
  KEY `pref_4` (`pref_4`),
  KEY `pref_5` (`pref_5`),
  CONSTRAINT `ac3_ibfk_1` FOREIGN KEY (`Roll_number`) REFERENCES `user` (`Roll_number`),
  CONSTRAINT `ac3_ibfk_2` FOREIGN KEY (`pref_1`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac3_ibfk_3` FOREIGN KEY (`pref_2`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac3_ibfk_4` FOREIGN KEY (`pref_3`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac3_ibfk_5` FOREIGN KEY (`pref_4`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac3_ibfk_6` FOREIGN KEY (`pref_5`) REFERENCES `subject` (`Subj_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ac3`
--

LOCK TABLES `ac3` WRITE;
/*!40000 ALTER TABLE `ac3` DISABLE KEYS */;
/*!40000 ALTER TABLE `ac3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ac4`
--

DROP TABLE IF EXISTS `ac4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ac4` (
  `Roll_number` int(11) NOT NULL DEFAULT '0',
  `submittedOn` datetime DEFAULT NULL,
  `pref_1` int(11) DEFAULT NULL,
  `pref_2` int(11) DEFAULT NULL,
  `pref_3` int(11) DEFAULT NULL,
  `pref_4` int(11) DEFAULT NULL,
  `pref_5` int(11) DEFAULT NULL,
  `allottedChoice` int(11) DEFAULT NULL,
  PRIMARY KEY (`Roll_number`),
  KEY `pref_1` (`pref_1`),
  KEY `pref_2` (`pref_2`),
  KEY `pref_3` (`pref_3`),
  KEY `pref_4` (`pref_4`),
  KEY `pref_5` (`pref_5`),
  CONSTRAINT `ac4_ibfk_1` FOREIGN KEY (`Roll_number`) REFERENCES `user` (`Roll_number`),
  CONSTRAINT `ac4_ibfk_2` FOREIGN KEY (`pref_1`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac4_ibfk_3` FOREIGN KEY (`pref_2`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac4_ibfk_4` FOREIGN KEY (`pref_3`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac4_ibfk_5` FOREIGN KEY (`pref_4`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac4_ibfk_6` FOREIGN KEY (`pref_5`) REFERENCES `subject` (`Subj_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ac4`
--

LOCK TABLES `ac4` WRITE;
/*!40000 ALTER TABLE `ac4` DISABLE KEYS */;
/*!40000 ALTER TABLE `ac4` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ac5`
--

DROP TABLE IF EXISTS `ac5`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ac5` (
  `Roll_number` int(11) NOT NULL DEFAULT '0',
  `submittedOn` datetime DEFAULT NULL,
  `pref_1` int(11) DEFAULT NULL,
  `pref_2` int(11) DEFAULT NULL,
  `pref_3` int(11) DEFAULT NULL,
  `pref_4` int(11) DEFAULT NULL,
  `pref_5` int(11) DEFAULT NULL,
  `allottedChoice` int(11) DEFAULT NULL,
  PRIMARY KEY (`Roll_number`),
  KEY `pref_1` (`pref_1`),
  KEY `pref_2` (`pref_2`),
  KEY `pref_3` (`pref_3`),
  KEY `pref_4` (`pref_4`),
  KEY `pref_5` (`pref_5`),
  CONSTRAINT `ac5_ibfk_1` FOREIGN KEY (`Roll_number`) REFERENCES `user` (`Roll_number`),
  CONSTRAINT `ac5_ibfk_2` FOREIGN KEY (`pref_1`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac5_ibfk_3` FOREIGN KEY (`pref_2`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac5_ibfk_4` FOREIGN KEY (`pref_3`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac5_ibfk_5` FOREIGN KEY (`pref_4`) REFERENCES `subject` (`Subj_ID`),
  CONSTRAINT `ac5_ibfk_6` FOREIGN KEY (`pref_5`) REFERENCES `subject` (`Subj_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ac5`
--

LOCK TABLES `ac5` WRITE;
/*!40000 ALTER TABLE `ac5` DISABLE KEYS */;
/*!40000 ALTER TABLE `ac5` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject` (
  `Subj_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject_Name` varchar(45) DEFAULT NULL,
  `Sem` int(11) DEFAULT NULL,
  `File_Link` varchar(100) DEFAULT NULL,
  `Capacity` int(11) DEFAULT NULL,
  `Teacher` varchar(50) DEFAULT NULL,
  `imagelink` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Subj_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject`
--

LOCK TABLES `subject` WRITE;
/*!40000 ALTER TABLE `subject` DISABLE KEYS */;
/*!40000 ALTER TABLE `subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `Roll_number` int(11) NOT NULL DEFAULT '0',
  `Name` varchar(45) DEFAULT NULL,
  `Semester` int(1) DEFAULT NULL,
  `u_name` varchar(20) DEFAULT NULL,
  `pwd` varchar(32) DEFAULT NULL,
  `Type` varchar(1) DEFAULT NULL,
  `Branch` varchar(30) DEFAULT NULL,
  `ProfilePicLink` varchar(100) DEFAULT NULL,
  `RemindedDate` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`Roll_number`),
  UNIQUE KEY `u_name` (`u_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (165156,'Admin',NULL,'admin','4d06aee7b69a3ab8d6cc3d5db6dfadb5','A',NULL,NULL,NULL);
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

-- Dump completed on 2016-10-20  9:25:06
