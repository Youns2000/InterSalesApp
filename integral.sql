-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  ven. 30 oct. 2020 à 22:19
-- Version du serveur :  8.0.18
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `integral`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NomSociete` varchar(255) NOT NULL,
  `CodeClient` varchar(255) NOT NULL,
  `NIF` varchar(255) NOT NULL,
  `commercial` varchar(255) NOT NULL,
  `Activite1` text NOT NULL,
  `Activite2` text NOT NULL,
  `Adresse` text NOT NULL,
  `CodePostal` int(11) NOT NULL,
  `Ville` varchar(255) NOT NULL,
  `Wilaya` varchar(255) NOT NULL,
  `Pays` varchar(255) NOT NULL,
  `TelFixe` varchar(255) NOT NULL,
  `NomResp1` varchar(255) NOT NULL,
  `EmailResp1` varchar(255) NOT NULL,
  `PortableResp1` varchar(255) NOT NULL,
  `NomResp2` varchar(255) NOT NULL,
  `EmailResp2` varchar(255) NOT NULL,
  `PortableResp2` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `NomSociete`, `CodeClient`, `NIF`, `commercial`, `Activite1`, `Activite2`, `Adresse`, `CodePostal`, `Ville`, `Wilaya`, `Pays`, `TelFixe`, `NomResp1`, `EmailResp1`, `PortableResp1`, `NomResp2`, `EmailResp2`, `PortableResp2`) VALUES
(1, 'INTERMAT', 'CG97A', '', '', '', '', '3 CHEMIN DES REMISES', 60410, 'VERBERIE', 'Guelma', 'ALGERIE', '', '', '', '', '', '', ''),
(4, 'Youn\'s', 'TE25E', '', '', 'Develeppement', '', '31 domaine des roches', 91140, 'Villebon', 'Oran', 'Algérie', '0160109660', 'Benreguieg', 'younes.benreg@gmail.com', '0665580165', '', '', ''),
(6, 'Integral', 'AR14F', '4h64fghr1tb785', '', 'fournisseur', '', '31 domaine des roches', 91140, 'oran', 'oran', 'Algérie', '065554584', 'haddad', 'azeddine@integral.fr', '', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `comptes`
--

DROP TABLE IF EXISTS `comptes`;
CREATE TABLE IF NOT EXISTS `comptes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Statut` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Secteur` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comptes`
--

INSERT INTO `comptes` (`id`, `Statut`, `nom`, `prenom`, `mobile`, `email`, `Secteur`, `mdp`) VALUES
(1, 'admin', 'HADDAD', 'Azeddine', '', 'Azeddine@integral.fr', '', '$2y$10$wj6ucbeYkYrsleGTYeb3W.A1vLR2l6bo8zBnbnmd4iMCYS4JhdUxa'),
(2, 'user', 'GHEZALI', 'Ali', '', 'Ali@integral.fr', '', '$2y$10$mG3xFAzc/WY0SiPbYrcs1.ow3OqyqXqa5IrLJ2zghRqVCAe4240mO'),
(3, 'user', 'ATTAFI', 'Zitouni', '', 'Zitouni@integral.fr', '', '$2y$10$mG3xFAzc/WY0SiPbYrcs1.ow3OqyqXqa5IrLJ2zghRqVCAe4240mO'),
(4, 'user', 'SABER', 'Moumen', '', 'Moumen@integral.fr', '', '$2y$10$mG3xFAzc/WY0SiPbYrcs1.ow3OqyqXqa5IrLJ2zghRqVCAe4240mO'),
(5, 'user', 'MSERHED', 'Houcine', '', 'Houcine@integral.fr', '', '$2y$10$mG3xFAzc/WY0SiPbYrcs1.ow3OqyqXqa5IrLJ2zghRqVCAe4240mO'),
(6, 'user', 'BEN YAHIA', 'Diaa', '', 'Diaa@integral.fr', '', '$2y$10$mG3xFAzc/WY0SiPbYrcs1.ow3OqyqXqa5IrLJ2zghRqVCAe4240mO'),
(7, 'user', 'MAHORBACHA', 'Abdelkrim', '', 'Abdelkrim@integral.fr', '', '$2y$10$mG3xFAzc/WY0SiPbYrcs1.ow3OqyqXqa5IrLJ2zghRqVCAe4240mO'),
(8, 'user', 'DJALAB', 'Rachid', '', 'Rachid@integral.fr', '', '$2y$10$mG3xFAzc/WY0SiPbYrcs1.ow3OqyqXqa5IrLJ2zghRqVCAe4240mO');

-- --------------------------------------------------------

--
-- Structure de la table `engins`
--

DROP TABLE IF EXISTS `engins`;
CREATE TABLE IF NOT EXISTS `engins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Categorie` varchar(255) DEFAULT NULL,
  `Marque` varchar(255) DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `Ref` varchar(255) NOT NULL,
  `Prix` decimal(65,2) NOT NULL,
  `prix_transport` decimal(65,2) NOT NULL,
  `Origine` varchar(255) NOT NULL,
  `Numero_serie` int(11) DEFAULT NULL,
  `Annee_Fabrication` int(11) DEFAULT NULL,
  `Type_Moteur` varchar(255) DEFAULT NULL,
  `Numero_Serie_Moteur` int(11) DEFAULT NULL,
  `ConfBase` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `engins`
--

INSERT INTO `engins` (`id`, `Categorie`, `Marque`, `Type`, `Ref`, `Prix`, `prix_transport`, `Origine`, `Numero_serie`, `Annee_Fabrication`, `Type_Moteur`, `Numero_Serie_Moteur`, `ConfBase`) VALUES
(1, 'Postes Premium', 'Easy', 'Batch90', '', '0.00', '0.00', '', 0, 0, '', 0, ''),
(2, 'Postes Premium', 'Easy', 'Batch140', '', '0.00', '0.00', '', 0, 0, '', 0, ''),
(3, 'Postes Premium', 'Prime', '100', 'AC140', '5000.00', '1200.00', '1', 0, 0, '', 0, 'Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de'),
(4, 'Postes Premium', 'Prime', '140', '', '0.00', '0.00', '', 0, 0, '', 0, ''),
(5, 'Postes Premium', 'Prime', '200', '', '0.00', '0.00', '', 0, 0, '', 0, ''),
(6, 'Postes Mid Market', 'Apollo', '90 TPH', '', '0.00', '0.00', '', 0, 0, '0', 0, ''),
(7, 'Postes Mid Market', 'Apollo', '120 TPH', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(8, 'Postes Mid Market', 'Apollo', '180 TPH', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(9, 'Compacteurs', 'AMMANN', 'AP 240', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(10, 'Compacteurs', 'AMMANN', 'ART 280', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(11, 'Compacteurs', 'AMMANN', 'ARX 95', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(12, 'Compacteurs', 'AMMANN', 'ARX 45K', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(13, 'Compacteurs', 'AMMANN', 'AV 110X', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(14, 'Compacteurs', 'AMMANN', 'ASC 100', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(15, 'Compacteurs', 'AMMANN', 'ASC 150D', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(16, 'Compacteurs', 'AMMANN', 'ARS 200', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(17, 'Finisseurs', 'AMMANN', 'AFT300', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(18, 'Finisseurs', 'AMMANN', 'AFT400-2', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(19, 'Finisseurs', 'AMMANN', 'AFT700-2', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(20, 'Finisseurs', 'AMMANN', 'AFT700-3', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(21, 'Finisseurs', 'AMMANN', 'AFT800-3', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(22, 'Minipelles', 'AMX', '10ZT', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(23, 'Minipelles', 'AMX', '18ZT', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(24, 'Minipelles', 'AMX', '25ZT', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL),
(25, 'Minipelles', 'AMX', '85ZT', '', '0.00', '0.00', '', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Engin` int(11) NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Prix` decimal(65,2) NOT NULL,
  `prix_transport` int(11) NOT NULL,
  `Origine` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `options`
--

INSERT INTO `options` (`id`, `Engin`, `Nom`, `Prix`, `prix_transport`, `Origine`) VALUES
(1, 3, 'Silo de Stockage d\'enrobée de 45 Tonnes', '300.00', 15, '2'),
(2, 3, 'Silo de Filer de récupération', '200.00', 80, '25'),
(3, 3, 'Silo de Filer d\'apport', '500.50', 50, '203'),
(4, 3, 'Citerne de Bitume de 60 000 Litres', '500.00', 100, '46'),
(5, 3, 'Citerne de Bitume de 30 000 Litres', '300.00', 40, ''),
(6, 3, 'Prédoseur Supplémentaire', '100.00', 20, '');

-- --------------------------------------------------------

--
-- Structure de la table `others`
--

DROP TABLE IF EXISTS `others`;
CREATE TABLE IF NOT EXISTS `others` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MoyensDePaiement` varchar(255) NOT NULL,
  `Devises` varchar(255) NOT NULL,
  `Activités` varchar(255) NOT NULL,
  `CatégoriesProduits` varchar(255) NOT NULL,
  `Ports` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `others`
--

INSERT INTO `others` (`id`, `MoyensDePaiement`, `Devises`, `Activités`, `CatégoriesProduits`, `Ports`) VALUES
(1, 'Lettre de Crédit', 'EURO', 'Exploitation Carrière', 'Postes Premium', 'Alger'),
(2, 'Remise Documentaire', 'GBP', 'Travaux Public', 'Postes Mid Market', 'Oran'),
(3, 'Transfert Bancaire Libre', 'USD', 'Batiment', 'Compacteurs', 'Mostaganem'),
(4, 'Cash à l\'enlèvement', 'DA', 'Briquetterie', 'Finisseurs', 'Skikda'),
(5, 'Chèque', '', 'Cimenterie', 'Minipelles', 'Annaba'),
(6, '', '', 'Réparateur', 'Concassage Mobile', 'Djendjen'),
(7, '', '', 'Négociant', 'Concassage Premium', ''),
(8, '', '', 'Location', 'Concassage Mid Market', ''),
(9, '', '', 'Canalisations', 'Entretien des Routes', ''),
(10, '', '', 'Hydraulique', 'Centrales à Béton', ''),
(11, '', '', 'Centrale Béton', 'Lavage', ''),
(12, '', '', 'Faïencerie', 'Pièces de rechange', ''),
(13, '', '', '', 'Opportunités', '');

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

DROP TABLE IF EXISTS `pays`;
CREATE TABLE IF NOT EXISTS `pays` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `alpha2` varchar(2) NOT NULL,
  `alpha3` varchar(3) NOT NULL,
  `nom_en_gb` varchar(45) NOT NULL,
  `nom_fr_fr` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha2` (`alpha2`),
  UNIQUE KEY `alpha3` (`alpha3`),
  UNIQUE KEY `code_unique` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=242 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pays`
--

INSERT INTO `pays` (`id`, `code`, `alpha2`, `alpha3`, `nom_en_gb`, `nom_fr_fr`) VALUES
(1, 4, 'AF', 'AFG', 'Afghanistan', 'Afghanistan'),
(2, 8, 'AL', 'ALB', 'Albania', 'Albanie'),
(3, 10, 'AQ', 'ATA', 'Antarctica', 'Antarctique'),
(4, 12, 'DZ', 'DZA', 'Algeria', 'Algérie'),
(5, 16, 'AS', 'ASM', 'American Samoa', 'Samoa Américaines'),
(6, 20, 'AD', 'AND', 'Andorra', 'Andorre'),
(7, 24, 'AO', 'AGO', 'Angola', 'Angola'),
(8, 28, 'AG', 'ATG', 'Antigua and Barbuda', 'Antigua-et-Barbuda'),
(9, 31, 'AZ', 'AZE', 'Azerbaijan', 'Azerbaïdjan'),
(10, 32, 'AR', 'ARG', 'Argentina', 'Argentine'),
(11, 36, 'AU', 'AUS', 'Australia', 'Australie'),
(12, 40, 'AT', 'AUT', 'Austria', 'Autriche'),
(13, 44, 'BS', 'BHS', 'Bahamas', 'Bahamas'),
(14, 48, 'BH', 'BHR', 'Bahrain', 'Bahreïn'),
(15, 50, 'BD', 'BGD', 'Bangladesh', 'Bangladesh'),
(16, 51, 'AM', 'ARM', 'Armenia', 'Arménie'),
(17, 52, 'BB', 'BRB', 'Barbados', 'Barbade'),
(18, 56, 'BE', 'BEL', 'Belgium', 'Belgique'),
(19, 60, 'BM', 'BMU', 'Bermuda', 'Bermudes'),
(20, 64, 'BT', 'BTN', 'Bhutan', 'Bhoutan'),
(21, 68, 'BO', 'BOL', 'Bolivia', 'Bolivie'),
(22, 70, 'BA', 'BIH', 'Bosnia and Herzegovina', 'Bosnie-Herzégovine'),
(23, 72, 'BW', 'BWA', 'Botswana', 'Botswana'),
(24, 74, 'BV', 'BVT', 'Bouvet Island', 'Île Bouvet'),
(25, 76, 'BR', 'BRA', 'Brazil', 'Brésil'),
(26, 84, 'BZ', 'BLZ', 'Belize', 'Belize'),
(27, 86, 'IO', 'IOT', 'British Indian Ocean Territory', 'Territoire Britannique de l\'Océan Indien'),
(28, 90, 'SB', 'SLB', 'Solomon Islands', 'Îles Salomon'),
(29, 92, 'VG', 'VGB', 'British Virgin Islands', 'Îles Vierges Britanniques'),
(30, 96, 'BN', 'BRN', 'Brunei Darussalam', 'Brunéi Darussalam'),
(31, 100, 'BG', 'BGR', 'Bulgaria', 'Bulgarie'),
(32, 104, 'MM', 'MMR', 'Myanmar', 'Myanmar'),
(33, 108, 'BI', 'BDI', 'Burundi', 'Burundi'),
(34, 112, 'BY', 'BLR', 'Belarus', 'Bélarus'),
(35, 116, 'KH', 'KHM', 'Cambodia', 'Cambodge'),
(36, 120, 'CM', 'CMR', 'Cameroon', 'Cameroun'),
(37, 124, 'CA', 'CAN', 'Canada', 'Canada'),
(38, 132, 'CV', 'CPV', 'Cape Verde', 'Cap-vert'),
(39, 136, 'KY', 'CYM', 'Cayman Islands', 'Îles Caïmanes'),
(40, 140, 'CF', 'CAF', 'Central African', 'République Centrafricaine'),
(41, 144, 'LK', 'LKA', 'Sri Lanka', 'Sri Lanka'),
(42, 148, 'TD', 'TCD', 'Chad', 'Tchad'),
(43, 152, 'CL', 'CHL', 'Chile', 'Chili'),
(44, 156, 'CN', 'CHN', 'China', 'Chine'),
(45, 158, 'TW', 'TWN', 'Taiwan', 'Taïwan'),
(46, 162, 'CX', 'CXR', 'Christmas Island', 'Île Christmas'),
(47, 166, 'CC', 'CCK', 'Cocos (Keeling) Islands', 'Îles Cocos (Keeling)'),
(48, 170, 'CO', 'COL', 'Colombia', 'Colombie'),
(49, 174, 'KM', 'COM', 'Comoros', 'Comores'),
(50, 175, 'YT', 'MYT', 'Mayotte', 'Mayotte'),
(51, 178, 'CG', 'COG', 'Republic of the Congo', 'République du Congo'),
(52, 180, 'CD', 'COD', 'The Democratic Republic Of The Congo', 'République Démocratique du Congo'),
(53, 184, 'CK', 'COK', 'Cook Islands', 'Îles Cook'),
(54, 188, 'CR', 'CRI', 'Costa Rica', 'Costa Rica'),
(55, 191, 'HR', 'HRV', 'Croatia', 'Croatie'),
(56, 192, 'CU', 'CUB', 'Cuba', 'Cuba'),
(57, 196, 'CY', 'CYP', 'Cyprus', 'Chypre'),
(58, 203, 'CZ', 'CZE', 'Czech Republic', 'République Tchèque'),
(59, 204, 'BJ', 'BEN', 'Benin', 'Bénin'),
(60, 208, 'DK', 'DNK', 'Denmark', 'Danemark'),
(61, 212, 'DM', 'DMA', 'Dominica', 'Dominique'),
(62, 214, 'DO', 'DOM', 'Dominican Republic', 'République Dominicaine'),
(63, 218, 'EC', 'ECU', 'Ecuador', 'Équateur'),
(64, 222, 'SV', 'SLV', 'El Salvador', 'El Salvador'),
(65, 226, 'GQ', 'GNQ', 'Equatorial Guinea', 'Guinée Équatoriale'),
(66, 231, 'ET', 'ETH', 'Ethiopia', 'Éthiopie'),
(67, 232, 'ER', 'ERI', 'Eritrea', 'Érythrée'),
(68, 233, 'EE', 'EST', 'Estonia', 'Estonie'),
(69, 234, 'FO', 'FRO', 'Faroe Islands', 'Îles Féroé'),
(70, 238, 'FK', 'FLK', 'Falkland Islands', 'Îles (malvinas) Falkland'),
(71, 239, 'GS', 'SGS', 'South Georgia and the South Sandwich Islands', 'Géorgie du Sud et les Îles Sandwich du Sud'),
(72, 242, 'FJ', 'FJI', 'Fiji', 'Fidji'),
(73, 246, 'FI', 'FIN', 'Finland', 'Finlande'),
(74, 248, 'AX', 'ALA', 'Åland Islands', 'Îles Åland'),
(75, 250, 'FR', 'FRA', 'France', 'France'),
(76, 254, 'GF', 'GUF', 'French Guiana', 'Guyane Française'),
(77, 258, 'PF', 'PYF', 'French Polynesia', 'Polynésie Française'),
(78, 260, 'TF', 'ATF', 'French Southern Territories', 'Terres Australes Françaises'),
(79, 262, 'DJ', 'DJI', 'Djibouti', 'Djibouti'),
(80, 266, 'GA', 'GAB', 'Gabon', 'Gabon'),
(81, 268, 'GE', 'GEO', 'Georgia', 'Géorgie'),
(82, 270, 'GM', 'GMB', 'Gambia', 'Gambie'),
(83, 275, 'PS', 'PSE', 'Occupied Palestinian Territory', 'Territoire Palestinien Occupé'),
(84, 276, 'DE', 'DEU', 'Germany', 'Allemagne'),
(85, 288, 'GH', 'GHA', 'Ghana', 'Ghana'),
(86, 292, 'GI', 'GIB', 'Gibraltar', 'Gibraltar'),
(87, 296, 'KI', 'KIR', 'Kiribati', 'Kiribati'),
(88, 300, 'GR', 'GRC', 'Greece', 'Grèce'),
(89, 304, 'GL', 'GRL', 'Greenland', 'Groenland'),
(90, 308, 'GD', 'GRD', 'Grenada', 'Grenade'),
(91, 312, 'GP', 'GLP', 'Guadeloupe', 'Guadeloupe'),
(92, 316, 'GU', 'GUM', 'Guam', 'Guam'),
(93, 320, 'GT', 'GTM', 'Guatemala', 'Guatemala'),
(94, 324, 'GN', 'GIN', 'Guinea', 'Guinée'),
(95, 328, 'GY', 'GUY', 'Guyana', 'Guyana'),
(96, 332, 'HT', 'HTI', 'Haiti', 'Haïti'),
(97, 334, 'HM', 'HMD', 'Heard Island and McDonald Islands', 'Îles Heard et Mcdonald'),
(98, 336, 'VA', 'VAT', 'Vatican City State', 'Saint-Siège (état de la Cité du Vatican)'),
(99, 340, 'HN', 'HND', 'Honduras', 'Honduras'),
(100, 344, 'HK', 'HKG', 'Hong Kong', 'Hong-Kong'),
(101, 348, 'HU', 'HUN', 'Hungary', 'Hongrie'),
(102, 352, 'IS', 'ISL', 'Iceland', 'Islande'),
(103, 356, 'IN', 'IND', 'India', 'Inde'),
(104, 360, 'ID', 'IDN', 'Indonesia', 'Indonésie'),
(105, 364, 'IR', 'IRN', 'Islamic Republic of Iran', 'République Islamique d\'Iran'),
(106, 368, 'IQ', 'IRQ', 'Iraq', 'Iraq'),
(107, 372, 'IE', 'IRL', 'Ireland', 'Irlande'),
(108, 376, 'IL', 'ISR', 'Israel', 'Israël'),
(109, 380, 'IT', 'ITA', 'Italy', 'Italie'),
(110, 384, 'CI', 'CIV', 'Côte d\'Ivoire', 'Côte d\'Ivoire'),
(111, 388, 'JM', 'JAM', 'Jamaica', 'Jamaïque'),
(112, 392, 'JP', 'JPN', 'Japan', 'Japon'),
(113, 398, 'KZ', 'KAZ', 'Kazakhstan', 'Kazakhstan'),
(114, 400, 'JO', 'JOR', 'Jordan', 'Jordanie'),
(115, 404, 'KE', 'KEN', 'Kenya', 'Kenya'),
(116, 408, 'KP', 'PRK', 'Democratic People\'s Republic of Korea', 'République Populaire Démocratique de Corée'),
(117, 410, 'KR', 'KOR', 'Republic of Korea', 'République de Corée'),
(118, 414, 'KW', 'KWT', 'Kuwait', 'Koweït'),
(119, 417, 'KG', 'KGZ', 'Kyrgyzstan', 'Kirghizistan'),
(120, 418, 'LA', 'LAO', 'Lao People\'s Democratic Republic', 'République Démocratique Populaire Lao'),
(121, 422, 'LB', 'LBN', 'Lebanon', 'Liban'),
(122, 426, 'LS', 'LSO', 'Lesotho', 'Lesotho'),
(123, 428, 'LV', 'LVA', 'Latvia', 'Lettonie'),
(124, 430, 'LR', 'LBR', 'Liberia', 'Libéria'),
(125, 434, 'LY', 'LBY', 'Libyan Arab Jamahiriya', 'Jamahiriya Arabe Libyenne'),
(126, 438, 'LI', 'LIE', 'Liechtenstein', 'Liechtenstein'),
(127, 440, 'LT', 'LTU', 'Lithuania', 'Lituanie'),
(128, 442, 'LU', 'LUX', 'Luxembourg', 'Luxembourg'),
(129, 446, 'MO', 'MAC', 'Macao', 'Macao'),
(130, 450, 'MG', 'MDG', 'Madagascar', 'Madagascar'),
(131, 454, 'MW', 'MWI', 'Malawi', 'Malawi'),
(132, 458, 'MY', 'MYS', 'Malaysia', 'Malaisie'),
(133, 462, 'MV', 'MDV', 'Maldives', 'Maldives'),
(134, 466, 'ML', 'MLI', 'Mali', 'Mali'),
(135, 470, 'MT', 'MLT', 'Malta', 'Malte'),
(136, 474, 'MQ', 'MTQ', 'Martinique', 'Martinique'),
(137, 478, 'MR', 'MRT', 'Mauritania', 'Mauritanie'),
(138, 480, 'MU', 'MUS', 'Mauritius', 'Maurice'),
(139, 484, 'MX', 'MEX', 'Mexico', 'Mexique'),
(140, 492, 'MC', 'MCO', 'Monaco', 'Monaco'),
(141, 496, 'MN', 'MNG', 'Mongolia', 'Mongolie'),
(142, 498, 'MD', 'MDA', 'Republic of Moldova', 'République de Moldova'),
(143, 500, 'MS', 'MSR', 'Montserrat', 'Montserrat'),
(144, 504, 'MA', 'MAR', 'Morocco', 'Maroc'),
(145, 508, 'MZ', 'MOZ', 'Mozambique', 'Mozambique'),
(146, 512, 'OM', 'OMN', 'Oman', 'Oman'),
(147, 516, 'NA', 'NAM', 'Namibia', 'Namibie'),
(148, 520, 'NR', 'NRU', 'Nauru', 'Nauru'),
(149, 524, 'NP', 'NPL', 'Nepal', 'Népal'),
(150, 528, 'NL', 'NLD', 'Netherlands', 'Pays-Bas'),
(151, 530, 'AN', 'ANT', 'Netherlands Antilles', 'Antilles Néerlandaises'),
(152, 533, 'AW', 'ABW', 'Aruba', 'Aruba'),
(153, 540, 'NC', 'NCL', 'New Caledonia', 'Nouvelle-Calédonie'),
(154, 548, 'VU', 'VUT', 'Vanuatu', 'Vanuatu'),
(155, 554, 'NZ', 'NZL', 'New Zealand', 'Nouvelle-Zélande'),
(156, 558, 'NI', 'NIC', 'Nicaragua', 'Nicaragua'),
(157, 562, 'NE', 'NER', 'Niger', 'Niger'),
(158, 566, 'NG', 'NGA', 'Nigeria', 'Nigéria'),
(159, 570, 'NU', 'NIU', 'Niue', 'Niué'),
(160, 574, 'NF', 'NFK', 'Norfolk Island', 'Île Norfolk'),
(161, 578, 'NO', 'NOR', 'Norway', 'Norvège'),
(162, 580, 'MP', 'MNP', 'Northern Mariana Islands', 'Îles Mariannes du Nord'),
(163, 581, 'UM', 'UMI', 'United States Minor Outlying Islands', 'Îles Mineures Éloignées des États-Unis'),
(164, 583, 'FM', 'FSM', 'Federated States of Micronesia', 'États Fédérés de Micronésie'),
(165, 584, 'MH', 'MHL', 'Marshall Islands', 'Îles Marshall'),
(166, 585, 'PW', 'PLW', 'Palau', 'Palaos'),
(167, 586, 'PK', 'PAK', 'Pakistan', 'Pakistan'),
(168, 591, 'PA', 'PAN', 'Panama', 'Panama'),
(169, 598, 'PG', 'PNG', 'Papua New Guinea', 'Papouasie-Nouvelle-Guinée'),
(170, 600, 'PY', 'PRY', 'Paraguay', 'Paraguay'),
(171, 604, 'PE', 'PER', 'Peru', 'Pérou'),
(172, 608, 'PH', 'PHL', 'Philippines', 'Philippines'),
(173, 612, 'PN', 'PCN', 'Pitcairn', 'Pitcairn'),
(174, 616, 'PL', 'POL', 'Poland', 'Pologne'),
(175, 620, 'PT', 'PRT', 'Portugal', 'Portugal'),
(176, 624, 'GW', 'GNB', 'Guinea-Bissau', 'Guinée-Bissau'),
(177, 626, 'TL', 'TLS', 'Timor-Leste', 'Timor-Leste'),
(178, 630, 'PR', 'PRI', 'Puerto Rico', 'Porto Rico'),
(179, 634, 'QA', 'QAT', 'Qatar', 'Qatar'),
(180, 638, 'RE', 'REU', 'Réunion', 'Réunion'),
(181, 642, 'RO', 'ROU', 'Romania', 'Roumanie'),
(182, 643, 'RU', 'RUS', 'Russian Federation', 'Fédération de Russie'),
(183, 646, 'RW', 'RWA', 'Rwanda', 'Rwanda'),
(184, 654, 'SH', 'SHN', 'Saint Helena', 'Sainte-Hélène'),
(185, 659, 'KN', 'KNA', 'Saint Kitts and Nevis', 'Saint-Kitts-et-Nevis'),
(186, 660, 'AI', 'AIA', 'Anguilla', 'Anguilla'),
(187, 662, 'LC', 'LCA', 'Saint Lucia', 'Sainte-Lucie'),
(188, 666, 'PM', 'SPM', 'Saint-Pierre and Miquelon', 'Saint-Pierre-et-Miquelon'),
(189, 670, 'VC', 'VCT', 'Saint Vincent and the Grenadines', 'Saint-Vincent-et-les Grenadines'),
(190, 674, 'SM', 'SMR', 'San Marino', 'Saint-Marin'),
(191, 678, 'ST', 'STP', 'Sao Tome and Principe', 'Sao Tomé-et-Principe'),
(192, 682, 'SA', 'SAU', 'Saudi Arabia', 'Arabie Saoudite'),
(193, 686, 'SN', 'SEN', 'Senegal', 'Sénégal'),
(194, 690, 'SC', 'SYC', 'Seychelles', 'Seychelles'),
(195, 694, 'SL', 'SLE', 'Sierra Leone', 'Sierra Leone'),
(196, 702, 'SG', 'SGP', 'Singapore', 'Singapour'),
(197, 703, 'SK', 'SVK', 'Slovakia', 'Slovaquie'),
(198, 704, 'VN', 'VNM', 'Vietnam', 'Viet Nam'),
(199, 705, 'SI', 'SVN', 'Slovenia', 'Slovénie'),
(200, 706, 'SO', 'SOM', 'Somalia', 'Somalie'),
(201, 710, 'ZA', 'ZAF', 'South Africa', 'Afrique du Sud'),
(202, 716, 'ZW', 'ZWE', 'Zimbabwe', 'Zimbabwe'),
(203, 724, 'ES', 'ESP', 'Spain', 'Espagne'),
(204, 732, 'EH', 'ESH', 'Western Sahara', 'Sahara Occidental'),
(205, 736, 'SD', 'SDN', 'Sudan', 'Soudan'),
(206, 740, 'SR', 'SUR', 'Suriname', 'Suriname'),
(207, 744, 'SJ', 'SJM', 'Svalbard and Jan Mayen', 'Svalbard etÎle Jan Mayen'),
(208, 748, 'SZ', 'SWZ', 'Swaziland', 'Swaziland'),
(209, 752, 'SE', 'SWE', 'Sweden', 'Suède'),
(210, 756, 'CH', 'CHE', 'Switzerland', 'Suisse'),
(211, 760, 'SY', 'SYR', 'Syrian Arab Republic', 'République Arabe Syrienne'),
(212, 762, 'TJ', 'TJK', 'Tajikistan', 'Tadjikistan'),
(213, 764, 'TH', 'THA', 'Thailand', 'Thaïlande'),
(214, 768, 'TG', 'TGO', 'Togo', 'Togo'),
(215, 772, 'TK', 'TKL', 'Tokelau', 'Tokelau'),
(216, 776, 'TO', 'TON', 'Tonga', 'Tonga'),
(217, 780, 'TT', 'TTO', 'Trinidad and Tobago', 'Trinité-et-Tobago'),
(218, 784, 'AE', 'ARE', 'United Arab Emirates', 'Émirats Arabes Unis'),
(219, 788, 'TN', 'TUN', 'Tunisia', 'Tunisie'),
(220, 792, 'TR', 'TUR', 'Turkey', 'Turquie'),
(221, 795, 'TM', 'TKM', 'Turkmenistan', 'Turkménistan'),
(222, 796, 'TC', 'TCA', 'Turks and Caicos Islands', 'Îles Turks et Caïques'),
(223, 798, 'TV', 'TUV', 'Tuvalu', 'Tuvalu'),
(224, 800, 'UG', 'UGA', 'Uganda', 'Ouganda'),
(225, 804, 'UA', 'UKR', 'Ukraine', 'Ukraine'),
(226, 807, 'MK', 'MKD', 'The Former Yugoslav Republic of Macedonia', 'L\'ex-République Yougoslave de Macédoine'),
(227, 818, 'EG', 'EGY', 'Egypt', 'Égypte'),
(228, 826, 'GB', 'GBR', 'United Kingdom', 'Royaume-Uni'),
(229, 833, 'IM', 'IMN', 'Isle of Man', 'Île de Man'),
(230, 834, 'TZ', 'TZA', 'United Republic Of Tanzania', 'République-Unie de Tanzanie'),
(231, 840, 'US', 'USA', 'United States', 'États-Unis'),
(232, 850, 'VI', 'VIR', 'U.S. Virgin Islands', 'Îles Vierges des États-Unis'),
(233, 854, 'BF', 'BFA', 'Burkina Faso', 'Burkina Faso'),
(234, 858, 'UY', 'URY', 'Uruguay', 'Uruguay'),
(235, 860, 'UZ', 'UZB', 'Uzbekistan', 'Ouzbékistan'),
(236, 862, 'VE', 'VEN', 'Venezuela', 'Venezuela'),
(237, 876, 'WF', 'WLF', 'Wallis and Futuna', 'Wallis et Futuna'),
(238, 882, 'WS', 'WSM', 'Samoa', 'Samoa'),
(239, 887, 'YE', 'YEM', 'Yemen', 'Yémen'),
(240, 891, 'CS', 'SCG', 'Serbia and Montenegro', 'Serbie-et-Monténégro'),
(241, 894, 'ZM', 'ZMB', 'Zambia', 'Zambie');

-- --------------------------------------------------------

--
-- Structure de la table `proformas`
--

DROP TABLE IF EXISTS `proformas`;
CREATE TABLE IF NOT EXISTS `proformas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `DateCreation` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `DateValid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `EmisPar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Client` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `projet` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `DelaiLivraison` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `PortDest` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Engins` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Options` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `monnaie` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `proformas`
--

INSERT INTO `proformas` (`id`, `code`, `DateCreation`, `DateValid`, `EmisPar`, `Client`, `projet`, `DelaiLivraison`, `PortDest`, `Engins`, `Options`, `monnaie`) VALUES
(1, '01150920', '03/03/2020', '100j', 'Azeddine@integral.fr', 'TE25E', 'T8456', '100j', 'roterdam', '(AC140/Postes Premium/Prime/100/Moscow/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capcité de filtration de//3)', '0/0/1/0/0/3//', ''),
(10, '', '04/03/2020', '100j', 'Azeddine@integral.fr', 'AR14F', '', '100j', 'roterdam', '(AC140/Postes Premium/Prime/100/Moscow/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//10)', '0/0/1/0/0/3//', ''),
(11, '01100920', '20/04/2020', '100j', 'Azeddine@integral.fr', 'TE25E', 'T8456', '100j', 'roterdam', '(AC140/Postes Premium/Prime/100/Moscow/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//2)', '0/0/1/0/0/3//', ''),
(12, '01150945', '20/04/2020', '100j', 'Azeddine@integral.fr', 'TE25E', 'T8456', '100j', 'roterdam', '(AC140/Postes Premium/Prime/100/Moscow/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//2)', '0/0/1/0/0/3//', ''),
(13, '', '20/04/2020', '100j', 'Azeddine@integral.fr', 'TE25E', '', '100j', 'roterdam', '(AC140/Postes Premium/Prime/100/Moscow/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//3)', '0/0/1/0/0/3//', ''),
(14, '', '03/05/2020', '100j', 'Azeddine@integral.fr', 'AR14F', '', '100j', 'roterdam', '(AC140/Postes Premium/Prime/100/Moscow/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//3)', '0/1/0/1/3/0//', ''),
(15, '', '25/09/2020', '100j', 'Azeddine@integral.fr', 'TE25E', 'P85OE', '100j', 'Alger', '(AC140/Postes Premium/Prime/100/1/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//2)', '(0/2/0/1/1/0//)', 'EURO'),
(16, '', '25/09/2020', '', 'Azeddine@integral.fr', 'TE25E', 'T8456', '', '', '(AC140/Postes Premium/Prime/100/1/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//2)', '(0/2/0/1/1/0//)', ''),
(17, '', '25/09/2020', '2020-09-24', 'Azeddine@integral.fr', 'AR14F', 'T8456', '8', 'Oran', '(AC140/Postes Premium/Prime/100/1/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//3)', '(2/0/0/1/0/0//)', 'EURO'),
(18, '01170920', '25/09/2020', '2020-09-24', 'Azeddine@integral.fr', 'AR14F', 'T8456', '8', 'Oran', '(AC140/Postes Premium/Prime/100/1/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//3)', '(0/2/1/0/0/0//)', 'EURO'),
(19, '01191020', '16/10/2020', '2020-10-04', 'Azeddine@integral.fr', 'TE25E', 'T8456', '9', 'Oran', '(AC140/Postes Premium/Prime/100/1/Poste d\'enrobage Continu Ultra Mobile\r\nCapacité de Production : 140 T/heure à 3% d\'humidité\r\nInstallation Mobile sur Châssis composée de :\r\n3 Prédoseurs d\'une capacité chacune de 9 m3\r\nTambour sécheur Diamètre 1,8 m et Longueur 8,00 m\r\nBrûleur d\'uencapciteé de\r\nMalaxeur d\'une capcité de 3 Tonnes\r\nFiltre à Manches d\'une capacité de filtration de//3)', '(1/0/1/0/0/0//)', 'EURO');

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

DROP TABLE IF EXISTS `projets`;
CREATE TABLE IF NOT EXISTS `projets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `bft` int(11) NOT NULL,
  `dateCreation` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `etat` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id`, `nom`, `code`, `client`, `bft`, `dateCreation`, `description`, `etat`) VALUES
(1, 'TALBI POSTE', 'T8456', 'TE25E', 131, '10/09/2020', 'pourquoi pas\r\n', 0),
(2, 'TEST', 'P85OE', 'CG97A', 132, '23/09/2020', 'dlabzbdagjvbzddjbvazjdbvadljbvzadjdlaznbdaz', 1),
(4, 'FATHI GARAGE', 'FJ45P', 'TE25E', 321, '23/09/2020', 'le rappeler avant lundi', 0);

-- --------------------------------------------------------

--
-- Structure de la table `rapports`
--

DROP TABLE IF EXISTS `rapports`;
CREATE TABLE IF NOT EXISTS `rapports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` int(255) NOT NULL,
  `projet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `commandes` text NOT NULL,
  `visitesClient` text NOT NULL,
  `offres` text NOT NULL,
  `remarques` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `rapports`
--

INSERT INTO `rapports` (`id`, `num`, `projet`, `commandes`, `visitesClient`, `offres`, `remarques`) VALUES
(1, 1, 'T8456', 'yfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycds0', 'yfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycds1', 'yfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsfiljdieqldq2', 'kdlsnyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycdsyfqgudygfydvcvcsdyvudsycds3');

-- --------------------------------------------------------

--
-- Structure de la table `wilaya`
--

DROP TABLE IF EXISTS `wilaya`;
CREATE TABLE IF NOT EXISTS `wilaya` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `wilaya`
--

INSERT INTO `wilaya` (`id`, `nom`) VALUES
(1, 'ADRAR'),
(2, 'CHLEF'),
(3, 'LAGHOUAT'),
(4, 'OUM EL BOUAGHI'),
(5, 'BATNA'),
(6, 'BEJAIA'),
(7, 'BISKRA'),
(8, 'BECHAR'),
(9, 'BLIDA'),
(10, 'BOUIRA'),
(11, 'TAMENRASSET'),
(12, 'TEBESSA'),
(13, 'TLEMCEN'),
(14, 'TIARET'),
(15, 'TIZI OUZOU'),
(16, 'ALGER'),
(17, 'DJELFA'),
(18, 'JIJEL'),
(19, 'SETIF'),
(20, 'SAIDA'),
(21, 'SKIKDA'),
(22, 'SIDI BEL ABBES'),
(23, 'ANNABA'),
(24, 'GUELMA'),
(25, 'CONSTANTINE'),
(26, 'MEDEA'),
(27, 'MOSTAGANEM'),
(28, 'M\'SILA'),
(29, 'MASCARA'),
(30, 'OUARGLA'),
(31, 'ORAN'),
(32, 'EL BAYED'),
(33, 'ILLIZI'),
(34, 'BORDJ BOU ARRERIDJ'),
(35, 'BOUMERDES'),
(36, 'EL TAREF'),
(37, 'TINDOUF'),
(38, 'TISSEMSILT'),
(39, 'EL OUED'),
(40, 'KHENCHELA'),
(41, 'SOUK AHRAS'),
(42, 'TIPAZA'),
(43, 'MILA'),
(44, 'AIN DEFLA'),
(45, 'NAAMA'),
(46, 'AIN TIMOUCHENT'),
(47, 'GHARDAIA'),
(48, 'RELIZANE');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
