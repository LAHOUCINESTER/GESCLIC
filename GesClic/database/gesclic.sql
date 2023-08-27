-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20230716.63e1777ffc
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 27 Août 2023 à 13:20
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

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

CREATE TABLE `administrateurs` (
  `id_admin` int(11) NOT NULL,
  `id_login` int(11) DEFAULT NULL,
  `id_entreprise` int(11) DEFAULT NULL,
  `Nom` varchar(255) DEFAULT NULL,
  `Prenom` varchar(255) DEFAULT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id_admin`, `id_login`, `id_entreprise`, `Nom`, `Prenom`, `email`) VALUES
(1, 14, 1, 'Admin', 'Admin', ''),
(11, 14, NULL, NULL, NULL, 'admin@mail.com'),
(12, 45, NULL, NULL, NULL, 'Saida@mail.com');

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
(2, 1, 'CIH', '6788987', '12457', NULL, '2023-08-25');

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
  `cin_img` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cin`
--

INSERT INTO `cin` (`id`, `cin`, `date_exp_cin`, `employe_id`, `date_update_cin`, `cin_img`) VALUES
(4, 'jb', '2023-08-01', 1, '2023-08-14', 'people.jpg');

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
(5, '2023-08-25', '2023-09-23', 'autre', '30');

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
  `vu` varchar(6) NOT NULL DEFAULT 'Non'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`N_dmd`, `type_dmd`, `DateSoumission`, `Statut`, `dscr_dmd`, `etat`, `id_conge`, `id_admin`, `employe_id`, `id_bltn`, `employeID_destinataire`, `adminID_destinataire`, `reponse`, `vu`) VALUES
(1, 'att_travail', '2023-07-28', 'prise', NULL, 'en cours', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Non'),
(2, 'recuperation', '2023-07-28', NULL, NULL, 'en cours', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Non'),
(3, 'autre', '2023-07-28', NULL, '', 'en cours', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Non'),
(4, 'infos', '2023-07-28', NULL, 'tache effectuées ', 'en cours', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Non'),
(5, 'infos', '2023-07-12', 'Non prise', 'informations ', 'en cours', NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Non'),
(6, 'Attestation de domicialiation de salaire', '2023-07-28', 'Non prise', '', 'en cours', NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Non'),
(7, 'Attestation de travail', '2023-07-29', 'Non prise', '', 'en cours', NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Non'),
(8, 'Autres demandes', '2023-08-05', 'Non prise', 'infos', 'en cours', NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Non'),
(9, 'Informations', '2023-08-06', 'Non prise', '', 'en cours', NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Non'),
(15, 'Congé', '2023-08-08', 'Non prise', 'Description', 'en cours', 4, NULL, 1, NULL, NULL, NULL, NULL, 'Non'),
(16, 'Bulletin de paie', '2023-08-08', 'Non prise', '', 'en cours', NULL, NULL, 1, 1, NULL, NULL, NULL, 'Non'),
(17, 'Attestation de travail', '2023-08-08', 'Non prise', '', 'en cours', NULL, 1, NULL, NULL, 1, NULL, 'Déjà envoyé \r\n', 'Oui'),
(18, 'Attestation de travail', '2023-08-08', 'Non prise', '', 'en cours', NULL, NULL, 1, NULL, NULL, 1, NULL, 'Non'),
(19, 'Autres demandes', '2023-08-08', 'Non prise', '', 'en cours', NULL, NULL, 1, NULL, NULL, 1, NULL, 'Non'),
(20, 'Récupération', '2023-08-08', 'Non prise', '', 'en cours', NULL, NULL, 1, NULL, NULL, 1, NULL, 'Non'),
(21, 'Congé', '2023-08-08', 'Non prise', '', 'en cours', 5, NULL, 1, NULL, NULL, 1, NULL, 'Non'),
(22, 'Bulletin de paie', '2023-08-08', 'Non prise', '', 'en cours', NULL, NULL, 1, 2, NULL, 1, NULL, 'Non'),
(23, 'Attestation de domicialiation de salaire', '2023-08-25', 'Non prise', '', 'en cours', NULL, NULL, 1, NULL, NULL, 1, NULL, 'Non'),
(24, 'ADemande par admin ', '2023-08-08', 'Non prise', '', 'en cours', NULL, 1, NULL, NULL, 1, NULL, '\r\n', 'Oui');

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `employe_id` int(11) NOT NULL,
  `Nom` varchar(20) DEFAULT NULL,
  `Poste` varchar(20) DEFAULT NULL,
  `DateEmbauche` date DEFAULT NULL,
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

INSERT INTO `employe` (`employe_id`, `Nom`, `Poste`, `DateEmbauche`, `email`, `chef_id`, `id_login`, `id_entreprise`, `prenom`, `cin`, `date_naissance`, `lieu_naissance`, `adresse`, `tele`, `email_perso`, `permis`, `sexe`, `civilite`, `situation_fam`, `ville`, `code_postal`, `nationalite`, `profil_img`) VALUES
(1, 'Marzougui', 'it', NULL, 'wij@mail.com', NULL, 15, 1, 'WIJDANE', 'jb', '2023-08-12', 'AGA', 'AGA', '0600124356', '', '12', 'Féminin', 'Mlle', 'Célibataire', 'AGA', '800', 'marocaine', ''),
(2, 'widad', 'IT', NULL, 'jijou@mail.com', 4, 19, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'TEST', NULL, NULL, 'wiju@mail.com', 4, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Chef', NULL, NULL, 'chef@mail.com', NULL, 18, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, NULL, NULL, NULL, 'fff@mail.com', NULL, 13, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'WIDANE2', NULL, NULL, 'wijdane@mail.com', 4, 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id_entreprise` int(11) NOT NULL,
  `Nom_Entreprise` varchar(255) DEFAULT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `Contact` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id_entreprise`, `Nom_Entreprise`, `Adresse`, `Contact`) VALUES
(1, 'yanclic', 'tasila', '060000000');

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
(13, 'fff@mail.com', '$2y$10$3/mTvGvpfF.0isSCNpHmnOAGNXSvLXTQISDjMe8JFvgolKuSQ2jQW', 'chef'),
(14, 'admin@mail.com', '$2y$10$DO4ec62t5.Y1kNkbfif3beQ0tnSJHN6pdlfuLYRqwbCiSZTQ/pseW', 'employeur'),
(15, 'wij@mail.com', '$2y$10$H.z6QyfJ5DQNBrPACkbyEOIm8x0eQv6IaTXSCRYokV2MUtG8FDwUW', 'employee'),
(18, 'chef@mail.com', '$2y$10$ZaZ.8JunRcorY1eLIotCpO05TCeC4b8ucXdFE47Md24MzHRMB.iAC', 'chef'),
(19, 'jijou@mail.com', '$2y$10$jxjUaSgkfzMb3/iPHp2oH..kxCCq6d.CtfDDod29ef1mnuGWaUkt.', 'employee'),
(43, 'wiju@mail.com', '$2y$10$3a/GkvqhGUNTTSakY/8pmO.dqK4dr68o2euvi1/R5XYDvhoar0XEC', 'employee'),
(44, 'wijdane@mail.com', '$2y$10$z0cq699qksVF/gyFWajpHuJ7Y5MRBmd1GVhtoaff.1uqkpO5Bx22y', 'employee'),
(45, 'Saida@mail.com', '$2y$10$njndDohsPSQAkd7Ti7S0oOAfi8D8JGcl0sNcdNTGLyZ2kELCKdjCG', 'employeur');

-- --------------------------------------------------------

--
-- Structure de la table `paie`
--

CREATE TABLE `paie` (
  `id_bltn` int(11) NOT NULL,
  `mois` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paie`
--

INSERT INTO `paie` (`id_bltn`, `mois`) VALUES
(1, '1'),
(2, 'Mai');

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
  `type_permis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `permis`
--

INSERT INTO `permis` (`id`, `permis`, `permis_img`, `date_exp_permis`, `date_update_permis`, `employe_id`, `type_permis`) VALUES
(4, '12', 'people.jpg', '2023-08-30', '2023-08-14', 1, 'A');

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
  `statut` varchar(30) NOT NULL DEFAULT 'present',
  `employe_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `pointer`
--

INSERT INTO `pointer` (`point_id`, `date`, `h_entree`, `h_entree_sys`, `h_entree_chef`, `h_sortie`, `h_sortie_sys`, `h_sortie_chef`, `chef_id`, `statut`, `employe_id`) VALUES
(1, '2023-07-27', '10:15:23', '00:00:00', '00:00:00', '17:57:00', '19:57:00', '00:00:00', NULL, 'present', 2),
(3, '0000-00-00', '17:58:00', '17:58:00', '00:00:00', NULL, '00:00:00', '00:00:00', NULL, 'present', NULL),
(4, '2023-07-28', '17:54:00', '17:54:00', '16:21:17', '17:54:00', '17:54:00', '00:00:00', NULL, 'absent', 2),
(5, '2023-07-28', '17:54:00', '17:54:00', '16:21:17', '17:54:00', '17:54:00', '00:00:00', NULL, 'present', NULL),
(6, '2023-07-28', '17:54:00', '17:54:00', '00:00:00', '17:54:00', '17:54:00', '00:00:00', NULL, 'present', 2),
(7, '2023-07-28', '17:54:00', '17:54:00', '16:21:17', '17:54:00', '17:54:00', '00:00:00', NULL, 'present', 2),
(8, '2023-07-28', '17:54:00', '17:54:00', '16:21:17', '17:54:00', '17:54:00', '00:00:00', NULL, 'present', 1),
(9, '2023-07-28', '17:54:00', '17:54:00', '16:21:17', '17:54:00', '17:54:00', '00:00:00', NULL, 'present', NULL),
(11, '2023-07-13', '16:55:00', '17:55:00', '16:21:17', '17:55:00', '18:55:00', '00:00:00', NULL, 'present', 1),
(12, '2023-07-26', '17:57:00', '17:57:00', '16:21:17', '17:57:00', '19:57:00', '00:00:00', NULL, 'present', 1),
(13, '2023-07-04', '18:02:00', '18:02:00', '16:21:17', '18:05:00', '20:05:00', '00:00:00', NULL, 'present', 1),
(14, '2023-07-29', '14:13:00', '16:13:00', '16:21:17', '16:17:00', '18:17:00', '00:00:00', NULL, 'present', NULL),
(15, '2023-08-01', '16:18:00', '16:18:00', '16:21:17', '19:15:00', '19:15:00', '00:00:00', NULL, 'present', NULL),
(18, '2023-08-06', '16:05:00', '16:05:00', NULL, '16:30:00', '18:30:00', NULL, NULL, 'present', 1),
(19, '2023-08-07', '16:41:00', '16:41:00', NULL, NULL, '00:00:00', NULL, NULL, 'present', 1),
(20, '2023-08-25', '18:43:00', '18:43:00', NULL, '22:35:00', '22:35:00', NULL, NULL, 'present', 1),
(21, '2023-08-26', '17:57:00', '17:57:00', NULL, NULL, '00:00:00', NULL, NULL, 'present', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_login` (`id_login`,`id_entreprise`),
  ADD KEY `id_entreprise` (`id_entreprise`);

--
-- Index pour la table `banque`
--
ALTER TABLE `banque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employe_id` (`employe_id`),
  ADD KEY `id_entreprise` (`id_entreprise`);

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
  ADD KEY `adminID_destinataire` (`adminID_destinataire`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`employe_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `chef_id` (`chef_id`),
  ADD KEY `id_entreprise` (`id_entreprise`),
  ADD KEY `id_login` (`id_login`);

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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `banque`
--
ALTER TABLE `banque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `cin`
--
ALTER TABLE `cin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `conges`
--
ALTER TABLE `conges`
  MODIFY `id_conge` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `N_dmd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `employe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id_entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `paie`
--
ALTER TABLE `paie`
  MODIFY `id_bltn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `permis`
--
ALTER TABLE `permis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `pointer`
--
ALTER TABLE `pointer`
  MODIFY `point_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD CONSTRAINT `administrateurs_ibfk_1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `administrateurs_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `banque`
--
ALTER TABLE `banque`
  ADD CONSTRAINT `banque_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `banque_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`id_bltn`) REFERENCES `paie` (`id_bltn`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `demande_ibfk_2` FOREIGN KEY (`employeID_destinataire`) REFERENCES `employe` (`employe_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `demande_ibfk_3` FOREIGN KEY (`adminID_destinataire`) REFERENCES `administrateurs` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_admin` FOREIGN KEY (`id_admin`) REFERENCES `administrateurs` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_conge` FOREIGN KEY (`id_conge`) REFERENCES `conges` (`id_conge`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_employe_id` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_1` FOREIGN KEY (`chef_id`) REFERENCES `employe` (`employe_id`),
  ADD CONSTRAINT `employe_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`),
  ADD CONSTRAINT `employe_ibfk_3` FOREIGN KEY (`id_login`) REFERENCES `login` (`id`);

--
-- Contraintes pour la table `permis`
--
ALTER TABLE `permis`
  ADD CONSTRAINT `permis_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pointer`
--
ALTER TABLE `pointer`
  ADD CONSTRAINT `pointer_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`employe_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pointer_ibfk_2` FOREIGN KEY (`chef_id`) REFERENCES `employe` (`chef_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
