CREATE DATABASE IF NOT EXISTS edcommunity;
USE edcommunity;

-- Tabla de configuración general
CREATE TABLE configuracion (
  configuracion_id INT PRIMARY KEY AUTO_INCREMENT,
  dominio VARCHAR(280),
  nombre_sitio VARCHAR(150),
  descripcion_slogan TEXT,
  descripcion_sitio TEXT,
  favicon_url VARCHAR(200),
  sitio_logo_url VARCHAR(200),
  copyright_descripcion TEXT,
  email_sitio VARCHAR(180),
  busqueda_descripcion TEXT,
  pagina_descripcion TEXT,
  titulo_descripcion TEXT,
  busqueda_hastag TEXT,
  email_remitente VARCHAR(200),
  nombre_remitente VARCHAR(150),
  servidor_smtp VARCHAR(200),
  puerto_smtp VARCHAR(50),
  usuario_smtp VARCHAR(250),
  clave_smtp VARCHAR(250),
  autenticacion_ssl VARCHAR(2),
  publicar_sin_revision VARCHAR(2),
  verificar_cuenta VARCHAR(2)
);

-- Tabla de usuarios
CREATE TABLE user (
  id_user INT PRIMARY KEY AUTO_INCREMENT,
  bio TEXT,
  nombre VARCHAR(100),
  apellido VARCHAR(100),
  sexo CHAR(1),
  email VARCHAR(200),
  clave VARCHAR(200),
  fecha_creacion DATETIME,
  foto_url TEXT,
  usuario VARCHAR(50),
  estado VARCHAR(15),
  type_user VARCHAR(50)
);

-- Tabla de tableros
CREATE TABLE tableros (
  id_tablero INT PRIMARY KEY AUTO_INCREMENT,
  titulo VARCHAR(100),
  descripcion TEXT,
  estado VARCHAR(15),
  fecha_creacion DATETIME,
  id_usuario INT,
  tipo_tablero VARCHAR(10),
  imagen_tablero VARCHAR(120),
  FOREIGN KEY (id_usuario) REFERENCES user(id_user) ON DELETE CASCADE
);

-- Tabla de multimedia asignada a tableros
CREATE TABLE asignar_multimedia_t (
  id_asignar INT PRIMARY KEY AUTO_INCREMENT,
  ruta_multimedia VARCHAR(100),
  tipo_multimedia VARCHAR(10),
  type_media VARCHAR(50),
  estado VARCHAR(15),
  texto TEXT,
  precio FLOAT,
  metodo_de_pago VARCHAR(30),
  id_tablero INT,
  FOREIGN KEY (id_tablero) REFERENCES tableros(id_tablero) ON DELETE CASCADE
);

-- Tabla de comentarios
CREATE TABLE comentario (
  id_comentario INT PRIMARY KEY AUTO_INCREMENT,
  id_tablero INT,
  texto TEXT,
  data_og JSON,
  estado VARCHAR(15),
  usuario_id INT,
  fecha_publicacion DATETIME,
  tipo_comentario VARCHAR(10),
  tipo_post VARCHAR(255) NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_tablero) REFERENCES tableros(id_tablero) ON DELETE CASCADE
);

-- Tabla de acciones en comentarios
CREATE TABLE action_coment (
  id_action INT PRIMARY KEY AUTO_INCREMENT,
  type_action VARCHAR(30),
  id_coment INT,
  id_user INT,
  fecha_creacion DATETIME,
  FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_coment) REFERENCES comentario(id_comentario) ON DELETE CASCADE
);

-- Tabla de respuestas en comentarios
CREATE TABLE reply_coment (
  id_reply_id INT PRIMARY KEY AUTO_INCREMENT,
  text_coment TEXT,
  estado VARCHAR(15),
  user_id INT,
  coment_id INT,
  fecha_creacion DATETIME,
  FOREIGN KEY (user_id) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (coment_id) REFERENCES comentario(id_comentario) ON DELETE CASCADE
);

-- Tabla de categorías
CREATE TABLE categorias (
  id_categoria INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(80),
  fecha_creacion DATETIME
);

-- Tabla de categorías adicionales
CREATE TABLE categoria_v (
  id_catev INT PRIMARY KEY AUTO_INCREMENT,
  categoria VARCHAR(30),
  fecha_registro DATETIME
);

-- Tabla de logs
CREATE TABLE logs_I (
  id_log INT PRIMARY KEY AUTO_INCREMENT,
  id_user INT,
  fecha_log DATETIME NOT NULL,
  tipo VARCHAR(50),
  tracking VARCHAR(100),
  FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
);

-- Tabla de favoritos
CREATE TABLE favoritos (
  id_favorito INT PRIMARY KEY AUTO_INCREMENT,
  id_post INT,
  id_usuario INT,
  FOREIGN KEY (id_usuario) REFERENCES user(id_user) ON DELETE CASCADE
  -- Asegúrate de que la tabla 'posts' esté creada y 'id_post' sea una columna válida
);

-- Tabla de visualizaciones
CREATE TABLE views (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_tablero INT,
  id_usuario INT,
  cantidad INT DEFAULT NULL,
  FOREIGN KEY (id_tablero) REFERENCES tableros(id_tablero) ON DELETE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES user(id_user) ON DELETE CASCADE
);

-- Tabla de botones de menú
CREATE TABLE boton_menu (
  id_boton INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(50),
  url_boton VARCHAR(50)
);

-- Tabla de reportes
CREATE TABLE reportes (
  id_report INT PRIMARY KEY AUTO_INCREMENT,
  descripcion TEXT,
  fecha_creacion DATETIME,
  estado VARCHAR(15),
  id_usuario INT, 
  id_board INT,
  FOREIGN KEY (id_usuario) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_board) REFERENCES tableros(id_tablero)
);

-- Tabla de criterios
CREATE TABLE criterios (
  id_criterio INT PRIMARY KEY AUTO_INCREMENT,
  criterio VARCHAR(80),
  fecha_criterio DATE
);

-- Tabla de notificaciones
CREATE TABLE notificacion (
  id_notificacion INT PRIMARY KEY AUTO_INCREMENT,
  id_tablero INT,
  id_usuario_receptor INT,
  id_usuario_emisor INT,
  fecha DATETIME,
  tipo VARCHAR(50),
  estado VARCHAR(15),
  FOREIGN KEY (id_usuario_emisor) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_usuario_receptor) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_tablero) REFERENCES tableros(id_tablero) ON DELETE CASCADE
);

-- Tabla de anuncios (ads)
CREATE TABLE ads (
  ads_id INT PRIMARY KEY AUTO_INCREMENT,
  titulo VARCHAR(200),
  descripcion TEXT,
  id_user  INT,
  FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
  imagen_ruta VARCHAR(250),
  tipo VARCHAR(30),
  script_banner TEXT,
  posicion INT,
  fecha_ads DATETIME,
  link_banner VARCHAR(250),
  estado VARCHAR(15)
);

-- Tabla de likes
CREATE TABLE likes (
  id_like INT PRIMARY KEY AUTO_INCREMENT,
  id_user INT,
  id_tablero INT,
  estado VARCHAR(50),
  fecha_like DATETIME,
  FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_tablero) REFERENCES tableros(id_tablero) ON DELETE CASCADE
);
