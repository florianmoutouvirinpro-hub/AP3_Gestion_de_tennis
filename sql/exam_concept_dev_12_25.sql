-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 21 jan. 2026 à 22:01
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `exam_concept_dev_12_25`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fk_id_role` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `login`, `password`, `fk_id_role`) VALUES
(1, 'admin', '$2y$10$Uf3N2..7Z.heXopu5qWroegeAPO/ZCIH9aNnXyl6Sa998MjjIeD2G', 1),
(5, 'lecteur', '$2y$10$bpZaVQqn39dsukFnSFbpbOMVPrg3qHG9KEXc9XEefCOuzqejfcZjG', 2),
(4, 'dpo', '$2y$10$MeBqOmBGk3rS5lrmN5NlNeJB6rSO9tckiAk3nWC9axW8Ty7fFXcbq', 3);

-- --------------------------------------------------------

--
-- Structure de la table `fonction_bureau`
--

DROP TABLE IF EXISTS `fonction_bureau`;
CREATE TABLE IF NOT EXISTS `fonction_bureau` (
  `id_fonction` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_fonction`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fonction_bureau`
--

INSERT INTO `fonction_bureau` (`id_fonction`, `libelle`) VALUES
(1, 'Président'),
(2, 'Vice-président'),
(3, 'Trésorier'),
(4, 'Trésorier adjoint'),
(5, 'Secrétaire'),
(6, 'Secrétaire adjoint'),
(7, 'Président'),
(8, 'Vice-président'),
(9, 'Trésorier'),
(10, 'Trésorier adjoint'),
(11, 'Secrétaire'),
(12, 'Secrétaire adjoint');

-- --------------------------------------------------------

--
-- Structure de la table `formule`
--

DROP TABLE IF EXISTS `formule`;
CREATE TABLE IF NOT EXISTS `formule` (
  `idformule` int(11) NOT NULL AUTO_INCREMENT,
  `libelleformule` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idformule`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formule`
--

INSERT INTO `formule` (`idformule`, `libelleformule`) VALUES
(1, 'Championnat par équipe'),
(2, 'Cours collectif'),
(3, 'Cours individuel');

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

DROP TABLE IF EXISTS `historique`;
CREATE TABLE IF NOT EXISTS `historique` (
  `id_historique` int(11) NOT NULL AUTO_INCREMENT,
  `id_licencie` int(11) NOT NULL,
  `numero_licence` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `date_adhesion` date DEFAULT NULL,
  `date_suppression` datetime DEFAULT CURRENT_TIMESTAMP,
  `motif_suppression` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_historique`),
  KEY `idx_date_suppression` (`date_suppression`),
  KEY `idx_id_licencie` (`id_licencie`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

DROP TABLE IF EXISTS `inscription`;
CREATE TABLE IF NOT EXISTS `inscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_idlicencie` int(11) DEFAULT NULL,
  `fk_idformule` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_idlicencie` (`fk_idlicencie`),
  KEY `fk_idformule` (`fk_idformule`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`id`, `fk_idlicencie`, `fk_idformule`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(5, 1, 3),
(6, 6, 2);

-- --------------------------------------------------------

--
-- Structure de la table `licencie`
--

DROP TABLE IF EXISTS `licencie`;
CREATE TABLE IF NOT EXISTS `licencie` (
  `idlicencie` int(11) NOT NULL AUTO_INCREMENT,
  `numerolicence` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datenaissance` date DEFAULT NULL,
  `dateadhesion` date DEFAULT NULL,
  PRIMARY KEY (`idlicencie`),
  UNIQUE KEY `numerolicence` (`numerolicence`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `licencie`
--

INSERT INTO `licencie` (`idlicencie`, `numerolicence`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `datenaissance`, `dateadhesion`) VALUES
(1, '456789', 'Dupont', 'Michel', 'michel.dupont@mail.com', '0645678912', '15 rue des Tamaris', '1990-03-21', '2022-01-10'),
(2, '56783D', 'Durand', 'Jeanne', 'jeanne.durand@mail.com', '0715678923', '15 avenue du Foch', '1993-07-14', '2024-01-12'),
(6, '4455555', 'noir', 'Carote', 'heha@mail.com', '0779870053', '115 chemin', '2025-12-16', '2025-12-16');

--
-- Déclencheurs `licencie`
--
DROP TRIGGER IF EXISTS `tr_before_delete_licencie`;
DELIMITER $$
CREATE TRIGGER `tr_before_delete_licencie` BEFORE DELETE ON `licencie` FOR EACH ROW BEGIN
  INSERT INTO historique (
    id_licencie, numero_licence, nom, prenom, email, 
    telephone, adresse, date_naissance, date_adhesion, date_suppression
  ) VALUES (
    OLD.idlicencie, OLD.numerolicence, OLD.nom, OLD.prenom, 
    OLD.email, OLD.telephone, OLD.adresse, OLD.datenaissance, 
    OLD.dateadhesion, NOW()
  );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `mandat_bureau`
--

DROP TABLE IF EXISTS `mandat_bureau`;
CREATE TABLE IF NOT EXISTS `mandat_bureau` (
  `id_mandat` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_licencie` int(11) NOT NULL,
  `fk_id_fonction` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  PRIMARY KEY (`id_mandat`),
  KEY `fk_mandat_licencie` (`fk_id_licencie`),
  KEY `fk_mandat_fonction` (`fk_id_fonction`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

DROP TABLE IF EXISTS `personnel`;
CREATE TABLE IF NOT EXISTS `personnel` (
  `id_personnel` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_licencie` int(11) NOT NULL,
  `fk_id_type` int(11) NOT NULL,
  `date_entree` date NOT NULL,
  `date_sortie` date DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `remarques` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_personnel`),
  KEY `fk_personnel_licencie` (`fk_id_licencie`),
  KEY `fk_personnel_type` (`fk_id_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `libelle_role` (`libelle_role`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `libelle_role`) VALUES
(1, 'Administrateur'),
(2, 'Lecteur'),
(3, 'DPO');

-- --------------------------------------------------------

--
-- Structure de la table `type_personnel`
--

DROP TABLE IF EXISTS `type_personnel`;
CREATE TABLE IF NOT EXISTS `type_personnel` (
  `id_type` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_personnel`
--

INSERT INTO `type_personnel` (`id_type`, `libelle`) VALUES
(1, 'Moniteur principal'),
(2, 'Moniteur'),
(3, 'Stagiaire'),
(4, 'Secrétaire'),
(5, 'Moniteur principal'),
(6, 'Moniteur'),
(7, 'Stagiaire'),
(8, 'Secrétaire');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
