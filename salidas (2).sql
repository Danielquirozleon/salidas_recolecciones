-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-04-2025 a las 08:50:41
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
-- Base de datos: `salidas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `numero_cliente` varchar(50) NOT NULL,
  `nombre_cliente` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `numero_cliente`, `nombre_cliente`) VALUES
(1, '001', 'Prueba de cliete'),
(2, '002', 'Prueba de cliente2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_salida`
--

CREATE TABLE `detalles_salida` (
  `id` int(11) NOT NULL,
  `id_salida` int(11) NOT NULL,
  `codigo_producto` varchar(100) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_salida`
--

INSERT INTO `detalles_salida` (`id`, `id_salida`, `codigo_producto`, `nombre_producto`, `cantidad`) VALUES
(1, 1, '001', 'bufanda', 1),
(2, 2, '002', 'gorra', 23),
(3, 2, '001', 'bufanda', 2),
(4, 12, '001', '', 1),
(5, 12, '002', '', 2),
(6, 13, '002', '', 234),
(7, 13, '001', '', 23243),
(8, 22, '001', '', 2),
(9, 25, '001', 'Array', 1),
(10, 26, '002', 'Array', 1),
(11, 27, '001', 'bufanda', 1),
(12, 28, '001', 'bufanda', 223),
(13, 29, '001', 'bufanda', 1),
(14, 30, '001', 'bufanda', 12),
(15, 30, '002', 'gorra', 223),
(16, 31, '001', 'bufanda', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_salidas_almacen`
--

CREATE TABLE `detalle_salidas_almacen` (
  `id` int(11) NOT NULL,
  `id_salida` int(11) NOT NULL,
  `codigo_producto` varchar(50) NOT NULL,
  `descripcion_producto` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones_clientes`
--

CREATE TABLE `direcciones_clientes` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `direccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones_clientes`
--

INSERT INTO `direcciones_clientes` (`id`, `id_cliente`, `direccion`) VALUES
(1, 1, 'camino alas minas 84 int #33 Int. LT22, TLÁHUAC, CDMX, CP: 13410, Tel: 5531548296. Referencias: 001'),
(2, 1, 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd'),
(3, 1, 'Prueba 003 #33 Int. 12, 111, CDMX, CP: 13410, Tel: 5531548296. Referencias: pruab ultima'),
(4, 1, 'prueba 4 #33 Int. 12, TLÁHUAC, CDMX, CP: 13410, Tel: 5531548296. Referencias: aaa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo_producto` varchar(100) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo_producto`, `nombre_producto`) VALUES
(1, '001', 'bufanda'),
(2, '002', 'gorra');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas`
--

CREATE TABLE `salidas` (
  `id` int(11) NOT NULL,
  `folio` varchar(50) NOT NULL,
  `tipo_salida` enum('afecta','no_afecta') NOT NULL,
  `solicitado_por` varchar(100) NOT NULL,
  `dirigido_a` varchar(100) NOT NULL,
  `numero_cliente` varchar(50) DEFAULT NULL,
  `nombre_cliente` varchar(300) NOT NULL,
  `direccion_cliente` text DEFAULT NULL,
  `folio_afectacion` varchar(100) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `usuario_creacion` varchar(100) NOT NULL,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salidas`
--

INSERT INTO `salidas` (`id`, `folio`, `tipo_salida`, `solicitado_por`, `dirigido_a`, `numero_cliente`, `nombre_cliente`, `direccion_cliente`, `folio_afectacion`, `comentario`, `usuario_creacion`, `fecha_creacion`) VALUES
(1, 'SAL-20250427-8208', 'no_afecta', 'dddd', 'aaa', '123', '', NULL, NULL, 'prueba2.1', 'oscom', '2025-04-27 07:34:17'),
(2, 'SAL-20250427-4671', 'no_afecta', 'dddd', 'aaa', '002', '', NULL, NULL, 'prueba2.2', 'oscom', '2025-04-27 07:35:38'),
(3, 'SAL-20250427-6798', 'afecta', 'dddd', 'aaa', '001', '', NULL, NULL, 'prueba 2.3', 'oscom', '2025-04-27 07:43:43'),
(4, 'SAL-20250428-2608', 'no_afecta', 'dddd', 'aaa', '001', '', NULL, NULL, 'prubea 3.3', 'Daniel', '2025-04-27 17:41:57'),
(5, 'SAL-20250428-2333', 'no_afecta', 'dddd', 'aaa', '001', '', NULL, NULL, 'qqq', 'Daniel', '2025-04-27 18:19:25'),
(6, 'SAL-20250428-2157', 'afecta', 'dddd', 'aaa', '001', '', NULL, '', '0001', 'Daniel', '2025-04-27 18:34:38'),
(7, 'SAL-20250428-5544', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'camino alas minas 84 int #33 Int. LT22, TLÁHUAC, CDMX, CP: 13410, Tel: 5531548296. Referencias: 001', '', 'prueba4.1', 'Daniel', '2025-04-28 02:52:53'),
(9, 'SAL-20250428-4533', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'camino alas minas 84 int #33 Int. LT22, TLÁHUAC, CDMX, CP: 13410, Tel: 5531548296. Referencias: 001', '', '4.2', 'Daniel', '2025-04-28 02:57:07'),
(10, 'SAL-20250428-9274', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'camino alas minas 84 int #33 Int. LT22, TLÁHUAC, CDMX, CP: 13410, Tel: 5531548296. Referencias: 001', '', '4.3', 'Daniel', '2025-04-28 03:02:37'),
(12, 'SAL-20250428-8441', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'camino alas minas 84 int #33 Int. LT22, TLÁHUAC, CDMX, CP: 13410, Tel: 5531548296. Referencias: 001', '', '222', 'Daniel', '2025-04-28 03:09:25'),
(13, 'SAL-20250428-6453', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', 'prueba 4.6', 'Daniel', '2025-04-28 03:20:28'),
(14, 'SAL-20250428-9166', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', '22222', 'Daniel', '2025-04-28 03:30:23'),
(17, 'SAL-20250428-2469', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', '1111', 'Daniel', '2025-04-28 03:46:57'),
(18, 'SAL-20250428-1596', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', '\'\'1', 'Daniel', '2025-04-28 04:09:53'),
(20, 'SAL-20250428-7733', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', 'qqq', 'Daniel', '2025-04-28 04:11:34'),
(22, 'SAL-20250428-2304', 'no_afecta', 'dddd', 'pancho', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', '11', 'Daniel', '2025-04-28 04:13:06'),
(23, 'SAL-20250428-2567', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', '222', 'Daniel', '2025-04-28 04:49:16'),
(25, 'SAL-20250428-8196', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'camino alas minas 84 int #33 Int. LT22, TLÁHUAC, CDMX, CP: 13410, Tel: 5531548296. Referencias: 001', '', 'qqqq', 'Daniel', '2025-04-28 04:54:23'),
(26, 'SAL-20250428-6441', 'no_afecta', 'Dante', 'pancho', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', '111', 'Daniel', '2025-04-28 04:57:04'),
(27, 'SAL-20250428-1958', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', 'sss', 'Daniel', '2025-04-28 06:05:43'),
(28, 'SAL-20250428-2894', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'Prueba 003 #33 Int. 12, 111, CDMX, CP: 13410, Tel: 5531548296. Referencias: pruab ultima', '', 'qqqq', 'Daniel', '2025-04-28 07:14:22'),
(29, 'SAL-20250428-3193', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'Prueba 003 #33 Int. 12, 111, CDMX, CP: 13410, Tel: 5531548296. Referencias: pruab ultima', '', '111', 'Daniel', '2025-04-28 07:16:05'),
(30, 'SAL-20250428-8908', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'prueba 4 #33 Int. 12, TLÁHUAC, CDMX, CP: 13410, Tel: 5531548296. Referencias: aaa', '', 'sssss', 'Daniel', '2025-04-28 07:21:46'),
(31, 'SAL-20250428-6848', 'no_afecta', 'dddd', 'aaa', '001', 'Prueba de cliete', 'prueba 2 #12 Int. 12, tlapan, cdmx, CP: 13410, Tel: 484848939. Referencias: dkdkdkd', '', '111', 'Daniel', '2025-04-28 08:24:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas_almacen`
--

CREATE TABLE `salidas_almacen` (
  `id` int(11) NOT NULL,
  `folio` varchar(20) NOT NULL,
  `bodega` varchar(50) NOT NULL,
  `motivo_salida` varchar(100) NOT NULL,
  `solicitado_por` varchar(100) NOT NULL,
  `dirigido_a` varchar(100) NOT NULL,
  `numero_cliente` varchar(20) NOT NULL,
  `nombre_cliente` varchar(255) DEFAULT NULL,
  `direccion_cliente` text DEFAULT NULL,
  `telefono_destino` varchar(20) DEFAULT NULL,
  `destinatario` varchar(100) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `usuario_registro` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salidas_almacen`
--

INSERT INTO `salidas_almacen` (`id`, `folio`, `bodega`, `motivo_salida`, `solicitado_por`, `dirigido_a`, `numero_cliente`, `nombre_cliente`, `direccion_cliente`, `telefono_destino`, `destinatario`, `comentarios`, `usuario_registro`, `fecha_registro`) VALUES
(1, 'ALM00001', 'Brea', 'Complemento de entrega', '1', '1', '001', '', '1111', '111', '111', '1111', 'Daniel', '2025-04-28 08:35:18'),
(2, 'ALM00002', 'Brea', 'Cambio físico', 'dddd', '1', '001', '', 'qqqq', 'qqq', 'qq', '', 'Daniel', '2025-04-28 08:48:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`) VALUES
(1, 'oscom', '$2y$10$7gojJcGW6dmgj7SZKNw4V.h48wtcKh/y02QbMJGBtbRWJ9i0D9y7C'),
(2, 'oscom', '$2y$10$0IdgQeaKy9heoE6nwWmokO92TsaKhhVw0PqNMA7l0L8KSjvI0qy42'),
(3, 'Daniel', '$2y$10$KFr0PEmCjGRk80MyM/45n.x11omhsdUJIkKANUsQs.mazE1kmaOq2');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_cliente` (`numero_cliente`),
  ADD KEY `numero_cliente_2` (`numero_cliente`);

--
-- Indices de la tabla `detalles_salida`
--
ALTER TABLE `detalles_salida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_salida` (`id_salida`),
  ADD KEY `codigo_producto` (`codigo_producto`);

--
-- Indices de la tabla `detalle_salidas_almacen`
--
ALTER TABLE `detalle_salidas_almacen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_salida` (`id_salida`);

--
-- Indices de la tabla `direcciones_clientes`
--
ALTER TABLE `direcciones_clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_producto` (`codigo_producto`),
  ADD KEY `codigo_producto_2` (`codigo_producto`);

--
-- Indices de la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folio` (`folio`),
  ADD KEY `folio_2` (`folio`),
  ADD KEY `numero_cliente` (`numero_cliente`);

--
-- Indices de la tabla `salidas_almacen`
--
ALTER TABLE `salidas_almacen`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalles_salida`
--
ALTER TABLE `detalles_salida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `detalle_salidas_almacen`
--
ALTER TABLE `detalle_salidas_almacen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `direcciones_clientes`
--
ALTER TABLE `direcciones_clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salidas`
--
ALTER TABLE `salidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `salidas_almacen`
--
ALTER TABLE `salidas_almacen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_salida`
--
ALTER TABLE `detalles_salida`
  ADD CONSTRAINT `detalles_salida_ibfk_1` FOREIGN KEY (`id_salida`) REFERENCES `salidas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_salidas_almacen`
--
ALTER TABLE `detalle_salidas_almacen`
  ADD CONSTRAINT `detalle_salidas_almacen_ibfk_1` FOREIGN KEY (`id_salida`) REFERENCES `salidas_almacen` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `direcciones_clientes`
--
ALTER TABLE `direcciones_clientes`
  ADD CONSTRAINT `direcciones_clientes_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
