-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 12 oct. 2023 à 10:01
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `wordpress`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id_article` int NOT NULL AUTO_INCREMENT,
  `titre_article` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image_article` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contenu_article` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_article` date NOT NULL,
  `categorie` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `statut` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_user` int DEFAULT NULL,
  PRIMARY KEY (`id_article`),
  UNIQUE KEY `id_article` (`id_article`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id_article`, `titre_article`, `image_article`, `contenu_article`, `date_article`, `categorie`, `statut`, `id_user`) VALUES
(1, 'Le Mystère de la Guérison Instantanée à la Clinique Miracle', 'genetique.png', 'À la Clinique Miracle, un établissement de santé réputé pour ses traitements innovants, un phénomène médical mystérieux a émergé. Plusieurs patients atteints de maladies graves ont connu une guérison quasi instantanée après avoir été admis pour des traite', '2023-09-15', 'Santé', 'Brouillon', 0),
(2, 'L\'Évasion Spectaculaire du Lama Zorro', 'lamas.png', 'Dans un zoo exotique situé au cœur de la vallée de la Drôme, une évasion inhabituelle a récemment eu lieu. Un lama nommé Zorro, connu pour sa personnalité espiègle et son habileté à déjouer les attentes, a orchestré une évasion spectaculaire qui a stupéfi', '2023-10-10', 'Animaux', 'Brouillon', 0),
(3, 'Le Mystère de la Disparition des Chaussettes à Château-chaussettes', 'ducktales.jpg', 'Dans la petite ville pittoresque de Château-chaussettes, un phénomène étrange et inexplicable a laissé les habitants perplexes. Depuis plusieurs semaines, les chaussettes disparaissent mystérieusement des maisons et des buanderies locales. Ce n\'est pas se', '2023-09-01', 'FaitsDivers', 'Publié', 0),
(4, 'Révolution Technologique : Des Robots-Éboueurs Domestiques pour un Monde Plus Propre', 'computer.png', 'Dans une annonce surprenante, une société technologique fictive du nom de \"RoboClean\" prétend avoir révolutionné le secteur de la robotique avec le lancement de ses robots-éboueurs domestiques. Ces robots, appelés \"CleanBots\", seraient capables de résoudr', '2023-08-30', 'Informatique', 'Brouillon', 0),
(5, 'Le Vol Étonnant des Panneaux de Stop à Puzzleville', 'foret.png', 'Dans la petite ville de Puzzleville, une série de vols inhabituels a laissé les habitants perplexes. Les panneaux de stop, éléments essentiels de la sécurité routière, ont mystérieusement disparu des intersections principales de la ville. Au lieu de stopp', '2023-07-12', 'FaitsDivers', 'Relecture', 0),
(6, 'Le Mystère de la Bourgade : À la Poursuite du Tueur en Série', 'doctor.png', 'Au cœur d\'une paisible bourgade du nom de Clairval, un sombre et terrifiant mystère a récemment ébranlé la communauté. Depuis plusieurs mois, la ville est le théâtre d\'une série de meurtres brutaux, tous commis de manière méthodique et apparemment aléatoi', '2023-09-25', 'FaitsDivers', 'Publié', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id_page` int NOT NULL AUTO_INCREMENT,
  `titre_page` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image_page` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contenu_page` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_page` date NOT NULL,
  `statut_page` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_page`),
  UNIQUE KEY `id_page` (`id_page`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nom_user` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom_user` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `mail_user` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `pseudo_user` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password_user` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `avatar_user` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `compte_user` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `nom_user`, `prenom_user`, `mail_user`, `pseudo_user`, `password_user`, `avatar_user`, `compte_user`) VALUES
(1, 'Zammit', 'Laure', 'laure@mail.com', 'Loly', '$2y$10$2N2T7tnPa0bqR.KdBXxHruVeIxuiMicwkTdS.rnlXuW5QpN0CTIqu', 'little_me.jpg', 'admin'),
(2, 'Sigaud', 'Manon', 'manon@mail.com', 'Toto', '$2y$10$J9sfs9Znhg12OlqAqxsdbuYT2TLDRYH7ok6/5cLNCzi0.R8ZEfTnu', 'visuel_Leviator.jpg', 'membre'),
(3, 'Puggioni', 'Anthony', 'antho@mail.com', 'antho', '$2y$10$mSWXjPlHB5JSgLtPREFDi.tj8sXkWTy/P1wYVcsVNMyiwJF76X/7a', '172059_v9_ba.jpg', 'moderateur'),
(4, 'Chainey', 'Remi', 'remi@mail.com', 'Abel', '$2y$10$U23UUw82XkCITM40V7Dnx.D0bhREx60fmaTTjdEfT591aV1m3xr/a', 'visuel_Salameche.jpg', 'membre'),
(5, 'Poitrot', 'Kloe', 'kloe@mail.com', 'kloe', '$2y$10$WTYgbbDzBae/sksAcb0D8O8aLR8mLnFGlRGJpj.7CNninWcbnVRiy', 'visuel_Pikachu.jpg', 'membre'),
(6, 'Errante', 'Jean-Louis', 'jle@mail.com', 'jle', '$2y$10$jIAqR/u8WX.CHzgKVGsQLOl52Xwyh2yErk2iBHDsZcq.EkD2Qw6R.', 'JL.png', 'membre');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
