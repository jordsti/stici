-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 15 Octobre 2014 à 22:46
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `stici`
--

-- --------------------------------------------------------

--
-- Structure de la table `builds`
--

CREATE TABLE IF NOT EXISTS `builds` (
  `build_id` int(25) NOT NULL AUTO_INCREMENT,
  `current_id` int(25) NOT NULL,
  `job_id` int(25) NOT NULL,
  `status` int(2) NOT NULL,
  `stamp` int(25) NOT NULL,
  `build_number` int(2) NOT NULL,
  `worker_hash` varchar(64) NOT NULL,
  PRIMARY KEY (`build_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `buildsteps`
--

CREATE TABLE IF NOT EXISTS `buildsteps` (
  `buildstep_id` int(25) NOT NULL AUTO_INCREMENT,
  `job_id` int(25) NOT NULL,
  `step_order` int(2) NOT NULL,
  `executable` varchar(255) NOT NULL,
  `args` text NOT NULL,
  `flags` int(2) NOT NULL,
  PRIMARY KEY (`buildstep_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `current_jobs`
--

CREATE TABLE IF NOT EXISTS `current_jobs` (
  `current_id` int(2) NOT NULL AUTO_INCREMENT,
  `job_id` int(2) NOT NULL,
  `worker_id` int(2) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`current_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `envs`
--

CREATE TABLE IF NOT EXISTS `envs` (
  `env_id` int(25) NOT NULL AUTO_INCREMENT,
  `job_id` int(25) NOT NULL,
  `env_name` varchar(255) NOT NULL,
  `env_value` text NOT NULL,
  PRIMARY KEY (`env_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `job_id` int(25) NOT NULL AUTO_INCREMENT,
  `job_name` varchar(255) NOT NULL,
  `job_status` int(2) NOT NULL,
  `build_number` int(3) NOT NULL,
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `workers`
--

CREATE TABLE IF NOT EXISTS `workers` (
  `worker_id` int(25) NOT NULL AUTO_INCREMENT,
  `worker_hash` varchar(64) NOT NULL,
  `worker_status` int(2) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `remote_addr` varchar(255) NOT NULL,
  `last_tick` int(25) NOT NULL,
  PRIMARY KEY (`worker_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
