SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `quizzes` (
  `uuid` int(11) NOT NULL,
  `quizname` varchar(255) NOT NULL,
  `version` int(11) NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `quizzes_questions` (
  `uuid` varchar(255) NOT NULL,
  `quiz` int(11) NOT NULL,
  `data` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `data` mediumtext NOT NULL,
  `quiz` int(11) NOT NULL,
  `session` int(11) NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `results_responses` (
  `id` bigint(20) unsigned NOT NULL,
  `resultid` int(11) NOT NULL,
  `questionuuid` varchar(64) NOT NULL,
  `data` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `sessions` (
  `uuid` int(11) NOT NULL,
  `sessionid` varchar(32) NOT NULL,
  `sessionkey` varchar(32) NOT NULL,
  `quiz` int(11) NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`uuid`);

ALTER TABLE `quizzes_questions`
  ADD PRIMARY KEY (`uuid`,`quiz`),
  ADD KEY `quiz` (`quiz`);

ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `results_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questionuuid` (`questionuuid`),
  ADD KEY `resultid` (`resultid`);

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`uuid`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);


ALTER TABLE `quizzes`
  MODIFY `uuid` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `results_responses`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `sessions`
  MODIFY `uuid` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `quizzes_questions`
  ADD CONSTRAINT `quizzes_questions_ibfk_1` FOREIGN KEY (`quiz`) REFERENCES `quizzes` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `results_responses`
  ADD CONSTRAINT `results_responses_ibfk_2` FOREIGN KEY (`questionuuid`) REFERENCES `quizzes_questions` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `results_responses_ibfk_1` FOREIGN KEY (`resultid`) REFERENCES `results` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
