-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Giu 20, 2019 alle 16:49
-- Versione del server: 5.7.26-0ubuntu0.16.04.1
-- Versione PHP: 7.0.33-0ubuntu0.16.04.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s259444`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `dimension`
--

DROP TABLE IF EXISTS `dimension`;
CREATE TABLE `dimension` (
  `dimension` varchar(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `dimension`
--

INSERT INTO `dimension` (`dimension`, `value`) VALUES
('width', 6),
('length', 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `seatmap`
--

DROP TABLE IF EXISTS `seatmap`;
CREATE TABLE `seatmap` (
  `line` int(2) NOT NULL,
  `seat` char(1) NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  `mail` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `mail` varchar(40) NOT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`mail`, `password`) VALUES
('u1@p.it', 'ec6ef230f1828039ee794566b9c58adc'),
('u2@p.it', '270c1b084f3f146eb5787075158d9c53');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `seatmap`
--
ALTER TABLE `seatmap`
  ADD PRIMARY KEY (`line`,`seat`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`mail`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
