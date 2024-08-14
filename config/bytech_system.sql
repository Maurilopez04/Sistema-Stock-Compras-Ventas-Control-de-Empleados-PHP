-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-08-2024 a las 15:18:49
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bytech_system`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(4, 'Sin Categoría', 'Categoría por defecto (No eliminar)'),
(5, 'Servicios', 'Servicios que realizamos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ci_ruc` varchar(20) NOT NULL,
  `fecha_cumple` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `email`, `telefono`, `direccion`, `ci_ruc`, `fecha_cumple`) VALUES
(2, 'Mauri Lopez', 'maurilopez04@gmail.com', '0983882017', 'Dr. Valentín Rebull 165, Barrio San Felipe.', '5246218', '2004-03-30'),
(3, 'Sin nombre', 'cliente@empresa.com', '0', 'Sin nombre', '0', '2004-03-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `proveedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `producto_id`, `cantidad`, `precio`, `fecha`, `proveedor_id`) VALUES
(4, 7, 1, '120000.00', '2024-07-31 20:04:14', 1),
(5, 8, 1, '100000.00', '2024-07-31 20:04:14', 1),
(6, 3, 1, '900000.00', '2024-07-31 20:04:14', 1),
(7, 3, 1, '900000.00', '2024-07-31 20:04:27', 1),
(8, 6, 100, '45000.00', '2024-08-12 00:14:15', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `sueldo` decimal(10,2) NOT NULL,
  `puesto` varchar(100) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `casado` tinyint(1) NOT NULL,
  `hijos` int(11) NOT NULL,
  `ubicacion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `cedula`, `nombre`, `sueldo`, `puesto`, `numero`, `correo`, `fecha_contratacion`, `casado`, `hijos`, `ubicacion`) VALUES
(1, '2000100', 'Empleado de Prueba', '2800000.00', 'Auxiliar de programación', '0987654321', 'empleado@empresa.com', '2024-08-01', 0, 0, 'Calle 321. Asunción, Paraguay'),
(3, '5679201', 'Ayudante Bytech', '2800000.00', 'Auxiliar de programación', '0987654321', 'prueba@empresa.com', '2024-08-13', 1, 2, 'Calle 321. Asunción, Paraguay');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_stock`
--

CREATE TABLE `entradas_stock` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradas_stock`
--

INSERT INTO `entradas_stock` (`id`, `producto_id`, `cantidad`, `fecha`) VALUES
(1, 3, 2, '2024-07-26 13:08:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Otra Marca', 'Marca por defecto (No eliminar)'),
(2, 'Sin marca', 'No existe'),
(3, 'Keko', 'Marca Brasilera de equipamiento de autos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_stock`
--

CREATE TABLE `movimientos_stock` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos_stock`
--

INSERT INTO `movimientos_stock` (`id`, `producto_id`, `cantidad`, `tipo`, `fecha`) VALUES
(1, 3, 2, 'salida', '2024-07-26 13:15:08'),
(2, 3, 2, 'salida', '2024-07-26 13:16:17'),
(3, 3, 2, 'entrada', '2024-07-26 13:16:22'),
(4, 3, 2, 'salida', '2024-07-26 13:21:24'),
(5, 3, 4, 'entrada', '2024-07-26 14:20:42'),
(6, 3, 4, 'entrada', '2024-07-26 14:20:56'),
(7, 3, 1, 'entrada', '2024-07-27 00:38:51'),
(8, 7, 1, 'entrada', '2024-07-27 00:39:24'),
(9, 3, 1, 'salida', '2024-07-27 00:40:10'),
(10, 3, 1, 'salida', '2024-07-27 07:08:16'),
(11, 6, 1, 'salida', '2024-07-27 07:08:16'),
(12, 3, 1, 'salida', '2024-07-27 07:21:23'),
(13, 6, 1, 'salida', '2024-07-27 07:21:23'),
(14, 6, 1, 'salida', '2024-07-27 07:21:23'),
(15, 7, 1, 'salida', '2024-07-27 07:21:23'),
(16, 7, 1, 'salida', '2024-07-28 01:11:32'),
(17, 6, 1, 'salida', '2024-07-28 01:11:32'),
(18, 8, 1, 'entrada', '2024-07-28 01:15:08'),
(19, 8, 1, 'salida', '2024-07-31 19:04:40'),
(20, 7, 1, 'salida', '2024-07-31 19:05:02'),
(21, 6, 1, 'salida', '2024-07-31 19:05:02'),
(22, 3, 1, 'salida', '2024-07-31 19:07:54'),
(23, 7, 1, 'entrada', '2024-07-31 20:04:14'),
(24, 8, 1, 'entrada', '2024-07-31 20:04:14'),
(25, 3, 1, 'entrada', '2024-07-31 20:04:14'),
(26, 3, 1, 'entrada', '2024-07-31 20:04:27'),
(27, 3, 1, 'salida', '2024-08-04 20:50:57'),
(28, 3, 1, 'salida', '2024-08-04 21:08:47'),
(29, 7, 1, 'salida', '2024-08-04 21:08:47'),
(30, 3, 1, 'salida', '2024-08-11 19:29:57'),
(31, 6, 1, 'salida', '2024-08-11 19:29:57'),
(32, 3, 2, 'salida', '2024-08-11 23:39:51'),
(33, 6, 1, 'salida', '2024-08-11 23:46:15'),
(34, 3, 1, 'salida', '2024-08-11 23:46:15'),
(35, 7, 1, 'salida', '2024-08-11 23:46:15'),
(36, 6, 100, 'entrada', '2024-08-12 00:14:15'),
(37, 3, 1, 'salida', '2024-08-12 00:34:22'),
(38, 6, 3, 'salida', '2024-08-12 00:34:22'),
(39, 6, 2, 'salida', '2024-08-12 00:36:48'),
(40, 3, 1, 'salida', '2024-08-12 01:03:02'),
(41, 6, 3, 'salida', '2024-08-13 16:50:27'),
(42, 3, 1, 'salida', '2024-08-13 18:12:23'),
(43, 6, 2, 'salida', '2024-08-13 18:12:23'),
(44, 3, 5, 'entrada', '2024-08-13 19:11:38'),
(45, 8, 1, 'salida', '2024-08-13 19:23:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `costo` decimal(10,2) NOT NULL,
  `precioMayorista` decimal(10,2) NOT NULL,
  `precioMinorista` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `marca_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `imagen`, `costo`, `precioMayorista`, `precioMinorista`, `cantidad`, `categoria_id`, `marca_id`) VALUES
(3, 'Carpa Toyota Hilux Vigo D/C 2005/2015', 'dadsa', 'KC086 (1).jpeg', '12.00', '1150000.00', '1400000.00', 3, 5, 3),
(6, 'Lavado a espuma', 'dasa', 'WhatsApp Image 2024-07-15 at 02.44.57.jpeg', '2.00', '75000.00', '90000.00', 87, 4, NULL),
(7, 'Valija MOTOBUL 510Lts', 'qeqeqeq1313 asfsfsfsdfsdfsfsfsfsfsfsfs ewwrwrwrw wr wrwrwerwrwrwwwwr', 'WhatsApp Image 2024-07-06 at 23.25.48.jpeg', '12000.00', '20000.00', '2000000.00', -1, 4, 2),
(8, 'Detailing', '', 'WhatsApp Image 2024-07-15 at 02.44.42.jpeg', '100000.00', '150000.00', '200000.00', 0, 5, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `contacto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `contacto`) VALUES
(1, 'Herimarc', '0983882017'),
(4, 'Chacomer', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas_stock`
--

CREATE TABLE `salidas_stock` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones_empleados`
--

CREATE TABLE `transacciones_empleados` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `tipo_transaccion` enum('adelanto','bono','descuento','pago_final') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transacciones_empleados`
--

INSERT INTO `transacciones_empleados` (`id`, `empleado_id`, `tipo_transaccion`, `monto`, `fecha`, `descripcion`) VALUES
(1, 1, 'adelanto', '200000.00', '2024-08-14', ''),
(2, 1, 'adelanto', '200000.00', '2024-08-14', 'Hola'),
(3, 1, 'bono', '200000.00', '2024-08-13', '1'),
(5, 3, 'adelanto', '50000.00', '2024-08-12', 'Adelanto 07:48 martes'),
(6, 1, 'descuento', '200000.00', '2024-08-13', 'IPS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `creado_en`) VALUES
(2, 'Mauri Lopez', 'lolmauri04@gmail.com', '$2y$10$YM7GvPhffnB53QRN7.8cIuygMbF5yiLq5dvB59Npeo3o1MnmuQNaS', '2024-07-26 14:01:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `total`, `producto_id`, `cantidad`, `precio`, `fecha`) VALUES
(1, 2, '0.00', 3, 1, '1700000.00', '2024-07-27 00:40:10'),
(2, 2, '0.00', 3, 1, '140.00', '2024-07-27 07:08:16'),
(3, 2, '0.00', 6, 1, '133.00', '2024-07-27 07:08:16'),
(4, 2, '0.00', 3, 1, '140.00', '2024-07-27 07:21:23'),
(5, 2, '0.00', 6, 1, '133.00', '2024-07-27 07:21:23'),
(6, 2, '0.00', 6, 1, '133.00', '2024-07-27 07:21:23'),
(7, 2, '0.00', 7, 1, '30000.00', '2024-07-27 07:21:23'),
(8, 2, '0.00', 7, 1, '30000.00', '2024-07-28 01:11:32'),
(9, 2, '0.00', 6, 1, '133.00', '2024-07-28 01:11:32'),
(10, 2, '0.00', 8, 1, '200000.00', '2024-07-31 19:04:40'),
(11, 2, '0.00', 7, 1, '30000.00', '2024-07-31 19:05:02'),
(12, 2, '0.00', 6, 1, '133.00', '2024-07-31 19:05:02'),
(13, 2, '0.00', 3, 1, '1400000.00', '2024-07-31 19:07:54'),
(14, 2, '0.00', 3, 1, '1400000.00', '2024-08-04 20:50:57'),
(15, 2, '0.00', 3, 1, '1500000.00', '2024-08-04 21:08:47'),
(16, 2, '0.00', 7, 1, '1300000.00', '2024-08-04 21:08:47'),
(19, 2, '0.00', 3, 1, '14000000.00', '2024-08-11 19:29:57'),
(20, 2, '0.00', 6, 1, '130000.00', '2024-08-11 19:29:57'),
(21, 2, '0.00', 3, 2, '1400000.00', '2024-08-11 23:39:51'),
(22, 2, '0.00', 6, 1, '90000.00', '2024-08-11 23:46:15'),
(23, 2, '0.00', 3, 1, '1400000.00', '2024-08-11 23:46:15'),
(24, 2, '0.00', 7, 1, '3000000.00', '2024-08-11 23:46:15'),
(26, 3, '0.00', 3, 1, '1400000.00', '2024-08-12 00:34:22'),
(27, 3, '0.00', 6, 3, '90000.00', '2024-08-12 00:34:22'),
(28, 3, '0.00', 6, 2, '90000.00', '2024-08-12 00:36:48'),
(29, 3, '1400000.00', NULL, 0, '0.00', '2024-08-12 01:03:02'),
(30, 3, '270000.00', NULL, 0, '0.00', '2024-08-13 16:50:27'),
(31, 2, '1580000.00', NULL, 0, '0.00', '2024-08-13 18:12:23'),
(32, 2, '200000.00', NULL, 0, '0.00', '2024-08-13 19:23:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_detalle`
--

CREATE TABLE `ventas_detalle` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas_detalle`
--

INSERT INTO `ventas_detalle` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio`) VALUES
(1, 29, 3, 1, '1400000.00'),
(2, 30, 6, 3, '90000.00'),
(3, 31, 3, 1, '1400000.00'),
(4, 31, 6, 2, '90000.00'),
(5, 32, 8, 1, '200000.00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `fk_proveedor` (`proveedor_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entradas_stock`
--
ALTER TABLE `entradas_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `salidas_stock`
--
ALTER TABLE `salidas_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `transacciones_empleados`
--
ALTER TABLE `transacciones_empleados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empleado_id` (`empleado_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `entradas_stock`
--
ALTER TABLE `entradas_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `salidas_stock`
--
ALTER TABLE `salidas_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones_empleados`
--
ALTER TABLE `transacciones_empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `fk_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `entradas_stock`
--
ALTER TABLE `entradas_stock`
  ADD CONSTRAINT `entradas_stock_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD CONSTRAINT `movimientos_stock_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `salidas_stock`
--
ALTER TABLE `salidas_stock`
  ADD CONSTRAINT `salidas_stock_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `transacciones_empleados`
--
ALTER TABLE `transacciones_empleados`
  ADD CONSTRAINT `transacciones_empleados_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD CONSTRAINT `ventas_detalle_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `ventas_detalle_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
