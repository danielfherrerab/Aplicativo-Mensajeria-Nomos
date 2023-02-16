-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2022 a las 23:12:23
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--


CREATE TABLE `encargos` (
  `Id_encargo` int(10) NOT NULL,
  `Id_usuario` int(10) DEFAULT NULL,
  `Id_mensajero` int(10) DEFAULT NULL,
  `descripcion` varchar(60) NOT NULL,
  `Fecha_registrada` datetime NOT NULL,
  `Fecha_requerida` datetime NOT NULL,
  `Fecha_completado` datetime NOT NULL,
  `Observacion` varchar(255) NOT NULL,
  `Foto_encargo` varchar(255) NOT NULL,
  `Estado` varchar(20) NOT NULL,
  `visto` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `encargos`
--

INSERT INTO `encargos` (`Id_encargo`, `Id_usuario`, `Id_mensajero`, `descripcion`, `Fecha_registrada`, `Fecha_requerida`, `Fecha_completado`, `Observacion`, `Foto_encargo`, `Estado`, `visto`) VALUES
(1, 5, 2, 'Se debe enviar a...', '2022-11-18 13:41:46', '2022-11-19 13:41:46', '2022-11-21 07:01:46', '', '1301base de datos-Diagrama.png', 'pendiente', 1),
(2, 5, 2, 'Se debe enviar a... juanito perez', '2022-11-21 13:02:21', '2022-11-22 13:02:21', '2022-11-22 16:18:23', '', '', 'pendiente', 1),
(3, 4, 2, 'efijserdjhgfrejrgn', '2022-11-22 00:00:00', '2022-11-30 00:00:00', '2022-11-22 16:22:00', '', '', 'completado', 0),
(4, 4, 2, 'efijserdjhgfrejrgn', '2022-11-22 00:00:00', '2022-11-23 00:00:00', '2022-11-22 16:18:53', '', '', 'completado', 0),
(5, 4, 2, 'efijserdjhgfrejrgn', '2022-11-24 00:00:00', '2022-11-25 00:00:00', '0000-00-00 00:00:00', '', '', 'pendiente', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msg_enc`
--

CREATE TABLE `msg_enc` (
  `Id_usuario` int(10) NOT NULL,
  `Encargados` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `msg_enc`
--

INSERT INTO `msg_enc` (`Id_usuario`, `Encargados`) VALUES
(2, '4,5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `Id_rol` int(10) NOT NULL,
  `Rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`Id_rol`, `Rol`) VALUES
(1, 'administrador'),
(2, 'encargado'),
(3, 'mensajero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Id_usuario` int(10) NOT NULL,
  `Nombre_usuario` varchar(30) NOT NULL,
  `Correo_usuario` varchar(60) NOT NULL,
  `Clave_usuario` varchar(60) NOT NULL,
  `Id_rol` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id_usuario`, `Nombre_usuario`, `Correo_usuario`, `Clave_usuario`, `Id_rol`) VALUES
(2, 'Juan', 'juan@gmail.com', '$2y$10$JLW/p9RgC1igLq5o6b63E.JfpxXXZOOszRWSAxidrIOcPFbnRrp96', 3),
(4, 'administrador', 'admin@admin.com', '$2y$10$Glrtp/Nx6tYRvmLXnfnxG.v7BSoj.Qwr.9GKWOqlgqoRumJwM2Dti', 1),
(5, 'encargado', 'enc@gmail.com', '$2y$10$ZOXmgdQSrOHosfys7e9yr.xyIFWWBe4XuV3zHB.WFXXTNZD6cgOTe', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encargos`
--
ALTER TABLE `encargos`
  ADD PRIMARY KEY (`Id_encargo`),
  ADD KEY `Id_usuario` (`Id_usuario`),
  ADD KEY `Id_mensajero` (`Id_mensajero`);

--
-- Indices de la tabla `msg_enc`
--
ALTER TABLE `msg_enc`
  ADD KEY `Id_usuario` (`Id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`Id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id_usuario`),
  ADD KEY `Id_rol` (`Id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `encargos`
--
ALTER TABLE `encargos`
  MODIFY `Id_encargo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `Id_rol` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id_usuario` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `encargos`
--
ALTER TABLE `encargos`
  ADD CONSTRAINT `encargos_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`),
  ADD CONSTRAINT `encargos_ibfk_2` FOREIGN KEY (`Id_mensajero`) REFERENCES `usuarios` (`Id_usuario`);

--
-- Filtros para la tabla `msg_enc`
--
ALTER TABLE `msg_enc`
  ADD CONSTRAINT `msg_enc_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`Id_rol`) REFERENCES `roles` (`Id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
