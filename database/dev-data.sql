SET FOREIGN_KEY_CHECKS=0;

INSERT INTO `houses` (`houseid`, `housename`, `owner`) VALUES
(0, 'Other', -1337),
(1, 'North', -1337),
(2, 'South', -1337),
(3, 'East', -1337),
(4, 'West', -1337),
(5, 'dfghjkl', -1337);

INSERT INTO `quizzes` (`uuid`, `quizname`, `version`, `owner`) VALUES
(1, 'My Quiz', 0, -1337),
(2, 'AAA', 0, -1337),
(3, 'xxx', 0, -1337),
(4, 'bbbb', 0, -1337);

INSERT INTO `quizzes_questions` (`uuid`, `quiz`, `data`, `owner`) VALUES
('PolyQuestion_55bbdd0282fe7', 1, '{"uuid":"PolyQuestion_55bbe4338fa98","type":"Standard","version":0,"data":{"answers":[{"uuid":"PolyAnswer_55bbe4338f978","type":"Standard","version":1,"data":{"answerText":"2","points":0}},{"uuid":"PolyAnswer_55bbe4338f9c1","type":"Standard","version":1,"data":{"answerText":"4","points":1}},{"uuid":"PolyAnswer_55bbe4338fa08","type":"Standard","version":1,"data":{"answerText":"6","points":0}},{"uuid":"PolyAnswer_55bbe4338fa4f","type":"Standard","version":1,"data":{"answerText":"8","points":0}}],"question":"<p>What is 2+2?</p>"}}', -1337),
('PolyQuestion_55bbe4587102a', 1, '{"uuid":"PolyQuestion_55bbe4587102a","type":"Matching","version":0,"data":{"answers":[{"uuid":"PolyAnswer_55bbe45871075","type":"Matching","version":1,"data":{"left":"AA","right":"AA","points":1}},{"uuid":"PolyAnswer_55bbe458710c2","type":"Matching","version":1,"data":{"left":"bb","right":"bb","points":1}},{"uuid":"PolyAnswer_55bbe45871110","type":"Matching","version":1,"data":{"left":"cc","right":"cc","points":1}},{"uuid":"PolyAnswer_55bbe4587115e","type":"Matching","version":1,"data":{"left":"dd","right":"dd","points":1}}],"question":"Match the following"}}', -1337);

INSERT INTO `results` (`id`, `timestamp`, `data`, `quiz`, `session`, `owner`) VALUES
(1, 2015, 'asdasdasddad', 1, 'aaaa', -1337),
(2, 0, '', 1, 'aaaa', -1337),
(3, 11111, '', 1, 'aaaa', -1337);

INSERT INTO `results_responses` (`id`, `resultid`, `questionuuid`, `data`, `owner`) VALUES
(1, 1, 'PolyQuestion_55bbdd0282fe7', 'qwrdq21313', -1337);

INSERT INTO `sessions` (`sessionid`, `sessionkey`, `quiz`, `house`, `name`, `date`, `active`, `show_scores`, `owner`) VALUES
('0d1537', 'ef', 1, 4, 'qwdqwd32r3r23', 1445818125, 1, 0, -1337),
('46e655', '03', 2, 1, 'My Second Session', 1445835078, 0, 1, -1337),
('4d0342', 'b5', 3, 5, 'My Sesszzz', 1445833037, 0, 1, -1337),
('aaaa', 'aa', 4, 3, 'My First Session', 1439010222, 1, 0, -1337),
('b8ec77', '1b', 1, 0, 'asdasddddddd', 1446001592, 0, 1, -1337);

INSERT INTO `users` (`id`, `email`, `name`, `password`) VALUES
(-1337, 'cacerrillos@gmail.com', 'Caboolean', 'f7fa564375eec84eb35cddeba7617c57'),
(1, 'Estrada', '', 'ee0e6c4c20f0bca255bd3590b4e2db90'),
(2, 'creation', '', 'bd20dc4ea8ec4cdf9768642f04f7b3c3'),
(3, 'msmstg01', '', '8f0faa31796f8d347f3652753bdfef38'),
(8, 'MissP', '', 'e79581ee1998185d7cb41ab84352b97e'),
(9, 'heeeee', '', '0f88c5b7ec8e8bc1a2d74fcd316c991a'),
(10, 'hee', '', '81dc9bdb52d04dc20036dbd8313ed055'),
(11, 'dhedman', '', 'c50aacae8c9de6b2f424caebad1a06b8'),
(12, 'pellegrini', '', 'e79581ee1998185d7cb41ab84352b97e');
SET FOREIGN_KEY_CHECKS=1;
