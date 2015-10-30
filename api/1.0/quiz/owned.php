<?
session_start();
include_once("../../../func/config.func.php");
include_once("../objects/polyquiz.php");
$debug = false;
if(isset($_SESSION["is_admin"])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	echo json_encode(PolyQuiz::AllOwned($mysqli, $_SESSION['dbext']));
	exit();
	$resultObject = array();
	if($stmt = $mysqli->prepare(
		"SELECT `quizzes`.`uuid`, `quizzes`.`quizname`, `quizzes`.`quizsubject`, `quizzes`.`status`, COUNT(`results`.`timestamp`) FROM `quizzes` LEFT JOIN `results` ON `quizzes`.`uuid` = `results`.`quizuuid` WHERE `quizzes`.`owner` = ? GROUP BY `quizzes`.`quizname`"
	)){
		$stmt->bind_param("s", $_SESSION['dbext']);
		$stmt->execute();
		$stmt->bind_result($quizuuid, $quizname, $quizsubject, $quizstatus, $totalcount);
		$stmt->store_result();
		while($stmt->fetch()){
			$thisQuiz = array();
			$thisQuiz['uuid'] = $quizuuid;
			$thisQuiz['name'] = $quizname;
			$thisQuiz['subject'] = $quizsubject;
			$thisQuiz['status'] = $quizstatus;
			$thisQuiz['totalcount'] = $totalcount;
			$thisQuiz['totaltime'] = $timestamp;
			$thisQuiz['totalhouse'] = $house;
			array_push($resultObject, $thisQuiz);
		}
		$stmt->close();
	} else {
		echo $mysqli->error;
	}
	if($debug) {
		echo "<pre>".json_encode($resultObject, JSON_PRETTY_PRINT)."</pre>";
	} else {
		echo json_encode($resultObject);
	}
	exit();
}

?>