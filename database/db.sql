CREATE DATABASE IF NOT EXISTS ventasrd;
USE ventasrd;

CREATE TABLE user (
  id_user INT PRIMARY KEY AUTO_INCREMENT,
  bio TEXT,
  nombre VARCHAR(100),
  apellido VARCHAR(100),
  sexo VARCHAR(1),
  email VARCHAR(200),
  clave VARCHAR(200),
  fecha_creacion DATETIME,
  foto_url TEXT,
  usuario VARCHAR(50),
  estado VARCHAR(15),
  type_user VARCHAR(50)
);

CREATE TABLE boton_menu(
  id_boton INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(50),
  url_boton VARCHAR(50)
);

CREATE TABLE favoritos(
  id_favorito INT PRIMARY KEY AUTO_INCREMENT,
  id_post INT,
  id_usuario INT,
  FOREIGN KEY (id_usuario) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_post) REFERENCES posts(id_post) ON DELETE CASCADE
);

CREATE TABLE categoria_v(
  id_catev INT PRIMARY KEY AUTO_INCREMENT,
  categoria VARCHAR(30),
  fecha_registro DATETIME
); 



CREATE TABLE logs_I(
  id_log INT PRIMARY KEY AUTO_INCREMENT,
  id_user INT,
  fecha_log DATETIME NOT NULL,
  tipo VARCHAR(50),
  tracking VARCHAR(100)
);

CREATE TABLE comentario(
  id_comentario INT PRIMARY KEY AUTO_INCREMENT,
  id_post INT,
  id_tablero INT,
  texto TEXT,
  data_og JSON,
  estado VARCHAR(15),
  usuario_id INT,
  fecha_publicacion DATETIME,
  tipo_comentario VARCHAR(10),
  FOREIGN KEY (usuario_id) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_tablero) REFERENCES tableros(id_tablero) ON DELETE CASCADE
);

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

CREATE TABLE asignar_multimedia_t(
  id_asignar INT PRIMARY KEY AUTO_INCREMENT,
  ruta_multimedia VARCHAR(100),
  tipo_multimedia VARCHAR(10),
  type_media VARCHAR(50);
  texto TEXT,
  precio FLOAT,
  metodo_de_pago VARCHAR(30),
  id_tablero INT,
  FOREIGN KEY (id_tablero) REFERENCES tableros(id_tablero) ON DELETE CASCADE
);

CREATE TABLE criterios (
  id_criterio INT PRIMARY KEY AUTO_INCREMENT,
  criterio VARCHAR(80),
  fecha_criterio DATE
);

CREATE TABLE likes (
  id_like INT PRIMARY KEY AUTO_INCREMENT,
  id_user INT,
  FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
  id_tablero int,
  FOREIGN KEY (id_tablero) REFERENCES tableros(id_tablero),
  fecha_like DATETIME
);

CREATE TABLE view (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_video INT,
  FOREIGN KEY (id_video) REFERENCES posts(id_post) ON DELETE CASCADE,
  views INT DEFAULT NULL
);

CREATE TABLE categorias (
  id_categoria INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(80),
  fecha_creacion DATETIME
);

CREATE TABLE action_coment(
  id_action INT PRIMARY KEY AUTO_INCREMENT,
  type_action VARCHAR(30),
  id_coment INT,
  id_user INT,
  fecha_creacion DATETIME,
  FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_coment) REFERENCES comentario(id_comentario) ON DELETE CASCADE
);

CREATE TABLE reply_coment(
  id_reply_id INT PRIMARY KEY AUTO_INCREMENT,
  text_coment TEXT,
  estado VARCHAR(15),
  user_id INT,
  coment_id INT, 
  fecha_creacion DATETIME,
  FOREIGN KEY (user_id) REFERENCES user(id_user) ON DELETE CASCADE,
  FOREIGN KEY (coment_id) REFERENCES comentario(id_comentario) ON DELETE CASCADE
);

ALTER TABLE comentario ADD COLUMN tipo_post VARCHAR(255) NOT NULL;


CREATE TABLE configuracion(
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
  email_remitente VARCHAR(200) ,
	nombre_remitente VARCHAR(150),
	servidor_smtp VARCHAR(200),
	puerto_smtp VARCHAR(50),
	usuario_smtp VARCHAR(250),
	clave_smtp VARCHAR(250),
	autenticacion_ssl VARCHAR(2)
	
);

CREATE TABLE reportes (
  id_report INT PRIMARY KEY AUTO_INCREMENT,
  descripcion TEXT,
  fecha_creacion DATETIME,
  id_usuario INT, 
  FOREIGN KEY (id_usuario) REFERENCES user(id_user) ON DELETE CASCADE,
  id_board INT,
  FOREIGN KEY (id_board) REFERENCES tableros(id_tablero)
  
);


CREATE TABLE ads(

	 ads_id INT PRIMARY KEY AUTO_INCREMENT,
	 titulo VARCHAR(200),
	 descripcion TEXT,
	 imagen_ruta VARCHAR(250),
	 tipo VARCHAR(30),
	 script_banner TEXT,
	 posicion INT,
	 fecha_ads DATETIME,
   link_banner VARCHAR(250)
	 
)	