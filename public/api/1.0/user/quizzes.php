<?
session_start();
include_once("../../../func/config.func.php");
$debug = false;
if(isset($_SESSION["is_admin"])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	$resultObject = array();
	if($stmt = $mysqli->prepare(
		"SELECT `quizzes`.`uuid` FROM `quizzes` WHERE `owner`=?;")){
		$stmt->bind_param("s", $_SESSION['dbext']);
		$stmt->execute();
		$stmt->bind_result($quizuuid);
		$stmt->store_result();
		while($stmt->fetch()){
			array_push($resultObject, $quizuuid);
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