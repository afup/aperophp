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

INSERT INTO `User` (`lastname`, `firstname`, `email`, `member_id`) VALUES
('Example', 'User', 'user@example.com', 1);
