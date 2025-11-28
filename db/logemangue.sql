-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 25 nov. 2025 à 10:22
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
  `TYPE` varchar(50) DEFAULT NULL,
  `note` decimal(5,2) NOT NULL,
  `surface` decimal(6,2) DEFAULT NULL,
  `loyer` decimal(10,2) DEFAULT NULL,
  `charges_incluses` tinyint(1) DEFAULT NULL,
  `meuble` tinyint(1) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT NULL,
  `id_proprietaire` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Waiting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logement`
--

INSERT INTO `logement` (`ID`, `titre`, `description`, `adresse`, `ville`, `code_postal`, `TYPE`, `note`, `surface`, `loyer`, `charges_incluses`, `meuble`, `disponible`, `id_proprietaire`, `status`) VALUES
(37, 'Première annonce', 'testesttest', 'Quartier des chiens', 'Paris 17e Arrondissement', '92300', '0', 0.00, 32.00, 1982.00, 0, 0, NULL, 4, 'Approved'),
(38, 'Magnifique T2 Porte de Saint-Cloud', 'RANDOM', '113 Rue Danton B2', 'Levallois-Perret', '92300', '0', 0.00, 232.00, 87343.00, 0, 0, NULL, 4, 'Approved'),
(39, 'Test de Logement', 'A', 'Test de Logement', 'Test de Logement', 'Test de Lo', '0', 0.00, 3.00, 2.00, 0, 0, NULL, 4, 'Approved'),
(42, 'Test d\'Appartement n°1', 'Oui ', 'Quartier de Levallois', 'Levallois-Perret', '92300', '0', 0.00, 32.00, 887.00, 1, 0, NULL, 4, 'Approved'),
(43, 'Deuxième annonce', 'Beau T2 Versailles.', 'Quartier Versailles', 'Versailles', '78646', '0', 0.00, 42.00, 1430.00, 1, 1, NULL, 4, 'Approved');

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
(47, 'Bonsoir', '2025-11-21 20:24:10', 0, 4, 8, NULL),
(48, 'Bonsoir Monsieur', '2025-11-21 20:34:42', 0, 4, 7, NULL),
(49, 'Bonsoir à vous', '2025-11-21 20:36:05', 0, 7, 8, NULL),
(50, 'comment allez-vous?', '2025-11-25 10:19:52', 0, 4, 7, NULL);

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
(1, '2025-11-12', '2025-11-22', NULL, NULL, 4, 38);

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
  `biography` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `created_at`, `password`, `nom`, `prenom`, `telephone`, `note`, `date_naissance`, `universite`, `genre`, `type_utilisateur`, `is_admin`, `biography`) VALUES
(4, 'mc.carruette@gmail.com', '2025-11-04 19:07:07', '$2y$10$.MdmOd0bJ7.QECfZQUXYheHWbMwGp75exeRkAyeA4rqTVXfbA7Q6u', 'Mathis Carruette', NULL, '0784462719', NULL, '2025-11-05', NULL, 'Homme', 'Proprietaire', 1, ''),
(7, 'mc.carruette+3@gmail.com', '2025-11-04 19:14:41', '$2y$10$axI8bijBkYJiDeUgOKg44O.aTCgtOP1Xz82y.Ut1mUU08cqG15YOi', 'Compte2', NULL, '0606060606', NULL, '2025-11-26', NULL, 'Femme', 'Etudiant', NULL, ''),
(8, 'invite@f', '2025-11-04 19:41:50', '$2y$10$LVHWSpPDdLRWhVgO8IjuzuFOYJv/X.9jGA6PJetfR8LQcWOcaFYeO', 'Jules Neuille', NULL, '0606060606', NULL, '1976-07-31', NULL, 'Homme', 'Etudiant', NULL, 'Je suis la'),
(9, 'arthur.heilman@pasdia.fr', '2025-11-07 08:02:22', '$2y$10$CIkkbkEhb1R.bDwg2UllpeIdwi2SEJMO6ef.OVRPKaKlLmewedmAu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL),
(10, 'testdate@f', '2025-11-07 16:59:54', '$2y$10$may2tDLrftWwoGzBQcWnru/I.bgDKzwnW/Ln3dUzrZgKkdjClur2S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL),
(12, 'newdbtest@test.fr', '2025-11-07 18:00:54', '$2y$10$.Kb80cTVXx2xTITTrU4Bm.9IxxX1vyzJO8E1LogcvU5KTnWWrlfSW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL),
(13, 'invite@dfgdfg', '2025-11-07 18:05:25', '$2y$10$XtP5s97ir.qj0tvTkJNae.GgeOWO0h2khJccDT4Cd.3qkNL2Vbylu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL),
(14, 'logemangueclient@client.fr', '2025-11-08 14:56:27', '$2y$10$3XgEo0qRBGEf.HwydMDTf.zTth.QtH0CdyB7uRV84vtIwa98GDoJK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_logement` (`id_logement`);

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
  ADD KEY `id_logement` (`id_logement`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_logement` (`id_logement`),
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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`ID`),
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
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`id_logement`) REFERENCES `logement` (`ID`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`id_logement`) REFERENCES `logement` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
