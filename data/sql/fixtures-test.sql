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
-- Contenu de la table `Member`
--

INSERT INTO `Member` (`id`, `username`, `password`) VALUES
(1, 'user', '65e774516849ac5d28cb6a8088c6f441b694ff2e');

--
-- Contenu de la table `User`
--

INSERT INTO `User` (`id`, `lastname`, `firstname`, `email`, `member_id`) VALUES
(1, 'Example1', 'User1', 'user1@example.com', null),
(2, 'Example2', 'User2', 'user2@example.com', null),
(3, 'Example3', 'User3', 'user3@example.com', 1);

--
-- Contenu de la table `Drink`
--

INSERT INTO `Drink` (`id`, `place`, `address`, `day`, `hour`, `kind`, `description`, `user_id`, `city_id`, `latitude`, `longitude`) VALUES
(1, 'Au père tranquille', '16 rue Pierre Lescot, Paris, France', '2012-07-19', '19:30:00', 'drink', 'Apéro PHP de test au père tranquille', 3, 5, '48.86214', '2.34843');

--
-- Contenu de la table `Drink_Participation`
--

INSERT INTO `Drink_Participation` (`drink_id`, `user_id`, `percentage`, `reminder`) VALUES
(1, 1, 75, 1);
