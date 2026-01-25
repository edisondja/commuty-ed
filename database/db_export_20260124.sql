-- MariaDB dump 10.18  Distrib 10.4.17-MariaDB, for osx10.10 (x86_64)
--
-- Host: 127.0.0.1    Database: edcommunity
-- ------------------------------------------------------
-- Server version	10.4.17-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `action_coment`
--

DROP TABLE IF EXISTS `action_coment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action_coment` (
  `id_action` int(11) NOT NULL AUTO_INCREMENT,
  `type_action` varchar(30) DEFAULT NULL,
  `id_coment` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_action`),
  KEY `id_user` (`id_user`),
  KEY `id_coment` (`id_coment`),
  CONSTRAINT `action_coment_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `action_coment_ibfk_2` FOREIGN KEY (`id_coment`) REFERENCES `comentario` (`id_comentario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `action_coment`
--

LOCK TABLES `action_coment` WRITE;
/*!40000 ALTER TABLE `action_coment` DISABLE KEYS */;
/*!40000 ALTER TABLE `action_coment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ads` (
  `ads_id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `imagen_ruta` varchar(250) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `script_banner` text DEFAULT NULL,
  `posicion` int(11) DEFAULT NULL,
  `fecha_ads` datetime DEFAULT current_timestamp(),
  `link_banner` varchar(250) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`ads_id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `ads_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads`
--

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignar_multimedia_t`
--

DROP TABLE IF EXISTS `asignar_multimedia_t`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignar_multimedia_t` (
  `id_asignar` int(11) NOT NULL AUTO_INCREMENT,
  `ruta_multimedia` varchar(100) DEFAULT NULL,
  `tipo_multimedia` varchar(10) DEFAULT NULL,
  `type_media` varchar(50) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `metodo_de_pago` varchar(30) DEFAULT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_asignar`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `asignar_multimedia_t_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignar_multimedia_t`
--

LOCK TABLES `asignar_multimedia_t` WRITE;
/*!40000 ALTER TABLE `asignar_multimedia_t` DISABLE KEYS */;
INSERT INTO `asignar_multimedia_t` VALUES (1,'/videos/1769201424test.mp4','video',NULL,'activo',NULL,NULL,NULL,10),(2,'/videos/1769228618el_hombre_con_q.mp4','video',NULL,'activo',NULL,NULL,NULL,12),(3,'/videos/1769228750el_hombre_con_q.mp4','video',NULL,'activo',NULL,NULL,NULL,13),(4,'/videos/1769229137el_hombre_con_q.mp4','video',NULL,'activo',NULL,NULL,NULL,14),(5,'/videos/1769229342tst.mp4','video',NULL,'activo',NULL,NULL,NULL,15),(6,'/videos/1769230051tst.mp4','video',NULL,'activo',NULL,NULL,NULL,16),(7,'/videos/1769230293test.mp4','video',NULL,'activo',NULL,NULL,NULL,17),(8,'/videos/1769230526con_quien_quier.mp4','video',NULL,'activo',NULL,NULL,NULL,18);
/*!40000 ALTER TABLE `asignar_multimedia_t` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boton_menu`
--

DROP TABLE IF EXISTS `boton_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boton_menu` (
  `id_boton` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `url_boton` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_boton`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boton_menu`
--

LOCK TABLES `boton_menu` WRITE;
/*!40000 ALTER TABLE `boton_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `boton_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria_v`
--

DROP TABLE IF EXISTS `categoria_v`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria_v` (
  `id_catev` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(30) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_catev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_v`
--

LOCK TABLES `categoria_v` WRITE;
/*!40000 ALTER TABLE `categoria_v` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoria_v` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentario`
--

DROP TABLE IF EXISTS `comentario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comentario` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `data_og` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_og`)),
  `estado` varchar(15) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT current_timestamp(),
  `tipo_comentario` varchar(10) DEFAULT NULL,
  `tipo_post` varchar(255) NOT NULL,
  PRIMARY KEY (`id_comentario`),
  KEY `usuario_id` (`usuario_id`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario`
--

LOCK TABLES `comentario` WRITE;
/*!40000 ALTER TABLE `comentario` DISABLE KEYS */;
INSERT INTO `comentario` VALUES (1,7,'sin comentarios\r\n','[]','activo',2,'2026-01-22 11:26:15',NULL,'board'),(2,7,'ww','[]','activo',2,'2026-01-22 11:52:59',NULL,'board');
/*!40000 ALTER TABLE `comentario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion` (
  `configuracion_id` int(11) NOT NULL AUTO_INCREMENT,
  `dominio` varchar(280) DEFAULT NULL,
  `nombre_sitio` varchar(150) DEFAULT NULL,
  `descripcion_slogan` text DEFAULT NULL,
  `descripcion_sitio` text DEFAULT NULL,
  `favicon_url` varchar(200) DEFAULT NULL,
  `sitio_logo_url` varchar(200) DEFAULT NULL,
  `copyright_descripcion` text DEFAULT NULL,
  `email_sitio` varchar(180) DEFAULT NULL,
  `busqueda_descripcion` text DEFAULT NULL,
  `pagina_descripcion` text DEFAULT NULL,
  `titulo_descripcion` text DEFAULT NULL,
  `busqueda_hastag` text DEFAULT NULL,
  `email_remitente` varchar(200) DEFAULT NULL,
  `nombre_remitente` varchar(150) DEFAULT NULL,
  `servidor_smtp` varchar(200) DEFAULT NULL,
  `puerto_smtp` varchar(50) DEFAULT NULL,
  `usuario_smtp` varchar(250) DEFAULT NULL,
  `clave_smtp` varchar(250) DEFAULT NULL,
  `autenticacion_ssl` tinyint(1) DEFAULT NULL,
  `publicar_sin_revision` tinyint(1) DEFAULT NULL,
  `verificar_cuenta` tinyint(1) DEFAULT NULL,
  `rabbit_mq` tinyint(1) DEFAULT NULL,
  `ffmpeg` tinyint(1) DEFAULT NULL,
  `redis_cache` tinyint(1) DEFAULT NULL,
  `estilos_json` text DEFAULT NULL,
  PRIMARY KEY (`configuracion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion`
--

LOCK TABLES `configuracion` WRITE;
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `criterios`
--

DROP TABLE IF EXISTS `criterios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `criterios` (
  `id_criterio` int(11) NOT NULL AUTO_INCREMENT,
  `criterio` varchar(80) DEFAULT NULL,
  `fecha_criterio` date DEFAULT NULL,
  PRIMARY KEY (`id_criterio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `criterios`
--

LOCK TABLES `criterios` WRITE;
/*!40000 ALTER TABLE `criterios` DISABLE KEYS */;
/*!40000 ALTER TABLE `criterios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favoritos` (
  `id_favorito` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_favorito`),
  KEY `id_tablero` (`id_tablero`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos`
--

LOCK TABLES `favoritos` WRITE;
/*!40000 ALTER TABLE `favoritos` DISABLE KEYS */;
/*!40000 ALTER TABLE `favoritos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `id_like` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `fecha_like` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_like`),
  KEY `id_user` (`id_user`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (1,2,7,'activo','2026-01-23 06:42:39');
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs_I`
--

DROP TABLE IF EXISTS `logs_I`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs_I` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `fecha_log` datetime NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `tracking` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_log`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `logs_i_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_I`
--

LOCK TABLES `logs_I` WRITE;
/*!40000 ALTER TABLE `logs_I` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs_I` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion`
--

DROP TABLE IF EXISTS `notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario_receptor` int(11) DEFAULT NULL,
  `id_usuario_emisor` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `tipo` varchar(50) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_usuario_emisor` (`id_usuario_emisor`),
  KEY `id_usuario_receptor` (`id_usuario_receptor`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`id_usuario_emisor`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `notificacion_ibfk_2` FOREIGN KEY (`id_usuario_receptor`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `notificacion_ibfk_3` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion`
--

LOCK TABLES `notificacion` WRITE;
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
INSERT INTO `notificacion` VALUES (1,7,2,2,'2026-01-22 23:26:15','comentario','activo'),(2,7,2,2,'2026-01-22 23:52:59','comentario','activo');
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratings` (
  `id_rating` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_tablero` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL CHECK (`puntuacion` >= 1 and `puntuacion` <= 5),
  `estado` varchar(15) DEFAULT 'activo',
  `fecha_rating` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_rating`),
  UNIQUE KEY `unique_user_board_rating` (`id_user`,`id_tablero`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
INSERT INTO `ratings` VALUES (1,2,7,3,'activo','2026-01-23 15:36:05'),(2,2,10,4,'activo','2026-01-23 16:52:58');
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reply_coment`
--

DROP TABLE IF EXISTS `reply_coment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reply_coment` (
  `id_reply` int(11) NOT NULL AUTO_INCREMENT,
  `text_coment` text DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `coment_id` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_reply`),
  KEY `user_id` (`user_id`),
  KEY `coment_id` (`coment_id`),
  CONSTRAINT `reply_coment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `reply_coment_ibfk_2` FOREIGN KEY (`coment_id`) REFERENCES `comentario` (`id_comentario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reply_coment`
--

LOCK TABLES `reply_coment` WRITE;
/*!40000 ALTER TABLE `reply_coment` DISABLE KEYS */;
INSERT INTO `reply_coment` VALUES (4,'test','inactivo',2,1,'2026-01-22 11:39:10'),(5,'test','inactivo',2,1,'2026-01-22 11:42:53'),(6,'test','inactivo',2,2,'2026-01-22 11:53:06'),(7,'test','inactivo',2,2,'2026-01-22 11:56:23'),(8,'es','inactivo',2,2,'2026-01-22 11:58:42'),(9,'responde','inactivo',2,2,'2026-01-23 12:03:45'),(10,'hola','inactivo',2,2,'2026-01-23 12:03:57'),(11,'tes','inactivo',2,2,'2026-01-23 12:28:24'),(12,'perra','activo',2,2,'2026-01-23 12:39:43'),(13,'ddsds','activo',2,1,'2026-01-23 12:39:50'),(14,'es','activo',2,2,'2026-01-23 06:42:50');
/*!40000 ALTER TABLE `reply_coment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportes`
--

DROP TABLE IF EXISTS `reportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reportes` (
  `id_report` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` varchar(15) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_board` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_report`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_board` (`id_board`),
  CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `reportes_ibfk_2` FOREIGN KEY (`id_board`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportes`
--

LOCK TABLES `reportes` WRITE;
/*!40000 ALTER TABLE `reportes` DISABLE KEYS */;
/*!40000 ALTER TABLE `reportes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tableros`
--

DROP TABLE IF EXISTS `tableros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tableros` (
  `id_tablero` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `tipo_tablero` varchar(10) DEFAULT NULL,
  `imagen_tablero` varchar(120) DEFAULT NULL,
  `preview_tablero` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id_tablero`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `tableros_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tableros`
--

LOCK TABLES `tableros` WRITE;
/*!40000 ALTER TABLE `tableros` DISABLE KEYS */;
INSERT INTO `tableros` VALUES (1,NULL,'test','inactivo','2026-01-22 23:05:22',1,NULL,'',NULL),(2,NULL,'test','inactivo','2026-01-22 23:06:35',1,NULL,'',NULL),(3,NULL,'test','inactivo','2026-01-22 23:06:50',1,NULL,'',NULL),(4,NULL,'test','inactivo','2026-01-22 23:08:01',1,NULL,'',NULL),(5,NULL,'esto es una prueba amigos','activo','2026-01-22 23:10:13',1,NULL,'',NULL),(6,NULL,'que bonito e miren esto','inactivo','2026-01-22 23:22:52',2,NULL,'/imagenes_tablero/1769120572.jpg',NULL),(7,NULL,'test','activo','2026-01-22 23:25:20',2,NULL,'/imagenes_tablero/1769120720.jpg',NULL),(8,NULL,'test','activo','2026-01-23 06:46:50',2,NULL,'',NULL),(9,NULL,'las mejueres estan con quien quieren y el hombre ocn quien puede','activo','2026-01-23 21:42:33',2,NULL,'',NULL),(10,NULL,'test','inactivo','2026-01-23 21:50:24',2,NULL,'',NULL),(11,NULL,'el invierno se acerca','inactivo','2026-01-24 00:38:18',2,NULL,'',NULL),(12,NULL,'el hombre con quien puede y la con quien quiere?','inactivo','2026-01-24 05:23:38',2,NULL,'',NULL),(13,NULL,'el hombre con quien puede y la con quien quiere?','inactivo','2026-01-24 05:25:50',2,NULL,'',NULL),(14,NULL,'el hombre con quien puede y la con quien quiere?','activo','2026-01-24 05:32:17',2,NULL,'',NULL),(15,NULL,'tst','activo','2026-01-24 05:35:42',2,NULL,'',NULL),(16,NULL,'tst','activo','2026-01-24 05:47:31',2,NULL,'',NULL),(17,NULL,'test','activo','2026-01-24 05:51:33',2,NULL,'',NULL),(18,NULL,'con quien quiere verdad?','activo','2026-01-24 05:55:26',2,NULL,'imagenes_tablero/20260124045526_18.jpg','previa/20260124045526_18.mp4');
/*!40000 ALTER TABLE `tableros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `bio` text DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `clave` varchar(200) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `foto_url` text DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `type_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'','Edison','De Jesus Abreu','h','edisondja@gmail.com','b1a15bed7e5f02e5bb54d92f7d895bce\r\n','2026-01-22 11:03:18','assets/user_profile.png','edisondja','activo','admin'),(2,'test','Edison','De Jesus Abreu','h','ed_admin@gmail.com','b1a15bed7e5f02e5bb54d92f7d895bce','2026-01-22 11:19:57','assets/user_profile.png','ed_admin','activo','admin');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `views`
--

DROP TABLE IF EXISTS `views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `id_tablero` (`id_tablero`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `views_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE,
  CONSTRAINT `views_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `views`
--

LOCK TABLES `views` WRITE;
/*!40000 ALTER TABLE `views` DISABLE KEYS */;
INSERT INTO `views` VALUES (1,7,2,63),(2,10,2,2),(3,17,2,1),(4,18,2,2);
/*!40000 ALTER TABLE `views` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-24  0:57:29
