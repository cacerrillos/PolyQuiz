SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `globalsession` (
  `uuid` varchar(64) NOT NULL,
  `database` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`uuid`),
  KEY `database_index` (`database`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `images` (
  `uuid` varchar(65) NOT NULL,
  `url` text,
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `polysessions` (
  `id` varchar(65) NOT NULL,
  `recoveryid` varchar(16) NOT NULL,
  `recoverykey` varchar(16) NOT NULL,
  `data` longtext NOT NULL,
  `date` varchar(16) DEFAULT NULL,
  `owner` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_index` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `quizobjects` (
  `id` int(11) NOT NULL,
  `object` longtext,
  `owner` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_index` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `quizzes` (
  `uuid` varchar(255) NOT NULL,
  `quizname` text NOT NULL,
  `quizsubject` text,
  `status` int(11) DEFAULT '1',
  `owner` varchar(64) NOT NULL,
  PRIMARY KEY (`uuid`),
  KEY `owner_index` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` text,
  `lastname` text,
  `quizuuid` varchar(64) DEFAULT NULL,
  `rawscore` float DEFAULT NULL,
  `possiblescore` float DEFAULT NULL,
  `percentage` float DEFAULT NULL,
  `datestamp` varchar(16) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `house` enum('North','South','East','West','Other') DEFAULT NULL,
  `session` varchar(16) DEFAULT NULL,
  `flag` enum('yes','no') DEFAULT 'no',
  `frscore` float DEFAULT '0',
  `frpossible` float DEFAULT '0',
  `frgraded` float DEFAULT '1',
  `owner` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quizuuid_index` (`quizuuid`),
  KEY `owner_index` (`owner`),
  KEY `session_index` (`session`),
  KEY `house_index` (`house`),
  KEY `flag_index` (`flag`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4530 ;

CREATE TABLE IF NOT EXISTS `sessions` (
  `uuid` varchar(16) NOT NULL,
  `key` varchar(16) DEFAULT NULL,
  `house` enum('North','South','East','West','Other') DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `quiz` varchar(64) DEFAULT NULL,
  `date` varchar(16) DEFAULT NULL,
  `sessionname` text,
  `owner` varchar(64) DEFAULT NULL,
  `score` int(11) DEFAULT '1',
  PRIMARY KEY (`uuid`),
  KEY `owner_index` (`owner`),
  KEY `quiz_index` (`quiz`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `stats` (
  `id` varchar(64) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `permissionsid` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `permissionsid` (`permissionsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;


ALTER TABLE `globalsession`
  ADD CONSTRAINT `globalsession_ibfk_1` FOREIGN KEY (`database`) REFERENCES `users` (`permissionsid`) ON UPDATE CASCADE;

ALTER TABLE `polysessions`
  ADD CONSTRAINT `polysessions_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`permissionsid`) ON UPDATE CASCADE;

ALTER TABLE `quizobjects`
  ADD CONSTRAINT `quizobjects_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`permissionsid`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`permissionsid`) ON UPDATE CASCADE;

ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`permissionsid`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`permissionsid`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
