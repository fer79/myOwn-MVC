-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2022 a las 09:15:40
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_sitemacreditos`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `cancel_movement` (`id_movement` BIGINT)   BEGIN
        DECLARE sp_idAccount Bigint;
        DECLARE sp_balanceActual DECIMAL(10,2);
        DECLARE sp_movement int;
        DECLARE sp_amountMovement DECIMAL(10,2);
        DECLARE sp_nuevoBalance DECIMAL(10,2);

        SELECT accountid,movement,amount INTO sp_idAccount,sp_movement,sp_amountMovement FROM movement WHERE idmovement = id_movement;
        SELECT balance INTO sp_balanceActual FROM account WHERE idaccount = sp_idAccount;

        IF sp_movement = 1 OR sp_movement = 2 THEN
            IF sp_movement = 1 THEN
                SET sp_nuevoBalance = sp_balanceActual + sp_amountMovement;
            ELSE
                SET sp_nuevoBalance = sp_balanceActual - sp_amountMovement;
            END IF;
            UPDATE movement SET status = 0 WHERE idmovement = id_movement;
            UPDATE account SET balance = sp_nuevoBalance WHERE idaccount = sp_idAccount;
            SELECT idaccount,balance FROM account WHERE idaccount = sp_idAccount;
        END IF;
        
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client`
--

CREATE TABLE `client` (
  `idclient` bigint(20) NOT NULL,
  `identification` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `names` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `lastnames` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `email` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `address` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `tin` varchar(20) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `taxname` varchar(200) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `taxadress` varchar(200) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `client`
--

INSERT INTO `client` (`idclient`, `identification`, `names`, `lastnames`, `phone`, `email`, `address`, `tin`, `taxname`, `taxadress`, `datecreated`, `status`) VALUES
(1, '67868797', 'Carlos', 'Mora', 4687879, 'ejemplo@mora.com', 'Av. Italia', '2409111', 'Mora inc.', 'Montevideo Uruguay', '2022-09-21 00:06:39', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account`
--

CREATE TABLE `account` (
  `idaccount` bigint(20) NOT NULL,
  `clientid` bigint(20) NOT NULL,
  `productid` bigint(20) NOT NULL,
  `frequencyid` bigint(20) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `dues` int(11) NOT NULL,
  `amount_dues` decimal(10,0) NOT NULL,
  `charge` decimal(10,0) NOT NULL,
  `balance` decimal(10,0) NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `account`
--

INSERT INTO `account` (`idaccount`, `clientid`, `productid`, `frequencyid`, `amount`, `dues`, `amount_dues`, `charge`, `balance`, `datecreated`, `status`) VALUES
(1, 1, 2, 2, '1000', 10, '100', '1000', '1000', '2022-10-03 01:27:10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frequency`
--

CREATE TABLE `frequency` (
  `idfrequency` bigint(20) NOT NULL,
  `frequency` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `frequency`
--

INSERT INTO `frequency` (`idfrequency`, `frequency`, `datecreated`, `status`) VALUES
(1, 'Semanal', '2022-09-29 01:38:43', 1),
(2, 'Quincenal', '2022-09-29 01:51:19', 1),
(3, 'Mensual', '2022-09-29 01:52:04', 1),
(4, 'Semestral', '2022-09-29 01:56:34', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movement`
--

CREATE TABLE `movement` (
  `idmovement` bigint(20) NOT NULL,
  `accountid` bigint(20) NOT NULL,
  `movementtypeid` bigint(20) NOT NULL,
  `movement` int(11) DEFAULT NULL,
  `amount` decimal(10,0) NOT NULL,
  `description` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `movement`
--

INSERT INTO `movement` (`idmovement`, `accountid`, `movementtypeid`, `movement`, `amount`, `description`, `datecreated`, `status`) VALUES
(1, 1, 1, 1, '100', 'Abono mensual', '2022-10-11 00:47:07', 1),
(2, 1, 1, 1, '100', 'Abono mensual', '2022-10-11 00:48:35', 1),
(3, 1, 3, 2, '50', 'Charge por mora', '2022-10-11 00:49:03', 1);

--
-- Disparadores `movement`
--
DELIMITER $$
CREATE TRIGGER `movement_A_I` AFTER INSERT ON `movement` FOR EACH ROW BEGIN
        DECLARE balanceActual DECIMAL(10,2);
        SELECT balance into balanceActual FROM account WHERE idaccount = new.accountid;
        if new.movement = 1 then
            UPDATE account SET balance = balanceActual - new.amount WHERE idaccount = new.accountid;
        else
            UPDATE account SET balance = balanceActual + new.amount WHERE idaccount = new.accountid;
        end if;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `idproduct` bigint(20) NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `description` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`idproduct`, `code`, `name`, `description`, `price`, `datecreated`, `status`) VALUES
(1, '242526', 'Teclado USB', 'Teclado USB', '200', '2022-09-24 03:05:28', 1),
(2, '123456', 'Televisor LED 48 Pulgadas', 'Televisor LED 48 pulgadas', '8000', '2022-09-24 03:23:45', 1),
(3, '478547', 'Mouse USB', 'Descripción product', '150', '2022-09-25 03:28:17', 1),
(4, '987878', 'Motinor LED 24 Pulgadas', 'Descripción monitor', '2500', '2022-09-25 03:29:28', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movement_type`
--

CREATE TABLE `movement_type` (
  `idmovementtype` bigint(20) NOT NULL,
  `movement` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `movement_type` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `movement_type`
--

INSERT INTO `movement_type` (`idmovementtype`, `movement`, `movement_type`, `description`, `datecreated`, `status`) VALUES
(1, 'Abono', 1, 'Abono recurrente', '2022-10-01 01:10:14', 1),
(2, 'Charge', 2, 'Charge a la account', '2022-10-01 01:21:08', 1),
(3, 'Charge Por Mora', 2, 'Charge por mora a la account', '2022-10-01 01:21:52', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id_user` bigint(20) NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `lastname` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id_user`, `name`, `lastname`, `email`, `password`, `datecreated`, `status`) VALUES
(1, 'Carlos', 'Mora', 'info@mora.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2022-11-12 01:23:00', 1),
(2, 'María', 'Pérez', 'mary@contacto.com', 'ba7816bf8f01cfea414140de5dae2223b00361a396177a9cb410ff61f20015ad', '2022-11-12 01:29:44', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`idclient`);

--
-- Indices de la tabla `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`idaccount`),
  ADD KEY `clientid` (`clientid`),
  ADD KEY `productid` (`productid`),
  ADD KEY `frequencyid` (`frequencyid`);

--
-- Indices de la tabla `frequency`
--
ALTER TABLE `frequency`
  ADD PRIMARY KEY (`idfrequency`);

--
-- Indices de la tabla `movement`
--
ALTER TABLE `movement`
  ADD PRIMARY KEY (`idmovement`),
  ADD KEY `accountid` (`accountid`),
  ADD KEY `movementtypeid` (`movementtypeid`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`idproduct`);

--
-- Indices de la tabla `movement_type`
--
ALTER TABLE `movement_type`
  ADD PRIMARY KEY (`idmovementtype`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `client`
--
ALTER TABLE `client`
  MODIFY `idclient` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `account`
--
ALTER TABLE `account`
  MODIFY `idaccount` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `frequency`
--
ALTER TABLE `frequency`
  MODIFY `idfrequency` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `movement`
--
ALTER TABLE `movement`
  MODIFY `idmovement` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `idproduct` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `movement_type`
--
ALTER TABLE `movement_type`
  MODIFY `idmovementtype` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id_user` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `account_ibfk_3` FOREIGN KEY (`frequencyid`) REFERENCES `frequency` (`idfrequency`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `movement`
--
ALTER TABLE `movement`
  ADD CONSTRAINT `movement_ibfk_1` FOREIGN KEY (`movementtypeid`) REFERENCES `movement_type` (`idmovementtype`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movement_ibfk_2` FOREIGN KEY (`accountid`) REFERENCES `account` (`idaccount`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
