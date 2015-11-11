
DROP TABLE IF EXISTS `houses`;
CREATE TABLE `houses` (
  `houseid` int(10) UNSIGNED NOT NULL,
  `housename` varchar(255) NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE `quizzes` (
  `uuid` int(11) NOT NULL,
  `quizname` varchar(255) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '0',
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `quizzes_questions`;
CREATE TABLE `quizzes_questions` (
  `uuid` varchar(255) NOT NULL,
  `quiz` int(11) NOT NULL,
  `data` mediumtext NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `results`;
CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `data` mediumtext NOT NULL,
  `quiz` int(11) NOT NULL,
  `session` varchar(32) NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `results_responses`;
CREATE TABLE `results_responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resultid` int(11) NOT NULL,
  `questionuuid` varchar(64) NOT NULL,
  `data` mediumtext NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `sessionid` varchar(32) NOT NULL,
  `sessionkey` varchar(32) NOT NULL,
  `quiz` int(11) NOT NULL,
  `house` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'DEFAULT NAME',
  `date` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  `show_scores` tinyint(1) DEFAULT '1',
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `houses`
  ADD PRIMARY KEY (`houseid`),
  ADD UNIQUE KEY `unique_houses` (`owner`,`housename`) USING BTREE;

ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `owner` (`owner`);

ALTER TABLE `quizzes_questions`
  ADD PRIMARY KEY (`uuid`,`quiz`),
  ADD KEY `quiz` (`quiz`),
  ADD KEY `owner` (`owner`);

ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner` (`owner`),
  ADD KEY `quiz` (`quiz`),
  ADD KEY `session` (`session`);

ALTER TABLE `results_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questionuuid` (`questionuuid`),
  ADD KEY `resultid` (`resultid`),
  ADD KEY `owner` (`owner`);

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`sessionid`),
  ADD KEY `house` (`house`),
  ADD KEY `quiz` (`quiz`),
  ADD KEY `owner` (`owner`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`email`);


ALTER TABLE `houses`
  MODIFY `houseid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `quizzes`
  MODIFY `uuid` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `results_responses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `houses`
  ADD CONSTRAINT `houses_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `quizzes_questions`
  ADD CONSTRAINT `quizzes_questions_ibfk_1` FOREIGN KEY (`quiz`) REFERENCES `quizzes` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quizzes_questions_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz`) REFERENCES `quizzes` (`uuid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `results_ibfk_3` FOREIGN KEY (`session`) REFERENCES `sessions` (`sessionid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `results_ibfk_4` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

ALTER TABLE `results_responses`
  ADD CONSTRAINT `results_responses_ibfk_1` FOREIGN KEY (`resultid`) REFERENCES `results` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `results_responses_ibfk_2` FOREIGN KEY (`questionuuid`) REFERENCES `quizzes_questions` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `results_responses_ibfk_3` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`quiz`) REFERENCES `quizzes` (`uuid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_3` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_4` FOREIGN KEY (`house`) REFERENCES `houses` (`houseid`) ON UPDATE CASCADE;
