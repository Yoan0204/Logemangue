-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 21 jan. 2026 à 23:18
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Structure de la table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `sujet` enum('question','logement','technique','compte','autre') NOT NULL,
  `message` text NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `statut` enum('non_lu','en_cours','traite','archive') DEFAULT 'non_lu',
  `repondu` tinyint(1) DEFAULT 0,
  `date_reponse` datetime DEFAULT NULL,
  `note_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contacts`
--

INSERT INTO `contacts` (`id`, `user_id`, `nom`, `email`, `telephone`, `sujet`, `message`, `date_creation`, `statut`, `repondu`, `date_reponse`, `note_admin`) VALUES
(1, 96, 'CARRUETTE Mathis', 'mc.carruette@gmail.com', '0784462719', 'question', 'test', '2026-01-20 18:51:56', 'non_lu', 0, NULL, NULL);

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
-- Structure de la table `faq`
--

CREATE TABLE `faq` (
  `id_faq` int(11) NOT NULL,
  `question` text NOT NULL,
  `reponse` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `faq`
--

INSERT INTO `faq` (`id_faq`, `question`, `reponse`) VALUES
(1, 'Comment créer un compte ?', 'Cliquez sur le bouton \"Inscription\" en haut de la page, remplissez le formulaire puis validez. Un email de confirmation pourra vous être envoyé.'),
(2, 'Comment se connecter à mon compte ?', 'Cliquez sur \"Connexion\", saisissez votre adresse email et votre mot de passe, puis validez.'),
(3, 'J’ai oublié mon mot de passe, que faire ?', 'Cliquez sur \"Mot de passe oublié\" sur la page de connexion et suivez les instructions pour en créer un nouveau.'),
(4, 'Comment publier une annonce de logement ?', 'Vous devez être connecté en tant que propriétaire ou organisme. Une fois connecté, cliquez sur \"Publier une annonce\" et complétez le formulaire.'),
(5, 'Pourquoi mon logement est en attente de validation ?', 'Tous les logements sont vérifiés par un administrateur avant publication afin de garantir la qualité et la sécurité des annonces.'),
(6, 'Comment contacter un propriétaire ou un étudiant ?', 'Utilisez le bouton \"Contacter\" présent sur une annonce pour pouvoir contacter un propriétaire'),
(7, 'Puis-je modifier ou supprimer mon annonce ?', 'Oui, depuis la section \"Mes annonces\", vous pouvez modifier ou supprimer vos logements à tout moment.'),
(8, 'Comment modifier mes informations personnelles ?', 'Rendez-vous dans la section \"Mon profil\" pour mettre à jour vos informations personnelles.'),
(9, 'Qui peut voir mes informations personnelles ?', 'Vos informations personnelles sont protégées et ne sont visibles par personne, seulement votre numéro de téléphone et votre nom lorsque vous échangez avec un utilisateur.'),
(10, 'Comment contacter le support ?', 'Vous pouvez nous contacter via la page \"Contact\" disponible en bas de page ou envoyer un mail directement à l\'adresse support@logemangue.fr.');

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
(106, 'Studio d&amp;#039;exemple', '\\\\\\\\\\\\\r\ntest', 'Quartier du marché', 'LE PLESSIS ROBINSON', '92350', 'Studio', 0.00, 12.00, 500.00, 1, 1, NULL, 96, 'Approved', '2026-01-20', 0, '2026-01-20', NULL),
(108, 'u000', '\\\\\\\\\\', 'u000', 'u000', 'u000', 'Studio', 0.00, 3.00, 2.00, 0, 0, NULL, 96, 'Waiting', '2026-01-20', 0, '0000-00-00', NULL),
(110, 'test d&#039;&#039;&#039;&#039;éééé&quot;&quot;&quot;&quot;\\\\', 'test d&#039;&#039;&#039;&#039;éééé&quot;&quot;&quot;&quot;\\\\', 'test d&#039;&#039;&#039;&#039;éééé&quot;&quot;&quot;&quot;\\\\', 'test d&#039;&#039;&#039;&#039;éééé&quot;&quot;&quo', 'test d&#03', 'Studio', 0.00, 1.00, 1.00, 1, 0, NULL, 96, 'Waiting', '2026-01-20', 0, '0000-00-00', NULL),
(112, 'test d&#039;&#039;&#039;&#039;éééé&quot;&quot;&quot;&quot;\\\\', 'test d&#039;&#039;&#039;&#039;éééé&quot;&quot;&quot;&quot;\\\\', 'test d&#039;&#039;&#039;&#039;éééé&quot;&quot;&quot;&quot;\\\\', 'test d&#039;&#039;&#039;&#039;éééé&quot;&quot;&quo', 'test d&#03', 'Studio', 0.00, 1.00, 1.00, 0, 0, NULL, 96, 'Waiting', '2026-01-20', 0, '0000-00-00', NULL),
(114, 'test d&#39;&#39;&#39;&#39;éééé&#34;&#34;&#34;&#34;\\\\\\\\', 'test d&#39;&#39;&#39;&#39;éééé&#34;&#34;&#34;&#34;\\\\\\\\', 'test d&#39;&#39;&#39;&#39;éééé&#34;&#34;&#34;&#34;\\\\\\\\', 'test d&#39;&#39;&#39;&#39;éééé&#34;&#34;&#34;&#34;', 'test d&#39', 'Studio', 0.00, 1.00, 1.00, 0, 0, NULL, 96, 'Waiting', '2026-01-20', 0, '0000-00-00', NULL),
(115, 'test déééé', 'test déééé', 'test déééé', 'test déééé', 'test déééé', 'Studio', 0.00, 1.00, 1.00, 0, 0, NULL, 96, 'Waiting', '2026-01-20', 0, '0000-00-00', NULL),
(117, 'test déééé', 'test déééé', 'test déééé', 'test déééé', 'test déééé', 'Studio', 0.00, 1.00, 1.00, 0, 0, NULL, 96, 'Waiting', '2026-01-20', 0, '0000-00-00', NULL),
(123, 'test pagination', 'u0000', '113 Rue Danton', 'LEVALLOIS PERRET', '92300', 'Studio', 0.00, 15.00, 15.00, 0, 0, NULL, 96, 'Waiting', '2026-01-21', 0, '0000-00-00', NULL),
(124, 'test pagination', 'u0000', '113 Rue Danton', 'LEVALLOIS PERRET', '92300', 'Studio', 0.00, 15.00, 15.00, 0, 0, NULL, 96, 'Waiting', '2026-01-21', 0, '0000-00-00', NULL),
(125, 'test pagination', 'u0000', '113 Rue Danton', 'LEVALLOIS PERRET', '92300', 'Studio', 0.00, 15.00, 15.00, 0, 0, NULL, 96, 'Waiting', '2026-01-21', 0, '0000-00-00', NULL),
(126, 'test pagination', 'u0000', '113 Rue Danton', 'LEVALLOIS PERRET', '92300', 'Studio', 0.00, 15.00, 15.00, 0, 0, NULL, 96, 'Waiting', '2026-01-21', 0, '0000-00-00', NULL),
(127, 'test pagination', 'u0000', '113 Rue Danton', 'LEVALLOIS PERRET', '92300', 'Studio', 0.00, 15.00, 15.00, 0, 0, NULL, 96, 'Waiting', '2026-01-21', 0, '0000-00-00', NULL),
(128, 'test pagination', 'u0000', '113 Rue Danton', 'LEVALLOIS PERRET', '92300', 'Studio', 0.00, 15.00, 15.00, 0, 0, NULL, 96, 'Waiting', '2026-01-21', 0, '0000-00-00', NULL),
(129, 'test pagination', 'u0000', '113 Rue Danton', 'LEVALLOIS PERRET', '92300', 'Studio', 0.00, 15.00, 15.00, 0, 0, NULL, 96, 'Waiting', '2026-01-21', 0, '0000-00-00', NULL),
(130, 'test pagination', 'u0000', '113 Rue Danton', 'LEVALLOIS PERRET', '92300', 'Studio', 0.00, 15.00, 15.00, 0, 0, NULL, 96, 'Waiting', '2026-01-21', 0, '0000-00-00', NULL),
(131, 'test d&#39;&#39;&#39;&#39;éééé', 'test d&#39;&#39;&#39;&#39;éééé', 'test d&#39;&#39;&#39;&#39;éééé', 'test d&#39;&#39;&#39;&#39;éééé', 'test d&#39', 'Studio', 0.00, 1.00, 1.00, 0, 0, NULL, 96, 'Waiting', '2026-01-21', 0, '0000-00-00', NULL);

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
(113, '\"\"\"\"\"\"\"\"\"\"\"\"\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'\'', '2026-01-20 19:26:09', 1, 96, 96, NULL),
(114, 'Bonjour', '2026-01-20 19:26:12', 1, 96, 96, NULL),
(115, 'test', '2026-01-20 20:10:13', 1, 43, 96, NULL);

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
(62, '../uploads/photo_696fb86f136e94.41484255.jpg', '', 106),
(63, '../uploads/photo_696fb86f13e498.16673597.jpg', '', 106),
(64, '../uploads/photo_696fb86f145959.94676263.jpeg', '', 106),
(65, '../uploads/photo_69714c8fa39df7.36304435.jpeg', '', 123),
(66, '../uploads/photo_69714c916057e4.83831316.jpeg', '', 124),
(67, '../uploads/photo_69714c926d0d31.43757300.jpeg', '', 125),
(68, '../uploads/photo_69714c934ea256.39773284.jpeg', '', 126),
(69, '../uploads/photo_69714c941bd739.20965873.jpeg', '', 127),
(70, '../uploads/photo_69714c95eede45.64821082.jpeg', '', 128),
(71, '../uploads/photo_69714c9700fd33.09096981.jpeg', '', 129),
(72, '../uploads/photo_69714c98935fd7.25685895.jpeg', '', 130);

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
(44, '2026-01-20', '2026-02-20', 'Refusée', 500.00, 96, 106),
(45, '2026-01-20', '2026-02-20', 'Refusée', 500.00, 43, 106);

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
  `facile` varchar(255) DEFAULT NULL,
  `email_verifie` tinyint(1) DEFAULT 0,
  `verification_token` varchar(64) DEFAULT NULL,
  `banned` int(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `created_at`, `password`, `nom`, `prenom`, `telephone`, `note`, `date_naissance`, `universite`, `genre`, `type_utilisateur`, `is_admin`, `biography`, `reset_token`, `reset_expires`, `facile`, `email_verifie`, `verification_token`, `banned`) VALUES
(43, 'etudiant@logemangue.fr', '2025-12-21 17:35:29', '$2y$10$zyuY1Det54lpI0G0hgVYkOSGUz9a.A/LFVj3lfDhWVXV.s8JtsalO', 'Etudiant Nom', NULL, '0784462719', NULL, '2025-12-09', NULL, 'Femme', 'Etudiant', NULL, '', '', '0000-00-00 00:00:00.000000', 'locataire.dossierfacile.logement.gouv.fr/public-file/80cb71cb-64d6-4dc9-97d3-f621fc1b3029', 1, NULL, 0),
(44, 'user1@mail.com', '2026-01-12 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom1', 'Prenom1', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(45, 'user2@mail.com', '2026-01-11 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom2', 'Prenom2', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(46, 'user3@mail.com', '2026-01-10 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom3', 'Prenom3', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(47, 'user4@mail.com', '2026-01-09 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom4', 'Prenom4', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(48, 'user5@mail.com', '2026-01-08 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom5', 'Prenom5', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(49, 'user6@mail.com', '2026-01-07 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom6', 'Prenom6', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(50, 'user7@mail.com', '2026-01-06 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom7', 'Prenom7', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(51, 'user8@mail.com', '2026-01-05 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom8', 'Prenom8', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(52, 'user9@mail.com', '2026-01-04 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom9', 'Prenom9', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(53, 'user10@mail.com', '2026-01-03 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom10', 'Prenom10', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(54, 'user11@mail.com', '2026-01-02 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom11', 'Prenom11', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(55, 'user12@mail.com', '2026-01-01 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom12', 'Prenom12', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(56, 'user13@mail.com', '2025-12-31 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom13', 'Prenom13', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(57, 'user14@mail.com', '2025-12-30 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom14', 'Prenom14', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(58, 'user15@mail.com', '2025-12-29 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom15', 'Prenom15', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(59, 'user16@mail.com', '2025-12-28 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom16', 'Prenom16', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(60, 'user17@mail.com', '2025-12-27 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom17', 'Prenom17', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(61, 'user18@mail.com', '2025-12-26 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom18', 'Prenom18', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(62, 'user19@mail.com', '2025-12-25 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom19', 'Prenom19', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(63, 'user20@mail.com', '2025-12-24 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom20', 'Prenom20', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(64, 'user21@mail.com', '2025-12-23 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom21', 'Prenom21', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(65, 'user22@mail.com', '2025-12-22 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom22', 'Prenom22', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(66, 'user23@mail.com', '2025-12-21 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom23', 'Prenom23', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(67, 'user24@mail.com', '2025-12-20 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom24', 'Prenom24', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(68, 'user25@mail.com', '2025-12-19 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom25', 'Prenom25', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(69, 'user26@mail.com', '2025-12-18 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom26', 'Prenom26', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(70, 'user27@mail.com', '2025-12-17 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom27', 'Prenom27', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(71, 'user28@mail.com', '2025-12-16 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom28', 'Prenom28', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(72, 'user29@mail.com', '2025-12-15 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom29', 'Prenom29', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(73, 'user30@mail.com', '2026-01-13 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom30', 'Prenom30', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(74, 'user31@mail.com', '2026-01-12 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom31', 'Prenom31', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(75, 'user32@mail.com', '2026-01-11 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom32', 'Prenom32', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(76, 'user33@mail.com', '2026-01-10 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom33', 'Prenom33', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(77, 'user34@mail.com', '2026-01-09 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom34', 'Prenom34', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(78, 'user35@mail.com', '2026-01-08 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom35', 'Prenom35', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(79, 'user36@mail.com', '2026-01-07 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom36', 'Prenom36', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(80, 'user37@mail.com', '2026-01-06 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom37', 'Prenom37', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(81, 'user38@mail.com', '2026-01-05 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom38', 'Prenom38', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(82, 'user39@mail.com', '2026-01-04 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom39', 'Prenom39', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(83, 'user40@mail.com', '2026-01-03 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom40', 'Prenom40', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(84, 'user41@mail.com', '2026-01-02 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom41', 'Prenom41', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(85, 'user42@mail.com', '2026-01-01 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom42', 'Prenom42', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(86, 'user43@mail.com', '2025-12-31 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom43', 'Prenom43', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(87, 'user44@mail.com', '2025-12-30 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom44', 'Prenom44', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(88, 'user45@mail.com', '2025-12-29 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom45', 'Prenom45', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(89, 'user46@mail.com', '2025-12-28 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom46', 'Prenom46', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(90, 'user47@mail.com', '2025-12-27 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom47', 'Prenom47', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(91, 'user48@mail.com', '2025-12-26 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom48', 'Prenom48', NULL, NULL, NULL, NULL, NULL, 'Etudiant', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(92, 'user49@mail.com', '2025-12-25 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom49', 'Prenom49', NULL, NULL, NULL, NULL, NULL, 'Proprietaire', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(93, 'user50@mail.com', '2025-12-24 21:15:53', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8x6zZ7JzH2d7d7Hq4yQ0L6Jj6N8eW2', 'Nom50', 'Prenom50', NULL, NULL, NULL, NULL, NULL, 'Organisme', 0, NULL, '', '0000-00-00 00:00:00.000000', NULL, 0, NULL, 0),
(96, 'mc.carruette@gmail.com', '2026-01-20 17:12:01', '$2y$10$vSqjtjXe86S4RXpQB1g96.xbr1uTHVlk.06puFuA.GvQeGyx62KAS', 'CARRUETTE Mathis', NULL, '0784462719', NULL, '2005-11-10', NULL, 'Homme', 'Proprietaire', 1, '', '', '0000-00-00 00:00:00.000000', 'locataire.dossierfacile.logement.gouv.fr/public-file/80cb71cb-64d6-4dc9-97d3-f621fc1b3029', 1, NULL, 0);

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
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_statut` (`statut`),
  ADD KEY `idx_date_creation` (`date_creation`),
  ADD KEY `idx_email` (`email`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id_faq`);

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
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `faq`
--
ALTER TABLE `faq`
  MODIFY `id_faq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `logement`
--
ALTER TABLE `logement`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

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
-- Contraintes pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
