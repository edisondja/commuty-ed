-- ============================================
-- SCRIPT DE CORRECCIÓN PARA PRODUCCIÓN
-- Ejecutar este script para agregar columnas faltantes
-- ============================================

-- Desactivar verificación de claves foráneas temporalmente
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- TABLA: configuracion - Agregar columnas faltantes
-- ============================================
ALTER TABLE `configuracion` 
  ADD COLUMN IF NOT EXISTS `publicar_sin_revision` tinyint(1) DEFAULT 1,
  ADD COLUMN IF NOT EXISTS `verificar_cuenta` tinyint(1) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `rabbit_mq` tinyint(1) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `ffmpeg` tinyint(1) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `redis_cache` tinyint(1) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `estilos_json` text DEFAULT NULL;

-- ============================================
-- TABLA: tableros - Agregar id_reproductor
-- ============================================
ALTER TABLE `tableros` 
  ADD COLUMN IF NOT EXISTS `id_reproductor` int(11) DEFAULT NULL;

-- ============================================
-- TABLA: likes - Agregar estado
-- ============================================
ALTER TABLE `likes` 
  ADD COLUMN IF NOT EXISTS `estado` varchar(50) DEFAULT 'activo';

-- ============================================
-- TABLA: views - Agregar cantidad
-- ============================================
ALTER TABLE `views` 
  ADD COLUMN IF NOT EXISTS `cantidad` int(11) DEFAULT 1;

-- ============================================
-- TABLA: ratings - Crear si no existe
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
-- TABLA: reproductores_vast - Crear si no existe
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

-- Insertar reproductor por defecto si no existe
INSERT IGNORE INTO `reproductores_vast` (`nombre`, `descripcion`, `es_default`, `activo`) 
VALUES ('Reproductor Principal', 'Reproductor por defecto del sitio', 1, 1);

-- Reactivar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- VERIFICACIÓN
-- ============================================
SELECT 'Migraciones completadas exitosamente' AS resultado;
