-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 31-10-2024 a las 23:31:35
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `chespinema`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `pk_genero` smallint NOT NULL,
  `nombre_genero` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`pk_genero`, `nombre_genero`, `descripcion`) VALUES
(1, 'Terror', 'Genero de terror terrorifico que provoca sensaciones terrorificas a cualquiera que llega a estar en presencia de este terrorifico genero.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelicula`
--

CREATE TABLE `pelicula` (
  `pk_pelicula` smallint NOT NULL,
  `titulo` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `foto` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `url` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `fecha_subida` datetime NOT NULL,
  `fk_usuarios` smallint DEFAULT NULL,
  `fk_genero` smallint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `pelicula`
--

INSERT INTO `pelicula` (`pk_pelicula`, `titulo`, `descripcion`, `foto`, `url`, `estatus`, `fecha_subida`, `fk_usuarios`, `fk_genero`) VALUES
(3, 'asdasda', 'asdasdasd', 'descarga_11zon.jpg', 'https://www.php.net/manual/es/mysqli.quickstart.connections.php', 1, '2024-10-31 03:04:18', NULL, 1),
(4, 'asfasfsaf', 'asfasfafs', 'descarga_11zon.jpg', 'https://www.php.net/manual/es/mysqli.quickstart.connections.php', 1, '2024-10-31 04:13:32', NULL, 1),
(5, 'NOSEEEE', 'Una peli super epica cabrooon', 'yoi_11zon.png', 'https://www.php.net/manual/es/mysqli.quickstart.connections.php', 1, '2024-10-31 04:24:04', NULL, 1),
(6, 'CUCHUPETAS', 'EL CUCHUPEtas SISIS', 'descarga_11zon.jpg', 'https://www.php.net/manual/es/mysqli.quickstart.connections.php', 1, '2024-10-31 17:21:41', NULL, 1),
(7, 'La roca', 'pelicula', 'yoi_11zon.png', 'https://www.php.net/manual/es/mysqli.quickstart.connections.php', 1, '2024-10-31 17:47:39', NULL, 1),
(8, 'adssadsadsad', 'safxzcsfsDAS', 'file.png', 'https://www.php.net/manual/es/mysqli.quickstart.connections.php', 1, '2024-10-31 17:49:19', NULL, 1),
(9, 'adssadsadsad', 'safxzcsfsDAS', 'file.png', 'https://www.php.net/manual/es/mysqli.quickstart.connections.php', 1, '2024-10-31 17:50:22', NULL, 1),
(10, 'adssadsadsad', 'safxzcsfsDAS', 'file.png', 'https://www.php.net/manual/es/mysqli.quickstart.connections.php', 1, '2024-10-31 17:50:54', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `pk_usuarios` smallint NOT NULL,
  `usuario` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `contrasena` varchar(64) COLLATE utf8mb4_spanish_ci NOT NULL,
  `correo` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `tel` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `tipo_usuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`pk_usuarios`, `usuario`, `contrasena`, `correo`, `tel`, `estatus`, `fecha_creacion`, `tipo_usuario`) VALUES
(2, 'HOLA', 'nosenose', 'nose@gmail.com', '6951240124', 1, '2024-10-30 15:29:44', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`pk_genero`);

--
-- Indices de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD PRIMARY KEY (`pk_pelicula`),
  ADD KEY `fk_usuarios` (`fk_usuarios`),
  ADD KEY `fk_genero` (`fk_genero`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`pk_usuarios`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `pk_genero` smallint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  MODIFY `pk_pelicula` smallint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `pk_usuarios` smallint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD CONSTRAINT `pelicula_ibfk_1` FOREIGN KEY (`fk_usuarios`) REFERENCES `usuarios` (`pk_usuarios`),
  ADD CONSTRAINT `pelicula_ibfk_2` FOREIGN KEY (`fk_genero`) REFERENCES `generos` (`pk_genero`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
