-- ============================================
-- COMMUTY-ED - SCHEMA COMPLETO DE BASE DE DATOS
-- Versión: 2.0 - Generado el 2026-01-24
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- ============================================
-- TABLA: users (PRIMERO - otras tablas dependen de ella)
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: tableros
-- ============================================
CREATE TABLE IF NOT EXISTS `tableros` (
  `id_tablero` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `tipo_tablero` varchar(10) DEFAULT NULL,
  `imagen_tablero` varchar(120) DEFAULT NULL,
  `preview_tablero` varchar(120) DEFAULT NULL,
  `id_reproductor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tablero`),
  KEY `id_usuario` (`id_usuario`),
  KEY `idx_reproductor` (`id_reproductor`),
  CONSTRAINT `tableros_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: comentario
-- ============================================
CREATE TABLE IF NOT EXISTS `comentario` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `data_og` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT current_timestamp(),
  `tipo_comentario` varchar(10) DEFAULT NULL,
  `tipo_post` varchar(255) NOT NULL DEFAULT 'board',
  PRIMARY KEY (`id_comentario`),
  KEY `usuario_id` (`usuario_id`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: reply_coment
-- ============================================
CREATE TABLE IF NOT EXISTS `reply_coment` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: action_coment
-- ============================================
CREATE TABLE IF NOT EXISTS `action_coment` (
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

-- ============================================
-- TABLA: likes
-- ============================================
CREATE TABLE IF NOT EXISTS `likes` (
  `id_like` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'activo',
  `fecha_like` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_like`),
  KEY `id_user` (`id_user`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: views
-- ============================================
CREATE TABLE IF NOT EXISTS `views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `id_tablero` (`id_tablero`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `views_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE,
  CONSTRAINT `views_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: favoritos
-- ============================================
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id_favorito` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_favorito`),
  KEY `id_tablero` (`id_tablero`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: ratings
-- ============================================
CREATE TABLE IF NOT EXISTS `ratings` (
  `id_rating` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_tablero` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL,
  `estado` varchar(15) DEFAULT 'activo',
  `fecha_rating` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_rating`),
  UNIQUE KEY `unique_user_board_rating` (`id_user`,`id_tablero`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: reportes
-- ============================================
CREATE TABLE IF NOT EXISTS `reportes` (
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

-- ============================================
-- TABLA: notificacion
-- ============================================
CREATE TABLE IF NOT EXISTS `notificacion` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: asignar_multimedia_t
-- ============================================
CREATE TABLE IF NOT EXISTS `asignar_multimedia_t` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: ads (banners/publicidad)
-- ============================================
CREATE TABLE IF NOT EXISTS `ads` (
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

-- ============================================
-- TABLA: configuracion
-- ============================================
CREATE TABLE IF NOT EXISTS `configuracion` (
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
  `autenticacion_ssl` tinyint(1) DEFAULT 0,
  `publicar_sin_revision` tinyint(1) DEFAULT 1,
  `verificar_cuenta` tinyint(1) DEFAULT 0,
  `rabbit_mq` tinyint(1) DEFAULT 0,
  `ffmpeg` tinyint(1) DEFAULT 0,
  `redis_cache` tinyint(1) DEFAULT 0,
  `google_analytics_id` varchar(50) DEFAULT NULL,
  `estilos_json` text DEFAULT NULL,
  PRIMARY KEY (`configuracion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: reproductores_vast
-- ============================================
CREATE TABLE IF NOT EXISTS `reproductores_vast` (
  `id_reproductor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `vast_url` text DEFAULT NULL,
  `vast_url_mid` text DEFAULT NULL,
  `vast_url_post` text DEFAULT NULL,
  `skip_delay` int(11) DEFAULT 5,
  `mid_roll_time` int(11) DEFAULT 30,
  `activo` tinyint(1) DEFAULT 1,
  `es_default` tinyint(1) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_reproductor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: logs_i
-- ============================================
CREATE TABLE IF NOT EXISTS `logs_i` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `fecha_log` datetime NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `tracking` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_log`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `logs_i_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: service_failures (Registro de fallos de servicios)
-- ============================================
CREATE TABLE IF NOT EXISTS `service_failures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `service_name` varchar(100) NOT NULL,
  `error_type` varchar(50) DEFAULT NULL,
  `error_message` text NOT NULL,
  `stack_trace` text DEFAULT NULL,
  `additional_data` json DEFAULT NULL,
  `resolved` tinyint(1) DEFAULT 0,
  `resolved_at` datetime DEFAULT NULL,
  `resolved_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_service` (`service_name`),
  KEY `idx_timestamp` (`timestamp`),
  KEY `idx_resolved` (`resolved`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: categorias
-- ============================================
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: categoria_v
-- ============================================
CREATE TABLE IF NOT EXISTS `categoria_v` (
  `id_catev` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(30) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_catev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: criterios
-- ============================================
CREATE TABLE IF NOT EXISTS `criterios` (
  `id_criterio` int(11) NOT NULL AUTO_INCREMENT,
  `criterio` varchar(80) DEFAULT NULL,
  `fecha_criterio` date DEFAULT NULL,
  PRIMARY KEY (`id_criterio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLA: boton_menu
-- ============================================
CREATE TABLE IF NOT EXISTS `boton_menu` (
  `id_boton` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `url_boton` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_boton`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- DATOS INICIALES
-- ============================================

-- Insertar reproductor VAST por defecto
INSERT IGNORE INTO `reproductores_vast` (`nombre`, `descripcion`, `es_default`, `activo`) 
VALUES ('Reproductor Principal', 'Reproductor por defecto del sitio', 1, 1);

-- Insertar configuración inicial vacía (el instalador la completa)
INSERT INTO `configuracion` (`configuracion_id`, `dominio`, `nombre_sitio`, `publicar_sin_revision`, `verificar_cuenta`, `rabbit_mq`, `ffmpeg`, `redis_cache`) 
VALUES (1, '', '', 1, 0, 0, 0, 0)
ON DUPLICATE KEY UPDATE `configuracion_id` = `configuracion_id`;
