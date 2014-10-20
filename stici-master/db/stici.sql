-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 20 Octobre 2014 à 21:29
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
  `stamp_end` int(25) NOT NULL,
  `build_number` int(2) NOT NULL,
  `worker_hash` varchar(64) NOT NULL,
  PRIMARY KEY (`build_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `buildsteps_logs`
--

CREATE TABLE IF NOT EXISTS `buildsteps_logs` (
  `log_id` int(25) NOT NULL AUTO_INCREMENT,
  `step_id` int(25) NOT NULL,
  `build_id` int(25) NOT NULL,
  `duration` int(25) NOT NULL,
  `stdout` text NOT NULL,
  `stderr` text NOT NULL,
  `return_code` int(11) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `build_files`
--

CREATE TABLE IF NOT EXISTS `build_files` (
  `file_id` int(25) NOT NULL AUTO_INCREMENT,
  `build_id` int(25) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filesize` int(25) NOT NULL,
  `stamp` int(25) NOT NULL,
  `file_hash` varchar(32) NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

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
-- Structure de la table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(25) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `flags` int(2) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `job_id` int(25) NOT NULL AUTO_INCREMENT,
  `remote_git` varchar(255) NOT NULL,
  `job_name` varchar(255) NOT NULL,
  `job_status` int(2) NOT NULL,
  `build_number` int(3) NOT NULL,
  `target` int(2) NOT NULL,
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(25) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hash_type` varchar(8) NOT NULL,
  `email` varchar(255) NOT NULL,
  `stamp` int(25) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(25) NOT NULL,
  `group_id` int(25) NOT NULL,
  PRIMARY KEY (`link_id`)
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
  `worker_os` int(2) NOT NULL,
  PRIMARY KEY (`worker_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


INSERT INTO `users` (`username`, `password`, `hash_type`, `email`, `stamp`) VALUES
('admin', 'admin', 'clear', 'admin@localhost.com', 0);

INSERT INTO `groups` (`group_name`, `flags`) VALUES
('Guest', 1),
('Admin', 4095),
('User', 157);

INSERT INTO `users_groups` (`group_id`, `user_id`) VALUES
(2, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
