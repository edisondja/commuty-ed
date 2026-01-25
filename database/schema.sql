-- ============================================
-- COMMUTY-ED - Esquema de Base de Datos
-- Versión: 1.0 (Actualizado 2026-01-24)
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- ============================================
-- Tabla de configuración general
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
  `estilos_json` text DEFAULT NULL,
  PRIMARY KEY (`configuracion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de usuarios
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
  `estado` varchar(15) DEFAULT 'activo',
  `type_user` varchar(50) DEFAULT 'user',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de tableros (publicaciones)
-- ============================================
CREATE TABLE IF NOT EXISTS `tableros` (
  `id_tablero` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` varchar(15) DEFAULT 'activo',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL,
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
-- Tabla de multimedia asignada a tableros
-- ============================================
CREATE TABLE IF NOT EXISTS `asignar_multimedia_t` (
  `id_asignar` int(11) NOT NULL AUTO_INCREMENT,
  `ruta_multimedia` varchar(100) DEFAULT NULL,
  `tipo_multimedia` varchar(10) DEFAULT NULL,
  `type_media` varchar(50) DEFAULT NULL,
  `estado` varchar(15) DEFAULT 'activo',
  `texto` text DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `metodo_de_pago` varchar(30) DEFAULT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_asignar`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `asignar_multimedia_t_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de comentarios
-- ============================================
CREATE TABLE IF NOT EXISTS `comentario` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `data_og` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_og`)),
  `estado` varchar(15) DEFAULT 'activo',
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT current_timestamp(),
  `tipo_comentario` varchar(10) DEFAULT NULL,
  `tipo_post` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_comentario`),
  KEY `usuario_id` (`usuario_id`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de respuestas a comentarios
-- ============================================
CREATE TABLE IF NOT EXISTS `reply_coment` (
  `id_reply` int(11) NOT NULL AUTO_INCREMENT,
  `id_comentario` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `estado` varchar(10) DEFAULT 'activo',
  `id_user` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_reply`),
  KEY `id_user` (`id_user`),
  KEY `id_comentario` (`id_comentario`),
  CONSTRAINT `reply_coment_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `reply_coment_ibfk_2` FOREIGN KEY (`id_comentario`) REFERENCES `comentario` (`id_comentario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de acciones en comentarios (likes)
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
-- Tabla de likes en tableros
-- ============================================
CREATE TABLE IF NOT EXISTS `likes` (
  `id_like` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_like`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de vistas
-- ============================================
CREATE TABLE IF NOT EXISTS `views` (
  `id_view` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_view`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_tablero` (`id_tablero`),
  CONSTRAINT `views_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `views_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de reportes
-- ============================================
CREATE TABLE IF NOT EXISTS `reports` (
  `id_report` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `razon` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` varchar(15) DEFAULT 'pendiente',
  PRIMARY KEY (`id_report`),
  KEY `id_tablero` (`id_tablero`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE,
  CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de favoritos
-- ============================================
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id_favorito` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_favorito`),
  KEY `id_tablero` (`id_tablero`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de notificaciones
-- ============================================
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id_notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `id_referencia` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de anuncios/banners
-- ============================================
CREATE TABLE IF NOT EXISTS `ads` (
  `id_ads` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen_ruta` varchar(200) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT 'imagen',
  `script_banner` text DEFAULT NULL,
  `posicion` int(11) DEFAULT 1,
  `link_banner` varchar(255) DEFAULT NULL,
  `estado` varchar(15) DEFAULT 'activo',
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_ads`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `ads_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de ratings (calificaciones)
-- ============================================
CREATE TABLE IF NOT EXISTS `ratings` (
  `id_rating` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_rating`),
  UNIQUE KEY `unique_user_board_rating` (`id_tablero`,`id_usuario`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE,
  CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de reproductores VAST
-- ============================================
CREATE TABLE IF NOT EXISTS `reproductores_vast` (
  `id_reproductor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `vast_url` text DEFAULT NULL COMMENT 'URL del tag VAST para pre-roll',
  `vast_url_mid` text DEFAULT NULL COMMENT 'URL del tag VAST para mid-roll',
  `vast_url_post` text DEFAULT NULL COMMENT 'URL del tag VAST para post-roll',
  `skip_delay` int(11) DEFAULT 5 COMMENT 'Segundos antes de poder saltar el anuncio',
  `mid_roll_time` int(11) DEFAULT 30 COMMENT 'Segundo en que aparece el mid-roll',
  `activo` tinyint(1) DEFAULT 1,
  `es_default` tinyint(1) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_reproductor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de seguimiento (followers)
-- ============================================
CREATE TABLE IF NOT EXISTS `seguir` (
  `id_seguir` int(11) NOT NULL AUTO_INCREMENT,
  `id_seguidor` int(11) DEFAULT NULL,
  `id_seguido` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_seguir`),
  KEY `id_seguidor` (`id_seguidor`),
  KEY `id_seguido` (`id_seguido`),
  CONSTRAINT `seguir_ibfk_1` FOREIGN KEY (`id_seguidor`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `seguir_ibfk_2` FOREIGN KEY (`id_seguido`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabla de tokens de verificación
-- ============================================
CREATE TABLE IF NOT EXISTS `tokens_verificacion` (
  `id_token` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `tipo` varchar(50) DEFAULT 'verificacion',
  `expira` datetime DEFAULT NULL,
  `usado` tinyint(1) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_token`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `tokens_verificacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Insertar reproductor por defecto
-- ============================================
INSERT INTO `reproductores_vast` (`nombre`, `descripcion`, `es_default`, `activo`) 
VALUES ('Reproductor Principal', 'Reproductor por defecto del sitio', 1, 1)
ON DUPLICATE KEY UPDATE nombre = nombre;

SET FOREIGN_KEY_CHECKS = 1;
