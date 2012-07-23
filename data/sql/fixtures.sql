SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Contenu de la table `City`
--

INSERT INTO `City` (`name`) VALUES
('Bordeaux'),
('Lyon'),
('Nantes'),
('Orléans'),
('Paris'),
('Toulouse');

--
-- Contenu de la table `Member` (mdp is "password")
--

INSERT INTO `Member` (`id`, `username`, `password`, `active`) VALUES
(1, 'user', '1d85bd100e0dd11b20f67a5834c8c2d67e7d9720', true),
(2, 'user2', '1d85bd100e0dd11b20f67a5834c8c2d67e7d9720', true),
(3, 'inactive_user', '1d85bd100e0dd11b20f67a5834c8c2d67e7d9720', false);

--
-- Contenu de la table `User`
--

INSERT INTO `User` (`id`, `lastname`, `firstname`, `email`, `token`, `member_id`) VALUES
(1, 'Example1', 'User1', 'user1@example.org', 'token', null),
(2, 'Example2', 'User2', 'user2@example.org', 'token', null),
(3, 'Example3', 'User3', 'user3@example.org', 'token', 1);

--
-- Contenu de la table `Drink`
--

INSERT INTO `Drink` (`id`, `place`, `address`, `day`, `hour`, `kind`, `description`, `member_id`, `city_id`, `latitude`, `longitude`) VALUES
(1, 'Au père tranquille', '16 rue Pierre Lescot, Paris, France', '2016-07-19', '19:30:00', 'drink', 'Apéro PHP de test au père tranquille', 1, 5, '48.86214', '2.34843'),
(2, 'Au père tranquille', '16 rue Pierre Lescot, Paris, France', '2010-07-19', '19:30:00', 'drink', 'Apéro déjà passé.', 1, 5, '48.86214', '2.34843');

--
-- Contenu de la table `Drink_Participation`
--

INSERT INTO `Drink_Participation` (`drink_id`, `user_id`, `percentage`, `reminder`) VALUES
(1, 1, 75, 1),
(1, 3, 75, 1);

--
-- Contenu de la table `Drink_Comment`
--

INSERT INTO `Drink_Comment` (`id`, `created_at`, `content`, `drink_id`, `user_id`) VALUES
(1, '2012-07-03 21:56:06', 'c\'est génial !', 1, 2),
(2, '2012-07-03 21:57:17', 'Je suis bien d\'accord.', 1, 3);
