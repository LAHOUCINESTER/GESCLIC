-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20230716.63e1777ffc
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Lun. 02 Oct. 2023 à 17:16
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gesclic`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `validate_est_chef` (IN `in_employee_id` INT, IN `in_est_chef` INT, IN `in_chef_id` INT)   BEGIN
    IF in_est_chef = 1 AND in_chef_id IS NOT NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un employé chef ne peut pas avoir un chef.'$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `absence`
--

CREATE TABLE `absence` (
  `id` int(11) NOT NULL,
  `employe_id` int(11) DEFAULT NULL,
  `chef_id` int(11) DEFAULT NULL,
  `cause` varchar(30) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `absence`
--

INSERT INTO `absence` (`id`, `employe_id`, `chef_id`, `cause`, `date`) VALUES
(5, 3, 4, 'Maladie', '2023-08-02'),
(6, 1, 4, 'Congé', '2023-08-09'),
(8, 1, 4, 'Maladie', '2023-09-10'),
(9, 4, 4, 'Mission', '2023-09-23');

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

CREATE TABLE `administrateurs` (
  `id_admin` int(11) NOT NULL,
  `id_login` int(11) DEFAULT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id_admin`, `id_login`, `email`) VALUES
(1, 14, ''),
(11, 14, 'admin@mail.com'),
(12, NULL, 'Saida@mail.com'),
(15, 66, 'neww@mail.com'),
(16, 67, 'employeur@mail.com');

-- --------------------------------------------------------

--
-- Structure de la table `admin_entreprise`
--

CREATE TABLE `admin_entreprise` (
  `id_admin` int(11) DEFAULT NULL,
  `id_entreprise` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `admin_entreprise`
--

INSERT INTO `admin_entreprise` (`id_admin`, `id_entreprise`, `id`) VALUES
(11, 1, 1),
(11, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `banque`
--

CREATE TABLE `banque` (
  `id` int(11) NOT NULL,
  `employe_id` int(11) DEFAULT NULL,
  `nom_banque` varchar(100) DEFAULT NULL,
  `rib` varchar(50) DEFAULT NULL,
  `iban` varchar(50) DEFAULT NULL,
  `id_entreprise` int(11) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `banque`
--

INSERT INTO `banque` (`id`, `employe_id`, `nom_banque`, `rib`, `iban`, `id_entreprise`, `date`) VALUES
(2, 1, 'CIH', '6788987', '12457', NULL, '2023-08-25'),
(3, NULL, 'Barid Bank', '145600809090', 'U9000000', 1, '2023-09-20');

-- --------------------------------------------------------

--
-- Structure de la table `chef`
--

CREATE TABLE `chef` (
  `chef_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cin` int(8) NOT NULL,
  `archive` int(11) NOT NULL DEFAULT 0,
  `id_entreprise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `chef`
--

INSERT INTO `chef` (`chef_id`, `email`, `cin`, `archive`, `id_entreprise`) VALUES
(3, 'Cheff@mail.com', 87654321, 1, 1),
(4, 'chef@mail.com', 87654321, 0, 1),
(14, 'testchef@mail.com', 12345678, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `cin`
--

CREATE TABLE `cin` (
  `id` int(11) NOT NULL,
  `cin` varchar(8) DEFAULT NULL,
  `date_exp_cin` date DEFAULT NULL,
  `employe_id` int(11) DEFAULT NULL,
  `date_update_cin` date DEFAULT NULL,
  `cin_img` text DEFAULT NULL,
  `cin_img2` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cin`
--

INSERT INTO `cin` (`id`, `cin`, `date_exp_cin`, `employe_id`, `date_update_cin`, `cin_img`, `cin_img2`) VALUES
(4, 'jb', '2023-08-01', 1, '2023-08-14', 'people.jpg', NULL),
(7, 'D1324561', '2023-09-20', 1, '2023-10-02', '651ace22dd991_', '651ace22dd998_'),
(8, 'JB12345', '2023-09-30', 8, '2023-09-22', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `conges`
--

CREATE TABLE `conges` (
  `id_conge` int(11) NOT NULL,
  `DateDebut_conge` date DEFAULT NULL,
  `DateFin_conge` date DEFAULT NULL,
  `type_conge` varchar(30) DEFAULT NULL,
  `periode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `conges`
--

INSERT INTO `conges` (`id_conge`, `DateDebut_conge`, `DateFin_conge`, `type_conge`, `periode`) VALUES
(1, '2023-08-02', '2023-08-21', 'autre', '20'),
(2, '2023-08-09', '2023-08-10', 'autre', '1'),
(3, '2023-08-09', '2023-08-10', 'autre', '1'),
(4, '2023-08-08', '2023-08-17', 'autre', '9'),
(5, '2023-08-25', '2023-09-23', 'autre', '30'),
(6, '2023-09-07', '2023-09-29', 'autre', '22'),
(7, '2023-09-07', '2023-09-29', 'autre', '22'),
(8, '2023-09-07', '2023-09-29', 'autre', '22'),
(9, '2023-09-15', '2023-10-04', 'annuel', '20'),
(10, '2023-09-13', '2023-10-22', 'annuel', '40'),
(11, '2023-10-01', '2023-10-03', 'annuel', '2');

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

CREATE TABLE `demande` (
  `N_dmd` int(11) NOT NULL,
  `type_dmd` varchar(100) DEFAULT NULL,
  `DateSoumission` date DEFAULT NULL,
  `Statut` varchar(50) DEFAULT 'Non prise',
  `dscr_dmd` varchar(100) DEFAULT NULL,
  `etat` varchar(30) NOT NULL DEFAULT 'en cours',
  `id_conge` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `employe_id` int(11) DEFAULT NULL,
  `id_bltn` int(11) DEFAULT NULL,
  `employeID_destinataire` int(11) DEFAULT NULL,
  `adminID_destinataire` int(11) DEFAULT NULL,
  `reponse` varchar(100) DEFAULT NULL,
  `vu` varchar(6) NOT NULL DEFAULT 'Non',
  `id_recup` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`N_dmd`, `type_dmd`, `DateSoumission`, `Statut`, `dscr_dmd`, `etat`, `id_conge`, `id_admin`, `employe_id`, `id_bltn`, `employeID_destinataire`, `adminID_destinataire`, `reponse`, `vu`, `id_recup`) VALUES
(68, 'Attestation de travail', '2023-09-19', 'prise', 'Fin de stage ', 'Consultée', NULL, NULL, 1, NULL, NULL, 11, 'Attestation envoyée ', 'Oui', NULL),
(69, 'Avertissement', '2023-09-19', 'Non prise', '', 'Consultée', NULL, 11, NULL, NULL, 1, NULL, 'Réponse envoyé \r\n', 'Oui', NULL),
(70, 'Congé', '2023-09-25', 'Non prise', '', 'Consultée', 10, NULL, 1, NULL, NULL, 11, 'Réponse envoyé \r\n', 'Oui', NULL),
(71, 'Congé', '2023-10-01', 'Non prise', '', 'en cours', 11, NULL, 1, NULL, NULL, 11, NULL, 'Non', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `employe_id` int(11) NOT NULL,
  `Nom` varchar(20) DEFAULT NULL,
  `Poste` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `chef_id` int(11) DEFAULT NULL,
  `id_login` int(11) DEFAULT NULL,
  `id_entreprise` int(11) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `cin` varchar(8) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(50) DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `tele` varchar(10) DEFAULT NULL,
  `email_perso` varchar(50) DEFAULT NULL,
  `permis` varchar(50) DEFAULT NULL,
  `sexe` varchar(50) DEFAULT NULL,
  `civilite` varchar(50) DEFAULT NULL,
  `situation_fam` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `profil_img` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`employe_id`, `Nom`, `Poste`, `email`, `chef_id`, `id_login`, `id_entreprise`, `prenom`, `cin`, `date_naissance`, `lieu_naissance`, `adresse`, `tele`, `email_perso`, `permis`, `sexe`, `civilite`, `situation_fam`, `ville`, `code_postal`, `nationalite`, `profil_img`) VALUES
(1, 'Marzougui', 'Développement inform', 'wij@mail.com', 4, 15, 1, 'WIJDANE', 'D1324561', '2001-08-08', 'AGA', 'AGA', '0600124356', '', '1346FD45', 'Féminin', 'Mlle', 'Célibataire', 'AGA', '800', 'marocaine', '64f77799b19c9_20170902_132544.png'),
(3, 'TEST', 'Infographie', 'wiju@mail.com', 14, 43, 1, NULL, '12345678', '2004-09-08', NULL, NULL, NULL, NULL, NULL, 'Masculin', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Chef', 'Responsable RH', 'chef_emp@mail.com', 4, 18, 1, NULL, '87654321', '2003-09-17', NULL, NULL, NULL, NULL, NULL, 'Masculin', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Aymane', 'elk', 'employe@mail.com', NULL, 60, NULL, NULL, 'JB12345', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `employepro`
--

CREATE TABLE `employepro` (
  `id` int(11) NOT NULL,
  `employe_id` int(11) NOT NULL,
  `niveau_etude` varchar(50) DEFAULT NULL,
  `diplome` varchar(50) DEFAULT NULL,
  `specialite` varchar(50) DEFAULT NULL,
  `cv` text DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `employepro`
--

INSERT INTO `employepro` (`id`, `employe_id`, `niveau_etude`, `diplome`, `specialite`, `cv`, `date`) VALUES
(5, 1, 'Bac+3', NULL, 'dévelopement web ', NULL, '2023-09-05 19:04:40');

-- --------------------------------------------------------

--
-- Structure de la table `employe_entreprise`
--

CREATE TABLE `employe_entreprise` (
  `id` int(11) NOT NULL,
  `employe_id` int(11) DEFAULT NULL,
  `id_entreprise` int(11) DEFAULT NULL,
  `DateEmbauche` date DEFAULT NULL,
  `DateFin` date DEFAULT NULL,
  `fonction` varchar(50) DEFAULT NULL,
  `departement` varchar(100) DEFAULT NULL,
  `archive` int(11) NOT NULL DEFAULT 0,
  `update_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `employe_entreprise`
--

INSERT INTO `employe_entreprise` (`id`, `employe_id`, `id_entreprise`, `DateEmbauche`, `DateFin`, `fonction`, `departement`, `archive`, `update_date`) VALUES
(1, 5, 1, '2022-09-13', NULL, NULL, NULL, 0, '2023-09-19 22:02:39'),
(2, 1, 1, '2021-09-01', NULL, 'Développement informatique', 'IT', 0, '2023-09-19 22:02:39'),
(4, 8, 1, '2023-09-19', '2023-09-30', NULL, NULL, 1, '2023-09-19 22:46:26'),
(7, 4, 1, '2023-07-07', NULL, 'rh', 'RH', 0, '2023-09-25 15:57:32'),
(8, 3, 1, '2021-09-25', NULL, 'Infographie', NULL, 0, '2023-09-25 19:56:26');

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id_entreprise` int(11) NOT NULL,
  `Nom_Entreprise` varchar(255) DEFAULT NULL,
  `Contact` varchar(255) DEFAULT NULL,
  `patente` varchar(50) DEFAULT NULL,
  `fiscale` varchar(50) DEFAULT NULL,
  `ice` varchar(50) DEFAULT NULL,
  `cnss` varchar(50) DEFAULT NULL,
  `tp` varchar(50) DEFAULT NULL,
  `rc` varchar(50) DEFAULT NULL,
  `comp_assurance` varchar(100) DEFAULT NULL,
  `caisse_retraite` varchar(100) DEFAULT NULL,
  `forme` varchar(50) DEFAULT NULL,
  `activite` varchar(100) DEFAULT NULL,
  `delegation` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `adress1` varchar(100) DEFAULT NULL,
  `adress2` varchar(100) DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL,
  `ste_img` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id_entreprise`, `Nom_Entreprise`, `Contact`, `patente`, `fiscale`, `ice`, `cnss`, `tp`, `rc`, `comp_assurance`, `caisse_retraite`, `forme`, `activite`, `delegation`, `region`, `ville`, `email`, `fax`, `adress1`, `adress2`, `web`, `ste_img`) VALUES
(1, 'yanclic', '060000000', '12345', '146567', 'ICE123', 'CNSS12', '156', '1688', 'Assurance', 'Caisse', 'forme', 'activitee', 'dele', 'souss massa ', 'agadir ', 'yan@mail.com', '57898987', 'ADDR1', 'ADDR2', '', '650b2bec239b7_laptop.jpg'),
(2, 'TEST_ste', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'TEST_3', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('employee','chef','employeur') NOT NULL DEFAULT 'employee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `login`
--

INSERT INTO `login` (`id`, `email`, `password`, `user_type`) VALUES
(14, 'admin@mail.com', '$2y$10$DO4ec62t5.Y1kNkbfif3beQ0tnSJHN6pdlfuLYRqwbCiSZTQ/pseW', 'employeur'),
(15, 'wij@mail.com', '$2y$10$H.z6QyfJ5DQNBrPACkbyEOIm8x0eQv6IaTXSCRYokV2MUtG8FDwUW', 'employee'),
(18, 'chef@mail.com', '$2y$10$ZaZ.8JunRcorY1eLIotCpO05TCeC4b8ucXdFE47Md24MzHRMB.iAC', 'chef'),
(43, 'wiju@mail.com', '$2y$10$3a/GkvqhGUNTTSakY/8pmO.dqK4dr68o2euvi1/R5XYDvhoar0XEC', 'employee'),
(60, 'employe@mail.com', '$2y$10$8jXPAhPInGVYZ2plJ5wHF.jxZZs3kB4/.UbNb8r7RPpT8Hgl3dQTG', 'employee'),
(66, 'neww@mail.com', '$2y$10$crYFWgqw.tWG3kU5B1hws.1I3URKaUrxDpXWby8oBmOVAEzy90DUa', 'employeur'),
(67, 'employeur@mail.com', '$2y$10$6M9GM4/axQ7KIqLUNdQf8./BDqzlcR0yA75idC1QOqaBghYcFFVLu', 'employeur'),
(82, 'testchef@mail.com', '$2y$10$Il/Dk0bzJRPpTBYH71dLPuPoisp9ozEcXzTP7t3LGZhajxvRTHDVW', 'chef');

-- --------------------------------------------------------

--
-- Structure de la table `paie`
--

CREATE TABLE `paie` (
  `id_bltn` int(11) NOT NULL,
  `mois` varchar(30) DEFAULT NULL,
  `annee` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paie`
--

INSERT INTO `paie` (`id_bltn`, `mois`, `annee`) VALUES
(1, '1', NULL),
(2, 'Mai', NULL),
(3, 'Mars', NULL),
(4, 'Avril', NULL),
(5, 'Mars', NULL),
(6, 'Mars', NULL),
(7, 'Janvier', NULL),
(8, 'Mars', NULL),
(9, 'Juillet', NULL),
(10, 'Octobre', NULL),
(11, 'Mai', '2022');

-- --------------------------------------------------------

--
-- Structure de la table `permis`
--

CREATE TABLE `permis` (
  `id` int(11) NOT NULL,
  `permis` varchar(50) DEFAULT NULL,
  `permis_img` text DEFAULT NULL,
  `date_exp_permis` date DEFAULT NULL,
  `date_update_permis` date DEFAULT NULL,
  `employe_id` int(11) DEFAULT NULL,
  `type_permis` varchar(50) NOT NULL,
  `permis_img2` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `permis`
--

INSERT INTO `permis` (`id`, `permis`, `permis_img`, `date_exp_permis`, `date_update_permis`, `employe_id`, `type_permis`, `permis_img2`) VALUES
(4, '12', 'people.jpg', '2023-08-30', '2023-08-14', 1, 'A', NULL),
(7, '1346FD45', 'png_20220118_180110_0000.png', '2023-09-30', '2023-10-02', 1, 'A', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `pointer`
--

CREATE TABLE `pointer` (
  `point_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `h_entree` time DEFAULT NULL,
  `h_entree_sys` time NOT NULL,
  `h_entree_chef` time DEFAULT NULL,
  `h_sortie` time DEFAULT NULL,
  `h_sortie_sys` time NOT NULL,
  `h_sortie_chef` time DEFAULT NULL,
  `chef_id` int(11) DEFAULT NULL,
  `statut` varchar(30) NOT NULL DEFAULT 'Absent',
  `employe_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `pointer`
--

INSERT INTO `pointer` (`point_id`, `date`, `h_entree`, `h_entree_sys`, `h_entree_chef`, `h_sortie`, `h_sortie_sys`, `h_sortie_chef`, `chef_id`, `statut`, `employe_id`) VALUES
(5, '2023-07-28', '17:54:00', '17:54:00', '16:21:17', '17:54:00', '17:54:00', '00:00:00', NULL, 'present', NULL),
(8, '2023-08-27', '17:54:00', '17:54:00', '16:21:17', '17:54:00', '17:54:00', '00:00:00', NULL, 'present', 1),
(9, '2023-07-28', '17:54:00', '17:54:00', '16:21:17', '17:54:00', '17:54:00', '00:00:00', NULL, 'present', NULL),
(11, '2023-07-13', '16:55:00', '17:55:00', '16:21:17', '17:55:00', '18:55:00', '00:00:00', NULL, 'present', 1),
(12, '2023-07-26', '17:57:00', '17:57:00', '16:21:17', '17:57:00', '19:57:00', '00:00:00', NULL, 'present', 1),
(13, '2023-07-04', '18:02:00', '18:02:00', '16:21:17', '18:05:00', '20:05:00', '00:00:00', NULL, 'present', 1),
(14, '2023-07-29', '14:13:00', '16:13:00', '16:21:17', '16:17:00', '18:17:00', '00:00:00', NULL, 'present', NULL),
(15, '2023-08-01', '16:18:00', '16:18:00', '16:21:17', '19:15:00', '19:15:00', '00:00:00', NULL, 'present', NULL),
(18, '2023-08-06', '16:05:00', '16:05:00', NULL, '16:30:00', '18:30:00', NULL, NULL, 'present', 1),
(19, '2023-08-27', '16:41:00', '16:41:00', '16:41:00', NULL, '00:00:00', '20:30:00', NULL, 'present', 1),
(20, '2023-08-25', '18:43:00', '18:43:00', NULL, '22:35:00', '22:35:00', NULL, NULL, 'present', 1),
(21, '2023-08-26', '17:57:00', '17:57:00', NULL, NULL, '00:00:00', NULL, NULL, 'present', 1),
(22, '2023-08-30', '15:42:00', '15:42:00', NULL, NULL, '00:00:00', NULL, NULL, 'present', 1),
(31, '2023-09-01', NULL, '00:00:00', '09:09:18', NULL, '23:54:03', '23:54:03', 4, 'present', 3),
(51, '2023-10-02', NULL, '15:45:21', '15:45:21', NULL, '00:00:00', NULL, 14, 'present', 3);

-- --------------------------------------------------------

--
-- Structure de la table `recuperation`
--

CREATE TABLE `recuperation` (
  `id` int(11) NOT NULL,
  `DateDebut_recup` date NOT NULL,
  `DateFin_recup` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `recuperation`
--

INSERT INTO `recuperation` (`id`, `DateDebut_recup`, `DateFin_recup`) VALUES
(1, '2023-09-01', '2023-09-16');

-- --------------------------------------------------------

--
-- Structure de la table `repos`
--

CREATE TABLE `repos` (
  `id_repos` int(11) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `repos`
--

INSERT INTO `repos` (`id_repos`, `titre`, `date_debut`, `date_fin`) VALUES
(13, 'jour2', '2023-05-08', '2023-05-08'),
(26, 'JOur', '2023-09-02', '2023-09-07');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `absence`
--
ALTER TABLE `absence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employe_id` (`employe_id`,`chef_id`),
  ADD KEY `chef_id` (`chef_id`);

--
-- Index pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_login` (`id_login`);

--
-- Index pour la table `admin_entreprise`
--
ALTER TABLE `admin_entreprise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_admin` (`id_admin`,`id_entreprise`),
  ADD KEY `id_entreprise` (`id_entreprise`);

--
-- Index pour la table `banque`
--
ALTER TABLE `banque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employe_id` (`employe_id`),
  ADD KEY `id_entreprise` (`id_entreprise`);

--
-- Index pour la table `chef`
--
ALTER TABLE `chef`
  ADD PRIMARY KEY (`chef_id`);

--
-- Index pour la table `cin`
--
ALTER TABLE `cin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cin` (`cin`),
  ADD KEY `employe_id` (`employe_id`);

--
-- Index pour la table `conges`
--
ALTER TABLE `conges`
  ADD PRIMARY KEY (`id_conge`);

--
-- Index pour la table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`N_dmd`),
  ADD KEY `id_conge` (`id_conge`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `fk_employe_id` (`employe_id`),
  ADD KEY `id_bltn` (`id_bltn`),
  ADD KEY `employeID_destinataire` (`employeID_destinataire`,`adminID_destinataire`),
  ADD KEY `adminID_destinataire` (`adminID_destinataire`),
  ADD KEY `id_recup` (`id_recup`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`employe_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_entreprise` (`id_entreprise`),
  ADD KEY `id_login` (`id_login`),
  ADD KEY `chef_id` (`chef_id`);

--
-- Index pour la table `employepro`
--
ALTER TABLE `employepro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employe_id` (`employe_id`);

--
-- Index pour la table `employe_entreprise`
--
ALTER TABLE `employe_entreprise`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id_entreprise`);

--
-- Index pour la table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paie`
--
ALTER TABLE `paie`
  ADD PRIMARY KEY (`id_bltn`);

--
-- Index pour la table `permis`
--
ALTER TABLE `permis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `num_permis` (`permis`),
  ADD KEY `employe_id` (`employe_id`);

--
-- Index pour la table `pointer`
--
ALTER TABLE `pointer`
  ADD PRIMARY KEY (`point_id`),
  ADD KEY `chef_id` (`chef_id`,`employe_id`),
  ADD KEY `employe_id` (`employe_id`);

--
-- Index pour la table `recuperation`
--
ALTER TABLE `recuperation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `repos`
--
ALTER TABLE `repos`
  ADD PRIMARY KEY (`id_repos`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `absence`
--
ALTER TABLE `absence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `admin_entreprise`
--
ALTER TABLE `admin_entreprise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `banque`
--
ALTER TABLE `banque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `chef`
--
ALTER TABLE `chef`
  MODIFY `chef_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `cin`
--
ALTER TABLE `cin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `conges`
--
ALTER TABLE `conges`
  MODIFY `id_conge` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `N_dmd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `employe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `employepro`
--
ALTER TABLE `employepro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `employe_entreprise`
--
ALTER TABLE `employe_entreprise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id_entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT pour la table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT pour la table `paie`
--
ALTER TABLE `paie`
  MODIFY `id_bltn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `permis`
--
ALTER TABLE `permis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `pointer`
--
ALTER TABLE `pointer`
  MODIFY `point_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `recuperation`
--
ALTER TABLE `recuperation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `repos`
--
ALTER TABLE `repos`
  MODIFY `id_repos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `absence`
--
ALTER TABLE `absence`
  ADD CONSTRAINT `absence_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD CONSTRAINT `administrateurs_ibfk_1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `admin_entreprise`
--
ALTER TABLE `admin_entreprise`
  ADD CONSTRAINT `admin_entreprise_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administrateurs` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_entreprise_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `banque`
--
ALTER TABLE `banque`
  ADD CONSTRAINT `banque_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `banque_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `cin`
--
ALTER TABLE `cin`
  ADD CONSTRAINT `cin_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`id_bltn`) REFERENCES `paie` (`id_bltn`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `demande_ibfk_2` FOREIGN KEY (`employeID_destinataire`) REFERENCES `employe` (`employe_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `demande_ibfk_3` FOREIGN KEY (`adminID_destinataire`) REFERENCES `administrateurs` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `demande_ibfk_4` FOREIGN KEY (`id_recup`) REFERENCES `recuperation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_admin` FOREIGN KEY (`id_admin`) REFERENCES `administrateurs` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_conge` FOREIGN KEY (`id_conge`) REFERENCES `conges` (`id_conge`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_employe_id` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_3` FOREIGN KEY (`id_login`) REFERENCES `login` (`id`),
  ADD CONSTRAINT `employe_ibfk_4` FOREIGN KEY (`chef_id`) REFERENCES `chef` (`chef_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `employepro`
--
ALTER TABLE `employepro`
  ADD CONSTRAINT `employepro_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `permis`
--
ALTER TABLE `permis`
  ADD CONSTRAINT `permis_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pointer`
--
ALTER TABLE `pointer`
  ADD CONSTRAINT `pointer_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
