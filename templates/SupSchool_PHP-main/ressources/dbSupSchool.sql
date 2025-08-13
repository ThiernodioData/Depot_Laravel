-- SQL Dump pour la gestion des étudiants
-- Version : 1.1.0
-- Généré le : 28 février 2025
-- Auteur : Thierno Idrissa Diallo

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- CREATE DATABASE IF NOT EXISTS `SupSchool`;
-- USE `SupSchool`;

-- --------------------------------------------------------
-- Table `utilisateurs`
CREATE TABLE `utilisateurs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('Admin', 'Utilisateur') NOT NULL DEFAULT 'Utilisateur',
  `etat` INT(11) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  `created_by` VARCHAR(255) NOT NULL,
  `updated_by` VARCHAR(255) DEFAULT NULL,
  `deleted_by` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table `etudiants`
CREATE TABLE `etudiants` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `matricule` VARCHAR(50) NOT NULL UNIQUE,
  `adresse` TEXT DEFAULT NULL,
  `etat` INT(11) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  `created_by` VARCHAR(255) NOT NULL,
  `updated_by` VARCHAR(255) DEFAULT NULL,
  `deleted_by` VARCHAR(255) DEFAULT NULL,
  `id_utilisateur` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table `evaluations`
CREATE TABLE `evaluations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `type` ENUM('Examen', 'Test', 'Projet', 'Autre') NOT NULL,
  `semestre` ENUM('Semestre 1', 'Semestre 2', 'Semestre 3', 'Semestre 4') NOT NULL,
  `description` TEXT DEFAULT NULL,
  `date` DATETIME DEFAULT NULL,
  `etat` INT(11) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  `created_by` VARCHAR(255) NOT NULL,
  `updated_by` VARCHAR(255) DEFAULT NULL,
  `deleted_by` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table `notes`
CREATE TABLE `notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `note` DECIMAL(5,2) NOT NULL,
  `id_etudiant` INT(11) NOT NULL,
  `id_evaluation` INT(11) NOT NULL,
  `etat` INT(11) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  `created_by` VARCHAR(255) NOT NULL,
  `updated_by` VARCHAR(255) DEFAULT NULL,
  `deleted_by` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`id_evaluation`) REFERENCES `evaluations`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Remplissage des tables

-- Insertion dans la table `utilisateurs`
INSERT INTO `utilisateurs` (`id`, `nom`, `photo`, `email`, `password`, `role`, `etat`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 'Jean Dupont', 'jean_photo.png', 'admin@gmail.com', 'admin1234', 'Admin', 1, '2025-01-26 13:53:48', '2025-01-26 13:54:03', NULL, '1', NULL, NULL),
(2, 'Sophie Ndiaye', 'sophie_photo.png', 'sophie.ndiaye@universite.edu', 'hashedpassword456', 'etudiant', 1, '2025-01-26 13:53:48', '2025-01-26 13:54:03', NULL, '1', NULL, NULL);

-- Insertion dans la table `etudiants`
INSERT INTO `etudiants` (`id`, `nom`, `photo`, `email`, `password`, `matricule`, `adresse`, `etat`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `id_utilisateur`) VALUES
(1, 'Ibrahima Sow', 'ibrahima_photo.png', 'ibrahima.sow@universite.edu', 'hashedpassword111', 'MAT11111', 'Thiès, Sénégal', 1, '2025-01-26 13:53:48', '2025-01-26 13:54:03', NULL, '1', NULL, NULL, 1),
(2, 'Awa Ba', 'awa_photo.png', 'awa.ba@universite.edu', 'hashedpassword222', 'MAT22222', 'Kaolack, Sénégal', 1, '2025-01-26 13:53:48', '2025-01-26 13:54:03', NULL, '1', NULL, NULL, 2);

-- Insertion dans la table `evaluations`
INSERT INTO `evaluations` (`nom`, `type`, `semestre`, `description`, `date`, `etat`, `created_by`)
VALUES
('Examen Informatique', 'Examen', 'Semestre 1', 'Évaluation finale en programmation', '2025-01-26 09:00:00', 1, 'admin'),
('Projet Réseau', 'Projet', 'Semestre 2', 'Mise en place d’un réseau local', '2025-02-10 14:00:00', 1, 'admin');

-- Insertion dans la table `notes`
INSERT INTO `notes` (`id`, `note`, `id_etudiant`, `id_evaluation`, `etat`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 15.00, 1, 1, 1, '2025-01-26 13:53:48', '2025-01-26 13:54:03', NULL, '1', NULL, NULL),
(2, 17.50, 2, 2, 1, '2025-01-26 13:53:48', '2025-01-26 13:54:03', NULL, '1', NULL, NULL);

COMMIT;
