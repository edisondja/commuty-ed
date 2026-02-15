-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 25-01-2026 a las 20:56:10
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `edcommunity`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `action_coment`
--

CREATE TABLE `action_coment` (
  `id_action` int(11) NOT NULL,
  `type_action` varchar(30) DEFAULT NULL,
  `id_coment` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ads`
--

CREATE TABLE `ads` (
  `ads_id` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `imagen_ruta` varchar(250) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `script_banner` text DEFAULT NULL,
  `posicion` int(11) DEFAULT NULL,
  `fecha_ads` datetime DEFAULT current_timestamp(),
  `link_banner` varchar(250) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignar_multimedia_t`
--

CREATE TABLE `asignar_multimedia_t` (
  `id_asignar` int(11) NOT NULL,
  `ruta_multimedia` varchar(100) DEFAULT NULL,
  `tipo_multimedia` varchar(10) DEFAULT NULL,
  `type_media` varchar(50) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `metodo_de_pago` varchar(30) DEFAULT NULL,
  `id_tablero` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boton_menu`
--

CREATE TABLE `boton_menu` (
  `id_boton` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `url_boton` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_v`
--

CREATE TABLE `categoria_v` (
  `id_catev` int(11) NOT NULL,
  `categoria` varchar(30) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `id_comentario` int(11) NOT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `data_og` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_og`)),
  `estado` varchar(15) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT current_timestamp(),
  `tipo_comentario` varchar(10) DEFAULT NULL,
  `tipo_post` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `configuracion_id` int(11) NOT NULL,
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
  `estilos_json` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criterios`
--

CREATE TABLE `criterios` (
  `id_criterio` int(11) NOT NULL,
  `criterio` varchar(80) DEFAULT NULL,
  `fecha_criterio` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id_favorito` int(11) NOT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE `likes` (
  `id_like` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `fecha_like` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_I`
--

CREATE TABLE `logs_I` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `fecha_log` datetime NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `tracking` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario_receptor` int(11) DEFAULT NULL,
  `id_usuario_emisor` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `tipo` varchar(50) DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ratings`
--

CREATE TABLE `ratings` (
  `id_rating` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_tablero` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL CHECK (`puntuacion` >= 1 and `puntuacion` <= 5),
  `estado` varchar(15) DEFAULT 'activo',
  `fecha_rating` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reply_coment`
--

CREATE TABLE `reply_coment` (
  `id_reply` int(11) NOT NULL,
  `text_coment` text DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `coment_id` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id_report` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` varchar(15) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_board` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reproductores_vast`
--

CREATE TABLE `reproductores_vast` (
  `id_reproductor` int(11) NOT NULL,
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
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tableros`
--

CREATE TABLE `tableros` (
  `id_tablero` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `tipo_tablero` varchar(10) DEFAULT NULL,
  `imagen_tablero` varchar(120) DEFAULT NULL,
  `preview_tablero` varchar(120) DEFAULT NULL,
  `id_reproductor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
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
  `type_user` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `views`
--

CREATE TABLE `views` (
  `id` int(11) NOT NULL,
  `id_tablero` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `action_coment`
--
ALTER TABLE `action_coment`
  ADD PRIMARY KEY (`id_action`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_coment` (`id_coment`);

--
-- Indices de la tabla `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`ads_id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `asignar_multimedia_t`
--
ALTER TABLE `asignar_multimedia_t`
  ADD PRIMARY KEY (`id_asignar`),
  ADD KEY `id_tablero` (`id_tablero`);

--
-- Indices de la tabla `boton_menu`
--
ALTER TABLE `boton_menu`
  ADD PRIMARY KEY (`id_boton`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `categoria_v`
--
ALTER TABLE `categoria_v`
  ADD PRIMARY KEY (`id_catev`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `id_tablero` (`id_tablero`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`configuracion_id`);

--
-- Indices de la tabla `criterios`
--
ALTER TABLE `criterios`
  ADD PRIMARY KEY (`id_criterio`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `id_tablero` (`id_tablero`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_like`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_tablero` (`id_tablero`);

--
-- Indices de la tabla `logs_I`
--
ALTER TABLE `logs_I`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario_emisor` (`id_usuario_emisor`),
  ADD KEY `id_usuario_receptor` (`id_usuario_receptor`),
  ADD KEY `id_tablero` (`id_tablero`);

--
-- Indices de la tabla `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id_rating`),
  ADD UNIQUE KEY `unique_user_board_rating` (`id_user`,`id_tablero`),
  ADD KEY `id_tablero` (`id_tablero`);

--
-- Indices de la tabla `reply_coment`
--
ALTER TABLE `reply_coment`
  ADD PRIMARY KEY (`id_reply`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coment_id` (`coment_id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id_report`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_board` (`id_board`);

--
-- Indices de la tabla `reproductores_vast`
--
ALTER TABLE `reproductores_vast`
  ADD PRIMARY KEY (`id_reproductor`);

--
-- Indices de la tabla `tableros`
--
ALTER TABLE `tableros`
  ADD PRIMARY KEY (`id_tablero`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `idx_reproductor` (`id_reproductor`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tablero` (`id_tablero`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `action_coment`
--
ALTER TABLE `action_coment`
  MODIFY `id_action` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ads`
--
ALTER TABLE `ads`
  MODIFY `ads_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignar_multimedia_t`
--
ALTER TABLE `asignar_multimedia_t`
  MODIFY `id_asignar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `boton_menu`
--
ALTER TABLE `boton_menu`
  MODIFY `id_boton` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria_v`
--
ALTER TABLE `categoria_v`
  MODIFY `id_catev` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `configuracion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `criterios`
--
ALTER TABLE `criterios`
  MODIFY `id_criterio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `likes`
--
ALTER TABLE `likes`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs_I`
--
ALTER TABLE `logs_I`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id_rating` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reply_coment`
--
ALTER TABLE `reply_coment`
  MODIFY `id_reply` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id_report` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reproductores_vast`
--
ALTER TABLE `reproductores_vast`
  MODIFY `id_reproductor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tableros`
--
ALTER TABLE `tableros`
  MODIFY `id_tablero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `views`
--
ALTER TABLE `views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `action_coment`
--
ALTER TABLE `action_coment`
  ADD CONSTRAINT `action_coment_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `action_coment_ibfk_2` FOREIGN KEY (`id_coment`) REFERENCES `comentario` (`id_comentario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ads`
--
ALTER TABLE `ads`
  ADD CONSTRAINT `ads_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asignar_multimedia_t`
--
ALTER TABLE `asignar_multimedia_t`
  ADD CONSTRAINT `asignar_multimedia_t_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE;

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE;

--
-- Filtros para la tabla `logs_I`
--
ALTER TABLE `logs_I`
  ADD CONSTRAINT `logs_i_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`id_usuario_emisor`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacion_ibfk_2` FOREIGN KEY (`id_usuario_receptor`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacion_ibfk_3` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reply_coment`
--
ALTER TABLE `reply_coment`
  ADD CONSTRAINT `reply_coment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `reply_coment_ibfk_2` FOREIGN KEY (`coment_id`) REFERENCES `comentario` (`id_comentario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `reportes_ibfk_2` FOREIGN KEY (`id_board`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tableros`
--
ALTER TABLE `tableros`
  ADD CONSTRAINT `tableros_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `views`
--
ALTER TABLE `views`
  ADD CONSTRAINT `views_ibfk_1` FOREIGN KEY (`id_tablero`) REFERENCES `tableros` (`id_tablero`) ON DELETE CASCADE,
  ADD CONSTRAINT `views_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
