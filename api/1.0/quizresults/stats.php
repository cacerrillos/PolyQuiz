<?
session_start();
include_once("../../../func/config.func.php");
$debug = false;
if(isset($_SESSION["is_admin"])){
	if(isset($_GET['uuid'])){
		$uuid = $_GET['uuid'];
	} else {
		exit();
	}
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	$resultObject = array();
	if(isset($_GET['house'])){
		$house = $_GET['house'];
		
		
	} else {
		if($stmt = $mysqli->prepare(
			"SELECT COUNT(`results`.`timestamp`), `results`.`timestamp`, `results`.`house` FROM `results` WHERE `results`.`owner` = ? AND `results`.`quizuuid` = ? ORDER BY `results`.`timestamp` DESC LIMIT 1;")){
			$stmt->bind_param("ss", $_SESSION['dbext'], $uuid);
			$stmt->execute();
			$stmt->bind_result($count, $latest, $quizsubject);
			$stmt->store_result();
			while($stmt->fetch()){
				$resultObject['count'] = $count;
				if($count != 0) {
					$resultObject['latest'] = $latest;
				} else {
					$resultObject['latest'] = 0;
				}
				
			}
			$stmt->close();
		} else {
			echo $mysqli->error;
		}
	}
	
	if($debug) {
		echo "<pre>".json_encode($resultObject, JSON_PRETTY_PRINT)."</pre>";
	} else {
		echo json_encode($resultObject);
	}
	exit();
}

?>