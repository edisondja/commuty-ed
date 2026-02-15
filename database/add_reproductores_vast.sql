-- Tabla para configurar reproductores de video con VAST
CREATE TABLE IF NOT EXISTS reproductores_vast (
    id_reproductor INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    vast_url TEXT COMMENT 'URL del tag VAST para pre-roll',
    vast_url_mid TEXT COMMENT 'URL del tag VAST para mid-roll',
    vast_url_post TEXT COMMENT 'URL del tag VAST para post-roll',
    skip_delay INT DEFAULT 5 COMMENT 'Segundos antes de poder saltar el anuncio',
    mid_roll_time INT DEFAULT 30 COMMENT 'Segundo en que aparece el mid-roll',
    activo TINYINT(1) DEFAULT 1,
    es_default TINYINT(1) DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar reproductor por defecto
INSERT INTO reproductores_vast (nombre, descripcion, es_default, activo) 
VALUES ('Reproductor Principal', 'Reproductor por defecto del sitio', 1, 1);
