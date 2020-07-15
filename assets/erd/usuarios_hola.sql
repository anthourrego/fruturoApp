-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-07-2020 a las 03:29:30
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `fruturoapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `fk_tipo_documento` int(11) NOT NULL DEFAULT 0,
  `nro_documento` varchar(100) NOT NULL DEFAULT '0',
  `fk_tipo_persona` int(11) NOT NULL DEFAULT 0,
  `correo` text NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `password` longtext NOT NULL DEFAULT '',
  `fecha_nacimiento` date NOT NULL,
  `telefono` varchar(50) NOT NULL DEFAULT '',
  `fk_perfil` int(11) NOT NULL DEFAULT 1,
  `estado` int(1) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `confirmado` int(1) NOT NULL DEFAULT 0,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `codigo_recuperacion` varchar(100) NOT NULL,
  `codigo_activacion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `fk_tipo_documento`, `nro_documento`, `fk_tipo_persona`, `correo`, `nombres`, `apellidos`, `password`, `fecha_nacimiento`, `telefono`, `fk_perfil`, `estado`, `fecha_creacion`, `confirmado`, `fk_creador`, `codigo_recuperacion`, `codigo_activacion`) VALUES
(1, 1, '0', 1, 'admin@admin.com', 'admin', 'admin', '$2y$15$Ll8/j2MZi29SqBRmtaYKpeCZh/SL62QXqFVUFRBT2jdJIOwysG5OC', '1998-09-11', '3103587032', 1, 1, '2020-05-08 21:09:51', 1, 1, '', ''),
(2, 2, '1087996797', 1, 'coo@fruturo.one', 'Juan', 'Londono', '$2y$15$1x/MTYxwaSA7eZ22uL.aqezNyLNmKZE1txFaYGUJisIgtoU1S30Ve', '1988-02-19', '3184663375', 1, 1, '2020-06-02 09:12:18', 1, 0, '', ''),
(3, 1, '1225091213', 1, 'antho.120@hotmail.com', 'Anthony', 'Urrego', '$2y$15$BKcVFrenQeIx85cCAr8fPORch/MDxFPBHPyIzlJfpIAsD/kBeR4M.', '2002-06-03', '3103587032', 3, 1, '2020-06-03 11:03:53', 1, 0, '$2y$15$bkMjvs9N.SvCsGwudig0uu8EsXu9zMoMkMNdlOY1qWAnehmUo7402', ''),
(4, 1, '1234567', 1, 'jf.arenas30@ciaf.edu.co', 'Cordobita', 'Rasca', '$2y$15$6UXNA/N6JcuHUkVQlbQ.6.QHnWb7CnvgfcY87Br.2Q7JRwNaQTrse', '2002-06-06', '3013013011', 2, 1, '2020-06-06 13:04:54', 1, 0, '', ''),
(9, 1, '1088335957', 1, 'juanfa107@gmail.com', 'juan felipe', 'arenas moreno', '$2y$15$FOeRIdWbOtY8F9/3/HDtEOStGEbUrGbDssFTuxxssNlYJVV5j5gJy', '1993-07-19', '3115509915', 2, 1, '2020-07-12 20:39:39', 1, 0, '$2y$15$3F9NwPhX0kGcb7eJknbyeuVN5g0b0bBo.rilFG64gD7Jd1WSark36', '$2y$15$P49V5la9TbEOYhYlyKN9Ae46SJgA7t2AgFZPomGXLGNWPoOAlpkLO');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
