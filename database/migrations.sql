-- ============================================
-- MIGRACIONES DE COMMUTY-ED
-- Ejecutar si tienes una base de datos antigua
-- ============================================

-- Agregar columnas faltantes a configuracion
ALTER TABLE `configuracion` 
    ADD COLUMN IF NOT EXISTS `publicar_sin_revision` tinyint(1) DEFAULT 1,
    ADD COLUMN IF NOT EXISTS `verificar_cuenta` tinyint(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `rabbit_mq` tinyint(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `ffmpeg` tinyint(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `redis_cache` tinyint(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `estilos_json` text DEFAULT NULL;

-- Convertir columnas VARCHAR a TINYINT si son del tipo incorrecto
-- (Ejecutar manualmente si hay problemas)
-- ALTER TABLE `configuracion` MODIFY COLUMN `autenticacion_ssl` tinyint(1) DEFAULT 0;
-- ALTER TABLE `configuracion` MODIFY COLUMN `publicar_sin_revision` tinyint(1) DEFAULT 1;
-- ALTER TABLE `configuracion` MODIFY COLUMN `verificar_cuenta` tinyint(1) DEFAULT 0;
-- ALTER TABLE `configuracion` MODIFY COLUMN `rabbit_mq` tinyint(1) DEFAULT 0;
-- ALTER TABLE `configuracion` MODIFY COLUMN `ffmpeg` tinyint(1) DEFAULT 0;
-- ALTER TABLE `configuracion` MODIFY COLUMN `redis_cache` tinyint(1) DEFAULT 0;

-- Agregar id_reproductor a tableros si no existe
ALTER TABLE `tableros` 
    ADD COLUMN IF NOT EXISTS `id_reproductor` int(11) DEFAULT NULL;

-- Crear tabla ratings si no existe
CREATE TABLE IF NOT EXISTS `ratings` (
  `id_rating` int(11) NOT NULL AUTO_INCREMENT,
  `id_tablero` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_rating`),
  UNIQUE KEY `unique_user_board_rating` (`id_tablero`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crear tabla reproductores_vast si no existe
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

-- Insertar reproductor por defecto si no existe
INSERT IGNORE INTO `reproductores_vast` (`nombre`, `descripcion`, `es_default`, `activo`) 
VALUES ('Reproductor Principal', 'Reproductor por defecto del sitio', 1, 1);

-- Renombrar id_reply_id a id_reply si existe la columna antigua
-- ALTER TABLE `reply_coment` CHANGE COLUMN `id_reply_id` `id_reply` int(11) NOT NULL AUTO_INCREMENT;
