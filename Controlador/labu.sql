-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-09-2025 a las 21:22:17
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
-- Base de datos: `labu`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conversaciones`
--

CREATE TABLE `conversaciones` (
  `id_conversacion` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_mensaje` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre`, `descripcion`, `foto_url`, `activo`, `fecha_creacion`) VALUES
(1, 'Electricista', 'Trabajos de electricidad en el hogar, comercios o industrias', 'img/especialidades/electricista.webp', 1, '2025-09-12 18:00:29'),
(2, 'Plomero', 'Reparación e instalación de cañerías, sanitarios y gas', 'img/especialidades/plomero.jpg', 1, '2025-09-12 18:00:29'),
(3, 'Carpintero', 'Fabricación y reparación de muebles y estructuras de madera', 'img/especialidades/carpintero.webp', 1, '2025-09-12 18:00:29'),
(20, 'Pintor', 'Pintura de interiores y exteriores, restauración de superficies', 'img/especialidades/pintor.jpg', 1, '2025-09-12 18:28:42'),
(21, 'Jardinero', 'Diseño, mantenimiento y cuidado de jardines y áreas verdes', 'img/especialidades/jardinero.webp', 1, '2025-09-12 18:28:42'),
(22, 'Albañil', 'Construcción, reformas y trabajos en mampostería y cemento', 'img/especialidades/albanil.jpg', 1, '2025-09-12 18:28:42'),
(23, 'Cerrajero', 'Apertura de cerraduras, cambio de llaves y sistemas de seguridad', 'img/especialidades/cerrajero.jpg', 1, '2025-09-12 18:28:42'),
(24, 'Gasista', 'Instalación y reparación de sistemas de gas y calefacción', 'img/especialidades/gasista.jpg', 1, '2025-09-12 18:28:42'),
(25, 'Herrero', 'Fabricación y reparación de estructuras metálicas, rejas, portones', 'img/especialidades/herrero.webp', 1, '2025-09-12 18:28:42'),
(26, 'Mecánico', 'Reparación y mantenimiento de vehículos automotores', 'img/especialidades/mecanico.webp', 1, '2025-09-12 18:28:42'),
(27, 'Tecnico de PC', 'Reparación y mantenimiento de computadoras y notebooks', 'img/especialidades/tecnico_pc.jpg', 1, '2025-09-12 18:28:42'),
(28, 'Tecnico de Celulares', 'Reparación de smartphones, tablets y dispositivos móviles', 'img/especialidades/tecnico_celulares.jpg', 1, '2025-09-12 18:28:42'),
(29, 'Mudanzas', 'Transporte de muebles y pertenencias de un lugar a otro', 'img/especialidades/mudanzas.jpg', 1, '2025-09-12 18:28:42'),
(30, 'Niñera', 'Cuidado de niños, asistencia y acompañamiento infantil', 'img/especialidades/ninera.jpg', 1, '2025-09-12 18:28:42'),
(31, 'Cuidado de Adultos Mayores', 'Asistencia y acompañamiento de personas mayores', 'img/especialidades/adultos_mayores.png', 1, '2025-09-12 18:28:42'),
(32, 'Limpieza', 'Servicios de limpieza de hogares, oficinas y locales', 'img/especialidades/limpieza.jpg', 1, '2025-09-12 18:28:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidades`
--

CREATE TABLE `localidades` (
  `id_localidad` int(11) NOT NULL,
  `nombre_localidad` varchar(100) NOT NULL,
  `id_provincia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `localidades`
--

INSERT INTO `localidades` (`id_localidad`, `nombre_localidad`, `id_provincia`) VALUES
(1, 'La Plata', 1),
(2, 'Mar del Plata', 1),
(3, 'Bahía Blanca', 1),
(4, 'Tandil', 1),
(5, 'San Isidro', 1),
(6, 'Pilar', 1),
(7, 'Lomas de Zamora', 1),
(8, 'Quilmes', 1),
(9, 'Morón', 1),
(10, 'Lanús', 1),
(11, 'Merlo', 1),
(12, 'Almirante Brown', 1),
(13, 'Florencio Varela', 1),
(14, 'Berazategui', 1),
(15, 'Malvinas Argentinas', 1),
(16, 'Escobar', 1),
(17, 'Tigre', 1),
(18, 'San Fernando', 1),
(19, 'Vicente López', 1),
(20, 'La Matanza', 1),
(21, 'General Pueyrredón', 1),
(22, 'Tres de Febrero', 1),
(23, 'Agronomía', 2),
(24, 'Almagro', 2),
(25, 'Balvanera', 2),
(26, 'Barracas', 2),
(27, 'Belgrano', 2),
(28, 'Boedo', 2),
(29, 'Caballito', 2),
(30, 'Chacarita', 2),
(31, 'Coghlan', 2),
(32, 'Colegiales', 2),
(33, 'Constitución', 2),
(34, 'Flores', 2),
(35, 'Floresta', 2),
(36, 'La Boca', 2),
(37, 'La Paternal', 2),
(38, 'Liniers', 2),
(39, 'Mataderos', 2),
(40, 'Monte Castro', 2),
(41, 'Monserrat', 2),
(42, 'Nueva Pompeya', 2),
(43, 'Núñez', 2),
(44, 'Palermo', 2),
(45, 'Parque Avellaneda', 2),
(46, 'Parque Chacabuco', 2),
(47, 'Parque Chas', 2),
(48, 'Parque Patricios', 2),
(49, 'Puerto Madero', 2),
(50, 'Recoleta', 2),
(51, 'Retiro', 2),
(52, 'Saavedra', 2),
(53, 'San Cristóbal', 2),
(54, 'San Nicolás', 2),
(55, 'San Telmo', 2),
(56, 'Vélez Sársfield', 2),
(57, 'Versalles', 2),
(58, 'Villa Crespo', 2),
(59, 'Villa Devoto', 2),
(60, 'Villa General Mitre', 2),
(61, 'Villa Lugano', 2),
(62, 'Villa Luro', 2),
(63, 'Villa Ortúzar', 2),
(64, 'Villa Pueyrredón', 2),
(65, 'Villa Real', 2),
(66, 'Villa Riachuelo', 2),
(67, 'Villa Santa Rita', 2),
(68, 'Villa Soldati', 2),
(69, 'Villa Urquiza', 2),
(70, 'San Fernando del Valle de Catamarca', 3),
(71, 'Belén', 3),
(72, 'Santa María', 3),
(73, 'Tinogasta', 3),
(74, 'Andalgalá', 3),
(75, 'Resistencia', 4),
(76, 'Barranqueras', 4),
(77, 'Charata', 4),
(78, 'Castelli', 4),
(79, 'Presidencia Roque Sáenz Peña', 4),
(80, 'Rawson', 5),
(81, 'Comodoro Rivadavia', 5),
(82, 'Puerto Madryn', 5),
(83, 'Trelew', 5),
(84, 'Esquel', 5),
(85, 'Córdoba', 6),
(86, 'Río Cuarto', 6),
(87, 'Villa Carlos Paz', 6),
(88, 'San Francisco', 6),
(89, 'Alta Gracia', 6),
(90, 'Corrientes', 7),
(91, 'Goya', 7),
(92, 'Curuzú Cuatiá', 7),
(93, 'Santo Tomé', 7),
(94, 'Mercedes', 7),
(95, 'Paraná', 8),
(96, 'Concordia', 8),
(97, 'Gualeguaychú', 8),
(98, 'Concepción del Uruguay', 8),
(99, 'Federación', 8),
(100, 'Formosa', 9),
(101, 'Clorinda', 9),
(102, 'El Colorado', 9),
(103, 'Pirané', 9),
(104, 'San Salvador de Jujuy', 10),
(105, 'Palpalá', 10),
(106, 'San Pedro', 10),
(107, 'Libertador General San Martín', 10),
(108, 'Santa Rosa', 11),
(109, 'General Pico', 11),
(110, 'Toay', 11),
(111, 'General Acha', 11),
(112, 'La Rioja', 12),
(113, 'Chilecito', 12),
(114, 'Aimogasta', 12),
(115, 'Chamical', 12),
(116, 'Mendoza', 13),
(117, 'San Rafael', 13),
(118, 'Godoy Cruz', 13),
(119, 'Maipú', 13),
(120, 'Luján de Cuyo', 13),
(121, 'Posadas', 14),
(122, 'Oberá', 14),
(123, 'Eldorado', 14),
(124, 'Puerto Iguazú', 14),
(125, 'Neuquén', 15),
(126, 'Centenario', 15),
(127, 'Cutral Có', 15),
(128, 'San Martín de los Andes', 15),
(129, 'Viedma', 16),
(130, 'General Roca', 16),
(131, 'San Carlos de Bariloche', 16),
(132, 'Cipolletti', 16),
(133, 'Salta', 17),
(134, 'San Ramón de la Nueva Orán', 17),
(135, 'Tartagal', 17),
(136, 'Cafayate', 17),
(137, 'San Juan', 18),
(138, 'Rawson', 18),
(139, 'Rivadavia', 18),
(140, 'Chimbas', 18),
(141, 'San Luis', 19),
(142, 'Villa Mercedes', 19),
(143, 'Merlo', 19),
(144, 'La Punta', 19),
(145, 'Río Gallegos', 20),
(146, 'Caleta Olivia', 20),
(147, 'El Calafate', 20),
(148, 'Las Heras', 20),
(149, 'Santa Fe', 21),
(150, 'Rosario', 21),
(151, 'Rafaela', 21),
(152, 'Reconquista', 21),
(153, 'Venado Tuerto', 21),
(154, 'Santiago del Estero', 22),
(155, 'La Banda', 22),
(156, 'Termas de Río Hondo', 22),
(157, 'Frías', 22),
(158, 'Ushuaia', 23),
(159, 'Río Grande', 23),
(160, 'Tolhuin', 23),
(161, 'San Miguel de Tucumán', 24),
(162, 'Yerba Buena', 24),
(163, 'Tafí Viejo', 24),
(164, 'Concepción', 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `id_conversacion` int(11) NOT NULL,
  `id_remitente` int(11) NOT NULL,
  `contenido` text DEFAULT NULL,
  `tipo` enum('texto','imagen','archivo') DEFAULT 'texto',
  `url_archivo` varchar(255) DEFAULT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes_conversacion`
--

CREATE TABLE `participantes_conversacion` (
  `id_conversacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `id_provincia` int(11) NOT NULL,
  `nombre_provincia` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`id_provincia`, `nombre_provincia`) VALUES
(1, 'Buenos Aires'),
(2, 'Ciudad Autónoma de Buenos Aires'),
(3, 'Catamarca'),
(4, 'Chaco'),
(5, 'Chubut'),
(6, 'Córdoba'),
(7, 'Corrientes'),
(8, 'Entre Ríos'),
(9, 'Formosa'),
(10, 'Jujuy'),
(11, 'La Pampa'),
(12, 'La Rioja'),
(13, 'Mendoza'),
(14, 'Misiones'),
(15, 'Neuquén'),
(16, 'Río Negro'),
(17, 'Salta'),
(18, 'San Juan'),
(19, 'San Luis'),
(20, 'Santa Cruz'),
(21, 'Santa Fe'),
(22, 'Santiago del Estero'),
(23, 'Tierra del Fuego, Antártida e Islas del Atlántico Sur'),
(24, 'Tucumán');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reseñas`
--

CREATE TABLE `reseñas` (
  `id_review` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `calificacion` int(11) DEFAULT NULL CHECK (`calificacion` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre_servicio` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `id_trabajador` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `descripcion_trabajo` text DEFAULT NULL,
  `puntaje_promedio` decimal(3,2) DEFAULT 0.00,
  `cantidad_reviews` int(11) DEFAULT 0,
  `plan_suscripcion` enum('Free','Basico','Premium') DEFAULT 'Free',
  `fecha_inicio_plan` date DEFAULT NULL,
  `fecha_fin_plan` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `id_especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('M','F','Otro') DEFAULT 'Otro',
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `bio` text DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `id_localidad` int(11) DEFAULT NULL,
  `es_trabajador` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `dni`, `fecha_nacimiento`, `genero`, `telefono`, `email`, `contraseña`, `fecha_creacion`, `bio`, `foto_perfil`, `id_localidad`, `es_trabajador`) VALUES
(5, 'Pedro', 'Coppola', '47515094', '2007-01-05', 'M', '01139131817', 'pedroicoppola@gmail.com', '$2y$10$tW/wFGv68MIEdKg0gPLoEu6dZroF83RcdobdV9yie1Nh1xVLGOci2', '2025-09-12 17:00:28', NULL, NULL, 7, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  ADD PRIMARY KEY (`id_conversacion`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `localidades`
--
ALTER TABLE `localidades`
  ADD PRIMARY KEY (`id_localidad`),
  ADD KEY `id_provincia` (`id_provincia`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_conversacion` (`id_conversacion`),
  ADD KEY `id_remitente` (`id_remitente`);

--
-- Indices de la tabla `participantes_conversacion`
--
ALTER TABLE `participantes_conversacion`
  ADD PRIMARY KEY (`id_conversacion`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`id_provincia`);

--
-- Indices de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_trabajador` (`id_trabajador`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`id_trabajador`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_trabajador_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_localidad` (`id_localidad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  MODIFY `id_conversacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `localidades`
--
ALTER TABLE `localidades`
  MODIFY `id_localidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `provincias`
--
ALTER TABLE `provincias`
  MODIFY `id_provincia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `localidades`
--
ALTER TABLE `localidades`
  ADD CONSTRAINT `localidades_ibfk_1` FOREIGN KEY (`id_provincia`) REFERENCES `provincias` (`id_provincia`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`id_conversacion`) REFERENCES `conversaciones` (`id_conversacion`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_remitente`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `participantes_conversacion`
--
ALTER TABLE `participantes_conversacion`
  ADD CONSTRAINT `participantes_conversacion_ibfk_1` FOREIGN KEY (`id_conversacion`) REFERENCES `conversaciones` (`id_conversacion`) ON DELETE CASCADE,
  ADD CONSTRAINT `participantes_conversacion_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reseñas`
--
ALTER TABLE `reseñas`
  ADD CONSTRAINT `reseñas_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`),
  ADD CONSTRAINT `reseñas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD CONSTRAINT `fk_trabajador_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trabajadores_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_localidad`) REFERENCES `localidades` (`id_localidad`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
