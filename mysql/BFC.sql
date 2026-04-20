-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 16-03-2026 a las 05:35:11
-- Versión del servidor: 8.0.45
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `BFC`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `usuario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `usuario`, `password`, `nombre`, `creado_en`) VALUES
(1, 'admin', '$2y$10$Mf.iz2XqBHdCdT3GMg1jD.XM8uNS.1DnwznJoNbJnIGlqA.f4NET.', 'Administrador Principal', '2026-02-10 19:50:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bailarines`
--

CREATE TABLE `bailarines` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` enum('M','F','Otro') COLLATE utf8mb4_unicode_ci DEFAULT 'Otro',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bailarines`
--

INSERT INTO `bailarines` (`id`, `nombre`, `email`, `telefono`, `genero`, `password`, `activo`, `creado_en`) VALUES
(4, 'Juan Candia', 'jdcm3002@gmail.com', '12872549', 'M', '$2y$10$bHcwiAP4LMM0V/ureyvF1ORxE2t4hCkELA331TjcUGSL136U9lDVq', 1, '2026-02-10 20:24:50'),
(5, 'Lucia Salas', 'luciasalascostas@gmail.com', '72706455', 'F', '$2y$10$MzJiBa/9ANwmTvCM5j8gZuLFOquncRVhq7GLH3eZUoTLI9nXgTOve', 1, '2026-02-19 20:44:06'),
(6, 'Paolo Jimenez', 'pajimenez.a@gmail.com', '', 'M', '$2y$10$S25EH.jGXQ2NhTtUeb2XhuediEL/0kqWdcZzXcYl/3p0EXA2zIOkO', 1, '2026-02-19 23:16:00'),
(7, 'Isabela Delgadillo', 'idelgadillogaldo@gmail.com', '', 'F', '$2y$10$LYhwnqMOMkb/58GpBFX.uuF.oqPHkecRoehK5MAm7jO5l7SNIyzCS', 1, '2026-02-19 23:16:37'),
(8, 'Salvador Antequera', 'salvadorantequeracanedo@gmail.com', '', 'M', '$2y$10$x6z7PVbCodGnEXaCZZDyi.TS1O0CtiacocuE8W0gXXeIgjRHkwmCm', 1, '2026-02-19 23:17:06'),
(9, 'Valeria Cirbian', 'valeriacirbian@gmail.com', '', 'F', '$2y$10$GsXUJ4fX6jxy3AIon8wuYeniR86om1N0zrlkBg1DC3buB7UrYqEe.', 1, '2026-02-19 23:17:35'),
(10, 'Isabel Torrez', 'isabeltorrez0507@gmail.com', '', 'F', '$2y$10$1EnIdeiApZVq1vtm47xcPu7YAq3Zvxq6sN9XWHnkDPZ2V0raSe69e', 1, '2026-02-19 23:18:01'),
(11, 'Esteban Torrez', 'estebantorrez2020@gmail.com', '', 'M', '$2y$10$TBbPmL7pcZMvvfjdqVaaj.kNKMxDGuVtAFSznqT5u9FmkYoOpfdKi', 1, '2026-02-19 23:18:27'),
(12, 'Natalia Becerra', 'natybecerra28@gmail.com', '', 'F', '$2y$10$8GYzLZhtBD1LpNuHlvSNJ.lznmGF8dCR9pxzlU3y7Wc58FM1Z9DJW', 1, '2026-02-19 23:18:56'),
(13, 'Camila Nogales', 'camilanogalesquiroz@gmail.com', '', 'F', '$2y$10$3nnYFJ284HCZy9IG2vHcUORtnpk/qRTP84GIqki1d2gw6n3SmGzGO', 1, '2026-02-19 23:58:29'),
(14, 'Celia Luna', 'celialuna137@gmail.com', '', 'F', '$2y$10$pAZAxU6f5H01cLZbwDM6duYVMdLB7unUprxy1mesWjIj3L4rBJOl2', 1, '2026-02-20 00:01:09'),
(15, 'Melani chiquie', 'chiquiemelaniep21@gmail.com', '', 'F', '$2y$10$YKxY4QPMvCH9QzlbJyxLpuuk/DQCul3MkwTrYW95NpdOzBVAK10Qi', 1, '2026-02-20 01:18:07'),
(16, 'Alejandra Gutierres', 'ale05325@gmail.com', '', 'F', '$2y$10$9Imc1rqki957NuTvbs/qCuNpXJu7De9y8eJ32l8G8E6sryIuMpYgu', 1, '2026-02-20 01:18:43'),
(17, 'Jessica Guerrero', 'guerrerojessica268@gmail.com', '', 'F', '$2y$10$lLDK9VGKRKRUwRLxdVE/yu5IqdIBHaJk8fepLqMOqJGFulwT5wydW', 1, '2026-02-20 01:19:06'),
(19, 'Veimar Torrez', 'veimartorrez@hotmail.com', '71706039', 'M', '$2y$10$ZY2b6nZc.8FO0oYrRYvlgOshufp0ZbtQG7TCy1EgiJF.VFAV/p4ZW', 1, '2026-03-06 01:57:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funciones`
--

CREATE TABLE `funciones` (
  `id` int NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `lugar` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `funciones`
--

INSERT INTO `funciones` (`id`, `nombre`, `fecha`, `lugar`, `descripcion`, `creado_en`) VALUES
(3, 'Boda', '2026-02-21 14:47:00', 'Salon de eventos la terraza', '', '2026-02-18 18:47:28'),
(4, 'Prueba', '2026-02-20 23:53:00', 'Mi casita', 'Este evento es de prueba \r\n', '2026-02-18 23:49:48'),
(5, 'Majito ', '2026-03-11 19:30:00', 'Teatro Acha ', '', '2026-03-06 01:43:43'),
(6, 'Danza Panama', '2026-04-15 00:00:00', 'Panama', 'Solo Alejandra y Paolo ', '2026-03-06 01:53:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int NOT NULL,
  `bailarin_id` int NOT NULL,
  `vestuario_id` int NOT NULL,
  `funcion_id` int NOT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_devolucion` datetime DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado','devuelto') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `observaciones` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `bailarin_id`, `vestuario_id`, `funcion_id`, `fecha_solicitud`, `fecha_devolucion`, `estado`, `observaciones`) VALUES
(6, 5, 4, 3, '2026-02-19 21:33:09', NULL, 'aprobado', '4'),
(7, 5, 15, 3, '2026-02-19 21:33:52', NULL, 'aprobado', 'Celeste'),
(8, 5, 16, 3, '2026-02-19 21:34:01', NULL, 'aprobado', 'Tumbo'),
(9, 4, 12, 3, '2026-02-19 21:36:14', NULL, 'aprobado', ''),
(10, 4, 14, 3, '2026-02-19 21:36:44', NULL, 'aprobado', ''),
(11, 4, 14, 3, '2026-02-19 21:36:49', NULL, 'aprobado', ''),
(12, 4, 14, 3, '2026-02-19 21:36:52', NULL, 'aprobado', ''),
(13, 4, 14, 3, '2026-02-19 21:36:56', NULL, 'aprobado', ''),
(14, 4, 8, 3, '2026-02-19 21:37:09', NULL, 'aprobado', ''),
(15, 4, 8, 3, '2026-02-19 21:37:32', '2026-02-19 17:38:19', 'devuelto', ''),
(16, 4, 8, 3, '2026-02-19 21:38:29', '2026-02-19 21:21:33', 'devuelto', ''),
(17, 4, 8, 3, '2026-02-19 21:38:47', NULL, 'aprobado', ''),
(18, 11, 9, 3, '2026-02-20 01:18:20', NULL, 'aprobado', ''),
(19, 13, 4, 3, '2026-02-20 01:18:31', NULL, 'aprobado', 'Sin n??mero CN'),
(20, 11, 14, 3, '2026-02-20 01:18:36', NULL, 'aprobado', ''),
(21, 12, 15, 3, '2026-02-20 01:19:08', NULL, 'aprobado', 'Crema'),
(22, 12, 16, 3, '2026-02-20 01:19:14', NULL, 'aprobado', ''),
(23, 12, 4, 3, '2026-02-20 01:19:25', '2026-03-06 01:56:10', 'devuelto', ''),
(24, 9, 4, 3, '2026-02-20 01:19:37', NULL, 'aprobado', ''),
(25, 9, 4, 3, '2026-02-20 01:19:55', '2026-02-19 21:20:36', 'devuelto', ''),
(26, 14, 16, 3, '2026-02-20 01:19:55', NULL, 'aprobado', ''),
(27, 11, 14, 3, '2026-02-20 01:20:23', NULL, 'aprobado', ''),
(28, 9, 5, 3, '2026-02-20 01:20:24', NULL, 'pendiente', ''),
(29, 11, 14, 3, '2026-02-20 01:20:30', NULL, 'aprobado', ''),
(30, 9, 15, 3, '2026-02-20 01:20:47', NULL, 'aprobado', ''),
(31, 6, 8, 4, '2026-02-20 01:20:51', NULL, 'aprobado', '15'),
(32, 6, 10, 3, '2026-02-20 01:21:03', NULL, 'aprobado', '15'),
(33, 9, 16, 3, '2026-02-20 01:21:03', NULL, 'aprobado', ''),
(34, 16, 4, 3, '2026-02-20 01:21:07', NULL, 'aprobado', ''),
(35, 6, 12, 3, '2026-02-20 01:21:19', NULL, 'aprobado', '15'),
(36, 16, 6, 3, '2026-02-20 01:21:26', NULL, 'aprobado', ''),
(37, 6, 14, 3, '2026-02-20 01:21:30', NULL, 'aprobado', ''),
(38, 14, 15, 3, '2026-02-20 01:21:37', NULL, 'aprobado', 'Blusa naranja'),
(39, 16, 16, 3, '2026-02-20 01:21:54', NULL, 'aprobado', 'Azul con blanco'),
(40, 14, 4, 3, '2026-02-20 01:22:31', NULL, 'aprobado', 'Negro'),
(41, 7, 4, 3, '2026-02-20 01:22:35', NULL, 'aprobado', 'Talla 7,color negro'),
(42, 14, 5, 3, '2026-02-20 01:22:57', NULL, 'aprobado', 'Verde'),
(43, 16, 15, 3, '2026-02-20 01:23:42', NULL, 'aprobado', 'Blanca'),
(44, 15, 4, 3, '2026-02-20 01:24:23', NULL, 'aprobado', ''),
(45, 15, 6, 3, '2026-02-20 01:25:15', NULL, 'aprobado', 'Fucsia'),
(46, 16, 15, 3, '2026-02-20 01:25:38', NULL, 'aprobado', 'Blanca'),
(47, 12, 4, 3, '2026-02-20 01:25:44', '2026-02-19 21:28:19', 'devuelto', ''),
(48, 12, 4, 3, '2026-02-20 01:27:19', NULL, 'aprobado', ''),
(49, 6, 14, 3, '2026-02-20 01:27:25', NULL, 'aprobado', ''),
(50, 6, 14, 3, '2026-02-20 01:27:41', NULL, 'aprobado', ''),
(51, 11, 14, 3, '2026-02-20 01:27:54', '2026-02-19 21:28:40', 'devuelto', ''),
(52, 11, 14, 3, '2026-02-20 01:28:51', NULL, 'aprobado', ''),
(53, 5, 43, 5, '2026-03-06 01:44:15', NULL, 'aprobado', ''),
(54, 6, 42, 5, '2026-03-06 01:50:25', NULL, 'aprobado', ''),
(55, 16, 28, 5, '2026-03-06 01:53:09', '2026-03-06 01:53:41', 'devuelto', ''),
(56, 16, 28, 5, '2026-03-06 02:01:44', '2026-03-06 02:04:39', 'devuelto', 'Unico'),
(57, 16, 29, 5, '2026-03-06 02:01:58', '2026-03-06 02:04:49', 'devuelto', 'Unico'),
(58, 16, 28, 5, '2026-03-06 02:02:06', NULL, 'aprobado', ''),
(59, 16, 29, 5, '2026-03-06 02:03:00', NULL, 'aprobado', 'Única'),
(60, 16, 30, 5, '2026-03-06 02:03:10', NULL, 'aprobado', 'Única'),
(61, 16, 31, 5, '2026-03-06 02:03:20', NULL, 'aprobado', 'Única'),
(62, 16, 28, 5, '2026-03-06 02:03:54', NULL, 'aprobado', 'Única'),
(63, 9, 40, 5, '2026-03-06 02:09:33', NULL, 'aprobado', 'Color tumbo'),
(64, 9, 41, 5, '2026-03-06 02:09:59', NULL, 'aprobado', 'Color celeste'),
(65, 9, 43, 5, '2026-03-06 02:10:55', NULL, 'aprobado', 'Solo fustes color verde');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vestuarios`
--

CREATE TABLE `vestuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `talla` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` enum('Hombre','Mujer','Unisex') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad_total` int DEFAULT '1',
  `cantidad_disponible` int DEFAULT '1',
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vestuarios`
--

INSERT INTO `vestuarios` (`id`, `nombre`, `descripcion`, `talla`, `genero`, `cantidad_total`, `cantidad_disponible`, `imagen`, `creado_en`) VALUES
(4, 'Almillas ', '', 'unica', 'Mujer', 11, 2, '', '2026-02-19 20:47:15'),
(5, 'Chumpis con chuspas ', '', 'unica', 'Mujer', 10, 0, '', '2026-02-19 20:47:49'),
(6, 'Aguayo', '', 'unica', 'Unisex', 15, 13, '', '2026-02-19 20:48:20'),
(7, 'Sombrero de oveja', '', 'unica', 'Unisex', 20, 20, '', '2026-02-19 20:48:49'),
(8, 'Lluchus(chulo grueso)', '', 'unica', 'Hombre', 6, 7, '', '2026-02-19 20:51:09'),
(9, 'Pantalon de bayeta ', '', 'negro', 'Hombre', 4, 4, '', '2026-02-19 20:52:22'),
(10, 'Pantalon de bayeta ', '', 'blanco', 'Hombre', 4, 4, '', '2026-02-19 20:52:42'),
(11, 'Camisa de tocuyo', '', 'unica', 'Hombre', 3, 10, '', '2026-02-19 20:53:49'),
(12, 'Faja Tinku ', '', 'morado ', 'Hombre', 4, 4, '', '2026-02-19 20:54:34'),
(13, 'Faja Tinku ', '', 'A cuadros', 'Hombre', 4, 6, '', '2026-02-19 20:55:17'),
(14, 'Chalinas Tinku', '', '', 'Unisex', 18, 4, '', '2026-02-19 20:56:05'),
(15, 'Blusa de encaje Cochabamba', '', 'unica', 'Mujer', 6, 0, '', '2026-02-19 20:57:11'),
(16, 'Pollera elegante cochabamba y tullmas', '', 'unica', 'Mujer', 6, 1, '', '2026-02-19 20:57:49'),
(17, 'Mantas de encaje', '', 'unica', 'Mujer', 2, 2, '', '2026-02-19 20:58:33'),
(18, 'Manta ', '', 'blanca', 'Mujer', 2, 2, '', '2026-02-19 20:58:53'),
(19, 'Manta ', '', 'Guinda', 'Mujer', 2, 2, '', '2026-02-19 20:59:08'),
(20, 'Manta ', '', 'Ploma', 'Mujer', 2, 2, '', '2026-02-19 20:59:22'),
(21, 'Camisa de tocuyo', '', '', 'Mujer', 5, 5, '', '2026-02-19 21:07:38'),
(22, 'Caporales botas pantal??n sombrero y faja ', '', 'unica', 'Hombre', 10, 9, '', '2026-02-19 21:18:55'),
(23, 'Caporales chaqueta', '', 'unica', 'Hombre', 10, 10, '', '2026-02-19 21:20:58'),
(24, 'Caporales blusa cors??   ', '', '', 'Mujer', 10, 10, '', '2026-02-19 21:22:04'),
(25, 'Caporales pollera tullmas y sombrero', '', '10', 'Mujer', 10, 1, '', '2026-02-19 21:23:08'),
(26, 'Caporal tacos', '', '', 'Mujer', 10, 10, '', '2026-02-19 21:23:32'),
(27, 'Pantalones Oriente', '', 'blancos', 'Hombre', 2, 2, '', '2026-02-19 21:25:35'),
(28, 'Pujllay acsu', '', '', 'Mujer', 6, 4, '', '2026-03-05 23:59:54'),
(29, 'pujllay almilla', '', '', 'Mujer', 6, 5, '', '2026-03-06 00:00:10'),
(30, 'pujllay sombrero', '', '', 'Mujer', 6, 5, '', '2026-03-06 00:00:24'),
(31, 'pujllay cintas con monedas', '', '', 'Mujer', 6, 5, '', '2026-03-06 00:00:46'),
(32, 'pujllay topos', '', '', 'Mujer', 6, 6, '', '2026-03-06 00:01:03'),
(33, 'Pujllay Saco', '', '', 'Hombre', 6, 6, '', '2026-03-06 00:01:28'),
(34, 'pujllay pantalón negro', '', '', 'Hombre', 6, 6, '', '2026-03-06 01:32:13'),
(35, 'Pujllay poncho', '', '', 'Hombre', 6, 6, '', '2026-03-06 01:32:37'),
(36, 'Pañoleta fucsia', '	', '', 'Hombre', 10, 10, '', '2026-03-06 01:38:03'),
(37, 'Pañoleta amarilla ', '', '', 'Hombre', 12, 12, '', '2026-03-06 01:38:20'),
(38, 'Pujllay flores montera  ', '', '', 'Hombre', 6, 6, '', '2026-03-06 01:38:47'),
(39, 'Cofia', '', '', 'Hombre', 6, 6, '', '2026-03-06 01:39:08'),
(40, 'pollera chuquisaqueña ', '', '', 'Mujer', 6, 5, '', '2026-03-06 01:39:58'),
(41, 'Blusa chuquisaqueña', '', '', 'Mujer', 6, 5, '', '2026-03-06 01:40:09'),
(42, 'Leva ', '', 'negra', 'Hombre', 6, 5, '', '2026-03-06 01:40:39'),
(43, 'traje cantarina', '3 fustes, 1 pollera, 1 blusa', '', 'Mujer', 4, 2, '', '2026-03-06 01:41:32');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `bailarines`
--
ALTER TABLE `bailarines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `funciones`
--
ALTER TABLE `funciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bailarin_id` (`bailarin_id`),
  ADD KEY `vestuario_id` (`vestuario_id`),
  ADD KEY `funcion_id` (`funcion_id`);

--
-- Indices de la tabla `vestuarios`
--
ALTER TABLE `vestuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `bailarines`
--
ALTER TABLE `bailarines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `funciones`
--
ALTER TABLE `funciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `vestuarios`
--
ALTER TABLE `vestuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`bailarin_id`) REFERENCES `bailarines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`vestuario_id`) REFERENCES `vestuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_3` FOREIGN KEY (`funcion_id`) REFERENCES `funciones` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
