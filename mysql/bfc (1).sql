-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-02-2026 a las 21:08:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bfc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
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
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `genero` enum('M','F','Otro') DEFAULT 'Otro',
  `password` varchar(255) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bailarines`
--

INSERT INTO `bailarines` (`id`, `nombre`, `email`, `telefono`, `genero`, `password`, `activo`, `creado_en`) VALUES
(4, 'Juan Candia', 'jdcm3002@gmail.com', '12872549', 'M', '$2y$10$bHcwiAP4LMM0V/ureyvF1ORxE2t4hCkELA331TjcUGSL136U9lDVq', 1, '2026-02-10 20:24:50'),
(5, 'Lucía Salas', 'luciasalascostas@gmail.com', '72706455', 'F', '$2y$10$MzJiBa/9ANwmTvCM5j8gZuLFOquncRVhq7GLH3eZUoTLI9nXgTOve', 1, '2026-02-19 20:44:06'),
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
(18, 'Veimar Torrez', 'veimartorrez76@gmail.com', '', 'M', '$2y$10$l8fzRjTDWzS6k5LI4weX4eBsXC4Gja5BmclifAWquZAeqc92zTyfW', 1, '2026-02-20 01:34:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funciones`
--

CREATE TABLE `funciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `fecha` datetime NOT NULL,
  `lugar` varchar(150) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `funciones`
--

INSERT INTO `funciones` (`id`, `nombre`, `fecha`, `lugar`, `descripcion`, `creado_en`) VALUES
(3, 'Boda', '2026-02-21 14:47:00', 'Salon de eventos la terraza', '', '2026-02-18 18:47:28'),
(4, 'Prueba', '2026-02-20 23:53:00', 'Mi casita', 'Este evento es de prueba \r\n', '2026-02-18 23:49:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL,
  `bailarin_id` int(11) NOT NULL,
  `vestuario_id` int(11) NOT NULL,
  `funcion_id` int(11) NOT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_devolucion` datetime DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado','devuelto') DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL
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
(19, 13, 4, 3, '2026-02-20 01:18:31', NULL, 'aprobado', 'Sin número CN'),
(20, 11, 14, 3, '2026-02-20 01:18:36', NULL, 'aprobado', ''),
(21, 12, 15, 3, '2026-02-20 01:19:08', NULL, 'aprobado', 'Crema'),
(22, 12, 16, 3, '2026-02-20 01:19:14', NULL, 'aprobado', ''),
(23, 12, 4, 3, '2026-02-20 01:19:25', NULL, 'aprobado', ''),
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
(52, 11, 14, 3, '2026-02-20 01:28:51', NULL, 'aprobado', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vestuarios`
--

CREATE TABLE `vestuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `talla` varchar(10) DEFAULT NULL,
  `genero` enum('Hombre','Mujer','Unisex') NOT NULL,
  `cantidad_total` int(11) DEFAULT 1,
  `cantidad_disponible` int(11) DEFAULT 1,
  `imagen` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vestuarios`
--

INSERT INTO `vestuarios` (`id`, `nombre`, `descripcion`, `talla`, `genero`, `cantidad_total`, `cantidad_disponible`, `imagen`, `creado_en`) VALUES
(4, 'Almillas ', '', 'unica', 'Mujer', 11, 1, '', '2026-02-19 20:47:15'),
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
(22, 'Caporales botas pantalón sombrero y faja ', '', 'unica', 'Hombre', 10, 9, '', '2026-02-19 21:18:55'),
(23, 'Caporales chaqueta', '', 'unica', 'Hombre', 10, 10, '', '2026-02-19 21:20:58'),
(24, 'Caporales blusa corsé   ', '', '', 'Mujer', 10, 10, '', '2026-02-19 21:22:04'),
(25, 'Caporales pollera tullmas y sombrero', '', '10', 'Mujer', 10, 1, '', '2026-02-19 21:23:08'),
(26, 'Caporal tacos', '', '', 'Mujer', 10, 10, '', '2026-02-19 21:23:32'),
(27, 'Pantalones Oriente', '', 'blancos', 'Hombre', 2, 2, '', '2026-02-19 21:25:35');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `bailarines`
--
ALTER TABLE `bailarines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `funciones`
--
ALTER TABLE `funciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `vestuarios`
--
ALTER TABLE `vestuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
