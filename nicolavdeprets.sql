-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : nicolavdeprets.mysql.db
-- Généré le : ven. 25 avr. 2025 à 11:33
-- Version du serveur : 8.0.41-32
-- Version de PHP : 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `nicolavdeprets`
--

-- --------------------------------------------------------

--
-- Structure de la table `archive_ticket`
--

CREATE TABLE `archive_ticket` (
  `id` int NOT NULL,
  `creator` int NOT NULL,
  `ticket_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `closed_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `archive_ticket`
--

INSERT INTO `archive_ticket` (`id`, `creator`, `ticket_name`, `created_at`, `is_closed`, `closed_by`, `updated_at`, `message`) VALUES
(28, 24, '123456', '2025-04-21 23:30:53', 0, 24, '2025-04-24 22:23:03', 'dazdazd'),
(32, 29, 'rr', '2025-04-23 11:21:40', 0, 29, '2025-04-24 22:23:06', 'rrr'),
(33, 28, 'tg', '2025-04-23 19:35:30', 0, 28, '2025-04-24 22:23:10', 'tg'),
(35, 24, 'rfff', '2025-04-23 20:05:18', 0, 24, '2025-04-24 22:23:12', 'ffzef'),
(36, 24, 'rr', '2025-04-23 20:19:41', 0, 24, '2025-04-24 22:23:15', 'rr'),
(37, 24, 'test des archive', '2025-04-24 08:27:10', 0, 24, '2025-04-24 22:23:17', 'zefudgzeuyfguyze'),
(38, 24, 'test tchat archive', '2025-04-24 08:32:27', 0, 24, '2025-04-24 22:23:20', 'zefzef');

-- --------------------------------------------------------

--
-- Structure de la table `patch_note`
--

CREATE TABLE `patch_note` (
  `id` int NOT NULL,
  `up_vote` int DEFAULT '0',
  `down_vote` int DEFAULT '0',
  `message` text NOT NULL,
  `posted_by` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `posted_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_perm` int NOT NULL,
  `can_reply_ticket` tinyint(1) DEFAULT '0',
  `can_ban_permanently` tinyint(1) DEFAULT '0',
  `can_ban_temporarily` tinyint(1) DEFAULT '0',
  `can_post_patchnotes` tinyint(1) DEFAULT '0',
  `can_manage_users` tinyint(1) DEFAULT '0',
  `can_view_reports` tinyint(1) DEFAULT '0',
  `can_edit_roles` tinyint(1) DEFAULT '0',
  `can_delete_tickets` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`nom`, `id_perm`, `can_reply_ticket`, `can_ban_permanently`, `can_ban_temporarily`, `can_post_patchnotes`, `can_manage_users`, `can_view_reports`, `can_edit_roles`, `can_delete_tickets`) VALUES
('Useur', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('Guide', 2, 0, 0, 1, 0, 0, 1, 0, 0),
('Modo', 3, 0, 1, 1, 0, 1, 1, 0, 1),
('Dev', 4, 0, 0, 1, 1, 0, 1, 0, 0),
('Admin', 5, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_tag` varchar(255) NOT NULL,
  `status` varchar(1) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `autorisation` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `name`, `image_tag`, `status`, `is_active`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`, `autorisation`) VALUES
(1, 'Utilisateur', 'images_statut/utilisateur.png', 'a', 1, '2025-03-31 08:17:44', '2025-04-24 20:08:52', 24, 25, NULL, 1),
(2, 'Admin', 'images_statut/admin.png', 'a', 1, '2025-03-31 08:17:44', '2025-04-24 20:08:10', 24, 24, NULL, 5),
(3, 'Dev', 'images_statut/dev.png', 'a', 1, '2025-03-31 08:17:44', '2025-04-24 20:08:20', 24, 25, NULL, 4),
(4, 'Modo', 'images_statut/modo.png', 'a', 1, '2025-03-31 08:17:44', '2025-04-24 20:08:49', 24, 25, NULL, 3),
(5, 'Guide', 'images_statut/guide.png', 'a', 1, '2025-03-31 08:17:44', '2025-04-24 20:08:24', 24, 25, NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `ticket`
--

CREATE TABLE `ticket` (
  `id` int NOT NULL,
  `creator` int NOT NULL,
  `ticket_name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_closed` tinyint(1) DEFAULT '0',
  `closed_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_close` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ticket`
--

INSERT INTO `ticket` (`id`, `creator`, `ticket_name`, `message`, `created_at`, `is_closed`, `closed_by`, `updated_at`, `is_close`) VALUES
(29, 24, 'Support test', 'Principes factitarunt et accendente hoc de adulatorum progressu principes exitiale oblato poenae vel illo fertur eius obstinatum effervescebat fertur illo accendente non quod eius intepescit elogio exitiale fertur aetatis aliis non more accendente numquam intepescit et similia accendente et progressu illo quod factitarunt obstinatum hoc more haec aliquando in addictum revocari in more in numquam oblato in haec quod obstinatum similia non factitarunt illo neminem eius aetatis iussisse aliis quod obstinatum numquam propositum oblato in et hoc aetatis non quod illo revocari quod cohorte aetatis illo aliquando poenae quod progressu adulatorum propositum haec hoc fertur adulatorum quoque iussisse exitiale factitarunt.', '2025-04-22 08:21:29', 0, NULL, '2025-04-25 08:10:00', 0),
(30, 24, 't', 'zfe', '2025-04-22 08:43:43', 0, NULL, '2025-04-25 08:10:04', 0),
(31, 25, 'g', 'q', '2025-04-22 18:49:15', 0, NULL, '2025-04-22 18:49:15', 0),
(34, 28, 'feur', 'feur', '2025-04-23 17:49:12', 0, NULL, '2025-04-23 17:49:12', 0);

-- --------------------------------------------------------

--
-- Structure de la table `ticket_message`
--

CREATE TABLE `ticket_message` (
  `id` int NOT NULL,
  `ticket_id` int NOT NULL,
  `creator` int NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `published_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ticket_message`
--

INSERT INTO `ticket_message` (`id`, `ticket_id`, `creator`, `message`, `created_at`, `published_at`) VALUES
(47, 34, 28, 'test', '2025-04-23 17:55:01', '2025-04-23 17:55:01'),
(48, 29, 24, 'test 1 2 3 4 5 6 7 8 9 10', '2025-04-23 17:55:58', '2025-04-23 17:55:58'),
(49, 34, 28, 'fe', '2025-04-23 17:57:23', '2025-04-23 17:57:23'),
(50, 34, 28, 'fea', '2025-04-23 17:57:25', '2025-04-23 17:57:25'),
(51, 29, 24, 't', '2025-04-23 17:57:27', '2025-04-23 17:57:27'),
(52, 34, 28, 'gea', '2025-04-23 17:57:31', '2025-04-23 17:57:31'),
(55, 29, 24, 'gfdtr', '2025-04-24 07:26:16', '2025-04-24 07:26:16');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_perm` int DEFAULT '1',
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `ticket_count` int DEFAULT '0',
  `open_ticket_count` int DEFAULT '0',
  `closed_ticket_count` int DEFAULT '0',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_perm`, `id`, `name`, `email`, `phone`, `password`, `ticket_count`, `open_ticket_count`, `closed_ticket_count`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 19, 'CHRISTELLE VAN DAELE', 'christelle308@hotmail.com', '0497742173', '$2y$12$lF1r9WYePosh.GxHKobQxu0U5Odpb9oKaL8X5.H4p5VyUgUHRiVqu', 0, 0, 0, 'uploads/60584886_413741469181334_8674626768641982464_n.jpg', '2025-04-04 10:46:33', '2025-04-25 09:27:33'),
(1, 23, 'Jennifer', 'jenniraj18@hotmail.com', '0466384595', '$2y$12$YUFYaux2.1ez8yZvTrBTZul4YBQ0V0YdZTRuIwA5yx7/L0pWfFLDm', 0, 0, 0, 'uploads/11pmpm.jpg', '2025-04-18 06:58:48', '2025-04-18 06:58:48'),
(2, 24, 'Nico7600', 'depretsnico@gmail.com', '0987654321', '$2y$12$iyPUsuAGoUGBBWegP/pRCeGjqs5DozJOO.icGCYw1lTKTz8Zy09T6', 7, 2, 5, 'uploads/Mao mao.jpeg', '2025-04-18 11:57:43', '2025-04-24 06:32:49'),
(2, 25, 'Shiro', 'shannimoulin@gmail.com', '0497742173', '$2y$12$s7tg9cHl7Ry31ZDeCdzGhe38mIIkh6ocUwoXb28RJOPg1LQ/3EHUe', 1, 1, 0, 'uploads/téléchargé (5).jpg', '2025-04-18 12:19:14', '2025-04-22 18:49:15'),
(2, 28, 'Aurelien', 'pasmonemail@perso.com', '0987654321', '$2y$12$gpNqFY7swKUghrjH/AkHwe0u77rectzZgma/26IJUuOL00SNwCUbW', 2, 2, 0, 'uploads/default.png', '2025-04-18 13:25:48', '2025-04-24 12:38:22'),
(5, 29, 'test', 'test@test.test', '0123456789', '$2y$12$Gb.IS/fqo3vJ6TIFkc1fbe42NSVW2dqCsDDOHvWVKnejiYXZTNgYC', 1, 0, 1, 'uploads/Capture d\'écran 2025-04-06 172456.png', '2025-04-23 09:21:00', '2025-04-24 11:49:52');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `archive_ticket`
--
ALTER TABLE `archive_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `patch_note`
--
ALTER TABLE `patch_note`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id_perm`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_role_autorisation` (`autorisation`);

--
-- Index pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator` (`creator`),
  ADD KEY `closed_by` (`closed_by`);

--
-- Index pour la table `ticket_message`
--
ALTER TABLE `ticket_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `creator` (`creator`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_id_perm` (`id_perm`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `archive_ticket`
--
ALTER TABLE `archive_ticket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `patch_note`
--
ALTER TABLE `patch_note`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id_perm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `ticket_message`
--
ALTER TABLE `ticket_message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `fk_role_autorisation` FOREIGN KEY (`autorisation`) REFERENCES `permissions` (`id_perm`);

--
-- Contraintes pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`closed_by`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `ticket_message`
--
ALTER TABLE `ticket_message`
  ADD CONSTRAINT `ticket_message_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_message_ibfk_2` FOREIGN KEY (`creator`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_id_perm` FOREIGN KEY (`id_perm`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
