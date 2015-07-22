<?
session_start();
include_once("../../../func/config.func.php");
include_once("../../../func/genquiz.func.php");
$debug = false;
if(isset($_SESSION["is_admin"])){
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	$resultObject = array();
	if(isset($_GET['uuid'])){
		$uuid = $_GET['uuid'];
	} else {
		exit();
	}
	if($stmt = $mysqli->prepare("SELECT `quizzes`.`uuid` FROM `quizzes` WHERE `quizzes`.`uuid` = ? AND `quizzes`.`owner` = ?;")){
		$stmt->bind_param("ss", $uuid, $_SESSION['dbext']);
		$stmt->execute();
		$stmt->store_result();
		$stmt->fetch();
		if($stmt->num_rows != 1){
			die("ERROR");
		}
		$stmt->close();
	} else {
		die($mysqli->error);
	}
	if($stmt = $mysqli->prepare("SELECT `" . $uuid . "`.`id`, `" . $uuid . "`.`object` FROM `" . $uuid . "` ORDER BY `" . $uuid . "`.`id` ASC;")){
		$stmt->bind_param("s", $_SESSION['dbext']);
		$stmt->execute();
		$stmt->bind_result($id, $object);
		$stmt->store_result();
		while($stmt->fetch()){
			$thisQuestion = array();
			$thisQuestion['id'] = $id;
			$thisObject = unserialize($object);
			
			//echo "<pre>".var_dump($thisObject)."</pre>";
			//exit();
			
			//$thisQuestion['object'] = $thisObject;
			$thisQuestion['questiontype'] = $thisObject->type;
			$thisQuestion['questiontext'] = substr($thisObject->question, 0, 40);
			$thisQuestion['questiontextfull'] = $thisObject->question;
			$thisQuestion['answerArray'] = $thisObject->answerArray;
			$thisQuestion['rawObject'] = $thisObject;
			//$test = array_merge($thisQuestion, array($thisObject));
			//echo "<pre>".json_encode($test, JSON_PRETTY_PRINT)."</pre>";
			//array_push($thisQuestion, $thisObject);
			//$thisQuestion['objectjson'] = json_encode($object);
			array_push($resultObject, $thisQuestion);
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