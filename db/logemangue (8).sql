-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 09 jan. 2026 à 10:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `logemangue`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id_avis` int(11) NOT NULL,
  `note` decimal(3,1) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `date_avis` datetime DEFAULT current_timestamp(),
  `id_etudiant` int(11) DEFAULT NULL,
  `id_logement` int(11) DEFAULT NULL
) ;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id_avis`, `note`, `commentaire`, `date_avis`, `id_etudiant`, `id_logement`) VALUES
(21, 4.0, NULL, '2025-12-22 10:59:08', 42, 100);

--
-- Déclencheurs `avis`
--
DELIMITER $$
CREATE TRIGGER `maj_moyenne_apres_avis` AFTER INSERT ON `avis` FOR EACH ROW BEGIN
    UPDATE logement
    SET note = (
        SELECT AVG(a.note)
        FROM avis a
        WHERE a.id_logement = NEW.id_logement
    )
    WHERE ID = NEW.id_logement;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `ID` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `universite` varchar(100) DEFAULT NULL,
  `niveau_etude` varchar(50) DEFAULT NULL,
  `note` decimal(4,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`ID`, `nom`, `prenom`, `email`, `telephone`, `date_naissance`, `universite`, `niveau_etude`, `note`) VALUES
(1, 'CARRUETTE', 'Mathis', NULL, NULL, NULL, NULL, NULL, 4.4),
(4, 'CARRUETTE', 'Mathilde', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `logement`
--

CREATE TABLE `logement` (
  `ID` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `adresse` varchar(200) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `TYPE` text DEFAULT NULL,
  `note` decimal(5,2) NOT NULL,
  `surface` decimal(6,2) DEFAULT NULL,
  `loyer` decimal(10,2) DEFAULT NULL,
  `charges_incluses` tinyint(1) DEFAULT NULL,
  `meuble` tinyint(1) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT NULL,
  `id_proprietaire` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Waiting',
  `date_publication` date NOT NULL DEFAULT current_timestamp(),
  `colocation` int(1) DEFAULT NULL,
  `date_disponibilite` date DEFAULT NULL,
  `tags` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logement`
--

INSERT INTO `logement` (`ID`, `titre`, `description`, `adresse`, `ville`, `code_postal`, `TYPE`, `note`, `surface`, `loyer`, `charges_incluses`, `meuble`, `disponible`, `id_proprietaire`, `status`, `date_publication`, `colocation`, `date_disponibilite`, `tags`) VALUES
(99, 'Petit Studio La rochelle', 'Petit Studio La rochelle', 'Quartier Historique', 'LA ROCHELLE', '17000', 'Studio', 0.00, 15.00, 390.00, 0, 1, NULL, 42, 'Approved', '2025-12-21', NULL, '2025-12-25', NULL),
(100, 'T2 Plessis-Robinson Citadin', 'T2 Plessis-Robinson Citadin', 'Quartier Descartes', 'PARIS 01', '75001', 'Studio', 4.00, 41.00, 1290.00, 0, 1, NULL, 42, 'Approved', '2025-12-21', 0, '2026-01-07', NULL),
(101, 'T2 Avignon - Centre Historique', 'T2 Avignon - Centre Historique', 'Centre Historique', 'AVIGNON', '84000', 'T2', 0.00, 49.00, 1020.00, 0, 1, NULL, 42, 'Approved', '2025-12-21', NULL, NULL, NULL),
(102, 'Studio étudiant Clamart', 'Studio étudiant Clamart', 'Clamart', 'CLAMART', '92140', 'Studio', 0.00, 15.00, 605.00, 1, 1, NULL, 42, 'Approved', '2025-12-21', NULL, NULL, NULL),
(103, 'Studio Paris 64', 'Studio Paris 6', 'Centre Ville', 'PARIS 06', '75006', 'Studio', 0.00, 12.00, 605.00, 0, 1, NULL, 42, 'Approved', '2025-12-21', 0, '2025-12-10', NULL),
(104, 'ééééééééééééééééééééé', '&quot;&quot;&#039;&#039;ééé', 'ééééééééééééé', 'EECKE', '59114', 'T2', 0.00, 23.00, 23.00, 0, 0, NULL, 42, 'Waiting', '2025-12-22', NULL, NULL, NULL),
(105, 'Test des nouveaux champs', 'oui', 'OUi oui', 'LEVALLOIS PERRET', '92300', 'Studio', 0.00, 23.00, 455.00, 0, 0, NULL, 42, 'Approved', '2025-12-23', 1, '2026-01-09', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `ID` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `lu` tinyint(1) DEFAULT 0,
  `id_expediteur` int(11) NOT NULL,
  `id_destinataire` int(11) NOT NULL,
  `id_reservation` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`ID`, `contenu`, `date_envoi`, `lu`, `id_expediteur`, `id_destinataire`, `id_reservation`) VALUES
(91, 'svp, acceptez ma candidature', '2025-12-21 21:30:11', 0, 42, 42, NULL),
(92, 'svp, acceptez ma candidature', '2025-12-21 21:30:41', 0, 43, 42, NULL),
(93, 'non', '2025-12-21 21:33:38', 0, 42, 43, NULL),
(94, 'oui', '2025-12-23 15:35:08', 0, 42, 43, NULL),
(95, 'aled', '2025-12-23 15:37:06', 0, 42, 43, NULL),
(97, 'test', '2026-01-09 09:51:48', 0, 42, 42, NULL),
(98, 'ciao', '2026-01-09 10:00:46', 0, 42, 43, NULL),
(99, 'oui', '2026-01-09 10:01:04', 0, 42, 43, NULL),
(100, 'e', '2026-01-09 10:01:59', 0, 42, 43, NULL),
(101, 'ee', '2026-01-09 10:02:00', 0, 42, 43, NULL),
(102, 'yessir', '2026-01-09 10:05:01', 0, 42, 43, NULL),
(103, 'ca va le frere?', '2026-01-09 10:05:06', 0, 42, 43, NULL),
(104, 'oui', '2026-01-09 10:05:09', 0, 42, 43, NULL),
(105, 'x', '2026-01-09 10:05:12', 0, 42, 43, NULL),
(106, 'x', '2026-01-09 10:05:14', 0, 42, 43, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `id_photo` int(11) NOT NULL,
  `url_photo` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `id_logement` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`id_photo`, `url_photo`, `description`, `id_logement`) VALUES
(57, '../uploads/photo_6948006c8489a6.76984767.jpg', '', 99),
(58, '../uploads/photo_6948009abc3055.94165043.jpg', '', 100),
(59, '../uploads/photo_694800b919a4e1.77969327.webp', '', 101),
(60, '../uploads/photo_694800d1ef0885.93328444.jpg', '', 102),
(61, '../uploads/photo_69480194cfdd59.38039414.jpg', '', 103);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `ID` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `id_etudiant` int(11) DEFAULT NULL,
  `id_logement` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`ID`, `date_debut`, `date_fin`, `statut`, `montant`, `id_etudiant`, `id_logement`) VALUES
(40, '2025-12-21', '2026-01-21', 'Approuvée', 1290.00, 42, 100),
(42, '2025-12-21', '2026-01-21', 'Refusée', 390.00, 42, 99),
(43, '2025-12-24', '2026-01-24', 'En Attente', 1020.00, 42, 101);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `note` decimal(5,2) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `universite` varchar(100) DEFAULT NULL,
  `genre` varchar(10) DEFAULT NULL CHECK (`genre` in ('Homme','Femme','Autre')),
  `type_utilisateur` varchar(15) NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `biography` text DEFAULT NULL,
  `reset_token` varchar(255) NOT NULL,
  `reset_expires` datetime(6) NOT NULL,
  `facile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `created_at`, `password`, `nom`, `prenom`, `telephone`, `note`, `date_naissance`, `universite`, `genre`, `type_utilisateur`, `is_admin`, `biography`, `reset_token`, `reset_expires`, `facile`) VALUES
(42, 'mc.carruette@gmail.com', '2025-12-21 13:07:02', '$2y$10$VHcrwotLzPDfpbi8B3si8e92M6vEvvu9VJ99TnKS9dAzcocbRvIUW', 'Mathis CARRUETTE', NULL, '0784462719', NULL, '2025-12-10', NULL, 'Homme', 'Proprietaire', 1, '', '4529758263bfef052d6df953d860c105e0996def5ecf19b2963e507cbca92b96', '2026-01-09 11:07:04.000000', ''),
(43, 'etudiant@logemangue.fr', '2025-12-21 17:35:29', '$2y$10$zyuY1Det54lpI0G0hgVYkOSGUz9a.A/LFVj3lfDhWVXV.s8JtsalO', 'Etudiant Nom', NULL, '0784462719', NULL, '2025-12-09', NULL, 'Femme', 'Etudiant', NULL, '', '', '0000-00-00 00:00:00.000000', 'locataire.dossierfacile.logement.gouv.fr/public-file/80cb71cb-64d6-4dc9-97d3-f621fc1b3029');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_logement` (`id_logement`),
  ADD KEY `avis_ibfk_1` (`id_etudiant`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `logement`
--
ALTER TABLE `logement`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `proprietaire` (`id_proprietaire`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_expediteur` (`id_expediteur`),
  ADD KEY `id_destinataire` (`id_destinataire`),
  ADD KEY `message_ibfk_3` (`id_reservation`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id_photo`),
  ADD KEY `photo_ibfk_1` (`id_logement`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `reservation_ibfk_2` (`id_logement`),
  ADD KEY `reservation_ibfk_1` (`id_etudiant`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `logement`
--
ALTER TABLE `logement`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_logement`) REFERENCES `logement` (`ID`);

--
-- Contraintes pour la table `logement`
--
ALTER TABLE `logement`
  ADD CONSTRAINT `proprietaire` FOREIGN KEY (`id_proprietaire`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_expediteur`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_destinataire`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_ibfk_3` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`ID`) ON DELETE SET NULL;

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`id_logement`) REFERENCES `logement` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`id_logement`) REFERENCES `logement` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
