-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-07-2020 a las 16:57:16
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.3

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
-- Estructura de tabla para la tabla `certificaciones`
--

CREATE TABLE `certificaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL DEFAULT '0',
  `descripcion` text NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `certificaciones`
--

INSERT INTO `certificaciones` (`id`, `nombre`, `descripcion`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(1, 'Certificado 1', '', '2020-05-22 13:55:38', 1, 1),
(2, 'Certificado 2', '', '2020-05-22 13:57:20', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cosechas`
--

CREATE TABLE `cosechas` (
  `id` int(11) NOT NULL,
  `fk_producto` int(11) NOT NULL DEFAULT 0,
  `fk_productos_derivados` int(11) DEFAULT NULL,
  `fk_finca` int(11) NOT NULL DEFAULT 0,
  `volumen_total` float NOT NULL DEFAULT 0,
  `precio` decimal(10,0) NOT NULL DEFAULT 0,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cosechas`
--

INSERT INTO `cosechas` (`id`, `fk_producto`, `fk_productos_derivados`, `fk_finca`, `volumen_total`, `precio`, `fecha_inicio`, `fecha_final`, `estado`, `fecha_creacion`, `fk_creador`) VALUES
(1, 1, 0, 1, 456465, '9999999999', '2020-07-14', '2020-07-14', 1, '2020-07-14 22:00:31', 1),
(2, 1, 0, 1, 2321, '23321', '2020-07-14', '2020-07-14', 1, '2020-07-14 22:06:47', 1),
(3, 1, 0, 2, 5000, '40000', '2020-07-16', '2020-07-25', 1, '2020-07-14 22:15:10', 1),
(4, 3, 0, 3, 160, '30000', '2020-07-14', '2020-07-14', 1, '2020-07-14 22:19:23', 1),
(5, 4, 0, 2, 10000, '5000', '2020-07-14', '2020-07-14', 1, '2020-07-14 22:23:00', 1),
(6, 5, 0, 1, 4000, '5000', '2020-07-14', '2020-07-14', 1, '2020-07-14 22:23:30', 1),
(7, 6, 0, 3, 1000, '5000', '2020-07-14', '2020-07-14', 1, '2020-07-14 22:24:00', 1),
(8, 7, 0, 1, 5000, '2000', '2020-07-14', '2020-07-14', 1, '2020-07-14 22:33:41', 1),
(9, 8, 0, 2, 50, '5000', '2020-07-14', '2020-07-14', 1, '2020-07-14 22:34:15', 1),
(10, 9, 0, 3, 50, '4000', '2020-07-01', '2020-07-14', 1, '2020-07-14 22:36:58', 1),
(11, 15, 0, 1, 100, '4000', '2020-07-15', '2020-07-15', 1, '2020-07-15 21:03:56', 1),
(12, 14, 0, 2, 400, '4000', '2020-07-15', '2020-07-15', 1, '2020-07-15 21:04:19', 1),
(13, 12, 0, 3, 100, '7000', '2020-07-15', '2020-07-15', 1, '2020-07-15 21:05:01', 1),
(14, 10, 0, 3, 80, '6000', '2020-07-15', '2020-07-15', 1, '2020-07-15 21:06:14', 1),
(15, 12, 0, 1, 100, '5000', '2020-07-15', '2020-07-15', 1, '2020-07-15 21:06:41', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cosechas_certificaciones`
--

CREATE TABLE `cosechas_certificaciones` (
  `id` int(11) NOT NULL,
  `fk_cosecha` int(11) NOT NULL DEFAULT 0,
  `fk_certificacion` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cosechas_certificaciones`
--

INSERT INTO `cosechas_certificaciones` (`id`, `fk_cosecha`, `fk_certificacion`, `fecha_creacion`, `fk_creador`) VALUES
(1, 1, 1, '2020-07-14 00:00:00', 1),
(2, 2, 1, '2020-07-14 00:00:00', 1),
(3, 2, 1, '2020-07-13 00:00:00', 1),
(4, 2, 2, '2020-07-13 00:00:00', 1),
(5, 3, 1, '2020-07-14 00:00:00', 1),
(6, 3, 2, '2020-07-14 00:00:00', 1),
(7, 4, 1, '2020-07-14 00:00:00', 1),
(8, 4, 2, '2020-07-14 00:00:00', 1),
(9, 5, 1, '2020-07-14 00:00:00', 1),
(10, 6, 1, '2020-07-14 00:00:00', 1),
(11, 7, 1, '2020-07-14 00:00:00', 1),
(12, 7, 2, '2020-07-14 00:00:00', 1),
(13, 8, 1, '2020-07-14 00:00:00', 1),
(14, 9, 1, '2020-07-14 00:00:00', 1),
(15, 9, 2, '2020-07-14 00:00:00', 1),
(16, 10, 1, '2020-07-14 00:00:00', 1),
(17, 11, 1, '2020-07-15 00:00:00', 1),
(18, 12, 1, '2020-07-15 00:00:00', 1),
(19, 13, 1, '2020-07-15 00:00:00', 1),
(20, 14, 1, '2020-07-15 00:00:00', 1),
(21, 15, 1, '2020-07-15 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cosechas_productos_documentos`
--

CREATE TABLE `cosechas_productos_documentos` (
  `id` int(11) NOT NULL,
  `tipo` text NOT NULL,
  `ruta` text NOT NULL,
  `tipo_documento` int(11) DEFAULT NULL,
  `fk_producto` int(11) DEFAULT NULL,
  `fk_cosecha` int(11) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cosechas_productos_documentos`
--

INSERT INTO `cosechas_productos_documentos` (`id`, `tipo`, `ruta`, `tipo_documento`, `fk_producto`, `fk_cosecha`, `fecha_creacion`, `fk_creador`) VALUES
(1, 'jpg', 'almacenamiento/cosechas/1/0.jpg', NULL, NULL, 1, '2020-07-14 22:00:31', 1),
(2, 'jpg', 'almacenamiento/cosechas/2/0.jpg', NULL, NULL, 2, '2020-07-14 22:06:47', 1),
(3, 'jpg', 'almacenamiento/cosechas/2/1.jpg', NULL, NULL, 2, '2020-07-13 20:12:33', 1),
(4, 'jpg', 'almacenamiento/cosechas/2/2.jpg', NULL, NULL, 2, '2020-07-13 20:12:33', 1),
(5, 'jpg', 'almacenamiento/cosechas/3/0.jpg', NULL, NULL, 3, '2020-07-14 22:15:10', 1),
(6, 'jpg', 'almacenamiento/cosechas/4/0.jpg', NULL, NULL, 4, '2020-07-14 22:19:23', 1),
(7, 'jpg', 'almacenamiento/cosechas/4/1.jpg', NULL, NULL, 4, '2020-07-14 22:19:23', 1),
(8, 'jpg', 'almacenamiento/cosechas/5/0.jpg', NULL, NULL, 5, '2020-07-14 22:23:00', 1),
(9, 'jpg', 'almacenamiento/cosechas/5/1.jpg', NULL, NULL, 5, '2020-07-14 22:23:00', 1),
(10, 'jpg', 'almacenamiento/cosechas/6/0.jpg', NULL, NULL, 6, '2020-07-14 22:23:30', 1),
(11, 'jpg', 'almacenamiento/cosechas/6/1.jpg', NULL, NULL, 6, '2020-07-14 22:23:30', 1),
(12, 'jpg', 'almacenamiento/cosechas/7/0.jpg', NULL, NULL, 7, '2020-07-14 22:24:00', 1),
(13, 'jpg', 'almacenamiento/cosechas/8/0.jpg', NULL, NULL, 8, '2020-07-14 22:33:41', 1),
(14, 'jpg', 'almacenamiento/cosechas/8/1.jpg', NULL, NULL, 8, '2020-07-14 22:33:41', 1),
(15, 'jpeg', 'almacenamiento/cosechas/9/0.jpeg', NULL, NULL, 9, '2020-07-14 22:34:15', 1),
(16, 'jpg', 'almacenamiento/cosechas/9/1.jpg', NULL, NULL, 9, '2020-07-14 22:34:15', 1),
(17, 'jpg', 'almacenamiento/cosechas/10/0.jpg', NULL, NULL, 10, '2020-07-14 22:36:58', 1),
(18, 'jpg', 'almacenamiento/cosechas/10/1.jpg', NULL, NULL, 10, '2020-07-14 22:36:58', 1),
(19, 'jpg', 'almacenamiento/cosechas/11/0.jpg', NULL, NULL, 11, '2020-07-15 21:03:56', 1),
(20, 'jpg', 'almacenamiento/cosechas/12/0.jpg', NULL, NULL, 12, '2020-07-15 21:04:19', 1),
(21, 'jpg', 'almacenamiento/cosechas/13/0.jpg', NULL, NULL, 13, '2020-07-15 21:05:01', 1),
(22, 'jpg', 'almacenamiento/cosechas/14/0.jpg', NULL, NULL, 14, '2020-07-15 21:06:14', 1),
(23, 'jpg', 'almacenamiento/cosechas/14/1.jpg', NULL, NULL, 14, '2020-07-15 21:06:14', 1),
(24, 'jpg', 'almacenamiento/cosechas/15/0.jpg', NULL, NULL, 15, '2020-07-15 21:06:41', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cosecha_oferta`
--

CREATE TABLE `cosecha_oferta` (
  `id` int(11) NOT NULL,
  `fk_cosecha` int(11) NOT NULL DEFAULT 0,
  `mensaje` text NOT NULL DEFAULT '0',
  `oferta` decimal(10,0) NOT NULL DEFAULT 0,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 1,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id`, `nombre`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(1, 'Amazonas', '2020-05-08 00:33:56', 1, 1),
(2, 'Antioquia', '2020-05-08 00:33:56', 1, 1),
(3, 'Arauca', '2020-05-08 00:33:56', 1, 1),
(4, 'Atlántico', '2020-05-08 00:33:56', 1, 1),
(5, 'Bolívar', '2020-05-08 00:33:56', 1, 1),
(6, 'Boyacá', '2020-05-08 00:33:56', 1, 1),
(7, 'Caldas', '2020-05-08 00:33:56', 1, 1),
(8, 'Caquetá', '2020-05-08 00:33:56', 1, 1),
(9, 'Casanare', '2020-05-08 00:33:56', 1, 1),
(10, 'Cauca', '2020-05-08 00:33:56', 1, 1),
(11, 'Cesar', '2020-05-08 00:33:56', 1, 1),
(12, 'Chocó', '2020-05-08 00:33:56', 1, 1),
(13, 'Córdoba', '2020-05-08 00:33:56', 1, 1),
(14, 'Cundinamarca', '2020-05-08 00:33:56', 1, 1),
(15, 'Güainia', '2020-05-08 00:33:56', 1, 1),
(16, 'Guaviare', '2020-05-08 00:33:56', 1, 1),
(17, 'Huila', '2020-05-08 00:33:56', 1, 1),
(18, 'La Guajira', '2020-05-08 00:33:56', 1, 1),
(19, 'Magdalena', '2020-05-08 00:33:56', 1, 1),
(20, 'Meta', '2020-05-08 00:33:56', 1, 1),
(21, 'Nariño', '2020-05-08 00:33:56', 1, 1),
(22, 'Norte de Santander', '2020-05-08 00:33:56', 1, 1),
(23, 'Putumayo', '2020-05-08 00:33:56', 1, 1),
(24, 'Quindio', '2020-05-08 00:33:56', 1, 1),
(25, 'Risaralda', '2020-05-08 00:33:56', 1, 1),
(26, 'San Andrés y Providencia', '2020-05-08 00:33:56', 1, 1),
(27, 'Santander', '2020-05-08 00:33:56', 1, 1),
(28, 'Sucre', '2020-05-08 00:33:56', 1, 1),
(29, 'Tolima', '2020-05-08 00:33:56', 1, 1),
(30, 'Valle del Cauca', '2020-05-08 00:33:56', 1, 1),
(31, 'Vaupés', '2020-05-08 00:33:56', 1, 1),
(32, 'Vichada', '2020-05-08 00:33:56', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fincas`
--

CREATE TABLE `fincas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `fk_municipio` int(11) NOT NULL DEFAULT 0,
  `direccion` text NOT NULL,
  `hectareas` float DEFAULT NULL,
  `registro_ica` mediumtext DEFAULT NULL,
  `fk_usuario` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `fk_finca_tipo` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `fincas`
--

INSERT INTO `fincas` (`id`, `nombre`, `fk_municipio`, `direccion`, `hectareas`, `registro_ica`, `fk_usuario`, `fecha_creacion`, `estado`, `fk_finca_tipo`) VALUES
(1, '21321', 1, '231312', 321231, NULL, 1, '2020-06-25 12:12:11', 1, 1),
(2, 'las bailarinas', 888, 'mz e casa 34 la chimba', 5000, '123124', 1, '2020-07-14 22:14:10', 1, 1),
(3, 'la esmeralda', 1071, 'la mar&iacute;a valle', 50, '123', 1, '2020-07-14 22:17:23', 1, 1),
(4, 'Consumer', 888, 'Carrera 35 Nro 72-05', NULL, NULL, 1, '2020-07-20 00:01:17', 1, 2),
(5, 'Consumer 2', 347, 'funca', 0, '', 1, '2020-07-20 00:05:25', 1, 2),
(6, 'pruebas', 351, 'funcara', NULL, NULL, 1, '2020-07-20 00:09:56', 1, 2),
(7, 'La casita', 351, 'funcara\r\n', 4500, NULL, 1, '2020-07-20 00:10:26', 1, 1),
(8, 'La quinta', 329, 'La finquita de cuqui', 8700, '45000fsdfsa', 1, '2020-07-20 00:10:54', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fincas_tipos`
--

CREATE TABLE `fincas_tipos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `fincas_tipos`
--

INSERT INTO `fincas_tipos` (`id`, `nombre`, `estado`, `fk_creador`, `fecha_creacion`) VALUES
(1, 'Frescos', 1, 1, '2020-07-16 22:01:39'),
(2, 'Procesados', 1, 1, '2020-07-16 22:30:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `nombre_tabla` varchar(100) NOT NULL,
  `id_registro` int(11) NOT NULL DEFAULT 0,
  `accion` text NOT NULL DEFAULT '0',
  `fk_usuario` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `nombre_tabla`, `id_registro`, `accion`, `fk_usuario`, `fecha_creacion`) VALUES
(1, 'cosechas_productos_documentos', 1, 'Creacion de fotos de consecha con id: 1', 1, '2020-07-14 22:00:31'),
(2, 'cosechas', 1, 'Se crea la cosecha', 1, '2020-07-14 22:00:31'),
(3, 'cosechas_certificaciones', 1, 'Se crea la cosecha con el certificado', 1, '2020-07-14 22:00:32'),
(4, 'cosechas_productos_documentos', 2, 'Creacion de fotos de consecha con id: 2', 1, '2020-07-14 22:06:47'),
(5, 'cosechas', 2, 'Se crea la cosecha', 1, '2020-07-14 22:06:47'),
(6, 'cosechas_certificaciones', 2, 'Se crea la cosecha con el certificado', 1, '2020-07-14 22:06:47'),
(7, 'perfiles', 2, 'Se inhabilita el perfil Productor', 1, '2020-07-14 22:13:49'),
(8, 'perfiles', 3, 'Se edita el perfil Usuario', 1, '2020-07-14 22:14:04'),
(9, 'usuarios', 2, 'Se activa usuario', 2, '2020-07-14 22:23:00'),
(10, 'modulos', 15, 'Se ha creado el módulo predios_tipos', 1, '2020-07-16 21:30:56'),
(11, 'usuarios_modulos', 17, 'El permiso se ha creado', 1, '2020-07-16 21:37:05'),
(12, 'fincas_tipos', 1, 'Se crea el finca tipo Frescos', 1, '2020-07-16 22:01:39'),
(13, 'fincas_tipos', 1, 'Se edita el tipo predios Frescos1', 1, '2020-07-16 22:13:28'),
(14, 'fincas_tipos', 1, 'Se edita el tipo predios Frescos', 1, '2020-07-16 22:13:32'),
(15, 'usuarios_modulos', 18, 'El permiso se ha creado', 1, '2020-07-16 22:25:42'),
(16, 'usuarios_modulos', 19, 'El permiso se ha creado', 1, '2020-07-16 22:25:47'),
(17, 'fincas_tipos', 1, 'Se inhabilita el fincas_tipos Frescos', 1, '2020-07-16 22:30:25'),
(18, 'fincas_tipos', 1, 'Se habilita el fincas_tipos Frescos', 1, '2020-07-16 22:30:38'),
(19, 'fincas_tipos', 2, 'Se crea el predio tipo Procesados', 1, '2020-07-16 22:30:59'),
(20, 'fincas_tipos', 2, 'Se edita el tipo predios Procesados3', 1, '2020-07-16 22:31:47'),
(21, 'fincas_tipos', 2, 'Se edita el tipo predios Procesados', 1, '2020-07-16 22:31:50'),
(22, 'fincas', 4, 'Se crea la finca Consumer', 1, '2020-07-20 00:01:17'),
(23, 'fincas', 5, 'Se crea la finca Consumer 2', 1, '2020-07-20 00:05:25'),
(24, 'fincas', 6, 'Se crea la finca pruebas', 1, '2020-07-20 00:09:56'),
(25, 'fincas', 7, 'Se crea la finca La casita', 1, '2020-07-20 00:10:26'),
(26, 'fincas', 8, 'Se crea la finca La quinta', 1, '2020-07-20 00:10:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `tag` mediumtext NOT NULL DEFAULT '0',
  `icono` mediumtext NOT NULL DEFAULT '0',
  `ruta` text NOT NULL,
  `fk_modulo_tipo` int(11) NOT NULL DEFAULT 0,
  `fk_modulo` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `nombre`, `tag`, `icono`, `ruta`, `fk_modulo_tipo`, `fk_modulo`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(1, 'usuarios', 'Usuarios', 'fas fa-users-cog', 'usuarios/usuarios/', 1, 0, '2020-05-11 20:12:04', 1, 1),
(2, 'modulos', 'Modulos', 'fab fa-modx', 'modulos/', 1, 0, '2020-05-11 20:13:05', 1, 1),
(3, 'productos', 'Lista Productos', 'fas fa-apple-alt', '#', 1, 0, '2020-05-21 22:56:20', 1, 1),
(4, 'certificados', 'Certificados', 'fas fa-certificate', 'certificados/', 1, 0, '2020-05-22 10:58:16', 1, 1),
(5, 'usuarios_tipo_documento', 'Tipo documento', 'fas fa-id-card', 'usuarios/tipo_documento/', 1, 1, '2020-05-28 23:29:18', 1, 1),
(6, 'usuarios_lista', 'Registros', 'fas fa-users', 'usuarios/registros/', 1, 1, '2020-05-28 23:53:39', 1, 1),
(7, 'usuarios_tipo_persona', 'Tipo persona', 'far fa-user', 'usuarios/tipo_persona/', 1, 1, '2020-05-30 00:29:39', 1, 1),
(8, 'usuarios_perfiles', 'Perfiles', 'fas fa-user-tag', 'usuarios/perfiles/', 1, 1, '2020-05-30 00:51:57', 1, 1),
(9, 'ofertas', 'Ofertas', 'fas fa-award', 'ofertas/', 1, 0, '2020-06-05 12:34:22', 1, 1),
(10, 'provincias', 'Provincias', 'fa fa-building', 'provincias/', 1, 0, '2020-06-22 13:05:18', 1, 1),
(11, 'provincias_departamentos', 'Departamentos', 'fas fa-city', 'provincias/departamentos/', 1, 10, '2020-06-22 13:05:54', 1, 1),
(12, 'provincias_municipios', 'Municipios', 'fas fa-city', 'provincias/municipios/', 1, 10, '2020-06-22 13:06:29', 1, 1),
(13, 'productos_1', 'Productos', 'fas fa-carrot', 'productos/productos', 1, 3, '2020-07-06 20:20:07', 1, 1),
(14, 'productos_derivados', 'Derivados', 'fas fa-seedling', 'productos/derivados', 1, 3, '2020-07-06 20:21:54', 1, 1),
(15, 'predios_tipos', 'Tipos predios', 'fas fa-home', 'predios_tipos', 1, 0, '2020-07-16 21:30:56', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo_tipo`
--

CREATE TABLE `modulo_tipo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `modulo_tipo`
--

INSERT INTO `modulo_tipo` (`id`, `nombre`, `fecha_creacion`, `estado`, `fk_creador`) VALUES
(1, 'Modulo', '2020-05-11 10:44:37', 1, 1),
(2, 'Permiso', '2020-05-11 10:44:37', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fk_departamento` int(10) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 1,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id`, `nombre`, `fk_departamento`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(1, 'Leticia', 1, '2020-05-08 00:33:56', 1, 1),
(2, 'Puerto Nariño', 1, '2020-05-08 00:33:56', 1, 1),
(3, 'Abejorral', 2, '2020-05-08 00:33:56', 1, 1),
(4, 'Abriaquí', 2, '2020-05-08 00:33:56', 1, 1),
(5, 'Alejandria', 2, '2020-05-08 00:33:56', 1, 1),
(6, 'Amagá', 2, '2020-05-08 00:33:56', 1, 1),
(7, 'Amalfi', 2, '2020-05-08 00:33:56', 1, 1),
(8, 'Andes', 2, '2020-05-08 00:33:56', 1, 1),
(9, 'Angelópolis', 2, '2020-05-08 00:33:56', 1, 1),
(10, 'Angostura', 2, '2020-05-08 00:33:56', 1, 1),
(11, 'Anorí', 2, '2020-05-08 00:33:56', 1, 1),
(12, 'Anzá', 2, '2020-05-08 00:33:56', 1, 1),
(13, 'Apartadó', 2, '2020-05-08 00:33:56', 1, 1),
(14, 'Arboletes', 2, '2020-05-08 00:33:56', 1, 1),
(15, 'Argelia', 2, '2020-05-08 00:33:56', 1, 1),
(16, 'Armenia', 2, '2020-05-08 00:33:56', 1, 1),
(17, 'Barbosa', 2, '2020-05-08 00:33:56', 1, 1),
(18, 'Bello', 2, '2020-05-08 00:33:56', 1, 1),
(19, 'Belmira', 2, '2020-05-08 00:33:56', 1, 1),
(20, 'Betania', 2, '2020-05-08 00:33:56', 1, 1),
(21, 'Betulia', 2, '2020-05-08 00:33:56', 1, 1),
(22, 'Bolívar', 2, '2020-05-08 00:33:56', 1, 1),
(23, 'Briceño', 2, '2020-05-08 00:33:56', 1, 1),
(24, 'Burítica', 2, '2020-05-08 00:33:56', 1, 1),
(25, 'Caicedo', 2, '2020-05-08 00:33:56', 1, 1),
(26, 'Caldas', 2, '2020-05-08 00:33:56', 1, 1),
(27, 'Campamento', 2, '2020-05-08 00:33:56', 1, 1),
(28, 'Caracolí', 2, '2020-05-08 00:33:56', 1, 1),
(29, 'Caramanta', 2, '2020-05-08 00:33:56', 1, 1),
(30, 'Carepa', 2, '2020-05-08 00:33:56', 1, 1),
(31, 'Carmen de Viboral', 2, '2020-05-08 00:33:56', 1, 1),
(32, 'Carolina', 2, '2020-05-08 00:33:56', 1, 1),
(33, 'Caucasia', 2, '2020-05-08 00:33:56', 1, 1),
(34, 'Cañasgordas', 2, '2020-05-08 00:33:56', 1, 1),
(35, 'Chigorodó', 2, '2020-05-08 00:33:56', 1, 1),
(36, 'Cisneros', 2, '2020-05-08 00:33:56', 1, 1),
(37, 'Cocorná', 2, '2020-05-08 00:33:56', 1, 1),
(38, 'Concepción', 2, '2020-05-08 00:33:56', 1, 1),
(39, 'Concordia', 2, '2020-05-08 00:33:56', 1, 1),
(40, 'Copacabana', 2, '2020-05-08 00:33:56', 1, 1),
(41, 'Cáceres', 2, '2020-05-08 00:33:56', 1, 1),
(42, 'Dabeiba', 2, '2020-05-08 00:33:56', 1, 1),
(43, 'Don Matías', 2, '2020-05-08 00:33:56', 1, 1),
(44, 'Ebéjico', 2, '2020-05-08 00:33:56', 1, 1),
(45, 'El Bagre', 2, '2020-05-08 00:33:56', 1, 1),
(46, 'Entrerríos', 2, '2020-05-08 00:33:56', 1, 1),
(47, 'Envigado', 2, '2020-05-08 00:33:56', 1, 1),
(48, 'Fredonia', 2, '2020-05-08 00:33:56', 1, 1),
(49, 'Frontino', 2, '2020-05-08 00:33:56', 1, 1),
(50, 'Giraldo', 2, '2020-05-08 00:33:56', 1, 1),
(51, 'Girardota', 2, '2020-05-08 00:33:56', 1, 1),
(52, 'Granada', 2, '2020-05-08 00:33:56', 1, 1),
(53, 'Guadalupe', 2, '2020-05-08 00:33:56', 1, 1),
(54, 'Guarne', 2, '2020-05-08 00:33:56', 1, 1),
(55, 'Guatapé', 2, '2020-05-08 00:33:56', 1, 1),
(56, 'Gómez Plata', 2, '2020-05-08 00:33:56', 1, 1),
(57, 'Heliconia', 2, '2020-05-08 00:33:56', 1, 1),
(58, 'Hispania', 2, '2020-05-08 00:33:56', 1, 1),
(59, 'Itagüí', 2, '2020-05-08 00:33:56', 1, 1),
(60, 'Ituango', 2, '2020-05-08 00:33:56', 1, 1),
(61, 'Jardín', 2, '2020-05-08 00:33:56', 1, 1),
(62, 'Jericó', 2, '2020-05-08 00:33:56', 1, 1),
(63, 'La Ceja', 2, '2020-05-08 00:33:56', 1, 1),
(64, 'La Estrella', 2, '2020-05-08 00:33:56', 1, 1),
(65, 'La Pintada', 2, '2020-05-08 00:33:56', 1, 1),
(66, 'La Unión', 2, '2020-05-08 00:33:56', 1, 1),
(67, 'Liborina', 2, '2020-05-08 00:33:56', 1, 1),
(68, 'Maceo', 2, '2020-05-08 00:33:56', 1, 1),
(69, 'Marinilla', 2, '2020-05-08 00:33:56', 1, 1),
(70, 'Medellín', 2, '2020-05-08 00:33:56', 1, 1),
(71, 'Montebello', 2, '2020-05-08 00:33:56', 1, 1),
(72, 'Murindó', 2, '2020-05-08 00:33:56', 1, 1),
(73, 'Mutatá', 2, '2020-05-08 00:33:56', 1, 1),
(74, 'Nariño', 2, '2020-05-08 00:33:56', 1, 1),
(75, 'Nechí', 2, '2020-05-08 00:33:56', 1, 1),
(76, 'Necoclí', 2, '2020-05-08 00:33:56', 1, 1),
(77, 'Olaya', 2, '2020-05-08 00:33:56', 1, 1),
(78, 'Peque', 2, '2020-05-08 00:33:56', 1, 1),
(79, 'Peñol', 2, '2020-05-08 00:33:56', 1, 1),
(80, 'Pueblorrico', 2, '2020-05-08 00:33:56', 1, 1),
(81, 'Puerto Berrío', 2, '2020-05-08 00:33:56', 1, 1),
(82, 'Puerto Nare', 2, '2020-05-08 00:33:56', 1, 1),
(83, 'Puerto Triunfo', 2, '2020-05-08 00:33:56', 1, 1),
(84, 'Remedios', 2, '2020-05-08 00:33:56', 1, 1),
(85, 'Retiro', 2, '2020-05-08 00:33:56', 1, 1),
(86, 'Ríonegro', 2, '2020-05-08 00:33:56', 1, 1),
(87, 'Sabanalarga', 2, '2020-05-08 00:33:56', 1, 1),
(88, 'Sabaneta', 2, '2020-05-08 00:33:56', 1, 1),
(89, 'Salgar', 2, '2020-05-08 00:33:56', 1, 1),
(90, 'San Andrés de Cuerquía', 2, '2020-05-08 00:33:56', 1, 1),
(91, 'San Carlos', 2, '2020-05-08 00:33:56', 1, 1),
(92, 'San Francisco', 2, '2020-05-08 00:33:56', 1, 1),
(93, 'San Jerónimo', 2, '2020-05-08 00:33:56', 1, 1),
(94, 'San José de Montaña', 2, '2020-05-08 00:33:56', 1, 1),
(95, 'San Juan de Urabá', 2, '2020-05-08 00:33:56', 1, 1),
(96, 'San Luís', 2, '2020-05-08 00:33:56', 1, 1),
(97, 'San Pedro', 2, '2020-05-08 00:33:56', 1, 1),
(98, 'San Pedro de Urabá', 2, '2020-05-08 00:33:56', 1, 1),
(99, 'San Rafael', 2, '2020-05-08 00:33:56', 1, 1),
(100, 'San Roque', 2, '2020-05-08 00:33:56', 1, 1),
(101, 'San Vicente', 2, '2020-05-08 00:33:56', 1, 1),
(102, 'Santa Bárbara', 2, '2020-05-08 00:33:56', 1, 1),
(103, 'Santa Fé de Antioquia', 2, '2020-05-08 00:33:56', 1, 1),
(104, 'Santa Rosa de Osos', 2, '2020-05-08 00:33:56', 1, 1),
(105, 'Santo Domingo', 2, '2020-05-08 00:33:56', 1, 1),
(106, 'Santuario', 2, '2020-05-08 00:33:56', 1, 1),
(107, 'Segovia', 2, '2020-05-08 00:33:56', 1, 1),
(108, 'Sonsón', 2, '2020-05-08 00:33:56', 1, 1),
(109, 'Sopetrán', 2, '2020-05-08 00:33:56', 1, 1),
(110, 'Tarazá', 2, '2020-05-08 00:33:56', 1, 1),
(111, 'Tarso', 2, '2020-05-08 00:33:56', 1, 1),
(112, 'Titiribí', 2, '2020-05-08 00:33:56', 1, 1),
(113, 'Toledo', 2, '2020-05-08 00:33:56', 1, 1),
(114, 'Turbo', 2, '2020-05-08 00:33:56', 1, 1),
(115, 'Támesis', 2, '2020-05-08 00:33:56', 1, 1),
(116, 'Uramita', 2, '2020-05-08 00:33:56', 1, 1),
(117, 'Urrao', 2, '2020-05-08 00:33:56', 1, 1),
(118, 'Valdivia', 2, '2020-05-08 00:33:56', 1, 1),
(119, 'Valparaiso', 2, '2020-05-08 00:33:56', 1, 1),
(120, 'Vegachí', 2, '2020-05-08 00:33:56', 1, 1),
(121, 'Venecia', 2, '2020-05-08 00:33:56', 1, 1),
(122, 'Vigía del Fuerte', 2, '2020-05-08 00:33:56', 1, 1),
(123, 'Yalí', 2, '2020-05-08 00:33:56', 1, 1),
(124, 'Yarumal', 2, '2020-05-08 00:33:56', 1, 1),
(125, 'Yolombó', 2, '2020-05-08 00:33:56', 1, 1),
(126, 'Yondó (Casabe)', 2, '2020-05-08 00:33:56', 1, 1),
(127, 'Zaragoza', 2, '2020-05-08 00:33:56', 1, 1),
(128, 'Arauca', 3, '2020-05-08 00:33:56', 1, 1),
(129, 'Arauquita', 3, '2020-05-08 00:33:56', 1, 1),
(130, 'Cravo Norte', 3, '2020-05-08 00:33:56', 1, 1),
(131, 'Fortúl', 3, '2020-05-08 00:33:56', 1, 1),
(132, 'Puerto Rondón', 3, '2020-05-08 00:33:56', 1, 1),
(133, 'Saravena', 3, '2020-05-08 00:33:56', 1, 1),
(134, 'Tame', 3, '2020-05-08 00:33:56', 1, 1),
(135, 'Baranoa', 4, '2020-05-08 00:33:56', 1, 1),
(136, 'Barranquilla', 4, '2020-05-08 00:33:56', 1, 1),
(137, 'Campo de la Cruz', 4, '2020-05-08 00:33:56', 1, 1),
(138, 'Candelaria', 4, '2020-05-08 00:33:56', 1, 1),
(139, 'Galapa', 4, '2020-05-08 00:33:56', 1, 1),
(140, 'Juan de Acosta', 4, '2020-05-08 00:33:56', 1, 1),
(141, 'Luruaco', 4, '2020-05-08 00:33:56', 1, 1),
(142, 'Malambo', 4, '2020-05-08 00:33:56', 1, 1),
(143, 'Manatí', 4, '2020-05-08 00:33:56', 1, 1),
(144, 'Palmar de Varela', 4, '2020-05-08 00:33:56', 1, 1),
(145, 'Piojo', 4, '2020-05-08 00:33:56', 1, 1),
(146, 'Polonuevo', 4, '2020-05-08 00:33:56', 1, 1),
(147, 'Ponedera', 4, '2020-05-08 00:33:56', 1, 1),
(148, 'Puerto Colombia', 4, '2020-05-08 00:33:56', 1, 1),
(149, 'Repelón', 4, '2020-05-08 00:33:56', 1, 1),
(150, 'Sabanagrande', 4, '2020-05-08 00:33:56', 1, 1),
(151, 'Sabanalarga', 4, '2020-05-08 00:33:56', 1, 1),
(152, 'Santa Lucía', 4, '2020-05-08 00:33:56', 1, 1),
(153, 'Santo Tomás', 4, '2020-05-08 00:33:56', 1, 1),
(154, 'Soledad', 4, '2020-05-08 00:33:56', 1, 1),
(155, 'Suan', 4, '2020-05-08 00:33:56', 1, 1),
(156, 'Tubará', 4, '2020-05-08 00:33:56', 1, 1),
(157, 'Usiacuri', 4, '2020-05-08 00:33:56', 1, 1),
(158, 'Achí', 5, '2020-05-08 00:33:56', 1, 1),
(159, 'Altos del Rosario', 5, '2020-05-08 00:33:56', 1, 1),
(160, 'Arenal', 5, '2020-05-08 00:33:56', 1, 1),
(161, 'Arjona', 5, '2020-05-08 00:33:56', 1, 1),
(162, 'Arroyohondo', 5, '2020-05-08 00:33:56', 1, 1),
(163, 'Barranco de Loba', 5, '2020-05-08 00:33:56', 1, 1),
(164, 'Calamar', 5, '2020-05-08 00:33:56', 1, 1),
(165, 'Cantagallo', 5, '2020-05-08 00:33:56', 1, 1),
(166, 'Cartagena', 5, '2020-05-08 00:33:56', 1, 1),
(167, 'Cicuco', 5, '2020-05-08 00:33:56', 1, 1),
(168, 'Clemencia', 5, '2020-05-08 00:33:56', 1, 1),
(169, 'Córdoba', 5, '2020-05-08 00:33:56', 1, 1),
(170, 'El Carmen de Bolívar', 5, '2020-05-08 00:33:56', 1, 1),
(171, 'El Guamo', 5, '2020-05-08 00:33:56', 1, 1),
(172, 'El Peñon', 5, '2020-05-08 00:33:56', 1, 1),
(173, 'Hatillo de Loba', 5, '2020-05-08 00:33:56', 1, 1),
(174, 'Magangué', 5, '2020-05-08 00:33:56', 1, 1),
(175, 'Mahates', 5, '2020-05-08 00:33:56', 1, 1),
(176, 'Margarita', 5, '2020-05-08 00:33:56', 1, 1),
(177, 'María la Baja', 5, '2020-05-08 00:33:56', 1, 1),
(178, 'Mompós', 5, '2020-05-08 00:33:56', 1, 1),
(179, 'Montecristo', 5, '2020-05-08 00:33:56', 1, 1),
(180, 'Morales', 5, '2020-05-08 00:33:56', 1, 1),
(181, 'Norosí', 5, '2020-05-08 00:33:56', 1, 1),
(182, 'Pinillos', 5, '2020-05-08 00:33:56', 1, 1),
(183, 'Regidor', 5, '2020-05-08 00:33:56', 1, 1),
(184, 'Río Viejo', 5, '2020-05-08 00:33:56', 1, 1),
(185, 'San Cristobal', 5, '2020-05-08 00:33:56', 1, 1),
(186, 'San Estanislao', 5, '2020-05-08 00:33:56', 1, 1),
(187, 'San Fernando', 5, '2020-05-08 00:33:56', 1, 1),
(188, 'San Jacinto', 5, '2020-05-08 00:33:56', 1, 1),
(189, 'San Jacinto del Cauca', 5, '2020-05-08 00:33:56', 1, 1),
(190, 'San Juan de Nepomuceno', 5, '2020-05-08 00:33:56', 1, 1),
(191, 'San Martín de Loba', 5, '2020-05-08 00:33:56', 1, 1),
(192, 'San Pablo', 5, '2020-05-08 00:33:56', 1, 1),
(193, 'Santa Catalina', 5, '2020-05-08 00:33:56', 1, 1),
(194, 'Santa Rosa ', 5, '2020-05-08 00:33:56', 1, 1),
(195, 'Santa Rosa del Sur', 5, '2020-05-08 00:33:56', 1, 1),
(196, 'Simití', 5, '2020-05-08 00:33:56', 1, 1),
(197, 'Soplaviento', 5, '2020-05-08 00:33:56', 1, 1),
(198, 'Talaigua Nuevo', 5, '2020-05-08 00:33:56', 1, 1),
(199, 'Tiquisio (Puerto Rico)', 5, '2020-05-08 00:33:56', 1, 1),
(200, 'Turbaco', 5, '2020-05-08 00:33:56', 1, 1),
(201, 'Turbaná', 5, '2020-05-08 00:33:56', 1, 1),
(202, 'Villanueva', 5, '2020-05-08 00:33:56', 1, 1),
(203, 'Zambrano', 5, '2020-05-08 00:33:56', 1, 1),
(204, 'Almeida', 6, '2020-05-08 00:33:56', 1, 1),
(205, 'Aquitania', 6, '2020-05-08 00:33:56', 1, 1),
(206, 'Arcabuco', 6, '2020-05-08 00:33:56', 1, 1),
(207, 'Belén', 6, '2020-05-08 00:33:56', 1, 1),
(208, 'Berbeo', 6, '2020-05-08 00:33:56', 1, 1),
(209, 'Beteitiva', 6, '2020-05-08 00:33:56', 1, 1),
(210, 'Boavita', 6, '2020-05-08 00:33:56', 1, 1),
(211, 'Boyacá', 6, '2020-05-08 00:33:56', 1, 1),
(212, 'Briceño', 6, '2020-05-08 00:33:56', 1, 1),
(213, 'Buenavista', 6, '2020-05-08 00:33:56', 1, 1),
(214, 'Busbanza', 6, '2020-05-08 00:33:56', 1, 1),
(215, 'Caldas', 6, '2020-05-08 00:33:56', 1, 1),
(216, 'Campohermoso', 6, '2020-05-08 00:33:56', 1, 1),
(217, 'Cerinza', 6, '2020-05-08 00:33:56', 1, 1),
(218, 'Chinavita', 6, '2020-05-08 00:33:56', 1, 1),
(219, 'Chiquinquirá', 6, '2020-05-08 00:33:56', 1, 1),
(220, 'Chiscas', 6, '2020-05-08 00:33:56', 1, 1),
(221, 'Chita', 6, '2020-05-08 00:33:56', 1, 1),
(222, 'Chitaraque', 6, '2020-05-08 00:33:56', 1, 1),
(223, 'Chivatá', 6, '2020-05-08 00:33:56', 1, 1),
(224, 'Chíquiza', 6, '2020-05-08 00:33:56', 1, 1),
(225, 'Chívor', 6, '2020-05-08 00:33:56', 1, 1),
(226, 'Ciénaga', 6, '2020-05-08 00:33:56', 1, 1),
(227, 'Coper', 6, '2020-05-08 00:33:56', 1, 1),
(228, 'Corrales', 6, '2020-05-08 00:33:56', 1, 1),
(229, 'Covarachía', 6, '2020-05-08 00:33:56', 1, 1),
(230, 'Cubará', 6, '2020-05-08 00:33:56', 1, 1),
(231, 'Cucaita', 6, '2020-05-08 00:33:56', 1, 1),
(232, 'Cuitiva', 6, '2020-05-08 00:33:56', 1, 1),
(233, 'Cómbita', 6, '2020-05-08 00:33:56', 1, 1),
(234, 'Duitama', 6, '2020-05-08 00:33:56', 1, 1),
(235, 'El Cocuy', 6, '2020-05-08 00:33:56', 1, 1),
(236, 'El Espino', 6, '2020-05-08 00:33:56', 1, 1),
(237, 'Firavitoba', 6, '2020-05-08 00:33:56', 1, 1),
(238, 'Floresta', 6, '2020-05-08 00:33:56', 1, 1),
(239, 'Gachantivá', 6, '2020-05-08 00:33:56', 1, 1),
(240, 'Garagoa', 6, '2020-05-08 00:33:56', 1, 1),
(241, 'Guacamayas', 6, '2020-05-08 00:33:56', 1, 1),
(242, 'Guateque', 6, '2020-05-08 00:33:56', 1, 1),
(243, 'Guayatá', 6, '2020-05-08 00:33:56', 1, 1),
(244, 'Guicán', 6, '2020-05-08 00:33:56', 1, 1),
(245, 'Gámeza', 6, '2020-05-08 00:33:56', 1, 1),
(246, 'Izá', 6, '2020-05-08 00:33:56', 1, 1),
(247, 'Jenesano', 6, '2020-05-08 00:33:56', 1, 1),
(248, 'Jericó', 6, '2020-05-08 00:33:56', 1, 1),
(249, 'La Capilla', 6, '2020-05-08 00:33:56', 1, 1),
(250, 'La Uvita', 6, '2020-05-08 00:33:56', 1, 1),
(251, 'La Victoria', 6, '2020-05-08 00:33:56', 1, 1),
(252, 'Labranzagrande', 6, '2020-05-08 00:33:56', 1, 1),
(253, 'Macanal', 6, '2020-05-08 00:33:56', 1, 1),
(254, 'Maripí', 6, '2020-05-08 00:33:56', 1, 1),
(255, 'Miraflores', 6, '2020-05-08 00:33:56', 1, 1),
(256, 'Mongua', 6, '2020-05-08 00:33:56', 1, 1),
(257, 'Monguí', 6, '2020-05-08 00:33:56', 1, 1),
(258, 'Moniquirá', 6, '2020-05-08 00:33:56', 1, 1),
(259, 'Motavita', 6, '2020-05-08 00:33:56', 1, 1),
(260, 'Muzo', 6, '2020-05-08 00:33:56', 1, 1),
(261, 'Nobsa', 6, '2020-05-08 00:33:56', 1, 1),
(262, 'Nuevo Colón', 6, '2020-05-08 00:33:56', 1, 1),
(263, 'Oicatá', 6, '2020-05-08 00:33:56', 1, 1),
(264, 'Otanche', 6, '2020-05-08 00:33:56', 1, 1),
(265, 'Pachavita', 6, '2020-05-08 00:33:56', 1, 1),
(266, 'Paipa', 6, '2020-05-08 00:33:56', 1, 1),
(267, 'Pajarito', 6, '2020-05-08 00:33:56', 1, 1),
(268, 'Panqueba', 6, '2020-05-08 00:33:56', 1, 1),
(269, 'Pauna', 6, '2020-05-08 00:33:56', 1, 1),
(270, 'Paya', 6, '2020-05-08 00:33:56', 1, 1),
(271, 'Paz de Río', 6, '2020-05-08 00:33:56', 1, 1),
(272, 'Pesca', 6, '2020-05-08 00:33:56', 1, 1),
(273, 'Pisva', 6, '2020-05-08 00:33:56', 1, 1),
(274, 'Puerto Boyacá', 6, '2020-05-08 00:33:56', 1, 1),
(275, 'Páez', 6, '2020-05-08 00:33:56', 1, 1),
(276, 'Quipama', 6, '2020-05-08 00:33:56', 1, 1),
(277, 'Ramiriquí', 6, '2020-05-08 00:33:56', 1, 1),
(278, 'Rondón', 6, '2020-05-08 00:33:56', 1, 1),
(279, 'Ráquira', 6, '2020-05-08 00:33:56', 1, 1),
(280, 'Saboyá', 6, '2020-05-08 00:33:56', 1, 1),
(281, 'Samacá', 6, '2020-05-08 00:33:56', 1, 1),
(282, 'San Eduardo', 6, '2020-05-08 00:33:56', 1, 1),
(283, 'San José de Pare', 6, '2020-05-08 00:33:56', 1, 1),
(284, 'San Luís de Gaceno', 6, '2020-05-08 00:33:56', 1, 1),
(285, 'San Mateo', 6, '2020-05-08 00:33:56', 1, 1),
(286, 'San Miguel de Sema', 6, '2020-05-08 00:33:56', 1, 1),
(287, 'San Pablo de Borbur', 6, '2020-05-08 00:33:56', 1, 1),
(288, 'Santa María', 6, '2020-05-08 00:33:56', 1, 1),
(289, 'Santa Rosa de Viterbo', 6, '2020-05-08 00:33:56', 1, 1),
(290, 'Santa Sofía', 6, '2020-05-08 00:33:56', 1, 1),
(291, 'Santana', 6, '2020-05-08 00:33:56', 1, 1),
(292, 'Sativanorte', 6, '2020-05-08 00:33:56', 1, 1),
(293, 'Sativasur', 6, '2020-05-08 00:33:56', 1, 1),
(294, 'Siachoque', 6, '2020-05-08 00:33:56', 1, 1),
(295, 'Soatá', 6, '2020-05-08 00:33:56', 1, 1),
(296, 'Socha', 6, '2020-05-08 00:33:56', 1, 1),
(297, 'Socotá', 6, '2020-05-08 00:33:56', 1, 1),
(298, 'Sogamoso', 6, '2020-05-08 00:33:56', 1, 1),
(299, 'Somondoco', 6, '2020-05-08 00:33:56', 1, 1),
(300, 'Sora', 6, '2020-05-08 00:33:56', 1, 1),
(301, 'Soracá', 6, '2020-05-08 00:33:56', 1, 1),
(302, 'Sotaquirá', 6, '2020-05-08 00:33:56', 1, 1),
(303, 'Susacón', 6, '2020-05-08 00:33:56', 1, 1),
(304, 'Sutamarchán', 6, '2020-05-08 00:33:56', 1, 1),
(305, 'Sutatenza', 6, '2020-05-08 00:33:56', 1, 1),
(306, 'Sáchica', 6, '2020-05-08 00:33:56', 1, 1),
(307, 'Tasco', 6, '2020-05-08 00:33:56', 1, 1),
(308, 'Tenza', 6, '2020-05-08 00:33:56', 1, 1),
(309, 'Tibaná', 6, '2020-05-08 00:33:56', 1, 1),
(310, 'Tibasosa', 6, '2020-05-08 00:33:56', 1, 1),
(311, 'Tinjacá', 6, '2020-05-08 00:33:56', 1, 1),
(312, 'Tipacoque', 6, '2020-05-08 00:33:56', 1, 1),
(313, 'Toca', 6, '2020-05-08 00:33:56', 1, 1),
(314, 'Toguí', 6, '2020-05-08 00:33:56', 1, 1),
(315, 'Topagá', 6, '2020-05-08 00:33:56', 1, 1),
(316, 'Tota', 6, '2020-05-08 00:33:56', 1, 1),
(317, 'Tunja', 6, '2020-05-08 00:33:56', 1, 1),
(318, 'Tunungua', 6, '2020-05-08 00:33:56', 1, 1),
(319, 'Turmequé', 6, '2020-05-08 00:33:56', 1, 1),
(320, 'Tuta', 6, '2020-05-08 00:33:56', 1, 1),
(321, 'Tutasá', 6, '2020-05-08 00:33:56', 1, 1),
(322, 'Ventaquemada', 6, '2020-05-08 00:33:56', 1, 1),
(323, 'Villa de Leiva', 6, '2020-05-08 00:33:56', 1, 1),
(324, 'Viracachá', 6, '2020-05-08 00:33:56', 1, 1),
(325, 'Zetaquirá', 6, '2020-05-08 00:33:56', 1, 1),
(326, 'Úmbita', 6, '2020-05-08 00:33:56', 1, 1),
(327, 'Aguadas', 7, '2020-05-08 00:33:56', 1, 1),
(328, 'Anserma', 7, '2020-05-08 00:33:56', 1, 1),
(329, 'Aranzazu', 7, '2020-05-08 00:33:56', 1, 1),
(330, 'Belalcázar', 7, '2020-05-08 00:33:56', 1, 1),
(331, 'Chinchiná', 7, '2020-05-08 00:33:56', 1, 1),
(332, 'Filadelfia', 7, '2020-05-08 00:33:56', 1, 1),
(333, 'La Dorada', 7, '2020-05-08 00:33:56', 1, 1),
(334, 'La Merced', 7, '2020-05-08 00:33:56', 1, 1),
(335, 'La Victoria', 7, '2020-05-08 00:33:56', 1, 1),
(336, 'Manizales', 7, '2020-05-08 00:33:56', 1, 1),
(337, 'Manzanares', 7, '2020-05-08 00:33:56', 1, 1),
(338, 'Marmato', 7, '2020-05-08 00:33:56', 1, 1),
(339, 'Marquetalia', 7, '2020-05-08 00:33:56', 1, 1),
(340, 'Marulanda', 7, '2020-05-08 00:33:56', 1, 1),
(341, 'Neira', 7, '2020-05-08 00:33:56', 1, 1),
(342, 'Norcasia', 7, '2020-05-08 00:33:56', 1, 1),
(343, 'Palestina', 7, '2020-05-08 00:33:56', 1, 1),
(344, 'Pensilvania', 7, '2020-05-08 00:33:56', 1, 1),
(345, 'Pácora', 7, '2020-05-08 00:33:56', 1, 1),
(346, 'Risaralda', 7, '2020-05-08 00:33:56', 1, 1),
(347, 'Río Sucio', 7, '2020-05-08 00:33:56', 1, 1),
(348, 'Salamina', 7, '2020-05-08 00:33:56', 1, 1),
(349, 'Samaná', 7, '2020-05-08 00:33:56', 1, 1),
(350, 'San José', 7, '2020-05-08 00:33:56', 1, 1),
(351, 'Supía', 7, '2020-05-08 00:33:56', 1, 1),
(352, 'Villamaría', 7, '2020-05-08 00:33:56', 1, 1),
(353, 'Viterbo', 7, '2020-05-08 00:33:56', 1, 1),
(354, 'Albania', 8, '2020-05-08 00:33:56', 1, 1),
(355, 'Belén de los Andaquíes', 8, '2020-05-08 00:33:56', 1, 1),
(356, 'Cartagena del Chairá', 8, '2020-05-08 00:33:56', 1, 1),
(357, 'Curillo', 8, '2020-05-08 00:33:56', 1, 1),
(358, 'El Doncello', 8, '2020-05-08 00:33:56', 1, 1),
(359, 'El Paujil', 8, '2020-05-08 00:33:56', 1, 1),
(360, 'Florencia', 8, '2020-05-08 00:33:56', 1, 1),
(361, 'La Montañita', 8, '2020-05-08 00:33:56', 1, 1),
(362, 'Milán', 8, '2020-05-08 00:33:56', 1, 1),
(363, 'Morelia', 8, '2020-05-08 00:33:56', 1, 1),
(364, 'Puerto Rico', 8, '2020-05-08 00:33:56', 1, 1),
(365, 'San José del Fragua', 8, '2020-05-08 00:33:56', 1, 1),
(366, 'San Vicente del Caguán', 8, '2020-05-08 00:33:56', 1, 1),
(367, 'Solano', 8, '2020-05-08 00:33:56', 1, 1),
(368, 'Solita', 8, '2020-05-08 00:33:56', 1, 1),
(369, 'Valparaiso', 8, '2020-05-08 00:33:56', 1, 1),
(370, 'Aguazul', 9, '2020-05-08 00:33:56', 1, 1),
(371, 'Chámeza', 9, '2020-05-08 00:33:56', 1, 1),
(372, 'Hato Corozal', 9, '2020-05-08 00:33:56', 1, 1),
(373, 'La Salina', 9, '2020-05-08 00:33:56', 1, 1),
(374, 'Maní', 9, '2020-05-08 00:33:56', 1, 1),
(375, 'Monterrey', 9, '2020-05-08 00:33:56', 1, 1),
(376, 'Nunchía', 9, '2020-05-08 00:33:56', 1, 1),
(377, 'Orocué', 9, '2020-05-08 00:33:56', 1, 1),
(378, 'Paz de Ariporo', 9, '2020-05-08 00:33:56', 1, 1),
(379, 'Pore', 9, '2020-05-08 00:33:56', 1, 1),
(380, 'Recetor', 9, '2020-05-08 00:33:56', 1, 1),
(381, 'Sabanalarga', 9, '2020-05-08 00:33:56', 1, 1),
(382, 'San Luís de Palenque', 9, '2020-05-08 00:33:56', 1, 1),
(383, 'Sácama', 9, '2020-05-08 00:33:56', 1, 1),
(384, 'Tauramena', 9, '2020-05-08 00:33:56', 1, 1),
(385, 'Trinidad', 9, '2020-05-08 00:33:56', 1, 1),
(386, 'Támara', 9, '2020-05-08 00:33:56', 1, 1),
(387, 'Villanueva', 9, '2020-05-08 00:33:56', 1, 1),
(388, 'Yopal', 9, '2020-05-08 00:33:56', 1, 1),
(389, 'Almaguer', 10, '2020-05-08 00:33:56', 1, 1),
(390, 'Argelia', 10, '2020-05-08 00:33:56', 1, 1),
(391, 'Balboa', 10, '2020-05-08 00:33:56', 1, 1),
(392, 'Bolívar', 10, '2020-05-08 00:33:56', 1, 1),
(393, 'Buenos Aires', 10, '2020-05-08 00:33:56', 1, 1),
(394, 'Cajibío', 10, '2020-05-08 00:33:56', 1, 1),
(395, 'Caldono', 10, '2020-05-08 00:33:56', 1, 1),
(396, 'Caloto', 10, '2020-05-08 00:33:56', 1, 1),
(397, 'Corinto', 10, '2020-05-08 00:33:56', 1, 1),
(398, 'El Tambo', 10, '2020-05-08 00:33:56', 1, 1),
(399, 'Florencia', 10, '2020-05-08 00:33:56', 1, 1),
(400, 'Guachené', 10, '2020-05-08 00:33:56', 1, 1),
(401, 'Guapí', 10, '2020-05-08 00:33:56', 1, 1),
(402, 'Inzá', 10, '2020-05-08 00:33:56', 1, 1),
(403, 'Jambaló', 10, '2020-05-08 00:33:56', 1, 1),
(404, 'La Sierra', 10, '2020-05-08 00:33:56', 1, 1),
(405, 'La Vega', 10, '2020-05-08 00:33:56', 1, 1),
(406, 'López (Micay)', 10, '2020-05-08 00:33:56', 1, 1),
(407, 'Mercaderes', 10, '2020-05-08 00:33:56', 1, 1),
(408, 'Miranda', 10, '2020-05-08 00:33:56', 1, 1),
(409, 'Morales', 10, '2020-05-08 00:33:56', 1, 1),
(410, 'Padilla', 10, '2020-05-08 00:33:56', 1, 1),
(411, 'Patía (El Bordo)', 10, '2020-05-08 00:33:56', 1, 1),
(412, 'Piamonte', 10, '2020-05-08 00:33:56', 1, 1),
(413, 'Piendamó', 10, '2020-05-08 00:33:56', 1, 1),
(414, 'Popayán', 10, '2020-05-08 00:33:56', 1, 1),
(415, 'Puerto Tejada', 10, '2020-05-08 00:33:56', 1, 1),
(416, 'Puracé (Coconuco)', 10, '2020-05-08 00:33:56', 1, 1),
(417, 'Páez (Belalcazar)', 10, '2020-05-08 00:33:56', 1, 1),
(418, 'Rosas', 10, '2020-05-08 00:33:56', 1, 1),
(419, 'San Sebastián', 10, '2020-05-08 00:33:56', 1, 1),
(420, 'Santa Rosa', 10, '2020-05-08 00:33:56', 1, 1),
(421, 'Santander de Quilichao', 10, '2020-05-08 00:33:56', 1, 1),
(422, 'Silvia', 10, '2020-05-08 00:33:56', 1, 1),
(423, 'Sotara (Paispamba)', 10, '2020-05-08 00:33:56', 1, 1),
(424, 'Sucre', 10, '2020-05-08 00:33:56', 1, 1),
(425, 'Suárez', 10, '2020-05-08 00:33:56', 1, 1),
(426, 'Timbiquí', 10, '2020-05-08 00:33:56', 1, 1),
(427, 'Timbío', 10, '2020-05-08 00:33:56', 1, 1),
(428, 'Toribío', 10, '2020-05-08 00:33:56', 1, 1),
(429, 'Totoró', 10, '2020-05-08 00:33:56', 1, 1),
(430, 'Villa Rica', 10, '2020-05-08 00:33:56', 1, 1),
(431, 'Aguachica', 11, '2020-05-08 00:33:56', 1, 1),
(432, 'Agustín Codazzi', 11, '2020-05-08 00:33:56', 1, 1),
(433, 'Astrea', 11, '2020-05-08 00:33:56', 1, 1),
(434, 'Becerríl', 11, '2020-05-08 00:33:56', 1, 1),
(435, 'Bosconia', 11, '2020-05-08 00:33:56', 1, 1),
(436, 'Chimichagua', 11, '2020-05-08 00:33:56', 1, 1),
(437, 'Chiriguaná', 11, '2020-05-08 00:33:56', 1, 1),
(438, 'Curumaní', 11, '2020-05-08 00:33:56', 1, 1),
(439, 'El Copey', 11, '2020-05-08 00:33:56', 1, 1),
(440, 'El Paso', 11, '2020-05-08 00:33:56', 1, 1),
(441, 'Gamarra', 11, '2020-05-08 00:33:56', 1, 1),
(442, 'Gonzalez', 11, '2020-05-08 00:33:56', 1, 1),
(443, 'La Gloria', 11, '2020-05-08 00:33:56', 1, 1),
(444, 'La Jagua de Ibirico', 11, '2020-05-08 00:33:56', 1, 1),
(445, 'La Paz (Robles)', 11, '2020-05-08 00:33:56', 1, 1),
(446, 'Manaure Balcón del Cesar', 11, '2020-05-08 00:33:56', 1, 1),
(447, 'Pailitas', 11, '2020-05-08 00:33:56', 1, 1),
(448, 'Pelaya', 11, '2020-05-08 00:33:56', 1, 1),
(449, 'Pueblo Bello', 11, '2020-05-08 00:33:56', 1, 1),
(450, 'Río de oro', 11, '2020-05-08 00:33:56', 1, 1),
(451, 'San Alberto', 11, '2020-05-08 00:33:56', 1, 1),
(452, 'San Diego', 11, '2020-05-08 00:33:56', 1, 1),
(453, 'San Martín', 11, '2020-05-08 00:33:56', 1, 1),
(454, 'Tamalameque', 11, '2020-05-08 00:33:56', 1, 1),
(455, 'Valledupar', 11, '2020-05-08 00:33:56', 1, 1),
(456, 'Acandí', 12, '2020-05-08 00:33:56', 1, 1),
(457, 'Alto Baudó (Pie de Pato)', 12, '2020-05-08 00:33:56', 1, 1),
(458, 'Atrato (Yuto)', 12, '2020-05-08 00:33:56', 1, 1),
(459, 'Bagadó', 12, '2020-05-08 00:33:56', 1, 1),
(460, 'Bahía Solano (Mútis)', 12, '2020-05-08 00:33:56', 1, 1),
(461, 'Bajo Baudó (Pizarro)', 12, '2020-05-08 00:33:56', 1, 1),
(462, 'Belén de Bajirá', 12, '2020-05-08 00:33:56', 1, 1),
(463, 'Bojayá (Bellavista)', 12, '2020-05-08 00:33:56', 1, 1),
(464, 'Cantón de San Pablo', 12, '2020-05-08 00:33:56', 1, 1),
(465, 'Carmen del Darién (CURBARADÓ)', 12, '2020-05-08 00:33:56', 1, 1),
(466, 'Condoto', 12, '2020-05-08 00:33:56', 1, 1),
(467, 'Cértegui', 12, '2020-05-08 00:33:56', 1, 1),
(468, 'El Carmen de Atrato', 12, '2020-05-08 00:33:56', 1, 1),
(469, 'Istmina', 12, '2020-05-08 00:33:56', 1, 1),
(470, 'Juradó', 12, '2020-05-08 00:33:56', 1, 1),
(471, 'Lloró', 12, '2020-05-08 00:33:56', 1, 1),
(472, 'Medio Atrato', 12, '2020-05-08 00:33:56', 1, 1),
(473, 'Medio Baudó', 12, '2020-05-08 00:33:56', 1, 1),
(474, 'Medio San Juan (ANDAGOYA)', 12, '2020-05-08 00:33:56', 1, 1),
(475, 'Novita', 12, '2020-05-08 00:33:56', 1, 1),
(476, 'Nuquí', 12, '2020-05-08 00:33:56', 1, 1),
(477, 'Quibdó', 12, '2020-05-08 00:33:56', 1, 1),
(478, 'Río Iró', 12, '2020-05-08 00:33:56', 1, 1),
(479, 'Río Quito', 12, '2020-05-08 00:33:56', 1, 1),
(480, 'Ríosucio', 12, '2020-05-08 00:33:56', 1, 1),
(481, 'San José del Palmar', 12, '2020-05-08 00:33:56', 1, 1),
(482, 'Santa Genoveva de Docorodó', 12, '2020-05-08 00:33:56', 1, 1),
(483, 'Sipí', 12, '2020-05-08 00:33:56', 1, 1),
(484, 'Tadó', 12, '2020-05-08 00:33:56', 1, 1),
(485, 'Unguía', 12, '2020-05-08 00:33:56', 1, 1),
(486, 'Unión Panamericana (ÁNIMAS)', 12, '2020-05-08 00:33:56', 1, 1),
(487, 'Ayapel', 13, '2020-05-08 00:33:56', 1, 1),
(488, 'Buenavista', 13, '2020-05-08 00:33:56', 1, 1),
(489, 'Canalete', 13, '2020-05-08 00:33:56', 1, 1),
(490, 'Cereté', 13, '2020-05-08 00:33:56', 1, 1),
(491, 'Chimá', 13, '2020-05-08 00:33:56', 1, 1),
(492, 'Chinú', 13, '2020-05-08 00:33:56', 1, 1),
(493, 'Ciénaga de Oro', 13, '2020-05-08 00:33:56', 1, 1),
(494, 'Cotorra', 13, '2020-05-08 00:33:56', 1, 1),
(495, 'La Apartada y La Frontera', 13, '2020-05-08 00:33:56', 1, 1),
(496, 'Lorica', 13, '2020-05-08 00:33:56', 1, 1),
(497, 'Los Córdobas', 13, '2020-05-08 00:33:56', 1, 1),
(498, 'Momil', 13, '2020-05-08 00:33:56', 1, 1),
(499, 'Montelíbano', 13, '2020-05-08 00:33:56', 1, 1),
(500, 'Monteria', 13, '2020-05-08 00:33:56', 1, 1),
(501, 'Moñitos', 13, '2020-05-08 00:33:56', 1, 1),
(502, 'Planeta Rica', 13, '2020-05-08 00:33:56', 1, 1),
(503, 'Pueblo Nuevo', 13, '2020-05-08 00:33:56', 1, 1),
(504, 'Puerto Escondido', 13, '2020-05-08 00:33:56', 1, 1),
(505, 'Puerto Libertador', 13, '2020-05-08 00:33:56', 1, 1),
(506, 'Purísima', 13, '2020-05-08 00:33:56', 1, 1),
(507, 'Sahagún', 13, '2020-05-08 00:33:56', 1, 1),
(508, 'San Andrés Sotavento', 13, '2020-05-08 00:33:56', 1, 1),
(509, 'San Antero', 13, '2020-05-08 00:33:56', 1, 1),
(510, 'San Bernardo del Viento', 13, '2020-05-08 00:33:56', 1, 1),
(511, 'San Carlos', 13, '2020-05-08 00:33:56', 1, 1),
(512, 'San José de Uré', 13, '2020-05-08 00:33:56', 1, 1),
(513, 'San Pelayo', 13, '2020-05-08 00:33:56', 1, 1),
(514, 'Tierralta', 13, '2020-05-08 00:33:56', 1, 1),
(515, 'Tuchín', 13, '2020-05-08 00:33:56', 1, 1),
(516, 'Valencia', 13, '2020-05-08 00:33:56', 1, 1),
(517, 'Agua de Dios', 14, '2020-05-08 00:33:56', 1, 1),
(518, 'Albán', 14, '2020-05-08 00:33:56', 1, 1),
(519, 'Anapoima', 14, '2020-05-08 00:33:56', 1, 1),
(520, 'Anolaima', 14, '2020-05-08 00:33:56', 1, 1),
(521, 'Apulo', 14, '2020-05-08 00:33:56', 1, 1),
(522, 'Arbeláez', 14, '2020-05-08 00:33:56', 1, 1),
(523, 'Beltrán', 14, '2020-05-08 00:33:56', 1, 1),
(524, 'Bituima', 14, '2020-05-08 00:33:56', 1, 1),
(525, 'Bogotá D.C.', 14, '2020-05-08 00:33:56', 1, 1),
(526, 'Bojacá', 14, '2020-05-08 00:33:56', 1, 1),
(527, 'Cabrera', 14, '2020-05-08 00:33:56', 1, 1),
(528, 'Cachipay', 14, '2020-05-08 00:33:56', 1, 1),
(529, 'Cajicá', 14, '2020-05-08 00:33:56', 1, 1),
(530, 'Caparrapí', 14, '2020-05-08 00:33:56', 1, 1),
(531, 'Carmen de Carupa', 14, '2020-05-08 00:33:56', 1, 1),
(532, 'Chaguaní', 14, '2020-05-08 00:33:56', 1, 1),
(533, 'Chipaque', 14, '2020-05-08 00:33:56', 1, 1),
(534, 'Choachí', 14, '2020-05-08 00:33:56', 1, 1),
(535, 'Chocontá', 14, '2020-05-08 00:33:56', 1, 1),
(536, 'Chía', 14, '2020-05-08 00:33:56', 1, 1),
(537, 'Cogua', 14, '2020-05-08 00:33:56', 1, 1),
(538, 'Cota', 14, '2020-05-08 00:33:56', 1, 1),
(539, 'Cucunubá', 14, '2020-05-08 00:33:56', 1, 1),
(540, 'Cáqueza', 14, '2020-05-08 00:33:56', 1, 1),
(541, 'El Colegio', 14, '2020-05-08 00:33:56', 1, 1),
(542, 'El Peñón', 14, '2020-05-08 00:33:56', 1, 1),
(543, 'El Rosal', 14, '2020-05-08 00:33:56', 1, 1),
(544, 'Facatativá', 14, '2020-05-08 00:33:56', 1, 1),
(545, 'Fosca', 14, '2020-05-08 00:33:56', 1, 1),
(546, 'Funza', 14, '2020-05-08 00:33:56', 1, 1),
(547, 'Fusagasugá', 14, '2020-05-08 00:33:56', 1, 1),
(548, 'Fómeque', 14, '2020-05-08 00:33:56', 1, 1),
(549, 'Fúquene', 14, '2020-05-08 00:33:56', 1, 1),
(550, 'Gachalá', 14, '2020-05-08 00:33:56', 1, 1),
(551, 'Gachancipá', 14, '2020-05-08 00:33:56', 1, 1),
(552, 'Gachetá', 14, '2020-05-08 00:33:56', 1, 1),
(553, 'Gama', 14, '2020-05-08 00:33:56', 1, 1),
(554, 'Girardot', 14, '2020-05-08 00:33:56', 1, 1),
(555, 'Granada', 14, '2020-05-08 00:33:56', 1, 1),
(556, 'Guachetá', 14, '2020-05-08 00:33:56', 1, 1),
(557, 'Guaduas', 14, '2020-05-08 00:33:56', 1, 1),
(558, 'Guasca', 14, '2020-05-08 00:33:56', 1, 1),
(559, 'Guataquí', 14, '2020-05-08 00:33:56', 1, 1),
(560, 'Guatavita', 14, '2020-05-08 00:33:56', 1, 1),
(561, 'Guayabal de Siquima', 14, '2020-05-08 00:33:56', 1, 1),
(562, 'Guayabetal', 14, '2020-05-08 00:33:56', 1, 1),
(563, 'Gutiérrez', 14, '2020-05-08 00:33:56', 1, 1),
(564, 'Jerusalén', 14, '2020-05-08 00:33:56', 1, 1),
(565, 'Junín', 14, '2020-05-08 00:33:56', 1, 1),
(566, 'La Calera', 14, '2020-05-08 00:33:56', 1, 1),
(567, 'La Mesa', 14, '2020-05-08 00:33:56', 1, 1),
(568, 'La Palma', 14, '2020-05-08 00:33:56', 1, 1),
(569, 'La Peña', 14, '2020-05-08 00:33:56', 1, 1),
(570, 'La Vega', 14, '2020-05-08 00:33:56', 1, 1),
(571, 'Lenguazaque', 14, '2020-05-08 00:33:56', 1, 1),
(572, 'Machetá', 14, '2020-05-08 00:33:56', 1, 1),
(573, 'Madrid', 14, '2020-05-08 00:33:56', 1, 1),
(574, 'Manta', 14, '2020-05-08 00:33:56', 1, 1),
(575, 'Medina', 14, '2020-05-08 00:33:56', 1, 1),
(576, 'Mosquera', 14, '2020-05-08 00:33:56', 1, 1),
(577, 'Nariño', 14, '2020-05-08 00:33:56', 1, 1),
(578, 'Nemocón', 14, '2020-05-08 00:33:56', 1, 1),
(579, 'Nilo', 14, '2020-05-08 00:33:56', 1, 1),
(580, 'Nimaima', 14, '2020-05-08 00:33:56', 1, 1),
(581, 'Nocaima', 14, '2020-05-08 00:33:56', 1, 1),
(582, 'Pacho', 14, '2020-05-08 00:33:56', 1, 1),
(583, 'Paime', 14, '2020-05-08 00:33:56', 1, 1),
(584, 'Pandi', 14, '2020-05-08 00:33:56', 1, 1),
(585, 'Paratebueno', 14, '2020-05-08 00:33:56', 1, 1),
(586, 'Pasca', 14, '2020-05-08 00:33:56', 1, 1),
(587, 'Puerto Salgar', 14, '2020-05-08 00:33:56', 1, 1),
(588, 'Pulí', 14, '2020-05-08 00:33:56', 1, 1),
(589, 'Quebradanegra', 14, '2020-05-08 00:33:56', 1, 1),
(590, 'Quetame', 14, '2020-05-08 00:33:56', 1, 1),
(591, 'Quipile', 14, '2020-05-08 00:33:56', 1, 1),
(592, 'Ricaurte', 14, '2020-05-08 00:33:56', 1, 1),
(593, 'San Antonio de Tequendama', 14, '2020-05-08 00:33:56', 1, 1),
(594, 'San Bernardo', 14, '2020-05-08 00:33:56', 1, 1),
(595, 'San Cayetano', 14, '2020-05-08 00:33:56', 1, 1),
(596, 'San Francisco', 14, '2020-05-08 00:33:56', 1, 1),
(597, 'San Juan de Río Seco', 14, '2020-05-08 00:33:56', 1, 1),
(598, 'Sasaima', 14, '2020-05-08 00:33:56', 1, 1),
(599, 'Sesquilé', 14, '2020-05-08 00:33:56', 1, 1),
(600, 'Sibaté', 14, '2020-05-08 00:33:56', 1, 1),
(601, 'Silvania', 14, '2020-05-08 00:33:56', 1, 1),
(602, 'Simijaca', 14, '2020-05-08 00:33:56', 1, 1),
(603, 'Soacha', 14, '2020-05-08 00:33:56', 1, 1),
(604, 'Sopó', 14, '2020-05-08 00:33:56', 1, 1),
(605, 'Subachoque', 14, '2020-05-08 00:33:56', 1, 1),
(606, 'Suesca', 14, '2020-05-08 00:33:56', 1, 1),
(607, 'Supatá', 14, '2020-05-08 00:33:56', 1, 1),
(608, 'Susa', 14, '2020-05-08 00:33:56', 1, 1),
(609, 'Sutatausa', 14, '2020-05-08 00:33:56', 1, 1),
(610, 'Tabio', 14, '2020-05-08 00:33:56', 1, 1),
(611, 'Tausa', 14, '2020-05-08 00:33:56', 1, 1),
(612, 'Tena', 14, '2020-05-08 00:33:56', 1, 1),
(613, 'Tenjo', 14, '2020-05-08 00:33:56', 1, 1),
(614, 'Tibacuy', 14, '2020-05-08 00:33:56', 1, 1),
(615, 'Tibirita', 14, '2020-05-08 00:33:56', 1, 1),
(616, 'Tocaima', 14, '2020-05-08 00:33:56', 1, 1),
(617, 'Tocancipá', 14, '2020-05-08 00:33:56', 1, 1),
(618, 'Topaipí', 14, '2020-05-08 00:33:56', 1, 1),
(619, 'Ubalá', 14, '2020-05-08 00:33:56', 1, 1),
(620, 'Ubaque', 14, '2020-05-08 00:33:56', 1, 1),
(621, 'Ubaté', 14, '2020-05-08 00:33:56', 1, 1),
(622, 'Une', 14, '2020-05-08 00:33:56', 1, 1),
(623, 'Venecia (Ospina Pérez)', 14, '2020-05-08 00:33:56', 1, 1),
(624, 'Vergara', 14, '2020-05-08 00:33:56', 1, 1),
(625, 'Viani', 14, '2020-05-08 00:33:56', 1, 1),
(626, 'Villagómez', 14, '2020-05-08 00:33:56', 1, 1),
(627, 'Villapinzón', 14, '2020-05-08 00:33:56', 1, 1),
(628, 'Villeta', 14, '2020-05-08 00:33:56', 1, 1),
(629, 'Viotá', 14, '2020-05-08 00:33:56', 1, 1),
(630, 'Yacopí', 14, '2020-05-08 00:33:56', 1, 1),
(631, 'Zipacón', 14, '2020-05-08 00:33:56', 1, 1),
(632, 'Zipaquirá', 14, '2020-05-08 00:33:56', 1, 1),
(633, 'Útica', 14, '2020-05-08 00:33:56', 1, 1),
(634, 'Inírida', 15, '2020-05-08 00:33:56', 1, 1),
(635, 'Calamar', 16, '2020-05-08 00:33:56', 1, 1),
(636, 'El Retorno', 16, '2020-05-08 00:33:56', 1, 1),
(637, 'Miraflores', 16, '2020-05-08 00:33:56', 1, 1),
(638, 'San José del Guaviare', 16, '2020-05-08 00:33:56', 1, 1),
(639, 'Acevedo', 17, '2020-05-08 00:33:56', 1, 1),
(640, 'Agrado', 17, '2020-05-08 00:33:56', 1, 1),
(641, 'Aipe', 17, '2020-05-08 00:33:56', 1, 1),
(642, 'Algeciras', 17, '2020-05-08 00:33:56', 1, 1),
(643, 'Altamira', 17, '2020-05-08 00:33:56', 1, 1),
(644, 'Baraya', 17, '2020-05-08 00:33:56', 1, 1),
(645, 'Campoalegre', 17, '2020-05-08 00:33:56', 1, 1),
(646, 'Colombia', 17, '2020-05-08 00:33:56', 1, 1),
(647, 'Elías', 17, '2020-05-08 00:33:56', 1, 1),
(648, 'Garzón', 17, '2020-05-08 00:33:56', 1, 1),
(649, 'Gigante', 17, '2020-05-08 00:33:56', 1, 1),
(650, 'Guadalupe', 17, '2020-05-08 00:33:56', 1, 1),
(651, 'Hobo', 17, '2020-05-08 00:33:56', 1, 1),
(652, 'Isnos', 17, '2020-05-08 00:33:56', 1, 1),
(653, 'La Argentina', 17, '2020-05-08 00:33:56', 1, 1),
(654, 'La Plata', 17, '2020-05-08 00:33:56', 1, 1),
(655, 'Neiva', 17, '2020-05-08 00:33:56', 1, 1),
(656, 'Nátaga', 17, '2020-05-08 00:33:56', 1, 1),
(657, 'Oporapa', 17, '2020-05-08 00:33:56', 1, 1),
(658, 'Paicol', 17, '2020-05-08 00:33:56', 1, 1),
(659, 'Palermo', 17, '2020-05-08 00:33:56', 1, 1),
(660, 'Palestina', 17, '2020-05-08 00:33:56', 1, 1),
(661, 'Pital', 17, '2020-05-08 00:33:56', 1, 1),
(662, 'Pitalito', 17, '2020-05-08 00:33:56', 1, 1),
(663, 'Rivera', 17, '2020-05-08 00:33:56', 1, 1),
(664, 'Saladoblanco', 17, '2020-05-08 00:33:56', 1, 1),
(665, 'San Agustín', 17, '2020-05-08 00:33:56', 1, 1),
(666, 'Santa María', 17, '2020-05-08 00:33:56', 1, 1),
(667, 'Suaza', 17, '2020-05-08 00:33:56', 1, 1),
(668, 'Tarqui', 17, '2020-05-08 00:33:56', 1, 1),
(669, 'Tello', 17, '2020-05-08 00:33:56', 1, 1),
(670, 'Teruel', 17, '2020-05-08 00:33:56', 1, 1),
(671, 'Tesalia', 17, '2020-05-08 00:33:56', 1, 1),
(672, 'Timaná', 17, '2020-05-08 00:33:56', 1, 1),
(673, 'Villavieja', 17, '2020-05-08 00:33:56', 1, 1),
(674, 'Yaguará', 17, '2020-05-08 00:33:56', 1, 1),
(675, 'Íquira', 17, '2020-05-08 00:33:56', 1, 1),
(676, 'Albania', 18, '2020-05-08 00:33:56', 1, 1),
(677, 'Barrancas', 18, '2020-05-08 00:33:56', 1, 1),
(678, 'Dibulla', 18, '2020-05-08 00:33:56', 1, 1),
(679, 'Distracción', 18, '2020-05-08 00:33:56', 1, 1),
(680, 'El Molino', 18, '2020-05-08 00:33:56', 1, 1),
(681, 'Fonseca', 18, '2020-05-08 00:33:56', 1, 1),
(682, 'Hatonuevo', 18, '2020-05-08 00:33:56', 1, 1),
(683, 'La Jagua del Pilar', 18, '2020-05-08 00:33:56', 1, 1),
(684, 'Maicao', 18, '2020-05-08 00:33:56', 1, 1),
(685, 'Manaure', 18, '2020-05-08 00:33:56', 1, 1),
(686, 'Riohacha', 18, '2020-05-08 00:33:56', 1, 1),
(687, 'San Juan del Cesar', 18, '2020-05-08 00:33:56', 1, 1),
(688, 'Uribia', 18, '2020-05-08 00:33:56', 1, 1),
(689, 'Urumita', 18, '2020-05-08 00:33:56', 1, 1),
(690, 'Villanueva', 18, '2020-05-08 00:33:56', 1, 1),
(691, 'Algarrobo', 19, '2020-05-08 00:33:56', 1, 1),
(692, 'Aracataca', 19, '2020-05-08 00:33:56', 1, 1),
(693, 'Ariguaní (El Difícil)', 19, '2020-05-08 00:33:56', 1, 1),
(694, 'Cerro San Antonio', 19, '2020-05-08 00:33:56', 1, 1),
(695, 'Chivolo', 19, '2020-05-08 00:33:56', 1, 1),
(696, 'Ciénaga', 19, '2020-05-08 00:33:56', 1, 1),
(697, 'Concordia', 19, '2020-05-08 00:33:56', 1, 1),
(698, 'El Banco', 19, '2020-05-08 00:33:56', 1, 1),
(699, 'El Piñon', 19, '2020-05-08 00:33:56', 1, 1),
(700, 'El Retén', 19, '2020-05-08 00:33:56', 1, 1),
(701, 'Fundación', 19, '2020-05-08 00:33:56', 1, 1),
(702, 'Guamal', 19, '2020-05-08 00:33:56', 1, 1),
(703, 'Nueva Granada', 19, '2020-05-08 00:33:56', 1, 1),
(704, 'Pedraza', 19, '2020-05-08 00:33:56', 1, 1),
(705, 'Pijiño', 19, '2020-05-08 00:33:56', 1, 1),
(706, 'Pivijay', 19, '2020-05-08 00:33:56', 1, 1),
(707, 'Plato', 19, '2020-05-08 00:33:56', 1, 1),
(708, 'Puebloviejo', 19, '2020-05-08 00:33:56', 1, 1),
(709, 'Remolino', 19, '2020-05-08 00:33:56', 1, 1),
(710, 'Sabanas de San Angel (SAN ANGEL)', 19, '2020-05-08 00:33:56', 1, 1),
(711, 'Salamina', 19, '2020-05-08 00:33:56', 1, 1),
(712, 'San Sebastián de Buenavista', 19, '2020-05-08 00:33:56', 1, 1),
(713, 'San Zenón', 19, '2020-05-08 00:33:56', 1, 1),
(714, 'Santa Ana', 19, '2020-05-08 00:33:56', 1, 1),
(715, 'Santa Bárbara de Pinto', 19, '2020-05-08 00:33:56', 1, 1),
(716, 'Santa Marta', 19, '2020-05-08 00:33:56', 1, 1),
(717, 'Sitionuevo', 19, '2020-05-08 00:33:56', 1, 1),
(718, 'Tenerife', 19, '2020-05-08 00:33:56', 1, 1),
(719, 'Zapayán (PUNTA DE PIEDRAS)', 19, '2020-05-08 00:33:56', 1, 1),
(720, 'Zona Bananera (PRADO - SEVILLA)', 19, '2020-05-08 00:33:56', 1, 1),
(721, 'Acacías', 20, '2020-05-08 00:33:56', 1, 1),
(722, 'Barranca de Upía', 20, '2020-05-08 00:33:56', 1, 1),
(723, 'Cabuyaro', 20, '2020-05-08 00:33:56', 1, 1),
(724, 'Castilla la Nueva', 20, '2020-05-08 00:33:56', 1, 1),
(725, 'Cubarral', 20, '2020-05-08 00:33:56', 1, 1),
(726, 'Cumaral', 20, '2020-05-08 00:33:56', 1, 1),
(727, 'El Calvario', 20, '2020-05-08 00:33:56', 1, 1),
(728, 'El Castillo', 20, '2020-05-08 00:33:56', 1, 1),
(729, 'El Dorado', 20, '2020-05-08 00:33:56', 1, 1),
(730, 'Fuente de Oro', 20, '2020-05-08 00:33:56', 1, 1),
(731, 'Granada', 20, '2020-05-08 00:33:56', 1, 1),
(732, 'Guamal', 20, '2020-05-08 00:33:56', 1, 1),
(733, 'La Macarena', 20, '2020-05-08 00:33:56', 1, 1),
(734, 'Lejanías', 20, '2020-05-08 00:33:56', 1, 1),
(735, 'Mapiripan', 20, '2020-05-08 00:33:56', 1, 1),
(736, 'Mesetas', 20, '2020-05-08 00:33:56', 1, 1),
(737, 'Puerto Concordia', 20, '2020-05-08 00:33:56', 1, 1),
(738, 'Puerto Gaitán', 20, '2020-05-08 00:33:56', 1, 1),
(739, 'Puerto Lleras', 20, '2020-05-08 00:33:56', 1, 1),
(740, 'Puerto López', 20, '2020-05-08 00:33:56', 1, 1),
(741, 'Puerto Rico', 20, '2020-05-08 00:33:56', 1, 1),
(742, 'Restrepo', 20, '2020-05-08 00:33:56', 1, 1),
(743, 'San Carlos de Guaroa', 20, '2020-05-08 00:33:56', 1, 1),
(744, 'San Juan de Arama', 20, '2020-05-08 00:33:56', 1, 1),
(745, 'San Juanito', 20, '2020-05-08 00:33:56', 1, 1),
(746, 'San Martín', 20, '2020-05-08 00:33:56', 1, 1),
(747, 'Uribe', 20, '2020-05-08 00:33:56', 1, 1),
(748, 'Villavicencio', 20, '2020-05-08 00:33:56', 1, 1),
(749, 'Vista Hermosa', 20, '2020-05-08 00:33:56', 1, 1),
(750, 'Albán (San José)', 21, '2020-05-08 00:33:56', 1, 1),
(751, 'Aldana', 21, '2020-05-08 00:33:56', 1, 1),
(752, 'Ancuya', 21, '2020-05-08 00:33:56', 1, 1),
(753, 'Arboleda (Berruecos)', 21, '2020-05-08 00:33:56', 1, 1),
(754, 'Barbacoas', 21, '2020-05-08 00:33:56', 1, 1),
(755, 'Belén', 21, '2020-05-08 00:33:56', 1, 1),
(756, 'Buesaco', 21, '2020-05-08 00:33:56', 1, 1),
(757, 'Chachaguí', 21, '2020-05-08 00:33:56', 1, 1),
(758, 'Colón (Génova)', 21, '2020-05-08 00:33:56', 1, 1),
(759, 'Consaca', 21, '2020-05-08 00:33:56', 1, 1),
(760, 'Contadero', 21, '2020-05-08 00:33:56', 1, 1),
(761, 'Cuaspud (Carlosama)', 21, '2020-05-08 00:33:56', 1, 1),
(762, 'Cumbal', 21, '2020-05-08 00:33:56', 1, 1),
(763, 'Cumbitara', 21, '2020-05-08 00:33:56', 1, 1),
(764, 'Córdoba', 21, '2020-05-08 00:33:56', 1, 1),
(765, 'El Charco', 21, '2020-05-08 00:33:56', 1, 1),
(766, 'El Peñol', 21, '2020-05-08 00:33:56', 1, 1),
(767, 'El Rosario', 21, '2020-05-08 00:33:56', 1, 1),
(768, 'El Tablón de Gómez', 21, '2020-05-08 00:33:56', 1, 1),
(769, 'El Tambo', 21, '2020-05-08 00:33:56', 1, 1),
(770, 'Francisco Pizarro', 21, '2020-05-08 00:33:56', 1, 1),
(771, 'Funes', 21, '2020-05-08 00:33:56', 1, 1),
(772, 'Guachavés', 21, '2020-05-08 00:33:56', 1, 1),
(773, 'Guachucal', 21, '2020-05-08 00:33:56', 1, 1),
(774, 'Guaitarilla', 21, '2020-05-08 00:33:56', 1, 1),
(775, 'Gualmatán', 21, '2020-05-08 00:33:56', 1, 1),
(776, 'Iles', 21, '2020-05-08 00:33:56', 1, 1),
(777, 'Imúes', 21, '2020-05-08 00:33:56', 1, 1),
(778, 'Ipiales', 21, '2020-05-08 00:33:56', 1, 1),
(779, 'La Cruz', 21, '2020-05-08 00:33:56', 1, 1),
(780, 'La Florida', 21, '2020-05-08 00:33:56', 1, 1),
(781, 'La Llanada', 21, '2020-05-08 00:33:56', 1, 1),
(782, 'La Tola', 21, '2020-05-08 00:33:56', 1, 1),
(783, 'La Unión', 21, '2020-05-08 00:33:56', 1, 1),
(784, 'Leiva', 21, '2020-05-08 00:33:56', 1, 1),
(785, 'Linares', 21, '2020-05-08 00:33:56', 1, 1),
(786, 'Magüi (Payán)', 21, '2020-05-08 00:33:56', 1, 1),
(787, 'Mallama (Piedrancha)', 21, '2020-05-08 00:33:56', 1, 1),
(788, 'Mosquera', 21, '2020-05-08 00:33:56', 1, 1),
(789, 'Nariño', 21, '2020-05-08 00:33:56', 1, 1),
(790, 'Olaya Herrera', 21, '2020-05-08 00:33:56', 1, 1),
(791, 'Ospina', 21, '2020-05-08 00:33:56', 1, 1),
(792, 'Policarpa', 21, '2020-05-08 00:33:56', 1, 1),
(793, 'Potosí', 21, '2020-05-08 00:33:56', 1, 1),
(794, 'Providencia', 21, '2020-05-08 00:33:56', 1, 1),
(795, 'Puerres', 21, '2020-05-08 00:33:56', 1, 1),
(796, 'Pupiales', 21, '2020-05-08 00:33:56', 1, 1),
(797, 'Ricaurte', 21, '2020-05-08 00:33:56', 1, 1),
(798, 'Roberto Payán (San José)', 21, '2020-05-08 00:33:56', 1, 1),
(799, 'Samaniego', 21, '2020-05-08 00:33:56', 1, 1),
(800, 'San Bernardo', 21, '2020-05-08 00:33:56', 1, 1),
(801, 'San Juan de Pasto', 21, '2020-05-08 00:33:56', 1, 1),
(802, 'San Lorenzo', 21, '2020-05-08 00:33:56', 1, 1),
(803, 'San Pablo', 21, '2020-05-08 00:33:56', 1, 1),
(804, 'San Pedro de Cartago', 21, '2020-05-08 00:33:56', 1, 1),
(805, 'Sandoná', 21, '2020-05-08 00:33:56', 1, 1),
(806, 'Santa Bárbara (Iscuandé)', 21, '2020-05-08 00:33:56', 1, 1),
(807, 'Sapuyes', 21, '2020-05-08 00:33:56', 1, 1),
(808, 'Sotomayor (Los Andes)', 21, '2020-05-08 00:33:56', 1, 1),
(809, 'Taminango', 21, '2020-05-08 00:33:56', 1, 1),
(810, 'Tangua', 21, '2020-05-08 00:33:56', 1, 1),
(811, 'Tumaco', 21, '2020-05-08 00:33:56', 1, 1),
(812, 'Túquerres', 21, '2020-05-08 00:33:56', 1, 1),
(813, 'Yacuanquer', 21, '2020-05-08 00:33:56', 1, 1),
(814, 'Arboledas', 22, '2020-05-08 00:33:56', 1, 1),
(815, 'Bochalema', 22, '2020-05-08 00:33:56', 1, 1),
(816, 'Bucarasica', 22, '2020-05-08 00:33:56', 1, 1),
(817, 'Chinácota', 22, '2020-05-08 00:33:56', 1, 1),
(818, 'Chitagá', 22, '2020-05-08 00:33:56', 1, 1),
(819, 'Convención', 22, '2020-05-08 00:33:56', 1, 1),
(820, 'Cucutilla', 22, '2020-05-08 00:33:56', 1, 1),
(821, 'Cáchira', 22, '2020-05-08 00:33:56', 1, 1),
(822, 'Cácota', 22, '2020-05-08 00:33:56', 1, 1),
(823, 'Cúcuta', 22, '2020-05-08 00:33:56', 1, 1),
(824, 'Durania', 22, '2020-05-08 00:33:56', 1, 1),
(825, 'El Carmen', 22, '2020-05-08 00:33:56', 1, 1),
(826, 'El Tarra', 22, '2020-05-08 00:33:56', 1, 1),
(827, 'El Zulia', 22, '2020-05-08 00:33:56', 1, 1),
(828, 'Gramalote', 22, '2020-05-08 00:33:56', 1, 1),
(829, 'Hacarí', 22, '2020-05-08 00:33:56', 1, 1),
(830, 'Herrán', 22, '2020-05-08 00:33:56', 1, 1),
(831, 'La Esperanza', 22, '2020-05-08 00:33:56', 1, 1),
(832, 'La Playa', 22, '2020-05-08 00:33:56', 1, 1),
(833, 'Labateca', 22, '2020-05-08 00:33:56', 1, 1),
(834, 'Los Patios', 22, '2020-05-08 00:33:56', 1, 1),
(835, 'Lourdes', 22, '2020-05-08 00:33:56', 1, 1),
(836, 'Mutiscua', 22, '2020-05-08 00:33:56', 1, 1),
(837, 'Ocaña', 22, '2020-05-08 00:33:56', 1, 1),
(838, 'Pamplona', 22, '2020-05-08 00:33:56', 1, 1),
(839, 'Pamplonita', 22, '2020-05-08 00:33:56', 1, 1),
(840, 'Puerto Santander', 22, '2020-05-08 00:33:56', 1, 1),
(841, 'Ragonvalia', 22, '2020-05-08 00:33:56', 1, 1),
(842, 'Salazar', 22, '2020-05-08 00:33:56', 1, 1),
(843, 'San Calixto', 22, '2020-05-08 00:33:56', 1, 1),
(844, 'San Cayetano', 22, '2020-05-08 00:33:56', 1, 1),
(845, 'Santiago', 22, '2020-05-08 00:33:56', 1, 1),
(846, 'Sardinata', 22, '2020-05-08 00:33:56', 1, 1),
(847, 'Silos', 22, '2020-05-08 00:33:56', 1, 1),
(848, 'Teorama', 22, '2020-05-08 00:33:56', 1, 1),
(849, 'Tibú', 22, '2020-05-08 00:33:56', 1, 1),
(850, 'Toledo', 22, '2020-05-08 00:33:56', 1, 1),
(851, 'Villa Caro', 22, '2020-05-08 00:33:56', 1, 1),
(852, 'Villa del Rosario', 22, '2020-05-08 00:33:56', 1, 1),
(853, 'Ábrego', 22, '2020-05-08 00:33:56', 1, 1),
(854, 'Colón', 23, '2020-05-08 00:33:56', 1, 1),
(855, 'Mocoa', 23, '2020-05-08 00:33:56', 1, 1),
(856, 'Orito', 23, '2020-05-08 00:33:56', 1, 1),
(857, 'Puerto Asís', 23, '2020-05-08 00:33:56', 1, 1),
(858, 'Puerto Caicedo', 23, '2020-05-08 00:33:56', 1, 1),
(859, 'Puerto Guzmán', 23, '2020-05-08 00:33:56', 1, 1),
(860, 'Puerto Leguízamo', 23, '2020-05-08 00:33:56', 1, 1),
(861, 'San Francisco', 23, '2020-05-08 00:33:56', 1, 1),
(862, 'San Miguel', 23, '2020-05-08 00:33:56', 1, 1),
(863, 'Santiago', 23, '2020-05-08 00:33:56', 1, 1),
(864, 'Sibundoy', 23, '2020-05-08 00:33:56', 1, 1),
(865, 'Valle del Guamuez', 23, '2020-05-08 00:33:56', 1, 1),
(866, 'Villagarzón', 23, '2020-05-08 00:33:56', 1, 1),
(867, 'Armenia', 24, '2020-05-08 00:33:56', 1, 1),
(868, 'Buenavista', 24, '2020-05-08 00:33:56', 1, 1),
(869, 'Calarcá', 24, '2020-05-08 00:33:56', 1, 1),
(870, 'Circasia', 24, '2020-05-08 00:33:56', 1, 1),
(871, 'Cordobá', 24, '2020-05-08 00:33:56', 1, 1),
(872, 'Filandia', 24, '2020-05-08 00:33:56', 1, 1),
(873, 'Génova', 24, '2020-05-08 00:33:56', 1, 1),
(874, 'La Tebaida', 24, '2020-05-08 00:33:56', 1, 1),
(875, 'Montenegro', 24, '2020-05-08 00:33:56', 1, 1),
(876, 'Pijao', 24, '2020-05-08 00:33:56', 1, 1),
(877, 'Quimbaya', 24, '2020-05-08 00:33:56', 1, 1),
(878, 'Salento', 24, '2020-05-08 00:33:56', 1, 1),
(879, 'Apía', 25, '2020-05-08 00:33:56', 1, 1),
(880, 'Balboa', 25, '2020-05-08 00:33:56', 1, 1),
(881, 'Belén de Umbría', 25, '2020-05-08 00:33:56', 1, 1),
(882, 'Dos Quebradas', 25, '2020-05-08 00:33:56', 1, 1),
(883, 'Guática', 25, '2020-05-08 00:33:56', 1, 1),
(884, 'La Celia', 25, '2020-05-08 00:33:56', 1, 1),
(885, 'La Virginia', 25, '2020-05-08 00:33:56', 1, 1),
(886, 'Marsella', 25, '2020-05-08 00:33:56', 1, 1),
(887, 'Mistrató', 25, '2020-05-08 00:33:56', 1, 1),
(888, 'Pereira', 25, '2020-05-08 00:33:56', 1, 1),
(889, 'Pueblo Rico', 25, '2020-05-08 00:33:56', 1, 1),
(890, 'Quinchía', 25, '2020-05-08 00:33:56', 1, 1),
(891, 'Santa Rosa de Cabal', 25, '2020-05-08 00:33:56', 1, 1),
(892, 'Santuario', 25, '2020-05-08 00:33:56', 1, 1),
(893, 'Providencia', 26, '2020-05-08 00:33:56', 1, 1),
(894, 'Aguada', 27, '2020-05-08 00:33:56', 1, 1),
(895, 'Albania', 27, '2020-05-08 00:33:56', 1, 1),
(896, 'Aratoca', 27, '2020-05-08 00:33:56', 1, 1),
(897, 'Barbosa', 27, '2020-05-08 00:33:56', 1, 1),
(898, 'Barichara', 27, '2020-05-08 00:33:56', 1, 1),
(899, 'Barrancabermeja', 27, '2020-05-08 00:33:56', 1, 1),
(900, 'Betulia', 27, '2020-05-08 00:33:56', 1, 1),
(901, 'Bolívar', 27, '2020-05-08 00:33:56', 1, 1),
(902, 'Bucaramanga', 27, '2020-05-08 00:33:56', 1, 1),
(903, 'Cabrera', 27, '2020-05-08 00:33:56', 1, 1),
(904, 'California', 27, '2020-05-08 00:33:56', 1, 1),
(905, 'Capitanejo', 27, '2020-05-08 00:33:56', 1, 1),
(906, 'Carcasí', 27, '2020-05-08 00:33:56', 1, 1),
(907, 'Cepita', 27, '2020-05-08 00:33:56', 1, 1),
(908, 'Cerrito', 27, '2020-05-08 00:33:56', 1, 1),
(909, 'Charalá', 27, '2020-05-08 00:33:56', 1, 1),
(910, 'Charta', 27, '2020-05-08 00:33:56', 1, 1),
(911, 'Chima', 27, '2020-05-08 00:33:56', 1, 1),
(912, 'Chipatá', 27, '2020-05-08 00:33:56', 1, 1),
(913, 'Cimitarra', 27, '2020-05-08 00:33:56', 1, 1),
(914, 'Concepción', 27, '2020-05-08 00:33:56', 1, 1),
(915, 'Confines', 27, '2020-05-08 00:33:56', 1, 1),
(916, 'Contratación', 27, '2020-05-08 00:33:56', 1, 1),
(917, 'Coromoro', 27, '2020-05-08 00:33:56', 1, 1),
(918, 'Curití', 27, '2020-05-08 00:33:56', 1, 1),
(919, 'El Carmen', 27, '2020-05-08 00:33:56', 1, 1),
(920, 'El Guacamayo', 27, '2020-05-08 00:33:56', 1, 1),
(921, 'El Peñon', 27, '2020-05-08 00:33:56', 1, 1),
(922, 'El Playón', 27, '2020-05-08 00:33:56', 1, 1),
(923, 'Encino', 27, '2020-05-08 00:33:56', 1, 1),
(924, 'Enciso', 27, '2020-05-08 00:33:56', 1, 1),
(925, 'Floridablanca', 27, '2020-05-08 00:33:56', 1, 1),
(926, 'Florián', 27, '2020-05-08 00:33:56', 1, 1),
(927, 'Galán', 27, '2020-05-08 00:33:56', 1, 1),
(928, 'Girón', 27, '2020-05-08 00:33:56', 1, 1),
(929, 'Guaca', 27, '2020-05-08 00:33:56', 1, 1),
(930, 'Guadalupe', 27, '2020-05-08 00:33:56', 1, 1),
(931, 'Guapota', 27, '2020-05-08 00:33:56', 1, 1),
(932, 'Guavatá', 27, '2020-05-08 00:33:56', 1, 1),
(933, 'Guepsa', 27, '2020-05-08 00:33:56', 1, 1),
(934, 'Gámbita', 27, '2020-05-08 00:33:56', 1, 1),
(935, 'Hato', 27, '2020-05-08 00:33:56', 1, 1),
(936, 'Jesús María', 27, '2020-05-08 00:33:56', 1, 1),
(937, 'Jordán', 27, '2020-05-08 00:33:56', 1, 1),
(938, 'La Belleza', 27, '2020-05-08 00:33:56', 1, 1),
(939, 'La Paz', 27, '2020-05-08 00:33:56', 1, 1),
(940, 'Landázuri', 27, '2020-05-08 00:33:56', 1, 1),
(941, 'Lebrija', 27, '2020-05-08 00:33:56', 1, 1),
(942, 'Los Santos', 27, '2020-05-08 00:33:56', 1, 1),
(943, 'Macaravita', 27, '2020-05-08 00:33:56', 1, 1),
(944, 'Matanza', 27, '2020-05-08 00:33:56', 1, 1),
(945, 'Mogotes', 27, '2020-05-08 00:33:56', 1, 1),
(946, 'Molagavita', 27, '2020-05-08 00:33:56', 1, 1),
(947, 'Málaga', 27, '2020-05-08 00:33:56', 1, 1),
(948, 'Ocamonte', 27, '2020-05-08 00:33:56', 1, 1),
(949, 'Oiba', 27, '2020-05-08 00:33:56', 1, 1),
(950, 'Onzaga', 27, '2020-05-08 00:33:56', 1, 1),
(951, 'Palmar', 27, '2020-05-08 00:33:56', 1, 1),
(952, 'Palmas del Socorro', 27, '2020-05-08 00:33:56', 1, 1),
(953, 'Pie de Cuesta', 27, '2020-05-08 00:33:56', 1, 1),
(954, 'Pinchote', 27, '2020-05-08 00:33:56', 1, 1),
(955, 'Puente Nacional', 27, '2020-05-08 00:33:56', 1, 1),
(956, 'Puerto Parra', 27, '2020-05-08 00:33:56', 1, 1),
(957, 'Puerto Wilches', 27, '2020-05-08 00:33:56', 1, 1),
(958, 'Páramo', 27, '2020-05-08 00:33:56', 1, 1),
(959, 'Rio Negro', 27, '2020-05-08 00:33:56', 1, 1),
(960, 'Sabana de Torres', 27, '2020-05-08 00:33:56', 1, 1),
(961, 'San Andrés', 27, '2020-05-08 00:33:56', 1, 1),
(962, 'San Benito', 27, '2020-05-08 00:33:56', 1, 1),
(963, 'San Gíl', 27, '2020-05-08 00:33:56', 1, 1),
(964, 'San Joaquín', 27, '2020-05-08 00:33:56', 1, 1),
(965, 'San José de Miranda', 27, '2020-05-08 00:33:56', 1, 1),
(966, 'San Miguel', 27, '2020-05-08 00:33:56', 1, 1),
(967, 'San Vicente del Chucurí', 27, '2020-05-08 00:33:56', 1, 1),
(968, 'Santa Bárbara', 27, '2020-05-08 00:33:56', 1, 1),
(969, 'Santa Helena del Opón', 27, '2020-05-08 00:33:56', 1, 1),
(970, 'Simacota', 27, '2020-05-08 00:33:56', 1, 1),
(971, 'Socorro', 27, '2020-05-08 00:33:56', 1, 1),
(972, 'Suaita', 27, '2020-05-08 00:33:56', 1, 1),
(973, 'Sucre', 27, '2020-05-08 00:33:56', 1, 1),
(974, 'Suratá', 27, '2020-05-08 00:33:56', 1, 1),
(975, 'Tona', 27, '2020-05-08 00:33:56', 1, 1),
(976, 'Valle de San José', 27, '2020-05-08 00:33:56', 1, 1),
(977, 'Vetas', 27, '2020-05-08 00:33:56', 1, 1),
(978, 'Villanueva', 27, '2020-05-08 00:33:56', 1, 1),
(979, 'Vélez', 27, '2020-05-08 00:33:56', 1, 1),
(980, 'Zapatoca', 27, '2020-05-08 00:33:56', 1, 1),
(981, 'Buenavista', 28, '2020-05-08 00:33:56', 1, 1),
(982, 'Caimito', 28, '2020-05-08 00:33:56', 1, 1),
(983, 'Chalán', 28, '2020-05-08 00:33:56', 1, 1),
(984, 'Colosó (Ricaurte)', 28, '2020-05-08 00:33:56', 1, 1),
(985, 'Corozal', 28, '2020-05-08 00:33:56', 1, 1);
INSERT INTO `municipios` (`id`, `nombre`, `fk_departamento`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(986, 'Coveñas', 28, '2020-05-08 00:33:56', 1, 1),
(987, 'El Roble', 28, '2020-05-08 00:33:56', 1, 1),
(988, 'Galeras (Nueva Granada)', 28, '2020-05-08 00:33:56', 1, 1),
(989, 'Guaranda', 28, '2020-05-08 00:33:56', 1, 1),
(990, 'La Unión', 28, '2020-05-08 00:33:56', 1, 1),
(991, 'Los Palmitos', 28, '2020-05-08 00:33:56', 1, 1),
(992, 'Majagual', 28, '2020-05-08 00:33:56', 1, 1),
(993, 'Morroa', 28, '2020-05-08 00:33:56', 1, 1),
(994, 'Ovejas', 28, '2020-05-08 00:33:56', 1, 1),
(995, 'Palmito', 28, '2020-05-08 00:33:56', 1, 1),
(996, 'Sampués', 28, '2020-05-08 00:33:56', 1, 1),
(997, 'San Benito Abad', 28, '2020-05-08 00:33:56', 1, 1),
(998, 'San Juan de Betulia', 28, '2020-05-08 00:33:56', 1, 1),
(999, 'San Marcos', 28, '2020-05-08 00:33:56', 1, 1),
(1000, 'San Onofre', 28, '2020-05-08 00:33:56', 1, 1),
(1001, 'San Pedro', 28, '2020-05-08 00:33:56', 1, 1),
(1002, 'Sincelejo', 28, '2020-05-08 00:33:56', 1, 1),
(1003, 'Sincé', 28, '2020-05-08 00:33:56', 1, 1),
(1004, 'Sucre', 28, '2020-05-08 00:33:56', 1, 1),
(1005, 'Tolú', 28, '2020-05-08 00:33:56', 1, 1),
(1006, 'Tolú Viejo', 28, '2020-05-08 00:33:56', 1, 1),
(1007, 'Alpujarra', 29, '2020-05-08 00:33:56', 1, 1),
(1008, 'Alvarado', 29, '2020-05-08 00:33:56', 1, 1),
(1009, 'Ambalema', 29, '2020-05-08 00:33:56', 1, 1),
(1010, 'Anzoátegui', 29, '2020-05-08 00:33:56', 1, 1),
(1011, 'Armero (Guayabal)', 29, '2020-05-08 00:33:56', 1, 1),
(1012, 'Ataco', 29, '2020-05-08 00:33:56', 1, 1),
(1013, 'Cajamarca', 29, '2020-05-08 00:33:56', 1, 1),
(1014, 'Carmen de Apicalá', 29, '2020-05-08 00:33:56', 1, 1),
(1015, 'Casabianca', 29, '2020-05-08 00:33:56', 1, 1),
(1016, 'Chaparral', 29, '2020-05-08 00:33:56', 1, 1),
(1017, 'Coello', 29, '2020-05-08 00:33:56', 1, 1),
(1018, 'Coyaima', 29, '2020-05-08 00:33:56', 1, 1),
(1019, 'Cunday', 29, '2020-05-08 00:33:56', 1, 1),
(1020, 'Dolores', 29, '2020-05-08 00:33:56', 1, 1),
(1021, 'Espinal', 29, '2020-05-08 00:33:56', 1, 1),
(1022, 'Falan', 29, '2020-05-08 00:33:56', 1, 1),
(1023, 'Flandes', 29, '2020-05-08 00:33:56', 1, 1),
(1024, 'Fresno', 29, '2020-05-08 00:33:56', 1, 1),
(1025, 'Guamo', 29, '2020-05-08 00:33:56', 1, 1),
(1026, 'Herveo', 29, '2020-05-08 00:33:56', 1, 1),
(1027, 'Honda', 29, '2020-05-08 00:33:56', 1, 1),
(1028, 'Ibagué', 29, '2020-05-08 00:33:56', 1, 1),
(1029, 'Icononzo', 29, '2020-05-08 00:33:56', 1, 1),
(1030, 'Lérida', 29, '2020-05-08 00:33:56', 1, 1),
(1031, 'Líbano', 29, '2020-05-08 00:33:56', 1, 1),
(1032, 'Mariquita', 29, '2020-05-08 00:33:56', 1, 1),
(1033, 'Melgar', 29, '2020-05-08 00:33:56', 1, 1),
(1034, 'Murillo', 29, '2020-05-08 00:33:56', 1, 1),
(1035, 'Natagaima', 29, '2020-05-08 00:33:56', 1, 1),
(1036, 'Ortega', 29, '2020-05-08 00:33:56', 1, 1),
(1037, 'Palocabildo', 29, '2020-05-08 00:33:56', 1, 1),
(1038, 'Piedras', 29, '2020-05-08 00:33:56', 1, 1),
(1039, 'Planadas', 29, '2020-05-08 00:33:56', 1, 1),
(1040, 'Prado', 29, '2020-05-08 00:33:56', 1, 1),
(1041, 'Purificación', 29, '2020-05-08 00:33:56', 1, 1),
(1042, 'Rioblanco', 29, '2020-05-08 00:33:56', 1, 1),
(1043, 'Roncesvalles', 29, '2020-05-08 00:33:56', 1, 1),
(1044, 'Rovira', 29, '2020-05-08 00:33:56', 1, 1),
(1045, 'Saldaña', 29, '2020-05-08 00:33:56', 1, 1),
(1046, 'San Antonio', 29, '2020-05-08 00:33:56', 1, 1),
(1047, 'San Luis', 29, '2020-05-08 00:33:56', 1, 1),
(1048, 'Santa Isabel', 29, '2020-05-08 00:33:56', 1, 1),
(1049, 'Suárez', 29, '2020-05-08 00:33:56', 1, 1),
(1050, 'Valle de San Juan', 29, '2020-05-08 00:33:56', 1, 1),
(1051, 'Venadillo', 29, '2020-05-08 00:33:56', 1, 1),
(1052, 'Villahermosa', 29, '2020-05-08 00:33:56', 1, 1),
(1053, 'Villarrica', 29, '2020-05-08 00:33:56', 1, 1),
(1054, 'Alcalá', 30, '2020-05-08 00:33:56', 1, 1),
(1055, 'Andalucía', 30, '2020-05-08 00:33:56', 1, 1),
(1056, 'Ansermanuevo', 30, '2020-05-08 00:33:56', 1, 1),
(1057, 'Argelia', 30, '2020-05-08 00:33:56', 1, 1),
(1058, 'Bolívar', 30, '2020-05-08 00:33:56', 1, 1),
(1059, 'Buenaventura', 30, '2020-05-08 00:33:56', 1, 1),
(1060, 'Buga', 30, '2020-05-08 00:33:56', 1, 1),
(1061, 'Bugalagrande', 30, '2020-05-08 00:33:56', 1, 1),
(1062, 'Caicedonia', 30, '2020-05-08 00:33:56', 1, 1),
(1063, 'Calima (Darién)', 30, '2020-05-08 00:33:56', 1, 1),
(1064, 'Cali', 30, '2020-05-08 00:33:56', 1, 1),
(1065, 'Candelaria', 30, '2020-05-08 00:33:56', 1, 1),
(1066, 'Cartago', 30, '2020-05-08 00:33:56', 1, 1),
(1067, 'Dagua', 30, '2020-05-08 00:33:56', 1, 1),
(1068, 'El Cairo', 30, '2020-05-08 00:33:56', 1, 1),
(1069, 'El Cerrito', 30, '2020-05-08 00:33:56', 1, 1),
(1070, 'El Dovio', 30, '2020-05-08 00:33:56', 1, 1),
(1071, 'El Águila', 30, '2020-05-08 00:33:56', 1, 1),
(1072, 'Florida', 30, '2020-05-08 00:33:56', 1, 1),
(1073, 'Ginebra', 30, '2020-05-08 00:33:56', 1, 1),
(1074, 'Guacarí', 30, '2020-05-08 00:33:56', 1, 1),
(1075, 'Jamundí', 30, '2020-05-08 00:33:56', 1, 1),
(1076, 'La Cumbre', 30, '2020-05-08 00:33:56', 1, 1),
(1077, 'La Unión', 30, '2020-05-08 00:33:56', 1, 1),
(1078, 'La Victoria', 30, '2020-05-08 00:33:56', 1, 1),
(1079, 'Obando', 30, '2020-05-08 00:33:56', 1, 1),
(1080, 'Palmira', 30, '2020-05-08 00:33:56', 1, 1),
(1081, 'Pradera', 30, '2020-05-08 00:33:56', 1, 1),
(1082, 'Restrepo', 30, '2020-05-08 00:33:56', 1, 1),
(1083, 'Riofrío', 30, '2020-05-08 00:33:56', 1, 1),
(1084, 'Roldanillo', 30, '2020-05-08 00:33:56', 1, 1),
(1085, 'San Pedro', 30, '2020-05-08 00:33:56', 1, 1),
(1086, 'Sevilla', 30, '2020-05-08 00:33:56', 1, 1),
(1087, 'Toro', 30, '2020-05-08 00:33:56', 1, 1),
(1088, 'Trujillo', 30, '2020-05-08 00:33:56', 1, 1),
(1089, 'Tulúa', 30, '2020-05-08 00:33:56', 1, 1),
(1090, 'Ulloa', 30, '2020-05-08 00:33:56', 1, 1),
(1091, 'Versalles', 30, '2020-05-08 00:33:56', 1, 1),
(1092, 'Vijes', 30, '2020-05-08 00:33:56', 1, 1),
(1093, 'Yotoco', 30, '2020-05-08 00:33:56', 1, 1),
(1094, 'Yumbo', 30, '2020-05-08 00:33:56', 1, 1),
(1095, 'Zarzal', 30, '2020-05-08 00:33:56', 1, 1),
(1096, 'Carurú', 31, '2020-05-08 00:33:56', 1, 1),
(1097, 'Mitú', 31, '2020-05-08 00:33:56', 1, 1),
(1098, 'Taraira', 31, '2020-05-08 00:33:56', 1, 1),
(1099, 'Cumaribo', 32, '2020-05-08 00:33:56', 1, 1),
(1100, 'La Primavera', 32, '2020-05-08 00:33:56', 1, 1),
(1101, 'Puerto Carreño', 32, '2020-05-08 00:33:56', 1, 1),
(1102, 'Santa Rosalía', 32, '2020-05-08 00:33:56', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id`, `nombre`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(1, 'Administrador', '2020-05-08 01:38:49', 1, 1),
(2, 'Usuario', '2020-05-08 01:39:12', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL DEFAULT '0',
  `presentacion` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `reg_invima` text DEFAULT NULL,
  `fk_finca` int(11) DEFAULT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `presentacion`, `descripcion`, `fecha_creacion`, `estado`, `reg_invima`, `fk_finca`, `fk_creador`) VALUES
(1, 'Bananos', NULL, NULL, '2020-06-01 23:16:52', 1, NULL, NULL, 1),
(2, 'manzana', NULL, NULL, '2020-07-06 23:16:28', 0, NULL, NULL, 1),
(3, 'Aguacate', NULL, NULL, '2020-07-14 22:18:13', 1, NULL, NULL, 1),
(4, 'piña', NULL, NULL, '2020-07-14 22:21:38', 1, NULL, NULL, 1),
(5, 'mango', NULL, NULL, '2020-07-14 22:21:47', 1, NULL, NULL, 1),
(6, 'guayaba', NULL, NULL, '2020-07-14 22:22:18', 1, NULL, NULL, 1),
(7, 'Yuca', NULL, NULL, '2020-07-14 22:32:43', 1, NULL, NULL, 1),
(8, 'Cilantro', NULL, NULL, '2020-07-14 22:32:49', 1, NULL, NULL, 1),
(9, 'Durazno', NULL, NULL, '2020-07-14 22:36:15', 1, NULL, NULL, 1),
(10, 'mamoncillo', NULL, NULL, '2020-07-15 21:01:58', 1, NULL, NULL, 1),
(11, 'toronja', NULL, NULL, '2020-07-15 21:02:03', 1, NULL, NULL, 1),
(12, 'sandia', NULL, NULL, '2020-07-15 21:02:17', 1, NULL, NULL, 1),
(13, 'guanabana', NULL, NULL, '2020-07-15 21:02:29', 1, NULL, NULL, 1),
(14, 'naranja', NULL, NULL, '2020-07-15 21:02:33', 1, NULL, NULL, 1),
(15, 'mandarina', NULL, NULL, '2020-07-15 21:02:42', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_derivados`
--

CREATE TABLE `productos_derivados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(300) NOT NULL,
  `fk_producto` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `productos_derivados`
--

INSERT INTO `productos_derivados` (`id`, `nombre`, `descripcion`, `fk_producto`, `estado`, `fecha_creacion`, `fk_creador`) VALUES
(1, 'guineo', 'banano chiquito', 1, 1, '2020-07-06 21:45:41', 1),
(2, 'banano chonto', 'banano verde', 1, 1, '2020-07-06 21:59:06', 1),
(3, 'manzana verde', 'soy del verde soy feliz ', 2, 1, '2020-07-06 23:16:53', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id` int(11) NOT NULL,
  `abreviacion` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id`, `abreviacion`, `nombre`, `fecha_creacion`, `estado`, `fk_creador`) VALUES
(1, 'CC', 'Cédula de ciudadania', '2020-05-26 19:06:12', 1, 1),
(2, 'CE', 'Cédula extranjera', '2020-05-26 19:06:12', 1, 1),
(3, 'PA', 'Pasaporte', '2020-05-26 19:06:33', 1, 1),
(4, 'NIT', 'Nro. Identif. Tributaria', '2020-05-26 19:06:49', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_persona`
--

CREATE TABLE `tipo_persona` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_persona`
--

INSERT INTO `tipo_persona` (`id`, `nombre`, `fecha_creacion`, `estado`, `fk_creador`) VALUES
(1, 'Natural', '2020-05-26 19:05:10', 1, 1),
(2, 'Jurídica', '2020-05-26 19:05:10', 1, 1);

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
  `codigo_recuperacion` text NOT NULL,
  `codigo_activacion` text NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `fk_tipo_documento`, `nro_documento`, `fk_tipo_persona`, `correo`, `nombres`, `apellidos`, `password`, `fecha_nacimiento`, `telefono`, `fk_perfil`, `estado`, `fecha_creacion`, `confirmado`, `codigo_recuperacion`, `codigo_activacion`, `fk_creador`) VALUES
(1, 1, '0', 1, 'admin@admin.com', 'admin', 'admin', '$2y$15$WpgfJ0hjPFRZJFqmdsSUP.RVeoMzXJ8/rUXSrt.XE07oE2fOh5Sti', '1998-09-11', '3103587032', 1, 1, '2020-05-08 21:09:51', 1, '', '', 1),
(3, 1, '1225091213', 1, 'anthourrego@gmail.com', 'Anthony Smidh', 'Urrego Pineda', '$2y$15$45D5QOZXr/O4RLtWAWWLZuNqeQRMBlDnGIHAAl.jecObGPXjgn2S2', '1998-09-11', '3103587032', 2, 1, '2020-07-14 23:31:17', 0, '', '$2y$15$GQR1BywwL4Fdhn9219/p9eaEbtgwtgz5wCOnEeukxln4RDMoGr3uu', 0),
(9, 1, '1088335957', 1, 'juanfa107@gmail.com', 'juan felipe', 'arenas moreno', '$2y$15$FOeRIdWbOtY8F9/3/HDtEOStGEbUrGbDssFTuxxssNlYJVV5j5gJy', '1993-07-19', '3115509915', 2, 1, '2020-07-12 20:39:39', 1, '$2y$15$B2wTwJkWXY1ydyHUhs2VMuPDv7ie5JqLXwu6SJFJ7l7RXxWSsGbYC', '$2y$15$P49V5la9TbEOYhYlyKN9Ae46SJgA7t2AgFZPomGXLGNWPoOAlpkLO', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_modulos`
--

CREATE TABLE `usuarios_modulos` (
  `id` int(11) NOT NULL,
  `fk_modulo` int(11) NOT NULL DEFAULT 0,
  `fk_usuario` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios_modulos`
--

INSERT INTO `usuarios_modulos` (`id`, `fk_modulo`, `fk_usuario`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(1, 1, 1, '2020-05-20 23:16:32', 1, 1),
(2, 2, 1, '2020-05-20 23:16:37', 1, 1),
(3, 3, 1, '2020-05-21 22:56:36', 1, 1),
(4, 4, 1, '2020-05-22 10:58:28', 1, 1),
(5, 5, 4, '2020-05-28 23:37:40', 1, 0),
(6, 1, 4, '2020-05-28 23:37:41', 1, 0),
(7, 5, 1, '2020-05-28 23:37:49', 1, 1),
(8, 6, 1, '2020-05-28 23:54:17', 1, 1),
(9, 7, 1, '2020-05-30 00:30:46', 1, 1),
(10, 8, 1, '2020-05-30 00:52:15', 1, 1),
(11, 9, 1, '2020-06-05 12:43:45', 1, 1),
(12, 10, 1, '2020-06-22 13:06:50', 1, 1),
(13, 11, 1, '2020-06-22 13:06:50', 1, 1),
(14, 12, 1, '2020-06-22 13:06:52', 1, 1),
(15, 13, 1, '2020-07-06 20:22:51', 1, 1),
(16, 14, 1, '2020-07-06 20:22:52', 1, 1),
(17, 15, 1, '2020-07-16 21:37:05', 1, 1),
(18, 1, 9, '2020-07-16 22:25:42', 1, 1),
(19, 9, 9, '2020-07-16 22:25:47', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `certificaciones`
--
ALTER TABLE `certificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cosechas`
--
ALTER TABLE `cosechas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cosechas_certificaciones`
--
ALTER TABLE `cosechas_certificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cosechas_productos_documentos`
--
ALTER TABLE `cosechas_productos_documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cosecha_oferta`
--
ALTER TABLE `cosecha_oferta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `fincas`
--
ALTER TABLE `fincas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fincas_tipos`
--
ALTER TABLE `fincas_tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulo_tipo`
--
ALTER TABLE `modulo_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos_derivados`
--
ALTER TABLE `productos_derivados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `certificaciones`
--
ALTER TABLE `certificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cosechas`
--
ALTER TABLE `cosechas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `cosechas_certificaciones`
--
ALTER TABLE `cosechas_certificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `cosechas_productos_documentos`
--
ALTER TABLE `cosechas_productos_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `cosecha_oferta`
--
ALTER TABLE `cosecha_oferta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `fincas`
--
ALTER TABLE `fincas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `fincas_tipos`
--
ALTER TABLE `fincas_tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `modulo_tipo`
--
ALTER TABLE `modulo_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1103;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `productos_derivados`
--
ALTER TABLE `productos_derivados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
