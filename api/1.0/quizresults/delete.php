<?
session_start();
include_once("../../../func/config.func.php");
if(!isset($_SESSION["is_admin"])){
	
} else {
	$mysqli = new mysqli($db_host, $db_user, $db_password);
	$mysqli -> select_db($db_name);
	if(isset($_GET['uuid'])){
		if($_GET['uuid']=="all"){
			if($stmt = $mysqli->prepare("DELETE FROM `results` WHERE owner = ?;")) {
				$stmt->bind_param("s", $_SESSION['dbext']);
				$stmt->execute();
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
		} else if(!is_int($uuid) && isset($_GET['house'])){
			if($stmt = $mysqli->prepare("DELETE FROM `results` WHERE owner = ? AND quizuuid=? AND house=?;")) {
				$stmt->bind_param("sss", $_SESSION['dbext'], $_GET['uuid'], $_GET['house']);
				$stmt->execute();
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
		} else {
			if($stmt = $mysqli->prepare("DELETE FROM `results` WHERE owner = ? AND id=?;")) {
				$stmt->bind_param("ss", $_SESSION['dbext'], $_GET['uuid']);
				$stmt->execute();
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
		}
		$mysqli->close();
	} else if(isset($_GET['session'])){
		if($stmt = $mysqli->prepare("DELETE FROM `results` WHERE owner = ? AND session=?;")) {
				$stmt->bind_param("ss", $_SESSION['dbext'], $_GET['session']);
				$stmt->execute();
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
		$mysqli->close();
	} else if(isset($_POST['uuid'])) {
		if(isset($_POST['house'])){
			if($stmt = $mysqli->prepare("DELETE FROM `results` WHERE owner = ? AND quizuuid=? AND house=?;")) {
				$stmt->bind_param("sss", $_SESSION['dbext'], $_POST['uuid'], $_POST['house']);
				$stmt->execute();
				$stmt->close();
			} else {
				echo $mysqli->error;
			}
			$mysqli->close();
		}
	}
	$mysqli->close();
}
echo '{ "status": true }';
?>