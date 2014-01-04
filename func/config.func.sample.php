<?
date_default_timezone_set('America/Los_Angeles');
$db_host = "localhost";
$db_user = "username";
$db_password = "password";
$db_name = "PolyQuiz";
$db_array = array(
		"db_host" => $db_host,
		"db_user" => $db_user,
		"db_password" => $db_password,
		"db_name" => $db_name);
$alphabet = array(
		0 => "A",
		1 => "B",
		2 => "C",
		3 => "D",
		4 => "E",
		5 => "F",
		6 => "G",
		7 => "H",
		8 => "I",
		9 => "J",
		10 => "K",
		11 => "L",
		12 => "M",
		13 => "N",
		14 => "O",
		15 => "P",
		16 => "Q",
		17 => "R",
		18 => "S",
		19 => "T",
		20 => "U",
		21 => "V",
		22 => "W",
		23 => "X",
		24 => "Y",
		25 => "Z");
define("MULTIPLE", 0);
define("FREERESPONSE", 1);
define("MATCHING", 2);
define("SURVEY", 3);
?>