-- MySQL dump 10.16  Distrib 10.1.23-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: tiku
-- ------------------------------------------------------
-- Server version	10.1.23-MariaDB-9+deb9u1

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
-- Table structure for table `chapters`
--

DROP TABLE IF EXISTS `chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chapters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `chapter_id` int(10) unsigned NOT NULL COMMENT '章节id',
  `chapter` smallint(5) unsigned NOT NULL COMMENT '第几章',
  `title` varchar(128) DEFAULT NULL COMMENT '标题',
  `count` int(10) unsigned DEFAULT NULL COMMENT '此章节下的题目个数',
  `car_type` varchar(64) DEFAULT NULL COMMENT '车型',
  `course` varchar(64) DEFAULT NULL COMMENT '课程代号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COMMENT='章节';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapters`
--

LOCK TABLES `chapters` WRITE;
/*!40000 ALTER TABLE `chapters` DISABLE KEYS */;
INSERT INTO `chapters` VALUES (1,121,1,'道路交通安全法律、法规和规章',565,'bus','kemu1'),(2,122,2,'交通信号',337,'bus','kemu1'),(3,123,3,'安全行车、文明驾驶基础知识',284,'bus','kemu1'),(4,124,4,'机动车驾驶操作相关基础知识',139,'bus','kemu1'),(5,125,5,'客车专题',63,'bus','kemu1'),(6,134,14,'违法行为综合判断与案例分析',41,'bus','kemu3'),(7,135,15,'安全行车常识',375,'bus','kemu3'),(8,136,16,'常见交通标志、标线和交通手势辨识',407,'bus','kemu3'),(9,137,17,'驾驶职业道德和文明驾驶常识',226,'bus','kemu3'),(10,138,18,'恶劣气候和复杂道路条件下驾驶常识',472,'bus','kemu3'),(11,139,19,'紧急情况下避险常识',148,'bus','kemu3'),(12,140,20,'交通事故救护及常见危化品处置常识',49,'bus','kemu3'),(13,121,1,'道路交通安全法律、法规和规章',565,'truck','kemu1'),(14,122,2,'交通信号',337,'truck','kemu1'),(15,123,3,'安全行车、文明驾驶基础知识',284,'truck','kemu1'),(16,124,4,'机动车驾驶操作相关基础知识',139,'truck','kemu1'),(17,126,6,'货车专题',71,'truck','kemu1'),(18,134,14,'违法行为综合判断与案例分析',41,'truck','kemu3'),(19,135,15,'安全行车常识',375,'truck','kemu3'),(20,136,16,'常见交通标志、标线和交通手势辨识',407,'truck','kemu3'),(21,137,17,'驾驶职业道德和文明驾驶常识',226,'truck','kemu3'),(22,138,18,'恶劣气候和复杂道路条件下驾驶常识',472,'truck','kemu3'),(23,139,19,'紧急情况下避险常识',148,'truck','kemu3'),(24,140,20,'交通事故救护及常见危化品处置常识',49,'truck','kemu3'),(25,121,1,'道路交通安全法律、法规和规章',565,'car','kemu1'),(26,122,2,'交通信号',337,'car','kemu1'),(27,123,3,'安全行车、文明驾驶基础知识',284,'car','kemu1'),(28,124,4,'机动车驾驶操作相关基础知识',139,'car','kemu1'),(29,127,7,'违法行为综合判断与案例分析',40,'car','kemu3'),(30,128,8,'安全行车常识',328,'car','kemu3'),(31,129,9,'常见交通标志、标线和交通手势辨识',215,'car','kemu3'),(32,130,10,'驾驶职业道德和文明驾驶常识',170,'car','kemu3'),(33,131,11,'恶劣气候和复杂道路条件下驾驶常识',222,'car','kemu3'),(34,132,12,'紧急情况下避险常识',110,'car','kemu3'),(35,133,13,'交通事故救护及常见危化品处置常识',43,'car','kemu3'),(36,207,1,'道路交通安全法',258,'moto','kemu1'),(37,209,2,'交通信号',62,'moto','kemu1'),(38,210,3,'安全行车、文明驾驶',80,'moto','kemu1'),(39,208,4,'摩托车安全文明驾驶',204,'moto','kemu3'),(40,173,1,'职业道德和法律法规',501,'keyun','zigezheng'),(41,174,2,'安全行车',488,'keyun','zigezheng'),(42,175,3,'应急处置和紧急救护',359,'keyun','zigezheng'),(43,176,4,'汽车使用技术',283,'keyun','zigezheng'),(44,177,5,'客运知识',332,'keyun','zigezheng'),(45,178,1,'职业道德和法律法规',466,'huoyun','zigezheng'),(46,179,2,'安全行车',478,'huoyun','zigezheng'),(47,180,3,'应急处置和紧急救护',228,'huoyun','zigezheng'),(48,181,4,'汽车使用技术',339,'huoyun','zigezheng'),(49,182,5,'货运知识',320,'huoyun','zigezheng'),(50,200,1,'法规',140,'weixian','zigezheng'),(51,201,2,'特性',85,'weixian','zigezheng'),(52,202,3,'运装',60,'weixian','zigezheng'),(53,203,4,'车辆',95,'weixian','zigezheng'),(54,204,5,'应急',115,'weixian','zigezheng'),(55,183,1,'教练员职责',49,'jiaolian','zigezheng'),(56,184,2,'道路交通安全法律法规',124,'jiaolian','zigezheng'),(57,185,3,'道路运输法律法规',72,'jiaolian','zigezheng'),(58,186,4,'交通安全心理与安全意识',101,'jiaolian','zigezheng'),(59,187,5,'教育学、教育心理学及其应用',101,'jiaolian','zigezheng'),(60,188,6,'驾驶员培训教学大纲',49,'jiaolian','zigezheng'),(61,189,7,'教学方法及规范化教学',189,'jiaolian','zigezheng'),(62,190,8,'教学手段',160,'jiaolian','zigezheng'),(63,191,9,'教案的编写',43,'jiaolian','zigezheng'),(64,192,10,'典型交通事故案例分析',57,'jiaolian','zigezheng'),(65,193,11,'车辆结构与安全性能',138,'jiaolian','zigezheng'),(66,194,12,'车辆维护与故障处理',38,'jiaolian','zigezheng'),(67,195,13,'车辆安全检视',25,'jiaolian','zigezheng'),(68,196,14,'环保与节能驾驶',56,'jiaolian','zigezheng'),(69,197,15,'特殊环境安全驾驶方法',64,'jiaolian','zigezheng'),(70,198,16,'应急驾驶',40,'jiaolian','zigezheng'),(71,199,17,'事故现场应急处理',25,'jiaolian','zigezheng'),(72,211,18,'驾驶职业道德与文明驾驶',146,'jiaolian','zigezheng'),(73,212,1,'职业道德与服务规范',244,'chuzu','zigezheng'),(74,213,2,'安全运营与治安防范',330,'chuzu','zigezheng'),(75,214,3,'汽车使用与常见故障处理',90,'chuzu','zigezheng'),(76,215,4,'节能驾驶',67,'chuzu','zigezheng'),(77,216,5,'运价知识与计价器使用',38,'chuzu','zigezheng'),(78,217,6,'道路交通事故处理与应急救护',103,'chuzu','zigezheng'),(79,218,7,'出租车汽车政策、法规、标准',187,'chuzu','zigezheng'),(80,219,8,'出租车服务质量信誉考核',149,'chuzu','zigezheng');
/*!40000 ALTER TABLE `chapters` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-23 10:29:22
